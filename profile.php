<?php
// profile.php

// Ensure these files exist and contain the necessary functions (start_secure_session, current_user)
require_once __DIR__ . '/lib/auth.php';
require_once __DIR__ . '/config/db.php';

// Define the back link destination
// *** UPDATED: Now points to 5index.php as requested ***
$back_link_destination = "5index.php"; 

// Start a secure session
start_secure_session();

// Check if the user is logged in
$user = current_user();
if (!$user) {
    // If not logged in, redirect to the sign-in/sign-up page
    // *** FIX: Corrected typo in the file path from '5index`.php' to '5index.php' ***
    header('Location: /JANANI/5index.php');
    exit;
}

// 1. Fetch user profile data
// Ensure $pdo is initialized in /config/db.php
try {
    $stmt = $pdo->prepare('SELECT * FROM user_profiles WHERE user_id = ? LIMIT 1');
    $stmt->execute([$user['id']]);
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Basic error handling for database issues
    // In a real application, you'd log this error
    $profile = false; // Set to false if fetching fails
}

// 2. Fetch avatar image path
$avatar = null;
if ($profile && !empty($profile['avatar_image_id'])) {
    try {
        $img = $pdo->prepare('SELECT path FROM images WHERE id = ? LIMIT 1');
        $img->execute([$profile['avatar_image_id']]);
        $imgRow = $img->fetch(PDO::FETCH_ASSOC);
        $avatar = $imgRow['path'] ?? null;
    } catch (PDOException $e) {
        // Handle image fetch failure
    }
}

// Construct Full Name for display
$fullName = trim(($profile['first_name'] ?? '') . ' ' . ($profile['last_name'] ?? ''));
if (empty($fullName)) {
    // Fallback to username if first/last name isn't set
    $fullName = $user['username'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>My Profile - <?php echo htmlspecialchars($user['username']); ?></title>
<style>
    /* Google Font for a modern look */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

    :root {
        --pink-primary: #ff69b4; /* Hot Pink */
        --pink-light: #ffe8ed;
        --bg-color: #fcf6f7;
        --text-dark: #333;
        --text-light: #666;
        --transition-speed: 0.3s;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background: var(--bg-color);
        margin: 0;
        padding: 40px 20px;
        color: var(--text-dark);
        line-height: 1.6;
    }
    
    /* --- NEW Back Button/Link Styling --- */
    .back-link-container {
        max-width: 800px;
        margin: 0 auto 15px; /* Add margin above the card */
    }
    .back-link {
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        color: var(--pink-primary);
        font-weight: 600;
        font-size: 16px;
        padding: 8px 15px;
        border-radius: 8px;
        transition: background var(--transition-speed);
    }
    .back-link:hover {
        background: var(--pink-light);
    }
    .back-arrow-icon {
        font-size: 24px;
        margin-right: 5px;
        line-height: 1;
    }
    /* ------------------------------------- */

    .card {
        max-width: 800px;
        margin: 0 auto;
        background: #fff;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        transition: transform var(--transition-speed) ease-out, box-shadow var(--transition-speed);
    }
    .card:hover {
        transform: translateY(-5px); /* Slide effect on hover */
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
    }

    /* Profile Header Styling (existing) */
    .profile-header {
        display: flex;
        gap: 25px;
        align-items: center;
        border-bottom: 2px solid var(--pink-light);
        padding-bottom: 20px;
        margin-bottom: 30px;
        position: relative;
    }

    .avatar-container {
        flex-shrink: 0;
    }

    .avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%; /* Make it round */
        object-fit: cover;
        background: var(--pink-light);
        border: 4px solid var(--pink-primary);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--pink-primary);
        font-weight: 700;
        font-size: 36px;
    }
    .avatar-placeholder {
        /* Style for the initial letter fallback */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--pink-light);
        color: var(--pink-primary);
        font-weight: 700;
        font-size: 36px;
    }


    .profile-info h2 {
        margin: 0 0 5px 0;
        font-size: 28px;
        color: var(--pink-primary);
        font-weight: 700;
        letter-spacing: 0.5px;
    }
    .profile-info .label {
        color: var(--text-light);
        font-size: 14px;
        margin-bottom: 5px;
    }
    
    /* Actions/Buttons Styling (existing) */
    .actions {
        position: absolute;
        right: 0;
        top: 10px;
        display: flex;
        gap: 10px;
    }
    a.btn {
        background: var(--pink-primary);
        color: #fff;
        padding: 10px 18px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: background var(--transition-speed) ease-in-out, transform 0.2s;
        box-shadow: 0 4px 10px rgba(255, 105, 180, 0.4);
    }
    a.btn:hover {
        background: #ff4081; /* Darker pink on hover */
        transform: translateY(-2px); /* Subtle lift effect on hover */
    }
    a.btn.logout {
        background: #888;
        box-shadow: 0 4px 10px rgba(136, 136, 136, 0.4);
    }
    a.btn.logout:hover {
        background: #666;
    }

    /* Personal Details Styling (existing) */
    .details-section h3 {
        color: var(--pink-primary);
        margin-top: 0;
        border-left: 4px solid var(--pink-primary);
        padding-left: 10px;
        font-size: 20px;
        font-weight: 600;
    }
    .detail-item {
        margin-bottom: 15px;
        padding: 10px 15px;
        border-radius: 8px;
        background: #fdf2f4; /* Very light pink background */
        transition: background var(--transition-speed);
    }
    .detail-item:hover {
        background: var(--pink-light);
    }

    .detail-item strong {
        color: var(--text-dark);
        display: block;
        font-size: 14px;
        margin-bottom: 3px;
    }
    .detail-item span {
        color: var(--text-dark);
        font-weight: 400;
        font-size: 16px;
    }
    
    /* Responsive adjustment (existing) */
    @media (max-width: 600px) {
        .profile-header {
            flex-direction: column;
            text-align: center;
        }
        .actions {
            position: static;
            justify-content: center;
            margin-top: 15px;
            width: 100%;
        }
        .profile-info {
            width: 100%;
            text-align: center;
        }
    }
