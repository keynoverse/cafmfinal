<?php
require_once '../config/config.php';
require_once '../controllers/AuthController.php';

header('Content-Type: application/json');

try {
    $auth = new AuthController($conn);
    
    // Get user type from form ID
    $userType = str_replace('Form', '', $_POST['formId']);
    
    // Basic validation
    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['mobile']) || empty($_POST['password'])) {
        throw new Exception('Please fill in all required fields including password.');
    }
    
    // Validate email format
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format.');
    }
    
    // Validate password
    if (strlen($_POST['password']) < 8) {
        throw new Exception('Password must be at least 8 characters long.');
    }
    
    // Validate password confirmation
    if ($_POST['password'] !== $_POST['confirm_password']) {
        throw new Exception('Passwords do not match.');
    }
    
    // Validate mobile number
    $cleanMobile = preg_replace('/[^0-9]/', '', $_POST['mobile']);
    if (strlen($cleanMobile) < 10 || strlen($cleanMobile) > 15) {
        throw new Exception('Mobile number must be between 10 and 15 digits.');
    }
    
    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->bindValue(':email', $_POST['email']);
    $stmt->execute();
    if ($stmt->fetch(PDO::FETCH_ASSOC)) {
        throw new Exception('Email already registered.');
    }
    
    // Begin transaction
    $conn->beginTransaction();
    
    try {
        // Validate user type specific fields
        switch ($userType) {
            case 'tenant':
                if (empty($_POST['company_type'])) {
                    throw new Exception('Company type is required.');
                }
                if ($_POST['company_type'] === 'non_expo' && empty($_POST['company_name'])) {
                    throw new Exception('Company name is required for non-Expo companies.');
                }
                if ($_POST['company_type'] === 'expo' && empty($_POST['department'])) {
                    throw new Exception('Department is required for Expo companies.');
                }
                break;
                
            case 'service_provider':
                if (empty($_POST['company_name'])) {
                    throw new Exception('Company name is required.');
                }
                if (empty($_POST['categories'])) {
                    throw new Exception('Please select at least one service category.');
                }
                break;
                
            case 'client_enquiry':
                if (empty($_POST['department']) || empty($_POST['employee_id'])) {
                    throw new Exception('Department and Employee ID are required.');
                }
                break;
                
            case 'landlord':
                if (empty($_POST['organization']) || empty($_POST['position'])) {
                    throw new Exception('Organization and Position are required.');
                }
                break;
                
            case 'manager':
                if (empty($_POST['department']) || empty($_POST['employee_id']) || empty($_POST['access_level'])) {
                    throw new Exception('Department, Employee ID, and Access Level are required.');
                }
                break;
                
            default:
                throw new Exception('Invalid user type.');
        }
        
        // Handle file uploads
        $uploadedFiles = [];
        if (!empty($_FILES)) {
            $uploadDir = '../uploads/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
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
        
        // Hash password
        $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        // Insert user data
        $stmt = $conn->prepare("
            INSERT INTO users (name, email, mobile, user_type, verification_token, password_hash, additional_data, created_at)
            VALUES (:name, :email, :mobile, :user_type, :verification_token, :password_hash, :additional_data, NOW())
        ");
        
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
                    'categories' => is_array($_POST['categories']) ? implode(',', $_POST['categories']) : $_POST['categories'],
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
        
        $additionalDataJson = json_encode($additionalData);
        
        $stmt->bindValue(':name', $_POST['name']);
        $stmt->bindValue(':email', $_POST['email']);
        $stmt->bindValue(':mobile', $_POST['mobile']);
        $stmt->bindValue(':user_type', $userType);
        $stmt->bindValue(':verification_token', $verificationToken);
        $stmt->bindValue(':password_hash', $passwordHash);
        $stmt->bindValue(':additional_data', $additionalDataJson);
        
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
        
        // Commit the transaction
        $conn->commit();
        
        echo json_encode([
            'success' => true,
            'message' => 'Registration successful! Please check your email for verification.'
        ]);
    } catch (Exception $e) {
        // Rollback the transaction if an error occurs
        $conn->rollBack();
        throw $e;
    }
} catch (Exception $e) {
    error_log("Registration error: " . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} 