<?php
// =======================================================================
// Configuration & Data
// =======================================================================

// 1. Define Tool Categories/Filters
$all_filters = ['All', 'Health', 'Planning', 'Birth'];
$active_filter = $_GET['filter'] ?? 'All';

// 2. Consolidated Tools List with categories and links
$tools_data = [
    [
        'title' => 'Contraction timer',
        'description' => 'Track your contractions and get to know when it\'s time to head to the delivery room.',
        'link_file' => 'tool-contraction-timer.php', 
        'categories' => ['Birth'], 
        'icon_html' => '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#ff91a4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
    ],
    [
        'title' => 'Vaccination',
        'description' => 'Update your child\'s vaccination records by adding the received vaccines',
        'link_file' => 'tool-vaccination.php', 
        'categories' => ['Health'], 
        'icon_html' => '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#ff91a4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="12" x2="6" y2="12"/><line x1="12" y1="18" x2="12" y2="6"/><path d="M21 21L16 16"/><path d="M14 10L10 14"/><path d="M16 8L8 16"/></svg>',
    ],
    [
        'title' => 'Wellness',
        'description' => 'Safe workouts and yoga sessions tailored to pregnancy.',
        'link_file' => 'tool-wellness.php', 
        'categories' => ['Health'], 
        'icon_html' => '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#ff91a4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2C10.5 2 9.5 3.5 9.5 5.5C9.5 7.5 11.5 8 12 8C12.5 8 14.5 7.5 14.5 5.5C14.5 3.5 13.5 2 12 2Z"/><path d="M12 8V22"/><path d="M12 22L7 17"/><path d="M12 22L17 17"/><path d="M7 17L5 15"/><path d="M17 17L19 15"/></svg>',
    ],
    [
        'title' => 'Checklist',
        'description' => 'A comprehensive list with checkboxes, helping you to prepare',
        'link_file' => 'checklist.php', 
        'categories' => ['Planning', 'Birth'], 
        'icon_html' => '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#ff91a4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>',
    ],
];

// 3. Filter Logic
$filtered_tools = [];
if ($active_filter === 'All') {
    $filtered_tools = $tools_data;
} else {
    foreach ($tools_data as $tool) {
        if (in_array($active_filter, $tool['categories'])) {
            $filtered_tools[] = $tool;
        }
    }
}

