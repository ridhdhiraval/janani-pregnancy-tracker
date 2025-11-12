<?php
// Test script to check if checklist_items table exists and is working
require_once __DIR__ . '/config/db.php';

try {
    // Check if table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'checklist_items'");
    $table_exists = $stmt->rowCount() > 0;
    
    if ($table_exists) {
        echo "✅ checklist_items table exists!\n";
        
        // Test the query from 5index.php
        $user_id = 1; // Test user ID
        $stmt_checklist = $pdo->prepare('SELECT COUNT(*) as total, SUM(is_completed) as completed FROM checklist_items WHERE user_id = ?');
        $stmt_checklist->execute([$user_id]);
        $checklist_stats = $stmt_checklist->fetch();
        
        echo "✅ Query from 5index.php works!\n";
        echo "Total checklist items: " . $checklist_stats['total'] . "\n";
        echo "Completed items: " . $checklist_stats['completed'] . "\n";
        
    } else {
        echo "❌ checklist_items table does NOT exist!\n";
        echo "Please run the checklist_items_table.sql file in phpMyAdmin.\n";
        echo "Steps:\n";
        echo "1. Open phpMyAdmin\n";
        echo "2. Select janani_db database\n";
        echo "3. Click on SQL tab\n";
        echo "4. Copy and paste the contents of checklist_items_table.sql\n";
        echo "5. Click Go to execute\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
    echo "Error code: " . $e->getCode() . "\n";
}
?>