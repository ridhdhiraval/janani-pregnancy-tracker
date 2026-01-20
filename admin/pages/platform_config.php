<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: ../login.php");
    exit;
}
include('../inc/sidebar.php');
include '../inc/config.php';
include '../inc/db.php';


// DEFAULT VALUES (replace with DB values later)
$app_name          = "Janani – Pregnancy Care App";
$support_email     = "support@jananiapp.com";
$contact_email     = "contact@jananiapp.com";
$helpline          = "+91 108";  // Govt emergency style number
$default_country   = "India";
$default_timezone  = "Asia/Kolkata (GMT +5:30)";
$date_format       = "DD/MM/YYYY";
$logo_path         = "../uploads/logo.png";      // Update path as per your system
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Platform Configuration | Janani Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            margin: 0;
            background: #f7f8fb;
            font-family: 'Poppins', sans-serif;
        }

        .main {
            margin-left: 250px;
            padding: 40px 50px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 600;
        }

        .page-sub {
            font-size: 15px;
            color: #777;
            margin-bottom: 25px;
        }

        .info-box {
            background: #fff;
            padding: 25px;
            border-radius: 14px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            font-weight: 500;
            display: block;
            margin-bottom: 6px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }

        /* Show current logo */
        .preview-img {
            height: 60px;
            margin-top: 10px;
            border-radius: 8px;
        }

        .save-btn {
            padding: 12px 20px;
            background: #1DD1A1;
            border-radius: 10px;
            font-size: 14px;
            color: #fff;
            cursor: pointer;
            border: none;
        }

        .reset-btn {
            padding: 12px 20px;
            background: #777;
            border-radius: 10px;
            font-size: 14px;
            color: #fff;
            cursor: pointer;
            border: none;
            margin-left: 10px;
        }
    </style>
</head>

<body>

    <div class="main">

        <div class="page-title">Platform Configuration</div>
        <div class="page-sub">Manage branding, system preferences, regional settings, and platform-wide configuration for the Janani system.</div>

        <!-- ===========================
              1) APP BRANDING
        ============================ -->
        <div class="info-box">
            <div class="section-title">App Branding</div>

            <div class="form-group">
                <label>App Name</label>
                <input type="text" value="<?php echo $app_name; ?>">
            </div>

            <div class="form-group">
                <label>Current Logo</label>
                <img src="<?php echo $logo_path; ?>" class="preview-img">
                <input type="file">
            </div>

        </div>

        <!-- ===========================
              2) CONTACT INFORMATION
        ============================ -->
        <div class="info-box">
            <div class="section-title">Contact Information</div>

            <div class="form-group">
                <label>Support Email</label>
                <input type="email" value="<?php echo $support_email; ?>">
            </div>

            <div class="form-group">
                <label>Contact Email</label>
                <input type="email" value="<?php echo $contact_email; ?>">
            </div>

            <div class="form-group">
                <label>Emergency Helpline Number</label>
                <input type="text" value="<?php echo $helpline; ?>">
            </div>
        </div>

        <!-- ===========================
              3) REGIONAL SETTINGS
        ============================ -->
        <div class="info-box">
            <div class="section-title">Regional Settings</div>

            <div class="form-group">
                <label>Default Country</label>
                <select>
                    <option <?php if ($default_country == "India") echo "selected"; ?>>India</option>
                    <option <?php if ($default_country == "Nepal") echo "selected"; ?>>Nepal</option>
                    <option <?php if ($default_country == "Bangladesh") echo "selected"; ?>>Bangladesh</option>
                </select>
            </div>

            <div class="form-group">
                <label>Default Timezone</label>
                <select>
                    <option <?php if ($default_timezone == "Asia/Kolkata (GMT +5:30)") echo "selected"; ?>>Asia/Kolkata (GMT +5:30)</option>
                    <option <?php if ($default_timezone == "Asia/Dhaka (GMT +6)") echo "selected"; ?>>Asia/Dhaka (GMT +6)</option>
                </select>
            </div>

            <div class="form-group">
                <label>Date Format</label>
                <select>
                    <option <?php if ($date_format == "DD/MM/YYYY") echo "selected"; ?>>DD/MM/YYYY</option>
                    <option <?php if ($date_format == "MM/DD/YYYY") echo "selected"; ?>>MM/DD/YYYY</option>
                </select>
            </div>
        </div>

        <!-- BUTTONS -->
        <button class="save-btn">Save Settings</button>
        <button class="reset-btn">Reset</button>

    </div>
    <script>
        // --------------------------
        // 1) SAVE SETTINGS BUTTON
        // --------------------------
        document.querySelector(".save-btn").addEventListener("click", function() {
            Swal.fire({
                title: "Save Settings?",
                text: "Your platform configuration will be updated.",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Save",
                cancelButtonText: "Cancel",
                confirmButtonColor: "#1DD1A1"
            }).then(result => {
                if (result.isConfirmed) {
                    Swal.fire("Saved!", "Settings have been updated.", "success");
                }
            });
        });


        // --------------------------
        // 2) RESET BUTTON
        // --------------------------
        document.querySelector(".reset-btn").addEventListener("click", function() {
            Swal.fire({
                title: "Reset All?",
                text: "All changes will be cleared and reset to default.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Reset",
                cancelButtonText: "Cancel",
                confirmButtonColor: "#ff4d4d"
            }).then(result => {
                if (result.isConfirmed) {
                    location.reload(); // Reset page
                }
            });
        });


        // --------------------------
        // 3) LOGO PREVIEW (File Upload)
        // --------------------------
        const fileInput = document.querySelector("input[type='file']");
        const previewImage = document.querySelector(".preview-img");

        fileInput.addEventListener("change", function() {
            const file = this.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result; // Show preview
            };

            reader.readAsDataURL(file);
        });
    </script>


</body>

</html>