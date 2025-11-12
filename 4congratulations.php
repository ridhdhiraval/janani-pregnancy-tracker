<?php
require_once __DIR__ . '/lib/auth.php';
require_once __DIR__ . '/config/db.php';
start_secure_session();

$pregnancy_id = isset($_GET['pregnancy_id']) ? (int)$_GET['pregnancy_id'] : null;
$edd = null;
if ($pregnancy_id) {
    $stmt = $pdo->prepare('SELECT edd, user_id FROM pregnancies WHERE id = ? LIMIT 1');
    $stmt->execute([$pregnancy_id]);
    $p = $stmt->fetch();
    if ($p) {
        // if not logged in, save redirect and go to login
        if (empty($_SESSION['user_id'])) {
            // remember where to return after login
            $_SESSION['after_login_redirect'] = '/JANANI/4congratulations.php?pregnancy_id=' . $pregnancy_id;
            header('Location: /JANANI/1signinsignup.php');
            exit;
        }
        // ensure ownership
        if ($p['user_id'] != ($_SESSION['user_id'] ?? null)) {
            // not allowed
            header('Location: /JANANI/1signinsignup.php');
            exit;
        }
        $edd = $p['edd'];
    }
}

// fallback to a direct GET param due_date (legacy)
if (!$edd && !empty($_GET['due_date'])) {
    $edd = $_GET['due_date'];
}

// default if still not present
if (!$edd) {
    $edd = date('Y-m-d', strtotime('+39 weeks'));
}

// compute metrics
$today = new DateTimeImmutable('today');
$dueDate = DateTimeImmutable::createFromFormat('Y-m-d', $edd);
if (!$dueDate) {
    $dueDate = new DateTimeImmutable('today');
}

$interval = $today->diff($dueDate);
$days_to_go = (int)$interval->format('%r%a');
// Estimated total days of pregnancy (approx 280)
$total_days = 280;
$days_done = max(0, $total_days - max(0, $days_to_go));
$percent_done = round(($days_done / $total_days) * 100);
$weeks_done = floor($days_done / 7);
$current_week = "Week " . max(0, $weeks_done);

$last_period_date = $edd; // showing EDD to user; you can adjust as needed
$dashboard_url = "5index.php?date=" . urlencode($last_period_date);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Congratulations!</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        /* General Setup */
        body {
            font-family: Arial, sans-serif;
            background-color: #ffeef2; /* Soft pink background */
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            padding: 30px 10px;
            margin: 0;
            min-height: 100vh;
            color: #333;
        }
        .container {
            width: 100%;
            max-width: 450px;
            text-align: center;
        }

        /* Header (Congratulations) */
        .congratulations-header {
            font-size: 26px;
            font-weight: bold;
            color: #333;
            margin: 20px 0 5px;
            position: relative;
        }
        .congratulations-header::before,
        .congratulations-header::after {
            content: '✨'; /* Sparkle emoji */
            font-size: 18px;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
        }
        .congratulations-header::before { left: -25px; }
        .congratulations-header::after { right: -25px; }
        .congratulations-subtitle {
            font-size: 14px;
            color: #888;
            margin-bottom: 30px;
        }

        /* Main White Card */
        .main-card {
            background-color: white;
            padding: 40px 20px;
            border-radius: 25px;
            box-shadow: 0 8px 20px rgba(255, 133, 162, 0.1);
            margin-bottom: 20px;
        }

        /* Pregnancy Progress Circle */
        .progress-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 30px;
            position: relative;
            padding: 0 50px; /* Creates space for absolute side-data */
        }
        .progress-circle {
            width: 150px;
            height: 150px;
            border: 5px solid #ffdddd; /* Light pink border for the ring */
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #ff69b4; /* Pink text color */
            flex-shrink: 0; 
        }
        .progress-week {
            font-size: 36px;
            font-weight: bold;
        }
        .progress-days {
            font-size: 14px;
            color: #888;
        }
        .progress-side-data {
            position: absolute;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
        }
        
        .progress-percent {
            left: 0; /* Place it at the start of the padded area */
            color: #ff69b4;
        }
        .progress-days-to-go {
            right: 0; /* Place it at the end of the padded area */
            color: #555;
        }

        /* Last Period Info */
        .last-period-info {
            font-size: 14px;
            color: #555;
            margin-bottom: 10px;
        }
        .last-period-date {
            font-weight: bold;
            color: #ff69b4;
            padding: 5px 10px;
            background-color: #ffe8ed;
            border-radius: 5px;
            display: inline-block; /* To contain the background and padding */
        }
        .last-period-info a {
            text-decoration: none;
            color: inherit;
            margin-left: 5px;
            cursor: pointer;
        }
        .last-period-info i {
            font-size: 12px;
            color: #ff69b4;
        }

        /* Cycle Info Box */
        .cycle-info-box {
            background-color: #f7f7f7; /* Very light gray/beige */
            padding: 10px;
            border-radius: 8px;
            font-size: 14px;
            color: #888;
            margin-top: 15px;
        }

        /* Continue Button */
        .continue-button {
            width: 100%;
            max-width: 450px;
            padding: 18px 0;
            margin-top: 30px; 
            background-color: #ff69b4; /* Main pink button color */
            color: white;
            font-size: 18px;
            font-weight: bold;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(255, 105, 180, 0.3);
            transition: background-color 0.2s;
            /* Make link look like a button */
            text-decoration: none; 
            display: inline-block;
            text-align: center;
        }
        .continue-button:hover {
            background-color: #ff4d9a;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="congratulations-header">Congratulations!</div>
    <div class="congratulations-subtitle">on your pregnancy journey</div>

    <div class="main-card">
        <div class="progress-container">
            <div class="progress-side-data progress-percent">
                <?php echo htmlspecialchars($percent_done) . "%"; ?><br>DONE
            </div>
            
            <div class="progress-circle">
                <div class="progress-week"><?php echo htmlspecialchars($current_week); ?></div>
                <div class="progress-days">+0 Days</div>
            </div>

            <div class="progress-side-data progress-days-to-go">
                <?php echo htmlspecialchars($days_to_go); ?><br>DAYS TO GO
            </div>
        </div>

        <div class="last-period-info">
            Last Period: 
            <span class="last-period-date">
                <?php echo htmlspecialchars($last_period_date); ?>
                <a href="3select_last_period.php" title="Edit Date"><i class="fas fa-pencil-alt"></i></a> 
            </span>
        </div>

        <div class="cycle-info-box">
            We calculate using a 28-day menstrual cycle
        </div>
    </div>

    <a href="<?php echo htmlspecialchars($dashboard_url); ?>" class="continue-button">Continue to Dashboard</a>
</div>

</body>
</html>