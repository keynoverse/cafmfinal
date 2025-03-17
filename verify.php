<?php
require_once 'config/config.php';
require_once 'controllers/AuthController.php';

$auth = new AuthController($conn);
$message = '';
$status = '';

if (isset($_GET['token'])) {
    try {
        if ($auth->verifyEmail($_GET['token'])) {
            $status = 'success';
            $message = 'Your email has been verified successfully. You can now login to your account.';
        }
    } catch (Exception $e) {
        $status = 'error';
        $message = $e->getMessage();
    }
} else {
    $status = 'error';
    $message = 'Invalid verification link.';
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - CAFM System</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/dark-mode.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="verify-page">
    <div class="verify-container">
        <div class="verify-box">
            <h1>Email Verification</h1>
            
            <?php if ($status === 'success'): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo $message; ?>
                </div>
                <div class="text-center mt-4">
                    <a href="login.php" class="btn btn-primary">Login Now</a>
                </div>
            <?php else: ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo $message; ?>
                </div>
                <div class="text-center mt-4">
                    <a href="index.php" class="btn btn-secondary">Back to Home</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html> 