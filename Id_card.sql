-- Create database
CREATE DATABASE id_card_db;

-- Use the created database
USE id_card_db;

-- Create users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    photo VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    community VARCHAR(100) NOT NULL,
    domain VARCHAR(100) NOT NULL,
    area VARCHAR(100) NOT NULL,
    pin_code VARCHAR(10) NOT NULL
);

-- Insert sample data
INSERT INTO users (photo, name, community, domain, area, pin_code) VALUES
('uploads\passport size img.jpeg', 'Pratik Walunj', 'Engineering', 'Software', 'Pune', '10001'),
('uploads\php.avif', 'Jane Smith', 'Medical', 'Doctor', 'Los Angeles', '90001'),
('uploads\react native.jpg', 'Robert Brown', 'Education', 'Professor', 'Chicago', '60601'),
('uploads\mcs.jpg', 'Emily Davis', 'Business', 'Finance', 'Houston', '77001'),
('uploads\java_community.png', 'Michael Wilson', 'Arts', 'Graphic Design', 'San Francisco', '94101');
