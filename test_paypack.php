<?php
// Test PayPack API connectivity
require_once 'config/database.php';
require_once 'includes/PayPackHandler.php';

echo "<h2>PayPack API Test</h2>";

// Test environment variables
echo "<h3>Environment Variables:</h3>";
echo "PAYPACK_CLIENT_ID: " . ($_ENV['PAYPACK_CLIENT_ID'] ?? 'NOT SET') . "<br>";
echo "PAYPACK_CLIENT_SECRET: " . ($_ENV['PAYPACK_CLIENT_SECRET'] ? 'SET' : 'NOT SET') . "<br>";
echo "PAYPACK_API_URL: " . ($_ENV['PAYPACK_API_URL'] ?? 'NOT SET') . "<br>";

// Test database connection
echo "<h3>Database Connection:</h3>";
try {
    $db = new Database();
    $conn = $db->connect();
    echo "✅ Database connected successfully<br>";
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "<br>";
    exit;
}

// Test PayPack handler initialization
echo "<h3>PayPack Handler:</h3>";
try {
    $paypack = new PaypackHandler($conn);
    echo "✅ PayPack handler initialized<br>";
} catch (Exception $e) {
    echo "❌ PayPack handler failed: " . $e->getMessage() . "<br>";
    exit;
}

// Test authentication
echo "<h3>PayPack Authentication:</h3>";
$reflection = new ReflectionClass($paypack);
$method = $reflection->getMethod('getPaypackToken');
$method->setAccessible(true);

try {
    $token = $method->invoke($paypack);
    if ($token) {
        echo "✅ Authentication successful<br>";
        echo "Token (first 20 chars): " . substr($token, 0, 20) . "...<br>";
    } else {
        echo "❌ Authentication failed<br>";
    }
} catch (Exception $e) {
    echo "❌ Authentication error: " . $e->getMessage() . "<br>";
}

// Test with a sample transaction if one exists
echo "<h3>Sample Transaction Test:</h3>";
$stmt = $conn->prepare("SELECT * FROM payment_transactions WHERE payment_method = 'paypack' ORDER BY created_at DESC LIMIT 1");
$stmt->execute();
$transaction = $stmt->get_result()->fetch_assoc();

if ($transaction) {
    echo "Testing with transaction ID: " . $transaction['transaction_id'] . "<br>";
    echo "Gateway reference: " . ($transaction['gateway_reference'] ?? 'N/A') . "<br>";
    
    try {
        $result = $paypack->checkPaymentStatus($transaction['transaction_id']);
        echo "Status check result: " . json_encode($result, JSON_PRETTY_PRINT) . "<br>";
    } catch (Exception $e) {
        echo "❌ Status check failed: " . $e->getMessage() . "<br>";
    }
} else {
    echo "No PayPack transactions found in database<br>";
}

echo "<h3>Error Log (last 50 lines):</h3>";
echo "<pre style='background: #f5f5f5; padding: 10px; max-height: 300px; overflow-y: scroll;'>";
$logFile = ini_get('error_log');
if ($logFile && file_exists($logFile)) {
    $lines = file($logFile);
    $lastLines = array_slice($lines, -50);
    echo htmlspecialchars(implode('', $lastLines));
} else {
    echo "Error log file not found or not accessible";
}
echo "</pre>";
?>