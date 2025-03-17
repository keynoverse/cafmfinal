<?php
require_once 'models/UserModel.php';

class AuthController {
    private $userModel;
    
    public function __construct($conn) {
        $this->userModel = new UserModel($conn);
    }
    
    public function login($email, $password) {
        $user = $this->userModel->validateLogin($email, $password);
        
        if ($user) {
            // Start session and store user data
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role_id'];
            $_SESSION['user_email'] = $user['email'];
            
            return true;
        }
        return false;
    }
    
    public function logout() {
        // Destroy session
        session_destroy();
        // Clear session variables
        $_SESSION = array();
        
        return true;
    }
    
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    public function getCurrentUser() {
        if ($this->isLoggedIn()) {
            return $this->userModel->getUserByEmail($_SESSION['user_email']);
        }
        return null;
    }
} 