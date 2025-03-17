CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    mobile VARCHAR(20),
    user_type ENUM('tenant', 'service_provider', 'client_enquiry', 'landlord', 'manager') NOT NULL,
    is_verified BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    verification_token VARCHAR(64),
    reset_token VARCHAR(64),
    reset_token_expires DATETIME,
    last_login DATETIME,
    additional_data JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    permissions JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS documents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    type VARCHAR(50) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS company_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS service_provider_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    provider_id INT,
    category_id INT,
    FOREIGN KEY (provider_id) REFERENCES users(id),
    FOREIGN KEY (category_id) REFERENCES company_categories(id)
);

CREATE TABLE IF NOT EXISTS company_details (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    company_name VARCHAR(100),
    company_type ENUM('expo', 'non_expo'),
    department_name VARCHAR(100),
    trade_license_path VARCHAR(255),
    lease_contract_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS registration_requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    reviewed_by INT,
    review_date TIMESTAMP,
    comments TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (reviewed_by) REFERENCES users(id)
);

-- Insert default roles
INSERT INTO roles (name, description) VALUES
('admin', 'System Administrator'),
('manager', 'Department Manager'),
('tenant', 'Building Tenant'),
('service_provider', 'Service Provider'),
('client_care', 'Client Care Representative'),
('vendor', 'Vendor/Contractor'),
('work_order_creator', 'Work Order Creator'),
('approver', 'Request Approver'),
('solver', 'Problem Solver');

-- Insert default company categories
INSERT INTO company_categories (name, description) VALUES
('electrical', 'Electrical services and maintenance'),
('plumbing', 'Plumbing services and repairs'),
('hvac', 'Heating, Ventilation, and Air Conditioning'),
('cleaning', 'Cleaning and janitorial services'),
('security', 'Security services and systems'); 