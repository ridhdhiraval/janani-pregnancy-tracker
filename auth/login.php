<?php
require_once __DIR__ . '/../lib/auth.php';
start_secure_session();

// Enable error logging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log the start of login process
error_log('Login process started');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method not allowed';
    error_log('Error: Invalid request method');
    exit;
}

$csrf = $_POST['csrf_token'] ?? '';
if (!verify_csrf_token($csrf)) {
    $_SESSION['form_mode'] = 'sign-in';
    $_SESSION['errors'] = ['Invalid CSRF token'];
    header('Location: /JANANI/1signinsignup.php');
    exit;
}

$username = trim($_POST['username_in'] ?? '');
$password = $_POST['password_in'] ?? '';

// find by username first then fallback to email
$user = findUserByUsername($username);
if (!$user) {
    $user = findUserByEmail($username);
}

if (!$user || !password_verify($password, $user['password_hash'])) {
    $_SESSION['form_mode'] = 'sign-in';
    $_SESSION['errors'] = ['Invalid username/email or password'];
    error_log('Login failed: Invalid credentials');
    header('Location: /JANANI/1signinsignup.php');
    exit;
}

error_log('User authenticated successfully: ' . $user['username']);

// Successful login
create_session_for_user($user['id']);
if (!empty($_POST['remember'])) {
    create_remember_token($user['id']);
}

// Always go to start pregnancy flow after login
$redirect = '/JANANI/5index.php';



// Clear any stored redirect
unset($_SESSION['after_login_redirect']);

// Ensure the URL is safe
$redirect = filter_var($redirect, FILTER_SANITIZE_URL);
error_log('Redirecting to: ' . $redirect);
header('Location: ' . $redirect);
exit;
