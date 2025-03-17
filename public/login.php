<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Redirect if already logged in
redirectIfLoggedIn();

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Login logic here
}

include '../includes/header.php';
?>

<!-- Login form HTML here -->

<?php include '../includes/footer.php'; ?> 