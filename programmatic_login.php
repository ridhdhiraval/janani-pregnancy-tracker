<?php
require_once __DIR__ . '/lib/auth.php';
require_once __DIR__ . '/config/db.php';

// Hardcoded user credentials for testing
$username = 'testuser';
$password = 'password';

// Find the user by username
$stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password_hash'])) {
    create_session_for_user($user['id']);
    echo "User logged in successfully!";
} else {
    echo "Invalid username or password.";
}
?>