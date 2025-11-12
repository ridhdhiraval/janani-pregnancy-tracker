-- Migration: Add time_of_birth column to babies table if missing
USE janani_db;

-- Only add the column if it does not exist
ALTER TABLE babies ADD COLUMN time_of_birth TIME DEFAULT NULL AFTER dob;

-- No data transformation needed; values are optional and can remain NULL
