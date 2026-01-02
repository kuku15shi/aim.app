-- Database Setup for InstaClone
-- WARNING: This will DELETE existing data!

-- Disable foreign key checks to allow dropping tables
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS likes;
DROP TABLE IF EXISTS posts;
DROP TABLE IF EXISTS users;

SET FOREIGN_KEY_CHECKS = 1;

-- Create Users Table with OTP and Role support
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    otp VARCHAR(6),
    is_verified TINYINT(1) DEFAULT 0,
    role ENUM('user', 'admin') DEFAULT 'user',
    status ENUM('pending', 'active', 'suspended') DEFAULT 'active',
    profile_pic VARCHAR(255) DEFAULT 'uploads/profiles/default.png',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create Posts Table
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    caption TEXT,
    approval_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Likes Table
CREATE TABLE likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    post_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    UNIQUE KEY unique_like (user_id, post_id)
);

-- Comments Table
CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    post_id INT NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
);

-- Default Admin User (Password: admin123)
-- You can log in with: admin / admin123
INSERT INTO users (username, email, password_hash, is_verified, role, status) 
VALUES ('admin', 'admin@instaclone.com', '$2y$10$jOVGxYUd5yla373QHiu0De7F4g5L9c9Ejb6m/tzMA3CvPkdnNUooS', 1, 'admin', 'active');
