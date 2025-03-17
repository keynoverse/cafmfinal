<?php
require_once '../config/config.php';
require_once PROJECT_ROOT . '/controllers/AuthController.php';
require_once PROJECT_ROOT . '/controllers/SettingsController.php';

// Initialize controllers
$auth = new AuthController($conn);
$settings = new SettingsController($conn);

// Check if user is logged in and has admin privileges
if (!$auth->isLoggedIn()) {
    header('Location: ../public/login.php');
    exit;
}

$user = $auth->getCurrentUser();
if (!isset($user['role_id']) || $user['role_id'] != 1) { // Assuming role_id 1 is super_admin
    header('Location: index.php');
    exit;
}

// Handle form submissions
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'update_module_permissions':
                    $roleId = $_POST['role_id'];
                    $modulePermissions = $_POST['module_permissions'] ?? [];
                    $settings->updateModulePermissions($roleId, $modulePermissions);
                    $message = 'Module permissions updated successfully';
                    $messageType = 'success';
                    break;

                case 'update_menu_permissions':
                    $roleId = $_POST['role_id'];
                    $menuPermissions = $_POST['menu_permissions'] ?? [];
                    $settings->updateMenuPermissions($roleId, $menuPermissions);
                    $message = 'Menu permissions updated successfully';
                    $messageType = 'success';
                    break;
            }
        }
    } catch (Exception $e) {
        $message = $e->getMessage();
        $messageType = 'error';
    }
}

// Get all roles, modules, and menu items
$roles = $settings->getAllRoles();
$modules = $settings->getAllModules();
$menuItems = $settings->getFlatMenuItems();

// Include header
include_once PROJECT_ROOT . '/includes/header.php';
?>

