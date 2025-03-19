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
        <!-- Include navigation -->
        <?php include '../../../includes/navigation.php'; ?>

        <div class="main-content">
            <div class="content-header">
                <div class="header-left">
                    <h1>Asset Management</h1>
                    <nav class="breadcrumb">
                        <a href="../../index.php">Dashboard</a> /
                        <span>Asset Management</span>
                    </nav>
                </div>
                <div class="header-actions">
                    <button class="btn btn-primary" onclick="openModal('addAssetModal')">
                        <i class="fas fa-plus"></i> Add New Asset
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
                        <div class="number">2,547</div>
                        <div class="trend positive">+5.2% <i class="fas fa-arrow-up"></i></div>
                    </div>
                </div>
                <div class="analytics-card">
                    <div class="card-icon bg-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="card-content">
                        <h3>Active Assets</h3>
                        <div class="number">2,103</div>
                        <div class="trend positive">+3.1% <i class="fas fa-arrow-up"></i></div>
                    </div>
                </div>
                <div class="analytics-card">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div class="card-content">
                        <h3>Under Maintenance</h3>
                        <div class="number">342</div>
                        <div class="trend negative">-2.4% <i class="fas fa-arrow-down"></i></div>
                    </div>
                </div>
                <div class="analytics-card">
                    <div class="card-icon bg-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="card-content">
                        <h3>Critical Issues</h3>
                        <div class="number">102</div>
                        <div class="trend negative">+1.8% <i class="fas fa-arrow-up"></i></div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions-grid">
                <div class="action-card" onclick="openModal('generateQRModal')">
                    <i class="fas fa-qrcode"></i>
                    <span>Generate QR Codes</span>
                </div>
                <div class="action-card" onclick="openModal('assetReportModal')">
                    <i class="fas fa-file-alt"></i>
                    <span>Asset Report</span>
                </div>
                <div class="action-card" onclick="openModal('maintenanceScheduleModal')">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Maintenance Schedule</span>
                </div>
                <div class="action-card" onclick="openModal('exportDataModal')">
                    <i class="fas fa-file-export"></i>
                    <span>Export Data</span>
                </div>
            </div>

            <!-- Filters Panel -->
            <div class="filters-panel" id="filtersPanel">
                <div class="filters-grid">
                    <div class="filter-group">
                        <label>Search</label>
                        <input type="text" class="form-control" placeholder="Search assets...">
                    </div>
                    <div class="filter-group">
                        <label>Category</label>
                        <select class="form-control">
                            <option value="">All Categories</option>
                            <option value="equipment">Equipment</option>
                            <option value="furniture">Furniture</option>
                            <option value="it">IT Assets</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Status</label>
                        <select class="form-control">
                            <option value="">All Statuses</option>
                            <option value="active">Active</option>
                            <option value="maintenance">Under Maintenance</option>
                            <option value="inactive">Inactive</option>
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
                </div>
                <div class="filters-actions">
                    <button class="btn btn-primary" onclick="applyFilters()">Apply Filters</button>
                    <button class="btn btn-secondary" onclick="resetFilters()">Reset</button>
                </div>
            </div>

            <!-- Assets Table -->
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>Asset ID</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Purchase Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Sample row, will be populated dynamically -->
                        <tr>
                            <td><input type="checkbox" class="row-checkbox"></td>
                            <td>AST001</td>
                            <td>HP Laptop</td>
                            <td>IT Equipment</td>
                            <td>Floor 1, Room 101</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td>2023-01-15</td>
                            <td class="action-buttons">
                                <button class="btn-icon" onclick="viewAsset('AST001')">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-icon" onclick="editAsset('AST001')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-icon" onclick="deleteAsset('AST001')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="table-footer">
                    <div class="table-info">Showing 1-10 of 100 assets</div>
                    <div class="pagination">
                        <button class="btn btn-icon"><i class="fas fa-chevron-left"></i></button>
                        <button class="btn btn-primary">1</button>
                        <button class="btn">2</button>
                        <button class="btn">3</button>
                        <button class="btn btn-icon"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Asset Modal -->
    <div class="modal" id="addAssetModal">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h2>Add New Asset</h2>
                <button class="close" onclick="closeModal('addAssetModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-grid">
                    <!-- Basic Information -->
                    <div class="form-section">
                        <h3>Basic Information</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Asset Name</label>
                                <input type="text" class="form-control" placeholder="Enter asset name">
                            </div>
                            <div class="form-group">
                                <label>Asset Category</label>
                                <div class="input-group">
                                    <select class="form-control">
                                        <option value="">Select Category</option>
                                    </select>
                                    <button class="btn" onclick="openModal('addCategoryModal')">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Location Information -->
                    <div class="form-section">
                        <h3>Location</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Building</label>
                                <select class="form-control">
                                    <option value="">Select Building</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Floor</label>
                                <select class="form-control">
                                    <option value="">Select Floor</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Room</label>
                                <input type="text" class="form-control" placeholder="Enter room number">
                            </div>
                        </div>
                    </div>

                    <!-- Procurement Details -->
                    <div class="form-section">
                        <h3>Procurement Details</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Purchase Date</label>
                                <input type="date" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Purchase Cost</label>
                                <input type="number" class="form-control" placeholder="Enter cost">
                            </div>
                            <div class="form-group">
                                <label>Vendor</label>
                                <div class="input-group">
                                    <select class="form-control">
                                        <option value="">Select Vendor</option>
                                    </select>
                                    <button class="btn" onclick="openModal('addVendorModal')">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Technical Specifications -->
                    <div class="form-section">
                        <h3>Technical Specifications</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Model Number</label>
                                <input type="text" class="form-control" placeholder="Enter model number">
                            </div>
                            <div class="form-group">
                                <label>Serial Number</label>
                                <input type="text" class="form-control" placeholder="Enter serial number">
                            </div>
                            <div class="form-group">
                                <label>Manufacturer</label>
                                <input type="text" class="form-control" placeholder="Enter manufacturer">
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
                            <div class="form-group">
                                <label>Warranty Type</label>
                                <select class="form-control">
                                    <option value="">Select Type</option>
                                    <option value="standard">Standard</option>
                                    <option value="extended">Extended</option>
                                    <option value="premium">Premium</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Maintenance Schedule -->
                    <div class="form-section">
                        <h3>Maintenance Schedule</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Maintenance Frequency</label>
                                <select class="form-control">
                                    <option value="">Select Frequency</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="quarterly">Quarterly</option>
                                    <option value="yearly">Yearly</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Next Maintenance Date</label>
                                <input type="date" class="form-control">
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="form-section">
                        <h3>Additional Information</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Notes</label>
                                <textarea class="form-control" rows="3" placeholder="Enter additional notes"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Attachments</label>
                                <input type="file" class="form-control" multiple>
                                <small class="form-text">Upload relevant documents (max 5MB each)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('addAssetModal')">Cancel</button>
                <button class="btn btn-primary" onclick="saveAsset()">Save Asset</button>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal" id="addCategoryModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Category</h2>
                <button class="close" onclick="closeModal('addCategoryModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Category Name</label>
                    <input type="text" class="form-control" placeholder="Enter category name">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" rows="3" placeholder="Enter category description"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('addCategoryModal')">Cancel</button>
                <button class="btn btn-primary" onclick="saveCategory()">Save Category</button>
            </div>
        </div>
    </div>

    <!-- Add Vendor Modal -->
    <div class="modal" id="addVendorModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Vendor</h2>
                <button class="close" onclick="closeModal('addVendorModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Vendor Name</label>
                    <input type="text" class="form-control" placeholder="Enter vendor name">
                </div>
                <div class="form-group">
                    <label>Contact Person</label>
                    <input type="text" class="form-control" placeholder="Enter contact person name">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" placeholder="Enter email address">
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="tel" class="form-control" placeholder="Enter phone number">
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <textarea class="form-control" rows="3" placeholder="Enter vendor address"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('addVendorModal')">Cancel</button>
                <button class="btn btn-primary" onclick="saveVendor()">Save Vendor</button>
            </div>
        </div>
    </div>

    <script src="../../../assets/js/main.js"></script>
    <script>
    // Modal functions
    function openModal(modalId) {
        document.getElementById(modalId).style.display = 'block';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    }

    // Filter functions
    function applyFilters() {
        // Implement filter logic
        console.log('Applying filters...');
    }

    function resetFilters() {
        // Reset all filter inputs
        document.querySelectorAll('.filter-group input, .filter-group select').forEach(input => {
            input.value = '';
        });
    }

    // Asset functions
    function viewAsset(assetId) {
        // Implement view logic
        console.log('Viewing asset:', assetId);
    }

    function editAsset(assetId) {
        // Load asset data and open modal
        loadAssetData(assetId);
        openModal('addAssetModal');
    }

    function deleteAsset(assetId) {
        if (confirm('Are you sure you want to delete this asset?')) {
            // Implement delete logic
            console.log('Deleting asset:', assetId);
        }
    }

    function saveAsset() {
        // Implement save logic
        closeModal('addAssetModal');
    }

    function saveCategory() {
        // Implement save logic
        closeModal('addCategoryModal');
    }

    function saveVendor() {
        // Implement save logic
        closeModal('addVendorModal');
    }

    // Load asset data for editing
    function loadAssetData(assetId) {
        // Implement load logic
        console.log('Loading asset data:', assetId);
    }
    </script>
</body>
</html>

</html>
