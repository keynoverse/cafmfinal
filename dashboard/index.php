<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if user is logged in
checkLogin();

$user = [
    'name' => $_SESSION['user_name'] ?? 'User',
    'role' => $_SESSION['user_role'] ?? 'Unknown'
];

include '../includes/header.php';
?>

<div class="dashboard-container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="user-info">
                <img src="../assets/images/default-avatar.png" alt="User Avatar" class="user-avatar">
                <div class="user-details">
                    <h3><?php echo $user['name']; ?></h3>
                    <p><?php echo $user['role']; ?></p>
                </div>
            </div>
        </div>
        
        <nav class="sidebar-nav">
            <ul>
                <li class="active">
                    <a href="index.php">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="modules/assets/">
                        <i class="fas fa-boxes"></i>
                        <span>Asset Management</span>
                    </a>
                </li>
                <li>
                    <a href="modules/facility/">
                        <i class="fas fa-building"></i>
                        <span>Facility Management</span>
                    </a>
                </li>
                <li>
                    <a href="modules/energy/">
                        <i class="fas fa-bolt"></i>
                        <span>Energy Management</span>
                    </a>
                </li>
                <li>
                    <a href="modules/vendors/">
                        <i class="fas fa-handshake"></i>
                        <span>Vendor Management</span>
                    </a>
                </li>
                <li>
                    <a href="profile.php">
                        <i class="fas fa-user"></i>
                        <span>Profile</span>
                    </a>
                </li>
                <li>
                    <a href="../logout.php">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header class="content-header">
            <h1>Dashboard</h1>
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
                </ul>
            </div>

            <!-- Performance Metrics -->
            <div class="dashboard-card metrics">
                <h3>Performance Metrics</h3>
                <div class="metric-container">
                    <!-- Add charts/graphs here -->
                </div>
            </div>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?> 