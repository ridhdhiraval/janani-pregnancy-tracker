<?php
// PHP Section: Define dynamic data variables
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/lib/auth.php';
start_secure_session();

$estimated_due_date = "21/07/2026";
$length_of_pregnancy = "280 days (40+0)";

$user = current_user();
if ($user) {
    $stmt = $pdo->prepare('SELECT id, edd FROM pregnancies WHERE user_id = ? AND status = "active" ORDER BY created_at DESC LIMIT 1');
    $stmt->execute([$user['id']]);
    $p = $stmt->fetch();
    if ($p && !empty($p['edd'])) {
        $dueDate = DateTimeImmutable::createFromFormat('Y-m-d', $p['edd']);
        if ($dueDate) {
            $estimated_due_date = $dueDate->format('d/m/Y');
        }
    }

    $lenStmt = $pdo->prepare('SELECT value FROM settings WHERE user_id = ? AND `key` = ? LIMIT 1');
    $lenStmt->execute([$user['id'], 'pregnancy_length_days']);
    $row = $lenStmt->fetch();
    $days = 280;
    if ($row && is_numeric($row['value'])) {
        $days = max(250, min(300, (int)$row['value']));
    }
    $w = intdiv($days, 7);
    $d = $days % 7;
    $length_of_pregnancy = sprintf('%d days (%d+%d)', $days, $w, $d);
}

// Define target files for navigation
$target_edd_file = "edit_edd_date.php";
$target_length_file = "edit_pregnancy_length.php"; // Though we use a modal, keeping the variable
$back_link = "7settings.php";

// Array defining the options for the Length of pregnancy modal
// Value: Actual value to be stored/displayed
// Label: Display text in the modal
$length_options = [
    ['value' => '279 days (39+6)', 'label' => '279 days (39+6)'],
    ['value' => '280 days (40+0)', 'label' => '280 days (40+0)'],
    ['value' => '281 days (40+1)', 'label' => '281 days (40+1)'],
    ['value' => '282 days (40+2)', 'label' => '282 days (40+2)'],
    ['value' => '283 days (40+3)', 'label' => '283 days (40+3)'],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My pregnancy</title>
    
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
            z-index: 10;
        }

        /* --- Content Wrapper for Alignment, Background, and Shadow --- */
        .content-wrapper {
            max-width: 1200px; 
            margin: 20px auto; 
            background-color: white; 
            padding: 0 30px; 
            border-bottom: 1px solid #e0d9cd;
            min-height: 400px; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.05); 
        }

        /* --- "Current pregnancy" Section Header --- */
        .current-pregnancy-section h2 {
            font-size: 20px; 
            font-weight: bold;
            color: #4b4b4b;
            padding: 25px 0 10px; /* Reduced bottom padding */
            margin: 0;
        }

        /* --- Data Table Container --- */
        .data-table-container {
            padding-bottom: 25px; 
        }

        .data-table-container table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table-container tr {
            border-bottom: 1px solid #e0d9cd; 
            cursor: pointer; 
            transition: background-color 0.1s;
        }

        .data-table-container tr:hover {
            background-color: #fcfcfc; 
        }
        
        .data-table-container tr:last-child { 
            border-bottom: none; 
        }

        .data-table-container td {
            padding: 20px 0; 
            font-size: 18px; 
            color: #4b4b4b;
        }

        .data-table-container td.label {
            text-align: left;
            font-weight: normal;
        }

        .data-table-container td.value {
            text-align: right;
            font-weight: 500;
            font-size: 20px; 
        }

        /* ================================================= */
        /* --- Length Selection Modal Styles (Based on Image) --- */
        /* ================================================= */

        .modal-overlay {
            display: none; /* Hidden by default */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black overlay */
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .length-modal {
            background-color: white;
            width: 90%;
            max-width: 300px; /* Constrain size to mimic mobile feel */
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            padding: 20px 0; /* Padding inside the modal */
        }

        .modal-header-text {
            font-size: 18px;
            font-weight: bold;
            color: #4b4b4b;
            padding: 0 20px 15px;
            border-bottom: 1px solid #e0d9cd;
        }

        .modal-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .modal-list li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            font-size: 16px;
            color: #4b4b4b;
            cursor: pointer;
            transition: background-color 0.1s;
        }

        .modal-list li:hover {
            background-color: #f7f7f7;
        }

        /* Custom Radio Button Styling */
        .radio-button {
            display: block;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid #ccc;
            margin-left: 10px;
            position: relative;
        }

        .radio-button.selected {
            border-color: #e65252; /* Red border for selected */
        }

        .radio-button.selected::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #e65252; /* Red fill for selected */
        }
    </style>
