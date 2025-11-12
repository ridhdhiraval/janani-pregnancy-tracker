<?php
session_start();
require_once 'config/db.php';

// Test database connection
try {
    $stmt = $pdo->query("SELECT 1");
    echo "Database connection: OK<br>";
} catch (Exception $e) {
    echo "Database connection failed: " . $e->getMessage() . "<br>";
}

// Test if babies table exists
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM babies");
    echo "Babies table exists: OK<br>";
} catch (Exception $e) {
    echo "Babies table issue: " . $e->getMessage() . "<br>";
}

// Simulate login
$_SESSION['user_id'] = 1;
echo "Session set for user_id: 1<br>";

// Test baby delivery form link
echo '<a href="baby_delivery.php">Test Baby Delivery Form</a><br>';
echo '<a href="6child.php">Test Child Page</a>';
?>