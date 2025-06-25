<?php
require_once 'config/database.php';
require_once 'vendor/autoload.php'; // Make sure Composer's autoload is included

class PaymentsController
{
    public function pay()
    {
        $rental_id = $_GET['rental_id'] ?? null;
        if (!$rental_id) {
            require 'views/404.php';
            exit;
        }

        $db = new Database();
        $conn = $db->connect();

        $stmt = $conn->prepare("SELECT * FROM rentals WHERE rental_id = ?");
        $stmt->bind_param("i", $rental_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $rental = $result->fetch_assoc();

        if (!$rental) {
            require 'views/404.php';
            exit;
        }

        // Pass rental info to the payment view
        require 'views/rentals/pay.php';
    }

    public function process()
    {
        $paymentMethod = $_POST['payment_method'] ?? 'stripe';
        $rental_id = $_POST['rental_id'] ?? null;
        $db = new Database();
        $conn = $db->connect();

        // Fetch rental info
        $stmt = $conn->prepare("SELECT * FROM rentals WHERE rental_id = ?");
        $stmt->bind_param("i", $rental_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $rental = $result->fetch_assoc();

        if (!$rental) {
            require 'views/404.php';
            exit;
        }

        // Fetch admin user
        $adminResult = $conn->query("SELECT * FROM users WHERE role = 'admin'");
        $adminUser = $adminResult ? $adminResult->fetch_assoc() : null;
        // Fetch user info (assume user_id is in session)
        $user_id = $_SESSION['user_id'] ?? null;
        $user = null;
        if ($user_id) {
            $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
        }

        if ($paymentMethod === 'stripe') {
            \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']); // Replace with your Stripe secret key
            $token = $_POST['stripeToken'] ?? null;
            if (!$token || !$rental_id) {
                $error = 'Missing payment token or rental ID.';
                require 'views/rentals/fail.php';
                exit;
            }
            try {
                $charge = \Stripe\Charge::create([
                    'amount' => intval($rental['total_cost'] * 100), // amount in cents
                    'currency' => 'rwf', // Use RWF for Rwanda Francs
                    'description' => 'Car Rental Payment',
                    'source' => $token,
                ]);

                // Save payment info to DB
                $stmt = $conn->prepare("INSERT INTO payments (rental_id, amount, payment_method, transaction_id, status, payment_date) VALUES (?, ?, ?, ?, ?, NOW())");
                $status = 'completed';
                $method = 'stripe';
                $stmt->bind_param("idsss", $rental_id, $rental['total_cost'], $method, $charge->id, $status);
                $stmt->execute();

                // Update rental status
                $stmt = $conn->prepare("UPDATE rentals SET status = 'active' WHERE rental_id = ?");
                $stmt->bind_param("i", $rental_id);
                $stmt->execute();

                // Trigger messaging (email)
                if ($user) {
                    require_once __DIR__ . '/../includes/MessagingService.php';
                    $messaging = new MessagingService();
                    $messaging->sendPaymentConfirmation([
                        'email' => $user['email'] ?? '',
                        'phone' => $user['phone'] ?? '',
                        'name' => $user['full_name'] ?? '',
                        'amount' => $rental['total_cost'],
                        'payment_method' => 'Card (Stripe)',
                        'transaction_id' => $charge->id,
                        'rental_id' => $rental_id,
                        'admin_email' => $adminUser['email'] ?? '',
                    ]);
                }
                // Optional: Log the result
                error_log("Messaging result: " . json_encode($result));

                $GLOBALS['rental_id'] = $rental_id; // For use in the view
                require 'views/rentals/success.php';
            } catch (\Stripe\Exception\CardException $e) {
                $error = "Card error: " . $e->getMessage();
                $GLOBALS['rental_id'] = $rental_id;
                require 'views/rentals/fail.php';
            } catch (\Exception $e) {
                $error = "Payment error: " . $e->getMessage();
                $GLOBALS['rental_id'] = $rental_id;
                require 'views/rentals/fail.php';
            }
        } elseif ($paymentMethod === 'paypack') {
            // TODO: Implement includes/PaypackHandler.php
            require_once __DIR__ . '/../includes/PayPackHandler.php';
            $phoneNumber = trim($_POST['phone_number'] ?? '');
            if (empty($phoneNumber)) {
                $error = "Phone number is required for Mobile Money payment.";
                require 'views/rentals/fail.php';
                exit;
            }
            $paypack = new PaypackHandler($conn);
            $result = $paypack->initiatePayment($rental_id, $rental['total_cost'], $phoneNumber);
            if ($result['success']) {
                // // Save payment info to DB
                // $stmt = $conn->prepare("INSERT INTO payments (rental_id, amount, payment_method, transaction_id, status, payment_date) VALUES (?, ?, ?, ?, ?, NOW())");
                $status = 'completed';
                $method = 'paypack';
                $transactionId = $result['transaction_id'] ?? '';
                // $stmt->bind_param("idsss", $rental_id, $rental['total_cost'], $method, $transactionId, $status);
                // $stmt->execute();

                // // Update rental status
                // $stmt = $conn->prepare("UPDATE rentals SET status = 'active' WHERE rental_id = ?");
                // $stmt->bind_param("i", $rental_id);
                // $stmt->execute();

                // Trigger messaging (email/SMS)
                if ($user) {
                    require_once __DIR__ . '/../includes/MessagingService.php';
                    $messaging = new MessagingService();
                   $sendMessage = $messaging->sendPaymentConfirmation([
                        'email' => $user['email'] ?? '',
                        'phone' => $user['phone'] ?? '',
                        'name' => $user['full_name'] ?? '',
                        'amount' => $rental['total_cost'],
                        'payment_method' => $method,
                        'transaction_id' => $transactionId,
                        'rental_id' => $rental_id,
                        'admin_email' => $adminUser['email'] ?? '',

                    ]);
                }

                if ($sendMessage) {
                    $GLOBALS['rental_id'] = $rental_id;
                    require 'views/rentals/success.php';
                } else {
                    $error = "Failed to send payment confirmation message.";
                    $GLOBALS['rental_id'] = $rental_id;
                    require 'views/rentals/fail.php';
                }
            } else {
                $error = $result['message'] ?? 'Mobile Money payment failed.';
                $GLOBALS['rental_id'] = $rental_id;
                require 'views/rentals/fail.php';
            }
        } else {
            $error = "Invalid payment method selected.";
            $GLOBALS['rental_id'] = $rental_id;
            require 'views/rentals/fail.php';
        }
    }
}