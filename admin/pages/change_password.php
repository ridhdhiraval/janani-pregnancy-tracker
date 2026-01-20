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
    <title>Change Password | Janani Admin</title>
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
            max-width: 600px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            font-weight: 500;
            display: block;
            margin-bottom: 6px;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }

        .requirements {
            font-size: 13px;
            color: #555;
            margin-top: -10px;
            margin-bottom: 15px;
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

        .cancel-btn {
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

        <div class="page-title">Change Password</div>
        <div class="page-sub">Update your admin panel password securely.</div>

        <div class="info-box">

            <div class="form-group">
                <label>Current Password</label>
                <input type="password" id="currentPass" placeholder="Enter current password">
            </div>

            <div class="form-group">
                <label>New Password</label>
                <input type="password" id="newPass" placeholder="Enter new password">
            </div>

            <div class="form-group">
                <label>Confirm New Password</label>
                <input type="password" id="confirmPass" placeholder="Confirm new password">
            </div>

            <div class="requirements">
                <strong>Password Requirements:</strong><br>
                • Minimum 8 characters<br>
                • At least one uppercase letter<br>
                • At least one digit<br>
                • At least one special character
            </div>

            <button class="save-btn" onclick="updatePassword()">Update Password</button>
            <button class="cancel-btn" onclick="cancelChanges()">Cancel</button>

        </div>

    </div>

<script>

// 🔐 Password validation function
function validatePassword(p) {
    return (
        p.length >= 8 &&
        /[A-Z]/.test(p) &&
        /\d/.test(p) &&
        /[\W]/.test(p)
    );
}

// ⭐ Update Password Button
function updatePassword() {

    let current = document.getElementById("currentPass").value;
    let newp = document.getElementById("newPass").value;
    let conf = document.getElementById("confirmPass").value;

    if (current.trim() === "") {
        return Swal.fire("Error", "Please enter your current password.", "error");
    }

    if (!validatePassword(newp)) {
        return Swal.fire("Weak Password", "Your new password does not meet the requirements.", "warning");
    }

    if (newp !== conf) {
        return Swal.fire("Mismatch", "New password and confirm password do not match.", "error");
    }

    Swal.fire({
        icon: "success",
        title: "Password Updated",
        text: "Your password has been successfully changed.",
        confirmButtonColor: "#1DD1A1"
    });

}

// ❌ Cancel Button
function cancelChanges() {
    Swal.fire({
        title: "Cancel Changes?",
        text: "All entered data will be cleared.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, Clear",
        confirmButtonColor: "#d33"
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById("currentPass").value = "";
            document.getElementById("newPass").value = "";
            document.getElementById("confirmPass").value = "";

            Swal.fire("Cleared", "All fields are reset.", "info");
        }
    });
}

</script>

</body>
</html>
