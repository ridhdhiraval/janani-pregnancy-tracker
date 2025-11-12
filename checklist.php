<?php
require_once __DIR__ . '/lib/auth.php';
require_once __DIR__ . '/config/db.php';
start_secure_session();
$user = current_user();

// Simple checklist data
$checklist_data = [
    ['id' => 1, 'category' => 'First Trimester', 'task' => 'Schedule first prenatal visit', 'completed' => 0],
    ['id' => 2, 'category' => 'First Trimester', 'task' => 'Start taking prenatal vitamins', 'completed' => 0],
    ['id' => 3, 'category' => 'First Trimester', 'task' => 'Quit smoking and avoid alcohol', 'completed' => 0],
    ['id' => 4, 'category' => 'Second Trimester', 'task' => 'Schedule second trimester screening', 'completed' => 0],
    ['id' => 5, 'category' => 'Second Trimester', 'task' => 'Start thinking about baby names', 'completed' => 0],
    ['id' => 6, 'category' => 'Third Trimester', 'task' => 'Attend childbirth classes', 'completed' => 0],
    ['id' => 7, 'category' => 'Third Trimester', 'task' => 'Pack hospital bag', 'completed' => 0],
];

$total_tasks = count($checklist_data);
$completed_tasks = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pregnancy Checklist</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        /* CSS Styling */
        :root {
            --primary-pink: #f8e5e8;
            --dark-pink: #e07f91;
            --text-color: #5d5d5d;
            --white: #ffffff;
            --shadow-light: 0 4px 12px rgba(0, 0, 0, 0.08);
            --completed-green: #90EE90;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--primary-pink);
            min-height: 100vh;
        }

        .checklist-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background-color: var(--white);
            border-radius: 20px;
            box-shadow: var(--shadow-light);
        }

        /* Header and Progress */
        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: var(--dark-pink);
            font-size: 36px;
            margin: 0 0 10px;
        }

        .trimester-info {
            text-align: center;
            margin-bottom: 20px;
            padding: 15px;
            background-color: var(--primary-pink);
            border-radius: 10px;
            color: var(--dark-pink);
            font-weight: 600;
            font-size: 18px;
        }

        .progress-bar-container {
            background-color: var(--primary-pink);
            border-radius: 15px;
            padding: 5px;
            margin-bottom: 20px;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .progress-bar {
            height: 25px;
            width: <?php echo $progress_percent; ?>%; /* PHP sets initial width */
            background-color: var(--dark-pink);
            border-radius: 15px;
            transition: width 0.5s ease-in-out;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-weight: 600;
            font-size: 14px;
            min-width: 0%; /* Start at 0% */
        }
        
        .progress-bar[style*="width: 0%"] {
             opacity: 0; /* Hide text when 0% */
        }
        
        /* Checklist Grouping */
        .checklist-group {
            margin-bottom: 30px;
            padding: 15px;
            border: 1px solid var(--primary-pink);
            border-radius: 15px;
            background-color: #fffafc;
        }
        
        .checklist-group h2 {
            color: var(--dark-pink);
            font-size: 24px;
            border-bottom: 2px solid var(--primary-pink);
            padding-bottom: 5px;
            margin-top: 0;
            margin-bottom: 15px;
        }

        /* Individual Task Item */
        .task-item {
            display: flex;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px dashed #f0f0f0;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .task-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        
        .task-item:hover {
            background-color: #fcf6f8;
        }

        /* Custom Checkbox Style */
        .task-checkbox {
            appearance: none;
            width: 25px;
            height: 25px;
            border: 2px solid var(--dark-pink);
            border-radius: 6px;
            margin-right: 15px;
            cursor: pointer;
            position: relative;
            flex-shrink: 0;
            transition: all 0.2s;
        }

        .task-checkbox:checked {
            background-color: var(--dark-pink);
            border-color: var(--dark-pink);
        }

        .task-checkbox:checked::after {
            content: '\2713'; /* Checkmark symbol */
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: var(--white);
            font-size: 18px;
            font-weight: 700;
        }

        .task-label {
            font-size: 18px;
            color: var(--text-color);
            flex-grow: 1;
            transition: color 0.2s;
        }

        .task-item.completed .task-label {
            text-decoration: line-through;
            color: #b3b3b3;
        }

        .task-item.completed .task-checkbox {
            border-color: #ccc;
        }

        /* Filter Tabs */
        .filter-tabs {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .filter-tabs button {
            padding: 8px 15px;
            border: 2px solid var(--dark-pink);
            border-radius: 25px;
            background-color: var(--white);
            color: var(--dark-pink);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .filter-tabs button.active {
            background-color: var(--dark-pink);
            color: var(--white);
            box-shadow: 0 4px 8px rgba(224, 127, 145, 0.4);
        }

        /* Back Button */
        .back-link {
            display: block;
            text-align: center;
            margin-top: 30px;
            color: var(--dark-pink);
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
        }
        .back-link:hover {
            text-decoration: underline;
        }

        /* Media Query for smaller screens */
        @media (max-width: 600px) {
            .checklist-container {
                margin: 20px 10px;
                padding: 20px;
            }
            .header h1 {
                font-size: 28px;
            }
            .checklist-group h2 {
                font-size: 20px;
            }
            .task-label {
                font-size: 16px;
            }
            .filter-tabs {
                flex-wrap: wrap;
            }
        }

    </style>
</head>
<body>

    <div class="checklist-container">
        
        <div class="header">
            <h1>🤰 My Pregnancy Checklist</h1>
            <?php if ($user && isset($current_trimester)): ?>
            <div class="trimester-info">
                Currently in Trimester <?php echo $current_trimester; ?> (Week <?php echo isset($weeks_done) ? $weeks_done : 'calculating'; ?>)
            </div>
            <?php endif; ?>
            <p style="color: var(--text-color);">**Total Progress: <span id="progress-text"><?php echo $completed_tasks . ' / ' . $total_tasks; ?></span> tasks completed**</p>
            
            <div class="progress-bar-container">
                <div class="progress-bar" id="progressBar" style="width: <?php echo $progress_percent; ?>%;">
                    <?php echo $progress_percent; ?>%
                </div>
            </div>
            
            <div class="filter-tabs" id="filterTabs">
                <button data-filter="all" class="active">All Tasks</button>
                <button data-filter="Trimester 1: Health">T1 Health</button>
                <button data-filter="Trimester 2: Planning">T2 Planning</button>
                <button data-filter="Trimester 3: Preparation">T3 Prep</button>
            </div>
        </div>

        <div id="checklistTasks">
            <?php
            // Group tasks by category (Trimester/Topic)
            $grouped_tasks = [];
            foreach ($checklist_data as $task) {
                $grouped_tasks[$task['category']][] = $task;
            }

            foreach ($grouped_tasks as $category => $tasks):
            ?>
            <div class="checklist-group" data-category="<?php echo htmlspecialchars($category); ?>">
                <h2><?php echo htmlspecialchars($category); ?></h2>
                <?php foreach ($tasks as $task): ?>
                <div class="task-item <?php echo $task['completed'] ? 'completed' : ''; ?>" 
                     data-id="<?php echo $task['id']; ?>"
                     data-completed="<?php echo $task['completed']; ?>">
                    
                    <input type="checkbox" 
                           class="task-checkbox" 
                           id="task-<?php echo $task['id']; ?>" 
                           <?php echo $task['completed'] ? 'checked' : ''; ?>
                           onclick="toggleTask(<?php echo $task['id']; ?>, this)">
                           
                    <label for="task-<?php echo $task['id']; ?>" class="task-label">
                        <?php echo htmlspecialchars($task['task']); ?>
                    </label>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endforeach; ?>
        </div>

        <a href="5index.php" class="back-link">← Go back to Dashboard</a>
        
    </div>

    <script>
        // JavaScript for interactivity
        
        // PHP Data is passed to JS for dynamic updates
        const allTasks = <?php echo json_encode($checklist_data); ?>;
        const totalTasks = allTasks.length;

        /**
         * Toggles the completion status of a task and updates the UI.
         * @param {number} taskId - The unique ID of the task.
         * @param {HTMLElement} checkbox - The checkbox element.
         */
        function toggleTask(taskId, checkbox) {
            const taskItem = checkbox.closest('.task-item');
            const isCompleted = checkbox.checked;

            // 1. Update the local array data
            const taskIndex = allTasks.findIndex(task => task.id === taskId);
            if (taskIndex !== -1) {
                allTasks[taskIndex].completed = isCompleted ? 1 : 0;
            }

            // 2. Update the visual class for strike-through
            if (isCompleted) {
                taskItem.classList.add('completed');
            } else {
                taskItem.classList.remove('completed');
            }
            
            // 3. Update the global progress bar
            updateProgress();
            
            // In a real application, an AJAX call would be made here to save the status to the database:
            /*
            fetch('save_checklist.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: taskId, completed: isCompleted ? 1 : 0 })
            })
            .then(response => response.json())
            .then(data => console.log('Saved:', data));
            */
        }
        
        /**
         * Calculates and updates the checklist progress display.
         */
        function updateProgress() {
            const completedCount = allTasks.filter(task => task.completed === 1).length;
            const percentage = totalTasks > 0 ? Math.round((completedCount / totalTasks) * 100) : 0;

            const progressBar = document.getElementById('progressBar');
            const progressText = document.getElementById('progress-text');

            progressBar.style.width = `${percentage}%`;
            progressBar.textContent = `${percentage}%`;
            progressBar.style.opacity = percentage > 0 ? '1' : '0';

            progressText.textContent = `${completedCount} / ${totalTasks}`;
        }
        
        /**
         * Filters the task groups based on the selected category.
         * @param {string} filter - The category name to filter by, or 'all'.
         */
        function filterTasks(filter) {
            const groups = document.querySelectorAll('.checklist-group');
            
            groups.forEach(group => {
                const category = group.getAttribute('data-category');
                
                if (filter === 'all') {
                    group.style.display = 'block';
                } else if (category.includes(filter)) {
                    group.style.display = 'block';
                } else {
                    group.style.display = 'none';
                }
            });
            
            // Update active button state
            document.querySelectorAll('#filterTabs button').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelector(`button[data-filter="${filter}"]`).classList.add('active');
        }
        
        // Initialize event listeners for filter buttons
        document.getElementById('filterTabs').addEventListener('click', (event) => {
            if (event.target.tagName === 'BUTTON') {
                const filterValue = event.target.getAttribute('data-filter');
                filterTasks(filterValue);
            }
        });

        // Initial progress update on load (to ensure JS values match PHP)
        document.addEventListener('DOMContentLoaded', updateProgress);
        
    </script>
</body>
</html>