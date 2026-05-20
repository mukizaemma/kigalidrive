-- Fix hotel_bookings table to add missing columns
-- Run this SQL directly in your database if migrations aren't working

-- Add property_id column if it doesn't exist
SET @dbname = DATABASE();
SET @tablename = "hotel_bookings";
SET @columnname = "property_id";
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  "SELECT 'Column property_id already exists.'",
  CONCAT("ALTER TABLE ", @tablename, " ADD COLUMN ", @columnname, " BIGINT UNSIGNED NULL AFTER hotel_id")
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Add unit_id column if it doesn't exist
SET @columnname = "unit_id";
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  "SELECT 'Column unit_id already exists.'",
  CONCAT("ALTER TABLE ", @tablename, " ADD COLUMN ", @columnname, " BIGINT UNSIGNED NULL AFTER room_id")
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Add foreign key for property_id if it doesn't exist
SET @fk_name = "hotel_bookings_property_id_foreign";
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
    WHERE
      (TABLE_SCHEMA = @dbname)
      AND (TABLE_NAME = @tablename)
      AND (CONSTRAINT_NAME = @fk_name)
  ) > 0,
  "SELECT 'Foreign key property_id already exists.'",
  CONCAT("ALTER TABLE ", @tablename, " ADD CONSTRAINT ", @fk_name, " FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE")
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Add foreign key for unit_id if it doesn't exist
SET @fk_name = "hotel_bookings_unit_id_foreign";
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
    WHERE
      (TABLE_SCHEMA = @dbname)
      AND (TABLE_NAME = @tablename)
      AND (CONSTRAINT_NAME = @fk_name)
  ) > 0,
  "SELECT 'Foreign key unit_id already exists.'",
  CONCAT("ALTER TABLE ", @tablename, " ADD CONSTRAINT ", @fk_name, " FOREIGN KEY (unit_id) REFERENCES units(id) ON DELETE CASCADE")
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;








