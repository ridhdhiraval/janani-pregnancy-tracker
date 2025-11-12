<?php
// PHP Section: Define dynamic data variables
$page_title = "App settings";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?> - App Settings View</title>
    
    <style>
        /* General body reset and font */
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f7f3ed; /* Light beige background */
            min-height: 100vh;
        }

        /* --- Full Width Header Bar Wrapper (Simulates the App Header) --- */
        .header-bar-wrapper {
            width: 100%;
            background-color: #ffffff; /* White header background */
            border-bottom: 1px solid #e0d9cd;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        
        /* --- Top Header Bar Content (Constrained width) --- */
        .app-header {
            display: flex;
            align-items: center;
            padding: 15px 30px; 
            max-width: 1200px; 
            margin: 0 auto; /* Center the header content */
        }
        
        .app-header .header-content {
            display: flex;
            align-items: center;
            flex-grow: 1;
            position: relative;
        }

        .app-header h1 {
            font-size: 20px; 
            font-weight: 500;
            color: #4b4b4b;
            margin: 0 auto;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            white-space: nowrap; 
        }

        /* Back Arrow Styling */
        .back-arrow {
            font-size: 24px; 
            text-decoration: none;
            color: #4b4b4b; 
            line-height: 1;
            position: relative;
            z-index: 10;
        }

        /* --- Content Wrapper for Alignment, Background, and Shadow --- */
        .content-wrapper {
            max-width: 1200px; 
            margin: 0 auto; 
            padding: 0 30px; 
        }

        /* --- Settings List Style --- */
        .settings-list {
            background-color: #ffffff; /* White background for the list block */
            border-radius: 8px; 
            margin-top: 20px; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.05); 
        }
        
        .setting-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            cursor: pointer;
            border-bottom: 1px solid #e0d9cd; 
            margin: 0 30px; 
            transition: background-color 0.1s;
            text-decoration: none; /* KEY: Remove underline from the <a> tag */
            color: inherit; 
        }

        .setting-item:last-child {
            border-bottom: none;
        }
        
        .setting-item:first-child {
            padding-top: 25px; 
        }

        .setting-item:last-child {
            padding-bottom: 25px; 
        }

        .setting-item:hover {
            background-color: #fcfcfc;
        }

        .setting-item span.label {
            font-size: 18px;
            color: #4b4b4b;
            font-weight: 500;
        }

        /* Right Arrow Styling */
        .setting-item span.arrow {
            font-size: 24px;
            color: #ccc; 
            font-weight: bold; 
        }
    </style>
</head>
<body>

<div class="settings-app-container">
    
    <div class="header-bar-wrapper">
        <header class="app-header">
            <div class="header-content">
                <a href="7settings.php" class="back-arrow">&lt;</a> 
                <h1><?php echo htmlspecialchars($page_title); ?></h1>
            </div>
        </header>
    </div>

    <div class="content-wrapper">
        <div class="settings-list">
            <a href="family.php" class="setting-item" id="setting-family">
                <span class="label">My family</span>
                <span class="arrow">&rsaquo;</span>
            </a>
            
            <a href="profile.php" class="setting-item" id="setting-profile">
                <span class="label">My profile</span>
                <span class="arrow">&rsaquo;</span>
            </a>
            
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const backButton = document.querySelector('.back-arrow');
        const settingItems = document.querySelectorAll('.setting-item');

        // 1. Back Button Click Handler - Removed e.preventDefault() to allow navigation
        backButton.addEventListener('click', function(e) {
            console.log("Navigating to 7settings.php...");
            // The browser will handle the navigation because e.preventDefault() is removed.
        });

        // 2. Settings Item Click Handler - Removed e.preventDefault() to allow navigation
        settingItems.forEach(item => {
            item.addEventListener('click', function(e) {
                const label = item.querySelector('.label').textContent;
                console.log(`Navigating to ${item.getAttribute('href')} for ${label}...`);
                // The browser will handle the navigation because e.preventDefault() is removed.
            });
        });
    });
</script>

</body>
</html>