<?php
/**
 * PayPackHandler
 * Handles mobile money (MTN/Airtel) payments for car rentals.
 *
 * Usage:
 *   $paypack = new PayPackHandler();
 *   $result = $paypack->initiatePayment($rentalId, $amount, $phoneNumber);
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
        
        // Use provided connection or get global connection
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
     * @param int $rentalId
     * @param float $amount
     * @param string $phoneNumber
     * @return array [success => bool, transaction_id => string, message => string]
     */
    public function initiatePayment($rentalId, $amount, $phoneNumber)
    {
        try {
            error_log("=== PAYPACK PAYMENT INITIATION ===");
            error_log("Rental ID: " . $rentalId);
            error_log("Amount: " . $amount);
            error_log("Original phone input: '" . $phoneNumber . "'");

            // Format phone number using the working method
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

            error_log("Token obtained, proceeding with payment");

            // Generate unique reference
            $reference = 'rental_' . $rentalId . '_' . time();
            error_log("Payment reference: " . $reference);

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

            // Make payment request using the exact same structure as working test
            $curl = curl_init();

            $headers = [
                "Authorization: Bearer $token",
                'Content-Type: application/json'
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
                $gatewayId = $responseData['ref'] ?? $reference;

                $stmt = $this->conn->prepare("
                    UPDATE payment_transactions 
                    SET status = 'processing', gateway_transaction_id = ?, gateway_response = ?
                    WHERE transaction_id = ?
                ");
                $stmt->bind_param("ssi", $gatewayId, $response, $transactionId);
                $stmt->execute();

                error_log("Payment successful - Transaction ID: " . $transactionId . ", Gateway Ref: " . $gatewayId);

                return [
                    'success' => true,
                    'transaction_id' => $transactionId,
                    'gateway_reference' => $gatewayId,
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
                    'message' => $failureReason,
                    'debug_info' => [
                        'http_code' => $http_code,
                        'response' => $responseData,
                        'curl_error' => $curl_error
                    ]
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
     * Check payment status and send notifications
     * @param int $transactionId
     * @return array
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
                return ['success' => false, 'message' => 'Authentication failed'];
            }

            // Make status check request
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->apiUrl . "/transactions/find/" . $reference,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer $token"
                ),
            ));

            $response = curl_exec($curl);
            $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $curl_error = curl_error($curl);

            error_log("Status check HTTP Code: " . $http_code);
            error_log("Status check response: " . $response);

            curl_close($curl);

            if ($curl_error) {
                error_log("CURL error during status check: " . $curl_error);
                return ['success' => false, 'message' => 'Network error during status check'];
            }

            $paypackStatus = null;
            if ($http_code == 200) {
                $responseData = json_decode($response, true);

                if ($responseData && isset($responseData['status'])) {
                    $paypackStatus = $responseData['status'];
                    error_log("PayPack status: " . $paypackStatus);

                    // Map PayPack status to our system status
                    $newStatus = null;
                    switch (strtolower($paypackStatus)) {
                        case 'successful':
                        case 'completed':
                            $newStatus = 'completed';
                            break;
                        case 'failed':
                        case 'cancelled':
                            $newStatus = 'failed';
                            break;
                        case 'pending':
                        case 'processing':
                            $newStatus = 'processing';
                            break;
                    }

                    // Update transaction status if changed
                    if ($newStatus && $newStatus !== $result['status']) {
                        $stmt = $this->conn->prepare("
                            UPDATE payment_transactions 
                            SET status = ?, updated_at = CURRENT_TIMESTAMP, gateway_response = ?
                            WHERE transaction_id = ?
                        ");
                        $stmt->bind_param("ssi", $newStatus, $response, $transactionId);
                        $stmt->execute();

                        error_log("Transaction status updated to: " . $newStatus);

                        // If completed, send notifications and update rental
                        $notifications = null;
                        if ($newStatus === 'completed') {
                            $notifications = $this->handleCompletedPayment($transactionId);
                            error_log("Notification result: " . json_encode($notifications));
                        }

                        return [
                            'success' => true,
                            'status_updated' => true,
                            'new_status' => $newStatus,
                            'notifications_sent' => $notifications,
                            'message' => 'Payment status updated'
                        ];
                    } else {
                        return [
                            'success' => true,
                            'status_updated' => false,
                            'current_status' => $result['status'],
                            'message' => 'No status change'
                        ];
                    }
                }
            }

            error_log("Status check failed - HTTP Code: " . $http_code);
            return ['success' => false, 'message' => 'Failed to check payment status'];

        } catch (Exception $e) {
            error_log("Exception during status check: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error checking payment status: ' . $e->getMessage()];
        }
    }

    /**
     * Handle completed payment - update rental and send notifications
     * @param int $transactionId
     * @return array
     */
    private function handleCompletedPayment($transactionId)
    {
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

            // Update rental status to paid
            $stmt = $this->conn->prepare("
                UPDATE rentals 
                SET payment_status = 'paid', status = 'confirmed'
                WHERE rental_id = ?
            ");
            $stmt->bind_param("i", $data['rental_id']);
            $stmt->execute();

            // Send payment confirmation notifications
            $notificationResult = $this->messagingService->sendPaymentConfirmation([
                'email' => $data['email'],
                'phone' => $data['phone'],
                'name' => $data['full_name'],
                'amount' => $data['amount'],
                'payment_method' => 'Mobile Money (PayPack)',
                'transaction_id' => $data['gateway_transaction_id'],
                'rental_id' => $data['rental_id'],
                'admin_email' => 'admin@yourcarrental.com' // Configure your admin email
            ]);

            return $notificationResult;

        } catch (Exception $e) {
            error_log("Error handling completed payment: " . $e->getMessage());
            return null;
        }
    }

        /**
     * Format phone number for PayPack API
     * @param string $phoneNumber
     * @return string
     */
    private function formatPhoneForPayPack($phoneNumber)
    {
        error_log("=== PHONE FORMATTING FOR PAYPACK ===");
        error_log("Original input: '" . $phoneNumber . "'");

        // Use exact same logic as working test script
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
            $formatted = $phone; // Fallback - don't try to be too smart
        }

        error_log("Final formatted number: " . $formatted);
        return $formatted;
    }

    /**
     * Load PayPack settings from database
     */
       private function loadSettings()
    {
        error_log("=== LOADING PAYPACK SETTINGS ===");

        // Load from environment variables
        $this->clientId = $_ENV['PAYPACK_CLIENT_ID'] ?? getenv('PAYPACK_CLIENT_ID');
        $this->clientSecret = $_ENV['PAYPACK_CLIENT_SECRET'] ?? getenv('PAYPACK_CLIENT_SECRET');
        $this->apiUrl = $_ENV['PAYPACK_API_URL'] ?? getenv('PAYPACK_API_URL') ?? 'https://payments.paypack.rw/api';

        error_log("Final settings - Client ID: " . ($this->clientId ? 'SET' : 'NOT SET'));
        error_log("Final settings - Client Secret: " . ($this->clientSecret ? 'SET' : 'NOT SET'));
        error_log("Final settings - API URL: " . $this->apiUrl);
    }

    /**
     * Get PayPack authentication token
     * @return string|false
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
     * @param string $token
     * @param string $reference
     * @return array
     */
    private function checkPaypackEvents($token, $reference)
    {
        $curl = curl_init();
        $url = $this->apiUrl . "/events/transactions?ref=" . urlencode($reference);
        
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
        curl_close($curl);
        
        $decoded = json_decode($response, true);
        
        return [
            'http_code' => $http_code,
            'response' => $response,
            'decoded' => $decoded
        ];
    }

    /**
     * Get transaction details by ID
     * @param int $transactionId
     * @return array|null
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
     * @param int $transactionId
     * @return array
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
