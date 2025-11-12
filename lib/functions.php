<?php
require_once __DIR__ . '/../config/db.php';

function findUserByEmail($email) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    return $stmt->fetch();
}

function findUserByUsername($username) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    return $stmt->fetch();
}

function createUser($username, $email, $password_hash, $role = 'mother') {
    global $pdo;
    $stmt = $pdo->prepare('INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)');
    $stmt->execute([$username, $email, $password_hash, $role]);
    return $pdo->lastInsertId();
}

function flash($key, $message = null) {
    if ($message === null) {
        if (isset($_SESSION[$key])) {
            $m = $_SESSION[$key];
            unset($_SESSION[$key]);
            return $m;
        }
        return null;
    }
    $_SESSION[$key] = $message;
}
