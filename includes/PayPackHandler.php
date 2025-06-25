<?php
/**
 * PayPackHandler
 * Handles mobile money (MTN/Airtel) payments for car rentals.
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/MessagingService.php';

class PayPackHandler
{
    private $clientId;
    private $clientSecret;
    private $apiUrl = 'https://payments.paypack.rw/api';
    private $conn;
    private $messagingService;

    public function __construct($connection = null)
    {
        error_log("=== PAYPACK HANDLER CONSTRUCTOR ===");

        if ($connection) {
            $this->conn = $connection;
        } else {
            global $conn;
            $this->conn = $conn;
        }

        $this->messagingService = new MessagingService();
        $this->loadSettings();
        error_log("PayPack handler initialized");
    }

    /**
     * Initiate a mobile money payment
     */
    public function initiatePayment($rentalId, $amount, $phoneNumber)
    {
        try {
            error_log("=== PAYPACK PAYMENT INITIATION ===");
            error_log("Rental ID: " . $rentalId);
            error_log("Amount: " . $amount);
            error_log("Original phone input: '" . $phoneNumber . "'");

            $formattedPhone = $this->formatPhoneForPayPack($phoneNumber);
            error_log("Formatted phone for PayPack: " . $formattedPhone);

            // Get authentication token
            $token = $this->getPaypackToken();
            if (!$token) {
                error_log("Failed to get authentication token");
                return [
                    'success' => false,
                    'message' => 'Failed to authenticate with PayPack - check credentials and API connectivity'
                ];
            }

            // Generate unique reference and idempotency key
            $reference = 'rental_' . $rentalId . '_' . time();
            $idempotencyKey = substr(md5($reference . microtime()), 0, 32);
            error_log("Payment reference: " . $reference);
            error_log("Idempotency key: " . $idempotencyKey);

            // Create payment transaction record BEFORE making the API call
            try {
                $stmt = $this->conn->prepare("
                    INSERT INTO payment_transactions (rental_id, user_id, payment_method, amount, currency, status, gateway_reference)
                    SELECT ?, user_id, 'paypack', ?, 'RWF', 'pending', ?
                    FROM rentals WHERE rental_id = ?
                ");
                $stmt->bind_param("idsi", $rentalId, $amount, $reference, $rentalId);
                $stmt->execute();
                $transactionId = $this->conn->insert_id;
                error_log("Transaction record created with ID: " . $transactionId);
            } catch (Exception $dbError) {
                error_log("Database error creating transaction: " . $dbError->getMessage());
                return [
                    'success' => false,
                    'message' => 'Database error: ' . $dbError->getMessage()
                ];
            }

            // Make payment request
            $curl = curl_init();

            $headers = [
                "Authorization: Bearer $token",
                'Content-Type: application/json',
                'Accept: application/json',
                'Idempotency-Key: ' . $idempotencyKey
            ];

            $paymentData = [
                "amount" => floatval($amount),
                "number" => $formattedPhone
            ];

            $jsonPayload = json_encode($paymentData);

            error_log("=== PAYPACK API REQUEST DEBUG ===");
            error_log("Payment request URL: " . $this->apiUrl . '/transactions/cashin');
            error_log("Payment request headers: " . json_encode($headers));
            error_log("Payment request data (JSON): " . $jsonPayload);

            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->apiUrl . '/transactions/cashin',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $jsonPayload,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_TIMEOUT => 30,
            ));

            $response = curl_exec($curl);
            $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $curl_error = curl_error($curl);

            error_log("=== PAYPACK API RESPONSE DEBUG ===");
            error_log("HTTP Code: " . $http_code);
            error_log("Raw Response: " . $response);
            error_log("CURL Error: " . $curl_error);

            curl_close($curl);

            // Handle response
            if ($curl_error) {
                error_log("CURL error: " . $curl_error);

                // Update transaction as failed
                $stmt = $this->conn->prepare("
                    UPDATE payment_transactions 
                    SET status = 'failed', failure_reason = ?
                    WHERE transaction_id = ?
                ");
                $stmt->bind_param("si", $curl_error, $transactionId);
                $stmt->execute();

                return [
                    'success' => false,
                    'message' => 'Network error: ' . $curl_error
                ];
            }

            $responseData = json_decode($response, true);
            error_log("Decoded response data: " . json_encode($responseData));

            // Update transaction record based on response
            if ($http_code == 200 || $http_code == 201) {
                $gatewayRef = $responseData['ref'] ?? $reference;

                $stmt = $this->conn->prepare("
                    UPDATE payment_transactions 
                    SET status = 'pending', gateway_transaction_id = ?, gateway_response = ?
                    WHERE transaction_id = ?
                ");
                $stmt->bind_param("ssi", $gatewayRef, $response, $transactionId);
                $stmt->execute();

                error_log("Payment initiated successfully - Transaction ID: " . $transactionId . ", Gateway Ref: " . $gatewayRef);

                return [
                    'success' => true,
                    'transaction_id' => $transactionId,
                    'gateway_reference' => $gatewayRef,
                    'message' => 'Payment initiated successfully. Please check your phone for the payment prompt.'
                ];
            } else {
                $failureReason = $responseData['message'] ?? 'Payment initiation failed (HTTP ' . $http_code . ')';

                $stmt = $this->conn->prepare("
                    UPDATE payment_transactions 
                    SET status = 'failed', failure_reason = ?, gateway_response = ?
                    WHERE transaction_id = ?
                ");
                $stmt->bind_param("ssi", $failureReason, $response, $transactionId);
                $stmt->execute();

                error_log("Payment failed - Reason: " . $failureReason);

                return [
                    'success' => false,
                    'message' => $failureReason
                ];
            }

        } catch (Exception $e) {
            error_log("PayPack Exception: " . $e->getMessage());
            error_log("Exception trace: " . $e->getTraceAsString());
            return [
                'success' => false,
                'message' => 'Payment processing error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Check payment status using events API
     */
    public function checkPaymentStatus($transactionId)
    {
        try {
            error_log("=== CHECKING PAYMENT STATUS ===");
            error_log("Transaction ID: " . $transactionId);

            // Get the gateway reference from database
            $stmt = $this->conn->prepare("
            SELECT gateway_transaction_id, gateway_reference, status 
            FROM payment_transactions 
            WHERE transaction_id = ?
        ");
            $stmt->bind_param("i", $transactionId);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();

            if (!$result) {
                error_log("Transaction not found in database");
                return ['success' => false, 'message' => 'Transaction not found'];
            }

            $reference = $result['gateway_transaction_id'] ?? $result['gateway_reference'];
            if (!$reference) {
                error_log("No gateway reference found");
                return ['success' => false, 'message' => 'No gateway reference found'];
            }

            error_log("Checking status for reference: " . $reference);

            // Get authentication token
            $token = $this->getPaypackToken();
            if (!$token) {
                error_log("Failed to get authentication token for status check");
                return ['success' => false, 'message' => 'Authentication failed - check PayPack credentials'];
            }

            error_log("Token obtained for status check: " . substr($token, 0, 20) . "...");

            // First try events API
            $eventsResult = $this->checkPaypackEvents($token, $reference);

            error_log("Events API HTTP Code: " . $eventsResult['http_code']);
            error_log("Events API Response: " . $eventsResult['response']);

            if ($eventsResult['http_code'] == 200 && $eventsResult['decoded']) {
                $events = $eventsResult['decoded'];

                // Look for completion events first
                if (isset($events['transactions']) && is_array($events['transactions'])) {
                    foreach ($events['transactions'] as $event) {
                        if (isset($event['event_kind'])) {
                            error_log("Found event: " . $event['event_kind']);

                            // Check for completion events
                            if (
                                $event['event_kind'] === 'transaction:processed' ||
                                $event['event_kind'] === 'transaction:successful' ||
                                $event['event_kind'] === 'transaction:completed'
                            ) {

                                error_log("Found completion event: " . $event['event_kind']);
                                return $this->updateTransactionStatus($transactionId, 'successful', $result['status'], $eventsResult['response']);
                            }

                            // Check for failure events
                            if (
                                $event['event_kind'] === 'transaction:failed' ||
                                $event['event_kind'] === 'transaction:cancelled'
                            ) {

                                error_log("Found failure event: " . $event['event_kind']);
                                return $this->updateTransactionStatus($transactionId, 'failed', $result['status'], $eventsResult['response']);
                            }
                        }
                    }

                    // If no completion events found, check for created events (still pending)
                    foreach ($events['transactions'] as $event) {
                        if (isset($event['event_kind']) && $event['event_kind'] === 'transaction:created') {
                            $createdTime = strtotime($event['created_at'] ?? '');
                            $minutesAgo = round((time() - $createdTime) / 60);

                            return [
                                'success' => true,
                                'status_updated' => false,
                                'current_status' => 'pending',
                                'message' => "Payment request sent {$minutesAgo} minutes ago. Please check your phone and approve the payment."
                            ];
                        }
                    }
                }
            } else {
                error_log("Events API failed with code: " . $eventsResult['http_code']);
            }

            // Fallback to direct transaction check
            error_log("Falling back to direct transaction check");
            return $this->checkTransactionDirect($token, $reference, $transactionId, $result['status']);

        } catch (Exception $e) {
            error_log("Exception during status check: " . $e->getMessage());
            error_log("Exception trace: " . $e->getTraceAsString());
            return ['success' => false, 'message' => 'Error checking payment status: ' . $e->getMessage()];
        }
    }


    /**
     * Update transaction status based on PayPack response
     */
    private function updateTransactionStatus($transactionId, $paypackStatus, $currentStatus, $response)
    {
        error_log("=== UPDATING TRANSACTION STATUS ===");
        error_log("Transaction ID: " . $transactionId);
        error_log("Current status: " . $currentStatus);
        error_log("PayPack status: " . $paypackStatus);

        // Map PayPack status to our system status
        $newStatus = null;
        switch (strtolower($paypackStatus)) {
            case 'successful':
            case 'success':
                $newStatus = 'completed';
                break;
            case 'failed':
            case 'cancelled':
                $newStatus = 'failed';
                break;
            case 'pending':
            case 'processing':
                $newStatus = 'pending';
                break;
        }

        error_log("Mapped new status: " . ($newStatus ?? 'null'));

        // Update transaction status if changed
        if ($newStatus && $newStatus !== $currentStatus) {
            $stmt = $this->conn->prepare("
            UPDATE payment_transactions 
            SET status = ?, updated_at = CURRENT_TIMESTAMP, gateway_response = ?
            WHERE transaction_id = ?
        ");
            $stmt->bind_param("ssi", $newStatus, $response, $transactionId);
            $updateResult = $stmt->execute();

            if (!$updateResult) {
                error_log("Failed to update transaction status: " . $this->conn->error);
                return [
                    'success' => false,
                    'message' => 'Failed to update transaction status'
                ];
            }

            error_log("Transaction status updated to: " . $newStatus);

            // If completed, handle completion
            if ($newStatus === 'completed') {
                error_log("Processing completed payment...");
                $completionResult = $this->handleCompletedPayment($transactionId);

                if ($completionResult && isset($completionResult['success']) && $completionResult['success']) {
                    error_log("Payment completion handled successfully");
                    return [
                        'success' => true,
                        'status_updated' => true,
                        'new_status' => $newStatus,
                        'redirect' => 'success',
                        'message' => 'Payment completed successfully',
                        'completion_details' => $completionResult
                    ];
                } else {
                    error_log("Payment completion handling failed: " . json_encode($completionResult));
                    return [
                        'success' => true,
                        'status_updated' => true,
                        'new_status' => $newStatus,
                        'redirect' => 'success',
                        'message' => 'Payment completed but some post-processing failed',
                        'completion_error' => $completionResult
                    ];
                }
            } elseif ($newStatus === 'failed') {
                return [
                    'success' => true,
                    'status_updated' => true,
                    'new_status' => $newStatus,
                    'redirect' => 'failed',
                    'message' => 'Payment failed'
                ];
            }

            return [
                'success' => true,
                'status_updated' => true,
                'new_status' => $newStatus,
                'message' => 'Payment status updated to ' . $newStatus
            ];
        } else {
            return [
                'success' => true,
                'status_updated' => false,
                'current_status' => $currentStatus,
                'message' => 'No status change - still ' . $currentStatus
            ];
        }
    }

    /**
     * Direct transaction status check using find API
     */
    private function checkTransactionDirect($token, $reference, $transactionId, $currentStatus)
    {
        error_log("=== DIRECT TRANSACTION CHECK ===");
        error_log("Reference: " . $reference);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->apiUrl . "/transactions/find/" . $reference,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $token",
                'Accept: application/json',
                'Content-Type: application/json'
            ),
            CURLOPT_TIMEOUT => 30,
        ));

        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($curl);

        error_log("Direct transaction check - HTTP Code: " . $http_code);
        error_log("Direct transaction response: " . $response);

        if ($curl_error) {
            error_log("CURL error in direct check: " . $curl_error);
        }

        curl_close($curl);

        if ($curl_error) {
            return [
                'success' => false,
                'message' => 'Network error during status check: ' . $curl_error
            ];
        }

        if ($http_code == 200) {
            $responseData = json_decode($response, true);
            if ($responseData && isset($responseData['status'])) {
                $paypackStatus = $responseData['status'];
                error_log("Direct check PayPack status: " . $paypackStatus);

                return $this->updateTransactionStatus($transactionId, $paypackStatus, $currentStatus, $response);
            } else {
                error_log("No status in direct response: " . $response);
                return [
                    'success' => false,
                    'message' => 'Invalid response from PayPack API'
                ];
            }
        } elseif ($http_code == 404) {
            return [
                'success' => true,
                'status_updated' => false,
                'current_status' => $currentStatus,
                'message' => 'Transaction not found in PayPack - may still be processing'
            ];
        } else {
            error_log("Direct check failed with HTTP " . $http_code . ": " . $response);
            return [
                'success' => false,
                'message' => 'PayPack API error (HTTP ' . $http_code . ')'
            ];
        }
    }

    /**
     * Handle completed payment - update rental and send notifications
     */

    /**
     * Handle completed payment - updated for correct table structure
     */
    private function handleCompletedPayment($transactionId)
    {
        error_log("=== HANDLING COMPLETED PAYMENT ===");
        error_log("Transaction ID: " . $transactionId);

        try {
            // Get transaction and rental details
            $stmt = $this->conn->prepare("
            SELECT 
                pt.rental_id,
                pt.amount,
                pt.gateway_transaction_id,
                r.user_id,
                u.full_name,
                u.email,
                u.phone
            FROM payment_transactions pt
            JOIN rentals r ON pt.rental_id = r.rental_id
            JOIN users u ON r.user_id = u.user_id
            WHERE pt.transaction_id = ?
        ");
            $stmt->bind_param("i", $transactionId);
            $stmt->execute();
            $data = $stmt->get_result()->fetch_assoc();

            if (!$data) {
                error_log("No data found for completed payment");
                return null;
            }

            error_log("Processing payment for rental ID: " . $data['rental_id']);

            // Start transaction
            $this->conn->begin_transaction();

            try {
                // Update rental status to active (remove payment_status since it doesn't exist)
                $stmt = $this->conn->prepare("
                UPDATE rentals 
                SET status = 'active'
                WHERE rental_id = ?
            ");
                $stmt->bind_param("i", $data['rental_id']);
                $updateResult = $stmt->execute();

                if (!$updateResult) {
                    throw new Exception("Failed to update rental status: " . $this->conn->error);
                }

                error_log("Rental status updated to 'active' successfully");

                // Insert into payments table for compatibility
                $stmt = $this->conn->prepare("
                INSERT INTO payments (rental_id, amount, payment_method, transaction_id, status, payment_date) 
                VALUES (?, ?, 'paypack', ?, 'completed', NOW())
                ON DUPLICATE KEY UPDATE status = 'completed', payment_date = NOW()
            ");
                $stmt->bind_param("ids", $data['rental_id'], $data['amount'], $data['gateway_transaction_id']);
                $paymentResult = $stmt->execute();

                if (!$paymentResult) {
                    throw new Exception("Failed to insert payment record: " . $this->conn->error);
                }

                error_log("Payment record inserted/updated successfully");

                // Commit transaction
                $this->conn->commit();
                error_log("Database transaction committed successfully");

                // Get admin user for notifications
                $adminResult = $this->conn->query("SELECT * FROM users WHERE role = 'admin' LIMIT 1");
                $adminUser = $adminResult ? $adminResult->fetch_assoc() : null;

                // Send payment confirmation notifications
                try {
                    $notificationResult = $this->messagingService->sendPaymentConfirmation([
                        'email' => $data['email'],
                        'phone' => $data['phone'],
                        'name' => $data['full_name'],
                        'amount' => $data['amount'],
                        'payment_method' => 'Mobile Money (PayPack)',
                        'transaction_id' => $data['gateway_transaction_id'],
                        'rental_id' => $data['rental_id'],
                        'admin_email' => $adminUser['email'] ?? 'admin@yourcarrental.com'
                    ]);

                    error_log("Notification result: " . json_encode($notificationResult));
                } catch (Exception $notifError) {
                    error_log("Notification failed but payment processed: " . $notifError->getMessage());
                    // Don't fail the whole process if notifications fail
                }

                return [
                    'success' => true,
                    'rental_updated' => true,
                    'payment_recorded' => true,
                    'notifications_sent' => isset($notificationResult) ? $notificationResult : null
                ];

            } catch (Exception $dbError) {
                // Rollback transaction on error
                $this->conn->rollback();
                error_log("Database transaction rolled back due to error: " . $dbError->getMessage());
                throw $dbError;
            }

        } catch (Exception $e) {
            error_log("Error handling completed payment: " . $e->getMessage());
            error_log("Exception trace: " . $e->getTraceAsString());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }


    /**
     * Format phone number for PayPack API
     */
    private function formatPhoneForPayPack($phoneNumber)
    {
        error_log("=== PHONE FORMATTING FOR PAYPACK ===");
        error_log("Original input: '" . $phoneNumber . "'");

        $phone = preg_replace('/[^0-9]/', '', $phoneNumber);
        error_log("After removing non-digits: '" . $phone . "'");

        if (strlen($phone) == 12 && substr($phone, 0, 3) == '250') {
            $formatted = '0' . substr($phone, 3);
        } elseif (strlen($phone) == 11 && substr($phone, 0, 2) == '25') {
            $formatted = '0' . substr($phone, 2);
        } elseif (strlen($phone) == 10 && substr($phone, 0, 1) == '0') {
            $formatted = $phone;
        } elseif (strlen($phone) == 9) {
            $formatted = '0' . $phone;
        } else {
            $formatted = $phone;
        }

        error_log("Final formatted number: " . $formatted);
        return $formatted;
    }

    /**
     * Load PayPack settings from environment variables
     */
    private function loadSettings()
    {
        error_log("=== LOADING PAYPACK SETTINGS ===");

        $this->clientId = $_ENV['PAYPACK_CLIENT_ID'] ?? getenv('PAYPACK_CLIENT_ID');
        $this->clientSecret = $_ENV['PAYPACK_CLIENT_SECRET'];
        $this->apiUrl = $_ENV['PAYPACK_API_URL'];

        error_log("Final settings - Client ID: " . ($this->clientId ? 'SET' : 'NOT SET'));
        error_log("Final settings - Client Secret: " . ($this->clientSecret ? 'SET' : 'NOT SET'));
        error_log("Final settings - API URL: " . $this->apiUrl);
    }

    /**
     * Get PayPack authentication token
     */
    private function getPaypackToken()
    {
        error_log("=== GETTING PAYPACK TOKEN ===");

        if (empty($this->clientId) || empty($this->clientSecret)) {
            error_log("PayPack credentials not set - Client ID: " . ($this->clientId ? 'SET' : 'EMPTY') . ", Secret: " . ($this->clientSecret ? 'SET' : 'EMPTY'));
            return false;
        }

        $curl = curl_init();

        $postData = json_encode([
            "client_id" => $this->clientId,
            "client_secret" => $this->clientSecret
        ]);

        error_log("Auth request URL: " . $this->apiUrl . '/auth/agents/authorize');
        error_log("Auth request data: " . $postData);

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->apiUrl . '/auth/agents/authorize',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($curl);

        error_log("Auth response HTTP Code: " . $http_code);
        error_log("Auth response body: " . $response);
        if ($curl_error) {
            error_log("Auth CURL Error: " . $curl_error);
        }

        curl_close($curl);

        if ($curl_error) {
            error_log("CURL error occurred: " . $curl_error);
            return false;
        }

        if ($http_code == 200) {
            $data = json_decode($response, true);
            if ($data && isset($data['access'])) {
                error_log("Token obtained successfully");
                return $data['access'];
            } else {
                error_log("No access token in response: " . $response);
                return false;
            }
        }

        error_log("Auth failed - HTTP Code: " . $http_code . ", Response: " . $response);
        return false;
    }

    /**
     * Check PayPack events for transaction status
     */
    private function checkPaypackEvents($token, $reference)
    {
        error_log("=== CHECKING PAYPACK EVENTS ===");

        $curl = curl_init();
        $url = $this->apiUrl . "/events/transactions?ref=" . urlencode($reference);

        error_log("Events API URL: " . $url);

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $token",
                "Accept: application/json"
            ),
            CURLOPT_TIMEOUT => 30,
        ));

        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($curl);

        if ($curl_error) {
            error_log("CURL error in events check: " . $curl_error);
        }

        curl_close($curl);

        $decoded = json_decode($response, true);

        return [
            'http_code' => $http_code,
            'response' => $response,
            'decoded' => $decoded,
            'curl_error' => $curl_error
        ];
    }

    /**
     * Get transaction details by ID
     */
    public function getTransactionDetails($transactionId)
    {
        try {
            $stmt = $this->conn->prepare("
                SELECT 
                    pt.*,
                    r.rental_id,
                    r.start_date,
                    r.end_date,
                    r.total_cost,
                    u.full_name,
                    u.email,
                    u.phone,
                    c.make,
                    c.model
                FROM payment_transactions pt
                JOIN rentals r ON pt.rental_id = r.rental_id
                JOIN users u ON r.user_id = u.user_id
                JOIN cars c ON r.car_id = c.car_id
                WHERE pt.transaction_id = ?
            ");

            $stmt->bind_param("i", $transactionId);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();

            return $result;

        } catch (Exception $e) {
            error_log("Error getting transaction details: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Cancel a pending transaction
     */
    public function cancelTransaction($transactionId)
    {
        try {
            $stmt = $this->conn->prepare("
                UPDATE payment_transactions 
                SET status = 'cancelled', updated_at = CURRENT_TIMESTAMP
                WHERE transaction_id = ? AND status IN ('pending', 'processing')
            ");

            $stmt->bind_param("i", $transactionId);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                return [
                    'success' => true,
                    'message' => 'Transaction cancelled successfully'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Transaction not found or cannot be cancelled'
                ];
            }

        } catch (Exception $e) {
            error_log("Error cancelling transaction: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error cancelling transaction: ' . $e->getMessage()
            ];
        }
    }
}
?>