<?php

session_start();

if (isset($_SESSION['admin_email'])) {
    header("Location: pages/dashboard.php");
    exit;
}

// TEMPORARY ADMIN CREDENTIAL (change later)
if (isset($_SESSION['registered_admin'])) {
    $ADMIN_EMAIL = $_SESSION['registered_admin']['email'];
    $ADMIN_PASS  = $_SESSION['registered_admin']['password'];
} else {
    // TEMP fallback admin
    $ADMIN_EMAIL = "hensypatell19@gmail.com";
    $ADMIN_PASS  = "h12345";
}

$error = null;
$success = null;

// Check for registration success message (UX improvement based on register.php logic)
if (isset($_GET['registered'])) {
    $success = "Registration successful! Please login.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"] ?? '');
    $password = trim($_POST["password"] ?? '');

    if ($email === $ADMIN_EMAIL && $password === $ADMIN_PASS) {
        $_SESSION['admin_email'] = $email;
        header("Location: pages/dashboard.php");
        exit;
    } else {
        $error = "Invalid login credentials!";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Login | Janani Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #005eff;
            --dark-blue: #0047c6;
            /* Slightly darker shade for the left panel */
            --light-bg: #e6f0ff;
            /* Light background for the body */
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

        /* New Split Screen Container */
        .container {
            display: flex;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.2);
            max-width: 900px;
            width: 90%;
            transition: all 0.3s ease;
        }

        /* Left Panel - Dark Blue Side */
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
            color: white;
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
            margin-bottom: 20px;
        }

        /* Right Panel - White Form Side */
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

        .error-box,
        .success-box {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 14px;
            line-height: 1.4;
        }

        .error-box {
            background: #ffebeb;
            color: #cc0000;
            border-left: 4px solid #cc0000;
        }

        .success-box {
            background: #e6f7ff;
            color: var(--primary-blue);
            border-left: 4px solid var(--primary-blue);
        }

        .register-link {
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

        /* Responsive adjustments for mobile */
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
            <p>Access the control panel to manage user data and application settings.</p>
        </div>
        <div class="right-panel">
            <h2>Welcome Back</h2>
            <p class="subtitle">Admin Login</p>

            <!-- Login Messages -->
            <?php
            if (!empty($success)) {
                echo "<div class='success-box'>{$success}</div>";
            } elseif (!empty($error)) {
                echo "<div class='error-box'>{$error}</div>";
            }
            ?>

            <form method="POST">
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
            <div class="register-link">
                Don't have an account? <a href="register.php">Register Now</a>
            </div>

        </div>
    </div>

</body>

</html>