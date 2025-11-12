<?php
// Debug script to check the checklist_items table structure and data

require_once 'config/db.php';

echo "<h2>Debugging Checklist Table</h2>";

// Check if trimester column exists
try {
    $stmt = $pdo->query("DESCRIBE checklist_items");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Table Structure:</h3>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($column['Field']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Key']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Default']) . "</td>";
        echo "<td>" . htmlspecialchars($column['Extra']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Check if trimester column exists
    $trimester_exists = false;
    foreach ($columns as $column) {
        if ($column['Field'] === 'trimester') {
            $trimester_exists = true;
            break;
        }
    }
    
    if ($trimester_exists) {
        echo "<p style='color: green;'>✓ Trimester column exists!</p>";
    } else {
        echo "<p style='color: red;'>✗ Trimester column does not exist!</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error checking table structure: " . $e->getMessage() . "</p>";
}

// Check sample data
try {
    echo "<h3>Sample Checklist Items:</h3>";
    $stmt = $pdo->query("SELECT id, category, item_name, is_completed, trimester FROM checklist_items LIMIT 10");
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
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
    } else {
        echo "<p>No checklist items found in database</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error checking data: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><a href='test_checklist_functionality.php'>Back to test</a></p>";

?>