<?php
class UserModel {
    private $db;

    public function __construct($conn) {
        $this->db = $conn;
    }

    public function createUser($data) {
        try {
            $sql = "INSERT INTO users (name, email, password, mobile, company, 
                    department, role_id, is_expo_city) 
                    VALUES (:name, :email, :password, :mobile, :company, 
                    :department, :role_id, :is_expo_city)";
            
            $stmt = $this->db->prepare($sql);
            
            // Hash password
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            
            return $stmt->execute([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $hashedPassword,
                'mobile' => $data['mobile'],
                'company' => $data['company'],
                'department' => $data['department'],
                'role_id' => $data['role_id'],
                'is_expo_city' => $data['is_expo_city'] ?? false
            ]);
        } catch (PDOException $e) {
            error_log("Error creating user: " . $e->getMessage());
            return false;
        }
    }

    public function getUserByEmail($email) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching user: " . $e->getMessage());
            return false;
        }
    }

    public function validateLogin($email, $password) {
        $user = $this->getUserByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
} 