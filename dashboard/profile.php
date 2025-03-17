<?php
require_once '../config/config.php';
require_once PROJECT_ROOT . '/controllers/AuthController.php';
require_once PROJECT_ROOT . '/controllers/UserController.php';

$auth = new AuthController($conn);
if (!$auth->isLoggedIn()) {
    header('Location: ../public/login.php');
    exit;
}

$user = $auth->getCurrentUser();
$userController = new UserController($conn);

$success = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        if (isset($_POST['update_profile'])) {
            // Update user profile
            $userData = [
                'name' => $_POST['name'] ?? '',
                'mobile' => $_POST['mobile'] ?? '',
                'company' => $_POST['company'] ?? '',
                'department' => $_POST['department'] ?? ''
            ];
            
            if ($userController->updateUser($user['id'], $userData)) {
                $success = 'Profile updated successfully!';
                $user = $auth->getCurrentUser(); // Refresh user data
            } else {
                $error = 'Failed to update profile. Please try again.';
            }
        } elseif (isset($_POST['change_password'])) {
            // Change password
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                $error = 'All password fields are required.';
            } elseif ($newPassword !== $confirmPassword) {
                $error = 'New passwords do not match.';
            } elseif ($auth->changePassword($user['id'], $currentPassword, $newPassword)) {
                $success = 'Password changed successfully!';
            } else {
                $error = 'Failed to change password. Current password may be incorrect.';
            }
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - CAFM System</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/dark-mode.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .profile-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 20px;
        }
        
        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 20px;
            border: 3px solid var(--accent-color, #00c6ff);
        }
        
        .profile-info h2 {
            margin: 0 0 5px 0;
        }
        
        .profile-info p {
            margin: 0;
            color: var(--secondary-color, #adb5bd);
        }
        
        .profile-stats {
            margin-top: 10px;
            font-size: 14px;
            color: var(--secondary-color, #adb5bd);
        }
        
        .profile-stats span {
            margin-right: 15px;
        }
        
        .profile-card {
            background: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .profile-card h3 {
            margin-top: 0;
            font-size: 18px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
            color: var(--text-color-dark, #333);
        }
        
        .nav-tabs {
            display: flex;
            border-bottom: 1px solid #eee;
            margin-bottom: 20px;
        }
        
        .nav-tabs .tab {
            padding: 10px 20px;
            cursor: pointer;
            margin-right: 5px;
            border-bottom: 2px solid transparent;
        }
        
        .nav-tabs .tab.active {
            border-bottom-color: var(--accent-color, #00c6ff);
            color: var(--accent-color, #00c6ff);
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar - Include same sidebar code from index.php -->
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
                    <li>
                        <a href="index.php">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    
                    <!-- Add the same module groups and links as in index.php -->
                    
                    <li class="active">
                        <a href="profile.php">
                            <i class="fas fa-user"></i>
                            <span>My Profile</span>
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
                <div class="header-left">
                    <button class="toggle-sidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1>My Profile</h1>
                </div>
                <div class="header-actions">
                    <a href="index.php" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </header>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <div class="profile-header">
                <img src="../assets/images/default-avatar.png" alt="User Avatar" class="profile-avatar">
                <div class="profile-info">
                    <h2><?php echo htmlspecialchars($user['name']); ?></h2>
                    <p><?php echo htmlspecialchars($user['email']); ?></p>
                    <p><?php echo htmlspecialchars(ucfirst($user['user_type'])); ?></p>
                    <div class="profile-stats">
                        <span><i class="fas fa-clock"></i> Member since: <?php echo date('M Y', strtotime($user['created_at'])); ?></span>
                        <span><i class="fas fa-sign-in-alt"></i> Last login: <?php echo !empty($user['last_login']) ? date('d M Y, H:i', strtotime($user['last_login'])) : 'Never'; ?></span>
                    </div>
                </div>
            </div>

            <div class="nav-tabs">
                <div class="tab active" data-tab="profile">Personal Information</div>
                <div class="tab" data-tab="security">Security</div>
                <div class="tab" data-tab="preferences">Preferences</div>
            </div>

            <div class="profile-container">
                <!-- Profile Information Tab -->
                <div class="tab-content active" id="profile-tab">
                    <div class="profile-card">
                        <h3>Profile Information</h3>
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       value="<?php echo htmlspecialchars($user['name']); ?>" 
                                       class="form-control"
                                       required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" 
                                       id="email" 
                                       value="<?php echo htmlspecialchars($user['email']); ?>" 
                                       class="form-control"
                                       disabled>
                                <small>Email cannot be changed. Contact support if needed.</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="mobile">Mobile Number</label>
                                <input type="text" 
                                       id="mobile" 
                                       name="mobile" 
                                       value="<?php echo htmlspecialchars($user['mobile'] ?? ''); ?>" 
                                       class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label for="company">Company</label>
                                <input type="text" 
                                       id="company" 
                                       name="company" 
                                       value="<?php echo htmlspecialchars($user['company'] ?? ''); ?>" 
                                       class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label for="department">Department</label>
                                <input type="text" 
                                       id="department" 
                                       name="department" 
                                       value="<?php echo htmlspecialchars($user['department'] ?? ''); ?>" 
                                       class="form-control">
                            </div>
                            
                            <button type="submit" name="update_profile" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Profile
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Security Tab -->
                <div class="tab-content" id="security-tab">
                    <div class="profile-card">
                        <h3>Change Password</h3>
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="current_password">Current Password</label>
                                <input type="password" 
                                       id="current_password" 
                                       name="current_password" 
                                       class="form-control"
                                       required>
                            </div>
                            
                            <div class="form-group">
                                <label for="new_password">New Password</label>
                                <input type="password" 
                                       id="new_password" 
                                       name="new_password" 
                                       class="form-control"
                                       required>
                                <small>Password must be at least 8 characters long</small>
                            </div>
                            
                            <div class="form-group">
                                <label for="confirm_password">Confirm New Password</label>
                                <input type="password" 
                                       id="confirm_password" 
                                       name="confirm_password" 
                                       class="form-control"
                                       required>
                            </div>
                            
                            <button type="submit" name="change_password" class="btn btn-primary">
                                <i class="fas fa-key"></i> Change Password
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Preferences Tab -->
                <div class="tab-content" id="preferences-tab">
                    <div class="profile-card">
                        <h3>User Preferences</h3>
                        <form method="POST" action="">
                            <div class="form-group">
                                <label>Theme</label>
                                <div class="toggle-switch">
                                    <input type="checkbox" id="theme-switch" class="toggle-input">
                                    <label for="theme-switch" class="toggle-label">
                                        <span class="toggle-inner"></span>
                                        <span class="toggle-switch-label">Dark Mode</span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Notifications</label>
                                <div class="checkbox-group">
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="notify-email" name="notify_email" checked>
                                        <label for="notify-email">Email Notifications</label>
                                    </div>
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="notify-browser" name="notify_browser" checked>
                                        <label for="notify-browser">Browser Notifications</label>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="submit" name="update_preferences" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Preferences
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/dashboard.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab switching
            const tabs = document.querySelectorAll('.tab');
            const tabContents = document.querySelectorAll('.tab-content');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');
                    
                    // Remove active class from all tabs and contents
                    tabs.forEach(t => t.classList.remove('active'));
                    tabContents.forEach(c => c.classList.remove('active'));
                    
                    // Add active class to current tab and content
                    this.classList.add('active');
                    document.getElementById(`${tabId}-tab`).classList.add('active');
                });
            });
            
            // Theme switcher
            const themeSwitch = document.getElementById('theme-switch');
            const currentTheme = document.documentElement.getAttribute('data-theme');
            
            if (currentTheme === 'dark') {
                themeSwitch.checked = true;
            }
            
            themeSwitch.addEventListener('change', function() {
                if (this.checked) {
                    document.documentElement.setAttribute('data-theme', 'dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.documentElement.setAttribute('data-theme', 'light');
                    localStorage.setItem('theme', 'light');
                }
            });
        });
    </script>
</body>
</html> 