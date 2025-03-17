-- Create database
CREATE DATABASE IF NOT EXISTS cafm_db;
USE cafm_db;

-- Users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    mobile VARCHAR(20),
    company VARCHAR(100),
    department VARCHAR(100),
    role_id INT,
    is_expo_city BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Roles table
CREATE TABLE roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    permissions JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Documents table (for trade licenses, lease contracts, etc.)
CREATE TABLE documents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    type VARCHAR(50) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Company Categories table
CREATE TABLE company_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Service Provider Categories table (for multiple categories per provider)
CREATE TABLE service_provider_categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    provider_id INT,
    category_id INT,
    FOREIGN KEY (provider_id) REFERENCES users(id),
    FOREIGN KEY (category_id) REFERENCES company_categories(id)
);

-- Company Details table
CREATE TABLE company_details (
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

-- Registration Requests table (for approval workflow)
CREATE TABLE registration_requests (
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