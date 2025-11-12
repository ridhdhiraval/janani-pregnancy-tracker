<?php
require_once __DIR__ . '/lib/auth.php';
require_once __DIR__ . '/config/db.php';
start_secure_session();

$user = current_user();
if (!$user) {
    header('Location: /JANANI/1signinsignup.php');
    exit;
}

$page_title = "Edit Mum Profile"; 
$back_link = "my_profile.php"; // Changed to go back to the profile view page
$edit_weight_link = "edit_weight.php?member=mum";

// 1. Fetch current profile
$stmt = $pdo->prepare('SELECT * FROM user_profiles WHERE user_id = ? LIMIT 1');
$stmt->execute([$user['id']]);
$profile = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch as associative array

$member_initial = strtoupper(substr($user['username'] ?? ($profile['first_name'] ?? 'M'), 0, 1));
$initial_mother_name = trim(($profile['first_name'] ?? '') . ' ' . ($profile['last_name'] ?? '')) ?: $user['username'];
$initial_phone = $profile['phone'] ?? '';
$initial_gender = $profile['gender'] ?? '';
$initial_dob = $profile['dob'] ?? '';
$initial_pre_pregnancy_weight = $profile['pre_pregnancy_weight'] ?? '';
$initial_profile_image = '';

if (!empty($profile['avatar_image_id'])) {
    $q = $pdo->prepare('SELECT path FROM images WHERE id = ? LIMIT 1');
    $q->execute([$profile['avatar_image_id']]);
    $r = $q->fetch(PDO::FETCH_ASSOC);
    $initial_profile_image = $r['path'] ?? '';
}

$errors = [];
$success = null;

// Handle POST (Existing logic preserved and slightly cleaned up)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF check
    $csrf = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($csrf)) {
        $errors[] = 'Invalid CSRF token';
    } else {
        $motherName = trim($_POST['mother_name'] ?? '');
        
        // Split name into first/last
        $first = '';
        $last = '';
        if ($motherName !== '') {
            $parts = preg_split('/\s+/', $motherName, 2); // Split only once
            $first = $parts[0];
            $last = count($parts) > 1 ? $parts[1] : '';
        }

        // Handle Avatar Upload
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $tmp = $_FILES['avatar']['tmp_name'];
            $name = basename($_FILES['avatar']['name']);
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','webp'];
            if (!in_array($ext, $allowed)) {
                $errors[] = 'Unsupported image type';
            } else {
                $targetDir = __DIR__ . '/uploads/avatars';
                if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
                $newName = 'avatar_' . $user['id'] . '_' . time() . '.' . $ext;
                $targetPath = $targetDir . '/' . $newName;
                
                if (move_uploaded_file($tmp, $targetPath)) {
                    $webPath = '/JANANI/uploads/avatars/' . $newName;
                    $ins = $pdo->prepare('INSERT INTO images (filename, path, mime_type, size, uploader_id, usage_tag, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())');
                    $ins->execute([$newName, $webPath, mime_content_type($targetPath), filesize($targetPath), $user['id'], 'avatar']);
                    $imageId = $pdo->lastInsertId();
                    // Update the profile's avatar_image_id for the database query
                    $profile['avatar_image_id'] = $imageId; 
                    $initial_profile_image = $webPath; // Update local variable for display refresh
                } else {
                    $errors[] = 'Failed to move uploaded file';
                }
            }
        }

        // Gather fields from POST for update/insert
        $phonePost = trim($_POST['phone'] ?? ($profile['phone'] ?? null));
        $genderPost = $_POST['gender'] ?? ($profile['gender'] ?? null);
        $dobPost = $_POST['dob'] ?? ($profile['dob'] ?? null);
        $preWeightPost = $_POST['pre_pregnancy_weight'] ?? ($profile['pre_pregnancy_weight'] ?? null);

        if (empty($errors)) {
            // Check for pre_pregnancy_weight column existence (as per original code)
            $hasPreWeight = false;
            try {
                $colQ = $pdo->prepare("SHOW COLUMNS FROM user_profiles LIKE 'pre_pregnancy_weight'");
                $colQ->execute();
                $hasPreWeight = (bool)$colQ->fetch();
            } catch (Exception $e) {
                // Ignore DB error for column check
            }

            if ($profile) {
                // Update existing profile
                $fields = ['first_name = ?', 'last_name = ?', 'phone = ?', 'gender = ?', 'dob = ?', 'avatar_image_id = ?'];
                $params = [ $first, $last, $phonePost, $genderPost, $dobPost, $profile['avatar_image_id'] ?? null ];
                
                if ($hasPreWeight) {
                    array_unshift($fields, 'pre_pregnancy_weight = ?');
                    array_unshift($params, $preWeightPost);
                }
                
                $sql = 'UPDATE user_profiles SET ' . implode(', ', $fields) . ' WHERE user_id = ?';
                $params[] = $user['id'];
                $up = $pdo->prepare($sql);
                $up->execute($params);
            } else {
                // Insert new profile
                if ($hasPreWeight) {
                    $ins = $pdo->prepare('INSERT INTO user_profiles (user_id, first_name, last_name, phone, gender, dob, pre_pregnancy_weight, avatar_image_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
                    $ins->execute([$user['id'], $first, $last, $phonePost, $genderPost, $dobPost, $preWeightPost, $profile['avatar_image_id'] ?? null]);
                } else {
                    $ins = $pdo->prepare('INSERT INTO user_profiles (user_id, first_name, last_name, phone, gender, dob, avatar_image_id) VALUES (?, ?, ?, ?, ?, ?, ?)');
                    $ins->execute([$user['id'], $first, $last, $phonePost, $genderPost, $dobPost, $profile['avatar_image_id'] ?? null]);
                }
            }
            $success = 'Profile updated successfully!';
            
            // Re-fetch profile data to update local variables with new data
            $stmt = $pdo->prepare('SELECT * FROM user_profiles WHERE user_id = ? LIMIT 1');
            $stmt->execute([$user['id']]);
            $profile = $stmt->fetch(PDO::FETCH_ASSOC);
            $initial_mother_name = trim(($profile['first_name'] ?? '') . ' ' . ($profile['last_name'] ?? '')) ?: $user['username'];
            $member_initial = strtoupper(substr($profile['first_name'] ?? $user['username'], 0, 1));
            $initial_phone = $profile['phone'] ?? '';
            $initial_gender = $profile['gender'] ?? '';
            $initial_dob = $profile['dob'] ?? '';
            $initial_pre_pregnancy_weight = $profile['pre_pregnancy_weight'] ?? '';
        }
    }
}

