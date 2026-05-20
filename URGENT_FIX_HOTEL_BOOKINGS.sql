-- URGENT FIX: Make hotel_id and room_id nullable in hotel_bookings table
-- Run this SQL immediately in your database (phpMyAdmin or MySQL client)

-- First, drop foreign key constraints if they exist (we'll recreate them)
ALTER TABLE `hotel_bookings` 
DROP FOREIGN KEY IF EXISTS `hotel_bookings_hotel_id_foreign`;

ALTER TABLE `hotel_bookings` 
DROP FOREIGN KEY IF EXISTS `hotel_bookings_room_id_foreign`;

-- Make hotel_id nullable
ALTER TABLE `hotel_bookings` 
MODIFY COLUMN `hotel_id` BIGINT UNSIGNED NULL;

-- Make room_id nullable
ALTER TABLE `hotel_bookings` 
MODIFY COLUMN `room_id` BIGINT UNSIGNED NULL;

-- Recreate foreign key constraints (only if tables exist)
-- Uncomment these if you want to keep the foreign keys
-- ALTER TABLE `hotel_bookings` 
-- ADD CONSTRAINT `hotel_bookings_hotel_id_foreign` 
-- FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE;

-- ALTER TABLE `hotel_bookings` 
-- ADD CONSTRAINT `hotel_bookings_room_id_foreign` 
-- FOREIGN KEY (`room_id`) REFERENCES `hotel_rooms` (`id`) ON DELETE CASCADE;








