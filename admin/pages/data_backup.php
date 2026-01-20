<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: ../login.php");
    exit;
}
include('../inc/sidebar.php');
include '../inc/config.php';
include '../inc/db.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Data & Backup Management | Janani Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body { margin: 0; background: #f7f8fb; font-family: 'Poppins', sans-serif; }
        .main { margin-left: 250px; padding: 40px 50px; }
        .page-title { font-size: 28px; font-weight: 600; }
        .page-sub { font-size: 15px; color: #777; margin-bottom: 25px; }

        .info-box { 
            background: #fff; padding: 25px; border-radius: 14px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); 
            margin-bottom: 30px; 
        }

        .section-title { font-size: 18px; font-weight: 600; margin-bottom: 12px; }

        .download-btn {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 12px 18px; margin: 10px 10px 0 0; border-radius: 10px;
            font-size: 14px; color: #fff; cursor: pointer; border: none;
            text-decoration: none; transition: 0.3s;
        }

        .download-btn i { margin-right: 8px; }
        .user-data { background: #1DD1A1; }
        .doctor-data { background: #4CAF50; }
        .appointments-data { background: #57dafe; }

        .download-btn:hover { opacity: 0.85; transform: translateY(-2px); }

        .card { 
            background: #fff; padding: 25px; border-radius: 14px; 
            box-shadow: 0 6px 20px rgba(0,0,0,0.06); 
            display: flex; align-items: center; 
            justify-content: space-between; margin-bottom: 20px;
        }

        .card h4 { margin: 0; font-size: 16px; font-weight: 500; }
    </style>
</head>

<body>
    <div class="main">
        <div class="page-title">Data & Backup Management</div>
        <div class="page-sub">Control data exports, backups, and system logs for secure administration.</div>

        <!-- Downloadable Data Section -->
        <div class="info-box">
            <div class="section-title">Downloadable Data</div>

            <div class="card">
                <h4>User Data</h4>
                <button onclick="downloadData('users')" class="download-btn user-data">
                    <i class="fa-solid fa-download"></i> Download
                </button>
            </div>

            <div class="card">
                <h4>Doctor Data</h4>
                <button onclick="downloadData('doctors')" class="download-btn doctor-data">
                    <i class="fa-solid fa-download"></i> Download
                </button>
            </div>

            <div class="card">
                <h4>Appointments Data</h4>
                <button onclick="downloadData('appointments')" class="download-btn appointments-data">
                    <i class="fa-solid fa-download"></i> Download
                </button>
            </div>
        </div>
    </div>

<script>
function downloadData(type) {
    Swal.fire({
        title: `Preparing ${type.charAt(0).toUpperCase() + type.slice(1)}...`,
        text: "Please wait a moment",
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    setTimeout(() => {
        window.location.href = `download.php?type=${type}`;
        Swal.close();
    }, 1000);
}
</script>

</body>
</html>
