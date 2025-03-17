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
    <title>CAFM System - Next-Generation Facility Management</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/dark-mode.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="home-page">
    <header class="main-header">
        <nav class="nav-container">
            <div class="logo">
                <img src="assets/images/logo.png" alt="CAFM Logo">
            </div>
            <div class="nav-links">
                <a href="#features">Features</a>
                <a href="#modules">Modules</a>
                <a href="#benefits">Benefits</a>
                <a href="#testimonials">Testimonials</a>
                <a href="#contact">Contact</a>
                <a href="public/login.php" class="btn btn-primary">Login</a>
                <a href="public/register.php" class="btn btn-outline">Register</a>
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
                <a href="public/register.php" class="btn btn-primary">Get Started</a>
                <a href="#demo" class="btn btn-secondary">Watch Demo</a>
            </div>
        </div>
        <div class="hero-overlay"></div>
    </section>

    <section id="features" class="features">
        <div class="section-container">
            <div class="section-header">
                <h2>Key Features</h2>
                <p>Our comprehensive suite of features designed to revolutionize facility management</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <i class="fas fa-robot"></i>
                    <h3>AI-Powered Analytics</h3>
                    <p>Smart predictions and automated decision-making for optimal facility management with machine learning algorithms that improve over time.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-building"></i>
                    <h3>Smart Building Integration</h3>
                    <p>IoT sensors and real-time monitoring for enhanced building performance, creating a responsive and intelligent environment.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-tasks"></i>
                    <h3>Work Order Management</h3>
                    <p>Automated workflow and intelligent task assignment system that prioritizes tasks and allocates resources efficiently.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-chart-line"></i>
                    <h3>Energy Management</h3>
                    <p>Advanced analytics for energy optimization and sustainability, reducing costs and environmental impact through smart monitoring.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-mobile-alt"></i>
                    <h3>Mobile Workforce</h3>
                    <p>Empower your team with mobile access to critical information and tasks from anywhere, anytime.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-shield-alt"></i>
                    <h3>Security & Risk Management</h3>
                    <p>Comprehensive security protocols and risk assessment tools to protect your facilities and data.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="modules" class="modules">
        <div class="section-container">
            <div class="section-header">
                <h2>System Modules</h2>
                <p>Our integrated modules work seamlessly together to provide a complete facility management solution</p>
            </div>
            <div class="module-grid">
                <div class="module-card">
                    <div class="module-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <h3>Asset Management</h3>
                    <ul>
                        <li>Comprehensive asset tracking and inventory</li>
                        <li>Detailed maintenance history and records</li>
                        <li>Lifecycle management and depreciation</li>
                        <li>QR code and barcode integration</li>
                        <li>Warranty and contract tracking</li>
                    </ul>
                </div>
                <div class="module-card">
                    <div class="module-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <h3>Facility Management</h3>
                    <ul>
                        <li>Intelligent work order system</li>
                        <li>Preventive maintenance scheduling</li>
                        <li>Service request management</li>
                        <li>Resource allocation optimization</li>
                        <li>Compliance and regulatory tracking</li>
                    </ul>
                </div>
                <div class="module-card">
                    <div class="module-icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <h3>Space Management</h3>
                    <ul>
                        <li>Real-time space utilization metrics</li>
                        <li>Occupancy tracking and analytics</li>
                        <li>Visual floor planning and mapping</li>
                        <li>Booking and reservation system</li>
                        <li>Space optimization recommendations</li>
                    </ul>
                </div>
                <div class="module-card">
                    <div class="module-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3>Vendor Management</h3>
                    <ul>
                        <li>Dedicated contractor portal</li>
                        <li>Performance metrics and KPIs</li>
                        <li>SLA monitoring and compliance</li>
                        <li>Automated invoice processing</li>
                        <li>Vendor qualification and rating</li>
                    </ul>
                </div>
                <div class="module-card">
                    <div class="module-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3>Energy Management</h3>
                    <ul>
                        <li>Real-time energy consumption monitoring</li>
                        <li>Automated anomaly detection</li>
                        <li>Sustainability reporting and metrics</li>
                        <li>Energy optimization recommendations</li>
                        <li>Carbon footprint tracking</li>
                    </ul>
                </div>
                <div class="module-card">
                    <div class="module-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3>Mobile Workforce</h3>
                    <ul>
                        <li>Field service mobile application</li>
                        <li>Real-time task assignment</li>
                        <li>GPS tracking and route optimization</li>
                        <li>Digital forms and checklists</li>
                        <li>Photo documentation and reporting</li>
                    </ul>
                </div>
                <div class="module-card">
                    <div class="module-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Analytics & Reporting</h3>
                    <ul>
                        <li>Customizable executive dashboards</li>
                        <li>Advanced data visualization</li>
                        <li>Predictive maintenance analytics</li>
                        <li>Cost analysis and budgeting tools</li>
                        <li>Automated report generation</li>
                    </ul>
                </div>
                <div class="module-card">
                    <div class="module-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Security & Risk Management</h3>
                    <ul>
                        <li>Access control integration</li>
                        <li>Security incident tracking</li>
                        <li>Risk assessment tools</li>
                        <li>Emergency response planning</li>
                        <li>Compliance documentation</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section id="benefits" class="benefits">
        <div class="section-container">
            <div class="section-header light">
                <h2>Business Benefits</h2>
                <p>Discover how our CAFM system delivers measurable value to your organization</p>
            </div>
            <div class="benefits-container">
                <div class="benefit-item">
                    <i class="fas fa-dollar-sign"></i>
                    <h3>Cost Reduction</h3>
                    <p>30% reduction in operational costs through smart automation and predictive maintenance</p>
                </div>
                <div class="benefit-item">
                    <i class="fas fa-clock"></i>
                    <h3>Time Savings</h3>
                    <p>50% faster response times for maintenance requests with automated workflows</p>
                </div>
                <div class="benefit-item">
                    <i class="fas fa-leaf"></i>
                    <h3>Sustainability</h3>
                    <p>25% reduction in energy consumption through intelligent monitoring and optimization</p>
                </div>
                <div class="benefit-item">
                    <i class="fas fa-chart-bar"></i>
                    <h3>Increased Productivity</h3>
                    <p>40% improvement in workforce efficiency with mobile tools and optimized scheduling</p>
                </div>
            </div>
        </div>
    </section>

    <section id="testimonials" class="testimonials">
        <div class="section-container">
            <div class="section-header">
                <h2>What Our Clients Say</h2>
                <p>Hear from organizations that have transformed their facility management with our solution</p>
            </div>
            <div class="testimonial-slider">
                <div class="testimonial-item">
                    <div class="testimonial-content">
                        <p>"The CAFM system has revolutionized how we manage our facilities. The predictive maintenance alone has saved us thousands in repair costs."</p>
                    </div>
                    <div class="testimonial-author">
                        <img src="assets/images/testimonial1.jpg" alt="Testimonial Author">
                        <div class="author-info">
                            <h4>Sarah Johnson</h4>
                            <p>Facilities Director, Global Enterprises</p>
                        </div>
                    </div>
                </div>
                <div class="testimonial-item">
                    <div class="testimonial-content">
                        <p>"The mobile workforce module has been a game-changer for our team. We've seen response times cut in half and customer satisfaction soar."</p>
                    </div>
                    <div class="testimonial-author">
                        <img src="assets/images/testimonial2.jpg" alt="Testimonial Author">
                        <div class="author-info">
                            <h4>Michael Chen</h4>
                            <p>Operations Manager, Tech Innovations</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="testimonial-dots">
                <span class="dot active"></span>
                <span class="dot"></span>
            </div>
        </div>
    </section>

    <section id="contact" class="contact">
        <div class="section-container">
            <div class="section-header">
                <h2>Get In Touch</h2>
                <p>Have questions about our CAFM system? Contact us today for more information</p>
            </div>
            <div class="contact-container">
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <h4>Location</h4>
                            <p>123 Business Avenue, Tech District</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <div>
                            <h4>Phone</h4>
                            <p>+1 234 567 890</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <h4>Email</h4>
                            <p>info@cafm-system.com</p>
                        </div>
                    </div>
                </div>
                <form class="contact-form">
                    <div class="form-group">
                        <input type="text" placeholder="Your Name" required>
                    </div>
                    <div class="form-group">
                        <input type="email" placeholder="Your Email" required>
                    </div>
                    <div class="form-group">
                        <select>
                            <option value="" disabled selected>Select Inquiry Type</option>
                            <option value="demo">Request Demo</option>
                            <option value="quote">Request Quote</option>
                            <option value="support">Technical Support</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <textarea placeholder="Your Message" required rows="5"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Send Message</button>
                </form>
            </div>
        </div>
    </section>

    <footer class="main-footer">
        <div class="footer-content">
            <div class="footer-section">
                <h4>About Us</h4>
                <p>Leading provider of AI-powered facility management solutions, helping organizations optimize their operations and reduce costs.</p>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div class="footer-section">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#modules">Modules</a></li>
                    <li><a href="#benefits">Benefits</a></li>
                    <li><a href="#testimonials">Testimonials</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Solutions</h4>
                <ul>
                    <li><a href="#">For Corporate Offices</a></li>
                    <li><a href="#">For Healthcare Facilities</a></li>
                    <li><a href="#">For Educational Institutions</a></li>
                    <li><a href="#">For Retail Spaces</a></li>
                    <li><a href="#">For Government Buildings</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Contact Info</h4>
                <p><i class="fas fa-map-marker-alt"></i> 123 Business Avenue, Tech District</p>
                <p><i class="fas fa-phone"></i> +1 234 567 890</p>
                <p><i class="fas fa-envelope"></i> info@cafm-system.com</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 CAFM System. All rights reserved.</p>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
    <script>
        // Simple testimonial slider
        document.addEventListener('DOMContentLoaded', function() {
            const testimonials = document.querySelectorAll('.testimonial-item');
            const dots = document.querySelectorAll('.dot');
            let currentIndex = 0;

            function showTestimonial(index) {
                testimonials.forEach(item => item.style.display = 'none');
                dots.forEach(dot => dot.classList.remove('active'));
                
                testimonials[index].style.display = 'block';
                dots[index].classList.add('active');
            }

            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    currentIndex = index;
                    showTestimonial(currentIndex);
                });
            });

            // Auto-rotate testimonials
            setInterval(() => {
                currentIndex = (currentIndex + 1) % testimonials.length;
                showTestimonial(currentIndex);
            }, 5000);

            // Initialize
            showTestimonial(currentIndex);
        });
    </script>
</body>
</html> 