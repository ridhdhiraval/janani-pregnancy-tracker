<?php
// Replace with real doctor data from DB
$doctor = [
    "name" => "Dr. Priya Sharma",
    "specialization" => "Gynecologist & Obstetrician",
    "email" => "priya.sharma@example.com",
    "phone" => "+91 98765 43210",
    "experience" => "12 Years",
    "hospital" => "Janani Women’s Care Hospital",
    "hospital_address" => "101, Orchid Tower, Near City Center, Anytown, 360005",
    "bio" => "Experienced gynecologist specializing in prenatal care, high-risk pregnancies, and women’s wellness. Dedicated to providing personalized and compassionate care.",
    "photo_url" => "https://randomuser.me/api/portraits/women/44.jpg",
    "average_rating" => 4.8,
    "review_count" => 145,
    "working_hours" => [
        "Mon - Fri" => "9:00 AM - 1:00 PM | 4:00 PM - 7:00 PM",
        "Sat" => "9:00 AM - 1:00 PM (by appointment)",
        "Sun" => "Closed"
    ]
];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Doctor Profile - <?= $doctor["name"] ?></title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
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
    --transition: 0.3s ease;
    font-family: 'Inter', Arial, sans-serif;
}

body {
    margin: 0;
    background: var(--pink-100); /* Slightly off-white background for depth */
    color: var(--text-color);
}

