// Dark mode functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize theme from localStorage
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);

    // Premium animations for feature cards
    const featureCards = document.querySelectorAll('.feature-card');
    featureCards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            const angleX = (y - centerY) / 20;
            const angleY = (centerX - x) / 20;

            card.style.transform = `perspective(1000px) rotateX(${angleX}deg) rotateY(${angleY}deg) scale3d(1.02, 1.02, 1.02)`;
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale3d(1, 1, 1)';
        });
    });

    // Initialize navigation menu
    initializeNavigation();
    
    // Initialize modals
    initializeModals();
    
    // Initialize any other components
    initializeComponents();

    // Smooth scroll with premium easing
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                const targetPosition = target.offsetTop - 100;
                const startPosition = window.pageYOffset;
                const distance = targetPosition - startPosition;
                const duration = 1000;
                let start = null;

                function animation(currentTime) {
                    if (start === null) start = currentTime;
                    const timeElapsed = currentTime - start;
                    const progress = Math.min(timeElapsed / duration, 1);
                    
                    // Premium easing function
                    const ease = progress => {
                        return progress < 0.5
                            ? 4 * progress * progress * progress
                            : 1 - Math.pow(-2 * progress + 2, 3) / 2;
                    };

                    window.scrollTo(0, startPosition + (distance * ease(progress)));

                    if (timeElapsed < duration) {
                        requestAnimationFrame(animation);
                    }
                }

                requestAnimationFrame(animation);
            }
        });
    });

    // Premium header effect
    const header = document.querySelector('.main-header');
    let lastScroll = 0;

    window.addEventListener('scroll', () => {
        if (!header) return;
        
        const currentScroll = window.pageYOffset;
        const headerHeight = header.offsetHeight;
        
        // Add blur effect based on scroll
        const blur = Math.min(currentScroll / 200, 10);
        header.style.backdropFilter = `blur(${blur}px)`;
        
        // Add transparency based on scroll
        const alpha = Math.max(0.8 - (currentScroll / 1000), 0.6);
        header.style.backgroundColor = `rgba(45, 45, 45, ${alpha})`;
        
        if (currentScroll > headerHeight) {
            header.classList.add('header-scrolled');
            if (currentScroll > lastScroll) {
                header.classList.add('header-hidden');
            } else {
                header.classList.remove('header-hidden');
            }
        } else {
            header.classList.remove('header-scrolled');
        }
        
        lastScroll = currentScroll;
    });

    // Premium form interactions
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input, textarea');
        
        inputs.forEach(input => {
            // Add glow effect on focus
            input.addEventListener('focus', () => {
                input.style.boxShadow = '0 0 20px rgba(0, 198, 255, 0.3)';
            });

            input.addEventListener('blur', () => {
                input.style.boxShadow = 'none';
            });

            // Add ripple effect on click
            input.addEventListener('click', (e) => {
                const ripple = document.createElement('div');
                ripple.classList.add('ripple');
                input.appendChild(ripple);

                const rect = input.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;

                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';

                setTimeout(() => ripple.remove(), 1000);
            });
        });

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            let isValid = validateForm(this);
            
            if (isValid) {
                // Add success animation
                form.classList.add('form-success');
                setTimeout(() => form.classList.remove('form-success'), 2000);
            }
        });
    });

    // Animation on scroll
    const animateOnScroll = () => {
        const elements = document.querySelectorAll('.animate-on-scroll');
        
        elements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            const elementBottom = element.getBoundingClientRect().bottom;
            
            if (elementTop < window.innerHeight && elementBottom > 0) {
                element.classList.add('animated');
            }
        });
    };

    window.addEventListener('scroll', animateOnScroll);
    animateOnScroll(); // Initial check
});

function initializeNavigation() {
    // Handle submenu toggles
    const submenuToggles = document.querySelectorAll('.has-submenu > a');
    submenuToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const parent = this.parentElement;
            
            // Close other open submenus
            const openMenus = document.querySelectorAll('.has-submenu.open');
            openMenus.forEach(menu => {
                if (menu !== parent) {
                    menu.classList.remove('open');
                }
            });
            
            // Toggle current submenu
            parent.classList.toggle('open');
        });
    });

    // Handle mobile menu toggle
    const menuToggle = document.querySelector('.menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    
    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('mobile-open');
        });

        // Close sidebar when clicking outside
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 992 && 
                !e.target.closest('.sidebar') && 
                !e.target.closest('.menu-toggle')) {
                sidebar.classList.remove('mobile-open');
            }
        });
    }

    // Set active menu item based on current page
    setActiveMenuItem();
}

