<?php
require_once __DIR__ . '/functions.php';

// Start secure session settings
function start_secure_session() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function generate_csrf_token() {
    start_secure_session();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    start_secure_session();
    return !empty($token) && !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function create_session_for_user($user_id) {
    start_secure_session();
    // regenerate session id
    session_regenerate_id(true);
    $_SESSION['user_id'] = $user_id;
}

function logout_user() {
    start_secure_session();
    // clear remember cookie if exists
    if (!empty($_COOKIE['janani_remember'])) {
        setcookie('janani_remember', '', time() - 3600, '/');
    }
    session_unset();
    session_destroy();
}

function current_user() {
    start_secure_session();
    global $pdo;
    if (!empty($_SESSION['user_id'])) {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch();
    }
    // remember-me via cookie
    if (!empty($_COOKIE['janani_remember'])) {
        list($uid, $token) = explode(':', $_COOKIE['janani_remember'], 2) + [null, null];
        if ($uid && $token) {
            $stmt = $pdo->prepare('SELECT * FROM auth_tokens WHERE user_id = ? AND token = ? AND expires_at > NOW() LIMIT 1');
            $stmt->execute([$uid, $token]);
            $row = $stmt->fetch();
            if ($row) {
                // refresh session
                create_session_for_user($uid);
                return findUserById($uid);
            }
        }
    }
    return null;
}

function findUserById($id) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function create_remember_token($user_id) {
    global $pdo;
    $token = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', strtotime('+30 days'));
    $stmt = $pdo->prepare('INSERT INTO auth_tokens (user_id, token, expires_at) VALUES (?, ?, ?)');
    $stmt->execute([$user_id, $token, $expires]);
    // set cookie
    setcookie('janani_remember', $user_id . ':' . $token, time() + 60*60*24*30, '/');
}
