<?php
// PHP logic (if needed later for backend)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Due Date</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-pink': '#ff69b4',
                        'light-pink': '#ffe6e9',
                        'bg-light': '#fcf6f6',
                        'text-dark': '#333',
                        'text-muted': '#888',
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: Arial, sans-serif;
            min-height: 100vh;
        }
        input[type="date"]::-webkit-calendar-picker-indicator {
            background: none;
            display: none;
        }
    </style>
</head>
<body class="bg-bg-light flex flex-col items-center p-5 text-text-dark">

    <div class="w-full max-w-sm text-center mt-12">

        <div class="text-primary-pink text-4xl mb-3">&#x2764;</div> 
        <h1 class="text-2xl font-bold text-text-dark mb-2">When is your baby due?</h1>
        <p class="text-sm text-text-muted mb-8">
            Select your baby's estimated due date to personalize your Janani experience.
        </p>

    <?php require_once __DIR__ . '/lib/auth.php'; start_secure_session(); $csrf = generate_csrf_token(); ?>
    <form id="date-selection-card" class="bg-white p-6 rounded-xl shadow-lg border border-pink-100" method="POST" action="/JANANI/pregnancy/save_due_date.php">
            
            <label for="due_date" class="block text-left text-sm font-semibold mb-2">Estimated Due Date</label>
            
            <div class="relative flex items-center mb-4">
                <input 
                    type="date" 
                    id="due_date" name="due_date"
                    class="w-full p-4 pr-12 text-lg border-2 border-pink-200 rounded-lg focus:border-primary-pink focus:ring-1 focus:ring-primary-pink transition duration-150 ease-in-out bg-light-pink/50 text-center" 
                    required
                >
                <i class="fas fa-calendar-check absolute right-4 text-primary-pink pointer-events-none"></i>
            </div>

            <!-- Selected date display -->
            <p id="selectedDateText" class="text-primary-pink font-semibold text-md hidden">
                <!-- JS will show text here -->
            </p>
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf); ?>">
            <button 
                id="continue-button"
                class="w-full py-3 mt-6 text-white font-bold rounded-lg bg-primary-pink hover:bg-pink-700 transition duration-150 ease-in-out shadow-lg shadow-pink-300/50 transform active:scale-95"
                type="button" onclick="handleDueSubmission()"
            >
                Continue
            </button>
            
            <p class="text-xs text-text-muted mt-4">
                You can always change this date later in your settings.
            </p>
        </form>
    </div>

    <script>
        const dateInput = document.getElementById('due_date');
        const selectedDateText = document.getElementById('selectedDateText');

        // Set default date 39 weeks from now
        document.addEventListener('DOMContentLoaded', () => {
            const future = new Date();
            future.setDate(future.getDate() + (39 * 7));
            const defaultDate = future.toISOString().split('T')[0];
            dateInput.value = defaultDate;

            // Show default date text
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            selectedDateText.textContent = `Your selected due date is: ${future.toLocaleDateString('en-US', options)}`;
            selectedDateText.classList.remove('hidden');
        });

        // When user changes the date
        dateInput.addEventListener('change', () => {
            const value = dateInput.value;
            if (value) {
                const selected = new Date(value);
                const options = { year: 'numeric', month: 'long', day: 'numeric' };
                selectedDateText.textContent = `Your selected due date is: ${selected.toLocaleDateString('en-US', options)}`;
                selectedDateText.classList.remove('hidden');
            }
        });

        // Continue button action
        function handleDueSubmission() {
            const selectedDate = dateInput.value;
            if (!selectedDate) {
                const card = document.getElementById('date-selection-card');
                const message = document.createElement('p');
                message.className = "text-red-500 text-sm mt-3 font-semibold";
                message.textContent = "Please select a valid estimated due date.";
                const existingMessage = card.querySelector('.text-red-500');
                if (existingMessage) existingMessage.remove();
                card.appendChild(message);
                return;
            }

            // Submit the form to persist EDD for the current user
            document.getElementById('date-selection-card').submit();
        }
    </script>

</body>
</html>
