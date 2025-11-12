<?php
session_start();
require_once 'config/db.php';

// Simulate logged-in user
$_SESSION['user_id'] = 1;

// Test data for baby delivery
$test_data = [
    'name' => 'Test Baby',
    'sex' => 'male',
    'dob' => '2024-11-01',
    'birth_weight_grams' => 3200,
    'notes' => 'Test delivery submission'
];

try {
    // Insert test baby data
    $stmt = $pdo->prepare("INSERT INTO babies (user_id, name, sex, dob, birth_weight_grams, notes, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    
    $stmt->execute([
        $_SESSION['user_id'],
        $test_data['name'],
        $test_data['sex'],
        $test_data['dob'],
        $test_data['birth_weight_grams'],
        $test_data['notes']
    ]);
    
    echo "Test baby data inserted successfully!<br>";
    echo "Baby ID: " . $pdo->lastInsertId() . "<br>";
    echo '<a href="6child.php">View in Child Page</a>';
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>