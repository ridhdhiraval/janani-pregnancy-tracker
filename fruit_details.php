<?php
// PHP Section: Define dynamic data for the weekly fruit/baby size
$page_title = "Baby Size Details"; 
$back_link = "5index.php"; 
$overview_link = "weekly_overview.php";

// Data for the current week (Poppy Seed example)
$baby_data = [
    "size_name" => "poppy seed",
    "week_range" => "1-3",
    "days_to_go" => 277,
    "progress_percent" => 1,
];

function get_fruit_image_src($size_name) {
    if ($size_name === "poppy seed") {
        return "images/seed.jpg";  // ✅ using your image here
    }
    return "images/default_fruit.png";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($page_title); ?></title>
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<style>
    /* --- Color Palette --- */
    :root {
        --light-pink-bg: #f5efe8;
        --dark-pink-circle: #e3b0ba;
        --button-pink: #ed8a95;
        --button-border: #666;
        --text-dark: #333;
        --text-light: #666;
    }

    body {
        margin: 0;
        padding: 0;
        font-family: "Segoe UI", Roboto, sans-serif;
        background-color: var(--light-pink-bg);
        color: var(--text-dark);
        width: 100%;
        height: 100vh;
        overflow-x: hidden;
    }

    .container {
        display: flex;
        flex-direction: column;
        height: 100vh;
        width: 100%;
        background-color: white;
        position: relative;
    }

    /* --- Floating Close Button --- */
    .close-button {
        position: fixed;
        top: 20px;
        right: 25px;
        font-size: 2.2rem;
        color: var(--text-dark);
        cursor: pointer;
        z-index: 100;
        background-color: white;
        border-radius: 50%;
        width: 45px;
        height: 45px;
        display: flex;
        justify-content: center;
        align-items: center;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .close-button:hover {
        background-color: var(--button-pink);
        color: white;
        transform: scale(1.1);
    }

    /* --- Main Area --- */
    .content-area {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-around;
        flex-grow: 1;
        padding: 60px 80px;
        box-sizing: border-box;
    }

    /* --- Left Section (Illustration) --- */
    .illustration-circle {
        position: relative;
        width: 350px;
        height: 350px;
        background-color: var(--dark-pink-circle);
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .progress-percent {
        position: absolute;
        top: 15px;
        right: 15px;
        width: 60px;
        height: 60px;
        background-color: var(--button-pink);
        color: white;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 1.1rem;
        font-weight: bold;
    }

    .baby-illustration {
        width: 80%;
        height: 80%;
        border-radius: 50%;
        object-fit: cover;
        animation: slowBounce 2.5s ease-in-out infinite; /* gentle animation */
    }

    @keyframes slowBounce {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
    }

    /* --- Right Section (Text Info) --- */
    .details-section {
        flex: 1;
        padding-left: 60px;
        max-width: 600px;
    }

    .size-text {
        font-size: 2rem;
        margin-bottom: 40px;
        line-height: 1.4;
    }

    .measurement-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 25px;
        margin-bottom: 40px;
    }

    .measurement-box {
        border: 1px solid #ccc;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        background-color: #fafafa;
    }

    .measurement-label {
        font-size: 0.9rem;
        color: var(--text-light);
        text-transform: uppercase;
        font-weight: 600;
    }

    .measurement-value {
        font-size: 1.3rem;
        font-weight: bold;
        margin-top: 8px;
    }

    /* --- Buttons --- */
    .button-group {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }

    .action-button {
        flex: 1;
        padding: 15px;
        border-radius: 30px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        text-transform: uppercase;
        border: none;
        text-decoration: none;
        text-align: center;
    }

    .share-button {
        background-color: var(--button-pink);
        color: white;
    }

    .overview-button {
        background-color: white;
        border: 1px solid var(--button-border);
        color: var(--text-dark);
    }

    .disclaimer {
        font-size: 0.8rem;
        color: var(--text-light);
        font-style: italic;
    }
</style>

<script>
    function closeView() {
        window.location.href = '5index.php';
    }
    function shareDetails() {
        alert('Opening share dialog for baby size details.');
    }
</script>
</head>

<body>
<div class="container">

    <!-- Floating close button -->
    <a href="javascript:void(0)" onclick="closeView()" class="close-button" title="Close">
        <i class='bx bx-x'></i>
    </a>

    <div class="content-area">

        <!-- Left side: Illustration -->
        <div class="illustration-circle">
            <div class="progress-percent"><?php echo htmlspecialchars($baby_data['progress_percent']); ?>%</div>
            <img src="<?php echo get_fruit_image_src($baby_data['size_name']); ?>" alt="Baby Illustration" class="baby-illustration">
        </div>

        <!-- Right side: Text info -->
        <div class="details-section">
            <h2 class="size-text">
                Your baby is now the size of a <br>
                <strong><?php echo strtoupper(htmlspecialchars($baby_data['size_name'])); ?></strong>
            </h2>

            <div class="measurement-grid">
                <div class="measurement-box">
                    <div class="measurement-label">Week</div>
                    <div class="measurement-value"><?php echo htmlspecialchars($baby_data['week_range']); ?></div>
                </div>
                <div class="measurement-box">
                    <div class="measurement-label">Days to Go</div>
                    <div class="measurement-value"><?php echo htmlspecialchars($baby_data['days_to_go']); ?></div>
                </div>
            </div>

            <div class="button-group">
                <a href="<?php echo htmlspecialchars($overview_link); ?>" class="action-button overview-button">Overview</a>
            </div>

            <p class="disclaimer">
                *The fruit/vegetable sizes are approximate and for visualization purposes only.
            </p>
        </div>
    </div>
</div>
    <?php include '15footer.php'; ?>

</body>
</html>