</head>
<body>

<div class="pregnancy-app-container">
    
    <div class="header-bar-wrapper">
        <header class="app-header">
            <div class="header-content">
                <a href="<?php echo htmlspecialchars($back_link); ?>" class="back-arrow">&#x2329;</a> 
                <h1>My pregnancy</h1>
            </div>
        </header>
    </div>

    <div class="content-wrapper">
        <section class="current-pregnancy-section">
            <h2>Current pregnancy</h2>

            <div class="data-table-container">
                <table>
                    <tr id="row-edd" data-href="<?php echo htmlspecialchars($target_edd_file); ?>">
                        <td class="label">Estimated Due Date</td>
                        <td class="value" id="eddValue"><?php echo htmlspecialchars($estimated_due_date); ?></td>
                    </tr>
                    
                    <tr id="row-length">
                        <td class="label">Length of pregnancy</td>
                        <td class="value" id="lengthValue"><?php echo htmlspecialchars($length_of_pregnancy); ?></td>
                    </tr>
                </table>
            </div>
        </section>
    </div>
</div>

<div class="modal-overlay" id="lengthModalOverlay">
    <div class="length-modal">
        <div class="modal-header-text">Length of pregnancy</div>
        
        <ul class="modal-list" id="lengthOptionList">
            <?php foreach ($length_options as $option): ?>
                <li data-value="<?php echo htmlspecialchars($option['value']); ?>">
                    <span><?php echo htmlspecialchars($option['label']); ?></span>
                    <span class="radio-button"></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const eddRow = document.getElementById('row-edd');
        const lengthRow = document.getElementById('row-length');
        const lengthModalOverlay = document.getElementById('lengthModalOverlay');
        const lengthOptionList = document.getElementById('lengthOptionList');
        const lengthValueDisplay = document.getElementById('lengthValue');

        // Initial selected value from PHP
        let currentLength = lengthValueDisplay.textContent.trim();

        // Function to update the visual selection in the modal
        function updateModalSelection(selectedValue) {
            lengthOptionList.querySelectorAll('li').forEach(li => {
                const radio = li.querySelector('.radio-button');
                if (li.getAttribute('data-value') === selectedValue) {
                    radio.classList.add('selected');
                } else {
                    radio.classList.remove('selected');
                }
            });
        }

        // --- Event Listeners ---

        // 1. Estimated Due Date Row Navigation (FIXED)
        eddRow.addEventListener('click', function() {
            const targetUrl = this.getAttribute('data-href');
            console.log("Navigating to EDD edit page: " + targetUrl);
            window.location.href = targetUrl; // <-- THIS LINE WAS MISSING THE REDIRECT
        });


        // 2. Click on Length of pregnancy row to OPEN MODAL
        lengthRow.addEventListener('click', function() {
            // Update the modal with the current value before opening
            currentLength = lengthValueDisplay.textContent.trim();
            updateModalSelection(currentLength);
            
            lengthModalOverlay.style.display = 'flex';
            document.body.style.overflow = 'hidden'; // Prevent scrolling body while modal is open
        });

        // 3. Click on an option in the modal to SELECT and CLOSE
        lengthOptionList.addEventListener('click', function(event) {
            let targetLi = event.target.closest('li');
            if (targetLi) {
                const newValue = targetLi.getAttribute('data-value');
                
                // 1. Update the display value on the main page
                lengthValueDisplay.textContent = newValue;
                
                // 2. Hide the modal
                lengthModalOverlay.style.display = 'none';
                document.body.style.overflow = ''; // Restore body scrolling
                
                // 3. Update visual selection
                updateModalSelection(newValue); 
            }
        });
        
        // 4. Click on the overlay to close the modal (Optional but common)
        lengthModalOverlay.addEventListener('click', function(event) {
            if (event.target === lengthModalOverlay) {
                lengthModalOverlay.style.display = 'none';
                document.body.style.overflow = '';
            }
        });
        
        // 5. Initial check for which radio button should be selected on load
        updateModalSelection(currentLength);
    });
</script>
    <?php include '15footer.php'; ?>

</body>
</html>