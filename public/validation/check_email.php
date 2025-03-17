<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once dirname(dirname(__DIR__)) . '/config/config.php';

// Set headers for JSON response
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

try {
    // Get email from POST request
    $email = $_POST['email'] ?? '';

    if (empty($email)) {
        throw new Exception('Email is required');
    }

    // Check if email exists in database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    
    $response = [
        'available' => true,
        'message' => 'Email is available'
    ];

    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        $response = [
            'available' => false,
            'message' => 'This email is already registered'
        ];
    }

    echo json_encode($response);

} catch (Exception $e) {
    error_log("Email check error: " . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'available' => false,
        'message' => $e->getMessage()
    ]);
}
