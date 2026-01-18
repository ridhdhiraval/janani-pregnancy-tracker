<?php
date_default_timezone_set('Asia/Kolkata');
require_once '../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['action'], $data['type'], $data['patient_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required parameters']);
    exit;
}

$action = $data['action']; // add, update, delete
$type = $data['type'];     // appointments, notes, prescriptions, reports
$patient_id = intval($data['patient_id']);
$id = isset($data['id']) ? intval($data['id']) : null;

try {
    switch ($type) {

        /* -----------------------------
                APPOINTMENTS
        ------------------------------*/
        case 'appointments':
            if ($action === 'add') {
                $stmt = $pdo->prepare("
                    INSERT INTO appointments 
                    (patient_id, appt_date, appt_time, appt_type, status)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $patient_id,
                    $data['date'],
                    $data['time'],
                    $data['type_name'],
                    $data['status']
                ]);
                $id = $pdo->lastInsertId();

            } elseif ($action === 'update') {
                $stmt = $pdo->prepare("
                    UPDATE appointments 
                    SET appt_date=?, appt_time=?, appt_type=?, status=? 
                    WHERE id=? AND patient_id=?
                ");
                $stmt->execute([
                    $data['date'],
                    $data['time'],
                    $data['type_name'],
                    $data['status'],
                    $id,
                    $patient_id
                ]);

            } elseif ($action === 'delete') {
                $stmt = $pdo->prepare("DELETE FROM appointments WHERE id=? AND patient_id=?");
                $stmt->execute([$id, $patient_id]);
            }
        break;


        /* -----------------------------
                CLINICAL NOTES
        ------------------------------*/
        case 'notes':
            if ($action === 'add') {
                $stmt = $pdo->prepare("
                    INSERT INTO clinical_notes (patient_id, note_date, content) 
                    VALUES (?, ?, ?)
                ");
                $stmt->execute([
                    $patient_id,
                    $data['date'],
                    $data['content']
                ]);
                $id = $pdo->lastInsertId();

            } elseif ($action === 'update') {
                $stmt = $pdo->prepare("
                    UPDATE clinical_notes 
                    SET note_date=?, content=? 
                    WHERE id=? AND patient_id=?
                ");
                $stmt->execute([
                    $data['date'],
                    $data['content'],
                    $id,
                    $patient_id
                ]);

            } elseif ($action === 'delete') {
                $stmt = $pdo->prepare("DELETE FROM clinical_notes WHERE id=? AND patient_id=?");
                $stmt->execute([$id, $patient_id]);
            }
        break;


        /* -----------------------------
                PRESCRIPTIONS
        ------------------------------*/
        case 'prescriptions':
            if ($action === 'add') {
                $stmt = $pdo->prepare("
                    INSERT INTO prescriptions 
                    (patient_id, prescribed_date, medication_name, dosage, instructions, status)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $patient_id,
                    $data['date'],
                    $data['medication'],
                    $data['dosage'],
                    $data['instructions'],
                    $data['status']
                ]);
                $id = $pdo->lastInsertId();

            } elseif ($action === 'update') {
                $stmt = $pdo->prepare("
                    UPDATE prescriptions 
                    SET prescribed_date=?, medication_name=?, dosage=?, instructions=?, status=?
                    WHERE id=? AND patient_id=?
                ");
                $stmt->execute([
                    $data['date'],
                    $data['medication'],
                    $data['dosage'],
                    $data['instructions'],
                    $data['status'],
                    $id,
                    $patient_id
                ]);

            } elseif ($action === 'delete') {
                $stmt = $pdo->prepare("DELETE FROM prescriptions WHERE id=? AND patient_id=?");
                $stmt->execute([$id, $patient_id]);
            }
        break;


        /* -----------------------------
                LAB REPORTS
        ------------------------------*/
        case 'reports':
            if ($action === 'add') {
                $stmt = $pdo->prepare("
                    INSERT INTO lab_reports 
                    (patient_id, report_date, report_type, summary)
                    VALUES (?, ?, ?, ?)
                ");
                $stmt->execute([
                    $patient_id,
                    $data['date'],
                    $data['type_name'],
                    $data['content']
                ]);
                $id = $pdo->lastInsertId();

            } elseif ($action === 'update') {
                $stmt = $pdo->prepare("
                    UPDATE lab_reports 
                    SET report_date=?, report_type=?, summary=?
                    WHERE id=? AND patient_id=?
                ");
                $stmt->execute([
                    $data['date'],
                    $data['type_name'],
                    $data['content'],
                    $id,
                    $patient_id
                ]);

            } elseif ($action === 'delete') {
                $stmt = $pdo->prepare("DELETE FROM lab_reports WHERE id=? AND patient_id=?");
                $stmt->execute([$id, $patient_id]);
            }
        break;


        default:
            throw new Exception("Invalid type provided");
    }

    echo json_encode([
        'success' => true,
        'id' => $id
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}
