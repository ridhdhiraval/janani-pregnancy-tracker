<?php
session_start();

// CHECK LOGIN
if (!isset($_SESSION['admin_email'])) {
    header("Location: ../login.php");
    exit;
}

// INCLUDE SIDEBAR
include('../inc/sidebar.php');
include '../inc/config.php';
include '../inc/db.php';
// FETCH DASHBOARD STATS
$users   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM users"))['c'];
$babies  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM babies"))['c'];
$preg    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM pregnancies"))['c'];


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | Janani</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- ========== SWEETALERT2 CDN (ADDED) ========== -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- ICONS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: #f7f8fb;
        }

        .main {
            margin-left: 250px;
            padding: 40px 50px;
        }

        /* TOP BAR */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .welcome-admin {
            font-size: 22px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .welcome-admin i {
            font-size: 28px;
            color: #4C8BF5;
        }

        /* TOP RIGHT ICONS */
        .top-icons {
            display: flex;
            align-items: center;
            gap: 25px;
            font-size: 20px;
        }

        .top-icons i {
            cursor: pointer;
            transition: 0.2s;
        }

        .top-icons i:hover {
            transform: scale(1.2);
            color: #0047c6;
        }

        /* CARDS */
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .card {
            background: #fff;
            padding: 25px;
            border-radius: 14px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.07);
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .card i {
            color: #4C8BF5;
            font-size: 34px;
            margin-bottom: 10px;
        }

        .card i.fa-heart-pulse {
            color: #FF6B6B;
        }

        .card i.fa-baby {
            color: #FF9F43;
        }

        .card i.fa-user-doctor {
            color: #6A67CE;
        }

        .card i.fa-people-group {
            color: #1DD1A1;
        }


        .card .count {
            font-size: 32px;
            font-weight: 600;
        }

        .card .label {
            color: #777;
        }

        /* BOXES */
        .box {
            background: #fff;
            padding: 50px;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.07);
            margin-bottom: 35px;
            transition: 0.3s;
        }

        .box:hover {
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.12);
        }

        /* SIDE-BY-SIDE CHARTS */
        .small-box-wrapper {
            display: flex;
            gap: 25px;
            width: 100%;
            margin-bottom: 25px;
        }

        .small-box {
            background: #fff;
            flex: 1;
            padding: 35px;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.07);
            height: 320px;
            display: flex;
            flex-direction: column;
        }

        .small-box:hover {
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.12);
        }

        .small-box canvas {
            flex-grow: 1;
            max-height: 240px;
        }

        /* ⭐ CHANGE START: Profile Modal UI */
        .profile-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(3px);
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .profile-modal-content {
            width: 380px;
            background: #fff;
            padding: 25px;
            border-radius: 16px;
            animation: fadeIn 0.3s ease;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .closeModal {
            float: right;
            font-size: 22px;
            cursor: pointer;
        }

        .profile-header img.profile-avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            margin-bottom: 15px;
        }

        .profile-actions button {
            width: 100%;
            background: #4C8BF5;
            border: none;
            padding: 12px;
            margin-top: 12px;
            color: white;
            border-radius: 10px;
            cursor: pointer;
            font-size: 15px;
            transition: 0.2s;
        }

        .profile-actions button:hover {
            background: #2a6cd4;
        }

        /* ==================== LOGOUT MODAL STYLES (ADDED) ==================== */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.45);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 999999;
        }

        .modal-box {
            width: 350px;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            animation: popupScale 0.2s ease;
        }

        @keyframes popupScale {
            from {
                transform: scale(0.8);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .modal-actions {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }

        .btn-cancel {
            background: #e0e0e0;
            padding: 8px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn-logout {
            background: #e53935;
            color: #fff;
            padding: 8px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        /* ==================== END LOGOUT MODAL STYLES ==================== */


        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* ⭐ CHANGE END */


        @media(max-width: 900px) {
            .small-box-wrapper {
                flex-direction: column;
            }
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
        }

        th {
            background: #f1f3f7;
            text-align: left;
        }

        td {
            border-bottom: 1px solid #eee;
        }

        /* EXPERT CARDS */
        .expert-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
        }

        .expert {
            background: #ffeaf3;
            padding: 20px;
            border-radius: 14px;
            text-align: center;
            font-weight: 500;
            color: #ff4f9d;
            transition: 0.2s;
        }

        .expert:hover {
            transform: translateY(-2px);
            background: #ffd2e8;
        }
    </style>

</head>

<body>

    <div class="main">


        <!-- TOP BAR -->
        <div class="top-bar">
            <div class="welcome-admin">
                Welcome, Admin
                <i class="fa-solid fa-user-shield"></i>
            </div>

            <div class="top-icons">
                <i class="fa-solid fa-circle-user" id="profileBtn" style="cursor:pointer;"></i>
                </a>
                <i class="fa-solid fa-right-from-bracket" id="logoutBtn" style="cursor:pointer;"></i>
            </div>

        </div>

        <!-- DASHBOARD CARDS -->
        <div class="card-grid">
            <div class="card" style="border-left: 5px solid #4C8BF5;">
                <i class="fa-solid fa-users"></i>
                <div class="count">10</div>
                <div class="label">Total Users</div>
            </div>

            <div class="card" style="border-left: 5px solid #FF6B6B;">
                <i class="fa-solid fa-heart-pulse"></i>
                <div class="count">1</div>
                <div class="label">Pregnant Users</div>
            </div>

            <div class="card" style="border-left: 5px solid #FF9F43;">
                <i class="fa-solid fa-baby"></i>
                <div class="count">2</div>
                <div class="label">Postpartum Users</div>
            </div>

            <div class="card" style="border-left: 5px solid #6A67CE;">
                <i class="fa-solid fa-user-doctor"></i>
                <div class="count">2</div>
                <div class="label">Doctors</div>
            </div>

            <div class="card" style="border-left: 5px solid #1DD1A1;">
                <i class="fa-solid fa-people-group"></i>
                <div class="count">5</div>
                <div class="label">Family Members</div>
            </div>
        </div>


        <!-- USER GROWTH GRAPH -->
        <div class="box">
            <h3>User Growth (Last 10 Days)</h3>
            <canvas id="userGrowth"></canvas>
        </div>


        <!-- USER TABLE -->
        <div class="box">
            <h3>Pregnant & Postpartum Users</h3>

            <table>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Trimester</th>
                    <th>Due Date</th>
                </tr>
                <tr>
                    <td>Pinal</td>
                    <td>pinal0108@gmail.com</td>
                    <td>2nd</td>
                    <td>05 Nov 2025</td>
                </tr>
                <tr>
                    <td>Komal Mishra</td>
                    <td>komal.mishra@gmail.com</td>
                    <td>3rd</td>
                    <td>10 Nov 2025</td>

                </tr>
                <tr>
                    <td>Rajvi</td>
                    <td>rajvi1121@gmail.com</td>
                    <td>2nd</td>
                    <td>2 Nov 2025</td>

                </tr>

            </table>
        </div>


        <!-- PIE + LINE GRAPH -->
        <div class="small-box-wrapper">

            <div class="small-box">
                <h3>Trimester Distribution</h3>
                <canvas id="trimesterPie"></canvas>
            </div>

            <div class="small-box">
                <h3>Daily Kick Count Alerts</h3>
                <canvas id="kickCountChart"></canvas>
            </div>

        </div>


        <!-- EXPERTS -->
        <div class="box">
            <h3>Experts</h3>

            <div class="expert-grid">
                <div class="expert">Hensy Patel</div>
                <div class="expert">Diya Tandel</div>
                <div class="expert">Ridhdhi Raval</div>
                <div class="expert">Komal Mishra</div>
                <div class="expert">Anandi Hudad</div>
            </div>
        </div>


        <!-- RECENT ACTIVITY -->
        <div class="box">
            <h3>Recent Activity</h3>
            <ul>
                <li>User Pinal updated kick count</li>
                <li>New doctor registered</li>
                <li>New postpartum user added</li>
            </ul>

            <canvas id="typeBar" height="140"></canvas>
        </div>

    </div>
    <!-- ⭐ PROFILE MODAL (Updated to show actual admin info) -->
    <div id="profileModal" class="profile-modal">
        <div class="profile-modal-content">
            <span class="closeModal">&times;</span>

            <div class="profile-header">
                <img src="../assets/images/avatar.png" class="profile-avatar">
            </div>

            <div class="profile-actions">
                <button onclick="window.location='account_info.php'">
                    <i class="fa-solid fa-user-gear"></i> View Full Profile
                </button>
                <button onclick="window.location='platform_config.php'">
                    <i class="fa-solid fa-gear"></i> Settings
                </button>
                <button onclick="window.location='../logout.php'">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </button>
            </div>
        </div>
    </div>
    <!-- ⭐ CHANGE END -->
    <!-- ==================== LOGOUT CONFIRMATION MODAL (ADDED) ==================== -->
    <div id="logoutModal" class="modal-overlay" style="display: none;">
        <div class="modal-box">
            <h3>Confirm Logout</h3>
            <p>Are you sure you want to logout?</p>

            <div class="modal-actions">
                <button id="cancelLogout" class="btn-cancel">Cancel</button>
                <button id="confirmLogout" class="btn-logout">Yes, Logout</button>
            </div>
        </div>
    </div>
    <!-- ==================== END LOGOUT CONFIRMATION MODAL ==================== -->





    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // ⭐ ANIMATED USER GROWTH BAR CHART WITH ZERO LINE
        const ctx = document.getElementById('userGrowth');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [
                    "Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"
                ],
                datasets: [{
                    label: "Growth (%)",
                    data: [
                        5, 12, -3, 8, -6, 10, 14, -2, 6, -4, 9, 11,
                        15, 18, -5, 12, -3, 20, 25, -8, 14, 10, -6, 22
                    ],
                    backgroundColor: function(context) {
                        return context.raw >= 0 ? "#1DD1A1" : "#FF6B6B";
                    },
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                animation: {
                    duration: 1800,
                    easing: 'easeOutQuart'
                },
                scales: {
                    y: {
                        title: {
                            display: true,
                            text: "Growth (%)"
                        },
                        grid: {
                            color: "#ddd"
                        },
                        beginAtZero: true
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });


        // ⭐ ICONS INSIDE TRIMESTER PIE CHART
        const trimesterIcons = new Image();
        trimesterIcons.src = "https://cdn-icons-png.flaticon.com/512/2965/2965870.png";

        new Chart(document.getElementById('trimesterPie'), {
            type: 'doughnut',
            data: {
                labels: ["Trimester 1", "Trimester 2", "Trimester 3"],
                datasets: [{
                    data: [30, 50, 20],
                    backgroundColor: ["#FF9F43", "#FF6B6B", "#FF85C2"],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                animation: {
                    duration: 1500
                },
                plugins: {
                    legend: {
                        position: "bottom"
                    }
                }
            }
        });


        // ⭐ SMOOTH LINE CHART (KICK COUNT)
        new Chart(document.getElementById('kickCountChart'), {
            type: 'line',
            data: {
                labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
                datasets: [{
                    label: "Alerts",
                    data: [5, 10, 7, 12, 15, 18, 10],
                    borderColor: "#6A67CE",
                    tension: 0.4,
                    fill: true,
                    backgroundColor: "rgba(106,103,206,0.2)"
                }]
            },
            options: {
                responsive: true,
                animation: {
                    duration: 1400
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });


        // ⭐ HORIZONTAL BAR (Recent Activity Counts)
        new Chart(document.getElementById("typeBar"), {
            type: "bar",
            data: {
                labels: ["Pregnant", "Postpartum", "Doctors", "Family"],
                datasets: [{
                    label: "Counts",
                    data: [530, 210, 45, 720],
                    backgroundColor: ["#ff4f9d", "#ff77b7", "#ff6fa9", "#ff8ec0"],
                    borderRadius: 8
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                animation: {
                    duration: 1300
                }
            }
        });
        // ⭐ CHANGE START: Profile Modal Script
        const profileBtn = document.getElementById("profileBtn");
        const profileModal = document.getElementById("profileModal");
        const closeModal = document.querySelector(".closeModal");

        profileBtn.onclick = () => {
            profileModal.style.display = "flex";
        };

        closeModal.onclick = () => {
            profileModal.style.display = "none";
        };

        window.onclick = (e) => {
            if (e.target === profileModal) {
                profileModal.style.display = "none";
            }
        };
        // ⭐ CHANGE END
        // ========== SWEETALERT LOGOUT CONFIRMATION (ADDED) ==========
        document.getElementById("logoutBtn").addEventListener("click", function(e) {
            e.preventDefault(); // stop default logout

            Swal.fire({
                title: "Are you sure?",
                text: "You will be logged out from your admin panel.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, Logout",
                cancelButtonText: "Cancel",
                backdrop: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "../logout.php"; // your logout file
                }
            });
        });
    </script>
</body>

</html>