<?php
require_once 'config.php';
require_once 'functions.php';

function handleRegistration($data, $files) {
    global $conn;
    
    try {
        $conn->beginTransaction();
        
        // Basic user data
        $sql = "INSERT INTO users (name, email, mobile, role_id) 
                VALUES (:name, :email, :mobile, :role_id)";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'role_id' => getRoleIdByType($data['user_type'])
        ]);
        
        $userId = $conn->lastInsertId();
        
        // Handle specific user type data
        switch($data['user_type']) {
            case 'tenant':
                handleTenantRegistration($userId, $data, $files);
                break;
            case 'service_provider':
                handleServiceProviderRegistration($userId, $data, $files);
                break;
            // Add other cases for different user types
        }
        
        // Create registration request
        $sql = "INSERT INTO registration_requests (user_id) VALUES (:user_id)";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        
        $conn->commit();
        return ['success' => true, 'message' => 'Registration successful. Awaiting approval.'];
        
    } catch (Exception $e) {
        $conn->rollBack();
        error_log("Registration error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Registration failed. Please try again.'];
    }
}

function handleTenantRegistration($userId, $data, $files) {
    global $conn;
    
    // Handle file uploads
    $tradeLicensePath = '';
    $leaseContractPath = '';
    
    if (isset($files['trade_license'])) {
        $tradeLicensePath = uploadFile($files['trade_license'], 'licenses');
    }
    
    if (isset($files['lease_contract'])) {
        $leaseContractPath = uploadFile($files['lease_contract'], 'documents');
    }
    
    $sql = "INSERT INTO company_details (
                user_id, company_name, company_type, department_name,
                trade_license_path, lease_contract_path
            ) VALUES (
                :user_id, :company_name, :company_type, :department_name,
                :trade_license_path, :lease_contract_path
            )";
            
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'user_id' => $userId,
        'company_name' => $data['company_name'] ?? null,
        'company_type' => $data['company_type'],
        'department_name' => $data['department'] ?? null,
        'trade_license_path' => $tradeLicensePath,
        'lease_contract_path' => $leaseContractPath
    ]);
}

function handleServiceProviderRegistration($userId, $data, $files) {
    global $conn;
    
    // Handle trade license upload
    $tradeLicensePath = '';
    if (isset($files['trade_license'])) {
        $tradeLicensePath = uploadFile($files['trade_license'], 'licenses');
    }
    
    // Insert company details
    $sql = "INSERT INTO company_details (
                user_id, company_name, trade_license_path
            ) VALUES (
                :user_id, :company_name, :trade_license_path
            )";
            
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'user_id' => $userId,
        'company_name' => $data['company_name'],
        'trade_license_path' => $tradeLicensePath
    ]);
    
    // Handle service categories
    if (isset($data['categories'])) {
        $sql = "INSERT INTO service_provider_categories (provider_id, category_id) 
                VALUES (:provider_id, :category_id)";
        $stmt = $conn->prepare($sql);
        
        foreach ($data['categories'] as $categoryId) {
            $stmt->execute([
                'provider_id' => $userId,
                'category_id' => $categoryId
            ]);
        }
    }
}

function uploadFile($file, $directory) {
    $targetDir = "../uploads/" . $directory . "/";
    $fileName = uniqid() . '_' . basename($file['name']);
    $targetPath = $targetDir . $fileName;
    
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return $directory . '/' . $fileName;
    }
    
    throw new Exception("File upload failed");
}

function getRoleIdByType($userType) {
    $roles = [
        'tenant' => 3,
        'service_provider' => 4,
        'client_enquiry' => 5,
        'landlord' => 1,
        'manager' => 2
    ];
    
    return $roles[$userType] ?? 3; // Default to tenant if not found
} 