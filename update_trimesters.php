<?php
require_once __DIR__ . '/config/db.php';

$sql = "
    UPDATE checklist_items SET trimester = 1 WHERE category LIKE 'Trimester 1:%';
    UPDATE checklist_items SET trimester = 2 WHERE category LIKE 'Trimester 2:%';
    UPDATE checklist_items SET trimester = 3 WHERE category LIKE 'Trimester 3:%';
";

try {
    $pdo->exec($sql);
    echo "Trimester data updated successfully.";
} catch (PDOException $e) {
    die("Error updating trimester data: " . $e->getMessage());
}
?>