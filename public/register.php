<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Redirect if already logged in
redirectIfLoggedIn();

$error = '';
$success = '';
$userTypes = [
    'tenant' => 'Tenant',
    'service_provider' => 'Service Provider',
    'client_enquiry' => 'Client Enquiry Desk',
    'landlord' => 'Landlord (Admin)',
    'manager' => 'Manager'
];

include '../includes/header.php';
?>

<div class="register-container">
    <div class="register-box">
        <h1>Registration</h1>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <!-- User Type Selection Form -->
        <div class="user-type-selection" id="userTypeForm">
            <h2>Select User Type</h2>
            <?php foreach ($userTypes as $value => $label): ?>
                <div class="user-type-option">
                    <button type="button" 
                            class="btn btn-outline-primary btn-block mb-2"
                            onclick="showRegistrationForm('<?php echo $value; ?>')">
                        <?php echo $label; ?>
                    </button>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Tenant Registration Form -->
        <form id="tenantForm" class="registration-form" style="display: none;">
            <h2>Tenant Registration</h2>
            
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Mobile Number</label>
                <input type="tel" name="mobile" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Company Type</label>
                <select name="company_type" class="form-control" required>
                    <option value="expo">Expo City</option>
                    <option value="non_expo">Non-Expo City</option>
                </select>
            </div>
            
            <div id="nonExpoFields">
                <div class="form-group">
                    <label>Company Name</label>
                    <input type="text" name="company_name" class="form-control">
                </div>
                
                <div class="form-group">
                    <label>Trade License</label>
                    <input type="file" name="trade_license" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                </div>
                
                <div class="form-group">
                    <label>Lease Contract</label>
                    <input type="file" name="lease_contract" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                </div>
            </div>
            
            <div id="expoFields" style="display: none;">
                <div class="form-group">
                    <label>Department Name</label>
                    <input type="text" name="department" class="form-control">
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">Register</button>
            <button type="button" class="btn btn-secondary btn-block" onclick="showUserTypeSelection()">Back</button>
        </form>

        <!-- Service Provider Registration Form -->
        <form id="serviceProviderForm" class="registration-form" style="display: none;">
            <h2>Service Provider Registration</h2>
            
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Mobile Number</label>
                <input type="tel" name="mobile" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Company Name</label>
                <input type="text" name="company_name" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Service Categories</label>
                <select name="categories[]" class="form-control" multiple required>
                    <option value="electrical">Electrical</option>
                    <option value="plumbing">Plumbing</option>
                    <option value="hvac">HVAC</option>
                    <option value="cleaning">Cleaning</option>
                    <option value="security">Security</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Trade License</label>
                <input type="file" name="trade_license" class="form-control" required accept=".pdf,.jpg,.jpeg,.png">
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">Register</button>
            <button type="button" class="btn btn-secondary btn-block" onclick="showUserTypeSelection()">Back</button>
        </form>

        <!-- Client Enquiry Registration Form -->
        <form id="client_enquiryForm" class="registration-form" style="display: none;">
            <h2>Client Enquiry Desk Registration</h2>
            
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Mobile Number</label>
                <input type="tel" name="mobile" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Department</label>
                <select name="department" class="form-control" required>
                    <option value="">Select Department</option>
                    <option value="customer_service">Customer Service</option>
                    <option value="maintenance">Maintenance</option>
                    <option value="operations">Operations</option>
                    <option value="security">Security</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Employee ID</label>
                <input type="text" name="employee_id" class="form-control" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">Register</button>
            <button type="button" class="btn btn-secondary btn-block" onclick="showUserTypeSelection()">Back</button>
        </form>

        <!-- Landlord Registration Form -->
        <form id="landlordForm" class="registration-form" style="display: none;">
            <h2>Landlord (Admin) Registration</h2>
            
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Mobile Number</label>
                <input type="tel" name="mobile" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Organization</label>
                <input type="text" name="organization" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Position</label>
                <input type="text" name="position" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Authorization Document</label>
                <input type="file" name="auth_document" class="form-control" required accept=".pdf,.jpg,.jpeg,.png">
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">Register</button>
            <button type="button" class="btn btn-secondary btn-block" onclick="showUserTypeSelection()">Back</button>
        </form>

        <!-- Manager Registration Form -->
        <form id="managerForm" class="registration-form" style="display: none;">
            <h2>Manager Registration</h2>
            
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Mobile Number</label>
                <input type="tel" name="mobile" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Department</label>
                <select name="department" class="form-control" required>
                    <option value="">Select Department</option>
                    <option value="facility">Facility Management</option>
                    <option value="operations">Operations</option>
                    <option value="maintenance">Maintenance</option>
                    <option value="security">Security</option>
                    <option value="energy">Energy Management</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Employee ID</label>
                <input type="text" name="employee_id" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Access Level</label>
                <select name="access_level" class="form-control" required>
                    <option value="department">Department Manager</option>
                    <option value="senior">Senior Manager</option>
                    <option value="executive">Executive Manager</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">Register</button>
            <button type="button" class="btn btn-secondary btn-block" onclick="showUserTypeSelection()">Back</button>
        </form>
    </div>
</div>

<script>
function showRegistrationForm(userType) {
    // Hide all forms
    document.querySelectorAll('.registration-form').forEach(form => {
        form.style.display = 'none';
    });
    
    // Hide user type selection
    document.getElementById('userTypeForm').style.display = 'none';
    
    // Show selected form
    document.getElementById(userType + 'Form').style.display = 'block';
}

function showUserTypeSelection() {
    // Hide all forms
    document.querySelectorAll('.registration-form').forEach(form => {
        form.style.display = 'none';
    });
    
    // Show user type selection
    document.getElementById('userTypeForm').style.display = 'block';
}

// Company type toggle for tenant registration
document.querySelector('select[name="company_type"]')?.addEventListener('change', function() {
    const nonExpoFields = document.getElementById('nonExpoFields');
    const expoFields = document.getElementById('expoFields');
    
    if (this.value === 'expo') {
        nonExpoFields.style.display = 'none';
        expoFields.style.display = 'block';
    } else {
        nonExpoFields.style.display = 'block';
        expoFields.style.display = 'none';
    }
});
</script>

<!-- Add this before closing </head> tag -->
<script src="<?php echo BASE_URL; ?>/assets/js/register-validation.js" defer></script>

<?php include '../includes/footer.php'; ?> 