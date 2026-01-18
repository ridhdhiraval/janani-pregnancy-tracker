<?php
// Include your database connection
require_once '../config/db.php'; // Make sure $pdo is defined here

// ------------------------
// Fetch dynamic values
// ------------------------

// 1. Upcoming Appointments (today) only
$stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE appt_date = CURDATE()");
$stmt->execute();
$upcoming_appointments = $stmt->fetchColumn();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Doctor Panel</title>

  <!-- FontAwesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    :root {
      --pink-100: #fde6eb;
      --pink-200: #f8d7de;
      --pink-300: #f6c6cd;
      --accent: #ec8fa0;
      --muted: #9b9b9b;
      --text-color: #3f3f3f;
      --background-color: #ffffff;
      --card-shadow: 0 6px 20px rgba(236,80,120,0.1);
      --transition: all 0.3s ease;
      font-family: 'Inter', Arial, sans-serif;
    }

    * { box-sizing: border-box; }
    body { margin: 0; background: var(--background-color); color: var(--text-color); line-height: 1.6; }
    h1, h2 { color: var(--accent); font-weight: 700; margin-bottom: 20px; }
    h1 { font-size: clamp(2rem, 5vw, 3rem); }

    .navbar {
      background: var(--pink-300);
      padding: 6px 18px;
      height: 60px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      position: sticky;
      top: 0;
      z-index: 1000;
    }
    .navbar a { color: var(--text-color); text-decoration: none; font-size: 20px; }
    .navbar i { font-size: 22px; cursor: pointer; transition: var(--transition); }
    .navbar i:hover { color: var(--accent); }
    .left-icons, .right-icons { display: flex; align-items: center; gap: 24px; }

    .top-hero { background: linear-gradient(135deg, var(--pink-100), var(--pink-200)); padding: 50px 40px 80px; border-bottom-left-radius: 40px; border-bottom-right-radius: 40px; }
    .container { max-width: 1200px; margin: auto; padding: 0 20px; }
    .top-row { display: flex; justify-content: center; align-items: center; } /* centered since only one item */
    .big { font-size: clamp(2.5rem, 6vw, 3.5rem); font-weight: 800; color: var(--accent); }
    .sub { text-transform: uppercase; font-weight: 700; color: #d17a8c; letter-spacing: 1.5px; display: block; margin-bottom: 5px; }
    .circle { width: 200px; height: 200px; border-radius: 50%; background: var(--background-color); border: 10px solid rgba(236,80,120,0.15); display: flex; align-items: center; justify-content: center; margin: auto; box-shadow: 0 0 0 10px var(--pink-100); }
    .week { font-size: 72px; color: var(--accent); font-weight: 800; text-align: center; }
    .label { font-weight: 600; color: #d17a8c; text-align: center; margin-top: 10px; font-size: 14px; }
    .trimester { text-align: center; font-weight: 700; font-size: 18px; color: var(--accent); margin-top: 20px; text-decoration: none; cursor: pointer; }
    .trimester:hover { text-decoration: underline; }

    .icons-row { margin-top: 50px; display: flex; justify-content: center; gap: 5vw; flex-wrap: wrap; }
    .icon-wrapper { text-decoration: none; color: inherit; display: block; transition: transform 0.3s ease; }
    .icon-wrapper:hover { transform: translateY(-5px); }
    .icon-circle { width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(180deg, var(--background-color), var(--pink-100)); border: 2px solid rgba(236,80,120,0.15); display: flex; align-items: center; justify-content: center; }
    .icon-circle i { font-size: 32px; color: var(--accent); }
    .icon-label { margin-top: 10px; text-align: center; font-weight: 600; color: var(--text-color); font-size: 14px; }

    .tools { padding: 50px 0; }
    .card { background: var(--background-color); padding: 24px; border-radius: 16px; box-shadow: var(--card-shadow); display: flex; gap: 20px; align-items: center; margin-bottom: 20px; text-decoration: none; color: inherit; transition: var(--transition); border: 1px solid transparent; }
    .card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(236,80,120,0.15); border-color: var(--accent); }
    .card-icon { width: 60px; height: 60px; border-radius: 12px; background: var(--pink-200); display: flex; align-items: center; justify-content: center; font-size: 28px; color: #c86a7e; }
    .action { font-weight: 700; color: var(--accent); margin-left: auto; font-size: 24px; }

    footer { background: var(--pink-300); margin-top: 60px; padding: 50px 0; border-top-left-radius: 24px; border-top-right-radius: 24px; }
    footer .grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 30px; }
    footer button { padding:10px 14px; border-radius:8px; background:white; border:1px solid #bbb; font-weight:700; }

    @media (max-width: 768px) {
        .top-row { flex-direction: column; text-align: center; }
        .circle { margin-top: 30px; width: 150px; height: 150px; border-width: 6px; }
        .week { font-size: 48px; }
        .icons-row { gap: 20px 5vw; }
        footer .grid { grid-template-columns: 1fr 1fr; gap: 40px; }
    }
    
    @media (max-width: 480px) {
        .top-hero, .tools { padding-left: 20px; padding-right: 20px; }
        footer .grid { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
  <div class="left-icons">
    <a href="doc_dashboard.php"><i class="fa-solid fa-house"></i></a>
    <a href="doc_patients.php"><i class="fa-solid fa-user-doctor"></i></a>
    <a href="doc_appointments.php"><i class="fa-solid fa-calendar-check"></i></a>
  </div>

  <div class="right-icons">
    <!-- Notification icon removed as requested -->
    <a href="doc_settings.php"><i class="fa-solid fa-gear"></i></a>
    <a href="doc_logout.php"><i class="fa-solid fa-right-from-bracket"></i></a>
  </div>
</div>

<!-- HERO -->
<div class="top-hero">
  <div class="container">
    <div class="top-row">
      <!-- Only Upcoming Appointments shown in center -->
      <a href="doc_appointments.php" style="text-decoration:none;">
        <div>
          <div class="circle">
            <div>
              <div class="week"><?= $upcoming_appointments ?></div>
              <div class="label">Appointments</div>
            </div>
          </div>
          <div class="trimester">Today's Overview</div>
        </div>
      </a>
    </div>

    <!-- ICON ROW -->
    <div class="icons-row">
      <a href="doc_patients.php" style="text-decoration:none; color:inherit; text-align:center;">
        <div class="icon-circle"><i class="fa-solid fa-users"></i></div>
        <div class="icon-label">Patients</div>
      </a>

      <a href="doc_reports.php" style="text-decoration:none; color:inherit; text-align:center;">
        <div class="icon-circle"><i class="fa-solid fa-file-medical"></i></div>
        <div class="icon-label">Reports</div>
      </a>

      <a href="doc_prescriptions.php" style="text-decoration:none; color:inherit; text-align:center;">
        <div class="icon-circle"><i class="fa-solid fa-prescription-bottle-medical"></i></div>
        <div class="icon-label">Prescriptions</div>
      </a>
    </div>
  </div>
</div>

<!-- CARDS -->
<div class="container tools">
  <a href="doc_appointments.php" class="card">
    <div class="card-icon"><i class="fa-solid fa-calendar"></i></div>
    <div class="content">
      <div style="font-weight:700;">Upcoming Appointments</div>
      <div style="color:var(--muted); margin-top:6px;">Manage today’s patient schedule.</div>
    </div>
    <div class="action">Open</div>
  </a>

  <a href="doc_reports.php" class="card">
    <div class="card-icon"><i class="fa-solid fa-folder-open"></i></div>
    <div class="content">
      <div style="font-weight:700;">Patient Reports</div>
      <div style="color:var(--muted); margin-top:6px;">View medical files, visit logs & notes.</div>
    </div>
    <div class="action">Open</div>
  </a>

  <!-- Alerts card removed as requested -->
</div>

<!-- FOOTER -->
<footer>
  <div class="container">
    <div class="grid">
      <div>
        <h2>Doctor Panel</h2>
      </div>
      <div>
        <strong>Useful Links</strong>
        <div>Privacy Policy</div>
        <div>Support</div>
        <div>Contact</div>
      </div>
     
    </div>
  </div>
</footer>

</body>
</html>
