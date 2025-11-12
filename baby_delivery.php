<?php
require_once __DIR__ . '/lib/auth.php';
require_once __DIR__ . '/config/db.php';
start_secure_session();
$user = current_user();
if (!$user) {
    header('Location: 1signinsignup.php');
    exit;
}

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? null;
    $dob = $_POST['dob'] ?? null;
    $tob = $_POST['tob'] ?? null;
    $sex = $_POST['sex'] ?? null;
    $weight = $_POST['weight'] ?? null;

    if (empty($name) || empty($dob) || empty($sex)) {
        $error = 'Please fill in all required fields.';
    } else {
        try {
            // Detect if babies.time_of_birth column exists to avoid SQL errors on older schemas
            $hasTimeOfBirth = false;
            try {
                $desc = $pdo->query('DESCRIBE babies');
                $columns = $desc->fetchAll(PDO::FETCH_ASSOC);
                foreach ($columns as $col) {
                    if (isset($col['Field']) && $col['Field'] === 'time_of_birth') {
                        $hasTimeOfBirth = true;
                        break;
                    }
                }
            } catch (Exception $e) {
                // If DESCRIBE fails, default to not having the column
                $hasTimeOfBirth = false;
            }

            if ($hasTimeOfBirth) {
                $stmt = $pdo->prepare('INSERT INTO babies (user_id, name, dob, time_of_birth, sex, birth_weight_grams) VALUES (?, ?, ?, ?, ?, ?)');
                $stmt->execute([$user['id'], $name, $dob, $tob ?: null, $sex, $weight ?: null]);
            } else {
                // Fallback insert without time_of_birth column
                $stmt = $pdo->prepare('INSERT INTO babies (user_id, name, dob, sex, birth_weight_grams) VALUES (?, ?, ?, ?, ?)');
                $stmt->execute([$user['id'], $name, $dob, $sex, $weight ?: null]);
            }
            header('Location: 6child.php');
            exit;
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Baby Delivery</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8e5e8;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .delivery-form-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }
        .delivery-form-container h1 {
            color: #e07f91;
            text-align: center;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #5d5d5d;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
        }
        .form-group input[type="submit"] {
            background-color: #e07f91;
            color: white;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .form-group input[type="submit"]:hover {
            background-color: #d46a7e;
        }
        .error-message {
            color: #d9534f;
            background-color: #f2dede;
            border: 1px solid #ebccd1;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="delivery-form-container">
        <h1>Log Baby's Delivery</h1>
        <?php if ($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form action="baby_delivery.php" method="POST">
            <div class="form-group">
                <label for="name">Baby's Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="dob" required>
            </div>
            <div class="form-group">
                <label for="tob">Time of Birth (optional)</label>
                <input type="time" id="tob" name="tob">
            </div>
            <div class="form-group">
                <label for="sex">Sex</label>
                <select id="sex" name="sex" required>
                    <option value="" disabled selected>Select Sex</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="weight">Birth Weight (grams)</label>
                <input type="number" id="weight" name="weight" placeholder="e.g., 3200">
            </div>
            <div class="form-group">
                <input type="submit" value="Save Baby Details">
            </div>
        </form>
    </div>
</body>
</html>