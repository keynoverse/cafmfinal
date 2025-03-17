<?php
require_once __DIR__ . '/../../config/config.php';

header('Content-Type: application/json');

try {
    $email = $_POST['email'] ?? '';
    
    if (empty($email)) {
        throw new Exception('Email is required');
    }
    
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $exists = $stmt->fetchColumn() > 0;
    
    echo json_encode([
        'available' => !$exists,
        'message' => $exists ? 'Email already registered' : 'Email available'
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['available' => false, 'message' => $e->getMessage()]);
}