<div class="settings-container">
    <div class="settings-header">
        <h1>System Settings</h1>
        <p>Manage system-wide settings, permissions, and configurations</p>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-<?php echo $messageType === 'success' ? 'success' : 'danger'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <div class="settings-tabs">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#modules" role="tab">Module Management</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#menu" role="tab">Menu Management</a>
            </li>
        </ul>

        <div class="tab-content">
            <!-- Modules Tab -->
            <div class="tab-pane fade show active" id="modules" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <h3>Module Permissions</h3>
                        <p>Enable or disable modules for different user roles</p>

                        <form method="POST" action="">
                            <input type="hidden" name="action" value="update_module_permissions">
                            
                            <div class="form-group">
                                <label for="role_select">Select Role:</label>
                                <select class="form-control" id="role_select" name="role_id" required>
                                    <?php foreach ($roles as $role): ?>
                                        <option value="<?php echo $role['id']; ?>">
                                            <?php echo htmlspecialchars($role['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="module-permissions">
                                <?php foreach ($modules as $module): ?>
                                    <div class="module-item">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" 
                                                   class="custom-control-input" 
                                                   id="module_<?php echo $module['id']; ?>" 
                                                   name="module_permissions[]" 
                                                   value="<?php echo $module['id']; ?>"
                                                   <?php echo $module['is_core'] ? 'checked disabled' : ''; ?>>
                                            <label class="custom-control-label" 
                                                   for="module_<?php echo $module['id']; ?>">
                                                <?php echo htmlspecialchars($module['name']); ?>
                                                <?php if ($module['is_core']): ?>
                                                    <span class="badge badge-primary">Core</span>
                                                <?php endif; ?>
                                            </label>
                                        </div>
                                        <small class="text-muted">
                                            <?php echo htmlspecialchars($module['description']); ?>
                                        </small>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <button type="submit" class="btn btn-primary">Save Module Permissions</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Menu Tab -->
            <div class="tab-pane fade" id="menu" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <h3>Menu Visibility</h3>
                        <p>Configure menu item visibility for different user roles</p>

                        <form method="POST" action="">
                            <input type="hidden" name="action" value="update_menu_permissions">
                            
                            <div class="form-group">
                                <label for="menu_role_select">Select Role:</label>
                                <select class="form-control" id="menu_role_select" name="role_id" required>
                                    <?php foreach ($roles as $role): ?>
                                        <option value="<?php echo $role['id']; ?>">
                                            <?php echo htmlspecialchars($role['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="menu-permissions">
                                <?php foreach ($menuItems as $item): ?>
                                    <div class="menu-item" style="margin-left: <?php echo $item['level'] * 20; ?>px;">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" 
                                                   class="custom-control-input" 
                                                   id="menu_<?php echo $item['id']; ?>" 
                                                   name="menu_permissions[]" 
                                                   value="<?php echo $item['id']; ?>">
                                            <label class="custom-control-label" 
                                                   for="menu_<?php echo $item['id']; ?>">
                                                <?php if ($item['icon']): ?>
                                                    <i class="fas <?php echo $item['icon']; ?>"></i>
                                                <?php endif; ?>
                                                <?php echo htmlspecialchars($item['name']); ?>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <button type="submit" class="btn btn-primary">Save Menu Permissions</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.settings-container {
    padding: 20px;
}

.settings-header {
    margin-bottom: 30px;
}

.settings-header h1 {
    margin-bottom: 10px;
    color: var(--text-color);
}

.settings-tabs {
    background: var(--card-bg);
    border-radius: 8px;
    box-shadow: var(--card-shadow);
}

.nav-tabs {
    border-bottom: 1px solid var(--border-color);
    padding: 0 20px;
}

.nav-tabs .nav-link {
    color: var(--text-color);
    border: none;
    padding: 15px 20px;
    position: relative;
}

.nav-tabs .nav-link:hover {
    border: none;
    color: var(--accent-color);
}

.nav-tabs .nav-link.active {
    background: none;
    border: none;
    color: var(--accent-color);
}

.nav-tabs .nav-link.active::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 2px;
    background: var(--accent-color);
}

.tab-content {
    padding: 20px;
}

.card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 8px;
}

.module-item, .menu-item {
    padding: 15px;
    border-bottom: 1px solid var(--border-color);
}

.module-item:last-child, .menu-item:last-child {
    border-bottom: none;
}

.custom-switch {
    padding-left: 2.25rem;
}

.custom-control-input:checked ~ .custom-control-label::before {
    background-color: var(--accent-color);
    border-color: var(--accent-color);
}

.badge-primary {
    background: var(--accent-color);
    margin-left: 10px;
}

.btn-primary {
    background: var(--accent-color);
    border: none;
    padding: 10px 20px;
    margin-top: 20px;
}

.btn-primary:hover {
    background: var(--gradient-start);
}

.alert {
    margin-bottom: 20px;
    border-radius: 8px;
}

.alert-success {
    background: rgba(40, 167, 69, 0.2);
    border: 1px solid #28a745;
    color: #28a745;
}

.alert-danger {
    background: rgba(220, 53, 69, 0.2);
    border: 1px solid #dc3545;
    color: #dc3545;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle role selection change for modules
    document.getElementById('role_select').addEventListener('change', function() {
        updateModulePermissions(this.value);
    });

    // Handle role selection change for menu items
    document.getElementById('menu_role_select').addEventListener('change', function() {
        updateMenuPermissions(this.value);
    });

    // Load initial permissions
    updateModulePermissions(document.getElementById('role_select').value);
    updateMenuPermissions(document.getElementById('menu_role_select').value);

    function updateModulePermissions(roleId) {
        fetch(`ajax/get_module_permissions.php?role_id=${roleId}`)
            .then(response => response.json())
            .then(data => {
                document.querySelectorAll('[name="module_permissions[]"]').forEach(checkbox => {
                    const moduleId = checkbox.value;
                    checkbox.checked = data.includes(parseInt(moduleId)) || checkbox.disabled;
                });
            });
    }

    function updateMenuPermissions(roleId) {
        fetch(`ajax/get_menu_permissions.php?role_id=${roleId}`)
            .then(response => response.json())
            .then(data => {
                document.querySelectorAll('[name="menu_permissions[]"]').forEach(checkbox => {
                    const menuId = checkbox.value;
                    checkbox.checked = data.includes(parseInt(menuId));
                });
            });
    }
});
</script>

<?php include_once PROJECT_ROOT . '/includes/footer.php'; ?>
