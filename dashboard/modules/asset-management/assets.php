<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../../includes/header.php';
require_once '../../../includes/navigation.php';
?>

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

<?php
require_once '../../../includes/footer.php';
?>
