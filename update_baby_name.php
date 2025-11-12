<?php
require_once 'config/db.php';

$stmt = $pdo->prepare('UPDATE babies SET name = ? WHERE user_id = 1 AND id = 1');
$result = $stmt->execute(['Krish']);

if ($result) {
    echo 'Baby name updated successfully to Krish!' . "\n";
} else {
    echo 'Failed to update baby name.' . "\n";
}
?>