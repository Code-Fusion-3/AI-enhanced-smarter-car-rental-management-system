<?php
session_start();
require_once 'config/database.php';
require_once 'includes/PayPackHandler.php';

$transaction_id = $_GET['transaction_id'] ?? null;

if (!$transaction_id) {
    die('Please provide transaction_id parameter: debug_payment.php?transaction_id=4');
}

echo "<h2>Payment Debug for Transaction ID: $transaction_id</h2>";

$db = new Database();
$conn = $db->connect();

// 1. Check current transaction status
echo "<h3>1. Current Transaction Status:</h3>";
$stmt = $conn->prepare("
    SELECT pt.*, r.status as rental_status, r.rental_id, r.user_id
    FROM payment_transactions pt 
    LEFT JOIN rentals r ON pt.rental_id = r.rental_id 
    WHERE pt.transaction_id = ?
");
$stmt->bind_param("i", $transaction_id);
$stmt->execute();
$transaction = $stmt->get_result()->fetch_assoc();

if (!$transaction) {
    die("Transaction not found!");
}

echo "<pre>" . json_encode($transaction, JSON_PRETTY_PRINT) . "</pre>";

// 2. Check if rental exists
echo "<h3>2. Rental Details:</h3>";
$stmt = $conn->prepare("SELECT * FROM rentals WHERE rental_id = ?");
$stmt->bind_param("i", $transaction['rental_id']);
$stmt->execute();
$rental = $stmt->get_result()->fetch_assoc();
echo "<pre>" . json_encode($rental, JSON_PRETTY_PRINT) . "</pre>";

// 3. Check if user exists
echo "<h3>3. User Details:</h3>";
$stmt = $conn->prepare("SELECT user_id, full_name, email, phone FROM users WHERE user_id = ?");
$stmt->bind_param("i", $transaction['user_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
echo "<pre>" . json_encode($user, JSON_PRETTY_PRINT) . "</pre>";

// 4. Check existing payments
echo "<h3>4. Existing Payment Records:</h3>";
$stmt = $conn->prepare("SELECT * FROM payments WHERE rental_id = ?");
$stmt->bind_param("i", $transaction['rental_id']);
$stmt->execute();
$payments = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
echo "<pre>" . json_encode($payments, JSON_PRETTY_PRINT) . "</pre>";

// 5. Manual completion test
if (isset($_GET['complete']) && $_GET['complete'] == '1') {
    echo "<h3>5. Manual Completion Test:</h3>";
    
    try {
        // Start transaction
        $conn->begin_transaction();
        
        // Update transaction status
        $stmt = $conn->prepare("UPDATE payment_transactions SET status = 'completed' WHERE transaction_id = ?");
        $stmt->bind_param("i", $transaction_id);
        $result1 = $stmt->execute();
        echo "Transaction status update: " . ($result1 ? "✅ Success" : "❌ Failed: " . $conn->error) . "<br>";
        
        // Update rental status (removed payment_status since it doesn't exist)
        $stmt = $conn->prepare("UPDATE rentals SET status = 'active' WHERE rental_id = ?");
        $stmt->bind_param("i", $transaction['rental_id']);
        $result2 = $stmt->execute();
        echo "Rental status update: " . ($result2 ? "✅ Success" : "❌ Failed: " . $conn->error) . "<br>";
        
        // Insert payment record
        $stmt = $conn->prepare("
            INSERT INTO payments (rental_id, amount, payment_method, transaction_id, status, payment_date) 
            VALUES (?, ?, 'paypack', ?, 'completed', NOW())
            ON DUPLICATE KEY UPDATE status = 'completed', payment_date = NOW()
        ");
        $stmt->bind_param("ids", $transaction['rental_id'], $transaction['amount'], $transaction['gateway_transaction_id']);
        $result3 = $stmt->execute();
        echo "Payment record insert: " . ($result3 ? "✅ Success" : "❌ Failed: " . $conn->error) . "<br>";
        
        if ($result1 && $result2 && $result3) {
            $conn->commit();
            echo "<br><strong>✅ All updates completed successfully!</strong><br>";
        } else {
            $conn->rollback();
            echo "<br><strong>❌ Some updates failed, rolled back!</strong><br>";
        }
        
    } catch (Exception $e) {
        $conn->rollback();
        echo "<br><strong>❌ Exception occurred: " . $e->getMessage() . "</strong><br>";
    }
    
    // Show updated status
    echo "<h4>Updated Status:</h4>";
    $stmt = $conn->prepare("
        SELECT pt.status as transaction_status, r.status as rental_status
        FROM payment_transactions pt 
        JOIN rentals r ON pt.rental_id = r.rental_id 
        WHERE pt.transaction_id = ?
    ");
    $stmt->bind_param("i", $transaction_id);
    $stmt->execute();
    $updated = $stmt->get_result()->fetch_assoc();
    echo "<pre>" . json_encode($updated, JSON_PRETTY_PRINT) . "</pre>";
    
} else {
    echo "<h3>5. Manual Completion:</h3>";
    echo "<a href='?transaction_id=$transaction_id&complete=1' style='background: #007cba; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Complete Payment Manually</a>";
}

// 6. PayPack API test
echo "<h3>6. PayPack API Status Check:</h3>";
try {
    $paypack = new PaypackHandler($conn);
    $result = $paypack->checkPaymentStatus($transaction_id);
    echo "<pre>" . json_encode($result, JSON_PRETTY_PRINT) . "</pre>";
} catch (Exception $e) {
    echo "❌ PayPack API Error: " . $e->getMessage();
}
?>
