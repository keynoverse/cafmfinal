<?php
// Project root path
define('PROJECT_ROOT', dirname(__DIR__));

// Database configuration
define('DB_HOST', '192.168.1.16');
define('DB_USER', 'samurysam');
define('DB_PASS', '9571840084');
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