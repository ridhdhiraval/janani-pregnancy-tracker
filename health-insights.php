<?php
// PHP Section: Define dynamic data and navigation links
$page_title = "Baby's Health Insights"; 
$back_link = "6child.php"; // Back link to the main profile/dashboard page

// --- DYNAMIC DATA SIMULATION ---
// Assuming the user is currently in the 28th week of pregnancy for this example
$current_week = 28; 

// Nutritional data for a specific week (In a real app, this would come from a database)
$weekly_insight = [
    "week" => $current_week,
    "focus_nutrient" => "Iron & DHA",
    "development_note" => "This week, the baby is developing billions of brain connections and storing vital nutrients. Iron is crucial for red blood cell production, and DHA supports optimal brain and eye development.",
    "top_foods" => [
        ["name" => "Iron-rich foods", "tip" => "Include lean red meat, lentils, spinach, and fortified cereals. Pair with Vitamin C (like citrus fruits) to boost absorption."],
        ["name" => "DHA sources", "tip" => "Eat fatty fish (like salmon), walnuts, chia seeds, and eggs, or consider a high-quality prenatal supplement."],
        ["name" => "Folic Acid", "tip" => "Continue consuming Folic Acid (Folate) found in leafy greens and beans; it’s essential for rapid cell division."],
    ],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <style>
        /* Base Theme Styles (Consistent) */
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f7f3ed; /* Light, warm background */
            color: #4b4b4b;
        }
        
        /* Header Bar */
        .app-header {
            position: sticky;
            top: 0;
            width: 100%;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 1000;
        }

        .back-arrow-btn {
            font-size: 28px; 
            text-decoration: none;
            color: #4b4b4b;
            cursor: pointer;
            padding: 0 5px;
            line-height: 1;
        }
        
        .header-title {
            font-size: 20px;
            font-weight: 600;
            flex-grow: 1;
            text-align: center;
            margin-left: -28px; 
        }

        /* Main Content Area */
        .content-area {
            padding: 20px;
            max-width: 750px; 
            margin: 0 auto;
        }
        
        /* --- Weekly Focus Card --- */
        .weekly-focus-card {
            background-color: #a8dadc; /* Blueish theme color */
            color: #333;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 30px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .weekly-focus-card h2 {
            font-size: 24px;
            font-weight: bold;
            margin: 0 0 5px;
        }
        
        .weekly-focus-card p {
            font-size: 15px;
            margin-top: 5px;
            font-weight: 500;
        }
        
        .nutrient-highlight {
            font-size: 20px;
            font-weight: bold;
            color: #e69999; /* Pinkish accent color */
            display: block;
            margin: 10px 0;
        }

        /* --- Tips Section --- */
        .tips-section {
            padding: 0 5px;
        }
        
        .tips-section h3 {
            font-size: 18px;
            font-weight: 600;
            color: #4b4b4b;
            margin-bottom: 20px;
            border-bottom: 2px solid #ccc;
            padding-bottom: 5px;
        }

        .tip-card {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 15px;
            border-left: 5px solid #2a9d8f; /* Greenish theme color */
        }
        
        .tip-card h4 {
            font-size: 16px;
            color: #e69999; /* Pinkish accent color */
            margin: 0 0 5px;
        }
        
        .tip-card p {
            font-size: 14px;
            color: #666;
            margin: 0;
        }
        
        .disclaimer {
            font-size: 12px;
            color: #999;
            text-align: center;
            margin-top: 30px;
            padding: 10px;
        }
    </style>
</head>
<body>

    <header class="app-header">
        <a href="<?php echo htmlspecialchars($back_link); ?>" id="backButton" class="back-arrow-btn">&#x2329;</a> 
        <div class="header-title"><?php echo htmlspecialchars($page_title); ?></div>
        <div></div> 
    </header>

    <div class="content-area">
        
        <div class="weekly-focus-card">
            <h2>Week <?php echo htmlspecialchars($weekly_insight['week']); ?> Focus</h2>
            <span class="nutrient-highlight"><?php echo htmlspecialchars($weekly_insight['focus_nutrient']); ?></span>
            <p>
                <?php echo htmlspecialchars($weekly_insight['development_note']); ?>
            </p>
        </div>

        <div class="tips-section">
            <h3>Dietary Tips and Sources</h3>
            
            <?php foreach ($weekly_insight['top_foods'] as $food): ?>
                <div class="tip-card">
                    <h4><?php echo htmlspecialchars($food['name']); ?></h4>
                    <p><?php echo htmlspecialchars($food['tip']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="disclaimer">
            *Disclaimer: Always consult with your doctor or a registered dietitian before making significant changes to your diet or starting any new supplements.
        </div>
        
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const backButton = document.getElementById('backButton'); 
            
            // --- GUARANTEED BACK BUTTON LOGIC ---
            backButton.addEventListener('click', function(e) {
                e.preventDefault(); 
                
                if (window.history.length > 1) {
                    window.history.back();
                } else {
                    // Fallback to the defined PHP link (6child.php)
                    window.location.href = backButton.href; 
                }
            });
        });
    </script>

</body>
</html>