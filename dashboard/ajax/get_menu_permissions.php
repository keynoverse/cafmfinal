<?php
require_once PROJECT_ROOT . '/config/config.php';
require_once PROJECT_ROOT . '/controllers/AuthController.php';
require_once PROJECT_ROOT . '/controllers/SettingsController.php';

// Initialize controllers
$auth = new AuthController($conn);
$settings = new SettingsController($conn);

// Check if user is logged in and has admin privileges
if (!$auth->isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$user = $auth->getCurrentUser();
if (!isset($user['role_id']) || $user['role_id'] != 1) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

// Get role ID from query parameters
$roleId = isset($_GET['role_id']) ? (int)$_GET['role_id'] : 0;
if ($roleId <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid role ID']);
    exit;
}

// Get menu permissions for the role
$permissions = $settings->getMenuPermissionsByRole($roleId);

// Extract menu IDs that are visible
$visibleMenus = array_map(
    function($permission) {
        return (int)$permission['id'];
    },
    array_filter($permissions, function($permission) {
        return $permission['is_visible'];
    })
);

header('Content-Type: application/json');
echo json_encode($visibleMenus); 