function initializeModals() {
    // Get all modal triggers
    const modalTriggers = document.querySelectorAll('[data-modal]');
    
    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            const modalId = this.getAttribute('data-modal');
            openModal(modalId);
        });
    });

    // Close modal handlers
    const closeButtons = document.querySelectorAll('.modal .close, .modal .btn-close');
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const modal = this.closest('.modal');
            closeModal(modal.id);
        });
    });

    // Close modal when clicking outside
    window.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal')) {
            closeModal(e.target.id);
        }
    });
}

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
}

function setActiveMenuItem() {
    const currentPath = window.location.pathname;
    const menuItems = document.querySelectorAll('.sidebar-nav a');
    
    menuItems.forEach(item => {
        if (item.getAttribute('href') === '#') return;
        
        const href = item.getAttribute('href');
        if (currentPath.includes(href)) {
            // Remove active class from all items
            menuItems.forEach(i => i.parentElement.classList.remove('active'));
            
            // Add active class to current item
            item.parentElement.classList.add('active');
            
            // If it's in a submenu, open the parent menu
            const submenu = item.closest('.submenu');
            if (submenu) {
                submenu.parentElement.classList.add('open');
            }
        }
    });
}

function initializeComponents() {
    // Initialize tooltips
    const tooltips = document.querySelectorAll('[data-tooltip]');
    tooltips.forEach(tooltip => {
        tooltip.addEventListener('mouseenter', showTooltip);
        tooltip.addEventListener('mouseleave', hideTooltip);
    });

    // Initialize dropdowns
    const dropdowns = document.querySelectorAll('.dropdown-toggle');
    dropdowns.forEach(dropdown => {
        dropdown.addEventListener('click', function(e) {
            e.preventDefault();
            this.nextElementSibling.classList.toggle('show');
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.matches('.dropdown-toggle')) {
            const dropdowns = document.querySelectorAll('.dropdown-menu.show');
            dropdowns.forEach(dropdown => {
                dropdown.classList.remove('show');
            });
        }
    });

    // Statistics counter animation
    const stats = document.querySelectorAll('.stat-item p');
    if (stats.length) {
        const options = {
            threshold: 0.5
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const target = entry.target;
                    const value = parseInt(target.getAttribute('data-value') || target.innerText);
                    animateValue(target, 0, value, 2000);
                    observer.unobserve(target);
                }
            });
        }, options);

        stats.forEach(stat => observer.observe(stat));
    }
}

// Helper function for number animation
function animateValue(element, start, end, duration) {
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        const value = Math.floor(progress * (end - start) + start);
        element.innerText = value;
        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    window.requestAnimationFrame(step);
}

// Form validation with premium error display
function validateForm(form) {
    let isValid = true;
    const inputs = form.querySelectorAll('input, textarea');
    
    inputs.forEach(input => {
        if (input.hasAttribute('required') && !input.value.trim()) {
            showError(input, 'This field is required');
            isValid = false;
        } else if (input.type === 'email' && input.value && !isValidEmail(input.value)) {
            showError(input, 'Please enter a valid email address');
            isValid = false;
        }
    });
    
    return isValid;
}

function showError(input, message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    
    // Add premium error animation
    errorDiv.style.animation = 'shake 0.5s ease-in-out';
    
    // Remove existing error message
    const existingError = input.parentNode.querySelector('.error-message');
    if (existingError) {
        existingError.remove();
    }
    
    input.classList.add('error');
    input.parentNode.appendChild(errorDiv);
    
    // Remove error after 3 seconds
    setTimeout(() => {
        errorDiv.remove();
        input.classList.remove('error');
    }, 3000);
}

function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

// Add premium shake animation
const shakeKeyframes = `
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    20%, 60% { transform: translateX(-5px); }
    40%, 80% { transform: translateX(5px); }
}
`;

const style = document.createElement('style');
style.textContent = shakeKeyframes;
document.head.appendChild(style);

// Utility functions
function showTooltip(e) {
    const tooltip = document.createElement('div');
    tooltip.className = 'tooltip';
    tooltip.textContent = this.getAttribute('data-tooltip');
    document.body.appendChild(tooltip);

    const rect = this.getBoundingClientRect();
    tooltip.style.top = rect.top - tooltip.offsetHeight - 10 + 'px';
    tooltip.style.left = rect.left + (rect.width - tooltip.offsetWidth) / 2 + 'px';
}

function hideTooltip() {
    const tooltips = document.querySelectorAll('.tooltip');
    tooltips.forEach(tooltip => tooltip.remove());
}

// Export functions for use in other files
window.openModal = openModal;
window.closeModal = closeModal; 