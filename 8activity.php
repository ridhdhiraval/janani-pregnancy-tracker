<?php
$page_title = "Your activity";
$fruits_collected = 1;
$fruits_total = 38;
$checklist_completion = 0;
$articles_read = 1;

// Calculate progress percentage
// Use floor() to get a whole number for a cleaner display in the value if preferred, 
// or keep it as-is for precision. Keeping it as is for precision.
$fruits_progress = ($fruits_collected / $fruits_total) * 100;

// You need to ensure the dot doesn't jump off the end if progress is 100% or very close to it.
// Here we cap the dot's position to slightly less than 100% if the progress is high,
// but for simplicity, we'll keep the direct calculation.
// CSS handles the overflow of the fill bar. The dot positioning needs the value.
$dot_position = min($fruits_progress, 100); 
// We use min() to prevent it from going over 100% just in case of float errors or future changes.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        :root {
            --app-primary: #F8B4C3;
            --app-secondary-bg: #FCF6F0;
            --card-bg: #FFFFFF;
            --text-dark: #333;
            --text-light: #555;
            --border-color: #ddd;
            --progress-bar-fill: #F8B4C3;
            --progress-bar-bg: #f9e2e5;
            --icon-bg: #F0F4F7;
            --side-padding: 25px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--app-secondary-bg);
            display: flex;
            justify-content: center;
            /* 💡 CHANGED: Align items to center vertically */
            align-items: center; 
            min-height: 100vh;
            margin: 0;
        }

        .activity-container {
            width: 100%;
            max-width: 700px;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: var(--app-secondary-bg);
            /* Removed bottom padding for vertical centering, added margin instead */
            padding: 20px 0; 
            margin: auto; /* Ensures vertical centering works well */
        }

        .header {
            width: 100%;
            text-align: center;
            padding: 20px 0;
            color: var(--text-dark);
        }

        .header h1 {
            font-size: 1.3rem;
            font-weight: 600;
            margin: 0;
        }

        .activity-cards {
            width: 100%;
            background-color: var(--card-bg);
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .activity-card {
            display: flex;
            align-items: center;
            padding: 20px var(--side-padding);
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .activity-card:hover {
            background-color: #fcfcfc;
        }

        .activity-card:not(:last-child) {
            border-bottom: 1px solid #f0f0f0;
        }

        .icon-wrapper {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background-color: var(--icon-bg);
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 20px;
        }

        .icon-wrapper i {
            font-size: 1.8rem;
            color: var(--text-dark);
        }

        .content {
            flex-grow: 1;
        }

        .content p {
            margin: 0;
            font-size: 0.95rem;
            color: var(--text-light);
        }

        .progress-bar-container {
            margin-top: 5px;
            width: 100%;
            height: 5px;
            background-color: var(--progress-bar-bg);
            border-radius: 2.5px;
            overflow: hidden;
            position: relative;
        }

        .progress-bar-fill {
            height: 100%;
            background-color: var(--progress-bar-fill);
            border-radius: 2.5px;
            transition: width 0.5s ease-in-out;
        }

        .progress-bar-dot {
            position: absolute;
            top: 50%;
            /* 💡 PHP variable used here for dynamic dot position */
            left: <?php echo $dot_position; ?>%; 
            transform: translate(-50%, -50%);
            width: 8px;
            height: 8px;
            background-color: var(--progress-bar-fill);
            border-radius: 50%;
        }

        .value {
            font-size: 1rem;
            font-weight: 500;
            color: var(--text-dark);
            margin-left: 15px;
        }

        /* --- Back Button --- */
        .back-btn {
            margin-top: 40px;
            padding: 12px 25px;
            font-size: 1rem;
            font-weight: 500;
            color: white;
            background-color: var(--app-primary);
            border: none;
            border-radius: 30px;
            cursor: pointer;
            transition: background 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .back-btn i {
            font-size: 1.3rem;
        }

        .back-btn:hover {
            background-color: #f58fa5;
        }

    </style>
    <script>
        function goBackToMainPage() {
            // Placeholder: Replace with actual navigation logic
            window.location.href = '5index.php';
        }

        function openFruitDetails() {
            // Placeholder: Replace with actual navigation logic
            window.location.href = 'fruit_details.php';
        }

        function openChecklist() {
            // Placeholder: Replace with actual navigation logic
            window.location.href = 'checklist.php';
        }

        function openArticleList() {
            // Placeholder: Replace with actual navigation logic
            window.location.href = 'articles.php';
        }
    </script>
</head>
<body>

<div class="activity-container">

    <div class="header">
        <h1><?php echo $page_title; ?></h1>
    </div>

    <div class="activity-cards">
        <div class="activity-card" onclick="openFruitDetails()">
            <div class="icon-wrapper">
                <i class='bx bxs-pear'></i>
            </div>
            <div class="content">
                <p>Number of fruits you have collected</p>
                <div class="progress-bar-container">
                    <div class="progress-bar-fill" style="width: <?php echo $fruits_progress; ?>%;"></div>
                    <div class="progress-bar-dot"></div> 
                </div>
            </div>
            <span class="value"><?php echo $fruits_collected . '/' . $fruits_total; ?></span>
        </div>

        <div class="activity-card" onclick="openChecklist()">
            <div class="icon-wrapper">
                <i class='bx bx-list-ul'></i>
            </div>
            <div class="content">
                <p>Your checklist completion</p>
                <div class="progress-bar-container">
                    <div class="progress-bar-fill" style="width: <?php echo $checklist_completion; ?>%;"></div>
                    <?php if ($checklist_completion > 0) { ?>
                        <div class="progress-bar-dot" style="left: <?php echo $checklist_completion; ?>%;"></div>
                    <?php } ?>
                </div>
            </div>
            <span class="value"><?php echo $checklist_completion; ?>%</span>
        </div>

        <div class="activity-card" onclick="openArticleList()">
            <div class="icon-wrapper">
                <i class='bx bx-file-text'></i>
            </div>
            <div class="content">
                <p>Number of articles you have read</p>
            </div>
            <span class="value"><?php echo $articles_read; ?></span>
        </div>
    </div>

    <button class="back-btn" onclick="goBackToMainPage()">
        <i class='bx bx-arrow-back'></i> Back
    </button>

</div>


        <?php include '15footer.php'; ?>


</body>
</html>