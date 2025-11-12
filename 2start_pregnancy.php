<?php
require_once __DIR__ . '/lib/auth.php';
require_once __DIR__ . '/config/db.php';
start_secure_session();

// Check if user is logged in
$user = current_user();
if (!$user) {
    // If not logged in, redirect to login page
    $_SESSION['after_login_redirect'] = '/JANANI/2start_pregnancy.php';
    header('Location: /JANANI/1signinsignup.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Janani</title>
    <style>
        /* CSS to match the app's clean, pink-and-white aesthetic */
        body {
            font-family: Arial, sans-serif;
            background-color: #fcf6f6; /* Very light pink/off-white background */
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            margin: 0;
            min-height: 100vh;
            color: #4a4a4a;
        }
        .container {
            width: 90%;
            max-width: 400px;
            text-align: center;
            margin-top: 50px;
        }
        .header {
            margin-bottom: 40px;
        }
        .heart-icon {
            color: #ff85a2; /* Pink color for heart */
            font-size: 36px;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-top: 10px;
        }
        .subtitle {
            font-size: 14px;
            color: #888;
            margin-top: 5px;
        }
        .card-box {
            background-color: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            margin-top: 30px;
        }
        .card-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        /* The option-card is styled as a link for navigation */
        .option-card {
            background-color: #fff8f8; /* Lighter pink for card background */
            border: 1px solid #ffc8d5;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            text-align: left;
            cursor: pointer;
            transition: background-color 0.2s;
            text-decoration: none; /* Remove underline from link */
            display: block; /* Make the link cover the whole card area */
            color: inherit; /* Inherit text color */
        }
        .option-card:hover {
            background-color: #ffe6e9;
        }
        .option-card i {
            font-size: 20px;
            color: #ff69b4; /* Brighter pink for icons */
            margin-right: 10px;
        }
        .option-card-label {
            font-weight: bold;
            display: block;
            color: #333;
        }
        .option-card-description {
            font-size: 12px;
            color: #888;
        }
        .footer-text {
            font-size: 12px;
            color: #aaa;
            margin-top: 20px;
        }
        /* Using a common icon font like Font Awesome for icons (requires linking) */
        @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <div class="heart-icon">&#x2764;</div> 
        <div class="title">Welcome to Janani</div>
        <div class="subtitle">
            Let us add your pregnancy and adjust the website according to your needs
        </div>
    </div>

    <div class="card-box">
        <div class="card-title">How do you want to enter your pregnancy?</div>
        
        <!-- Option 1: Calculate using Last Period -->
        <a href="3select_last_period.php" class="option-card">
            <span class="option-card-label"><i class="fas fa-calendar-alt"></i> Calculate using Last Period</span>
            <span class="option-card-description">Enter the first day of your last menstrual period</span>
        </a>

        <!-- Option 2: I have an Estimated Due Date (MODIFIED to navigate to the new page) -->
        <a href="3select_due_date.php" class="option-card">
            <span class="option-card-label"><i class="fas fa-heart"></i> I have an Estimated Due Date</span>
            <span class="option-card-description">Enter your baby's expected arrival date</span>
        </a>
    </div>

    <div class="footer-text">
        Your data is private and secure
    </div>
</div>

</body>
</html>
