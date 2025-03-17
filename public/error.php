<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$error = $_SESSION['error'] ?? [
    'code' => 404,
    'message' => 'Page not found',
    'details' => 'The requested page could not be found.'
];

unset($_SESSION['error']);

http_response_code($error['code']);
include '../includes/header.php';
?>

<div class="error-container">
    <div class="error-box">
        <div class="error-code"><?php echo $error['code']; ?></div>
        <h1><?php echo htmlspecialchars($error['message']); ?></h1>
        <p><?php echo htmlspecialchars($error['details']); ?></p>
        <div class="action-buttons">
            <a href="javascript:history.back()" class="btn btn-secondary">Go Back</a>
            <a href="index.php" class="btn btn-primary">Home Page</a>
        </div>
    </div>
</div>

<style>
.error-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f4f6f9;
    padding: 20px;
}

.error-box {
    background: white;
    padding: 40px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    text-align: center;
    max-width: 500px;
    width: 100%;
}

.error-code {
    font-size: 72px;
    font-weight: bold;
    color: #dc3545;
    margin-bottom: 20px;
}

.error-box h1 {
    color: #333;
    margin-bottom: 20px;
}

.action-buttons {
    margin-top: 30px;
}

.action-buttons .btn {
    margin: 0 10px;
}
</style>

<?php include '../includes/footer.php'; ?> 