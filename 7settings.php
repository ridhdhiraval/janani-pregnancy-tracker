<?php
// Settings / Profile page: show profile when logged in, otherwise show login/register links
require_once __DIR__ . '/lib/auth.php';
require_once __DIR__ . '/config/db.php';
start_secure_session();

$page_title = "Settings";

$user = current_user();
$profile = null;
if ($user) {
    // fetch profile
    try {
        $stmt = $pdo->prepare('SELECT * FROM user_profiles WHERE user_id = ? LIMIT 1');
        $stmt->execute([$user['id']]);
        $profile = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Fetch avatar path
        $avatar = null;
        if ($profile && !empty($profile['avatar_image_id'])) {
            $img = $pdo->prepare('SELECT path FROM images WHERE id = ? LIMIT 1');
            $img->execute([$profile['avatar_image_id']]);
            $imgRow = $img->fetch(PDO::FETCH_ASSOC);
            $avatar = $imgRow['path'] ?? null;
        }

    } catch (PDOException $e) {
        // Handle database error
        // log error and continue with null profile
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?> - Janani App</title>
    
    <style>
        /* --- Root Variables for Theme --- */
        :root {
            --color-primary: #ff6e8a; /* Deep Pink */
            --color-primary-dark: #d13a7c;
            --color-light-bg: #fcf6f7; /* Background */
            --color-card-bg: #ffffff;
            --color-border: #f0e9ea;
            --color-text: #4b4b4b;
            --color-hover: #fff0f5; /* Very light pink for hover */
            --color-arrow: #ccc;
        }

        /* General body reset and font */
        body {
            margin: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: var(--color-light-bg);
            min-height: 100vh;
            color: var(--color-text);
        }

        /* --- Full Width Header Bar Wrapper (App Header) --- */
        .header-bar-wrapper {
            width: 100%;
            background-color: var(--color-card-bg);
            border-bottom: 1px solid var(--color-border);
            box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        }
        
        /* --- Top Header Bar Content (Wider) --- */
        .app-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 30px; 
            max-width: 1000px; /* Increased max width */
            margin: 0 auto; 
        }
        
        .header-content h1 {
            font-size: 20px; 
            font-weight: 600;
            color: var(--color-text);
            margin: 0;
        }

        .back-arrow {
            font-size: 30px; 
            text-decoration: none;
            color: var(--color-text); 
            line-height: 1;
            z-index: 10;
            transition: color 0.2s;
        }
        .back-arrow:hover {
            color: var(--color-primary);
        }
        
        .log-in-link {
            font-size: 14px;
            font-weight: 600;
            color: var(--color-text);
            text-decoration: none;
            text-transform: uppercase;
            padding: 8px 12px;
            border-radius: 8px;
            transition: background-color 0.2s, color 0.2s;
        }
        .log-in-link:hover {
            background-color: var(--color-hover);
            color: var(--color-primary-dark);
        }

        /* --- Main Content Wrapper for Alignment --- */
        .content-wrapper {
            max-width: 800px; 
            margin: 30px auto; 
            padding: 0 20px; 
        }

        /* --- Settings Menu List (The Card) --- */
        .settings-menu-container {
            background-color: var(--color-card-bg);
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-top: 30px;
        }
        
        .setting-item {
            display: flex;
            align-items: center;
            padding: 20px 25px;
            cursor: pointer;
            text-decoration: none; 
            color: var(--color-text);
            border-bottom: 1px solid var(--color-border);
            transition: background-color 0.2s, transform 0.2s, box-shadow 0.2s;
            position: relative;
            overflow: hidden; /* For rounded corners on first/last items */
        }

        /* Styling for the first and last child to ensure rounded borders */
        .settings-menu-container .setting-item:first-child {
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
        }
        .settings-menu-container .setting-item:last-child {
            border-bottom: none;
            border-bottom-left-radius: 16px;
            border-bottom-right-radius: 16px;
        }

        .setting-item:hover {
            background-color: var(--color-hover);
            /* Add a subtle lift effect on hover */
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            z-index: 1;
        }

        .item-icon {
            font-size: 24px;
            margin-right: 20px;
            color: var(--color-primary);
        }

        .item-label {
            font-size: 16px;
            font-weight: 500;
            flex-grow: 1;
        }

        .item-arrow {
            font-size: 28px;
            color: var(--color-arrow); 
            font-weight: bold; 
        }

        /* --- Logged-In Profile Summary Block Styling --- */
        .profile-summary-item {
            align-items: flex-start !important;
            padding: 25px;
            border-bottom: 1px solid var(--color-border);
            /* Remove hover for the summary block itself */
            cursor: default;
            transition: none;
        }
        .profile-summary-item:hover {
            background-color: var(--color-card-bg);
            transform: none;
            box-shadow: none;
        }

        .profile-avatar-container {
            flex: 0 0 64px;
            margin-right: 15px;
        }

        .profile-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%; /* Circular avatar */
            object-fit: cover;
            border: 2px solid var(--color-primary);
        }
        
        .initials-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%; 
            background: #ffe8ed; 
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-primary-dark);
            font-size: 24px;
            font-weight: 700;
            border: 2px solid var(--color-primary);
        }

        .profile-details {
            flex: 1;
            padding-left: 0;
        }

        .profile-name {
            font-weight: 700;
            font-size: 18px;
            color: var(--color-primary-dark);
            margin-bottom: 4px;
        }

        .profile-info {
            color: #666;
            font-size: 14px;
            margin-top: 2px;
        }

        .edit-profile-link {
            display: inline-block;
            margin-top: 10px;
            color: var(--color-primary);
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            padding: 4px 0;
            transition: color 0.2s;
        }
        .edit-profile-link:hover {
            color: var(--color-primary-dark);
            text-decoration: underline;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .app-header {
                padding: 12px 15px;
            }
            .header-content h1 {
                position: static;
                transform: none;
                text-align: center;
                flex-grow: 1;
            }
            .back-arrow {
                margin-right: 15px;
            }
            .content-wrapper {
                padding: 0 15px;
                margin-top: 15px;
            }
            .setting-item {
                padding: 15px 20px;
            }
            .item-icon {
                font-size: 20px;
                margin-right: 15px;
            }
            .profile-summary-item {
                flex-direction: column;
                text-align: center;
            }
            .profile-avatar-container {
                margin-bottom: 15px;
                margin-right: 0;
            }
            .profile-details {
                text-align: center;
            }
        }
    </style>
