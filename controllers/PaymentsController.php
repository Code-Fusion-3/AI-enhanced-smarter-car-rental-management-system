<?php
require_once 'config/database.php';
require_once 'vendor/autoload.php'; // Make sure Composer's autoload is included

class PaymentsController {
    public function pay() {
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

    public function process() {
        \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']); // Replace with your Stripe secret key

        $token = $_POST['stripeToken'] ?? null;
        $rental_id = $_POST['rental_id'] ?? null;

        if (!$token || !$rental_id) {
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

        try {
            $charge = \Stripe\Charge::create([
                'amount' => intval($rental['total_cost'] * 100), // amount in cents
                'currency' => 'usd',
                'description' => 'Car Rental Payment',
                'source' => $token,
            ]);

            // Save payment info to DB (simplified)
            $stmt = $conn->prepare("INSERT INTO payments (rental_id, amount, payment_method, transaction_id, status, payment_date) VALUES (?, ?, ?, ?, ?, NOW())");
            $status = 'completed';
            $method = 'stripe';
            $stmt->bind_param("idsss", $rental_id, $rental['total_cost'], $method, $charge->id, $status);
            $stmt->execute();

            // Update rental status
            $stmt = $conn->prepare("UPDATE rentals SET status = 'active' WHERE rental_id = ?");
            $stmt->bind_param("i", $rental_id);
            $stmt->execute();

            $GLOBALS['rental_id'] = $rental_id; // For use in the view
            require 'views/rentals/success.php';
        } catch (\Stripe\Exception\CardException $e) {
            $error = "Card error: " . $e->getMessage();
            $GLOBALS['rental_id'] = $rental_id;
            require 'views/rentals/fail.php';
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            // Handles reused token, invalid parameters, etc.
            $error = "Payment error: " . $e->getMessage();
            $GLOBALS['rental_id'] = $rental_id;
            require 'views/rentals/fail.php';
        } catch (\Stripe\Exception\AuthenticationException $e) {
            // Invalid API keys
            $error = "Payment configuration error: Invalid Stripe API keys.";
            $GLOBALS['rental_id'] = $rental_id;
            require 'views/rentals/fail.php';
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            // Network error
            $error = "Network error: Unable to connect to payment gateway.";
            $GLOBALS['rental_id'] = $rental_id;
            require 'views/rentals/fail.php';
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // General Stripe API error
            $error = "Payment processing error: " . $e->getMessage();
            $GLOBALS['rental_id'] = $rental_id;
            require 'views/rentals/fail.php';
        } catch (\Exception $e) {
            // Any other error
            $error = "Unexpected error: " . $e->getMessage();
            $GLOBALS['rental_id'] = $rental_id;
            require 'views/rentals/fail.php';
        }
    }
}