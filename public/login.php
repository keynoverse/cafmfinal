<?php
require_once '../config/config.php';
require_once PROJECT_ROOT . '/controllers/AuthController.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$auth = new AuthController($conn);
if ($auth->isLoggedIn()) {
    header('Location: ../dashboard/index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Log login attempt
        error_log("Login attempt for email: " . ($_POST['email'] ?? 'not set'));
        
        // Validate input
        if (empty($_POST['email']) || empty($_POST['password'])) {
            throw new Exception('Email and password are required');
        }

        if ($auth->login($_POST['email'], $_POST['password'])) {
            error_log("Login successful, redirecting to dashboard");
            header('Location: ../dashboard/index.php');
            exit;
        } else {
            error_log("Login failed for user: " . $_POST['email']);
            $error = 'Invalid email or password';
        }
    } catch (Exception $e) {
        error_log("Login error: " . $e->getMessage());
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CAFM System</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/dark-mode.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--bg-color, #1a1a1a) 0%, #2c3e50 100%);
        }
        
        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }
        
        .login-box {
            background: rgba(45, 45, 45, 0.7);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.37);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .login-box h1 {
            text-align: center;
            margin-bottom: 30px;
            color: var(--text-color, #e9ecef);
        }
        
        .login-logo {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .login-logo img {
            max-width: 150px;
            height: auto;
        }
        
        .form-footer {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
            color: var(--secondary-color, #adb5bd);
        }
        
        .form-footer a {
            color: var(--accent-color, #00c6ff);
            text-decoration: none;
        }
        
        .form-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-box">
            <div class="login-logo">
                <img src="../assets/images/logo.png" alt="CAFM Logo">
            </div>
            
            <h1>Login</h1>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="" id="loginForm">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           required 
                           class="form-control"
                           placeholder="Enter your email">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required 
                           class="form-control"
                           placeholder="Enter your password">
                </div>
                
                <button type="submit" class="btn btn-primary btn-block" id="loginButton">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>

                <div class="form-footer">
                    <p>Don't have an account? <a href="register.php">Register</a></p>
                    <p><a href="forgot-password.php">Forgot Password?</a></p>
                </div>
            </form>
        </div>
    </div>

    <script src="../assets/js/main.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const loginForm = document.getElementById('loginForm');
        const loginButton = document.getElementById('loginButton');

        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Disable the button to prevent double submission
            loginButton.disabled = true;
            loginButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging in...';

            // Get form data
            const formData = new FormData(this);

            // Submit the form using fetch
            fetch(loginForm.action || window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(html => {
                // Check if the response contains a success redirect
                if (html.includes('Location: ../dashboard/index.php')) {
                    window.location.href = '../dashboard/index.php';
                } else {
                    // If there's an error, the page will be reloaded with the error message
                    document.documentElement.innerHTML = html;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Re-enable the button on error
                loginButton.disabled = false;
                loginButton.innerHTML = '<i class="fas fa-sign-in-alt"></i> Login';
                // Show error message
                const errorDiv = document.createElement('div');
                errorDiv.className = 'alert alert-danger';
                errorDiv.textContent = 'An error occurred. Please try again.';
                loginForm.insertBefore(errorDiv, loginForm.firstChild);
            });
        });
    });
    </script>
</body>
</html> 