-- Simple SQL to fix hotel_bookings table
-- Run this in your MySQL/phpMyAdmin

-- Check and add property_id column
ALTER TABLE `hotel_bookings` 
ADD COLUMN IF NOT EXISTS `property_id` BIGINT UNSIGNED NULL AFTER `hotel_id`;

-- Check and add unit_id column  
ALTER TABLE `hotel_bookings` 
ADD COLUMN IF NOT EXISTS `unit_id` BIGINT UNSIGNED NULL AFTER `room_id`;

-- Note: If your MySQL version doesn't support "IF NOT EXISTS" in ALTER TABLE,
-- use this instead (remove the IF NOT EXISTS part):

-- ALTER TABLE `hotel_bookings` 
-- ADD COLUMN `property_id` BIGINT UNSIGNED NULL AFTER `hotel_id`;

-- ALTER TABLE `hotel_bookings` 
-- ADD COLUMN `unit_id` BIGINT UNSIGNED NULL AFTER `room_id`;

-- Add foreign keys (only if tables exist)
-- ALTER TABLE `hotel_bookings` 
-- ADD CONSTRAINT `hotel_bookings_property_id_foreign` 
-- FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

-- ALTER TABLE `hotel_bookings` 
-- ADD CONSTRAINT `hotel_bookings_unit_id_foreign` 
-- FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE;








