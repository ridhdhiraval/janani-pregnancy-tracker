<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/lib/auth.php';
start_secure_session();

$page_title = "Estimated Due Date";
$estimated_due_date = "22/07/2026";
$last_period_date = "16/10/2025";
$menstrual_cycle_days = "28";
$back_link = "my_pregnancy.php";

$user = current_user();
if ($user) {
    $stmt = $pdo->prepare('SELECT lmp_date, edd FROM pregnancies WHERE user_id = ? AND status = "active" ORDER BY created_at DESC LIMIT 1');
    $stmt->execute([$user['id']]);
    $p = $stmt->fetch();
    if ($p) {
        if (!empty($p['edd'])) {
            $due = DateTimeImmutable::createFromFormat('Y-m-d', $p['edd']);
            if ($due) {
                $estimated_due_date = $due->format('d/m/Y');
            }
        }
        if (!empty($p['lmp_date'])) {
            $lmp = DateTimeImmutable::createFromFormat('Y-m-d', $p['lmp_date']);
            if ($lmp) {
                $last_period_date = $lmp->format('d/m/Y');
            }
        }
    }

    // Load menstrual cycle days from settings if available
    $s = $pdo->prepare('SELECT value FROM settings WHERE user_id = ? AND `key` = ? LIMIT 1');
    $s->execute([$user['id'], 'menstrual_cycle_days']);
    $row = $s->fetch();
    if ($row && is_numeric($row['value'])) {
        $menstrual_cycle_days = (string)max(20, min(45, (int)$row['value']));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($page_title); ?> - Desktop View</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet"> 

<style>
body {
    margin: 0;
    font-family: "Poppins", sans-serif;
    background-color: #f7f3ed;
    min-height: 100vh;
}
.header-bar-wrapper {
    width: 100%;
    background-color: #ffffff;
    border-bottom: 1px solid #e0d9cd;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
}
.app-header {
    display: flex;
    align-items: center;
    padding: 20px 40px;
    max-width: 90vw;
    margin: 0 auto;
    position: relative;
}
.app-header h1 {
    font-size: 24px;
    font-weight: 500;
    color: #4b4b4b;
    margin: 0 auto;
    white-space: nowrap;
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
}
.back-arrow {
    font-size: 30px;
    text-decoration: none;
    color: #4b4b4b;
    line-height: 1;
    z-index: 10;
}
.content-wrapper {
    max-width: 90vw;
    margin: 0 auto;
    padding: 20px 40px;
}
.data-list-container {
    max-width: 800px;
    margin: 20px auto;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    padding: 0 30px;
}
.data-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 25px 0;
    border-bottom: 1px solid #e0d9cd;
    cursor: pointer;
}
.data-item:last-child {
    border-bottom: none;
}
.data-item .label {
    font-size: 18px;
    color: #4b4b4b;
}
.data-item .value {
    font-size: 18px;
    color: #4b4b4b;
    font-weight: 500;
}
/* --- Common Modal Overlay for both modals --- */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.4);
    z-index: 1000;
    justify-content: center;
    align-items: center;
}
/* --- General Modal Styling for the calendar modal --- */
.calendar-modal {
    background-color: #fff;
    width: 90%;
    max-width: 380px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
}
/* Calendar Modal Specific Styles (retained) */
.modal-header {
    background-color: #f06c6c; /* Calendar modal header color */
    padding: 25px 20px;
    color: #fff;
}
.date-display {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.date-display .date-value {
    font-size: 32px;
    font-weight: 700;
}
.modal-body {
    padding: 15px 20px;
    color: #4b4b4b;
}
.month-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 18px;
    font-weight: 500;
    margin-bottom: 20px;
}
.nav-arrow {
    cursor: pointer;
    font-size: 24px;
    padding: 0 10px;
    user-select: none;
}
.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    text-align: center;
    gap: 8px 0;
    padding-bottom: 20px;
}
.calendar-grid .day-name {
    font-size: 14px;
    font-weight: 500;
    color: #888;
}
.date-cell {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 40px;
    height: 40px;
    margin: 0 auto;
    font-size: 16px;
    cursor: pointer;
    border-radius: 50%;
}
.date-cell:hover {
    background-color: #fde4e4;
}
.date-cell.selected {
    background-color: #f06c6c;
    color: #fff;
    font-weight: 700;
}
/* Calendar Modal Footer (retained) */
.modal-footer button {
    background: none;
    border: none;
    color: #f06c6c;
    margin-left: 25px;
    cursor: pointer;
    padding: 8px 10px;
}


