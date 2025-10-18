-- VetCare Database Schema for MySQL
-- Run this in phpMyAdmin or MySQL command line

-- Create database (if not exists)
CREATE DATABASE IF NOT EXISTS vetcare_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE vetcare_db;

-- Users table for authentication
CREATE TABLE users (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    reset_token VARCHAR(255),
    reset_expires DATETIME,
    email_verified BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_users_email (email),
    INDEX idx_users_reset_token (reset_token)
);

-- Veterinarians table
CREATE TABLE veterinarians (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    name VARCHAR(255) NOT NULL,
    specialty VARCHAR(255) NOT NULL,
    experience TEXT,
    rating DECIMAL(2,1) DEFAULT 0,
    reviews_count INT DEFAULT 0,
    image_url VARCHAR(500),
    availability_status ENUM('online', 'offline', 'busy') DEFAULT 'offline',
    bio TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_veterinarians_availability (availability_status)
);

-- Veterinarian badges/services
CREATE TABLE veterinarian_badges (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    veterinarian_id VARCHAR(36) NOT NULL,
    badge_name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (veterinarian_id) REFERENCES veterinarians(id) ON DELETE CASCADE
);

-- Clinics table
CREATE TABLE clinics (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    name VARCHAR(255) NOT NULL,
    address TEXT NOT NULL,
    latitude DECIMAL(10,8),
    longitude DECIMAL(11,8),
    rating DECIMAL(2,1) DEFAULT 0,
    open_hours VARCHAR(255),
    phone VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_clinics_location (latitude, longitude)
);

-- Services table
CREATE TABLE services (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    title VARCHAR(255) NOT NULL,
    description TEXT,
    price_start INT, -- in rupiah
    category ENUM('general', 'vaccination', 'surgery', 'emergency', 'nutrition') DEFAULT 'general',
    is_home_visit BOOLEAN DEFAULT FALSE,
    is_24h BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Articles table
CREATE TABLE articles (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    author_name VARCHAR(255) NOT NULL,
    author_id VARCHAR(36),
    published_date DATE DEFAULT (CURRENT_DATE),
    rating DECIMAL(2,1) DEFAULT 0,
    views_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES veterinarians(id) ON DELETE SET NULL,
    INDEX idx_articles_published (published_date DESC)
);

-- Consultations table
CREATE TABLE consultations (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    user_id VARCHAR(36) NOT NULL,
    veterinarian_id VARCHAR(36) NOT NULL,
    consultation_type ENUM('chat', 'video', 'phone') DEFAULT 'chat',
    status ENUM('pending', 'active', 'completed', 'cancelled') DEFAULT 'pending',
    scheduled_at DATETIME,
    started_at DATETIME,
    ended_at DATETIME,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (veterinarian_id) REFERENCES veterinarians(id) ON DELETE CASCADE,
    INDEX idx_consultations_user (user_id),
    INDEX idx_consultations_vet (veterinarian_id)
);

-- Consultation messages
CREATE TABLE consultation_messages (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    consultation_id VARCHAR(36) NOT NULL,
    sender_id VARCHAR(36) NOT NULL, -- can be user or vet
    sender_type ENUM('user', 'veterinarian') NOT NULL,
    message TEXT NOT NULL,
    message_type ENUM('text', 'image', 'file') DEFAULT 'text',
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (consultation_id) REFERENCES consultations(id) ON DELETE CASCADE,
    INDEX idx_messages_consultation (consultation_id)
);

-- Insert sample data (optional)
-- Sample user
INSERT INTO users (email, password_hash, full_name) VALUES
('admin@vetcare.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin VetCare');

-- Sample veterinarian
INSERT INTO veterinarians (name, specialty, experience, rating, reviews_count, availability_status, bio) VALUES
('Dr. Ahmad Santoso', 'Dokter Hewan Umum', '5 tahun pengalaman', 4.8, 25, 'online', 'Spesialis dalam kesehatan hewan peliharaan dan ternak.');

-- Sample clinic
INSERT INTO clinics (name, address, latitude, longitude, rating, open_hours, phone) VALUES
('Klinik Hewan Sejahtera', 'Jl. Raya Bogor KM 30, Jakarta', -6.2088, 106.8456, 4.5, '08:00-20:00', '+62211234567');

-- Sample service
INSERT INTO services (title, description, price_start, category, is_home_visit, is_24h) VALUES
('Konsultasi Online', 'Konsultasi kesehatan hewan via chat/video call', 50000, 'general', FALSE, TRUE),
('Kunjungan Rumah', 'Dokter datang ke rumah Anda', 100000, 'general', TRUE, FALSE),
('Vaksinasi', 'Vaksinasi lengkap untuk hewan peliharaan', 150000, 'vaccination', TRUE, FALSE);

-- Sample article
INSERT INTO articles (title, content, author_name, rating, views_count) VALUES
('Pentingnya Vaksinasi untuk Kucing', 'Vaksinasi merupakan salah satu cara pencegahan penyakit yang paling efektif...', 'Dr. Ahmad Santoso', 4.7, 150);
