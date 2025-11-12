<?php
// Script to populate checklist items with trimester data

require_once 'config/db.php';

echo "<h2>Populating Checklist Items</h2>";

try {
    // Clear existing items for user 1
    $stmt = $pdo->prepare("DELETE FROM checklist_items WHERE user_id = 1");
    $stmt->execute();
    echo "<p>Cleared existing checklist items for user 1</p>";
    
    // Trimester 1 Items (Weeks 1-12)
    $trimester1_items = [
        ['Schedule First Prenatal Visit', 'Book appointment with OB/GYN for initial checkup', 'Trimester 1: Health', 1],
        ['Start Prenatal Vitamins', 'Begin taking folic acid and prenatal vitamins daily', 'Trimester 1: Health', 1],
        ['Confirm Pregnancy', 'Take home pregnancy test and schedule blood work confirmation', 'Trimester 1: Health', 1],
        ['Research Healthcare Providers', 'Find and research OB/GYN or midwife options', 'Trimester 1: Planning', 1],
        ['Review Insurance Coverage', 'Check what prenatal care is covered by insurance', 'Trimester 1: Planning', 1],
        ['Track Symptoms', 'Start logging pregnancy symptoms and changes', 'Trimester 1: Health', 1],
        ['Adjust Diet', 'Eliminate alcohol, caffeine, and risky foods', 'Trimester 1: Nutrition', 1],
        ['Stay Hydrated', 'Drink at least 8 glasses of water daily', 'Trimester 1: Health', 1],
        ['Get Plenty of Rest', 'Aim for 8-9 hours of sleep per night', 'Trimester 1: Health', 1]
    ];
    
    foreach ($trimester1_items as $item) {
        $stmt = $pdo->prepare("INSERT INTO checklist_items (user_id, item_name, item_description, category, trimester, is_completed) VALUES (?, ?, ?, ?, ?, 0)");
        $stmt->execute([1, $item[0], $item[1], $item[2], $item[3]]);
    }
    echo "<p>Added " . count($trimester1_items) . " trimester 1 items</p>";
    
    // Trimester 2 Items (Weeks 13-27)
    $trimester2_items = [
        ['Schedule Anatomy Scan', 'Book 20-week ultrasound appointment', 'Trimester 2: Health', 2],
        ['Announce Pregnancy', 'Share pregnancy news with family and friends', 'Trimester 2: Planning', 2],
        ['Start Maternity Shopping', 'Begin shopping for maternity clothes', 'Trimester 2: Preparation', 2],
        ['Research Childbirth Classes', 'Find and register for prenatal classes', 'Trimester 2: Planning', 2],
        ['Plan Baby Registry', 'Create registry for baby shower gifts', 'Trimester 2: Planning', 2],
        ['Monitor Fetal Movement', 'Start tracking baby kicks and movements', 'Trimester 2: Health', 2],
        ['Consider Gender Reveal', 'Plan gender reveal party or announcement', 'Trimester 2: Planning', 2],
        ['Update Exercise Routine', 'Modify workouts for second trimester safety', 'Trimester 2: Health', 2],
        ['Plan Maternity Leave', 'Discuss leave options with employer', 'Trimester 2: Planning', 2],
        ['Research Pediatricians', 'Start looking for pediatric care options', 'Trimester 2: Planning', 2]
    ];
    
    foreach ($trimester2_items as $item) {
        $stmt = $pdo->prepare("INSERT INTO checklist_items (user_id, item_name, item_description, category, trimester, is_completed) VALUES (?, ?, ?, ?, ?, 0)");
        $stmt->execute([1, $item[0], $item[1], $item[2], $item[3]]);
    }
    echo "<p>Added " . count($trimester2_items) . " trimester 2 items</p>";
    
    // Trimester 3 Items (Weeks 28-40)
    $trimester3_items = [
        ['Pack Hospital Bag', 'Prepare bag with essentials for labor and delivery', 'Trimester 3: Preparation', 3],
        ['Install Car Seat', 'Install infant car seat and have it inspected', 'Trimester 3: Preparation', 3],
        ['Finalize Birth Plan', 'Complete and discuss birth plan with healthcare provider', 'Trimester 3: Planning', 3],
        ['Set Up Nursery', 'Prepare baby\'s room and organize essentials', 'Trimester 3: Preparation', 3],
        ['Take Childbirth Classes', 'Attend prenatal classes with partner', 'Trimester 3: Preparation', 3],
        ['Tour Birth Facility', 'Visit hospital or birthing center for tour', 'Trimester 3: Preparation', 3],
        ['Prepare Freezer Meals', 'Cook and freeze meals for postpartum period', 'Trimester 3: Preparation', 3],
        ['Wash Baby Clothes', 'Launder all baby clothes and linens', 'Trimester 3: Preparation', 3],
        ['Stock Up on Essentials', 'Buy diapers, wipes, and other baby supplies', 'Trimester 3: Preparation', 3],
        ['Practice Breathing Techniques', 'Learn and practice labor breathing exercises', 'Trimester 3: Health', 3]
    ];
    
    foreach ($trimester3_items as $item) {
        $stmt = $pdo->prepare("INSERT INTO checklist_items (user_id, item_name, item_description, category, trimester, is_completed) VALUES (?, ?, ?, ?, ?, 0)");
        $stmt->execute([1, $item[0], $item[1], $item[2], $item[3]]);
    }
    echo "<p>Added " . count($trimester3_items) . " trimester 3 items</p>";
    
    // Verify the data was inserted
    $stmt = $pdo->prepare("SELECT trimester, COUNT(*) as item_count FROM checklist_items WHERE user_id = 1 GROUP BY trimester ORDER BY trimester");
    $stmt->execute();
    $results = $stmt->fetchAll();
    
    echo "<h3>Summary:</h3>";
    foreach ($results as $row) {
        echo "<p>Trimester " . $row['trimester'] . ": " . $row['item_count'] . " items</p>";
    }
    
    $total_stmt = $pdo->prepare("SELECT COUNT(*) as total FROM checklist_items WHERE user_id = 1");
    $total_stmt->execute();
    $total = $total_stmt->fetch();
    echo "<p><strong>Total items: " . $total['total'] . "</strong></p>";
    
    echo "<p style='color: green;'>✓ Checklist items populated successfully!</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><a href='test_checklist_functionality.php'>Test the functionality</a></p>";
echo "<p><a href='checklist.php'>Go to checklist page</a></p>";

?>