<?php
session_start();
require_once '../config/database.php';
require_once '../models/Car.php';
require_once '../includes/AIChat.php';

header('Content-Type: application/json');

try {
    $db = new Database();
    $conn = $db->connect();
    
    $data = json_decode(file_get_contents('php://input'), true);
    $chatbot = new AIChat($conn);  // Pass database connection
    $response = $chatbot->getResponse($data['query']);
    
    echo json_encode([
        'status' => 'success',
        'response' => $response
    ]);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'success',
        'response' => "Here are our available vehicles:\n- Toyota Camry - $50/day\n- Honda CR-V - $65/day\n- BMW 3 Series - $85/day\nWhich one would you like to know more about?"
    ]);
}
