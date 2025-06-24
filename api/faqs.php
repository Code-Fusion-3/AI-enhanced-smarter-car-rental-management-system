<?php
require_once '../config/database.php';
header('Content-Type: application/json');

try {
    $db = new Database();
    $conn = $db->connect();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Fetch all active FAQs
        $sql = "SELECT id, question FROM faqs WHERE active = 1 ORDER BY id ASC";
        $result = $conn->query($sql);
        $faqs = [];
        while ($row = $result->fetch_assoc()) {
            $faqs[] = $row;
        }
        echo json_encode(['status' => 'success', 'faqs' => $faqs]);
        exit;
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Fetch answer for a specific FAQ by ID
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['id'])) {
            echo json_encode(['status' => 'error', 'message' => 'FAQ ID required']);
            exit;
        }
        $stmt = $conn->prepare('SELECT answer FROM faqs WHERE id = ? AND active = 1');
        $stmt->bind_param('i', $data['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            echo json_encode(['status' => 'success', 'answer' => $row['answer']]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'FAQ not found']);
        }
        exit;
    }

    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Server error']);
}