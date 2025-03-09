<?php
require_once '../includes/AIChat.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$chatbot = new AIChat();
$response = $chatbot->getResponse($data['query']);

echo json_encode(['response' => $response]);
