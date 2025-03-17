document.addEventListener('DOMContentLoaded', function() {
    // Toggle submenu
    const submenuToggles = document.querySelectorAll('.has-submenu > a');
    submenuToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const parent = this.parentElement;
            parent.classList.toggle('open');
        });
    });

    // Toggle sidebar on mobile
    const sidebarToggle = document.querySelector('.toggle-sidebar');
    const sidebar = document.querySelector('.sidebar');
    const body = document.querySelector('body');
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('mobile-open');
            } else {
                body.classList.toggle('sidebar-collapsed');
            }
        });
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768 && 
            sidebar.classList.contains('mobile-open') && 
            !sidebar.contains(e.target) && 
            !sidebarToggle.contains(e.target)) {
            sidebar.classList.remove('mobile-open');
        }
    });

    // Initialize any charts or data visualizations
    initializeCharts();
});

function initializeCharts() {
    // This function would initialize any charts or data visualizations
    // For now, it's just a placeholder
    console.log('Charts initialized');
}

// Handle active menu items based on current page
function setActiveMenuItem() {
    const currentPath = window.location.pathname;
    const menuItems = document.querySelectorAll('.sidebar-nav a');
    
    menuItems.forEach(item => {
        const href = item.getAttribute('href');
        if (href && currentPath.includes(href) && href !== '#') {
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

// Call this function when the page loads
document.addEventListener('DOMContentLoaded', setActiveMenuItem); 