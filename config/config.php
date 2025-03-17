<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'cafm_user');
define('DB_PASS', '1234');
define('DB_NAME', 'cafm_db');

// Application configuration
define('BASE_URL', '/cafm');
define('SITE_NAME', 'CAFM System');

// Session configuration
define('SESSION_TIME', 3600); // 1 hour

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
try {
    $conn = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
} 