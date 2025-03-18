<?php
require_once '../config/config.php';
require_once PROJECT_ROOT . '/controllers/AuthController.php';

$auth = new AuthController($conn);
if (!$auth->isLoggedIn()) {
    header('Location: ../public/login.php');
    exit;
}

$user = $auth->getCurrentUser();

// Fetch user role ID
$roleId = $user['role_id'];

// Check if the user is a master admin
if (isset($user['role_id']) && $user['role_id'] == 1) { // Assuming role_id 1 is master admin
    // Fetch all menu IDs
    $stmt = $conn->prepare("SELECT id FROM menu_items");
    $stmt->execute();
    $visibleMenuIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
} else {
    // Query visible menu items for the user's role
    $stmt = $conn->prepare("SELECT menu_id FROM menu_permissions WHERE role_id = ? AND is_visible = 1");
    $stmt->execute([$roleId]);
    $visibleMenuIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Function to check if a menu item is visible
function isMenuItemVisible($menuId, $visibleMenuIds) {
    return in_array($menuId, $visibleMenuIds);
}
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
                    <?php if (isMenuItemVisible(1, $visibleMenuIds)): ?>
                    <li class="active">
                        <a href="index.php">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <!-- Core Modules -->
                    <div class="module-group">
                        <div class="module-group-title">Core Modules</div>
                        
                        <?php if (isMenuItemVisible(3, $visibleMenuIds)): ?>
                        <li class="has-submenu">
                            <a href="#">
                                <i class="fas fa-boxes"></i>
                                <span>Asset Management</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="modules/asset-management/assets.php">Asset Master</a></li>
                                <li><a href="modules/asset-management/categories.php">Categories</a></li>
                                <li><a href="modules/asset-management/lifecycle.php">Lifecycle Management</a></li>
                                <li><a href="modules/asset-management/inventory.php">Inventory</a></li>
                                <li><a href="modules/asset-management/qr-codes.php">QR/Barcode</a></li>
                            </ul>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (isMenuItemVisible(4, $visibleMenuIds)): ?>
                        <li class="has-submenu">
                            <a href="#">
                                <i class="fas fa-building"></i>
                                <span>Facility Management</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="modules/facility-management/work-orders.php">Work Orders</a></li>
                                <li><a href="modules/facility-management/ppm.php">PPM</a></li>
                                <li><a href="modules/facility-management/complaints.php">Complaints</a></li>
                                <li><a href="modules/facility-management/sla.php">SLA Management</a></li>
                            </ul>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (isMenuItemVisible(5, $visibleMenuIds)): ?>
                        <li class="has-submenu">
                            <a href="#">
                                <i class="fas fa-users"></i>
                                <span>Client Management</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="modules/client-master/clients.php">Client Directory</a></li>
                                <li><a href="modules/client-master/portal.php">Client Portal</a></li>
                                <li><a href="modules/client-master/satisfaction.php">Satisfaction Tracking</a></li>
                            </ul>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (isMenuItemVisible(6, $visibleMenuIds)): ?>
                        <li class="has-submenu">
                            <a href="#">
                                <i class="fas fa-handshake"></i>
                                <span>Vendor Management</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="modules/vendor-master/vendors.php">Vendor Directory</a></li>
                                <li><a href="modules/vendor-master/performance.php">Performance Metrics</a></li>
                                <li><a href="modules/vendor-master/contracts.php">Contracts</a></li>
                            </ul>
                        </li>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Smart Building -->
                    <div class="module-group">
                        <div class="module-group-title">Smart Building</div>
                        
                        <?php if (isMenuItemVisible(8, $visibleMenuIds)): ?>
                        <li class="has-submenu">
                            <a href="#">
                                <i class="fas fa-robot"></i>
                                <span>AI & ML</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="modules/smart-building/predictive.php">Predictive Maintenance</a></li>
                                <li><a href="modules/smart-building/optimization.php">Space Optimization</a></li>
                                <li><a href="modules/smart-building/analytics.php">Usage Analytics</a></li>
                            </ul>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (isMenuItemVisible(9, $visibleMenuIds)): ?>
                        <li class="has-submenu">
                            <a href="#">
                                <i class="fas fa-network-wired"></i>
                                <span>IoT Integration</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="modules/iot-integration/sensors.php">Sensor Network</a></li>
                                <li><a href="modules/iot-integration/monitoring.php">Environmental Monitoring</a></li>
                                <li><a href="modules/iot-integration/alerts.php">Real-time Alerts</a></li>
                            </ul>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (isMenuItemVisible(10, $visibleMenuIds)): ?>
                        <li class="has-submenu">
                            <a href="#">
                                <i class="fas fa-bolt"></i>
                                <span>Energy Management</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="modules/energy-management/consumption.php">Consumption Monitoring</a></li>
                                <li><a href="modules/energy-management/optimization.php">Energy Optimization</a></li>
                                <li><a href="modules/energy-management/sustainability.php">Sustainability Metrics</a></li>
                            </ul>
                        </li>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Operations -->
                    <div class="module-group">
                        <div class="module-group-title">Operations</div>
                        
                        <?php if (isMenuItemVisible(12, $visibleMenuIds)): ?>
                        <li class="has-submenu">
                            <a href="#">
                                <i class="fas fa-mobile-alt"></i>
                                <span>Mobile Workforce</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="modules/mobile-workforce/tracking.php">GPS Tracking</a></li>
                                <li><a href="modules/mobile-workforce/tasks.php">Task Management</a></li>
                                <li><a href="modules/mobile-workforce/documentation.php">Photo Documentation</a></li>
                            </ul>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (isMenuItemVisible(13, $visibleMenuIds)): ?>
                        <li class="has-submenu">
                            <a href="#">
                                <i class="fas fa-shield-alt"></i>
                                <span>Security & Risk</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="modules/security-risk/access.php">Access Control</a></li>
                                <li><a href="modules/security-risk/incidents.php">Incident Reporting</a></li>
                                <li><a href="modules/security-risk/compliance.php">Safety Compliance</a></li>
                            </ul>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (isMenuItemVisible(14, $visibleMenuIds)): ?>
                        <li class="has-submenu">
                            <a href="#">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span>Emergency Management</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="modules/emergency/plans.php">Emergency Plans</a></li>
                                <li><a href="modules/emergency/drills.php">Drills & Training</a></li>
                                <li><a href="modules/emergency/contacts.php">Emergency Contacts</a></li>
                            </ul>
                        </li>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Analytics & Finance -->
                    <div class="module-group">
                        <div class="module-group-title">Analytics & Finance</div>
                        
                        <?php if (isMenuItemVisible(16, $visibleMenuIds)): ?>
                        <li class="has-submenu">
                            <a href="#">
                                <i class="fas fa-chart-line"></i>
                                <span>Analytics & Reporting</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="modules/analytics/dashboards.php">Custom Dashboards</a></li>
                                <li><a href="modules/analytics/reports.php">Report Builder</a></li>
                                <li><a href="modules/analytics/kpi.php">KPI Tracking</a></li>
                            </ul>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (isMenuItemVisible(17, $visibleMenuIds)): ?>
                        <li class="has-submenu">
                            <a href="#">
                                <i class="fas fa-leaf"></i>
                                <span>Sustainability</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="modules/sustainability/carbon.php">Carbon Footprint</a></li>
                                <li><a href="modules/sustainability/waste.php">Waste Management</a></li>
                                <li><a href="modules/sustainability/reporting.php">ESG Reporting</a></li>
                            </ul>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (isMenuItemVisible(18, $visibleMenuIds)): ?>
                        <li class="has-submenu">
                            <a href="#">
                                <i class="fas fa-dollar-sign"></i>
                                <span>Financial Control</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="modules/financial/budgeting.php">Budgeting</a></li>
                                <li><a href="modules/financial/invoices.php">Invoice Management</a></li>
                                <li><a href="modules/financial/roi.php">ROI Analysis</a></li>
                            </ul>
                        </li>
                        <?php endif; ?>
                    </div>
                    
                    <!-- System -->
                    <div class="module-group">
                        <div class="module-group-title">System</div>
                        
                        <?php if (isMenuItemVisible(20, $visibleMenuIds)): ?>
                        <li class="has-submenu">
                            <a href="#">
                                <i class="fas fa-user-cog"></i>
                                <span>User Management</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="modules/user-management/users.php">Users</a></li>
                                <li><a href="modules/user-management/roles.php">Roles & Permissions</a></li>
                                <li><a href="modules/user-management/activity.php">Activity Log</a></li>
                            </ul>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (isMenuItemVisible(22, $visibleMenuIds)): ?>
                        <li>
                            <a href="profile.php">
                                <i class="fas fa-user"></i>
                                <span>My Profile</span>
                            </a>
                        </li>
                        <?php endif; ?>
                        
                        <?php if (isMenuItemVisible(23, $visibleMenuIds)): ?>
                        <li>
                            <a href="../logout.php">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </a>
                        </li>
                        <?php endif; ?>
                    </div>
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