// Featured checklist items
$featured_checklist_items = [
    "Prima visita dall'ostetrica", 
    "Primi ultrasuoni",          
    "Assicurazione della gravidanza", 
    "Compra i primi pantaloni premaman", 
    "Compra un..." 
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tools</title>
    <style>
        :root {
            --soft-pink: #ffe6eb;
            --primary-pink: #ff91a4;
            --light-bg: #fcf8f6; 
            --white-bg: #ffffff;
            --text-dark: #333333;
            --text-grey: #6a6a6a;
            --border-light: #eeeeee;
        }

        body {
            font-family: 'Segoe UI', Roboto, Arial, sans-serif;
            background-color: var(--light-bg);
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 30px auto;
            background-color: var(--white-bg);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            padding-bottom: 50px;
        }

        /* Back button to 5index.php */
        .back-button {
            display: inline-block;
            margin: 20px 40px 10px;
            font-size: 16px;
            color: var(--primary-pink);
            text-decoration: none;
            font-weight: 500;
        }

        .header {
            padding: 0 40px 20px;
            font-size: 28px;
            font-weight: 600;
            color: var(--text-dark);
        }

        /* --- Featured Checklist --- */
        .featured-checklist-container {
            background-color: var(--light-bg);
            padding: 30px;
            margin: 20px 40px;
            border-radius: 15px;
            overflow: hidden;
            position: relative; 
            cursor: pointer; 
        }

        .featured-checklist-header {
            font-weight: 600;
            font-size: 13px;
            color: var(--text-grey);
            margin-bottom: 5px;
        }

        .featured-checklist-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 15px;
        }

        .featured-checklist-text {
            font-size: 16px;
            color: var(--text-grey);
            line-height: 1.5;
            margin-bottom: 25px;
        }

        .checklist-graphic {
            position: absolute;
            top: 0;
            right: 0;
            width: 60%;
            height: 100%;
            background-color: #e6f7f5; 
            border-radius: 0 15px 15px 0;
            overflow: hidden;
        }

        .checklist-overlay {
            position: absolute;
            top: 10%;
            right: 10%;
            width: 50%;
            padding: 15px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.85); 
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transform: rotate(5deg); 
        }

        .checklist-overlay-percent {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid var(--border-light);
        }
        
        .checklist-item {
            display: flex;
            align-items: center;
            font-size: 12px;
            color: var(--text-dark);
            margin-bottom: 6px;
        }

        .checklist-item-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: var(--primary-pink);
            margin-right: 8px;
        }

        /* --- Tool Filters --- */
        .tool-filters {
            display: flex;
            gap: 15px;
            padding: 0 40px;
            margin-bottom: 30px;
            overflow-x: auto; 
            white-space: nowrap;
        }

        .filter-btn {
            background-color: var(--white-bg);
            color: var(--text-grey);
            border: 1px solid var(--border-light);
            padding: 10px 20px;
            border-radius: 20px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none; 
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .filter-btn.active {
            background-color: var(--primary-pink);
            color: var(--white-bg);
            border-color: var(--primary-pink);
            font-weight: 600;
        }

        /* --- Tool Cards --- */
        .tool-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 0 40px 50px;
        }

        .tool-card-link {
            flex: 1 1 calc(50% - 20px);
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .tool-card {
            display: flex;
            align-items: center;
            padding: 20px;
            border: 1px solid var(--border-light);
            border-radius: 12px;
            background-color: var(--white-bg);
            transition: box-shadow 0.2s;
        }

        .tool-card:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .tool-icon-container {
            width: 70px;
            height: 70px;
            background-color: var(--soft-pink);
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 20px;
            flex-shrink: 0;
        }

        .tool-info {
            flex-grow: 1;
        }

        .tool-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 5px;
        }

        .tool-description {
            font-size: 14px;
            color: var(--text-grey);
        }

        .heart-icon {
            color: var(--primary-pink);
            font-size: 24px;
            cursor: pointer;
            margin-left: 10px;
            flex-shrink: 0;
        }

        /* Responsive for smaller desktops */
        @media (max-width: 900px) {
            .tool-card-link {
                flex: 1 1 100%;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Back Button -->
    <a href="5index.php" class="back-button">&larr; Back</a>

    <div class="header">Tools</div>

    <!-- Featured Checklist -->
    <a href="tool-checklist.php" class="tool-card-link">
        <div class="featured-checklist-container">
            <div class="featured-checklist-header">FEATURED</div>
            <div class="featured-checklist-title">Checklist</div>
            <div class="featured-checklist-text">
                Pregnancy puts a lot on your plate. Use our convenient checklist tool to make sure you don't miss anything.
            </div>
            <div class="checklist-graphic">
                <div class="checklist-overlay">
                    <div class="checklist-overlay-percent">20% <span style="font-size: 12px; font-weight: 400;">della checklist completato</span></div>
                    <?php foreach ($featured_checklist_items as $item): ?>
                        <div class="checklist-item">
                            <div class="checklist-item-dot"></div>
                            <span><?= htmlspecialchars($item) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </a>

    <!-- Tool Filters -->
    <div class="tool-filters">
        <?php foreach ($all_filters as $filter_name): ?>
            <a href="?filter=<?= urlencode($filter_name) ?>" 
               class="filter-btn <?= $active_filter === $filter_name ? 'active' : '' ?>">
                <?= htmlspecialchars($filter_name) ?>
            </a>
        <?php endforeach; ?>
    </div>
    
    <!-- Tool List -->
    <div class="tool-list">
        <?php 
        if (empty($filtered_tools)): ?>
            <p style="text-align: center; color: var(--text-grey); padding: 20px;">No tools found for the "<?= htmlspecialchars($active_filter) ?>" category.</p>
        <?php endif; 

        foreach ($filtered_tools as $tool): 
        ?>
            <a href="<?= htmlspecialchars($tool['link_file']) ?>" class="tool-card-link">
                <div class="tool-card">
                    <div class="tool-icon-container">
                        <?= $tool['icon_html'] ?>
                    </div>
                    <div class="tool-info">
                        <div class="tool-title"><?= htmlspecialchars($tool['title']) ?></div>
                        <div class="tool-description"><?= htmlspecialchars($tool['description']) ?></div>
                    </div>
                    <div class="heart-icon">&#x2661;</div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</div>
    <?php include '15footer.php'; ?>

</body>
</html>
