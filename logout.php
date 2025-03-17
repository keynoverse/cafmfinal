<?php
require_once 'config/config.php';
require_once PROJECT_ROOT . '/controllers/AuthController.php';

$auth = new AuthController($conn);
$auth->logout();

// Redirect to the login page
header('Location: public/login.php');
exit; 