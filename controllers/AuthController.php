<?php
require_once PROJECT_ROOT . '/models/UserModel.php';

class AuthController {
    private $userModel;
    private $conn;
    private $sessionTimeout = 3600; // 1 hour

    public function __construct($conn) {
        $this->conn = $conn;
        $this->userModel = new UserModel($conn);
        session_start();
    }
    
    public function login($email, $password) {
        try {
            // Get user by email
            $stmt = $this->conn->prepare("
                SELECT id, name, email, password_hash, user_type, is_verified, is_active 
                FROM users 
                WHERE email = :email
            ");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                error_log("Login failed: User not found - " . $email);
                return false;
            }

            // Verify password
            if (!password_verify($password, $user['password_hash'])) {
                error_log("Login failed: Invalid password for user - " . $email);
                return false;
            }

            // Check if user is verified
            if (!$user['is_verified']) {
                error_log("Login failed: User not verified - " . $email);
                return false;
            }

            // Check if user is active
            if (!$user['is_active']) {
                error_log("Login failed: User not active - " . $email);
                return false;
            }

            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_type'] = $user['user_type'];
            $_SESSION['last_activity'] = time();

            // Update last login timestamp
            $stmt = $this->conn->prepare("
                UPDATE users 
                SET last_login = NOW() 
                WHERE id = :id
            ");
            $stmt->execute(['id' => $user['id']]);

            error_log("Login successful for user: " . $email);
            return true;

        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            return false;
        }
    }
    
    public function logout() {
        // Clear all session variables
        $_SESSION = array();

        // Destroy the session cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        // Destroy the session
        session_destroy();
    }
    
    public function isLoggedIn() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['last_activity'])) {
            return false;
        }

        // Check session timeout
        if (time() - $_SESSION['last_activity'] > $this->sessionTimeout) {
            $this->logout();
            return false;
        }

        $_SESSION['last_activity'] = time();
        return true;
    }
    
    public function getCurrentUser() {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }
        
        try {
            $stmt = $this->conn->prepare("
                SELECT id, name, email, user_type, is_verified, is_active, last_login, created_at 
                FROM users 
                WHERE id = :id
            ");
            $stmt->execute(['id' => $_SESSION['user_id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user) {
                return null;
            }
            
            return $user;
        } catch (Exception $e) {
            error_log("Error fetching current user: " . $e->getMessage());
            return [
                'id' => $_SESSION['user_id'],
                'name' => $_SESSION['user_name'] ?? 'Unknown User',
                'email' => $_SESSION['user_email'] ?? '',
                'user_type' => $_SESSION['user_type'] ?? 'Unknown'
            ];
        }
    }

    public function verifyEmail($token) {
        $stmt = $this->conn->prepare("
            SELECT id, is_verified 
            FROM users 
            WHERE verification_token = ?
        ");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception('Invalid verification token.');
        }

        $user = $result->fetch_assoc();

        if ($user['is_verified']) {
            throw new Exception('Email already verified.');
        }

        // Update user verification status
        $stmt = $this->conn->prepare("
            UPDATE users 
            SET is_verified = 1, 
                verification_token = NULL, 
                verified_at = NOW() 
            WHERE id = ?
        ");
        $stmt->bind_param("i", $user['id']);
        
        if (!$stmt->execute()) {
            throw new Exception('Failed to verify email.');
        }

        return true;
    }

    public function resetPassword($email) {
        $stmt = $this->conn->prepare("SELECT id, name FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception('Email not found.');
        }

        $user = $result->fetch_assoc();
        $resetToken = bin2hex(random_bytes(32));
        $resetExpiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Save reset token
        $stmt = $this->conn->prepare("
            UPDATE users 
            SET reset_token = ?, 
                reset_token_expires = ? 
            WHERE id = ?
        ");
        $stmt->bind_param("ssi", $resetToken, $resetExpiry, $user['id']);
        
        if (!$stmt->execute()) {
            throw new Exception('Failed to initiate password reset.');
        }

        // Send reset email
        $resetLink = BASE_URL . '/reset-password.php?token=' . $resetToken;
        $to = $email;
        $subject = 'Reset Your CAFM Password';
        $message = "
            <html>
            <head>
                <title>Password Reset</title>
            </head>
            <body>
                <h2>Password Reset Request</h2>
                <p>Dear {$user['name']},</p>
                <p>We received a request to reset your password. Click the link below to set a new password:</p>
                <p><a href='{$resetLink}'>Reset Password</a></p>
                <p>This link will expire in 1 hour.</p>
                <p>If you did not request this reset, please ignore this email.</p>
                <p>Best regards,<br>CAFM Team</p>
            </body>
            </html>
        ";

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: CAFM System <noreply@cafm.com>\r\n";

        if (!mail($to, $subject, $message, $headers)) {
            error_log("Failed to send password reset email to: $to");
            throw new Exception('Failed to send password reset email.');
        }

        return true;
    }

    public function validateResetToken($token) {
        $stmt = $this->conn->prepare("
            SELECT id 
            FROM users 
            WHERE reset_token = ? 
            AND reset_token_expires > NOW()
        ");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        
        return $stmt->get_result()->num_rows > 0;
    }

    public function updatePassword($token, $newPassword) {
        $stmt = $this->conn->prepare("
            SELECT id 
            FROM users 
            WHERE reset_token = ? 
            AND reset_token_expires > NOW()
        ");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception('Invalid or expired reset token.');
        }

        $user = $result->fetch_assoc();
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update password and clear reset token
        $stmt = $this->conn->prepare("
            UPDATE users 
            SET password_hash = ?,
                reset_token = NULL,
                reset_token_expires = NULL,
                updated_at = NOW()
            WHERE id = ?
        ");
        $stmt->bind_param("si", $passwordHash, $user['id']);
        
        if (!$stmt->execute()) {
            throw new Exception('Failed to update password.');
        }

        return true;
    }

    public function changePassword($userId, $currentPassword, $newPassword) {
        $stmt = $this->conn->prepare("
            SELECT password_hash 
            FROM users 
            WHERE id = ?
        ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception('User not found.');
        }

        $user = $result->fetch_assoc();

        if (!password_verify($currentPassword, $user['password_hash'])) {
            throw new Exception('Current password is incorrect.');
        }

        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare("
            UPDATE users 
            SET password_hash = ?,
                updated_at = NOW()
            WHERE id = ?
        ");
        $stmt->bind_param("si", $passwordHash, $userId);
        
        if (!$stmt->execute()) {
            throw new Exception('Failed to change password.');
        }

        return true;
    }
} 