<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: ../login.php");
    exit;
}
include('../inc/sidebar.php');

// DEFAULT ADMIN INFO (Front-end only for now)
$full_name     = "Hensy Patel";
$admin_role    = "Super Admin";
$email         = "admin@jananiapp.com";
$phone         = "+91 9876543210";
$joining_date  = "01 Jan 2024";
$last_login    = "28 Nov 2025";
$profile_img   = "../uploads/admin_profile.jpg";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Account Information | Janani Panel</title>
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

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #ddd;
            display: block;
            margin-bottom: 15px;
        }

        /* Buttons */
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

        <div class="page-title">Admin Account Information</div>
        <div class="page-sub">View and update your personal admin profile details.</div>

        <!-- PROFILE IMAGE -->
        <div class="info-box">

            <img src="../assets/images/avatar.png" class="profile-avatar">

        <!-- PROFILE DETAILS -->
        <div class="profile-header">
        </div>
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" id="name" value="<?php echo $full_name; ?>">
        </div>

        <div class="form-group">
            <label>Admin Role</label>
            <select id="role">
                <option <?php if ($admin_role == "Super Admin") echo "selected"; ?>>Super Admin</option>
                <option <?php if ($admin_role == "Editor") echo "selected"; ?>>Editor</option>
                <option <?php if ($admin_role == "Manager") echo "selected"; ?>>Manager</option>
            </select>
        </div>

        <div class="form-group">
            <label>Email Address</label>
            <input type="email" id="email" value="<?php echo $email; ?>">
        </div>

        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" id="phone" value="<?php echo $phone; ?>">
        </div>

        <div class="form-group">
            <label>Joining Date</label>
            <input type="text" value="<?php echo $joining_date; ?>" disabled>
        </div>

        <div class="form-group">
            <label>Last Login Date</label>
            <input type="text" value="<?php echo $last_login; ?>" disabled>
        </div>
    </div>

    <!-- ACTION BUTTONS -->
    <button class="save-btn" onclick="saveChanges()">Save Changes</button>
    <button class="reset-btn" onclick="resetForm()">Reset</button>

    </div>

    <script>
        // LIVE PROFILE IMAGE PREVIEW
        document.getElementById("profileInput").onchange = function() {
            const img = document.getElementById("preview");
            img.src = URL.createObjectURL(this.files[0]);
        };

        // SAVE CHANGES BUTTON
        function saveChanges() {
            Swal.fire({
                icon: "success",
                title: "Profile Updated!",
                text: "Your admin profile changes have been saved.",
                confirmButtonColor: "#1DD1A1"
            });
        }

        // RESET BUTTON – RESTORE ORIGINAL VALUES
        function resetForm() {
            Swal.fire({
                title: "Reset Changes?",
                text: "All unsaved changes will be removed.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, Reset",
                confirmButtonColor: "#d33",
            }).then((result) => {
                if (result.isConfirmed) {
                    // Reset fields to default PHP values
                    document.getElementById("name").value = "<?php echo $full_name; ?>";
                    document.getElementById("role").value = "<?php echo $admin_role; ?>";
                    document.getElementById("email").value = "<?php echo $email; ?>";
                    document.getElementById("phone").value = "<?php echo $phone; ?>";
                    document.getElementById("preview").src = "<?php echo $profile_img; ?>";

                    Swal.fire({
                        icon: "info",
                        title: "Reset Successful",
                        text: "All fields restored to original values."
                    });
                }
            });
        }
    </script>

</body>

</html>