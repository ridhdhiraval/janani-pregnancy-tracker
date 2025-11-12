<?php
require_once __DIR__ . '/lib/auth.php';

// Hardcoded credentials for programmatic login
$username = 'testuser';
$password = 'password123';

// Manually verify the user's password
$user = find_user_by_username($username);

if ($user && password_verify($password, $user['password_hash'])) {
    // Regenerate session ID to prevent session fixation
    session_regenerate_id(true);

    // Store user data in the session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['logged_in_at'] = time();

    // Redirect to the growth chart page
    header('Location: growth.php');
    exit;
} else {
    // Handle login failure
    echo "Programmatic login failed. Please check credentials and user status.";
    exit;
}
?>