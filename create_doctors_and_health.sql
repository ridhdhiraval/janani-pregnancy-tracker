USE janani_db;

CREATE TABLE IF NOT EXISTS doctors (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  specialization VARCHAR(100) NOT NULL,
  qualification VARCHAR(150),
  experience_years INT UNSIGNED DEFAULT 0,
  phone VARCHAR(20),
  gender ENUM('Male','Female','Other') DEFAULT 'Other',
  dob DATE,
  address VARCHAR(255),
  status ENUM('active','inactive') DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_user (user_id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS appointments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  patient_id INT UNSIGNED NOT NULL,
  appt_date DATE NOT NULL,
  appt_time TIME,
  appt_type VARCHAR(100),
  status ENUM('Upcoming','Completed','Critical') DEFAULT 'Upcoming',
  INDEX idx_patient_date (patient_id, appt_date),
  FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS clinical_notes (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  patient_id INT UNSIGNED NOT NULL,
  note_date DATE NOT NULL,
  content TEXT NOT NULL,
  INDEX idx_patient_note (patient_id, note_date),
  FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS prescriptions (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  patient_id INT UNSIGNED NOT NULL,
  prescribed_date DATE NOT NULL,
  medication_name VARCHAR(100) NOT NULL,
  dosage VARCHAR(100),
  instructions TEXT,
  status ENUM('Active','Completed') DEFAULT 'Active',
  INDEX idx_patient_prescribed (patient_id, prescribed_date),
  FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS lab_reports (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  patient_id INT UNSIGNED NOT NULL,
  report_date DATE NOT NULL,
  report_type VARCHAR(100),
  summary TEXT,
  INDEX idx_patient_report (patient_id, report_date),
  FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS patient_details (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  age INT,
  gender ENUM('Male','Female','Other') DEFAULT 'Other',
  phone_number VARCHAR(20),
  address VARCHAR(255),
  emergency_contact VARCHAR(50),
  INDEX idx_user_details (user_id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO doctors (user_id, specialization, qualification, experience_years, phone, gender, dob, address, status, created_at, updated_at) VALUES
 (1, 'Cardiologist', 'MBBS, MD Cardiology', 10, '9876543210', 'Male', '1985-05-12', 'Chennai, Tamil Nadu', 'active', NOW(), NOW()),
 (2, 'Dermatologist', 'MBBS, DDVL', 7, '9123456780', 'Female', '1990-08-22', 'Coimbatore, Tamil Nadu', 'active', NOW(), NOW()),
 (3, 'Pediatrician', 'MBBS, MD Pediatrics', 12, '9001234567', 'Female', '1982-02-18', 'Madurai, Tamil Nadu', 'active', NOW(), NOW());

INSERT INTO patient_details (user_id, age, gender, phone_number, address, emergency_contact) VALUES
 (1, 28, 'Female', '+91-9876543210', '123, MG Road, Mumbai', '+91-9123456789'),
 (2, 30, 'Female', '+91-9123000000', 'Coimbatore, Tamil Nadu', '+91-9000000002'),
 (3, 32, 'Female', '+91-9001112223', 'Madurai, Tamil Nadu', '+91-9000000003');

INSERT INTO appointments (patient_id, appt_date, appt_time, appt_type, status) VALUES
 (1, '2025-12-01', '10:30:00', 'General Checkup', 'Upcoming'),
 (1, '2025-11-15', '14:00:00', 'Vaccination', 'Completed'),
 (1, '2025-11-20', '09:00:00', 'Follow-up', 'Critical'),
 (2, '2025-12-02', '11:00:00', 'Dermatology Review', 'Upcoming'),
 (2, '2025-11-18', '15:30:00', 'Skin Allergy Check', 'Completed');

INSERT INTO clinical_notes (patient_id, note_date, content) VALUES
 (1, '2025-11-15', 'Patient reported mild fever and headache. Prescribed paracetamol 500mg twice daily.'),
 (1, '2025-11-20', 'Follow-up visit: fever resolved, headache subsided.'),
 (2, '2025-11-18', 'Rash observed on forearms, advised topical steroid for 5 days.'),
 (2, '2025-11-25', 'Rash reduced, continue moisturizer, avoid irritants.');

INSERT INTO prescriptions (patient_id, prescribed_date, medication_name, dosage, instructions, status) VALUES
 (1, '2025-11-15', 'Paracetamol', '500mg', 'Take twice daily after meals', 'Completed'),
 (1, '2025-12-01', 'Vitamin D', '1000 IU', 'Take once daily in morning', 'Active'),
 (2, '2025-11-18', 'Hydrocortisone Cream', 'Apply thin layer', 'Apply twice daily for 5 days', 'Completed'),
 (2, '2025-12-02', 'Cetirizine', '10mg', 'Take once daily at night', 'Active');

INSERT INTO lab_reports (patient_id, report_date, report_type, summary) VALUES
 (1, '2025-11-16', 'CBC', 'All parameters are within normal range.'),
 (1, '2025-11-16', 'Blood Sugar', 'Fasting glucose: 95 mg/dL, Normal'),
 (2, '2025-11-19', 'Allergy Panel', 'Elevated response to dust mites; recommend avoidance and antihistamines.'),
 (2, '2025-11-19', 'Vitamin D', '25(OH)D level: 22 ng/mL; supplementation advised.');
