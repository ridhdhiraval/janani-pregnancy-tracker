<?php
// --- 1. PHP SETUP & LOGIC (Error Suppression ke saath) ---
$message_text = '';
$message_class = '';

// Koshish karein ki zaroori files include ho jaayein. Agar file nahi mili, toh Warning chhup jaayegi (@) aur code chalega.
// Lekin yaad rakhein: Email aur Database ka kaam nahi karega jab tak ye files nahi milengi!

try {
    // Apne database connection file ko include karein. Apne path ke hisaab se badal dein.
    @include 'db_connect.php'; 
    
    // PHPMailer classes ko include karein. Apne path ke hisaab se badal dein.
    @require 'PHPMailer/src/Exception.php';
    @require 'PHPMailer/src/PHPMailer.php';
    @require 'PHPMailer/src/SMTP.php';
    
    // Sirf tab chalao jab files mil gayi hon
    if (class_exists('PHPMailer\PHPMailer\PHPMailer') && function_exists('mysqli_connect')) {
        
        // Use classes only if they are defined
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
            $email = trim($_POST['email']);
            
            // Assume $conn is defined in db_connect.php
            if (!isset($conn) || !$conn) {
                throw new Exception("Database connection failed. Check db_connect.php");
            }

            // 1. Email ko 'users' table mein check karein
            $query = mysqli_query($conn, "SELECT id FROM users WHERE email='$email' LIMIT 1");

            if (mysqli_num_rows($query) > 0) {
                $token = bin2hex(random_bytes(32)); 
                $expiry = date("Y-m-d H:i:s", strtotime('+30 minutes'));
                mysqli_query($conn, "UPDATE users SET reset_token='$token', token_expiry='$expiry' WHERE email='$email'");

                $resetLink = "http://localhost/JANANI/reset_password.php?token=$token";

                // 5. Email bhejein
                $mail = new PHPMailer(true);
                // Configuration and sending logic...
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; 
                $mail->SMTPAuth = true;
                $mail->Username = 'your_email@gmail.com'; 
                $mail->Password = 'your_app_password';   
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->setFrom('your_email@gmail.com', 'RideNow Password Reset');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'RideNow: Password Reset Request';
                $mail->Body = "<p>Click the link to reset your password: <a href='$resetLink'>Reset Password</a></p>";
                $mail->send();
                
                $message_class = 'success';
                $message_text = '✅ Reset link aapke email address par bhej diya gaya hai.';

            } else {
                $message_class = 'error';
                $message_text = "⚠️ Email address hamare records mein nahi mila.";
            }
        }
    } else {
        // Agar PHPMailer ya DB files load nahi hui (phir bhi design dikhega)
        $message_class = 'error';
        $message_text = "⚠️ Configuration error: Email sending is disabled. Please check PHP includes.";
    }

} catch (Exception $e) {
    // Agar koi bhi PHP ya Mailer error aaye
    $message_class = 'error';
    $message_text = "❌ System Error: " . $e->getMessage();
} catch (Error $e) {
    // Fatal errors ke liye
    $message_class = 'error';
    $message_text = "❌ Fatal Error. Please check file paths and PHP version.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - RideNow</title>
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #FCE4EC; /* Light Baby Pink Background */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .form-container {
            background: #ffffff;
            padding: 35px 45px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(233, 30, 99, 0.2); /* Pinkish shadow */
            width: 100%;
            max-width: 380px;
            text-align: center;
        }

        h2 {
            color: #E91E63; /* Primary Pink */
            margin-bottom: 30px;
            font-weight: 700;
        }

        input[type="email"] {
            width: 100%;
            padding: 14px;
            margin-bottom: 25px;
            border: 1px solid #FFC1E3; /* Light Pink Border */
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        input[type="email"]:focus {
            border-color: #E91E63;
            box-shadow: 0 0 5px rgba(233, 30, 99, 0.5);
            outline: none;
        }

        button[type="submit"] {
            background-color: #E91E63; /* Primary Pink Button */
            color: white;
            padding: 14px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-size: 17px;
            font-weight: 600;
            transition: background-color 0.3s, transform 0.2s;
        }

        /* Hover Effect */
        button[type="submit"]:hover {
            background-color: #C2185B; /* Darker Pink on hover */
            transform: translateY(-2px); /* Lift effect */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        a {
            color: #E91E63;
            text-decoration: none;
            font-size: 14px;
            display: block;
            margin-top: 20px;
            transition: color 0.3s;
        }

        a:hover {
            text-decoration: underline;
            color: #C2185B;
        }

        p.message {
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
            margin-bottom: 25px;
            font-weight: 600;
            text-align: left;
            border-left: 5px solid;
        }

        .success {
            background-color: #F8BBD0; 
            color: #4A148C;
            border-color: #E91E63;
        }

        .error {
            background-color: #FFCDD2; 
            color: #B71C1C; 
            border-color: #B71C1C;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>🔑 Password Reset</h2>

        <?php if ($message_text): ?>
            <p class="message <?= $message_class ?>"><?= $message_text ?></p>
        <?php endif; ?>

        <?php 
            // Agar message success ho gaya hai, toh form ko hide kar do
            if ($message_class !== 'success' || !isset($_POST['email'])): 
        ?>
            <form method="POST" action="forgot_password.php">
                <input type="email" name="email" placeholder="Apna registered email daalein" required>
                <button type="submit">Reset Link Bhejein</button>
            </form>
        <?php endif; ?>
        
        <a href="login.php">Login Page Par Wapas Jaaein</a>
    </div>
</body>
</html>