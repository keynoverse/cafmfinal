<?php
require_once 'config/config.php';
require_once 'controllers/AuthController.php';

$auth = new AuthController($conn);
if ($auth->isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAFM System - Facility Management System</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/dark-mode.css">
</head>
<body class="home-page">
    <header class="main-header">
        <nav class="nav-container">
            <div class="logo">
                <img src="/assets/images/logo.png" alt="CAFM Logo">
            </div>
            <div class="nav-links">
                <a href="#features">Features</a>
                <a href="#modules">Modules</a>
                <a href="#benefits">Benefits</a>
                <a href="#contact">Contact</a>
                <a href="/login.php" class="btn btn-primary">Login</a>
                <a href="/register.php" class="btn btn-outline">Register</a>
            </div>
            <div class="menu-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </nav>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h1>Next-Generation Facility Management</h1>
            <p>AI-Powered CAFM System for Smart Building Management</p>
            <div class="hero-buttons">
                <a href="register.php" class="btn btn-primary">Get Started</a>
                <a href="#demo" class="btn btn-secondary">Watch Demo</a>
            </div>
        </div>
    </section>

    <section id="features" class="features">
        <h2>Key Features</h2>
        <div class="features-grid">
            <div class="feature-card">
                <i class="fas fa-robot"></i>
                <h3>AI-Powered Analytics</h3>
                <p>Smart predictions and automated decision-making for optimal facility management.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-building"></i>
                <h3>Smart Building Integration</h3>
                <p>IoT sensors and real-time monitoring for enhanced building performance.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-tasks"></i>
                <h3>Work Order Management</h3>
                <p>Automated workflow and intelligent task assignment system.</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-chart-line"></i>
                <h3>Energy Management</h3>
                <p>Advanced analytics for energy optimization and sustainability.</p>
            </div>
        </div>
    </section>

    <section id="modules" class="modules">
        <h2>System Modules</h2>
        <div class="module-grid">
            <div class="module-card">
                <h3>Asset Management</h3>
                <ul>
                    <li>Asset Tracking</li>
                    <li>Maintenance History</li>
                    <li>Lifecycle Management</li>
                </ul>
            </div>
            <div class="module-card">
                <h3>Facility Management</h3>
                <ul>
                    <li>Work Order System</li>
                    <li>PPM Scheduling</li>
                    <li>Service Requests</li>
                </ul>
            </div>
            <div class="module-card">
                <h3>Space Management</h3>
                <ul>
                    <li>Space Utilization</li>
                    <li>Occupancy Tracking</li>
                    <li>Visual Planning</li>
                </ul>
            </div>
            <div class="module-card">
                <h3>Vendor Management</h3>
                <ul>
                    <li>Contractor Portal</li>
                    <li>Performance Metrics</li>
                    <li>SLA Monitoring</li>
                </ul>
            </div>
        </div>
    </section>

    <section id="benefits" class="benefits">
        <h2>Business Benefits</h2>
        <div class="benefits-container">
            <div class="benefit-item">
                <i class="fas fa-dollar-sign"></i>
                <h3>Cost Reduction</h3>
                <p>30% reduction in operational costs through smart automation</p>
            </div>
            <div class="benefit-item">
                <i class="fas fa-clock"></i>
                <h3>Time Savings</h3>
                <p>50% faster response times for maintenance requests</p>
            </div>
            <div class="benefit-item">
                <i class="fas fa-leaf"></i>
                <h3>Sustainability</h3>
                <p>25% reduction in energy consumption</p>
            </div>
        </div>
    </section>

    <section id="contact" class="contact">
        <h2>Get In Touch</h2>
        <div class="contact-container">
            <form class="contact-form">
                <div class="form-group">
                    <input type="text" placeholder="Your Name" required>
                </div>
                <div class="form-group">
                    <input type="email" placeholder="Your Email" required>
                </div>
                <div class="form-group">
                    <textarea placeholder="Your Message" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Send Message</button>
            </form>
        </div>
    </section>

    <footer class="main-footer">
        <div class="footer-content">
            <div class="footer-section">
                <h4>About Us</h4>
                <p>Leading provider of AI-powered facility management solutions.</p>
            </div>
            <div class="footer-section">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#modules">Modules</a></li>
                    <li><a href="#benefits">Benefits</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Contact Info</h4>
                <p>Email: info@cafm-system.com</p>
                <p>Phone: +1 234 567 890</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 CAFM System. All rights reserved.</p>
        </div>
    </footer>

    <script src="/assets/js/main.js"></script>
</body>
</html> 