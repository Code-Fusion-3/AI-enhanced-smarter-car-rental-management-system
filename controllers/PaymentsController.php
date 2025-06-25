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
            \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
            $YOUR_DOMAIN = 'http://localhost/utb/AI-enhanced-smarter-car-rental-management-system';
            try {
                $session = \Stripe\Checkout\Session::create([
                    'payment_method_types' => ['card'],
                    'line_items' => [
                        [
                            'price_data' => [
                                'currency' => 'rwf',
                                'product_data' => [
                                    'name' => 'Car Rental Payment',
                                ],
                                'unit_amount' => intval($rental['total_cost']),
                            ],
                            'quantity' => 1,
                        ]
                    ],
                    'mode' => 'payment',
                    'success_url' => $YOUR_DOMAIN . '/index.php?page=payments&action=success&rental_id=' . $rental_id . '&session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => $YOUR_DOMAIN . '/index.php?page=payments&action=cancel&rental_id=' . $rental_id,
                    'metadata' => [
                        'rental_id' => $rental_id,
                    ],
                ]);
                header("Location: " . $session->url);
                exit;
            } catch (\Exception $e) {
                $error = "Payment error: " . $e->getMessage();
                $GLOBALS['rental_id'] = $rental_id;
                require 'views/rentals/fail.php';
            }
        } 
        elseif ($paymentMethod === 'paypack') {
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
                // Redirect to pending page instead of success
                $transaction_id = $result['transaction_id'];
                header("Location: index.php?page=payments&action=pending&transaction_id=" . $transaction_id);
                exit;
            } else {
                $error = $result['message'] ?? 'Mobile Money payment failed.';
                $GLOBALS['rental_id'] = $rental_id;
                require 'views/rentals/fail.php';
            }
        }
        else {
            $error = "Invalid payment method selected.";
            $GLOBALS['rental_id'] = $rental_id;
            require 'views/rentals/fail.php';
        }
    }

    public function pending()
    {
        $transaction_id = $_GET['transaction_id'] ?? null;
        if (!$transaction_id) {
            $error = 'Missing transaction information.';
            require 'views/rentals/fail.php';
            return;
        }

        $db = new Database();
        $conn = $db->connect();
        
        require_once __DIR__ . '/../includes/PayPackHandler.php';
        $paypack = new PaypackHandler($conn);
        
        // Get transaction details
        $transaction = $paypack->getTransactionDetails($transaction_id);
        if (!$transaction) {
            $error = 'Transaction not found.';
            require 'views/rentals/fail.php';
            return;
        }

        $GLOBALS['transaction'] = $transaction;
        require 'views/rentals/pending.php';
    }

public function checkStatus()
{
    header('Content-Type: application/json');
    
    $transaction_id = $_GET['transaction_id'] ?? null;
    if (!$transaction_id) {
        echo json_encode(['success' => false, 'message' => 'Missing transaction ID']);
        return;
    }

    error_log("=== PAYMENT STATUS CHECK REQUEST ===");
    error_log("Transaction ID: " . $transaction_id);
    error_log("Request time: " . date('Y-m-d H:i:s'));

    try {
        $db = new Database();
        $conn = $db->connect();
        
        // Check current transaction status in database first
        $stmt = $conn->prepare("SELECT * FROM payment_transactions WHERE transaction_id = ?");
        $stmt->bind_param("i", $transaction_id);
        $stmt->execute();
        $currentTransaction = $stmt->get_result()->fetch_assoc();
        
        if ($currentTransaction) {
            error_log("Current transaction status in DB: " . $currentTransaction['status']);
            error_log("Gateway reference: " . ($currentTransaction['gateway_reference'] ?? 'N/A'));
        }
        
        require_once __DIR__ . '/../includes/PayPackHandler.php';
        $paypack = new PaypackHandler($conn);
        
        $result = $paypack->checkPaymentStatus($transaction_id);
        
        error_log("Status check result: " . json_encode($result));
        
        // If payment was completed, verify the rental status was updated
        if (isset($result['redirect']) && $result['redirect'] === 'success') {
            $stmt = $conn->prepare("SELECT status FROM rentals WHERE rental_id = ?");
            $stmt->bind_param("i", $currentTransaction['rental_id']);
            $stmt->execute();
            $rental = $stmt->get_result()->fetch_assoc();
            
            error_log("Rental status after completion: " . json_encode($rental));
        }
        
        echo json_encode($result);
        
    } catch (Exception $e) {
        error_log("Exception in checkStatus: " . $e->getMessage());
        error_log("Exception trace: " . $e->getTraceAsString());
        echo json_encode([
            'success' => false, 
            'message' => 'Server error: ' . $e->getMessage()
        ]);
    }
}

