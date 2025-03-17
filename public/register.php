<?php
require_once '../config/config.php';
require_once '../controllers/AuthController.php';

$auth = new AuthController($conn);
if ($auth->isLoggedIn()) {
    header('Location: ../dashboard/index.php');
    exit;
}

$error = '';
$success = '';
$userTypes = [
    'tenant' => 'Tenant',
    'service_provider' => 'Service Provider',
    'client_enquiry' => 'Client Enquiry Desk',
    'landlord' => 'Landlord (Admin)',
    'manager' => 'Manager'
];
?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - CAFM System</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/dark-mode.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="register-page">
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

            <!-- Registration Forms -->
            <?php include '../views/user-management/registration_forms.php'; ?>
        </div>
    </div>

    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/register-validation.js"></script>
</body>
</html> 