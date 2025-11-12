<?php
// This PHP file provides a fully functional Vaccinations tracker interface.
// It includes adding vaccines, selecting doses, picking dates, and displaying saved data
// in a format similar to 1000385200.jpg.

// --- PHP Data Storage ---
// In a real application, this would be a database. We use a PHP session for persistence.
session_start();

// Initialize the list of saved vaccines if it doesn't exist
if (!isset($_SESSION['vaccinations'])) {
    // Adding a dummy record similar to 1000385200.jpg for initial display
    $_SESSION['vaccinations'] = [
        'vax-0' => [
            'name' => 'rubella',
            'doses' => [
                1 => '18/10/2025', // Example saved dose
            ]
        ]
    ];
}

$saved_vaccines = $_SESSION['vaccinations'];

// Handle AJAX-like requests to save the dose (simplification)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_dose') {
    $vax_id = $_POST['vax_id'];
    $dose_num = (int)$_POST['dose_num'];
    $date = $_POST['date'];
    $vax_name = $_POST['vax_name'];

    // If a new vaccine is being added
    if (!isset($_SESSION['vaccinations'][$vax_id])) {
        $_SESSION['vaccinations'][$vax_id] = [
            'name' => $vax_name,
            'doses' => []
        ];
    }
    
    // Save the dose date
    $_SESSION['vaccinations'][$vax_id]['doses'][$dose_num] = $date;
    
    // Redirect to prevent form resubmission and show updated data
    header('Location: tool-vaccination.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vaccinations</title>
    <style>
        /* --- Root Variables & Base Styles --- */
        :root {
            --primary-pink: #ff708e; 
            --soft-bg: #fcf8f6; 
            --text-dark: #333333;
            --text-grey: #6a6a6a;
            --plus-button-color: #6a8c6a; 
            --white-bg: #ffffff;
            --input-border: #e0e0e0;
            --dose-bg: #f0f0f0; 
        }

        body {
            font-family: 'Segoe UI', Roboto, Arial, sans-serif;
            background-color: var(--soft-bg);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            min-height: 100vh;
        }

        .app-container {
            width: 100%;
            max-width: 450px;
            background-color: var(--soft-bg);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            position: relative; 
            overflow-x: hidden;
            padding-bottom: 70px; 
        }

        /* --- Header/Top Bar --- */
        .header-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            color: var(--text-dark);
            font-size: 18px;
            font-weight: 500;
            background-color: var(--white-bg); 
            position: sticky;
            top: 0;
            z-index: 20;
        }

        .close-icon {
            font-size: 24px;
            font-weight: 300;
            cursor: pointer;
            color: var(--text-grey);
            text-decoration: none;
        }

        .header-title {
            flex-grow: 1;
            text-align: center;
            font-weight: 600;
        }
        
        .header-edit {
            color: var(--text-dark);
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
        }
        
        /* --- Profile Section (from 1000385200.jpg) --- */
        .profile-section {
            text-align: center;
            padding: 30px 20px 20px;
        }
        
        .profile-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: var(--white-bg);
            border: 1px solid var(--input-border);
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            font-weight: 500;
            color: var(--primary-pink);
        }
        
        .profile-name {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 20px;
        }
        
        /* --- Empty State Content (from 1000385197.jpg) --- */
        .empty-state {
            text-align: center;
            padding: 80px 40px 0;
            position: absolute;
            top: 40%; 
            left: 50%;
            transform: translate(-50%, -60%); 
            width: 80%;
            transition: opacity 0.3s;
            z-index: 1;
        }
        
        .empty-state.hidden {
            display: none;
        }

        /* Syringe Icon styles removed for brevity, assuming standard font/image use */
        .empty-state h1 {font-size: 24px; font-weight: 600; color: var(--text-dark); margin-bottom: 20px;}
        .empty-state p {font-size: 16px; line-height: 1.6; color: var(--text-grey); margin-bottom: 15px;}

        /* --- Vaccine List Styles (Combination of the two views) --- */
        .vaccine-list-container {
            padding: 0 20px;
        }

        .vaccine-card {
            background-color: var(--white-bg);
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            margin-bottom: 15px;
            overflow: hidden;
        }

        .vaccine-header {
            padding: 15px 20px;
            font-size: 14px;
            color: var(--text-grey);
            border-bottom: 1px solid var(--input-border);
        }
        
        .vaccine-header span {
            font-weight: 600;
            color: var(--text-dark);
            text-transform: uppercase;
            font-size: 18px;
            display: block;
        }

        .vaccine-name-display {
            font-size: 22px;
            font-weight: 600;
            color: var(--text-dark);
            text-transform: capitalize;
            margin-top: 5px;
        }

        .dose-list {
            padding: 10px 20px;
        }
        
        .dose-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            font-size: 16px;
            color: var(--text-dark);
            border-bottom: 1px dashed var(--input-border);
        }
        
        .dose-item:last-child {
            border-bottom: none;
        }
        
        .dose-label {
            font-weight: 500;
            color: var(--text-grey);
            width: 30%;
        }
        
        .dose-date {
            font-weight: 600;
            text-align: right;
            width: 70%;
        }

        /* --- Dose Selector Bar (Used for pending vaccines) --- */
        .vaccine-item-row {
            border-bottom: 1px solid var(--input-border);
            cursor: pointer;
            background-color: var(--white-bg);
        }
        
        .vaccine-name-toggle {
            padding: 15px 20px;
            font-size: 18px;
            font-weight: 500;
            color: var(--text-dark);
        }
        
        .dose-selector-container {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-in-out;
            padding: 0 20px;
            background-color: var(--white-bg);
        }
        
        .dose-selector-container.open {
            max-height: 100px; 
            padding: 10px 20px 20px;
        }

        .dose-buttons {
            display: flex;
            gap: 10px;
        }
        
        .dose-button {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--dose-bg);
            color: var(--text-dark);
            font-weight: 600;
            border: 2px solid transparent;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            transition: background-color 0.2s, color 0.2s;
        }
        
        .dose-button.given {
            background-color: var(--primary-pink);
            color: var(--white-bg);
        }
        
        /* --- Floating Plus Button and Input Bar --- */
        .fab-button {
            position: fixed;
            bottom: 30px;
            background-color: var(--plus-button-color);
            color: var(--white-bg);
            border: none;
            border-radius: 50%;
            width: 56px;
            height: 56px;
            font-size: 30px;
            line-height: 56px;
            text-align: center;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 10;
            right: calc(50% - 225px + 30px); 
            transition: transform 0.3s ease-in-out;
        }
        
        @media (max-width: 450px) {
            .fab-button {
                right: 30px; 
            }
        }
        
        .fab-button.hidden {
            transform: translateY(100px); 
            pointer-events: none;
        }

        .input-bar-container {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translate(-50%, 100%); 
            width: 100%;
            max-width: 450px;
            background-color: var(--white-bg);
            box-shadow: 0 -5px 15px rgba(0, 0, 0, 0.1);
            padding: 15px 20px;
            transition: transform 0.3s ease-in-out;
            z-index: 15;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .input-bar-container.visible {
            transform: translate(-50%, 0); 
        }

        .input-bar-container input[type="text"] {
            flex-grow: 1;
            padding: 12px 15px;
            border: 1px solid var(--input-border);
            border-radius: 25px;
            font-size: 16px;
            outline: none;
        }

        .input-bar-container button {
            background-color: var(--primary-pink);
            color: var(--white-bg);
            border: none;
            padding: 12px 20px;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            flex-shrink: 0;
        }
        
        /* --- Hidden Date Picker (Modal) --- */
        .date-picker-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 100;
        }

        .date-picker-content {
            background: var(--white-bg);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }
        
        .date-picker-content h3 {
            margin-top: 0;
            color: var(--text-dark);
        }
        
        .date-picker-content input[type="date"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid var(--input-border);
            border-radius: 5px;
            margin-bottom: 15px;
            width: 100%;
            box-sizing: border-box;
        }
        
        .date-picker-content button {
            padding: 10px 20px;
            background-color: var(--plus-button-color);
            color: white;
            border: none;
            border-radius: 20px;
            cursor: pointer;
        }

    </style>
