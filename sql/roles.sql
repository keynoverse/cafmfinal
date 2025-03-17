-- Roles and permissions tables

-- User roles table
CREATE TABLE IF NOT EXISTS roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    is_admin BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default roles
INSERT INTO roles (name, description, is_admin) VALUES
('super_admin', 'Super Administrator with full system access', TRUE),
('admin', 'Administrator with management privileges', TRUE),
('facility_manager', 'Facility Manager with operational control', FALSE),
('technician', 'Technician with maintenance responsibilities', FALSE),
('client', 'Client with limited access to relevant data', FALSE),
('vendor', 'External vendor with restricted access', FALSE),
('user', 'Basic user with minimal access', FALSE);

-- Update users table to include role_id (to be executed separately)
-- ALTER TABLE users ADD COLUMN role_id INT DEFAULT NULL;
-- ALTER TABLE users ADD CONSTRAINT fk_user_role FOREIGN KEY (role_id) REFERENCES roles(id); 