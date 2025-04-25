-- Create the users table if it doesn't exist (simplified for example)
CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_id INT NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    profile_img VARCHAR(255) DEFAULT 'default-user.jpg',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    status ENUM('active', 'inactive', 'banned') DEFAULT 'active',
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

-- Insert role values if they don't exist
INSERT IGNORE INTO roles (name) VALUES 
('Admin'),
('Tour Operator'),
('Traveler');

-- Delete existing users with the same username to avoid duplicates
DELETE FROM users WHERE username = 'admin';
DELETE FROM users WHERE username = 'operator';

-- Insert admin user (password: admin1234)
INSERT INTO users (role_id, full_name, username, email, password, phone) VALUES 
(1, 'Admin User', 'admin', 'admin@wanderlust.com', '$2y$10$Jrjwyrxcn621teAORnZdCeAa/oXK/ez70XSeoA7ngFy5IxAGKFwKG', '+639123456789');

-- Insert operator (password: ope1234)
INSERT INTO users (role_id, full_name, username, email, password, phone) VALUES 
(2, 'Tour Operator', 'operator', 'operator@wanderlust.com', '$2y$10$UxIMrOjCLaBc1UJ6/5uRCuIfnNtuEkD4prnBKHkKKYMZlW5YumPmC', '+639123456790');