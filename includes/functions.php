<?php
session_start();

function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /CAFM-Project/public/login.php');
        exit;
    }
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function redirectIfLoggedIn() {
    if (isset($_SESSION['user_id'])) {
        header('Location: /CAFM-Project/dashboard/');
        exit;
    }
}

function getUserRole() {
    return $_SESSION['user_role'] ?? null;
}

function hasPermission($requiredRole) {
    $userRole = getUserRole();
    // Add role hierarchy logic here
    return true; // Temporary
} 