<?php
class UserController {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function getUsers($filters = []) {
        $sql = "SELECT * FROM users";
        $params = [];
        
        // Apply filters
        if (!empty($filters)) {
            $whereClauses = [];
            
            if (isset($filters['user_type'])) {
                $whereClauses[] = "user_type = ?";
                $params[] = $filters['user_type'];
            }
            
            if (isset($filters['is_active'])) {
                $whereClauses[] = "is_active = ?";
                $params[] = $filters['is_active'] ? 1 : 0;
            }
            
            if (!empty($whereClauses)) {
                $sql .= " WHERE " . implode(" AND ", $whereClauses);
            }
        }
        
        $stmt = $this->conn->prepare($sql);
        
        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        
        return $users;
    }
    
    public function getUserById($userId) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return null;
        }
        
        return $result->fetch_assoc();
    }
    
    public function updateUser($userId, $data) {
        // Build the SQL query based on the data provided
        $updates = [];
        $params = [];
        $types = '';
        
        foreach ($data as $key => $value) {
            // Only update fields that have been provided
            if ($value !== null && $key !== 'id' && $key !== 'email') {
                $updates[] = "$key = ?";
                $params[] = $value;
                $types .= 's'; // Assuming all values are strings
            }
        }
        
        if (empty($updates)) {
            return false; // No fields to update
        }
        
        $sql = "UPDATE users SET " . implode(", ", $updates) . ", updated_at = NOW() WHERE id = ?";
        $params[] = $userId;
        $types .= 'i'; // userId is an integer
        
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param($types, ...$params);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error updating user: " . $e->getMessage());
            return false;
        }
    }
    
    public function createUser($data) {
        try {
            // Required fields
            if (empty($data['name']) || empty($data['email']) || empty($data['password']) || empty($data['user_type'])) {
                throw new Exception('Required fields missing');
            }
            
            // Check if email already exists
            $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $data['email']);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                throw new Exception('Email already exists');
            }
            
            // Generate verification token
            $verificationToken = bin2hex(random_bytes(32));
            
            // Hash password
            $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
            
            // Prepare SQL statement
            $sql = "INSERT INTO users (name, email, password_hash, mobile, user_type, verification_token) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssssss", 
                $data['name'], 
                $data['email'], 
                $passwordHash, 
                $data['mobile'] ?? '', 
                $data['user_type'],
                $verificationToken
            );
            
            if (!$stmt->execute()) {
                throw new Exception('Failed to create user');
            }
            
            $userId = $stmt->insert_id;
            
            // If additional data is provided
            if (isset($data['company']) || isset($data['department'])) {
                $this->updateUser($userId, [
                    'company' => $data['company'] ?? null,
                    'department' => $data['department'] ?? null
                ]);
            }
            
            return [
                'id' => $userId,
                'verification_token' => $verificationToken
            ];
            
        } catch (Exception $e) {
            error_log("Error creating user: " . $e->getMessage());
            throw $e;
        }
    }
    
    public function deleteUser($userId) {
        try {
            $stmt = $this->conn->prepare("UPDATE users SET is_active = 0, updated_at = NOW() WHERE id = ?");
            $stmt->bind_param("i", $userId);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error deleting user: " . $e->getMessage());
            return false;
        }
    }
} 