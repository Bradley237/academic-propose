-- E-Commerce Database Setup
-- Run this file to create all required tables

CREATE DATABASE IF NOT EXISTS ecommerce_db;
USE ecommerce_db;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Category Table
CREATE TABLE IF NOT EXISTS category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Product Table
CREATE TABLE IF NOT EXISTS product (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    stock INT DEFAULT 0,
    category_id INT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES category(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Cart Table
CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE
);

-- Payment Table
CREATE TABLE IF NOT EXISTS payment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50) DEFAULT 'cash',
    status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    paid_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert default admin user (password: admin123)
INSERT INTO users (username, email, password, role) VALUES 
('admin', 'admin@shop.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin')
ON DUPLICATE KEY UPDATE id=id;

-- Insert sample categories
INSERT INTO category (name, description) VALUES 
('Electronics', 'Phones, laptops and gadgets'),
('Clothing', 'Shirts, shoes and fashion'),
('Food & Drinks', 'Snacks, beverages and groceries')
ON DUPLICATE KEY UPDATE id=id;
