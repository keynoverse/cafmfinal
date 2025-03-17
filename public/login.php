<?php
require_once '../config/config.php';
require_once PROJECT_ROOT . '/controllers/AuthController.php';

$auth = new AuthController($conn);
if ($auth->isLoggedIn()) {
    header('Location: ../dashboard/index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        if ($auth->login($_POST['email'], $_POST['password'])) {
            header('Location: ../dashboard/index.php');
            exit;
        } else {
            $error = 'Invalid email or password';
        }
    } catch (Exception $e) {
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
            
            <form method="POST" action="">
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
                
                <button type="submit" class="btn btn-primary btn-block">
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
</body>
</html> 