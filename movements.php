<?php
// Movements page: save and list baby movement (kick) sessions
require_once __DIR__ . '/lib/auth.php';
require_once __DIR__ . '/config/db.php';
start_secure_session();
$user = current_user();

// If not logged in, redirect to sign-in
if (!$user) {
    header('Location: /JANANI/1signinsignup.php');
    exit;
}

// Handle POST (AJAX) to save a session
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $payload = json_decode(file_get_contents('php://input'), true);

    $session_date = $payload['session_date'] ?? null; // 'YYYY-MM-DD'
    $start_time   = $payload['start_time'] ?? null;   // 'HH:MM'
    $end_time     = $payload['end_time'] ?? null;     // 'HH:MM'
    $kicks_count  = isset($payload['kicks_count']) ? (int)$payload['kicks_count'] : null;
    $notes        = $payload['notes'] ?? null;

    // Basic validation
    if (!$session_date || !$start_time || !$end_time || $kicks_count === null) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'Missing required fields']);
        exit;
    }

    try {
        // Ensure table exists (will throw if missing)
        $pdo->query('SELECT 1 FROM baby_movements LIMIT 1');

        $stmt = $pdo->prepare('INSERT INTO baby_movements (user_id, session_date, start_time, end_time, kicks_count, notes) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $user['id'],
            $session_date,
            $start_time,
            $end_time,
            $kicks_count,
            $notes
        ]);

        echo json_encode(['ok' => true]);
        exit;
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
        exit;
    }
}

// Page title and back link
$page_title = "Baby Movements Log"; 
$back_link = "6child.php"; // Always go to this page