$csrf = generate_csrf_token();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <style>
        /* Define pink theme variables */
        :root {
            --pink-primary: #ff69b4; /* Hot Pink */
            --pink-light: #ffe8ed;
            --bg-color: #fcf6f7; /* Very light pink background */
            --text-dark: #333;
            --text-light: #666;
            --transition-speed: 0.3s;
        }

        /* General body reset and font */
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif; /* Use a modern font like Poppins (if imported) */
            background-color: var(--bg-color); 
            color: var(--text-dark);
        }

        /* --- Header Bar --- */
        .header-bar-wrapper {
            width: 100%;
            background-color: #fff; 
            border-bottom: 2px solid var(--pink-light);
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .app-header {
            display: flex;
            align-items: center;
            padding: 15px 20px; 
            max-width: 800px; 
            margin: 0 auto;
        }
        
        .app-header .header-content {
            display: flex;
            align-items: center;
            flex-grow: 1;
            position: relative;
        }

        .app-header h1 {
            font-size: 22px; 
            font-weight: 600;
            color: var(--pink-primary);
            margin: 0 auto;
            white-space: nowrap; 
        }

        .back-arrow {
            font-size: 32px; 
            text-decoration: none;
            color: var(--pink-primary);
            line-height: 1;
            padding-right: 10px;
            transition: color 0.2s;
        }
        .back-arrow:hover {
            color: #ff4081;
        }

        /* --- Main Content Area --- */
        .content-area {
            max-width: 800px; 
            margin: 20px auto; 
            padding: 0 20px 40px; 
            text-align: center;
        }

        /* --- Avatar Styling --- */
        .large-avatar-container {
            display: inline-block;
            margin-bottom: 25px;
            position: relative;
        }
        
        .large-avatar {
            width: 140px; 
            height: 140px;
            border-radius: 50%;
            background-color: var(--pink-light);
            border: 3px solid var(--pink-primary);
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .large-avatar .initial {
            font-size: 60px; 
            font-weight: bold;
            color: var(--pink-primary); 
            position: absolute; 
        }
        
        .large-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover; 
            border-radius: 50%; 
            position: absolute;
        }

        .photo-link {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background: var(--pink-primary);
            color: white;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            transition: transform 0.2s, background 0.2s;
        }
        .photo-link:hover {
            background: #ff4081;
            transform: scale(1.05);
        }

        .camera-icon {
            font-size: 20px;
            line-height: 1;
        }
        
        #photoInput {
            display: none;
        }
        
        /* --- Settings/Data List Container --- */
        .data-list-container {
            background-color: white; 
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08); 
            margin-top: 20px; 
            overflow: hidden;
        }
        
        .data-list-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 25px;
            border-bottom: 1px solid var(--pink-light);
            text-decoration: none;
            color: var(--text-dark);
            transition: background-color 0.2s;
        }
        
        .data-list-item:hover {
            background-color: #fdf2f4; /* Subtle light pink hover */
        }

        .data-list-item:last-child {
            border-bottom: none; 
        }

        .item-label {
            font-size: 16px;
            font-weight: 500;
            flex-basis: 35%; /* Fixed width for label */
            text-align: left;
        }

        .item-value {
            font-size: 16px;
            color: var(--text-light); 
            flex-grow: 1;
            text-align: right;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        /* Input/Select Styling within list items */
        .item-value input, .item-value select {
            border: 1px solid var(--pink-light);
            border-radius: 6px;
            padding: 8px 10px;
            font-size: 16px;
            color: var(--text-dark);
            max-width: 100%;
            text-align: right;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .item-value input:focus, .item-value select:focus {
            border-color: var(--pink-primary);
            box-shadow: 0 0 5px rgba(255, 105, 180, 0.5);
            outline: none;
        }

        /* Specific style for Mother's Name to make it look clickable */
        .data-list-item.clickable .item-value {
            color: var(--pink-primary);
            font-weight: 600;
        }

        .arrow-right {
            font-size: 24px;
            color: #b0b0b0;
            line-height: 1;
            margin-left: 10px;
            flex-shrink: 0;
        }
        
        /* Save Button Styling */
        .save-area {
            padding: 25px 0;
            text-align: center;
        }
        #saveBtn {
            background: var(--pink-primary);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
            box-shadow: 0 4px 10px rgba(255, 105, 180, 0.4);
        }
        #saveBtn:hover {
            background: #ff4081;
            transform: translateY(-2px);
        }
        .cancel-link {
            margin-left: 15px;
            color: var(--text-light);
            text-decoration: none;
            font-weight: 500;
            font-size: 16px;
        }

        /* --- Modal Styles (Improved) --- */
        .modal-overlay {
            display: none; 
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); 
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }

        .name-modal {
            background-color: white;
            width: 90%;
            max-width: 350px; 
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
            padding: 25px;
            animation: fadeIn 0.3s ease-out;
        }

        .modal-header-text {
            font-size: 20px;
            font-weight: 700;
            color: var(--pink-primary);
            margin-bottom: 20px;
        }
        
        .name-input {
            width: 100%;
            padding: 12px;
            border: 2px solid var(--pink-light);
            border-radius: 8px;
            box-sizing: border-box; 
            font-size: 16px;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 20px;
        }

        .modal-button {
            background: none;
            border: none;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            padding: 10px 15px;
            border-radius: 6px;
            transition: background-color 0.2s;
        }

        #cancelNameBtn {
            color: var(--text-light); 
        }

        #okNameBtn {
            background-color: var(--pink-primary); 
            color: white;
        }
        
        #okNameBtn:hover {
            background-color: #ff4081;
        }
        
        /* Success/Error Message Styles */
        .message-box {
            padding: 15px;
            margin: 15px auto;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>

<div class="profile-app-container">
    
    <div class="header-bar-wrapper">
        <header class="app-header">
            <div class="header-content">
                <a href="<?php echo htmlspecialchars($back_link); ?>" class="back-arrow">&#x2329;</a> 
                <h1><?php echo htmlspecialchars($page_title); ?></h1>
            </div>
        </header>
    </div>

    <div class="content-area">
        
        <?php if ($success): ?>
            <div class="message-box success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <?php if (!empty($errors)): ?>
            <div class="message-box error">
                <ul>
                    <?php foreach($errors as $err) echo "<li>" . htmlspecialchars($err) . "</li>"; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf); ?>">
            <div class="large-avatar-container">
                <div class="large-avatar" id="avatarContainer">
                    <span class="initial" id="avatarInitial" style="<?php echo !empty($initial_profile_image) ? 'display: none;' : 'display: block;'; ?>"><?php echo htmlspecialchars($member_initial); ?></span>
                    <img id="profileImage" src="<?php echo htmlspecialchars($initial_profile_image); ?>" alt="Profile Image" 
                        style="<?php echo !empty($initial_profile_image) ? 'display: block;' : 'display: none;'; ?>">
                </div>
                
                <div class="photo-link" id="photoLink">
                    <span class="camera-icon">&#128247;</span> 
                </div>
                
                <input type="file" id="photoInput" name="avatar" accept="image/*">
            </div>
            
            <div class="data-list-container">
                
                <div class="data-list-item clickable" id="row-name">
                    <span class="item-label">Mother's name</span>
                    <span class="item-value" id="motherNameValue"><?php echo htmlspecialchars($initial_mother_name); ?></span>
                    <span class="arrow-right">&#x232a;</span>
                </div>
                
                <div class="data-list-item" style="cursor: default; opacity: 0.8;">
                    <span class="item-label">Email (Read-Only)</span>
                    <span class="item-value"><?php echo htmlspecialchars($user['email']); ?></span>
                </div>
                
                <div class="data-list-item">
                    <span class="item-label">Phone</span>
                    <span class="item-value">
                        <input type="text" name="phone" value="<?php echo htmlspecialchars($initial_phone); ?>" placeholder="Enter phone number">
                    </span>
                </div>
                
                <div class="data-list-item">
                    <span class="item-label">Gender</span>
                    <span class="item-value">
                        <select name="gender">
                            <option value="" <?php if ($initial_gender === '') echo 'selected'; ?>>Select</option>
                            <option value="female" <?php if ($initial_gender === 'female') echo 'selected'; ?>>Female</option>
                            <option value="male" <?php if ($initial_gender === 'male') echo 'selected'; ?>>Male</option>
                            <option value="other" <?php if ($initial_gender === 'other') echo 'selected'; ?>>Other</option>
                        </select>
                    </span>
                </div>
                
                <div class="data-list-item">
                    <span class="item-label">Date of birth</span>
                    <span class="item-value">
                        <input type="date" name="dob" value="<?php echo htmlspecialchars($initial_dob); ?>">
                    </span>
                </div>
                
                
            </div>
            
            <div class="save-area">
                <input type="hidden" name="mother_name" id="motherNameInput" value="<?php echo htmlspecialchars($initial_mother_name); ?>">
                <input type="hidden" name="pre_pregnancy_weight" id="preWeightInput" value="<?php echo htmlspecialchars($initial_pre_pregnancy_weight); ?>">
                
                <button type="submit" id="saveBtn">Save Changes</button>
                <a href="my_profile.php" class="cancel-link">Cancel</a>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="nameModalOverlay">
    <div class="name-modal">
        <div class="modal-header-text">Edit Mother's Name</div>
        
        <div class="modal-input-container">
            <input type="text" class="name-input" id="nameInput" placeholder="Enter full name (First Last)">
        </div>

        <div class="modal-footer">
            <button class="modal-button" id="cancelNameBtn">CANCEL</button>
            <button class="modal-button" id="okNameBtn">OK</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elements
        const photoLink = document.getElementById('photoLink');
        const photoInput = document.getElementById('photoInput');
        const avatarInitial = document.getElementById('avatarInitial');
        const profileImage = document.getElementById('profileImage');
        const nameRow = document.getElementById('row-name');
        const motherNameValue = document.getElementById('motherNameValue');
        const motherNameInput = document.getElementById('motherNameInput'); // Hidden field

        // Modal Elements
        const nameModalOverlay = document.getElementById('nameModalOverlay');
        const nameInput = document.getElementById('nameInput');
        const cancelNameBtn = document.getElementById('cancelNameBtn');
        const okNameBtn = document.getElementById('okNameBtn');
        
        // --- Modal Control Functions ---
        
        function openNameModal() {
            // Load current value into the modal input
            nameInput.value = motherNameValue.textContent.trim();
            nameModalOverlay.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            setTimeout(() => nameInput.focus(), 100); 
        }

        function closeNameModal() {
            nameModalOverlay.style.display = 'none';
            document.body.style.overflow = '';
        }
        
        // --- Event Listeners ---

        // 1. Photo Upload Click
        photoLink.addEventListener('click', function() {
            photoInput.click(); 
        });
        
        // 2. Handle file selection change (Display selected image)
        photoInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    profileImage.src = e.target.result;
                    profileImage.style.display = 'block'; // Show the image
                    avatarInitial.style.display = 'none'; // Hide the initial
                };

                reader.readAsDataURL(this.files[0]); // Read the selected file
            } else {
                // If user cancels, keep current display state (either image or initial)
                // If current image is not set, show initial
                if (!profileImage.src || profileImage.src === window.location.href || profileImage.src.endsWith('undefined')) {
                    profileImage.style.display = 'none';
                    avatarInitial.style.display = 'block';
                }
            }
        });

        // 3. Mother's Name Row Click -> Open Modal
        nameRow.addEventListener('click', openNameModal);

        // 4. OK Button Click -> Save to display/hidden input and Close
        okNameBtn.addEventListener('click', function() {
            const newName = nameInput.value.trim();
            if (newName) {
                // Update visible value
                motherNameValue.textContent = newName;
                // Update hidden input (essential for form submission)
                motherNameInput.value = newName;
                
                // OPTIONAL: Update avatar initial on the fly
                const firstInitial = newName.charAt(0).toUpperCase();
                if (firstInitial) {
                    avatarInitial.textContent = firstInitial;
                }
            }
            closeNameModal();
        });

        // 5. CANCEL Button Click -> Discard and Close
        cancelNameBtn.addEventListener('click', closeNameModal);

        // 6. Close Modal on Overlay Click
        nameModalOverlay.addEventListener('click', function(event) {
            if (event.target === nameModalOverlay) {
                closeNameModal();
            }
        });
    });
</script>

</body>
</html>