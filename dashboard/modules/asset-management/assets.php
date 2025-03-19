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
                <div class="header-left">
                    <h1>Asset Management</h1>
                    <div class="breadcrumb">
                        <a href="../../../dashboard/index.php">Dashboard</a> / 
                        <span>Asset Management</span>
                    </div>
                </div>
                <div class="header-actions">
                    <button class="btn btn-primary" onclick="openAssetModal()">
                        <i class="fas fa-plus"></i> Add New Asset
                    </button>
                    <button class="btn btn-outline" onclick="toggleFilters()">
                        <i class="fas fa-filter"></i> Filters
                    </button>
                    <button class="btn btn-outline" onclick="exportAssets()">
                        <i class="fas fa-download"></i> Export
                    </button>
                </div>
            </div>

            <!-- Analytics Cards -->
            <div class="analytics-grid">
                <div class="analytics-card">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-cubes"></i>
                    </div>
                    <div class="card-content">
                        <h3>Total Assets</h3>
                        <p class="number">1,234</p>
                        <span class="trend positive">+5% from last month</span>
                    </div>
                </div>
                <div class="analytics-card">
                    <div class="card-icon bg-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="card-content">
                        <h3>Active Assets</h3>
                        <p class="number">987</p>
                        <span class="trend">80% of total</span>
                    </div>
                </div>
                <div class="analytics-card">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div class="card-content">
                        <h3>Under Maintenance</h3>
                        <p class="number">45</p>
                        <span class="trend negative">+12% from last week</span>
                    </div>
                </div>
                <div class="analytics-card">
                    <div class="card-icon bg-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="card-content">
                        <h3>Critical Issues</h3>
                        <p class="number">8</p>
                        <span class="trend">Requires attention</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions-grid">
                <div class="action-card">
                    <i class="fas fa-qrcode"></i>
                    <span>Generate QR Code</span>
                </div>
                <div class="action-card">
                    <i class="fas fa-file-pdf"></i>
                    <span>Asset Report</span>
                </div>
                <div class="action-card">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Maintenance Schedule</span>
                </div>
                <div class="action-card">
                    <i class="fas fa-chart-line"></i>
                    <span>Performance Analytics</span>
                </div>
                <div class="action-card">
                    <i class="fas fa-history"></i>
                    <span>Maintenance History</span>
                </div>
                <div class="action-card">
                    <i class="fas fa-file-contract"></i>
                    <span>Warranty Details</span>
                </div>
            </div>

            <!-- Advanced Filters Panel -->
            <div id="filtersPanel" class="filters-panel">
                <div class="filters-grid">
                    <div class="filter-group">
                        <label>Search</label>
                        <input type="text" class="form-control" placeholder="Search assets...">
                    </div>
                    <div class="filter-group">
                        <label>Category</label>
                        <select class="form-control">
                            <option value="">All Categories</option>
                            <option value="mechanical">Mechanical</option>
                            <option value="electrical">Electrical</option>
                            <option value="hvac">HVAC</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Status</label>
                        <select class="form-control">
                            <option value="">All Statuses</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="maintenance">Under Maintenance</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Location</label>
                        <select class="form-control">
                            <option value="">All Locations</option>
                            <option value="building1">Building 1</option>
                            <option value="building2">Building 2</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Purchase Date</label>
                        <input type="date" class="form-control">
                    </div>
                    <div class="filter-group">
                        <label>Warranty Status</label>
                        <select class="form-control">
                            <option value="">All</option>
                            <option value="active">Active</option>
                            <option value="expired">Expired</option>
                        </select>
                    </div>
                </div>
                <div class="filters-actions">
                    <button class="btn btn-secondary" onclick="resetFilters()">Reset</button>
                    <button class="btn btn-primary" onclick="applyFilters()">Apply Filters</button>
                </div>
            </div>

            <!-- Assets Table -->
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" onclick="toggleAllCheckboxes(this)"></th>
                            <th>Asset ID</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Purchase Date</th>
                            <th>Last Maintenance</th>
                            <th>Next Service</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Sample row -->
                        <tr>
                            <td><input type="checkbox"></td>
                            <td>AST001</td>
                            <td>Air Handling Unit</td>
                            <td>HVAC</td>
                            <td>Building 1 - Floor 3</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td>2023-01-15</td>
                            <td>2024-01-10</td>
                            <td>2024-04-10</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-icon" title="Edit" onclick="openAssetModal('AST001')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-icon" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn-icon" title="Maintenance History">
                                        <i class="fas fa-history"></i>
                                    </button>
                                    <button class="btn-icon" title="More Actions">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="table-footer">
                    <div class="table-info">Showing 1-10 of 1,234 assets</div>
                    <div class="pagination">
                        <button class="btn btn-outline" disabled><i class="fas fa-chevron-left"></i></button>
                        <button class="btn btn-outline active">1</button>
                        <button class="btn btn-outline">2</button>
                        <button class="btn btn-outline">3</button>
                        <span>...</span>
                        <button class="btn btn-outline">124</button>
                        <button class="btn btn-outline"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Asset Modal -->
    <div id="assetModal" class="modal">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h2>Add New Asset</h2>
                <button class="close" onclick="closeAssetModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="assetForm" class="form-grid">
                    <!-- Basic Information -->
                    <div class="form-section">
                        <h3>Basic Information</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Asset Name*</label>
                                <input type="text" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Asset ID*</label>
                                <input type="text" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Category*</label>
                                <div class="input-group">
                                    <select class="form-control" required>
                                        <option value="">Select Category</option>
                                        <option value="hvac">HVAC</option>
                                        <option value="electrical">Electrical</option>
                                        <option value="mechanical">Mechanical</option>
                                    </select>
                                    <button type="button" class="btn btn-icon" onclick="openCategoryModal()">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Subcategory</label>
                                <select class="form-control">
                                    <option value="">Select Subcategory</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Location & Installation -->
                    <div class="form-section">
                        <h3>Location & Installation</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Building*</label>
                                <select class="form-control" required>
                                    <option value="">Select Building</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Floor*</label>
                                <select class="form-control" required>
                                    <option value="">Select Floor</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Room/Area</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Installation Date</label>
                                <input type="date" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Commissioned Date</label>
                                <input type="date" class="form-control">
                            </div>
                        </div>
                    </div>

                    <!-- Procurement Details -->
                    <div class="form-section">
                        <h3>Procurement Details</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Vendor*</label>
                                <div class="input-group">
                                    <select class="form-control" required>
                                        <option value="">Select Vendor</option>
                                    </select>
                                    <button type="button" class="btn btn-icon" onclick="openVendorModal()">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Purchase Date*</label>
                                <input type="date" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Purchase Cost*</label>
                                <input type="number" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>PO Number</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                    </div>

                    <!-- Technical Specifications -->
                    <div class="form-section">
                        <h3>Technical Specifications</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Manufacturer*</label>
                                <input type="text" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Model Number*</label>
                                <input type="text" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Serial Number*</label>
                                <input type="text" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Capacity/Rating</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                    </div>

                    <!-- Warranty Information -->
                    <div class="form-section">
                        <h3>Warranty Information</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Warranty Start Date</label>
                                <input type="date" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Warranty End Date</label>
                                <input type="date" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Warranty Terms</label>
                            <textarea class="form-control" rows="3"></textarea>
                        </div>
                    </div>

                    <!-- Maintenance Schedule -->
                    <div class="form-section">
                        <h3>Maintenance Schedule</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Maintenance Frequency*</label>
                                <select class="form-control" required>
                                    <option value="">Select Frequency</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="quarterly">Quarterly</option>
                                    <option value="semi-annual">Semi-Annual</option>
                                    <option value="annual">Annual</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Next Service Date</label>
                                <input type="date" class="form-control">
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="form-section">
                        <h3>Additional Information</h3>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Notes</label>
                            <textarea class="form-control" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Attachments</label>
                            <input type="file" class="form-control" multiple>
                            <small class="form-text">Upload manuals, certificates, or other relevant documents</small>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeAssetModal()">Cancel</button>
                <button class="btn btn-primary" onclick="saveAsset()">Save Asset</button>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div id="categoryModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Category</h2>
                <button class="close" onclick="closeCategoryModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="categoryForm">
                    <div class="form-group">
                        <label>Category Name*</label>
                        <input type="text" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Parent Category</label>
                        <select class="form-control">
                            <option value="">None (Top Level)</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeCategoryModal()">Cancel</button>
                <button class="btn btn-primary" onclick="saveCategory()">Save Category</button>
            </div>
        </div>
    </div>

    <!-- Add Vendor Modal -->
    <div id="vendorModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Vendor</h2>
                <button class="close" onclick="closeVendorModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="vendorForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Vendor Name*</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Contact Person*</label>
                            <input type="text" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Email*</label>
                            <input type="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Phone*</label>
                            <input type="tel" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <textarea class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Service Categories</label>
                        <select class="form-control" multiple>
                            <option value="hvac">HVAC</option>
                            <option value="electrical">Electrical</option>
                            <option value="mechanical">Mechanical</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeVendorModal()">Cancel</button>
                <button class="btn btn-primary" onclick="saveVendor()">Save Vendor</button>
            </div>
        </div>
    </div>

    <script src="../../../assets/js/main.js"></script>
    <script>
    // Modal functions
    function openAssetModal(assetId = null) {
        document.getElementById('assetModal').style.display = 'block';
        if (assetId) {
            // Load asset data for editing
            loadAssetData(assetId);
        }
    }

    function closeAssetModal() {
        document.getElementById('assetModal').style.display = 'none';
    }

    function openCategoryModal() {
        document.getElementById('categoryModal').style.display = 'block';
    }

    function closeCategoryModal() {
        document.getElementById('categoryModal').style.display = 'none';
    }

    function openVendorModal() {
        document.getElementById('vendorModal').style.display = 'block';
    }

    function closeVendorModal() {
        document.getElementById('vendorModal').style.display = 'none';
    }

    // Filter functions
    function toggleFilters() {
        const filtersPanel = document.getElementById('filtersPanel');
        filtersPanel.classList.toggle('show');
    }

    function resetFilters() {
        document.querySelectorAll('#filtersPanel .form-control').forEach(input => {
            input.value = '';
        });
    }

    function applyFilters() {
        // Implement filter logic
        toggleFilters();
    }

    // Table functions
    function toggleAllCheckboxes(source) {
        const checkboxes = document.querySelectorAll('table input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = source.checked;
        });
    }

    // Export function
    function exportAssets() {
        // Implement export logic
    }

    // Save functions
    function saveAsset() {
        // Implement save logic
        closeAssetModal();
    }

    function saveCategory() {
        // Implement save logic
        closeCategoryModal();
    }

    function saveVendor() {
        // Implement save logic
        closeVendorModal();
    }

    // Load asset data for editing
    function loadAssetData(assetId) {
        // Implement load logic
    }
    </script>
</body>
</html>
