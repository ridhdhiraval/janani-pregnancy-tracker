<?php
// Database connection for Janani app (XAMPP)
// Configure here if needed
$DB_NAME = 'janani_db';
$DB_USER = 'root';
$DB_PASS = '';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Try multiple host/port combos commonly used in XAMPP
$DB_TARGETS = [
    ['host' => '127.0.0.1', 'port' => 3306],
    ['host' => 'localhost', 'port' => 3306],
    ['host' => '127.0.0.1', 'port' => 3307], // alternate port if 3306 busy
];

$pdo = null;
$lastException = null;
$connectionError = '';

foreach ($DB_TARGETS as $t) {
    $host = $t['host'];
    $port = $t['port'];
    $dsn = "mysql:host={$host};port={$port};dbname={$DB_NAME};charset=utf8mb4";
    try {
        $pdo = new PDO($dsn, $DB_USER, $DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        // Test the connection
        $pdo->query('SELECT 1');
        break; // success
    } catch (PDOException $e) {
        $connectionError = "Failed to connect to {$host}:{$port} - " . $e->getMessage() . "\n";
        error_log($connectionError);
        $lastException = $e;
        $pdo = null; // Ensure $pdo is null if connection fails
        // try next target
    }
}

if ($pdo === null) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    // Log detailed error
    $errorMsg = 'Database connection failed. ' . ($lastException ? $lastException->getMessage() : 'No connection could be made.');
    error_log('[JANANI] Database connection error: ' . $errorMsg);
    
    // Store a friendly message for the UI
    $_SESSION['errors'] = ['Database connection failed. Please check your database server and credentials.'];
    
    // For debugging - you might want to remove this in production
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        // AJAX request
        header('Content-Type: application/json');
        die(json_encode(['error' => 'Database connection failed']));
    } else {
        // Regular request
        if (!headers_sent()) {
            header('Location: /JANANI/1signinsignup.php?error=database_connection');
        }
    }
    exit;
}