/* --- Menstrual Cycle Modal Specific Styles (based on image) --- */
.cycle-modal {
    /* Use a fixed, smaller size as seen in the image */
    background-color: #fff;
    width: 90%;
    max-width: 320px; 
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    display: flex; /* Flex container for content and footer */
    flex-direction: column;
    padding: 0;
}
.cycle-modal-content {
    padding: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    overflow: hidden; /* Hide the scrollbar but keep the content scrollable via JS */
}
.cycle-modal-title {
    font-size: 18px;
    font-weight: 500;
    color: #4b4b4b;
    margin-bottom: 15px;
}
.picker-container {
    height: 150px; /* Height to show roughly 5 items */
    width: 100px;
    position: relative;
    overflow: hidden;
    /* Center lines */
    display: flex;
    justify-content: center;
    align-items: center;
}
.picker-container::before, .picker-container::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 1px;
    background-color: #f06c6c; /* Red lines for selection */
    left: 0;
    z-index: 10;
}
.picker-container::before {
    top: 50%;
    transform: translateY(-16px); /* Adjusted to match center item */
}
.picker-container::after {
    top: 50%;
    transform: translateY(16px); /* Adjusted to match center item */
}
.cycle-picker {
    height: 100%;
    width: 100%;
    overflow-y: scroll;
    text-align: center;
    /* Hide scrollbar */
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: none;  /* Firefox */
    scroll-snap-type: y mandatory;
    padding: 50px 0; /* Add padding to top/bottom to center the first/last elements */
}
.cycle-picker::-webkit-scrollbar {
    display: none; /* Chrome, Safari, Opera */
}
.cycle-item {
    font-size: 20px;
    padding: 5px 0;
    color: #888; /* Dim default items */
    transition: color 0.2s, font-size 0.2s;
    scroll-snap-align: center; /* Snap to center when scrolling stops */
    height: 30px; /* Uniform height for scrolling calculation */
    line-height: 30px;
}
.cycle-item.selected-cycle {
    font-size: 24px;
    font-weight: 500;
    color: #4b4b4b; /* Darker/larger for the selected item */
}

/* Cycle Modal Footer - matches image exact */
.cycle-modal-footer {
    display: flex;
    justify-content: flex-end;
    padding: 10px;
    border-top: 1px solid #e0d9cd;
}
.cycle-modal-footer button {
    background-color: #4b4b4b; /* Dark background */
    color: #fff;
    border: none;
    border-radius: 25px; /* Pill shape */
    padding: 10px 25px;
    font-weight: 500;
    text-transform: uppercase;
    cursor: pointer;
    margin-left: 10px;
    font-size: 16px;
}
.cycle-modal-footer #cancelCycleBtn {
    background-color: transparent;
    color: #4b4b4b;
    font-weight: 400;
}
</style>
</head>
<body>

<div class="due-date-app-container">
    <div class="header-bar-wrapper">
        <header class="app-header">
            <a href="<?php echo htmlspecialchars($back_link); ?>" class="back-arrow">&leftarrow;</a> 
            <div class="header-content">
                <h1><?php echo htmlspecialchars($page_title); ?></h1>
            </div>
        </header>
    </div>

    <div class="content-wrapper">
        <div class="data-list-container">
            <div class="data-item" id="data-edd">
                <span class="label">Estimated Due Date</span>
                <span class="value" id="eddValue"><?php echo htmlspecialchars($estimated_due_date); ?></span>
            </div>
            <div class="data-item" id="data-lmp">
                <span class="label">First day of last period</span>
                <span class="value" id="lmpValue"><?php echo htmlspecialchars($last_period_date); ?></span>
            </div>
            <div class="data-item" id="data-cycle">
                <span class="label">Menstrual cycle</span>
                <span class="value" id="cycleValue"><?php echo htmlspecialchars($menstrual_cycle_days); ?></span>
            </div>
        </div>
    </div>
</div>

<div class="modal-overlay" id="calendarModal">
    <div class="calendar-modal">
        <div class="modal-header">
            <h2 id="modalTitle">Select Date</h2>
            <div class="date-display">
                <span class="date-value" id="modalDateValue">22 Jul 2026</span>
            </div>
        </div>

        <div class="modal-body">
            <div class="month-nav">
                <span id="prevMonth" class="nav-arrow">&lt;</span>
                <span id="monthYear">July 2026</span>
                <span id="nextMonth" class="nav-arrow">&gt;</span>
            </div>
            <div class="calendar-grid" id="calendarGrid"></div>
        </div>

        <div class="modal-footer">
            <button id="cancelModalBtn">CANCEL</button>
            <button id="okModalBtn">OK</button>
        </div>
    </div>
</div>

