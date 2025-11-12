-- Add trimester field to checklist_items table
ALTER TABLE checklist_items ADD COLUMN trimester TINYINT DEFAULT 1 AFTER category;

-- Update existing checklist items with appropriate trimester values based on category
UPDATE checklist_items SET trimester = 1 WHERE category LIKE 'Trimester 22%';
UPDATE checklist_items SET trimester = 2 WHERE category LIKE 'Trimester 66%';
UPDATE checklist_items SET trimester = 3 WHERE category LIKE 'Trimester 99%';

-- For items without category or unknown category, default to trimester 1
UPDATE checklist_items SET trimester = 1 WHERE trimester IS NULL;