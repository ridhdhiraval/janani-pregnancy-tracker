<?php
// PHP Section: Define dynamic data and navigation links
$page_title = "Delivery Preparation"; 
$back_link = "6child.php"; 

// Delivery Prep Data
$preparation_items = [
    "hospital_bag" => [
        "title" => "Hospital Bag Checklist 🎒",
        "description" => "What to pack for Mum, Baby, and Partner for your hospital stay.",
        "items" => [
            "For Mum" => [
                "Comfortable clothes and toiletries", 
                "Maternity pads and large underwear", 
                "Nursing bras/pads (if breastfeeding)",
                "Photo ID, insurance papers, birth plan"
            ],
            "For Baby" => [
                "Newborn diapers and wipes",
                "Two or three outfits (onesies)",
                "Going-home outfit and blanket",
                "Car seat (installed and checked)"
            ],
            "For Partner" => [
                "Snacks, water bottle, and change of clothes",
                "Phone charger and camera/video recorder",
                "Pillow and light blanket"
            ]
        ]
    ],
    "packing_list" => [
        "title" => "Home Packing List 🧺",
        "description" => "Items to stock up on at home for the first few weeks.",
        "items" => [
            "Nursery Items" => ["Bassinet/Crib ready to use", "Changing supplies and bin", "Baby monitor set up"],
            "Feeding Supplies" => ["Bottles and sterilizer (if formula feeding)", "Formula (if needed)", "Breast pump and storage bags (if breastfeeding)"],
            "Essential Comfort" => ["Plenty of frozen meals/easy snacks", "Comfortable nursing/lounge wear for Mum", "Pain relief medication (consult doctor)"]
        ]
    ],
    "family_reminders" => [
        "title" => "Family & Partner Reminders 👨‍👩‍👧",
        "description" => "Important tasks and contact details for your support system.",
        "items" => [
            "Key Contacts" => ["Doctor/Midwife contact numbers saved", "Emergency contacts list printed", "Neighbor/Friend contact for pets/older kids"],
            "Logistics" => ["Car full of gas", "Route to hospital confirmed (with backup route)", "Hospital registration completed"],
            "Post-Delivery" => ["Plan for announcing the birth", "Arrangements for visitors (keep it limited initially)", "Schedule time for skin-to-skin bonding"]
        ]
    ]
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
            background-color: #f7f3ed;
            color: #4b4b4b;
        }
        
        /* Header Bar (Unchanged) */
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

        /* Main Content Area (Unchanged) */
        .content-area {
            padding: 20px;
            max-width: 700px; 
            margin: 0 auto;
        }

        /* --- Collapsible/Accordion Styles (Unchanged) --- */
        .accordion-item {
            background-color: white;
            border-radius: 12px;
            margin-bottom: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .accordion-header {
            background-color: #a8dadc; 
            color: #333;
            padding: 18px 20px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 18px;
            font-weight: bold;
            transition: background-color 0.2s;
        }

        .accordion-header:hover {
            background-color: #92c6c8;
        }
        
        .header-content h3 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        
        .header-content p {
            margin: 3px 0 0;
            font-size: 13px;
            font-weight: normal;
        }

        .accordion-icon {
            font-size: 20px;
            transition: transform 0.3s;
        }

        .accordion-content {
            padding: 0 20px;
            max-height: 0; 
            overflow: hidden;
            transition: max-height 0.4s ease-out, padding 0.4s ease-out;
            background-color: #ffffff;
        }
        
        .accordion-item.active .accordion-header {
            background-color: #2a9d8f; 
            color: white;
        }

        .accordion-item.active .accordion-icon {
            transform: rotate(180deg);
        }

        .accordion-item.active .accordion-content {
            max-height: 1000px; /* Increased height for safety */
            padding: 15px 20px 20px;
        }
        
        /* --- CHECKLIST STYLES (NEW/UPDATED) --- */
        .checklist-group {
            margin-bottom: 20px;
        }
        
        .checklist-group h4 {
            font-size: 16px;
            color: #e69999;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 5px;
            margin: 0 0 10px;
        }

        .checklist-group ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .checklist-item {
            padding: 8px 0;
            font-size: 14px;
            border-bottom: 1px dotted #eee;
            position: relative;
            cursor: pointer; /* Clickable li element */
            display: flex;
            align-items: flex-start;
        }
        
        /* Hide the default checkbox */
        .checklist-item input[type="checkbox"] {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }
        
        /* Custom checkmark appearance */
        .checkmark-label {
            display: block;
            position: relative;
            padding-left: 25px; /* Space for the custom box */
            line-height: 1.5;
            color: #4b4b4b; /* Default text color */
        }

        .checkmark-label:before {
            content: '';
            position: absolute;
            left: 0;
            top: 2px; /* Adjust alignment */
            width: 16px;
            height: 16px;
            border: 2px solid #2a9d8f; /* Green border */
            border-radius: 4px;
            background-color: #fff;
            transition: all 0.2s;
        }

        /* Checked state styles */
        .checklist-item input:checked + .checkmark-label:before {
            background-color: #2a9d8f; /* Green fill when checked */
            border-color: #2a9d8f;
        }
        
        .checklist-item input:checked + .checkmark-label {
            text-decoration: line-through;
            color: #999; /* Grey out text when checked */
        }

        .checkmark-label:after {
            content: '✔'; /* Tick mark symbol */
            position: absolute;
            left: 2px;
            top: 0px;
            font-size: 14px;
            color: white;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .checklist-item input:checked + .checkmark-label:after {
            opacity: 1; /* Show tick mark when checked */
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
        
        <?php foreach ($preparation_items as $key => $section): ?>
            <div class="accordion-item" id="prep-<?php echo htmlspecialchars($key); ?>">
                
                <div class="accordion-header">
                    <div class="header-content">
                        <h3><?php echo htmlspecialchars($section['title']); ?></h3>
                        <p><?php echo htmlspecialchars($section['description']); ?></p>
                    </div>
                    <span class="accordion-icon">&#x25BC;</span>
                </div>
                
                <div class="accordion-content">
                    <?php foreach ($section['items'] as $group_title => $items): ?>
                        <div class="checklist-group">
                            <h4><?php echo htmlspecialchars($group_title); ?></h4>
                            <ul>
                                <?php foreach ($items as $index => $item): 
                                    // Unique ID for each item: groupkey-itemindex
                                    $item_id = $key . '-' . $index;
                                ?>
                                    <li class="checklist-item">
                                        <input type="checkbox" id="check-<?php echo htmlspecialchars($item_id); ?>" data-item-id="<?php echo htmlspecialchars($item_id); ?>">
                                        <label for="check-<?php echo htmlspecialchars($item_id); ?>" class="checkmark-label">
                                            <?php echo htmlspecialchars($item); ?>
                                        </label>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endforeach; ?>
                </div>
                
            </div>
        <?php endforeach; ?>

    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const backButton = document.getElementById('backButton'); 
            const accordionHeaders = document.querySelectorAll('.accordion-header');
            
            // --- GUARANTEED BACK BUTTON LOGIC ---
            backButton.addEventListener('click', function(e) {
                e.preventDefault(); 
                
                if (window.history.length > 1) {
                    window.history.back();
                } else {
                    window.location.href = backButton.href; 
                }
            });
            // ------------------------------------
            
            // --- ACCORDION/COLLAPSIBLE LOGIC ---
            accordionHeaders.forEach(header => {
                header.addEventListener('click', () => {
                    const item = header.closest('.accordion-item');
                    item.classList.toggle('active');
                });
            });
            // ------------------------------------

            // --- CHECKLIST PERSISTENCE LOGIC ---
            
            // 1. Check if an item was previously checked
            function loadChecklistState() {
                document.querySelectorAll('.checklist-item input[type="checkbox"]').forEach(checkbox => {
                    const itemId = checkbox.getAttribute('data-item-id');
                    // Get state from browser's local storage
                    const isChecked = localStorage.getItem('checklist_' + itemId) === 'true';
                    if (isChecked) {
                        checkbox.checked = true;
                    }
                });
            }

            // 2. Save the state when a checkbox is clicked
            document.querySelectorAll('.checklist-item input[type="checkbox"]').forEach(checkbox => {
                checkbox.addEventListener('change', (e) => {
                    const itemId = e.target.getAttribute('data-item-id');
                    // Save the new state to local storage
                    localStorage.setItem('checklist_' + itemId, e.target.checked);
                });
            });

            // Load state when the page loads
            loadChecklistState();
        });
    </script>

</body>
</html>