</head>
<body>

<div class="app-container">
    
    <div class="header-bar">
        <a href="javascript:history.back()" class="close-icon">&times;</a>
        <div class="header-title">Vaccinations</div>
        <div class="header-edit">Edit</div>
    </div>

    <div class="profile-section">
        <div class="profile-circle">M</div>
        <div class="profile-name">Mum</div>
    </div>

    <div id="saved-vaccines-display" class="vaccine-list-container">
        <?php if (!empty($saved_vaccines)): ?>
            <?php foreach ($saved_vaccines as $vax_id => $vax_data): ?>
                <div class="vaccine-card">
                    <div class="vaccine-header">
                        VACCINE
                        <span class="vaccine-name-display"><?= htmlspecialchars($vax_data['name']) ?></span>
                    </div>
                    <div class="dose-list">
                        <?php 
                        // Show saved doses
                        foreach ($vax_data['doses'] as $dose_num => $date): 
                        ?>
                            <div class="dose-item">
                                <span class="dose-label">Dose <?= $dose_num ?></span>
                                <span class="dose-date"><?= htmlspecialchars($date) ?></span>
                            </div>
                        <?php endforeach; ?>
                        
                        <?php
                        // Show pending doses (up to 5)
                        $last_saved_dose = max(array_keys($vax_data['doses']) ?: [0]);
                        $max_doses = 5; // Fixed at 5 as requested
                        
                        for ($i = $last_saved_dose + 1; $i <= $max_doses; $i++):
                        ?>
                            <div class="dose-item">
                                <span class="dose-label">Dose <?= $i ?></span>
                                <span class="dose-date">
                                    <button class="dose-button" 
                                            onclick="openDatePicker('<?= htmlspecialchars($vax_id) ?>', <?= $i ?>, '<?= htmlspecialchars($vax_data['name']) ?>')">
                                        Mark as Given
                                    </button>
                                </span>
                            </div>
                        <?php endfor; ?>
                        
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <div id="pending-vaccine-list" class="vaccine-list-container">
        </div>
    
    <div id="empty-state" class="empty-state <?= empty($saved_vaccines) ? '' : 'hidden' ?>">
        <h1>You have not added any vaccinations yet</h1>
        <p>Keep track of your and your child's vaccinations easily by registering all given vaccines.</p>
        <p>Start by clicking on the **+** sign to add a vaccine to the list.</p>
    </div>
    
    <form id="add-vax-form" onsubmit="return false;">
        <div id="input-bar-container" class="input-bar-container">
            <input type="text" id="vaccine-input" placeholder="Add a vaccine (e.g., Polio)" onkeyup="checkInput(event)">
            <button id="add-button" disabled onclick="addVaccine()">Add</button>
        </div>
    </form>

    <button id="fab-button" class="fab-button" onclick="toggleInputBar()">
        +
    </button>
    
    <div id="date-picker-modal" class="date-picker-modal">
        <div class="date-picker-content">
            <h3>Select Dose Date:</h3>
            <input type="date" id="dose-date-input">
            <button onclick="saveDoseDate()">Save Date</button>
            <button onclick="closeDatePicker()" style="background-color: var(--text-grey); margin-left: 10px;">Cancel</button>
        </div>
    </div>
