<?php
require_once __DIR__ . '/../lib/auth.php';
start_secure_session();

// Set a flag to force setup on next login
$_SESSION['force_setup'] = true;

// Clear the user session
logout_user();

// Redirect to login page
header('Location: /JANANI/1signinsignup.php');
exit;
