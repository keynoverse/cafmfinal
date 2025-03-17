<?php
/**
 * Navigation include file
 * This file generates the navigation menu based on user role and permissions
 */

// Initialize settings controller if not already initialized
if (!isset($settingsController)) {
    require_once PROJECT_ROOT . '/controllers/SettingsController.php';
    $settingsController = new SettingsController($conn);
}

// Get current user and role
if (!isset($user)) {
    $user = $auth->getCurrentUser();
}

// Default to regular user role if not found
$roleId = 7; // Default to basic user role

// Check if user has role_id set
if (isset($user['role_id'])) {
    $roleId = $user['role_id'];
} else {
    // Try to determine role from user_type for backward compatibility
    if (isset($user['user_type'])) {
        $stmt = $conn->prepare("SELECT id FROM roles WHERE name = ?");
        $stmt->bind_param('s', $user['user_type']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $role = $result->fetch_assoc();
            $roleId = $role['id'];
        }
    }
}

// Get navigation menu for the user's role
$menuItems = $settingsController->getNavigationMenu($roleId);

/**
 * Function to render a menu item and its children
 */
function renderMenuItem($item, $level = 0) {
    $hasChildren = !empty($item['children']);
    $isActive = (strpos($_SERVER['REQUEST_URI'], $item['url']) !== false && $item['url'] !== '#');
    
    $activeClass = $isActive ? 'active' : '';
    $hasSubmenuClass = $hasChildren ? 'has-submenu' : '';
    
    // Set open class if any child is active
    $openClass = '';
    if ($hasChildren) {
        foreach ($item['children'] as $child) {
            if (strpos($_SERVER['REQUEST_URI'], $child['url']) !== false && $child['url'] !== '#') {
                $openClass = 'open';
                break;
            }
        }
    }
    
    echo "<li class=\"{$activeClass} {$hasSubmenuClass} {$openClass}\">";
    
    // Render the item link
    echo "<a href=\"{$item['url']}\">";
    if (!empty($item['icon'])) {
        echo "<i class=\"fas {$item['icon']}\"></i>";
    }
    echo "<span>{$item['name']}</span>";
    echo "</a>";
    
    // Render children if any
    if ($hasChildren) {
        echo "<ul class=\"submenu\">";
        foreach ($item['children'] as $childItem) {
            renderMenuItem($childItem, $level + 1);
        }
        echo "</ul>";
    }
    
    echo "</li>";
}

// Check if we're rendering navigation sidebar or some other type of menu
$isMainNavigation = true;
if (isset($navType) && $navType !== 'main') {
    $isMainNavigation = false;
}
?>

<?php if ($isMainNavigation): ?>
<!-- Main Navigation Sidebar -->
<aside class="sidebar">
    <div class="sidebar-header">
        <div class="user-info">
            <img src="<?php echo PROJECT_URL; ?>/assets/images/default-avatar.png" alt="User Avatar" class="user-avatar">
            <div class="user-details">
                <h3><?php echo htmlspecialchars($user['name']); ?></h3>
                <p><?php echo htmlspecialchars($user['user_type']); ?></p>
            </div>
        </div>
    </div>
    
    <nav class="sidebar-nav">
        <ul>
            <?php 
            // Render top-level menu items
            foreach ($menuItems as $item) {
                // Check if this item represents a module group heading
                if ($item['url'] === '#' && !empty($item['children'])) {
                    echo "<div class=\"module-group\">";
                    echo "<div class=\"module-group-title\">{$item['name']}</div>";
                    
                    // Render child items directly under the group
                    foreach ($item['children'] as $childItem) {
                        renderMenuItem($childItem);
                    }
                    
                    echo "</div>";
                } else {
                    // Render as regular menu item
                    renderMenuItem($item);
                }
            }
            ?>
        </ul>
    </nav>
</aside>
<?php endif; ?> 