<?php
require_once '../config/config.php';
require_once '../includes/functions.php';
require_once '../includes/validation.php';

header('Content-Type: application/json');

try {
    // Validate request method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Create uploads directory if it doesn't exist
    $uploadDir = '../uploads/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Get user type from form ID
    $userType = str_replace('Form', '', $_POST['formId']);
    
    // Basic validation
    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['mobile'])) {
        throw new Exception('Please fill in all required fields.');
    }
    
    // Validate email format
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format.');
    }
    
    // Validate mobile number
    $cleanMobile = preg_replace('/[^0-9]/', '', $_POST['mobile']);
    if (strlen($cleanMobile) < 10 || strlen($cleanMobile) > 15) {
        throw new Exception('Mobile number must be between 10 and 15 digits.');
    }
    
    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->execute(['email' => $_POST['email']]);
    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        throw new Exception('Email already registered.');
    }

    // Handle file uploads
    $uploadedFiles = [];
    if (!empty($_FILES)) {
        foreach ($_FILES as $fieldName => $fileInfo) {
            if ($fileInfo['error'] === UPLOAD_ERR_OK) {
                // Validate file type
                $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
                $fileType = mime_content_type($fileInfo['tmp_name']);
                if (!in_array($fileType, $allowedTypes)) {
                    throw new Exception("Invalid file type for {$fieldName}. Only JPG, PNG, and PDF files are allowed.");
                }
                
                // Validate file size (5MB limit)
                if ($fileInfo['size'] > 5 * 1024 * 1024) {
                    throw new Exception("File size for {$fieldName} exceeds 5MB limit.");
                }
                
                $fileName = time() . '_' . basename($fileInfo['name']);
                $uploadPath = $uploadDir . $fileName;
                
                if (!move_uploaded_file($fileInfo['tmp_name'], $uploadPath)) {
                    throw new Exception("Failed to upload {$fieldName}");
                }
                
                $uploadedFiles[$fieldName] = $fileName;
            }
        }
    }

    // Generate verification token
    $verificationToken = bin2hex(random_bytes(32));
    
    // Hash password (if provided)
    $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    // Prepare user data
    $userData = [
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'mobile' => $_POST['mobile'],
        'user_type' => $userType,
        'verification_token' => $verificationToken,
        'password_hash' => $password,
        'is_verified' => 0,
        'is_active' => 1
    ];

    // Additional data based on user type
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
                'categories' => $_POST['categories'] ?? [],
                'trade_license' => $uploadedFiles['trade_license'] ?? null
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
                'auth_document' => $uploadedFiles['auth_document'] ?? null
            ];
            break;
            
        case 'manager':
            $additionalData = [
                'department' => $_POST['department'],
                'employee_id' => $_POST['employee_id'],
                'access_level' => $_POST['access_level']
            ];
            break;
    }

    // Begin transaction
    $conn->beginTransaction();

    // Insert user
    $sql = "INSERT INTO users (name, email, mobile, user_type, verification_token, password_hash, is_verified, is_active, additional_data, created_at) 
            VALUES (:name, :email, :mobile, :user_type, :verification_token, :password_hash, :is_verified, :is_active, :additional_data, NOW())";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'name' => $userData['name'],
        'email' => $userData['email'],
        'mobile' => $userData['mobile'],
        'user_type' => $userData['user_type'],
        'verification_token' => $userData['verification_token'],
        'password_hash' => $userData['password_hash'],
        'is_verified' => $userData['is_verified'],
        'is_active' => $userData['is_active'],
        'additional_data' => json_encode($additionalData)
    ]);

    $userId = $conn->lastInsertId();

    // Create registration request
    $sql = "INSERT INTO registration_requests (user_id, status, created_at) VALUES (:user_id, 'pending', NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['user_id' => $userId]);

    $conn->commit();

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
    
    mail($to, $subject, $message, $headers);

    echo json_encode([
        'success' => true,
        'message' => 'Registration successful! Please check your email for verification.'
    ]);

} catch (Exception $e) {
    if (isset($conn) && $conn->inTransaction()) {
        $conn->rollBack();
    }
    
    error_log("Registration error: " . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}