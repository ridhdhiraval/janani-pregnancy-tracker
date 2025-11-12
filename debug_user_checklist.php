<?php
// Debug script to check checklist items for the current user

require_once 'config/db.php';
require_once 'lib/auth.php';

session_start();

// Ensure user is logged in for testing
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // Use user ID 1 for testing
}

echo "<h2>Debugging User Checklist Items</h2>";

// Get current user
$user = current_user();

if ($user) {
    echo "<p>Current user ID: " . $user['id'] . "</p>";
    echo "<p>User email: " . htmlspecialchars($user['email']) . "</p>";
    
    // Check all checklist items for this user
    try {
        $stmt = $pdo->prepare("SELECT id, category, item_name, is_completed, trimester FROM checklist_items WHERE user_id = ?");
        $stmt->execute([$user['id']]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<h3>All Checklist Items for User " . $user['id'] . ":</h3>";
        echo "<p>Found " . count($items) . " items</p>";
        
        if (count($items) > 0) {
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>ID</th><th>Category</th><th>Item Name</th><th>Completed</th><th>Trimester</th></tr>";
            foreach ($items as $item) {
                echo "<tr>";
                echo "<td>" . $item['id'] . "</td>";
                echo "<td>" . htmlspecialchars($item['category']) . "</td>";
                echo "<td>" . htmlspecialchars($item['item_name']) . "</td>";
                echo "<td>" . $item['is_completed'] . "</td>";
                echo "<td>" . ($item['trimester'] ?? 'NULL') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        
        // Test trimester filtering
        echo "<h3>Testing Trimester Filtering:</h3>";
        for ($trimester = 1; $trimester <= 3; $trimester++) {
            $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM checklist_items WHERE user_id = ? AND trimester <= ?");
            $stmt->execute([$user['id'], $trimester]);
            $result = $stmt->fetch();
            echo "<p>Items for trimester " . $trimester . " or below: " . $result['count'] . "</p>";
        }
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    }
    
} else {
    echo "<p>No user logged in</p>";
}

echo "<hr>";
echo "<p><a href='test_checklist_functionality.php'>Back to main test</a></p>";

?>