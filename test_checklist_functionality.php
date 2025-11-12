<?php
// Test script to verify checklist functionality with trimester support

require_once 'config/db.php';
require_once 'lib/auth.php';

// Start session
session_start();

// Test user login (assuming user ID 1 exists)
$_SESSION['user_id'] = 1;

// Get user data
$user = current_user();

echo "<h2>Testing Checklist Functionality</h2>";

if ($user) {
    echo "<p>User logged in: " . htmlspecialchars($user['email']) . "</p>";
    
    // Test pregnancy data retrieval
    $stmt = $pdo->prepare('SELECT edd FROM pregnancies WHERE user_id = ? AND status = "active" ORDER BY created_at DESC LIMIT 1');
    $stmt->execute([$user['id']]);
    $pregnancy = $stmt->fetch();
    
    if ($pregnancy && !empty($pregnancy['edd'])) {
        $edd = $pregnancy['edd'];
        echo "<p>EDD found: " . $edd . "</p>";
        
        // Calculate current trimester
        $today = new DateTimeImmutable('today');
        $dueDate = DateTimeImmutable::createFromFormat('Y-m-d', $edd);
        if ($dueDate) {
            $interval = $today->diff($dueDate);
            $days_to_go = (int)$interval->format('%r%a');
            $total_days = 280;
            $days_done = max(0, $total_days - max(0, $days_to_go));
            $weeks_done = floor($days_done / 7);
            
            // Determine current trimester based on weeks
            if ($weeks_done <= 12) {
                $current_trimester = 1;
            } elseif ($weeks_done <= 27) {
                $current_trimester = 2;
            } else {
                $current_trimester = 3;
            }
            
            echo "<p>Weeks done: " . $weeks_done . "</p>";
            echo "<p>Current trimester: " . $current_trimester . "</p>";
        }
    } else {
        echo "<p>No pregnancy data found, using default trimester 1</p>";
        $current_trimester = 1;
    }
    
    // Test checklist items retrieval with trimester filter
    echo "<h3>Checklist Items for Current Trimester</h3>";
    $stmt = $pdo->prepare('SELECT id, category, item_name as task, is_completed as completed, trimester FROM checklist_items WHERE user_id = ? AND trimester <= ? ORDER BY trimester, category, id');
    $stmt->execute([$user['id'], $current_trimester]);
    $checklist_data = $stmt->fetchAll();
    
    echo "<p>Found " . count($checklist_data) . " checklist items for trimester " . $current_trimester . " or below</p>";
    
    if (count($checklist_data) > 0) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Category</th><th>Task</th><th>Completed</th><th>Trimester</th></tr>";
        foreach ($checklist_data as $task) {
            echo "<tr>";
            echo "<td>" . $task['id'] . "</td>";
            echo "<td>" . htmlspecialchars($task['category']) . "</td>";
            echo "<td>" . htmlspecialchars($task['task']) . "</td>";
            echo "<td>" . ($task['completed'] ? 'Yes' : 'No') . "</td>";
            echo "<td>" . $task['trimester'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Calculate progress
        $total_tasks = count($checklist_data);
        $completed_tasks = count(array_filter($checklist_data, function($task) {
            return $task['completed'] == 1;
        }));
        $progress_percent = $total_tasks > 0 ? round(($completed_tasks / $total_tasks) * 100) : 0;
        
        echo "<p><strong>Progress: " . $completed_tasks . " / " . $total_tasks . " tasks completed (" . $progress_percent . "%)</strong></p>";
    }
    
} else {
    echo "<p>No user logged in</p>";
}

echo "<hr>";
echo "<p><a href='checklist.php'>Go to actual checklist page</a></p>";
echo "<p><a href='auth/logout.php'>Logout</a></p>";

?>