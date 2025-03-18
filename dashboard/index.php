<?php
require_once '../config/config.php';
require_once PROJECT_ROOT . '/controllers/AuthController.php';
require_once PROJECT_ROOT . '/dashboard/ajax/get_menu_permissions.php';

$auth = new AuthController($conn);
if (!$auth->isLoggedIn()) {
    header('Location: ../public/login.php');
    exit;
}

$user = $auth->getCurrentUser();
$menuItems = getMenuItems($user['user_type']);
?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CAFM System</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/dark-mode.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="user-info">
                    <img src="../assets/images/default-avatar.png" alt="User Avatar" class="user-avatar">
                    <div class="user-details">
                        <h3><?php echo htmlspecialchars($user['name']); ?></h3>
                        <p><?php echo htmlspecialchars($user['user_type']); ?></p>
                    </div>
                </div>
            </div>
            
            <nav class="sidebar-nav">
                <ul>
                    <?php foreach ($menuItems as $menuItem): ?>
                        <li class="<?php echo $menuItem['active'] ? 'active' : ''; ?>">
                            <a href="<?php echo htmlspecialchars($menuItem['link']); ?>">
                                <i class="<?php echo htmlspecialchars($menuItem['icon']); ?>"></i>
                                <span><?php echo htmlspecialchars($menuItem['name']); ?></span>
                            </a>
                            <?php if (!empty($menuItem['submenu'])): ?>
                                <ul class="submenu">
                                    <?php foreach ($menuItem['submenu'] as $submenuItem): ?>
                                        <li><a href="<?php echo htmlspecialchars($submenuItem['link']); ?>"><?php echo htmlspecialchars($submenuItem['name']); ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="content-header">
                <div class="header-left">
                    <button class="toggle-sidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1>Dashboard</h1>
                </div>
                <div class="header-actions">
                    <button class="btn btn-outline">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </button>
                    <button class="btn btn-outline">
                        <i class="fas fa-cog"></i>
                    </button>
                </div>
            </header>

            <!-- Dashboard Grid -->
            <div class="dashboard-grid">
                <!-- Quick Stats -->
                <div class="dashboard-card stats">
                    <h3>Quick Statistics</h3>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <h4>Active Assets</h4>
                            <p>1,234</p>
                        </div>
                        <div class="stat-item">
                            <h4>Open Work Orders</h4>
                            <p>42</p>
                        </div>
                        <div class="stat-item">
                            <h4>Energy Usage</h4>
                            <p>87%</p>
                        </div>
                        <div class="stat-item">
                            <h4>Pending Tasks</h4>
                            <p>15</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="dashboard-card activities">
                    <h3>Recent Activities</h3>
                    <ul class="activity-list">
                        <li>
                            <i class="fas fa-wrench"></i>
                            <div class="activity-details">
                                <p>Work order #123 completed</p>
                                <span>2 hours ago</span>
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-exclamation-triangle"></i>
                            <div class="activity-details">
                                <p>New maintenance alert</p>
                                <span>4 hours ago</span>
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-user-plus"></i>
                            <div class="activity-details">
                                <p>New tenant registration</p>
                                <span>6 hours ago</span>
                            </div>
                        </li>
                        <li>
                            <i class="fas fa-chart-line"></i>
                            <div class="activity-details">
                                <p>Energy usage report generated</p>
                                <span>Yesterday</span>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Performance Metrics -->
                <div class="dashboard-card metrics">
                    <h3>Performance Metrics</h3>
                    <div class="metric-container">
                        <div id="performance-chart" style="height: 250px; width: 100%;">
                            <!-- Chart will be rendered here by JavaScript -->
                            <div style="text-align: center; padding-top: 100px; color: #666;">
                                <i class="fas fa-chart-bar" style="font-size: 24px;"></i>
                                <p>Performance metrics visualization</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="dashboard-card quick-actions">
                    <h3>Quick Actions</h3>
                    <div class="action-buttons">
                        <a href="modules/facility-management/work-orders.php?action=new" class="btn btn-primary">
                            <i class="fas fa-plus"></i> New Work Order
                        </a>
                        <a href="modules/asset-management/assets.php?action=new" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Asset
                        </a>
                        <a href="modules/facility-management/complaints.php" class="btn btn-primary">
                            <i class="fas fa-clipboard-list"></i> View Complaints
                        </a>
                        <a href="modules/analytics/reports.php" class="btn btn-primary">
                            <i class="fas fa-file-alt"></i> Generate Report
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/dashboard.js"></script>
</body>
</html> 