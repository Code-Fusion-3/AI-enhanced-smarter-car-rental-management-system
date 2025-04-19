<?php
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function generateToken() {
    return bin2hex(random_bytes(32));
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Format date
function formatDate($date) {
    return date('F j, Y', strtotime($date));
}

// Calculate rental duration in days
function calculateDays($startDate, $endDate) {
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);
    $interval = $start->diff($end);
    return $interval->days;
}

// Format currency
function formatCurrency($amount) {
    return '$' . number_format($amount, 2);
}

// Log system activity
function logActivity($db, $userId, $action, $details = null, $ipAddress = null) {
    if (!$ipAddress) {
        $ipAddress = $_SERVER['REMOTE_ADDR'];
    }
    
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    
    $sql = "INSERT INTO system_logs (user_id, action, details, ip_address, user_agent) 
            VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param("issss", $userId, $action, $details, $ipAddress, $userAgent);
    $stmt->execute();
}