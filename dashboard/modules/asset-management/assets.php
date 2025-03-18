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
                    <!-- Add more navigation items here -->
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
