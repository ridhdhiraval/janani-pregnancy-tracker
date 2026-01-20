<?php

ob_start();
session_start();

// If already logged in → go to dashboard
if (isset($_SESSION['admin_email'])) {
    header("Location: pages/dashboard.php");
    exit;
}

$errors = [];
$name = '';
$email = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name     = trim($_POST['name']    ?? '');
    $email    = trim($_POST['email']   ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm  = trim($_POST['confirm']  ?? '');

    // Basic validation
    if ($name === '' || $email === '' || $password === '' || $confirm === '') {
        $errors[] = "All fields are required!";
    }

    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format!";
    }

    if ($password !== $confirm) {
        $errors[] = "Passwords do not match!";
    }

    // If no errors → save admin temporarily in session
    if (empty($errors)) {
        $_SESSION["registered_admin"] = [
            "name"     => $name,
            "email"    => $email,
            "password" => $password
        ];

        // Redirect to login
        header("Location: login.php?registered=1");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Admin Register | Janani Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #005eff;
            --dark-blue: #0047c6;
            --light-bg: #e6f0ff;
            --text-color: #333;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--light-bg);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            display: flex;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.2);
            max-width: 900px;
            width: 90%;
            transition: all 0.3s ease;
        }

        .left-panel {
            background: var(--dark-blue);
            color: white;
            padding: 40px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .left-panel h2 {
            margin-top: 15px;
            margin-bottom: 10px;
            font-weight: 600;
            font-size: 24px;
        }

        .left-panel p {
            opacity: 0.9;
            font-weight: 300;
            font-size: 15px;
        }

        .left-panel .icon {
            font-size: 60px;
            margin-bottom: 20px;
            line-height: 1;
        }

        .right-panel {
            background: white;
            padding: 40px;
            flex: 1.2;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .right-panel h2 {
            text-align: left;
            color: var(--text-color);
            margin-bottom: 5px;
            font-weight: 600;
            font-size: 28px;
        }

        .right-panel p.subtitle {
            color: var(--primary-blue);
            font-weight: 500;
            margin-bottom: 25px;
            font-size: 16px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid #ddd;
            font-size: 15px;
            box-sizing: border-box;
        }

        input:focus {
            border-color: var(--primary-blue);
            outline: none;
            box-shadow: 0 0 0 2px rgba(0, 94, 255, 0.2);
        }

        button {
            width: 100%;
            margin-top: 20px;
            padding: 14px;
            border-radius: 6px;
            border: none;
            background: var(--primary-blue);
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: var(--dark-blue);
        }

        .error-box {
            background: #ffebeb;
            color: #cc0000;
            padding: 10px;
            border-left: 4px solid #cc0000;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 14px;
            line-height: 1.4;
        }

        .login-link {
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
            color: var(--text-color);
        }

        a {
            color: var(--primary-blue);
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
        }

        a:hover {
            color: var(--dark-blue);
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                max-width: 400px;
            }

            .left-panel {
                padding: 30px;
                border-radius: 15px 15px 0 0;
            }

            .right-panel {
                padding: 30px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="left-panel">
            <div class="icon">
                <svg width="65" height="65" viewBox="0 0 24 24" fill="white">
                    <path d="M12 2a4 4 0 1 1 0 8a4 4 0 0 1 0-8zm2 9h-4a4 4 0 0 0-4 4v6h2v-6a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v6h2v-6a4 4 0 0 0-4-4z" />
                </svg>
            </div>

            <h2>Janani Admin</h2>
            <p>Create a new administrative account to gain full access to the control panel.</p>
        </div>

        <div class="right-panel">
            <h2>New Account</h2>
            <p class="subtitle">Admin Registration</p>

            <!-- Errors -->
            <?php if (!empty($errors)): ?>
                <div class="error-box">
                    <?php foreach ($errors as $e): ?>
                        <div>• <?= htmlspecialchars($e) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <input type="text" name="name" placeholder="Full Name" value="<?= htmlspecialchars($name) ?>" required>
                <input type="email" name="email" placeholder="Email Address" value="<?= htmlspecialchars($email) ?>" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirm" placeholder="Confirm Password" required>
                <button type="submit">Register Account</button>
            </form>

            <div class="login-link">
                Already have an account? <a href="login.php">Login</a>
            </div>
        </div>
    </div>
</body>

</html>