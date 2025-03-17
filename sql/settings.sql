-- Settings module tables

-- Menu items table 
CREATE TABLE IF NOT EXISTS menu_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    url VARCHAR(255) NOT NULL,
    icon VARCHAR(50),
    parent_id INT DEFAULT NULL,
    sort_order INT DEFAULT 0,
    is_module BOOLEAN DEFAULT FALSE,
    module_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES menu_items(id) ON DELETE CASCADE
);

-- Menu item permissions table (role-based)
CREATE TABLE IF NOT EXISTS menu_permissions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    menu_id INT NOT NULL,
    role_id INT NOT NULL,
    is_visible BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (menu_id) REFERENCES menu_items(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    UNIQUE KEY unique_menu_role (menu_id, role_id)
);

-- Modules table
CREATE TABLE IF NOT EXISTS modules (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    is_core BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Module permissions table (role-based)
CREATE TABLE IF NOT EXISTS module_permissions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    module_id INT NOT NULL,
    role_id INT NOT NULL,
    is_enabled BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    UNIQUE KEY unique_module_role (module_id, role_id)
);

-- Insert default modules
INSERT INTO modules (name, code, description, is_core) VALUES
('Asset Management', 'asset-management', 'Manage all facility assets', TRUE),
('Facility Management', 'facility-management', 'Core facility management features', TRUE),
('Client Management', 'client-master', 'Manage client relationships', TRUE),
('Vendor Management', 'vendor-master', 'Manage vendor relationships', TRUE),
('User Management', 'user-management', 'Manage users and roles', TRUE),
('Smart Building', 'smart-building', 'Smart building functionalities', FALSE),
('IoT Integration', 'iot-integration', 'Internet of Things integration', FALSE),
('Energy Management', 'energy-management', 'Energy usage and optimization', FALSE),
('Mobile Workforce', 'mobile-workforce', 'Mobile workforce management', FALSE),
('Analytics & Reporting', 'analytics', 'Advanced analytics and reporting', FALSE),
('Sustainability', 'sustainability', 'Sustainability initiatives', FALSE),
('Security & Risk', 'security-risk', 'Security and risk management', FALSE),
('Emergency Management', 'emergency', 'Emergency procedures and protocols', FALSE),
('Financial Control', 'financial', 'Financial management tools', FALSE),
('Settings', 'settings', 'System settings and configurations', TRUE);

-- Insert default menu items
INSERT INTO menu_items (name, url, icon, parent_id, sort_order, is_module, module_id) VALUES
('Dashboard', '/dashboard', 'fa-tachometer-alt', NULL, 1, FALSE, NULL),
('Core Modules', '#', 'fa-th-large', NULL, 2, FALSE, NULL),
('Asset Management', '/dashboard/asset-management', 'fa-cubes', 2, 1, TRUE, 1),
('Facility Management', '/dashboard/facility-management', 'fa-building', 2, 2, TRUE, 2),
('Client Management', '/dashboard/client-master', 'fa-users', 2, 3, TRUE, 3),
('Vendor Management', '/dashboard/vendor-master', 'fa-truck', 2, 4, TRUE, 4),
('Smart Building', '#', 'fa-microchip', NULL, 3, FALSE, NULL),
('AI & ML', '/dashboard/smart-building/ai-ml', 'fa-brain', 7, 1, TRUE, 6),
('IoT Integration', '/dashboard/iot-integration', 'fa-network-wired', 7, 2, TRUE, 7),
('Energy Management', '/dashboard/energy-management', 'fa-bolt', 7, 3, TRUE, 8),
('Operations', '#', 'fa-cogs', NULL, 4, FALSE, NULL),
('Mobile Workforce', '/dashboard/mobile-workforce', 'fa-mobile-alt', 11, 1, TRUE, 9),
('Security & Risk', '/dashboard/security-risk', 'fa-shield-alt', 11, 2, TRUE, 12),
('Emergency Management', '/dashboard/emergency', 'fa-exclamation-triangle', 11, 3, TRUE, 13),
('Analytics & Finance', '#', 'fa-chart-line', NULL, 5, FALSE, NULL),
('Analytics & Reporting', '/dashboard/analytics', 'fa-chart-bar', 15, 1, TRUE, 10),
('Sustainability', '/dashboard/sustainability', 'fa-leaf', 15, 2, TRUE, 11),
('Financial Control', '/dashboard/financial', 'fa-money-bill-wave', 15, 3, TRUE, 14),
('System', '#', 'fa-cog', NULL, 6, FALSE, NULL),
('User Management', '/dashboard/user-management', 'fa-user-cog', 19, 1, TRUE, 5),
('Settings', '/dashboard/settings', 'fa-sliders-h', 19, 2, TRUE, 15),
('Profile', '/dashboard/profile', 'fa-user-circle', 19, 3, FALSE, NULL),
('Logout', '/logout', 'fa-sign-out-alt', 19, 4, FALSE, NULL); 