</head>
<body>

<div class="settings-app-container">
    
    <div class="header-bar-wrapper">
        <header class="app-header">
            <a href="5index.php" class="back-arrow">&leftarrow;</a> 
            
            <div class="header-content">
                <h1><?php echo htmlspecialchars($page_title); ?></h1>
            </div>
            <?php if ($user): ?>
                <div style="display:flex;gap:12px;align-items:center">
                    <!-- Profile link is now redundant here as the summary is shown below, but keeping it for navigation -->
                    <a href="my_profile.php" class="log-in-link">Profile</a>
                    <a href="auth/logout.php" class="log-in-link">Logout</a>
                </div>
            <?php else: ?>
                <div style="display:flex;gap:12px;align-items:center">
                    <a href="1signinsignup.php" class="log-in-link">Log in</a>
                    <a href="1signinsignup.php" class="log-in-link" style="background-color: var(--color-primary); color: white; padding: 8px 16px;">Register</a>
                </div>
            <?php endif; ?>
        </header>
    </div>

    <div class="content-wrapper">
        
        <div class="settings-menu-container">
            <?php if ($user && $profile): 
                $fullName = trim(($profile['first_name'] ?? '') . ' ' . ($profile['last_name'] ?? ''));
                if (empty($fullName)) { $fullName = $user['username']; }
                $initials = strtoupper(substr($user['username'],0,1));
            ?>
                <!-- Profile Summary (Always first item) -->
                <div class="setting-item profile-summary-item">
                    <div class="profile-avatar-container">
                        <?php if (!empty($avatar)): ?>
                            <img src="<?php echo htmlspecialchars($avatar); ?>" alt="Avatar" class="profile-avatar">
                        <?php else: ?>
                            <div class="initials-avatar"><?php echo htmlspecialchars($initials); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="profile-details">
                        <div class="profile-name"><?php echo htmlspecialchars($fullName); ?></div>
                        <div class="profile-info">@<?php echo htmlspecialchars($user['username']); ?></div>
                        <div class="profile-info"><?php echo htmlspecialchars($user['email']); ?></div>
                        <a href="edit_mom_profile.php" class="edit-profile-link">Edit profile details</a>
                    </div>
                </div>
            <?php else: ?>
                <!-- Sign Up Link (If not logged in) -->
                <a href="1signinsignup.php" class="setting-item" id="setting-create">
                    <span class="item-icon">&#9825;</span> 
                    <span class="item-label">Create a Janani Account</span>
                    <span class="item-arrow">&rsaquo;</span>
                </a>
            <?php endif; ?>

            <!-- Core Settings Menu Items -->
            <a href="my_pregnancy.php" class="setting-item" id="setting-pregnancy">
                <span class="item-icon">&#x2665;</span> 
                <span class="item-label">My pregnancy tracking</span>
                <span class="item-arrow">&rsaquo;</span>
            </a>

            <a href="support_faq.php" class="setting-item" id="setting-faq">
                <span class="item-icon">&#x2753;</span> 
                <span class="item-label">Support and FAQ</span>
                <span class="item-arrow">&rsaquo;</span>
            </a>

            <a href="contact_us.php" class="setting-item" id="setting-contact">
                <span class="item-icon">&#x2709;</span> 
                <span class="item-label">Contact us</span>
                <span class="item-arrow">&rsaquo;</span>
            </a>
            
            <!-- Logout Link (If user is logged in, placed at the end of the menu) -->
            <?php if ($user): ?>
                <a href="auth/logout.php" class="setting-item" style="color: var(--color-primary-dark);" id="setting-logout">
                    <span class="item-icon" style="color: var(--color-primary-dark);">&#x2716;</span> 
                    <span class="item-label">Logout</span>
                    <span class="item-arrow">&rsaquo;</span>
                </a>
            <?php endif; ?>
            
        </div>
    </div>
</div>

<script>
    // The JavaScript is now simple and just logs a message, as the navigation is handled by the HTML links.
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function() {
                // This will run when any link is clicked, before the browser navigates.
                console.log(`Navigating to: ${link.getAttribute('href')}`);
            });
        });
    });
</script>
    <?php // include '15footer.php'; ?>

</body>
</html>