</style>
</head>
<body>

<div class="back-link-container">
    <a href="<?php echo htmlspecialchars($back_link_destination); ?>" class="back-link">
        <span class="back-arrow-icon">&#x2190;</span> 
        Back to Dashboard
    </a>
</div>

<div class="card">
    
    <div class="profile-header">
        <div class="avatar-container">
            <?php if ($avatar): ?>
                <img src="<?php echo htmlspecialchars($avatar); ?>" class="avatar" alt="User Avatar">
            <?php else: ?>
                <div class="avatar-placeholder"><?php echo strtoupper(substr($user['username'], 0, 1)); ?></div>
            <?php endif; ?>
        </div>
        
        <div class="profile-info">
            <h2><?php echo htmlspecialchars($fullName); ?></h2>
            <div class="label">Username: <strong><?php echo htmlspecialchars($user['username']); ?></strong></div>
            <div class="label">Email: <strong><?php echo htmlspecialchars($user['email']); ?></strong></div>
        </div>

        <div class="actions">
            <a class="btn" href="edit_mom_profile.php">Edit Profile</a>
            <a class="btn logout" href="auth/logout.php">Logout</a>
        </div>
    </div>
    
    <div class="details-section">
        <h3>Personal Details</h3>
        
        <?php if ($profile): ?>
            <div class="detail-item">
                <strong><span style="font-size:16px;">&#9742;</span> Phone Number</strong>
                <span><?php echo htmlspecialchars($profile['phone'] ?? 'N/A'); ?></span>
            </div>
            
            <div class="detail-item">
                <strong><span style="font-size:16px;">&#128197;</span> Date of Birth</strong>
                <span><?php echo htmlspecialchars($profile['dob'] ?? 'N/A'); ?></span>
            </div>
            
            <div class="detail-item">
                <strong><span style="font-size:16px;">&#9792;</span> Gender</strong>
                <span><?php echo htmlspecialchars($profile['gender'] ?? 'N/A'); ?></span>
            </div>
            
            <?php if (!empty($profile['bio'])): ?>
                <div class="detail-item">
                    <strong><span style="font-size:16px;">&#9997;</span> Bio / About Me</strong>
                    <span><?php echo nl2br(htmlspecialchars($profile['bio'])); ?></span>
                </div>
            <?php endif; ?>
        
        <?php else: ?>
            <p style="color:#ff4081; font-style:italic;">Profile details are not available. Please edit your profile to add them.</p>
        <?php endif; ?>
        
    </div>
</div>
</body>
</html>
