-- Janani application database schema
-- Run this in phpMyAdmin or mysql CLI (XAMPP MySQL)

CREATE DATABASE IF NOT EXISTS janani_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE janani_db;

-- Users (authentication)
CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(255) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role VARCHAR(50) NOT NULL DEFAULT 'mother', -- mother, asha, doctor, admin
  status TINYINT NOT NULL DEFAULT 1, -- 1=active,0=inactive
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Roles (optional) - keep if you want flexible roles
CREATE TABLE IF NOT EXISTS roles (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80) NOT NULL UNIQUE,
  description VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB;

-- Link user -> role (if using roles table)
CREATE TABLE IF NOT EXISTS user_roles (
  user_id INT UNSIGNED NOT NULL,
  role_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (user_id, role_id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Profiles (detailed profile info)
CREATE TABLE IF NOT EXISTS user_profiles (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL UNIQUE,
  first_name VARCHAR(100) DEFAULT NULL,
  last_name VARCHAR(100) DEFAULT NULL,
  pre_pregnancy_weight VARCHAR(50) DEFAULT NULL,
  phone VARCHAR(30) DEFAULT NULL,
  gender ENUM('female','male','other') DEFAULT NULL,
  dob DATE DEFAULT NULL,
  address TEXT,
  avatar_image_id INT UNSIGNED DEFAULT NULL,
  bio TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Images / Media
CREATE TABLE IF NOT EXISTS images (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  filename VARCHAR(255) NOT NULL,
  path VARCHAR(255) NOT NULL,
  mime_type VARCHAR(100) DEFAULT NULL,
  size INT UNSIGNED DEFAULT NULL,
  uploader_id INT UNSIGNED DEFAULT NULL,
  usage_tag VARCHAR(50) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (uploader_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Pregnancies
CREATE TABLE IF NOT EXISTS pregnancies (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  lmp_date DATE DEFAULT NULL,
  edd DATE DEFAULT NULL,
  trimester TINYINT DEFAULT NULL,
  notes TEXT,
  status ENUM('active','completed','lost','aborted') DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Medical history per pregnancy
CREATE TABLE IF NOT EXISTS medical_history (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  pregnancy_id INT UNSIGNED NOT NULL,
  recorded_by INT UNSIGNED DEFAULT NULL, -- user who recorded, could be ASHA/doctor
  record_date DATETIME DEFAULT CURRENT_TIMESTAMP,
  type VARCHAR(100) DEFAULT NULL, -- 'symptom','vital','note'
  title VARCHAR(255) DEFAULT NULL,
  details TEXT,
  attachment_image_id INT UNSIGNED DEFAULT NULL,
  FOREIGN KEY (pregnancy_id) REFERENCES pregnancies(id) ON DELETE CASCADE,
  FOREIGN KEY (recorded_by) REFERENCES users(id) ON DELETE SET NULL,
  FOREIGN KEY (attachment_image_id) REFERENCES images(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Appointments
CREATE TABLE IF NOT EXISTS appointments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  doctor_id INT UNSIGNED DEFAULT NULL,
  pregnancy_id INT UNSIGNED DEFAULT NULL,
  scheduled_at DATETIME NOT NULL,
  duration_minutes INT DEFAULT 30,
  status ENUM('scheduled','completed','cancelled','missed') DEFAULT 'scheduled',
  notes TEXT,
  reminder_sent TINYINT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (doctor_id) REFERENCES users(id) ON DELETE SET NULL,
  FOREIGN KEY (pregnancy_id) REFERENCES pregnancies(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Babies
CREATE TABLE IF NOT EXISTS babies (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  pregnancy_id INT UNSIGNED DEFAULT NULL,
  user_id INT UNSIGNED NOT NULL,
  name VARCHAR(150) DEFAULT NULL,
  dob DATE DEFAULT NULL,
  time_of_birth TIME DEFAULT NULL,
  sex ENUM('female','male','other') DEFAULT 'female',
  birth_weight_grams INT DEFAULT NULL,
  notes TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (pregnancy_id) REFERENCES pregnancies(id) ON DELETE SET NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Baby growth (weight/height over time)
CREATE TABLE IF NOT EXISTS baby_growth (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  baby_id INT UNSIGNED NOT NULL,
  recorded_at DATE NOT NULL,
  weight_grams INT DEFAULT NULL,
  height_mm INT DEFAULT NULL,
  head_circumference_mm INT DEFAULT NULL,
  notes TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (baby_id) REFERENCES babies(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Vaccinations
CREATE TABLE IF NOT EXISTS vaccinations (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  baby_id INT UNSIGNED NOT NULL,
  vaccine_name VARCHAR(255) NOT NULL,
  scheduled_date DATE DEFAULT NULL,
  given_date DATE DEFAULT NULL,
  status ENUM('pending','given','missed') DEFAULT 'pending',
  notes TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (baby_id) REFERENCES babies(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Password resets
CREATE TABLE IF NOT EXISTS password_resets (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  token VARCHAR(128) NOT NULL UNIQUE,
  expires_at DATETIME NOT NULL,
  used TINYINT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Email verifications
CREATE TABLE IF NOT EXISTS email_verifications (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  token VARCHAR(128) NOT NULL UNIQUE,
  expires_at DATETIME NOT NULL,
  used TINYINT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Auth tokens for remember-me / persistent login
CREATE TABLE IF NOT EXISTS auth_tokens (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  token VARCHAR(128) NOT NULL,
  expires_at DATETIME NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX (user_id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Notifications (for reminders etc.)
CREATE TABLE IF NOT EXISTS notifications (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  type VARCHAR(80) DEFAULT NULL,
  data JSON DEFAULT NULL,
  is_read TINYINT DEFAULT 0,
  send_at DATETIME DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Articles / CMS
CREATE TABLE IF NOT EXISTS articles (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  author_id INT UNSIGNED DEFAULT NULL,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  excerpt VARCHAR(512) DEFAULT NULL,
  content MEDIUMTEXT DEFAULT NULL,
  featured_image_id INT UNSIGNED DEFAULT NULL,
  status ENUM('draft','published','archived') DEFAULT 'draft',
  published_at DATETIME DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL,
  FOREIGN KEY (featured_image_id) REFERENCES images(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Article categories and tags (optional)
CREATE TABLE IF NOT EXISTS article_categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL UNIQUE,
  slug VARCHAR(150) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS article_category_map (
  article_id INT UNSIGNED NOT NULL,
  category_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (article_id, category_id),
  FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
  FOREIGN KEY (category_id) REFERENCES article_categories(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS article_tags (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS article_tag_map (
  article_id INT UNSIGNED NOT NULL,
  tag_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (article_id, tag_id),
  FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
  FOREIGN KEY (tag_id) REFERENCES article_tags(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- FAQs / Support messages
CREATE TABLE IF NOT EXISTS faqs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  question VARCHAR(500) NOT NULL,
  answer TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS contact_messages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  subject VARCHAR(255) DEFAULT NULL,
  message TEXT NOT NULL,
  status ENUM('new','read','replied') DEFAULT 'new',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Contraction timer sessions (tool)
CREATE TABLE IF NOT EXISTS contraction_sessions (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  started_at DATETIME NOT NULL,
  ended_at DATETIME DEFAULT NULL,
  notes TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Settings key/value
CREATE TABLE IF NOT EXISTS settings (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `key` VARCHAR(150) NOT NULL,
  `value` TEXT DEFAULT NULL,
  user_id INT UNSIGNED DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY unique_key_user (`key`, user_id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Activity logs
CREATE TABLE IF NOT EXISTS activity_logs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED DEFAULT NULL,
  action VARCHAR(255) NOT NULL,
  ip_address VARCHAR(45) DEFAULT NULL,
  meta JSON DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Final indexes for performance (examples)
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_pregnancies_user ON pregnancies(user_id);
CREATE INDEX idx_babies_user ON babies(user_id);
CREATE INDEX idx_articles_status ON articles(status);

-- =========================
-- Emergency Alerts Table
-- =========================

CREATE TABLE IF NOT EXISTS emergency_alerts (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED DEFAULT NULL,
  latitude DECIMAL(10,6) DEFAULT NULL,
  longitude DECIMAL(10,6) DEFAULT NULL,
  location_link VARCHAR(255) DEFAULT NULL,
  alert_time DATETIME DEFAULT CURRENT_TIMESTAMP,
  status ENUM('sent','acknowledged','resolved') DEFAULT 'sent',
  message TEXT DEFAULT 'Emergency alert triggered (possible labor pain)',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- =========================
-- 🚨 Checklist Items Table
-- =========================

SQL query: Copy


CREATE TABLE IF NOT EXISTS checklist_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  item_name VARCHAR(255) NOT NULL,
  item_description TEXT DEFAULT NULL,
  category VARCHAR(100) DEFAULT NULL,
  is_completed TINYINT DEFAULT 0,
  due_date DATE DEFAULT NULL,
  completed_at DATETIME DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_user_checklist (user_id),
  INDEX idx_category (category)
) ENGINE=InnoDB;
MySQL said: Documentation

#1046 - No database selected


-- End of schema

