<?php
require_once '../../config/config.php';

// Set headers for JSON response
header('Content-Type: application/json');

try {
    // Get field and value from POST request
    $field = $_POST['field'] ?? '';
    $value = $_POST['value'] ?? '';

    if (empty($field) || empty($value)) {
        throw new Exception('Field and value are required');
    }

    $response = ['valid' => false, 'message' => ''];

    switch ($field) {
        case 'email':
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $response['message'] = 'Please enter a valid email address';
            } else {
                $response['valid'] = true;
            }
            break;

        case 'mobile':
            // Remove any non-digit characters
            $cleanMobile = preg_replace('/[^0-9]/', '', $value);
            
            // Check if mobile number is between 10 and 15 digits
            if (strlen($cleanMobile) < 10 || strlen($cleanMobile) > 15) {
                $response['message'] = 'Mobile number must be between 10 and 15 digits';
            } else {
                $response['valid'] = true;
            }
            break;

        default:
            throw new Exception('Invalid field type');
    }

    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'valid' => false,
        'message' => $e->getMessage()
    ]);
}
