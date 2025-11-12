<?php
require_once __DIR__ . '/config/db.php';

// New user credentials
$username = 'testuser';
$password = 'password';
$full_name = 'Test User';
$email = 'testuser@example.com';

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert the new user into the database
$stmt = $pdo->prepare('INSERT INTO users (username, password_hash, email) VALUES (?, ?, ?)');

try {
    $pdo->beginTransaction();
    $stmt->execute([$username, $hashed_password, $email]);
    $user_id = $pdo->lastInsertId();

    // Insert into user_profiles
    $profile_stmt = $pdo->prepare('INSERT INTO user_profiles (user_id, first_name) VALUES (?, ?)');
    $profile_stmt->execute([$user_id, $full_name]);

    $pdo->commit();
    echo "User created successfully!";
} catch (PDOException $e) {
    $pdo->rollBack();
    if ($e->errorInfo[1] == 1062) {
        echo "Username or email already exists.";
    } else {
        echo "Error: " . $e->getMessage();
    }
}
?>