public function manualComplete()
{
    if (!isset($_GET['transaction_id'])) {
        die('Missing transaction ID');
    }
    
    $transaction_id = $_GET['transaction_id'];
    
    $db = new Database();
    $conn = $db->connect();
    
    require_once __DIR__ . '/../includes/PayPackHandler.php';
    $paypack = new PaypackHandler($conn);
    
    // Manually mark as completed for testing
    $stmt = $conn->prepare("UPDATE payment_transactions SET status = 'completed' WHERE transaction_id = ?");
    $stmt->bind_param("i", $transaction_id);
    $stmt->execute();
    
    // Get reflection to call private method
    $reflection = new ReflectionClass($paypack);
    $method = $reflection->getMethod('handleCompletedPayment');
    $method->setAccessible(true);
    
    $result = $method->invoke($paypack, $transaction_id);
    
    echo "<h2>Manual Completion Test</h2>";
    echo "<pre>" . json_encode($result, JSON_PRETTY_PRINT) . "</pre>";
    
    // Check final status
    $stmt = $conn->prepare("
        SELECT pt.*, r.status as rental_status, r.status 
        FROM payment_transactions pt 
        JOIN rentals r ON pt.rental_id = r.rental_id 
        WHERE pt.transaction_id = ?
    ");
    $stmt->bind_param("i", $transaction_id);
    $stmt->execute();
    $final = $stmt->get_result()->fetch_assoc();
    
    echo "<h3>Final Status:</h3>";
    echo "<pre>" . json_encode($final, JSON_PRETTY_PRINT) . "</pre>";
}



    public function success()
    {
        $rental_id = $_GET['rental_id'] ?? null;
        $session_id = $_GET['session_id'] ?? null;
        
        if (!$rental_id || !$session_id) {
            $error = 'Missing rental or session information.';
            $GLOBALS['rental_id'] = $rental_id;
            require 'views/rentals/fail.php';
            return;
        }

        $db = new Database();
        $conn = $db->connect();

        // Fetch rental info
        $stmt = $conn->prepare("SELECT * FROM rentals WHERE rental_id = ?");
        $stmt->bind_param("i", $rental_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $rental = $result->fetch_assoc();
        if (!$rental) {
            $error = 'Rental not found.';
            $GLOBALS['rental_id'] = $rental_id;
            require 'views/rentals/fail.php';
            return;
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

        \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
        try {
            $session = \Stripe\Checkout\Session::retrieve($session_id);
            if ($session && $session->payment_status === 'paid') {
                // Save payment info to DB (if not already saved)
                $transactionId = $session->payment_intent;
                $stmt = $conn->prepare("SELECT * FROM payments WHERE transaction_id = ?");
                $stmt->bind_param("s", $transactionId);
                $stmt->execute();
                $existingPayment = $stmt->get_result()->fetch_assoc();
                if (!$existingPayment) {
                    $stmt = $conn->prepare("INSERT INTO payments (rental_id, amount, payment_method, transaction_id, status, payment_date) VALUES (?, ?, ?, ?, ?, NOW())");
                    $status = 'completed';
                    $method = 'stripe';
                    $stmt->bind_param("idsss", $rental_id, $rental['total_cost'], $method, $transactionId, $status);
                    $stmt->execute();
                }
                // Update rental status
                $stmt = $conn->prepare("UPDATE rentals SET status = 'active' WHERE rental_id = ?");
                $stmt->bind_param("i", $rental_id);
                $stmt->execute();
                // Trigger messaging (email/SMS)
                if ($user) {
                    require_once __DIR__ . '/../includes/MessagingService.php';
                    $messaging = new MessagingService();
                    $messaging->sendPaymentConfirmation([
                        'email' => $user['email'] ?? '',
                        'phone' => $user['phone'] ?? '',
                        'name' => $user['full_name'] ?? '',
                        'amount' => $rental['total_cost'],
                        'payment_method' => 'Card (Stripe)',
                        'transaction_id' => $transactionId,
                        'rental_id' => $rental_id,
                        'admin_email' => $adminUser['email'] ?? '',
                    ]);
                }
                $GLOBALS['rental_id'] = $rental_id;
                require 'views/rentals/success.php';
            } else {
                $error = 'Payment not completed or session invalid.';
                $GLOBALS['rental_id'] = $rental_id;
                require 'views/rentals/fail.php';
            }
        } catch (\Exception $e) {
            $error = 'Stripe verification error: ' . $e->getMessage();
            $GLOBALS['rental_id'] = $rental_id;
            require 'views/rentals/fail.php';
        }
    }

    public function cancel()
    {
        $rental_id = $_GET['rental_id'] ?? null;
        $error = 'Payment was cancelled or not completed.';
        $GLOBALS['rental_id'] = $rental_id;
        require 'views/rentals/fail.php';
    }
}