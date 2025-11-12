<?php
require_once __DIR__ . '/../lib/auth.php';
start_secure_session();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Method not allowed';
    exit;
}

$csrf = $_POST['csrf_token'] ?? '';
if (!verify_csrf_token($csrf)) {
    $_SESSION['form_mode'] = 'sign-up';
    $_SESSION['errors'] = ['Invalid CSRF token'];
    header('Location: /JANANI/1signinsignup.php');
    exit;
}

$username = trim($_POST['username_up'] ?? '');
$email = trim($_POST['email_up'] ?? '');
$password = $_POST['password_up'] ?? '';
$password_confirm = $_POST['password_confirm'] ?? '';

$full_name = trim($_POST['full_name_up'] ?? '');
$phone = trim($_POST['phone_up'] ?? '');
$gender = $_POST['gender_up'] ?? null;
$dob = $_POST['dob_up'] ?? null;

// Basic server-side validation
$errors = [];
if ($username === '' || strlen($username) < 3) $errors[] = 'Username must be at least 3 characters.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email.';
if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
if ($password !== $password_confirm) $errors[] = 'Passwords do not match.';

if (!empty($errors)) {
    $_SESSION['form_mode'] = 'sign-up';
    $_SESSION['errors'] = $errors;
    header('Location: /JANANI/1signinsignup.php');
    exit;
}

// Check for existing user
if (findUserByEmail($email) || findUserByUsername($username)) {
    $_SESSION['form_mode'] = 'sign-up';
    $_SESSION['errors'] = ['Email or username already taken.'];
    header('Location: /JANANI/1signinsignup.php');
    exit;
}

// Create user
$hash = password_hash($password, PASSWORD_DEFAULT);
$userId = createUser($username, $email, $hash);

// Create or update profile row
$first = '';
$last = '';
if ($full_name !== '') {
    $parts = preg_split('/\s+/', $full_name);
    $first = array_shift($parts);
    $last = trim(implode(' ', $parts));
}

$stmt = $pdo->prepare('INSERT INTO user_profiles (user_id, first_name, last_name, phone, gender, dob) VALUES (?, ?, ?, ?, ?, ?)');
$stmt->execute([$userId, $first ?: null, $last ?: null, $phone ?: null, $gender ?: null, $dob ?: null]);

// Create session and optionally remember
create_session_for_user($userId);
if (!empty($_POST['remember'])) {
    create_remember_token($userId);
}

// Redirect to saved URL if present
$redirect = $_SESSION['after_login_redirect'] ?? '/JANANI/2start_pregnancy.php';
unset($_SESSION['after_login_redirect']);
header('Location: ' . $redirect);
exit;
