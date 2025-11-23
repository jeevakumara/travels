-- =====================================================
-- NMD Travels - Complete Database Schema
-- MySQL 5.7+ / MariaDB 10.2+
-- =====================================================

-- Create database (if not exists)
CREATE DATABASE IF NOT EXISTS travels CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE travels;

-- =====================================================
-- Table: bookings
-- Stores customer booking requests
-- =====================================================

CREATE TABLE IF NOT EXISTS bookings (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(120) NOT NULL,
  email VARCHAR(160) NOT NULL,
  phone VARCHAR(40) NOT NULL,
  package_name VARCHAR(160) NOT NULL,
  travel_date DATE NOT NULL,
  adults INT UNSIGNED NOT NULL DEFAULT 1,
  notes TEXT,
  status ENUM('new', 'confirmed', 'cancelled', 'completed') NOT NULL DEFAULT 'new',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_status (status),
  INDEX idx_created_at (created_at),
  INDEX idx_travel_date (travel_date),
  INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table: messages
-- Stores contact form submissions
-- =====================================================

CREATE TABLE IF NOT EXISTS messages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(160) NOT NULL,
  phone VARCHAR(40),
  subject VARCHAR(160),
  message TEXT NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_created_at (created_at),
  INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table: services
-- Stores travel packages and services
-- =====================================================

CREATE TABLE IF NOT EXISTS services (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(160) NOT NULL,
  summary TEXT,
  description TEXT,
  price DECIMAL(10,2),
  currency VARCHAR(10) DEFAULT 'INR',
  duration_label VARCHAR(80),
  image VARCHAR(255),
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_is_active (is_active),
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Sample Data for Services
-- =====================================================

INSERT INTO services (title, summary, description, price, currency, duration_label, is_active) VALUES
('Chennai Airport Transfer', 'Reliable pickup and drop services to Chennai Airport', 'Professional chauffeur-driven sedans and SUVs for comfortable airport transfers. Available 24/7 with flight tracking.', 800.00, 'INR', 'One-way', 1),
('Pondicherry Day Trip', 'Explore the French Riviera of the East', 'Full-day tour covering Rock Beach, Auroville, French Quarter, and local cuisine. Includes AC vehicle and driver.', 3500.00, 'INR', 'Full Day', 1),
('Mahabalipuram Heritage Tour', 'UNESCO World Heritage temples and beaches', 'Visit Shore Temple, Five Rathas, Krishna\'s Butterball, and enjoy fresh seafood by the beach.', 2800.00, 'INR', 'Full Day', 1),
('Tirupati Darshan Package', 'Spiritual journey to Lord Venkateswara Temple', 'Comfortable overnight trip with AC vehicle, experienced driver, and darshan arrangements.', 6500.00, 'INR', '2 Days', 1),
('Corporate Employee Transport', 'Daily office commute solutions', 'Reliable pickup and drop services for corporate employees. Monthly packages available.', 12000.00, 'INR', 'Per Month', 1),
('Wedding Transportation', 'Luxury vehicles for your special day', 'Decorated cars, buses, and tempo travellers for wedding events. Professional service guaranteed.', 15000.00, 'INR', 'Per Event', 1);

-- =====================================================
-- Admin User Table (Optional - for future use)
-- =====================================================

CREATE TABLE IF NOT EXISTS admin_users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(80) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  email VARCHAR(160) NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  last_login DATETIME NULL,
  INDEX idx_username (username),
  INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Performance Optimization
-- =====================================================

-- Analyze tables for query optimization
ANALYZE TABLE bookings, messages, services;

-- =====================================================
-- Backup Reminder
-- =====================================================

-- Set up automated backups using cron:
-- 0 2 * * * mysqldump -u username -p'password' travels > /backups/travels_$(date +\%Y\%m\%d).sql

-- =====================================================
-- Security Notes
-- =====================================================

-- 1. Change default MySQL root password
-- 2. Create dedicated database user with limited privileges:
--    CREATE USER 'travels_user'@'localhost' IDENTIFIED BY 'strong_password';
--    GRANT SELECT, INSERT, UPDATE, DELETE ON travels.* TO 'travels_user'@'localhost';
--    FLUSH PRIVILEGES;
-- 3. Disable remote root access
-- 4. Enable MySQL slow query log for optimization
