<?php
require_once PROJECT_ROOT . '/config/config.php';

function isMenuItemVisible($menuId, $roleId, $conn) {
    $stmt = $conn->prepare("SELECT is_visible FROM menu_permissions WHERE menu_id = ? AND role_id = ?");
    $stmt->execute([$menuId, $roleId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result && $result['is_visible'];
}

$roleId = $user['role_id'];

?>

<nav class="sidebar-nav">
    <ul>
        <?php if (isMenuItemVisible(1, $roleId, $conn)): ?>
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
            
            <?php if (isMenuItemVisible(3, $roleId, $conn)): ?>
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

            <?php if (isMenuItemVisible(4, $roleId, $conn)): ?>
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

            <?php if (isMenuItemVisible(5, $roleId, $conn)): ?>
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

            <?php if (isMenuItemVisible(6, $roleId, $conn)): ?>
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
            
            <?php if (isMenuItemVisible(8, $roleId, $conn)): ?>
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

            <?php if (isMenuItemVisible(9, $roleId, $conn)): ?>
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

            <?php if (isMenuItemVisible(10, $roleId, $conn)): ?>
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
            
            <?php if (isMenuItemVisible(12, $roleId, $conn)): ?>
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

            <?php if (isMenuItemVisible(13, $roleId, $conn)): ?>
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

            <?php if (isMenuItemVisible(14, $roleId, $conn)): ?>
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
            
            <?php if (isMenuItemVisible(16, $roleId, $conn)): ?>
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

            <?php if (isMenuItemVisible(17, $roleId, $conn)): ?>
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

            <?php if (isMenuItemVisible(18, $roleId, $conn)): ?>
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
    </ul>
</nav> 