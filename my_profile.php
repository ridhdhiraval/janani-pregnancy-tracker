<?php
// profile.php

// Ensure these files exist and contain the necessary functions (start_secure_session, current_user)
require_once __DIR__ . '/lib/auth.php';
require_once __DIR__ . '/config/db.php';

// Define the back link destination
$back_link_destination = "5index.php"; 

// Start a secure session
start_secure_session();

// Check if the user is logged in
$user = current_user();
if (!$user) {
    // If not logged in, redirect to the sign-in/sign-up page
    header('Location: /JANANI/1signinsignup.php');
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


// compute age if dob present
$age = null;
if (!empty($profile['dob'])) {
    try {
        $dobDate = new DateTime($profile['dob']);
        $now = new DateTime();
        $age = $now->diff($dobDate)->y;
    } catch (Exception $e) {
        $age = null;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>My Profile</title>
<style>
    body { font-family: Arial, sans-serif; background:#fcf6f7; margin:0; padding:20px; }
    .card { max-width:720px; margin:20px auto; background:#fff; padding:20px; border-radius:12px; box-shadow:0 6px 20px rgba(0,0,0,0.06); }
    .avatar { width:96px; height:96px; border-radius:12px; object-fit:cover; background:#ffe8ed; display:inline-block }
    .row { display:flex; gap:16px; align-items:center }
    .label{ color:#666; font-size:13px }
    a.btn { background:#ff69b4; color:#fff; padding:8px 12px; border-radius:8px; text-decoration:none }
    .actions { display:flex; gap:10px; justify-content:flex-end }
    /* Back Link Styles */
    .back-link-container {
        max-width: 720px; 
        margin: 0 auto 15px; /* Centers and adds space above card */
    }
    .back-link {
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        color: #ff69b4; /* Pink primary color */
        font-weight: bold;
        padding: 5px 10px;
        border-radius: 6px;
        transition: background 0.2s;
        font-size: 14px;
    }
    .back-link:hover {
        background: #ffe8ed; /* Light pink background on hover */
    }
</style>
</head>
<body>

<div class="back-link-container">
    <a href="<?php echo htmlspecialchars($back_link_destination); ?>" class="back-link">
        &#x2190; Back to Dashboard
    </a>
</div>

<div class="card">
    <div class="row">
        <div>
            <?php if ($avatar): ?>
                <img src="<?php echo htmlspecialchars($avatar); ?>" class="avatar" alt="avatar">
            <?php else: ?>
                <div class="avatar" style="display:flex;align-items:center;justify-content:center;color:#ff6f8a;font-weight:700"><?php echo strtoupper(substr($user['username'],0,1)); ?></div>
            <?php endif; ?>
        </div>
        <div style="flex:1; position:relative">
            <div class="actions" style="position:absolute;right:0;top:0">
                <a class="btn" href="edit_mom_profile.php">Edit profile</a>
                <a class="btn" href="auth/logout.php" style="background:#888">Logout</a>
            </div>
            <h2><?php echo htmlspecialchars($fullName); ?></h2>
            <div class="label">Username: <?php echo htmlspecialchars($user['username']); ?></div>
            <div class="label">Email: <?php echo htmlspecialchars($user['email']); ?></div>
            <div style="margin-top:12px">
                <!-- actions are at top-right -->
            </div>
        </div>
    </div>
    <hr style="margin:18px 0">
    <div>
        <h3>Personal details</h3>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($profile['phone'] ?? ''); ?></p>
        <?php if ($age !== null): ?>
            <p><strong>Age:</strong> <?php echo htmlspecialchars($age); ?> years</p>
        <?php endif; ?>
        <p><strong>DOB:</strong> <?php echo htmlspecialchars($profile['dob'] ?? ''); ?></p>
        <p><strong>Gender:</strong> <?php echo htmlspecialchars($profile['gender'] ?? ''); ?></p>
    </div>
</div>
</body>
</html>
