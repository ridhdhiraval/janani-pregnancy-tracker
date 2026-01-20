<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: ../login.php");
    exit;
}

include '../inc/db.php'; // your PDO connection

if (!isset($_GET['type'])) {
    die("No type provided.");
}

$type = $_GET['type'];
$table = "";
$extraJoin = "";
$renameColumns = [];

// ---------- TABLE SETUP ----------
switch ($type) {

    case 'users':
        $table = "users";
        break;

    case 'doctors':
        $table = "doctors";

        // JOIN to get doctor name from users table
        $extraJoin = " LEFT JOIN users ON doctors.user_id = users.id ";

        // Add a renamed column for output
        $renameColumns = [
            'users.username' => 'doctor_name'
        ];
        break;

    case 'appointments':
        $table = "appointments";
        break;

    default:
        die("Invalid type.");
}

// ----------- FETCH COLUMN NAMES AUTOMATICALLY ------------
$colsQuery = $pdo->prepare("SHOW COLUMNS FROM $table");
$colsQuery->execute();
$columns = $colsQuery->fetchAll(PDO::FETCH_COLUMN);

// Add doctor name column if doctors table
if ($type === 'doctors') {
    $columns[] = 'doctor_name';
}

// ----------- SET DOWNLOAD HEADERS ---------------
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename={$type}_data.csv");

$output = fopen("php://output", "w");
fputcsv($output, $columns);

// ----------- BUILD SELECT QUERY ----------------
$selectCols = [];

foreach ($columns as $col) {

    if ($col === 'doctor_name') {
        $selectCols[] = "users.username AS doctor_name";
    } else {
        $selectCols[] = "$table.$col";
    }
}

$finalSelect = implode(", ", $selectCols);

$sql = "SELECT $finalSelect FROM $table $extraJoin";

$stmt = $pdo->prepare($sql);
$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, $row);
}

fclose($output);
exit;
