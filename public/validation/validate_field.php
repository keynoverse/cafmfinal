<?php
require_once __DIR__ . '/../../config/config.php';

header('Content-Type: application/json');

try {
    $field = $_POST['field'] ?? '';
    $value = $_POST['value'] ?? '';
    
    $response = ['valid' => true, 'message' => ''];
    
    switch ($field) {
        case 'email':
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $response = ['valid' => false, 'message' => 'Invalid email format'];
            }
            break;
            
        case 'mobile':
            if (!preg_match('/^[0-9]{10,15}$/', preg_replace('/[^0-9]/', '', $value))) {
                $response = ['valid' => false, 'message' => 'Invalid mobile number'];
            }
            break;
    }
    
    echo json_encode($response);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['valid' => false, 'message' => $e->getMessage()]);
}
