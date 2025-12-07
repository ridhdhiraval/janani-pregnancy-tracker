<?php
require_once __DIR__ . '/config/db.php';

$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 2;

try {
    $stmt = $pdo->prepare('SELECT COUNT(*) AS c FROM patient_details WHERE user_id = ?');
    $stmt->execute([$user_id]);
    $exists = (int)$stmt->fetch()['c'] > 0;

    if (!$exists) {
        $stmt = $pdo->prepare('INSERT INTO patient_details (user_id, age, gender, phone_number, address, emergency_contact) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$user_id, 30, 'Female', '+91-9123000000', 'Coimbatore, Tamil Nadu', '+91-9000000002']);
    }

    $stmt = $pdo->prepare('SELECT COUNT(*) AS c FROM appointments WHERE patient_id = ?');
    $stmt->execute([$user_id]);
    if ((int)$stmt->fetch()['c'] === 0) {
        $ins = $pdo->prepare('INSERT INTO appointments (patient_id, appt_date, appt_time, appt_type, status) VALUES (?, ?, ?, ?, ?)');
        $ins->execute([$user_id, '2025-12-02', '11:00:00', 'Dermatology Review', 'Upcoming']);
        $ins->execute([$user_id, '2025-11-18', '15:30:00', 'Skin Allergy Check', 'Completed']);
    }

    $stmt = $pdo->prepare('SELECT COUNT(*) AS c FROM clinical_notes WHERE patient_id = ?');
    $stmt->execute([$user_id]);
    if ((int)$stmt->fetch()['c'] === 0) {
        $ins = $pdo->prepare('INSERT INTO clinical_notes (patient_id, note_date, content) VALUES (?, ?, ?)');
        $ins->execute([$user_id, '2025-11-18', 'Rash observed on forearms, advised topical steroid for 5 days.']);
        $ins->execute([$user_id, '2025-11-25', 'Rash reduced, continue moisturizer, avoid irritants.']);
    }

    $stmt = $pdo->prepare('SELECT COUNT(*) AS c FROM prescriptions WHERE patient_id = ?');
    $stmt->execute([$user_id]);
    if ((int)$stmt->fetch()['c'] === 0) {
        $ins = $pdo->prepare('INSERT INTO prescriptions (patient_id, prescribed_date, medication_name, dosage, instructions, status) VALUES (?, ?, ?, ?, ?, ?)');
        $ins->execute([$user_id, '2025-11-18', 'Hydrocortisone Cream', 'Apply thin layer', 'Apply twice daily for 5 days', 'Completed']);
        $ins->execute([$user_id, '2025-12-02', 'Cetirizine', '10mg', 'Take once daily at night', 'Active']);
    }

    $stmt = $pdo->prepare('SELECT COUNT(*) AS c FROM lab_reports WHERE patient_id = ?');
    $stmt->execute([$user_id]);
    if ((int)$stmt->fetch()['c'] === 0) {
        $ins = $pdo->prepare('INSERT INTO lab_reports (patient_id, report_date, report_type, summary) VALUES (?, ?, ?, ?)');
        $ins->execute([$user_id, '2025-11-19', 'Allergy Panel', 'Elevated response to dust mites; recommend avoidance and antihistamines.']);
        $ins->execute([$user_id, '2025-11-19', 'Vitamin D', '25(OH)D level: 22 ng/mL; supplementation advised.']);
    }

    header('Content-Type: text/plain');
    echo "OK\n";
    echo "Seeded user_id={$user_id} where needed\n";
} catch (PDOException $e) {
    header('Content-Type: text/plain');
    echo "ERROR\n";
    echo $e->getMessage();
}

