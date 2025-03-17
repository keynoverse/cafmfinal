<?php
require_once '../config/config.php';
require_once '../controllers/AuthController.php';

$auth = new AuthController($conn);
if ($auth->isLoggedIn()) {
    header('Location: ../dashboard/index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($auth->login($_POST['email'], $_POST['password'])) {
        header('Location: ../dashboard/index.php');
        exit;
    } else {
        $error = 'Invalid email or password';
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
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-box">
            <h1>Login</h1>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
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
                    Login
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