<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/lib/auth.php';

// User credentials
$username = 'testuser';
$password = 'password';

// Find the user in the database
$stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password_hash'])) {
    create_session_for_user($user['id']);
    echo "User logged in successfully!\n";

    // Now populate the data
    $user_id = $user['id'];

    // Find the baby_id for the current user
    $stmt = $pdo->prepare("SELECT id FROM babies WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $baby = $stmt->fetch();

    if (!$baby) {
        // If no baby, create one
        $stmt = $pdo->prepare("INSERT INTO babies (user_id, name, dob) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, 'Test Baby', '2023-01-01']);
        $baby_id = $pdo->lastInsertId();
        echo "Created a new baby.\n";
    } else {
        $baby_id = $baby['id'];
    }

    // Sample growth data (weight in kg, height in cm)
    $growth_data = [
        ['recorded_at' => '2023-01-15', 'weight_grams' => 3500, 'height_mm' => 500],
        ['recorded_at' => '2023-02-15', 'weight_grams' => 4500, 'height_mm' => 540],
        ['recorded_at' => '2023-03-15', 'weight_grams' => 5400, 'height_mm' => 580],
        ['recorded_at' => '2023-04-15', 'weight_grams' => 6200, 'height_mm' => 610],
        ['recorded_at' => '2023-05-15', 'weight_grams' => 6800, 'height_mm' => 640],
        ['recorded_at' => '2023-06-15', 'weight_grams' => 7300, 'height_mm' => 660],
    ];

    $stmt = $pdo->prepare("INSERT INTO baby_growth (baby_id, recorded_at, weight_grams, height_mm) VALUES (?, ?, ?, ?)");

    foreach ($growth_data as $data) {
        try {
            $stmt->execute([$baby_id, $data['recorded_at'], $data['weight_grams'], $data['height_mm']]);
        } catch (PDOException $e) {
            // Ignore duplicate entries
            if ($e->errorInfo[1] != 1062) {
                throw $e;
            }
        }
    }

    echo "Growth data populated successfully!\n";

} else {
    echo "Invalid username or password.\n";
}
?>