<div class="modal-overlay" id="cycleModal">
    <div class="cycle-modal">
        <div class="cycle-modal-content">
            <h2 class="cycle-modal-title">Menstrual cycle</h2>
            <div class="picker-container">
                <div class="cycle-picker" id="cyclePicker">
                    </div>
            </div>
        </div>
        <div class="cycle-modal-footer">
            <button id="cancelCycleBtn">CANCEL</button>
            <button id="okCycleBtn">OK</button>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', () => {
    // --- Data List Elements ---
    const eddData = document.getElementById('data-edd');
    const lmpData = document.getElementById('data-lmp');
    const cycleData = document.getElementById('data-cycle'); // New
    const eddValue = document.getElementById('eddValue');
    const lmpValue = document.getElementById('lmpValue');
    const cycleValue = document.getElementById('cycleValue'); // New

    // --- Calendar Modal Elements (Existing) ---
    const calendarModal = document.getElementById('calendarModal');
    const cancelModalBtn = document.getElementById('cancelModalBtn');
    const okModalBtn = document.getElementById('okModalBtn');
    const modalDateValue = document.getElementById('modalDateValue');
    const monthYear = document.getElementById('monthYear');
    const calendarGrid = document.getElementById('calendarGrid');
    const prevMonthBtn = document.getElementById('prevMonth');
    const nextMonthBtn = document.getElementById('nextMonth');
    const modalTitle = document.getElementById('modalTitle');

    let selectedDate = new Date();
    let currentDate = new Date(selectedDate);
    let activeField = ""; // "EDD" or "LMP"

    // --- Cycle Modal Elements (New) ---
    const cycleModal = document.getElementById('cycleModal');
    const cyclePicker = document.getElementById('cyclePicker');
    const cancelCycleBtn = document.getElementById('cancelCycleBtn');
    const okCycleBtn = document.getElementById('okCycleBtn');
    const CYCLE_ITEM_HEIGHT = 30; // Matches CSS .cycle-item height
    const MIN_CYCLE = 20;
    const MAX_CYCLE = 45;
    let selectedCycleDays = parseInt(cycleValue.textContent) || 28;

    // --- Utility Functions ---
    const parseDateGB = (dateString) => {
        const parts = dateString.split('/');
        // Note: Date constructor with (year, monthIndex, day)
        return new Date(parts[2], parts[1] - 1, parts[0]); 
    };

    const formatDateGB = (date) => {
        const d = date.getDate().toString().padStart(2, '0');
        const m = (date.getMonth() + 1).toString().padStart(2, '0');
        const y = date.getFullYear();
        return `${d}/${m}/${y}`;
    }

    // --- LMP/EDD Modal Logic (Existing) ---

    function renderCalendar(date) {
        // ... (Calendar rendering logic remains the same)
        const year = date.getFullYear();
        const month = date.getMonth();
        const months = ["January","February","March","April","May","June","July","August","September","October","November","December"];
        const firstDay = new Date(year, month, 1).getDay(); // 0 is Sunday, 1 is Monday
        const daysInMonth = new Date(year, month + 1, 0).getDate();

        monthYear.textContent = `${months[month]} ${year}`;
        calendarGrid.innerHTML = "";

        // Adjust day names to start with Monday (M, T, W, T, F, S, S)
        const dayNames = ["M", "T", "W", "T", "F", "S", "S"];
        dayNames.forEach(d => {
            const el = document.createElement('span');
            el.classList.add('day-name');
            el.textContent = d;
            calendarGrid.appendChild(el);
        });

        // Calculate leading blanks (0 for Mon, 1 for Tue, ..., 6 for Sun)
        const blanks = (firstDay === 0 ? 6 : firstDay - 1); 
        for (let i = 0; i < blanks; i++) {
            const blank = document.createElement('span');
            calendarGrid.appendChild(blank);
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const dayCell = document.createElement('span');
            dayCell.classList.add('date-cell');
            dayCell.textContent = day;

            const tempDate = new Date(year, month, day);

            if (
                tempDate.getTime() === selectedDate.setHours(0,0,0,0) // Compare date part only
            ) {
                dayCell.classList.add('selected');
            }

            dayCell.addEventListener('click', () => {
                document.querySelectorAll('.date-cell').forEach(c => c.classList.remove('selected'));
                dayCell.classList.add('selected');
                selectedDate = new Date(year, month, day);
                const formatted = `${day.toString().padStart(2, '0')} ${months[month].slice(0,3)} ${year}`;
                modalDateValue.textContent = formatted;
            });

            calendarGrid.appendChild(dayCell);
        }
        selectedDate.setHours(0,0,0,0); // Reset hours after selection to prevent time zone issues
    }


    function showCalendarModal(type) {
        activeField = type;
        const valueEl = type === "LMP" ? lmpValue : eddValue;
        
        // Use the current displayed value to initialize the calendar
        selectedDate = parseDateGB(valueEl.textContent);
        currentDate = new Date(selectedDate); // Set calendar view to the selected date

        modalTitle.textContent = type === "LMP" ? "First day of last period" : "Estimated Due Date";
        const d = selectedDate.getDate().toString().padStart(2, '0');
        const m = selectedDate.toLocaleDateString('en-GB', { month: 'short' });
        const y = selectedDate.getFullYear();
        modalDateValue.textContent = `${d} ${m} ${y}`;


        calendarModal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        renderCalendar(currentDate);
    }

    function hideCalendarModal() {
        calendarModal.style.display = 'none';
        document.body.style.overflow = '';
    }

    lmpData.onclick = () => showCalendarModal("LMP");
    eddData.onclick = () => showCalendarModal("EDD");

    cancelModalBtn.onclick = hideCalendarModal;

    okModalBtn.onclick = () => {
        const formattedMain = formatDateGB(selectedDate);

        if (activeField === "EDD") {
            eddValue.textContent = formattedMain;
        } else if (activeField === "LMP") {
            lmpValue.textContent = formattedMain;
            // Auto-calculate EDD = LMP + 280 days (standard cycle) or adjusted
            const cycleDays = parseInt(cycleValue.textContent) || 28;
            const adjustment = cycleDays - 28;
            const daysToAdd = 280 + adjustment; 

            const dueDate = new Date(selectedDate);
            dueDate.setDate(dueDate.getDate() + daysToAdd);
            const formattedEDD = formatDateGB(dueDate);
            eddValue.textContent = formattedEDD;
        }
        hideCalendarModal();
    };

    prevMonthBtn.onclick = () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar(currentDate);
    };

    nextMonthBtn.onclick = () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar(currentDate);
    };
    
    // --- Cycle Modal Logic (New, matches image) ---

    // Function to update the selected style based on scroll position
    function updateSelectedCycle() {
        // Calculate the index of the item that is centered
        const scrollPosition = cyclePicker.scrollTop;
        // Divide scroll by item height to get the index, then round to nearest
        const centerIndex = Math.round(scrollPosition / CYCLE_ITEM_HEIGHT);
        const actualValue = MIN_CYCLE + centerIndex;
        
        // Update the visual selection
        const items = cyclePicker.querySelectorAll('.cycle-item');
        items.forEach(item => item.classList.remove('selected-cycle'));

        if (items[centerIndex]) {
            items[centerIndex].classList.add('selected-cycle');
            selectedCycleDays = actualValue; // Store the current selection
        }
    }

    function showCycleModal() {
        cyclePicker.innerHTML = "";
        
        // 1. Render all cycle numbers
        for (let i = MIN_CYCLE; i <= MAX_CYCLE; i++) {
            const item = document.createElement('div');
            item.classList.add('cycle-item');
            item.textContent = i;
            cyclePicker.appendChild(item);
        }

        // 2. Initial scroll to the current value
        const initialIndex = selectedCycleDays - MIN_CYCLE;
        // The - (2 * CYCLE_ITEM_HEIGHT) centers the 3rd item (index 2) by default
        const initialScroll = initialIndex * CYCLE_ITEM_HEIGHT;
        
        // Scroll after a slight delay to ensure the content is rendered
        setTimeout(() => {
            cyclePicker.scrollTop = initialScroll;
            updateSelectedCycle(); // Initial selection highlight
        }, 50);

        cycleModal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function hideCycleModal() {
        cycleModal.style.display = 'none';
        document.body.style.overflow = '';
    }

    // Attach listener to the scroll event for continuous highlighting
    cyclePicker.addEventListener('scroll', updateSelectedCycle);
    
    // Attach listener to the main data item
    cycleData.onclick = () => {
        // Ensure selectedCycleDays reflects the currently displayed value
        selectedCycleDays = parseInt(cycleValue.textContent) || 28; 
        showCycleModal();
    }

    cancelCycleBtn.onclick = hideCycleModal;

    okCycleBtn.onclick = () => {
        cycleValue.textContent = selectedCycleDays;
        
        // Re-calculate EDD based on the new cycle length
        const lmpDate = parseDateGB(lmpValue.textContent);
        const cycleDays = parseInt(cycleValue.textContent);
        const adjustment = cycleDays - 28;
        const daysToAdd = 280 + adjustment; 

        const dueDate = new Date(lmpDate);
        dueDate.setDate(dueDate.getDate() + daysToAdd);
        const formattedEDD = formatDateGB(dueDate);
        eddValue.textContent = formattedEDD;

        hideCycleModal();
    };

});

</script>
    <?php include '15footer.php'; ?>

</body>
</html>