// Load sessions from DB for the current user (fallback to empty if none)
$movement_sessions = [];
try {
    $q = $pdo->prepare('SELECT session_date, start_time, end_time, kicks_count, COALESCE(notes, "") AS notes FROM baby_movements WHERE user_id = ? ORDER BY session_date DESC, start_time DESC');
    $q->execute([$user['id']]);
    $movement_sessions = $q->fetchAll(PDO::FETCH_ASSOC) ?: [];
} catch (PDOException $e) {
    // If table missing or other DB error, show no-data and keep page usable
    $movement_sessions = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <style>
        /* Base Theme Styles */
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f7f3ed;
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

        /* Back Arrow Button */
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
            max-width: 800px;
            margin: 0 auto;
        }
        
        /* Session Views */
        .kick-counting-view {
            display: none; /* Hide by default */
        }
        
        /* Record Button Section (Initial View) */
        .record-section {
            text-align: center;
            margin-bottom: 25px;
            padding: 20px;
            background-color: #a8dadc; 
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .record-button {
            background-color: #e69999; 
            color: white;
            border: none;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            font-size: 20px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s, background-color 0.2s;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin: 0 auto 10px;
            line-height: 1.2;
        }
        
        .record-button:hover {
            background-color: #d17a7a;
            transform: scale(1.05);
        }
        
        .record-button span {
            font-size: 36px;
        }

        .record-section p {
            color: #333;
            margin: 0;
            font-weight: 500;
        }

        /* Kick Counting Styles (New) */
        .kick-counter {
            text-align: center;
            padding: 20px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .kick-counter .timer {
            font-size: 48px;
            font-weight: bold;
            color: #2a9d8f;
            margin-bottom: 10px;
        }

        .kick-counter .current-count {
            font-size: 80px;
            font-weight: bold;
            color: #e69999;
            line-height: 1;
            margin-bottom: 20px;
        }

        .kick-counter .count-label {
            font-size: 18px;
            color: #666;
            margin-bottom: 30px;
        }

        .kick-btn, .end-session-btn {
            padding: 15px 30px;
            font-size: 20px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.2s;
            width: 100%;
            margin-bottom: 10px;
            border: none;
        }
        
        .kick-btn {
            background-color: #2a9d8f;
            color: white;
        }
        
        .kick-btn:hover {
            background-color: #218c81;
        }
        
        .end-session-btn {
            background-color: #f4a261;
            color: white;
            margin-top: 15px;
        }
        
        .end-session-btn:hover {
            background-color: #e09255;
        }


        /* Movement History List */
        .history-list-header {
            font-size: 18px;
            font-weight: 600;
            color: #4b4b4b;
            margin-bottom: 15px;
        }
        
        .movement-session-card {
            background-color: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            margin-bottom: 15px;
            border-left: 5px solid #2a9d8f;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .session-details {
            text-align: left;
            flex-grow: 1;
        }

        .session-details .date {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .session-details .time {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }
        
        .session-details .notes {
            font-size: 14px;
            color: #888;
            margin-top: 5px;
        }

        .kicks-count {
            text-align: right;
            padding: 5px 10px;
        }
        
        .kicks-count .number {
            font-size: 36px;
            font-weight: bold;
            color: #e69999; 
            line-height: 1;
        }
        
        .kicks-count .label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
        }

        .no-data {
            text-align: center;
            padding: 30px;
            color: #999;
            background-color: white;
            border-radius: 8px;
            margin-top: 20px;
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
        
        <div class="kick-counting-view" id="kickCountingView">
            <div class="kick-counter">
                <div class="timer" id="sessionTimer">00:00:00</div>
                <div class="current-count" id="currentKickCount">0</div>
                <div class="count-label">Total Kicks</div>
                
                <button class="kick-btn" id="recordKickButton">
                    <span style="font-size: 30px;">👣</span> Record Kick
                </button>
                
                <button class="end-session-btn" id="endSessionButton">
                    END SESSION & SAVE
                </button>
            </div>
        </div>
        
        <div class="log-history-view" id="logHistoryView">
            <div class="record-section">
                <button class="record-button" id="startKickSession">
                    <span>👣</span>
                    START LOG
                </button>
                <p>Start a new session to count kicks.</p>
            </div>

            <div class="history-list-header">Previous Sessions</div>
            
            <?php if (!empty($movement_sessions)): ?>
                <?php foreach ($movement_sessions as $session): ?>
                    <div class="movement-session-card">
                        <div class="session-details">
                            <div class="date"><?php echo date("l, d M Y", strtotime(htmlspecialchars($session['session_date']))); ?></div>
                            <div class="time">
                                <?php echo htmlspecialchars($session['start_time']); ?> to 
                                <?php echo htmlspecialchars($session['end_time']); ?>
                            </div>
                            <div class="notes"><?php echo htmlspecialchars($session['notes']); ?></div>
                        </div>
                        <div class="kicks-count">
                            <div class="number"><?php echo htmlspecialchars($session['kicks_count']); ?></div>
                            <div class="label">Kicks</div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-data">
                    No movement sessions recorded yet. Start a new log!
                </div>
            <?php endif; ?>
        </div>

    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startButton = document.getElementById('startKickSession');
            const logHistoryView = document.getElementById('logHistoryView');
            const kickCountingView = document.getElementById('kickCountingView');
            const recordKickButton = document.getElementById('recordKickButton');
            const currentKickCount = document.getElementById('currentKickCount');
            const sessionTimer = document.getElementById('sessionTimer');
            const endSessionButton = document.getElementById('endSessionButton');
            const backButton = document.getElementById('backButton');
            
            let kickCount = 0;
            let startTime = null;
            let timerInterval = null;

            // --- Function to format the timer ---
            function formatTime(ms) {
                const totalSeconds = Math.floor(ms / 1000);
                const hours = Math.floor(totalSeconds / 3600);
                const minutes = Math.floor((totalSeconds % 3600) / 60);
                const seconds = totalSeconds % 60;

                const pad = (num) => String(num).padStart(2, '0');

                return `${pad(hours)}:${pad(minutes)}:${pad(seconds)}`;
            }

            // --- Function to update the timer display ---
            function updateTimer() {
                if (startTime) {
                    const elapsedTime = Date.now() - startTime;
                    sessionTimer.textContent = formatTime(elapsedTime);
                }
            }
            
            // --- Start Session Logic ---
            startButton.addEventListener('click', function() {
                // 1. Hide the history view and show the counter view
                logHistoryView.style.display = 'none';
                kickCountingView.style.display = 'block';
                
                // 2. Initialize count and timer
                kickCount = 0;
                currentKickCount.textContent = kickCount;
                startTime = Date.now();
                sessionTimer.textContent = '00:00:00'; // Reset display

                // 3. Start the timer
                timerInterval = setInterval(updateTimer, 1000);
            });
            
            // --- Record Kick Logic ---
            recordKickButton.addEventListener('click', function() {
                if (startTime) { // Only count if a session is active
                    kickCount++;
                    currentKickCount.textContent = kickCount;
                }
                // Optional: Provide visual feedback for the button press
                recordKickButton.style.backgroundColor = '#5cb85c'; // Change color on press
                setTimeout(() => {
                    recordKickButton.style.backgroundColor = '#2a9d8f'; // Revert after a short delay
                }, 100);
            });
            
            // --- End Session Logic ---
            endSessionButton.addEventListener('click', function() {
                clearInterval(timerInterval); // Stop the timer
                const endTime = new Date();
                const sessionDuration = formatTime(endTime.getTime() - startTime);

                // Prepare fields for backend
                const pad = (n) => String(n).padStart(2, '0');
                const d = endTime;
                const session_date = `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}`;
                const start = new Date(startTime);
                const start_time = `${pad(start.getHours())}:${pad(start.getMinutes())}`;
                const end_time = `${pad(d.getHours())}:${pad(d.getMinutes())}`;

                const notes = prompt('Notes (optional):', '') || '';

                fetch('movements.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        session_date,
                        start_time,
                        end_time,
                        kicks_count: kickCount,
                        notes
                    })
                }).then(async (res) => {
                    const data = await res.json().catch(() => ({}));
                    if (!res.ok || !data.ok) {
                        const msg = (data && data.error) ? data.error : `HTTP ${res.status}`;
                        alert('Save failed: ' + msg);
                    } else {
                        alert('Session saved successfully.');
                        // Reload to show the new session in history
                        location.reload();
                    }
                }).catch((err) => {
                    alert('Network error: ' + (err?.message || err));
                }).finally(() => {
                    // Reset UI even if save fails
                    kickCountingView.style.display = 'none';
                    logHistoryView.style.display = 'block';
                    startTime = null;
                });
            });


            // --- Back Button Default Behavior ---
            backButton.addEventListener('click', function(e) {
                // If a session is active, prompt the user before leaving
                if (startTime) {
                     e.preventDefault();
                     const confirmLeave = confirm("You have an active kick counting session. Are you sure you want to discard it and go back?");
                     if (confirmLeave) {
                         clearInterval(timerInterval); // Stop timer before leaving
                         window.location.href = backButton.href; 
                     }
                } else {
                    // Normal back link action
                    window.location.href = backButton.href; 
                }
            });
            // ------------------------------------
        });
    </script>

</body>
</html>