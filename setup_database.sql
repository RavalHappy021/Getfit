-- SQL Setup Script for GetFit Project (Troubleshooting Version)
-- This version uses DROP TABLE to clear any corrupted table metadata.

CREATE DATABASE IF NOT EXISTS getfit_db;
USE getfit_db;

-- Drop existing tables to clear corrupted metadata
DROP TABLE IF EXISTS diet_plans;
DROP TABLE IF EXISTS workout_plans;
DROP TABLE IF EXISTS progress;
DROP TABLE IF EXISTS goals;
DROP TABLE IF EXISTS contact_messages;
DROP TABLE IF EXISTS admins;
DROP TABLE IF EXISTS users;

-- 1. Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    age INT,
    city VARCHAR(50),
    weight DECIMAL(5,2),
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. Admins Table
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

-- 3. Goals Table
CREATE TABLE goals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    goal_description TEXT,
    target_weight DECIMAL(5,2),
    target_date DATE,
    status VARCHAR(20) DEFAULT 'In Progress',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 4. Progress Table
CREATE TABLE progress (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    date DATE NOT NULL,
    weight DECIMAL(5,2),
    waist DECIMAL(4,2),
    chest DECIMAL(4,2),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 5. Workout Plans Table
CREATE TABLE workout_plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    workout VARCHAR(255),
    day VARCHAR(20),
    completed TINYINT(1) DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 6. Diet Plans Table
CREATE TABLE diet_plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    meal_time VARCHAR(20),
    food_items TEXT,
    calories INT,
    day VARCHAR(20),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 7. Contact Messages Table
CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    subject VARCHAR(255),
    message TEXT,
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Insert Default Admin
INSERT IGNORE INTO admins (username, password) VALUES ('admin', 'admin123');
