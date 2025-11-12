<?php
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../config/db.php';
start_secure_session();

$user = current_user();
if (!$user) {
    // Not logged in - redirect to sign in
    header('Location: /JANANI/1signinsignup.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

$csrf = $_POST['csrf_token'] ?? '';
if (!verify_csrf_token($csrf)) {
    $_SESSION['errors'] = ['Invalid CSRF token'];
    header('Location: /JANANI/1signinsignup.php');
    exit;
}

// Accept either direct EDD (due_date) or LMP (lmp_date)
$due = $_POST['due_date'] ?? '';
$lmp = $_POST['lmp_date'] ?? '';

if ($lmp) {
    // expect YYYY-MM-DD
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $lmp)) {
        $_SESSION['errors'] = ['Invalid LMP date format'];
        header('Location: /JANANI/3select_last_period.php');
        exit;
    }
    // compute EDD from LMP (+280 days)
    $due = date('Y-m-d', strtotime($lmp . ' +280 days'));
}

if (!$due || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $due)) {
    $_SESSION['errors'] = ['Invalid date format'];
    header('Location: /JANANI/3select_due_date.php');
    exit;
}

// If user has an active pregnancy, update it; otherwise insert
$stmt = $pdo->prepare('SELECT id FROM pregnancies WHERE user_id = ? AND status = "active" ORDER BY created_at DESC LIMIT 1');
$stmt->execute([$user['id']]);
$row = $stmt->fetch();
if ($row) {
    $pregnancy_id = $row['id'];
    $update = $pdo->prepare('UPDATE pregnancies SET edd = ?, lmp_date = ?, updated_at = NOW() WHERE id = ?');
    $update->execute([$due, $lmp ?: null, $pregnancy_id]);
} else {
    $ins = $pdo->prepare('INSERT INTO pregnancies (user_id, edd, lmp_date, status) VALUES (?, ?, ?, "active")');
    $ins->execute([$user['id'], $due, $lmp ?: null]);
    $pregnancy_id = $pdo->lastInsertId();
}

header('Location: /JANANI/4congratulations.php?pregnancy_id=' . urlencode($pregnancy_id));
exit;
