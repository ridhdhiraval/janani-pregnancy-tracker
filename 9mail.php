<?php
// PHP logic: Check for inbox messages
$page_title = "Preglife inbox";
$has_updates = false; // Based on the image
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        /* --- Color Palette from App Image --- */
        :root {
            --app-primary: #F8B4C3; /* Pink for accents/envelope */
            --app-secondary-bg: #FCF6F0; /* Light beige/off-white background */
            --text-dark: #333;
            --text-light: #555;
            --side-padding: 25px; /* Standard padding for corner elements */
        }

        /* --- Base & FULL DESKTOP COVERAGE --- */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--app-secondary-bg);
            display: flex;
            justify-content: center;
            align-items: flex-start;
            
            /* FORCE FULL SCREEN COVERAGE */
            min-height: 100vh;
            width: 100vw; 
            margin: 0;
            padding: 0;
            overflow-x: hidden; 
        }

        .inbox-container {
            width: 100vw;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center; 
            background-color: var(--app-secondary-bg);
        }
        
        /* This wrapper ensures the content stays readable (max 800px) */
        .content-wrapper {
            width: 100%;
            max-width: 800px; 
            box-sizing: border-box; 
        }
        
        /* --- Header Section --- */
        .header-full-width-section {
            width: 100vw;
            background-color: var(--app-secondary-bg);
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        
        .header {
            display: grid; 
            grid-template-columns: auto 1fr auto; 
            align-items: center;
            padding: 20px 0; 
            color: var(--text-dark);
            width: 100%; 
            text-align: center; 
        }
        
        .header .close-button {
            font-size: 1.5rem;
            cursor: pointer;
            justify-self: start; 
            padding-left: var(--side-padding); 
            color: var(--text-dark); 
        }

        .header h1 {
            font-size: 1.2rem;
            font-weight: 500;
            margin: 0;
        }

        .header .filler {
            /* Placeholder to maintain grid structure */
            justify-self: end; 
            padding-right: var(--side-padding); 
        }
        
        /* --- Inbox Content Section --- */
        .inbox-content-section {
            width: 100%;
            flex-grow: 1; /* Pushes content down on a large screen */
            display: flex;
            justify-content: center;
            align-items: center; 
            padding-top: 50px; /* Optional top padding */
        }

        .inbox-message {
            text-align: center;
            padding: 20px;
            max-width: 350px;
        }

        .inbox-message i {
            font-size: 5rem;
            color: var(--app-primary);
            margin-bottom: 20px;
        }

        .inbox-message p {
            font-size: 1rem;
            color: var(--text-light);
            line-height: 1.5;
            margin: 0;
        }

    </style>
    <script>
        // Close button: Navigates to 5index.php
        function closeInbox() {
            // back button par tap karne se 5index.php file open honi chahiye
            window.location.href = '5index.php';
        }
    </script>
</head>
<body>

<div class="inbox-container">
    
    <div class="header-full-width-section">
        <div class="content-wrapper">
            <div class="header">
                <span class="close-button" onclick="closeInbox()"><i class='bx bx-x'></i></span>
                <h1><?php echo $page_title; ?></h1>
                <span class="filler"></span>
            </div>
        </div>
    </div>
    
    <div class="inbox-content-section">
        <div class="inbox-message">
            <i class='bx bx-envelope-open'></i> 
            <?php if (!$has_updates): ?>
                <p>We have no updates.</p>
                <p>Please check again later.</p>
            <?php else: ?>
                <p>Your latest messages are here.</p>
            <?php endif; ?>
        </div>
    </div>
    
</div>

</body>
</html>