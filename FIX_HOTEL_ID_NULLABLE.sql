-- Fix hotel_bookings table to make hotel_id and room_id nullable
-- This allows bookings to use property_id/unit_id without requiring hotel_id/room_id

ALTER TABLE `hotel_bookings` 
MODIFY COLUMN `hotel_id` BIGINT UNSIGNED NULL;

ALTER TABLE `hotel_bookings` 
MODIFY COLUMN `room_id` BIGINT UNSIGNED NULL;








