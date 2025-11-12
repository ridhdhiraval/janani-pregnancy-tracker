<?php
require_once 'config/db.php';

try {
    $stmt = $pdo->query('SELECT * FROM baby_growth ORDER BY recorded_at ASC');
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo 'Growth data records: ' . count($data) . PHP_EOL;
    if (count($data) > 0) {
        foreach ($data as $record) {
            echo 'ID: ' . $record['id'] . ', Baby ID: ' . $record['baby_id'] . ', Weight: ' . $record['weight_grams'] . 'g, Height: ' . $record['height_mm'] . 'mm, Date: ' . $record['recorded_at'] . PHP_EOL;
        }
    } else {
        echo "No growth data found." . PHP_EOL;
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
?>