<?php
require_once 'config/db.php';

$stmt = $pdo->prepare('SELECT * FROM babies WHERE user_id = 1 ORDER BY id DESC LIMIT 1');
$stmt->execute();
$baby = $stmt->fetch(PDO::FETCH_ASSOC);

if ($baby) {
    echo "Baby ID: " . $baby['id'] . "\n";
    echo "Name: " . $baby['name'] . "\n";
    echo "Date of Birth: " . $baby['dob'] . "\n";
    echo "Sex: " . $baby['sex'] . "\n";
    echo "User ID: " . $baby['user_id'] . "\n";
    echo "Created: " . $baby['created_at'] . "\n";
} else {
    echo "No baby data found for user_id = 1\n";
}
?>