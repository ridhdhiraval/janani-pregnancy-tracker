-- Populate checklist_items with comprehensive items organized by trimester
USE janani_db;

-- Clear existing items for user 1 (for testing)
DELETE FROM checklist_items WHERE user_id = 1;

-- Trimester 1 Items (Weeks 1-12)
INSERT INTO checklist_items (user_id, item_name, item_description, category, trimester, is_completed) VALUES
(1, 'Schedule First Prenatal Visit', 'Book appointment with OB/GYN for initial checkup', 'Trimester 1: Health', 1, 0),
(1, 'Start Prenatal Vitamins', 'Begin taking folic acid and prenatal vitamins daily', 'Trimester 1: Health', 1, 0),
(1, 'Confirm Pregnancy', 'Take home pregnancy test and schedule blood work confirmation', 'Trimester 1: Health', 1, 0),
(1, 'Research Healthcare Providers', 'Find and research OB/GYN or midwife options', 'Trimester 1: Planning', 1, 0),
(1, 'Review Insurance Coverage', 'Check what prenatal care is covered by insurance', 'Trimester 1: Planning', 1, 0),
(1, 'Track Symptoms', 'Start logging pregnancy symptoms and changes', 'Trimester 1: Health', 1, 0),
(1, 'Adjust Diet', 'Eliminate alcohol, caffeine, and risky foods', 'Trimester 1: Nutrition', 1, 0),
(1, 'Stay Hydrated', 'Drink at least 8 glasses of water daily', 'Trimester 1: Health', 1, 0),
(1, 'Get Plenty of Rest', 'Aim for 8-9 hours of sleep per night', 'Trimester 1: Health', 1, 0);

-- Trimester 2 Items (Weeks 13-27)
INSERT INTO checklist_items (user_id, item_name, item_description, category, trimester, is_completed) VALUES
(1, 'Schedule Anatomy Scan', 'Book 20-week ultrasound appointment', 'Trimester 2: Health', 2, 0),
(1, 'Announce Pregnancy', 'Share pregnancy news with family and friends', 'Trimester 2: Planning', 2, 0),
(1, 'Start Maternity Shopping', 'Begin shopping for maternity clothes', 'Trimester 2: Preparation', 2, 0),
(1, 'Research Childbirth Classes', 'Find and register for prenatal classes', 'Trimester 2: Planning', 2, 0),
(1, 'Plan Baby Registry', 'Create registry for baby shower gifts', 'Trimester 2: Planning', 2, 0),
(1, 'Monitor Fetal Movement', 'Start tracking baby kicks and movements', 'Trimester 2: Health', 2, 0),
(1, 'Consider Gender Reveal', 'Plan gender reveal party or announcement', 'Trimester 2: Planning', 2, 0),
(1, 'Update Exercise Routine', 'Modify workouts for second trimester safety', 'Trimester 2: Health', 2, 0),
(1, 'Plan Maternity Leave', 'Discuss leave options with employer', 'Trimester 2: Planning', 2, 0),
(1, 'Research Pediatricians', 'Start looking for pediatric care options', 'Trimester 2: Planning', 2, 0);

-- Trimester 3 Items (Weeks 28-40)
INSERT INTO checklist_items (user_id, item_name, item_description, category, trimester, is_completed) VALUES
(1, 'Pack Hospital Bag', 'Prepare bag with essentials for labor and delivery', 'Trimester 3: Preparation', 3, 0),
(1, 'Install Car Seat', 'Install infant car seat and have it inspected', 'Trimester 3: Preparation', 3, 0),
(1, 'Finalize Birth Plan', 'Complete and discuss birth plan with healthcare provider', 'Trimester 3: Planning', 3, 0),
(1, 'Set Up Nursery', 'Prepare baby\'s room and organize essentials', 'Trimester 3: Preparation', 3, 0),
(1, 'Take Childbirth Classes', 'Attend prenatal classes with partner', 'Trimester 3: Preparation', 3, 0),
(1, 'Tour Birth Facility', 'Visit hospital or birthing center for tour', 'Trimester 3: Preparation', 3, 0),
(1, 'Prepare Freezer Meals', 'Cook and freeze meals for postpartum period', 'Trimester 3: Preparation', 3, 0),
(1, 'Wash Baby Clothes', 'Launder all baby clothes and linens', 'Trimester 3: Preparation', 3, 0),
(1, 'Stock Up on Essentials', 'Buy diapers, wipes, and other baby supplies', 'Trimester 3: Preparation', 3, 0),
(1, 'Practice Breathing Techniques', 'Learn and practice labor breathing exercises', 'Trimester 3: Health', 3, 0);

-- Verify the data was inserted
SELECT 
    trimester,
    COUNT(*) as item_count,
    GROUP_CONCAT(DISTINCT category) as categories
FROM checklist_items 
WHERE user_id = 1
GROUP BY trimester
ORDER BY trimester;

SELECT COUNT(*) as total_items FROM checklist_items WHERE user_id = 1;