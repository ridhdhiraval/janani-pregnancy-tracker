-- Checklist Items Table for JANANI Application
-- Run this SQL in phpMyAdmin to create the missing checklist_items table

USE janani_db;

-- Drop table if it exists (optional, for clean install)
-- DROP TABLE IF EXISTS checklist_items;

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

-- Add some sample checklist items for testing
INSERT INTO checklist_items (user_id, item_name, item_description, category, is_completed, due_date) VALUES
(1, 'First Prenatal Visit', 'Schedule your first prenatal appointment with your doctor', 'appointments', 0, DATE_ADD(CURDATE(), INTERVAL 7 DAY)),
(1, 'Start Prenatal Vitamins', 'Begin taking prenatal vitamins with folic acid', 'nutrition', 0, DATE_ADD(CURDATE(), INTERVAL 1 DAY)),
(1, 'Create Birth Plan', 'Write down your preferences for labor and delivery', 'planning', 0, DATE_ADD(CURDATE(), INTERVAL 30 DAY));

-- Verify table was created
SELECT 'checklist_items table created successfully!' AS message;
SELECT COUNT(*) as total_items FROM checklist_items;