INSERT INTO doctors 
(user_id, specialization, qualification, experience_years, phone, gender, dob, address, status, created_at, updated_at) 
VALUES
(1, 'Cardiologist', 'MBBS, MD Cardiology', 10, '9876543210', 'Male', '1985-05-12', 'Chennai, Tamil Nadu', 'active', NOW(), NOW()),
(2, 'Dermatologist', 'MBBS, DDVL', 7, '9123456780', 'Female', '1990-08-22', 'Coimbatore, Tamil Nadu', 'active', NOW(), NOW()),
(3, 'Pediatrician', 'MBBS, MD Pediatrics', 12, '9001234567', 'Female', '1982-02-18', 'Madurai, Tamil Nadu', 'active', NOW(), NOW());


-- 1. Appointments Table
CREATE TABLE appointments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    patient_id INT UNSIGNED NOT NULL,
    appt_date DATE NOT NULL,
    appt_time TIME,
    appt_type VARCHAR(100),
    status ENUM('Upcoming','Completed','Critical') DEFAULT 'Upcoming',
    FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 2. Clinical Notes Table
CREATE TABLE clinical_notes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    patient_id INT UNSIGNED NOT NULL,
    note_date DATE NOT NULL,
    content TEXT NOT NULL,
    FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 3. Prescriptions Table
CREATE TABLE prescriptions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    patient_id INT UNSIGNED NOT NULL,
    prescribed_date DATE NOT NULL,
    medication_name VARCHAR(100) NOT NULL,
    dosage VARCHAR(100),
    instructions TEXT,
    status ENUM('Active','Completed') DEFAULT 'Active',
    FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 4. Lab Reports Table
CREATE TABLE lab_reports (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    patient_id INT UNSIGNED NOT NULL,
    report_date DATE NOT NULL,
    report_type VARCHAR(100),
    summary TEXT,
    FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;


-- 1. Patient Details
CREATE TABLE IF NOT EXISTS patient_details (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    age INT,
    gender ENUM('Male','Female','Other') DEFAULT 'Other',
    phone_number VARCHAR(20),
    address VARCHAR(255),
    emergency_contact VARCHAR(50),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Insert sample patient details
INSERT INTO patient_details (user_id, age, gender, phone_number, address, emergency_contact)
VALUES
(1, 28, 'Female', '+91-9876543210', '123, MG Road, Mumbai', '+91-9123456789');

-------------------------------------------------
-- 2. Appointments
INSERT INTO appointments (patient_id, appt_date, appt_time, appt_type, status)
VALUES
(1, '2025-12-01', '10:30:00', 'General Checkup', 'Upcoming'),
(1, '2025-11-15', '14:00:00', 'Vaccination', 'Completed'),
(1, '2025-11-20', '09:00:00', 'Follow-up', 'Critical');

-------------------------------------------------
-- 3. Clinical Notes
INSERT INTO clinical_notes (patient_id, note_date, content)
VALUES
(1, '2025-11-15', 'Patient reported mild fever and headache. Prescribed paracetamol 500mg twice daily.'),
(1, '2025-11-20', 'Follow-up visit: fever resolved, headache subsided.');

-------------------------------------------------
-- 4. Prescriptions
INSERT INTO prescriptions (patient_id, prescribed_date, medication_name, dosage, instructions, status)
VALUES
(1, '2025-11-15', 'Paracetamol', '500mg', 'Take twice daily after meals', 'Completed'),
(1, '2025-12-01', 'Vitamin D', '1000 IU', 'Take once daily in morning', 'Active');

-------------------------------------------------
-- 5. Lab Reports
INSERT INTO lab_reports (patient_id, report_date, report_type, summary)
VALUES
(1, '2025-11-16', 'CBC', 'All parameters are within normal range.'),
(1, '2025-11-16', 'Blood Sugar', 'Fasting glucose: 95 mg/dL, Normal');
