<?php
require_once '../../../config/config.php';
require_once PROJECT_ROOT . '/controllers/AuthController.php';

$auth = new AuthController($conn);
if (!$auth->isLoggedIn()) {
    header('Location: ../../../public/login.php');
    exit;
}

$user = $auth->getCurrentUser();
?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asset Management - CAFM System</title>
    <link rel="stylesheet" href="../../../assets/css/main.css">
    <link rel="stylesheet" href="../../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../../assets/css/dark-mode.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="user-info">
                    <img src="../../../assets/images/default-avatar.png" alt="User Avatar" class="user-avatar">
                    <div class="user-details">
                        <h3><?php echo htmlspecialchars($user['name']); ?></h3>
                        <p><?php echo htmlspecialchars($user['user_type']); ?></p>
                    </div>
                </div>
            </div>
            
            <nav class="sidebar-nav">
                <ul>
                    <li>
                        <a href="../../../dashboard/index.php">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <!-- Core Modules -->
                    <div class="module-group">
                        <div class="module-group-title">Core Modules</div>
                        
                        <li class="has-submenu active">
                            <a href="#">
                                <i class="fas fa-boxes"></i>
                                <span>Asset Management</span>
                            </a>
                            <ul class="submenu">
                                <li class="active"><a href="../../../dashboard/modules/asset-management/assets.php">Asset Master</a></li>
                                <li><a href="../../../dashboard/modules/asset-management/categories.php">Categories</a></li>
                                <li><a href="../../../dashboard/modules/asset-management/lifecycle.php">Lifecycle Management</a></li>
                                <li><a href="../../../dashboard/modules/asset-management/inventory.php">Inventory</a></li>
                                <li><a href="../../../dashboard/modules/asset-management/qr-codes.php">QR/Barcode</a></li>
                                <li><a href="../../../dashboard/modules/asset-management/warranty.php">Warranty Management</a></li>
                                <li><a href="../../../dashboard/modules/asset-management/performance.php">Performance Monitoring</a></li>
                                <li><a href="../../../dashboard/modules/asset-management/documentation.php">Documentation</a></li>
                            </ul>
                        </li>
                        
                        <li class="has-submenu">
                            <a href="#">
                                <i class="fas fa-building"></i>
                                <span>Facility Management</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="../../../dashboard/modules/facility-management/work-orders.php">Work Orders</a></li>
                                <li><a href="../../../dashboard/modules/facility-management/ppm.php">PPM</a></li>
                                <li><a href="../../../dashboard/modules/facility-management/complaints.php">Complaints</a></li>
                                <li><a href="../../../dashboard/modules/facility-management/sla.php">SLA Management</a></li>
                                <li><a href="../../../dashboard/modules/facility-management/space.php">Space Management</a></li>
                                <li><a href="../../../dashboard/modules/facility-management/occupancy.php">Occupancy Tracking</a></li>
                                <li><a href="../../../dashboard/modules/facility-management/room-booking.php">Room Booking</a></li>
                                <li><a href="../../../dashboard/modules/facility-management/move.php">Move Management</a></li>
                            </ul>
                        </li>
                        
                        <li class="has-submenu">
                            <a href="#">
                                <i class="fas fa-users"></i>
                                <span>Client Management</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="../../../dashboard/modules/client-master/clients.php">Client Directory</a></li>
                                <li><a href="../../../dashboard/modules/client-master/portal.php">Client Portal</a></li>
                                <li><a href="../../../dashboard/modules/client-master/satisfaction.php">Satisfaction Tracking</a></li>
                            </ul>
                        </li>
                        
                        <li class="has-submenu">
                            <a href="#">
                                <i class="fas fa-handshake"></i>
                                <span>Vendor Management</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="../../../dashboard/modules/vendor-master/vendors.php">Vendor Directory</a></li>
                                <li><a href="../../../dashboard/modules/vendor-master/performance.php">Performance Metrics</a></li>
                                <li><a href="../../../dashboard/modules/vendor-master/contracts.php">Contracts</a></li>
                            </ul>
                        </li>
                    </div>
                    
                    <!-- Smart Building -->
                    <div class="module-group">
                        <div class="module-group-title">Smart Building</div>
                        
                        <li class="has-submenu">
                            <a href="#">
                                <i class="fas fa-robot"></i>
                                <span>AI & ML</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="../../../dashboard/modules/smart-building/predictive.php">Predictive Maintenance</a></li>
                                <li><a href="../../../dashboard/modules/smart-building/optimization.php">Space Optimization</a></li>
                                <li><a href="../../../dashboard/modules/smart-building/analytics.php">Usage Analytics</a></li>
                                <li><a href="../../../dashboard/modules/smart-building/anomaly.php">Anomaly Detection</a></li>
                                <li><a href="../../../dashboard/modules/smart-building/pattern.php">Pattern Recognition</a></li>
                                <li><a href="../../../dashboard/modules/smart-building/risk.php">Risk Assessment</a></li>
                            </ul>
                        </li>
                        
                        <li class="has-submenu">
                            <a href="#">
                                <i class="fas fa-network-wired"></i>
                                <span>IoT Integration</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="../../../dashboard/modules/iot-integration/sensors.php">Sensor Network</a></li>
                                <li><a href="../../../dashboard/modules/iot-integration/monitoring.php">Environmental Monitoring</a></li>
                                <li><a href="../../../dashboard/modules/iot-integration/alerts.php">Real-time Alerts</a></li>
                            </ul>
                        </li>
                        
                        <li class="has-submenu">
                            <a href="#">
                                <i class="fas fa-bolt"></i>
                                <span>Energy Management</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="../../../dashboard/modules/energy-management/consumption.php">Consumption Monitoring</a></li>
                                <li><a href="../../../dashboard/modules/energy-management/optimization.php">Energy Optimization</a></li>
                                <li><a href="../../../dashboard/modules/energy-management/sustainability.php">Sustainability Metrics</a></li>
                            </ul>
                        </li>
                    </div>
                    
                    <!-- Operations -->
                    <div class="module-group">
                        <div class="module-group-title">Operations</div>
                        
                        <li class="has-submenu">
                            <a href="#">
                                <i class="fas fa-mobile-alt"></i>
                                <span>Mobile Workforce</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="../../../dashboard/modules/mobile-workforce/tracking.php">GPS Tracking</a></li>
                                <li><a href="../../../dashboard/modules/mobile-workforce/tasks.php">Task Management</a></li>
                                <li><a href="../../../dashboard/modules/mobile-workforce/documentation.php">Photo Documentation</a></li>
                                <li><a href="../../../dashboard/modules/mobile-workforce/resource.php">Resource Allocation</a></li>
                                <li><a href="../../../dashboard/modules/mobile-workforce/cost.php">Cost Tracking</a></li>
                            </ul>
                        </li>
                        
                        <li class="has-submenu">
                            <a href="#">
                                <i class="fas fa-shield-alt"></i>
                                <span>Security & Risk</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="../../../dashboard/modules/security-risk/access.php">Access Control</a></li>
                                <li><a href="../../../dashboard/modules/security-risk/incidents.php">Incident Reporting</a></li>
                                <li><a href="../../../dashboard/modules/security-risk/compliance.php">Safety Compliance</a></li>
                            </ul>
                        </li>
                        
                        <li class="has-submenu">
                            <a href="#">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span>Emergency Management</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="../../../dashboard/modules/emergency/plans.php">Emergency Plans</a></li>
                                <li><a href="../../../dashboard/modules/emergency/drills.php">Drills & Training</a></li>
                                <li><a href="../../../dashboard/modules/emergency/contacts.php">Emergency Contacts</a></li>
                            </ul>
                        </li>
                    </div>
                    
                    <!-- Analytics & Finance -->
                    <div class="module-group">
                        <div class="module-group-title">Analytics & Finance</div>
                        
                        <li class="has-submenu">
                            <a href="#">
                                <i class="fas fa-chart-line"></i>
                                <span>Analytics & Reporting</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="../../../dashboard/modules/analytics/dashboards.php">Custom Dashboards</a></li>
                                <li><a href="../../../dashboard/modules/analytics/reports.php">Report Builder</a></li>
                                <li><a href="../../../dashboard/modules/analytics/kpi.php">KPI Tracking</a></li>
                            </ul>
                        </li>
                        
                        <li class="has-submenu">
                            <a href="#">
                                <i class="fas fa-leaf"></i>
                                <span>Sustainability</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="../../../dashboard/modules/sustainability/carbon.php">Carbon Footprint</a></li>
                                <li><a href="../../../dashboard/modules/sustainability/waste.php">Waste Management</a></li>
                                <li><a href="../../../dashboard/modules/sustainability/reporting.php">ESG Reporting</a></li>
                            </ul>
                        </li>
                        
                        <li class="has-submenu">
                            <a href="#">
                                <i class="fas fa-dollar-sign"></i>
                                <span>Financial Control</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="../../../dashboard/modules/financial/budgeting.php">Budgeting</a></li>
                                <li><a href="../../../dashboard/modules/financial/invoices.php">Invoice Management</a></li>
                                <li><a href="../../../dashboard/modules/financial/roi.php">ROI Analysis</a></li>
                                <li><a href="../../../dashboard/modules/financial/cost-tracking.php">Cost Tracking</a></li>
                                <li><a href="../../../dashboard/modules/financial/asset-valuation.php">Asset Valuation</a></li>
                                <li><a href="../../../dashboard/modules/financial/budget-planning.php">Budget Planning</a></li>
                            </ul>
                        </li>
                    </div>
                    
                    <!-- System -->
                    <div class="module-group">
                        <div class="module-group-title">System</div>
                        
                        <li class="has-submenu">
                            <a href="#">
                                <i class="fas fa-user-cog"></i>
                                <span>User Management</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="../../../dashboard/modules/user-management/users.php">Users</a></li>
                                <li><a href="../../../dashboard/modules/user-management/roles.php">Roles & Permissions</a></li>
                                <li><a href="../../../dashboard/modules/user-management/activity.php">Activity Log</a></li>
                            </ul>
                        </li>
                        
                        <li>
                            <a href="../../../dashboard/profile.php">
                                <i class="fas fa-user"></i>
                                <span>My Profile</span>
                            </a>
                        </li>
                        
                        <li>
                            <a href="../../../logout.php">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </a>
                        </li>
                    </div>

                    <!-- BIM System -->
                    <div class="module-group">
                        <div class="module-group-title">BIM System</div>
                        
                        <li class="has-submenu">
                            <a href="#">
                                <i class="fas fa-cube"></i>
                                <span>BIM Management</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="../../../dashboard/modules/bim/viewer.php">3D Model Viewer</a></li>
                                <li><a href="../../../dashboard/modules/bim/integration.php">BIM Data Integration</a></li>
                                <li><a href="../../../dashboard/modules/bim/clash.php">Clash Detection</a></li>
                                <li><a href="../../../dashboard/modules/bim/mep.php">MEP Systems</a></li>
                                <li><a href="../../../dashboard/modules/bim/planning.php">Construction Planning</a></li>
                            </ul>
                        </li>
                    </div>

                    <!-- Portal Access -->
                    <div class="module-group">
                        <div class="module-group-title">Portal Access</div>
                        
                        <li class="has-submenu">
                            <a href="#">
                                <i class="fas fa-door-open"></i>
                                <span>Portals</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="../../../dashboard/modules/portals/tenant.php">Tenant Portal</a></li>
                                <li><a href="../../../dashboard/modules/portals/landlord.php">Landlord Portal</a></li>
                                <li><a href="../../../dashboard/modules/portals/vendor.php">Vendor Portal</a></li>
                            </ul>
                        </li>
                    </div>
                </ul>
            </nav>
        </aside>

        <div class="main-content">
            <div class="content-header">
                <h1>Asset Management</h1>
                <button class="btn btn-primary" onclick="openAssetModal()">Add New Asset</button>
            </div>

            <!-- Summary Cards -->
            <div class="dashboard-grid">
                <div class="dashboard-card">
                    <h3>Total Assets</h3>
                    <p>100</p>
                </div>
                <div class="dashboard-card">
                    <h3>Categories</h3>
                    <p>5</p>
                </div>
                <div class="dashboard-card">
                    <h3>Active Assets</h3>
                    <p>80</p>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="filters">
                <input type="text" placeholder="Search assets..." class="form-control">
                <select class="form-control">
                    <option value="">All Categories</option>
                    <option value="1">Category 1</option>
                    <option value="2">Category 2</option>
                </select>
                <select class="form-control">
                    <option value="">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <!-- Assets Table -->
            <div class="dashboard-grid">
                <div class="dashboard-card">
                    <h3>Assets List</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Location</th>
                                <th>Purchase Date</th>
                                <th>Value</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Asset rows will go here -->
                        </tbody>
                    </table>
                    <!-- Pagination -->
                    <div class="pagination">
                        <button class="btn">Previous</button>
                        <button class="btn">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Asset Modal -->
    <div id="assetModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAssetModal()">&times;</span>
            <h2>Add/Edit Asset</h2>
            <form>
                <input type="text" placeholder="Asset Name" class="form-control">
                <input type="text" placeholder="Category" class="form-control">
                <input type="text" placeholder="Location" class="form-control">
                <input type="date" placeholder="Purchase Date" class="form-control">
                <input type="number" placeholder="Value" class="form-control">
                <select class="form-control">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>

    <script>
    function openAssetModal() {
        document.getElementById('assetModal').style.display = 'block';
    }

    function closeAssetModal() {
        document.getElementById('assetModal').style.display = 'none';
    }
    </script>
</body>
</html>
