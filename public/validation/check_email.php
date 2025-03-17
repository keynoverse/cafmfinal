<?php
require_once '../../config/config.php';

// Set headers for JSON response
header('Content-Type: application/json');

try {
    // Get email from POST request
    $email = $_POST['email'] ?? '';

    if (empty($email)) {
        throw new Exception('Email is required');
    }

    // Check if email exists in database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    
    $response = [
        'available' => true,
        'message' => 'Email is available'
    ];

    if ($stmt->rowCount() > 0) {
        $response = [
            'available' => false,
            'message' => 'This email is already registered'
        ];
    }

    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'available' => false,
        'message' => $e->getMessage()
    ]);
}
