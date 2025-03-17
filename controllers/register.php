<?php
require_once '../config/config.php';
require_once '../controllers/AuthController.php';

header('Content-Type: application/json');

try {
    $auth = new AuthController($conn);
    
    // Get user type from form ID
    $userType = str_replace('Form', '', $_POST['formId']);
    
    // Basic validation
    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['mobile'])) {
        throw new Exception('Please fill in all required fields.');
    }
    
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format.');
    }
    
    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $_POST['email']);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        throw new Exception('Email already registered.');
    }
    
    // Handle file uploads
    $uploadedFiles = [];
    if (!empty($_FILES)) {
        foreach ($_FILES as $fieldName => $fileInfo) {
            if ($fileInfo['error'] === UPLOAD_ERR_OK) {
                $fileName = time() . '_' . basename($fileInfo['name']);
                $uploadPath = '../uploads/' . $fileName;
                
                if (!move_uploaded_file($fileInfo['tmp_name'], $uploadPath)) {
                    throw new Exception('Failed to upload ' . $fieldName);
                }
                
                $uploadedFiles[$fieldName] = $fileName;
            }
        }
    }
    
    // Prepare additional data based on user type
    $additionalData = [];
    switch ($userType) {
        case 'tenant':
            $additionalData = [
                'company_type' => $_POST['company_type'],
                'company_name' => $_POST['company_type'] === 'non_expo' ? $_POST['company_name'] : null,
                'department' => $_POST['company_type'] === 'expo' ? $_POST['department'] : null,
                'trade_license' => $uploadedFiles['trade_license'] ?? null,
                'lease_contract' => $uploadedFiles['lease_contract'] ?? null
            ];
            break;
            
        case 'service_provider':
            $additionalData = [
                'company_name' => $_POST['company_name'],
                'categories' => implode(',', $_POST['categories']),
                'trade_license' => $uploadedFiles['trade_license']
            ];
            break;
            
        case 'client_enquiry':
            $additionalData = [
                'department' => $_POST['department'],
                'employee_id' => $_POST['employee_id']
            ];
            break;
            
        case 'landlord':
            $additionalData = [
                'organization' => $_POST['organization'],
                'position' => $_POST['position'],
                'auth_document' => $uploadedFiles['auth_document']
            ];
            break;
            
        case 'manager':
            $additionalData = [
                'department' => $_POST['department'],
                'employee_id' => $_POST['employee_id'],
                'access_level' => $_POST['access_level']
            ];
            break;
            
        default:
            throw new Exception('Invalid user type.');
    }
    
    // Generate verification token
    $verificationToken = bin2hex(random_bytes(32));
    
    // Insert user data
    $stmt = $conn->prepare("
        INSERT INTO users (name, email, mobile, user_type, verification_token, additional_data, created_at)
        VALUES (?, ?, ?, ?, ?, ?, NOW())
    ");
    
    $additionalDataJson = json_encode($additionalData);
    $stmt->bind_param("ssssss", 
        $_POST['name'],
        $_POST['email'],
        $_POST['mobile'],
        $userType,
        $verificationToken,
        $additionalDataJson
    );
    
    if (!$stmt->execute()) {
        throw new Exception('Failed to create user account.');
    }
    
    // Send verification email
    $verificationLink = BASE_URL . '/verify.php?token=' . $verificationToken;
    $to = $_POST['email'];
    $subject = 'Verify Your CAFM Account';
    $message = "
        <html>
        <head>
            <title>Email Verification</title>
        </head>
        <body>
            <h2>Welcome to CAFM System</h2>
            <p>Dear {$_POST['name']},</p>
            <p>Thank you for registering with our CAFM system. Please click the link below to verify your email address:</p>
            <p><a href='{$verificationLink}'>Verify Email Address</a></p>
            <p>If you did not create this account, please ignore this email.</p>
            <p>Best regards,<br>CAFM Team</p>
        </body>
        </html>
    ";
    
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: CAFM System <noreply@cafm.com>\r\n";
    
    if (!mail($to, $subject, $message, $headers)) {
        // Log email sending failure but don't throw exception
        error_log("Failed to send verification email to: $to");
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Registration successful! Please check your email for verification.'
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} 