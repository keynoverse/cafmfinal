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
    error_log("Validation error: " . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'valid' => false,
        'message' => $e->getMessage()
    ]);
}
