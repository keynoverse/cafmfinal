-- First ensure we have the super_admin role
INSERT INTO roles (name, description, is_admin)
SELECT 'super_admin', 'Super Administrator with full system access', TRUE
WHERE NOT EXISTS (
    SELECT 1 FROM roles WHERE name = 'super_admin'
);

-- Get the super_admin role id
SET @admin_role_id = (SELECT id FROM roles WHERE name = 'super_admin');

-- First add 'super_admin' to the user_type enum if not exists
ALTER TABLE users MODIFY COLUMN user_type 
ENUM('tenant', 'service_provider', 'client_enquiry', 'landlord', 'manager', 'super_admin') NOT NULL;

-- Create the super admin user
INSERT INTO users (
    name,
    email,
    password_hash,
    mobile,
    user_type,
    is_verified,
    is_active,
    created_at,
    updated_at
) VALUES (
    'Samer Khan',
    'samer@keynoverse.tech',
    '$2y$10$3Ur1TeLNPPRWYLWBPXc4b.jqYxVDyqeNB.VH5WwF0K1YvF8pVK0Uy',  -- Hash for 'Mewar@123'
    '9571840084',
    'landlord',  -- Using landlord as it's closest to admin in the current enum
    1,  -- is_verified
    1,  -- is_active
    NOW(),
    NOW()
);

-- Store additional admin privileges in additional_data
UPDATE users 
SET additional_data = JSON_OBJECT(
    'is_super_admin', true,
    'admin_since', NOW(),
    'permissions', JSON_ARRAY('all')
)
WHERE email = 'samer@keynoverse.tech';

-- Insert default permissions for super admin
INSERT INTO module_permissions (module_id, role_id, is_enabled)
SELECT id, @admin_role_id, 1 FROM modules;

INSERT INTO menu_permissions (menu_id, role_id, is_visible)
SELECT id, @admin_role_id, 1 FROM menu_items; 