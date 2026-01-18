<?php
date_default_timezone_set('Asia/Kolkata');
require_once '../config/db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$action = $data['action'] ?? null;
$type = $data['type'] ?? null;
$patient_id = isset($data['patient_id']) ? intval($data['patient_id']) : null;
$id = isset($data['id']) ? intval($data['id']) : null;

try {
    if($type!=='appointments') throw new Exception('Invalid type');
    if($action==='add'){
        $stmt=$pdo->prepare("INSERT INTO appointments (patient_id, appt_date, appt_time, appt_type, status) VALUES (?,?,?,?,?)");
        $stmt->execute([$patient_id,$data['date'],$data['time'],$data['type_name'],$data['status']]);
        $id=$pdo->lastInsertId();
    }elseif($action==='update'){
        $stmt=$pdo->prepare("UPDATE appointments SET patient_id=?, appt_date=?, appt_time=?, appt_type=?, status=? WHERE id=?");
        $stmt->execute([$patient_id,$data['date'],$data['time'],$data['type_name'],$data['status'],$id]);
    }elseif($action==='delete'){
        $stmt=$pdo->prepare("DELETE FROM appointments WHERE id=?");
        $stmt->execute([$id]);
    }else throw new Exception('Invalid action');

    echo json_encode(['success'=>true,'id'=>$id]);
}catch(Exception $e){
    http_response_code(500);
    echo json_encode(['error'=>$e->getMessage()]);
}
