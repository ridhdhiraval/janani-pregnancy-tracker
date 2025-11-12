<?php
// PHP data for weeks and their labels/icons
$weeks = [];

// Week 1-3 special entry: Using a placeholder URL for seed.jpg
// IMPORTANT: Replace 'YOUR_SEED_IMAGE_URL/seed.jpg' with the actual path to your image
$seed_icon = '<img width="60" height="60" src="images/seed.jpg" alt="Seed Icon"/>'; 
$weeks[] = ['label' => 'w 1-3', 'icon' => $seed_icon]; 

// Fill remaining weeks from w 4 to w 28 with '?' icon
for ($i = 4; $i <= 28; $i++) {
    $weeks[] = ['label' => 'w ' . $i, 'icon' => '<span>?</span>'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Weekly Development Overview</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root {
    --primary-color: #5d5c61; /* Dark Gray */
    --accent-color: #b0c4de; /* Light Slate Gray */
    --soft-bg: #f8f8f8; 
    --white: #ffffff;
    --text: #333;
    --week-circle-bg: #ffe0e6; /* Soft pink from image */
    --week-circle-text: #333;
    --border-color: #eee;
}
body {
    font-family: 'Inter', sans-serif;
    margin: 0;
    background: var(--soft-bg);
    color: var(--text);
    padding: 0;
    line-height: 1.6;
}
.app-container {
    width: 90%;
    max-width: 1000px; 
    margin: 40px auto;
    padding: 40px;
    background: var(--white);
    min-height: 80vh;
    box-shadow: 0 0 25px rgba(0,0,0,0.05);
    border-radius: 12px;
    position: relative;
    overflow: hidden; 
}
.top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0 20px;
    border-bottom: 1px solid var(--border-color); 
    margin-bottom: 30px;
}
.title {
    font-size: 24px; 
    font-weight: 700;
    margin: 0;
    color: var(--text);
    flex-grow: 1;
    text-align: center;
}
.back-arrow {
    font-weight: 700;
    font-size: 30px;
    color: var(--text);
    text-decoration: none;
    padding-right: 15px;
}

/* Weekly Grid Specific Styles */
.weekly-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 25px; 
    padding: 20px 0;
    text-align: center;
}

.week-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-decoration: none;
    color: var(--week-circle-text);
    transition: transform 0.2s ease-in-out;
}

.week-item:hover {
    transform: translateY(-5px);
}

.week-circle {
    width: 100px; 
    height: 100px;
    background-color: var(--week-circle-bg);
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 40px; 
    font-weight: 600;
    color: var(--text);
    margin-bottom: 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.08); 
    /* Important for image inside circle */
    overflow: hidden;
}

.week-circle img {
    /* Ensure the image scales neatly within the circle */
    width: 100%; 
    height: 100%;
    object-fit: cover; /* Crops the image to cover the entire circle area */
}

.week-circle span {
    /* Style for the '?' text */
    font-size: 40px;
    font-weight: 600;
    line-height: 1;
}

.week-label {
    font-size: 16px;
    font-weight: 500;
    color: var(--text);
}

/* Blur effect at the bottom for scrolling indication */
.blur-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 100px;
    background: linear-gradient(to top, var(--white), rgba(255,255,255,0));
    pointer-events: none;
}
</style>
</head>
<body>

<div class="app-container">
    
    <div class="top-bar">
        <a href="javascript:history.back()" class="back-arrow">&#x2190;</a> 
        <h1 class="title">Your baby's development</h1>
        <div></div> 
    </div>

    <div class="weekly-grid">
        <?php foreach ($weeks as $week): ?>
            <a href="#" class="week-item">
                <div class="week-circle">
                    <?php echo $week['icon']; ?>
                </div>
                <div class="week-label"><?php echo $week['label']; ?></div>
            </a>
        <?php endforeach; ?>
    </div>

    
    <div class="blur-overlay"></div>

</div>

</body>
</html>