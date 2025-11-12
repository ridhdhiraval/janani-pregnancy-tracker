<?php
// PHP Section: Define dynamic data and navigation links
$page_title = "My family";
$back_link = "7settings.php"; // Assuming a settings page or previous page
$add_family_member_link = "add_family_member.php"; // Link for the '+' icon

// Array to hold family members
$family_members = [
    [
        'initial' => 'M',
        'name' => 'Mum',
        'role' => 'Mum',
        'profile_link' => 'edit_mom_profile.php?id=mum' // Example link
    ],
    [
        'initial' => 'P',
        'name' => 'Partner',
        'role' => 'Partner',
        'profile_link' => 'edit_dad_profile.php?id=partner' // Example link
    ]
    // Add more family members as needed
    /*
    [
        'initial' => 'D',
        'name' => 'Dad',
        'role' => 'Dad',
        'profile_link' => 'edit_family_member.php?id=dad'
    ]
    */
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <style>
        /* General body reset and font */
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f5f5f5; /* Main page background color */
        }

        /* --- Full Width Header Bar Wrapper --- */
        .header-bar-wrapper {
            width: 100%;
            background-color: #f7f3ed; /* Header's distinct beige color */
            border-bottom: 1px solid #e0d9cd;
        }
        
        /* --- Top Header Bar Content (Constrained width) --- */
        .app-header {
            display: flex;
            align-items: center;
            padding: 25px 30px; 
            background-color: transparent; /* Inherits color from wrapper */
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
            font-size: 24px; 
            font-weight: normal;
            color: #4b4b4b;
            margin: 0 auto;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            white-space: nowrap; 
        }

        .back-arrow {
            font-size: 36px; 
            text-decoration: none;
            color: #4b4b4b;
            line-height: 1;
            position: relative;
            z-index: 10; /* Ensure arrow is clickable */
        }

        .add-icon {
            font-size: 30px;
            text-decoration: none;
            color: #4b4b4b;
            line-height: 1;
            margin-left: auto; /* Push to the right */
            position: relative;
            z-index: 10; /* Ensure icon is clickable */
            font-weight: bold; /* Make the plus sign bolder */
        }

        /* --- Main Content Wrapper for Alignment, Background, and Shadow --- */
        .content-wrapper {
            max-width: 1200px; 
            margin: 20px auto; 
            background-color: white; 
            padding: 0; /* No horizontal padding here, items have their own */
            border-bottom: 1px solid #e0d9cd;
            min-height: 400px; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.05); 
        }

        /* --- Family Member List Styling --- */
        .family-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .family-list-item {
            display: flex;
            align-items: center;
            padding: 20px 30px; /* Padding matches header */
            border-bottom: 1px solid #e0d9cd;
            cursor: pointer;
            transition: background-color 0.1s;
        }

        .family-list-item:hover {
            background-color: #fcfcfc;
        }

        .family-list-item:last-child {
            border-bottom: none; /* No border for the last item */
        }

        .avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #f7e0e0; /* Light pink/red background for avatars */
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 24px;
            font-weight: bold;
            color: #e65252; /* Red text for avatars */
            margin-right: 20px;
            flex-shrink: 0; /* Prevent avatar from shrinking */
        }

        .member-details {
            flex-grow: 1; /* Allows details to take up available space */
        }

        .member-details h3 {
            margin: 0;
            font-size: 18px;
            font-weight: normal; /* Normal weight for name */
            color: #4b4b4b;
        }

        .member-details p {
            margin: 0;
            font-size: 14px;
            color: #888; /* Lighter color for role */
        }

        .arrow-right {
            font-size: 28px; /* Size for the arrow */
            color: #b0b0b0; /* Light gray for the arrow */
            margin-left: 20px;
            flex-shrink: 0; /* Prevent arrow from shrinking */
            line-height: 1; /* Align vertically better */
        }
    </style>
</head>
<body>

<div class="family-app-container">
    
    <div class="header-bar-wrapper">
        <header class="app-header">
            <div class="header-content">
                <a href="<?php echo htmlspecialchars($back_link); ?>" class="back-arrow">&#x2329;</a> 
                <h1><?php echo htmlspecialchars($page_title); ?></h1>
                <a href="<?php echo htmlspecialchars($add_family_member_link); ?>" class="add-icon">&#x2b;</a> </div>
        </header>
    </div>

    <div class="content-wrapper">
        <ul class="family-list">
            <?php foreach ($family_members as $member): ?>
                <li class="family-list-item" data-href="<?php echo htmlspecialchars($member['profile_link']); ?>">
                    <div class="avatar">
                        <?php echo htmlspecialchars($member['initial']); ?>
                    </div>
                    <div class="member-details">
                        <h3><?php echo htmlspecialchars($member['name']); ?></h3>
                        <p><?php echo htmlspecialchars($member['role']); ?></p>
                    </div>
                    <span class="arrow-right">&#x232a;</span> </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const familyListItems = document.querySelectorAll('.family-list-item');
        const backArrow = document.querySelector('.back-arrow');
        const addIcon = document.querySelector('.add-icon');

        // Handle clicks on family list items
        familyListItems.forEach(item => {
            item.addEventListener('click', function() {
                const targetUrl = this.getAttribute('data-href');
                if (targetUrl) {
                    console.log("Navigating to family member profile: " + targetUrl);
                    window.location.href = targetUrl;
                }
            });
        });

        // Handle click on the back arrow
        backArrow.addEventListener('click', function(event) {
            // The href attribute already handles navigation, but you can add custom logic if needed
            console.log("Navigating back to: " + this.getAttribute('href'));
            // event.preventDefault(); // Uncomment if you want to prevent default and handle manually
            // window.history.back(); // Alternative: go back in browser history
        });

        // Handle click on the add icon
        addIcon.addEventListener('click', function(event) {
            // The href attribute already handles navigation
            console.log("Navigating to add family member page: " + this.getAttribute('href'));
            // event.preventDefault(); // Uncomment if you want to prevent default and handle manually
            // window.location.href = this.getAttribute('href'); // If default is prevented
        });
    });
</script>

</body>
</html>