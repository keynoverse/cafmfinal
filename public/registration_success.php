<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$message = $_SESSION['registration_message'] ?? null;
$type = $_SESSION['registration_type'] ?? null;

// Clear session messages
unset($_SESSION['registration_message']);
unset($_SESSION['registration_type']);

include '../includes/header.php';
?>

<div class="success-container">
    <div class="success-box">
        <i class="fas fa-check-circle"></i>
        <h1>Registration Successful!</h1>
        
        <?php if ($message): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php else: ?>
            <p class="message">Your registration has been submitted successfully.</p>
        <?php endif; ?>
        
        <?php if ($type === 'tenant' || $type === 'service_provider'): ?>
            <p class="info">Your application is under review. You will receive an email once your account is approved.</p>
        <?php endif; ?>
        
        <div class="action-buttons">
            <a href="login.php" class="btn btn-primary">Proceed to Login</a>
            <a href="index.php" class="btn btn-secondary">Back to Home</a>
        </div>
    </div>
</div>

<style>
.success-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f4f6f9;
    padding: 20px;
}

.success-box {
    background: white;
    padding: 40px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    text-align: center;
    max-width: 500px;
    width: 100%;
}

.success-box i {
    font-size: 64px;
    color: #28a745;
    margin-bottom: 20px;
}

.success-box h1 {
    color: #333;
    margin-bottom: 20px;
}

.message {
    font-size: 18px;
    color: #666;
    margin-bottom: 15px;
}

.info {
    background: #e8f4ff;
    padding: 15px;
    border-radius: 4px;
    margin: 20px 0;
    color: #0056b3;
}

.action-buttons {
    margin-top: 30px;
}

.action-buttons .btn {
    margin: 0 10px;
}
</style>

<?php include '../includes/footer.php'; ?>