</div>

<script>
    const fabButton = document.getElementById('fab-button');
    const inputBarContainer = document.getElementById('input-bar-container');
    const vaccineInput = document.getElementById('vaccine-input');
    const addButton = document.getElementById('add-button');
    const savedVaccinesDisplay = document.getElementById('saved-vaccines-display');
    const pendingVaccineList = document.getElementById('pending-vaccine-list');
    const emptyState = document.getElementById('empty-state');
    const datePickerModal = document.getElementById('date-picker-modal');
    const doseDateInput = document.getElementById('dose-date-input');
    
    let pendingVaccineIdCounter = 100; // Start counter higher than the PHP dummy 'vax-0'
    let currentVaxData = {}; // Stores data for the currently active date-picker context
    
    // --- Dose Selector Functions ---

    function toggleDoseSelector(element) {
        // Find the closest parent .vaccine-item-row (for dynamically added items)
        const vaccineItem = element.closest('.vaccine-item-row');
        // Find the dose selector bar within that item
        const selectorContainer = vaccineItem.querySelector('.dose-selector-container');
        
        if (selectorContainer) {
            selectorContainer.classList.toggle('open');
        }
    }
    
    // --- Date Picker Functions ---

    function openDatePicker(vaxId, doseNum, vaxName) {
        // Set the context data for saving later
        currentVaxData = { vax_id: vaxId, dose_num: doseNum, vax_name: vaxName };
        
        // Get today's date and set max limit for date picker
        const today = new Date().toISOString().split('T')[0];
        doseDateInput.value = today;
        doseDateInput.max = today;
        
        datePickerModal.style.display = 'flex';
    }

    function closeDatePicker() {
        datePickerModal.style.display = 'none';
        currentVaxData = {};
    }

    function saveDoseDate() {
        const selectedDate = doseDateInput.value;
        if (!selectedDate) {
            alert("Please select a date.");
            return;
        }

        // Format date to DD/MM/YYYY for display (matching 18/10/2025)
        const [year, month, day] = selectedDate.split('-');
        const displayDate = `${day}/${month}/${year}`;

        // Create a hidden form and submit data to PHP (simulate AJAX save and page reload)
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'tool-vaccination.php';
        
        const fields = {
            action: 'save_dose',
            vax_id: currentVaxData.vax_id,
            dose_num: currentVaxData.dose_num,
            date: displayDate,
            vax_name: currentVaxData.vax_name // Needed if it's a new vaccine ID
        };
        
        for (const key in fields) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = fields[key];
            form.appendChild(input);
        }

        document.body.appendChild(form);
        form.submit(); // This will trigger the PHP save logic and reload the page
        
        closeDatePicker();
    }


    // --- Main Control Functions ---

    function toggleInputBar() {
        const isVisible = inputBarContainer.classList.toggle('visible');
        fabButton.classList.toggle('hidden', isVisible);
        
        if (isVisible) {
            vaccineInput.focus();
        } else {
            vaccineInput.value = '';
            addButton.disabled = true;
        }
    }

    function checkInput(event) {
        addButton.disabled = vaccineInput.value.trim() === '';
        if (event.key === 'Enter' && !addButton.disabled) {
            addVaccine();
        }
    }

    function addVaccine() {
        const vaccineName = vaccineInput.value.trim();
        if (!vaccineName) return;

        pendingVaccineIdCounter++;
        const currentVaccineId = `vax-${pendingVaccineIdCounter}`;

        // 1. Create the new vaccine item row
        const newItem = document.createElement('div');
        newItem.className = 'vaccine-item-row';
        
        // 2. HTML structure for the new pending vaccine
        newItem.innerHTML = `
            <div class="vaccine-name-toggle" onclick="toggleDoseSelector(this)">
                ${vaccineName} (Pending)
            </div>
            <div class="dose-selector-container" id="${currentVaccineId}-selector">
                <div class="dose-buttons">
                    ${Array.from({length: 5}, (_, i) => i + 1).map(doseNum => `
                        <button class="dose-button" 
                                onclick="openDatePicker('${currentVaccineId}', ${doseNum}, '${vaccineName}')">
                            ${doseNum}
                        </button>
                    `).join('')}
                </div>
            </div>
        `;
        
        // 3. Insert the new item into the pending list
        pendingVaccineList.prepend(newItem);
        
        // 4. Hide empty state and clear/close input bar
        emptyState.classList.add('hidden');
        toggleInputBar(); 

        // 5. Automatically open the dose selector for the newly added item
        const newSelector = document.getElementById(`${currentVaccineId}-selector`);
        if (newSelector) {
            newSelector.classList.add('open');
        }
    }

    // --- Initialization ---
    
    // Hide the empty state if there are any saved or pending vaccines
    if (savedVaccinesDisplay.children.length > 0 || pendingVaccineList.children.length > 0) {
        emptyState.classList.add('hidden');
    }
    
</script>

</body>
</html>