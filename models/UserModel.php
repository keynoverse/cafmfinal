<?php
class UserModel {
    private $db;

    public function __construct($conn) {
        $this->db = $conn;
    }

    public function createUser($data) {
        try {
            $sql = "INSERT INTO users (name, email, password_hash, mobile, user_type) 
                    VALUES (:name, :email, :password, :mobile, :user_type)";
            
            $stmt = $this->db->prepare($sql);
            
            // Hash password
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            
            return $stmt->execute([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $hashedPassword,
                'mobile' => $data['mobile'],
                'user_type' => $data['user_type']
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
        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }
        return false;
    }

    public function updateUser($userId, $data) {
        try {
            $sql = "UPDATE users SET name = :name, mobile = :mobile, updated_at = NOW() 
                    WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'name' => $data['name'],
                'mobile' => $data['mobile'],
                'id' => $userId
            ]);
        } catch (PDOException $e) {
            error_log("Error updating user: " . $e->getMessage());
            return false;
        }
    }

    public function verifyEmail($token) {
        try {
            $sql = "UPDATE users SET is_verified = 1, verification_token = NULL, 
                    updated_at = NOW() WHERE verification_token = :token";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['token' => $token]);
        } catch (PDOException $e) {
            error_log("Error verifying email: " . $e->getMessage());
            return false;
        }
    }
} 