/* Navbar */
.navbar {
    background: var(--pink-300);
    padding: 6px 18px;
    height: 60px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.navbar a {
    color: var(--text-color);
    text-decoration: none;
    font-size: 20px;
    transition: var(--transition);
    padding: 8px;
    border-radius: 8px;
}

.navbar i:hover {
    color: var(--accent);
}

.left-icons, .right-icons {
    display: flex;
    gap: 20px;
}

/* MAIN CONTAINER */
.container {
    max-width: 1100px;
    margin: auto;
    padding: 40px 20px;
}

/* Header */
.profile-header {
    text-align: center;
    margin-bottom: 40px;
}

.profile-header h1 {
    color: var(--accent);
    font-size: 38px;
    margin: 10px 0;
    font-weight: 800;
}

/* Profile Card */
.profile-card {
    background: var(--background-color);
    box-shadow: var(--card-shadow);
    padding: 30px;
    border-radius: 20px;
    display: flex;
    gap: 30px;
    align-items: center;
    border: 2px solid transparent;
    transition: var(--transition);
}

.profile-card:hover {
    border-color: var(--accent);
}

/* Photo */
.profile-photo {
    width: 150px;
    min-width: 150px; /* Prevent shrinking */
    height: 150px;
    border-radius: 50%; /* Changed to circular for a softer look */
    object-fit: cover;
    border: 5px solid var(--pink-200);
    box-shadow: 0 0 0 2px var(--accent); /* Outer ring glow */
}

/* Info */
.profile-info h2 {
    margin: 0;
    font-size: 32px;
    color: var(--accent);
    font-weight: 700;
}

.profile-info .specialization {
    font-size: 18px;
    font-weight: 600;
    color: var(--text-color);
    margin-bottom: 15px;
    display: block;
}

.contact-info {
    padding-top: 10px;
    border-top: 1px solid var(--pink-200);
    margin-top: 15px;
}

/* Icon Text Pair Styling */
.icon-text-pair {
    display: flex;
    align-items: center;
    margin: 6px 0;
    font-size: 15px;
    color: var(--text-color);
}
.icon-text-pair i {
    margin-right: 10px;
    color: var(--accent);
    width: 18px;
    text-align: center;
}

/* Rating */
.rating {
    margin-top: 10px;
    display: flex;
    align-items: center;
    font-weight: 600;
    color: orange;
}
.rating i {
    color: gold;
    margin-right: 5px;
}

/* Details Grid */
.details-grid {
    margin-top: 40px;
    display: grid;
    grid-template-columns: repeat(auto-fit,minmax(280px,1fr));
    gap: 30px;
}

.detail-card {
    background: white;
    padding: 25px;
    border-radius: 16px;
    box-shadow: var(--card-shadow);
    border: 1px solid transparent;
    transition: var(--transition);
}

.detail-card:hover {
    border-color: var(--accent);
    transform: translateY(-2px);
}

.detail-title {
    font-weight: 800;
    color: var(--accent);
    font-size: 18px;
    margin-bottom: 10px;
}

.detail-value {
    font-size: 15px;
    color: var(--text-color);
    line-height: 1.6;
}

/* Working Hours Table */
.working-hours-table {
    width: 100%;
    border-collapse: collapse;
}
.working-hours-table td {
    padding: 8px 0;
    font-size: 15px;
    color: var(--text-color);
    border-bottom: 1px dotted var(--pink-200);
}
.working-hours-table td:first-child {
    font-weight: 600;
    width: 30%;
}
.working-hours-table tr:last-child td {
    border-bottom: none;
}

/* Edit Button */
.edit-btn {
    margin-top: 40px;
    display: inline-block;
    padding: 14px 28px;
    font-weight: 700;
    background: var(--accent);
    color: white;
    border-radius: 12px;
    text-decoration: none;
    transition: var(--transition);
    box-shadow: 0 4px 12px rgba(236, 143, 160, 0.4);
}

.edit-btn:hover {
    background: #d96b7f;
    transform: translateY(-1px);
}

/* Footer */
footer {
    margin-top: 60px;
    background: var(--pink-300);
    padding: 40px 0;
    /* Optional: Keep the border-radius or remove for a full-width look */
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .profile-card {
        flex-direction: column; /* Stack image and info vertically */
        text-align: center;
    }
    
    .profile-info {
        width: 100%;
    }
    
    .profile-info h2,
    .profile-info .specialization {
        text-align: center;
    }
    
    .profile-photo {
        margin-bottom: 15px;
    }

    .left-icons, .right-icons {
        gap: 12px;
    }

    .contact-info {
        display: inline-block; /* Helps center the block */
        border-top: none;
        padding-top: 0;
    }

    .icon-text-pair {
        justify-content: center; /* Center contact icons */
    }

    .rating {
        justify-content: center;
    }
}
</style>
</head>

<body>

<div class="navbar">
    <div class="left-icons">
        <a href="doc_dashboard.php" title="Dashboard"><i class="fa-solid fa-house"></i></a>
        <a href="doc_patients.php" title="Patients"><i class="fa-solid fa-user-doctor"></i></a>
        <a href="doc_appointments.php" title="Appointments"><i class="fa-solid fa-calendar-check"></i></a>
    </div>

    <div class="right-icons">
        <a href="doc_alerts.php" title="Alerts"><i class="fa-solid fa-bell"></i></a>
        <a href="doc_settings.php" title="Settings"><i class="fa-solid fa-gear"></i></a>
        <a href="doc_logout.php" title="Logout"><i class="fa-solid fa-right-from-bracket"></i></a>
    </div>
</div>

<div class="container">

    <div class="profile-header">
        <h1>Doctor Profile</h1>
        <p style="color:var(--muted);">Manage your personal and clinic information</p>
    </div>

    <div class="profile-card">
        <img src="<?= $doctor["photo_url"] ?>" class="profile-photo" alt="Dr. <?= $doctor["name"] ?> Profile Photo">

        <div class="profile-info">
            <h2><?= $doctor["name"] ?></h2>
            <span class="specialization"><?= $doctor["specialization"] ?></span>
            
            <div class="rating">
                <i class="fa-solid fa-star"></i>
                <?= number_format($doctor["average_rating"], 1) ?> (<?= $doctor["review_count"] ?> Reviews)
            </div>

            <div class="contact-info">
                <p class="icon-text-pair"><i class="fa-solid fa-envelope"></i> <?= $doctor["email"] ?></p>
                <p class="icon-text-pair"><i class="fa-solid fa-phone"></i> <?= $doctor["phone"] ?></p>
            </div>
        </div>
    </div>

    <div class="details-grid">

        <div class="detail-card">
            <div class="detail-title"><i class="fa-solid fa-briefcase"></i> Experience</div>
            <div class="detail-value"><?= $doctor["experience"] ?> in the field</div>
        </div>

        <div class="detail-card">
            <div class="detail-title"><i class="fa-solid fa-hospital"></i> Hospital & Location</div>
            <div class="detail-value">
                <strong><?= $doctor["hospital"] ?></strong>
                <p class="icon-text-pair" style="margin-top: 8px;">
                    <i class="fa-solid fa-location-dot"></i> 
                    <?= $doctor["hospital_address"] ?>
                </p>
            </div>
        </div>

        <div class="detail-card">
            <div class="detail-title"><i class="fa-solid fa-clock"></i> Working Hours</div>
            <div class="detail-value">
                <table class="working-hours-table">
                    <?php foreach ($doctor["working_hours"] as $day => $time): ?>
                        <tr>
                            <td><?= $day ?></td>
                            <td><?= $time ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>

        <div class="detail-card" style="grid-column: 1 / -1;"> <div class="detail-title"><i class="fa-solid fa-circle-info"></i> Professional Bio</div>
            <div class="detail-value"><?= $doctor["bio"] ?></div>
        </div>

    </div>
    
    <a href="doc_edit_profile.php" class="edit-btn">
        <i class="fa-solid fa-pen-to-square"></i> Edit Profile Information
    </a>

    <div class="details-grid" style="margin-top: 60px;">
        <div class="detail-card" style="grid-column: 1 / -1; background: var(--pink-200);">
            <div class="detail-title" style="font-size: 22px; margin-bottom: 20px;">
                <i class="fa-solid fa-comments"></i> Patient Reviews
            </div>
            <p>This area would display recent patient reviews and testimonials, managed via the database.</p>
            <p style="font-style: italic; color: var(--muted); margin-top: 15px;">(Feature implementation required: Fetch and loop through patient review data here.)</p>
        </div>
    </div>

</div>

<footer>
    <div class="container" style="text-align: center;">
        <h2 style="color:var(--accent); font-weight: 700;">Janani Doctor Panel</h2>
        <p style="color: var(--text-color); font-size: 14px; margin-top: 10px;">&copy; <?= date("Y"); ?> All rights reserved.</p>
    </div>
</footer>

</body>
</html>