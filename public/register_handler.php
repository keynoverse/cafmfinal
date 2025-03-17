<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once '../includes/validation.php';
require_once '../includes/register_handler.php';

header('Content-Type: application/json');

try {
    // Validate request
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }
    
    // Initialize validator
    $validator = new Validator($_POST);
    
    // Validate input
    if (!$validator->validate()) {
        echo json_encode([
            'success' => false,
            'errors' => $validator->getErrors()
        ]);
        exit;
    }
    
    // Process registration
    $result = handleRegistration($_POST, $_FILES);
    
    if ($result['success']) {
        // Store success message in session
        $_SESSION['registration_message'] = $result['message'];
        $_SESSION['registration_type'] = $_POST['user_type'];
    }
    
    echo json_encode($result);
    
} catch (Exception $e) {
    error_log("Registration error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred during registration. Please try again.'
    ]);
} 