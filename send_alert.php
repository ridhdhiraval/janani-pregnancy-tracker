<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ✅ Show all errors in browser (for debugging)
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// ✅ Always return JSON
header('Content-Type: application/json');

// ✅ Global exception handler to catch any uncaught errors
set_exception_handler(function($e) {
    echo json_encode(['status' => 'error', 'message' => 'Uncaught exception: ' . $e->getMessage()]);
    exit;
});

// ✅ Global error handler to catch any PHP errors
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    echo json_encode(['status' => 'error', 'message' => "PHP error [$errno]: $errstr in $errfile on line $errline"]);
    exit;
});

// ✅ Shutdown handler to catch fatal errors
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        echo json_encode(['status' => 'error', 'message' => 'Fatal error: ' . $error['message'] . ' in ' . $error['file'] . ' on line ' . $error['line']]);
    }
});

// ✅ Include PHPMailer
require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

// ✅ Database connection
$conn = new mysqli('localhost', 'root', '', 'janani_db');
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// ✅ Read JSON input
$raw = file_get_contents('php://input');
if (!$raw) {
    echo json_encode(['status' => 'error', 'message' => 'No input received']);
    exit;
}

$input = json_decode($raw, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid JSON format']);
    exit;
}

// ✅ Extract data
$user_id = isset($input['user_id']) && is_numeric($input['user_id']) && $input['user_id'] > 0 ? (int)$input['user_id'] : null;
$latitude = $input['latitude'] ?? 0;
$longitude = $input['longitude'] ?? 0;
$date = date('Y-m-d');
$time = date('H:i:s');
$map_link = "https://maps.google.com/?q={$latitude},{$longitude}";

$message = "🚨 EMERGENCY ALERT!\nMother may need help!\n\n"
    . "📅 Date: {$date}\n⏰ Time: {$time}\n📍 Location: {$map_link}";

// ✅ Save to database
$stmt = $conn->prepare("INSERT INTO emergency_alerts (user_id, latitude, longitude, location_link, message) VALUES (?, ?, ?, ?, ?)");
if ($stmt === false) {
    echo json_encode(['status' => 'error', 'message' => 'Database prepare failed: ' . $conn->error]);
    exit;
}

$stmt->bind_param('iddss', $user_id, $latitude, $longitude, $map_link, $message);

if ($stmt->execute() === false) {
    echo json_encode(['status' => 'error', 'message' => 'Database execute failed: ' . $stmt->error]);
    exit;
}
$stmt->close();

// ✅ Send email using PHPMailer
try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'km208337@gmail.com';  // your Gmail
    $mail->Password = 'jmyn ezhq nvds yumn'; // your app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom($mail->Username, 'JANANI Alert System');
    $mail->addAddress('ridhdhiraval2005@gmail.com'); // receiver email
    $mail->isHTML(true);
    $mail->Subject = '🚨 Emergency Alert from JANANI';
    $mail->Body = nl2br($message);

    $mail->send();

    echo json_encode(['status' => 'success', 'message' => 'Alert sent successfully!']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
}

exit;
?>
