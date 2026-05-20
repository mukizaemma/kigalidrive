# Database Reset and Migration Guide

## Overview
This guide will help you reset your database and apply all new migrations cleanly.

## Step 1: Backup Your Data (IMPORTANT!)

Before resetting, backup any important data:

```bash
# Export database
mysqldump -u root -p kjoseph > backup_$(date +%Y%m%d_%H%M%S).sql
```

## Step 2: Reset Database

### Option A: Fresh Start (Recommended for Development)

```bash
# Drop all tables and re-run migrations
php artisan migrate:fresh

# Or with seeders
php artisan migrate:fresh --seed
```

### Option B: Rollback and Re-migrate

```bash
# Rollback all migrations
php artisan migrate:reset

# Run all migrations again
php artisan migrate
```

## Step 3: Run New Migrations

The migrations will run in this order (correctly sequenced):

1. ✅ `2025_01_20_000001_create_facility_categories_table`
2. ✅ `2025_01_20_000002_add_category_to_amenities_table`
3. ✅ `2025_01_20_000003_create_unit_types_table`
4. ✅ `2025_01_20_000004_create_properties_table`
5. ✅ `2025_01_20_000005_create_property_images_table`
6. ✅ `2025_01_20_000006_create_property_facilities_table`
7. ✅ `2025_01_20_000007_create_units_table`
8. ✅ `2025_01_20_000008_create_unit_images_table`
9. ✅ `2025_01_20_000009_create_unit_facilities_table`
10. ✅ `2025_01_20_000010_create_unit_pricing_table`
11. ✅ `2025_01_20_000011_add_missing_columns_to_hotels_table` (Adds partner_id and address to hotels)
12. ✅ `2025_01_20_000012_create_unit_availability_table`
13. ✅ `2025_01_20_000013_migrate_hotels_to_properties` (Fixed - handles missing columns gracefully)
14. ✅ `2025_01_20_000014_update_bookings_for_units` (Fixed - safe migration with error handling)
15. ✅ `2025_01_20_000015_verify_migration_integrity` (Verifies data integrity)

## Step 4: Seed Initial Data

```bash
php artisan db:seed --class=FacilityCategorySeeder
php artisan db:seed --class=FacilitySeeder
php artisan db:seed --class=UnitTypeSeeder
```

## Migration Fixes Applied

### 1. Fixed Data Migration (2025_01_20_000012)
- ✅ Checks if `partner_id` column exists before using it
- ✅ Checks if `address` column exists before using it
- ✅ Uses NULL for missing columns instead of failing
- ✅ Prevents duplicate entries with WHERE NOT EXISTS

### 2. Fixed Image Migrations
- ✅ Checks for `caption` column existence
- ✅ Prevents duplicate image entries
- ✅ Handles missing columns gracefully

### 3. Fixed Bookings Migration (2025_01_20_000013)
- ✅ Wrapped in try-catch to prevent failures
- ✅ Checks table existence before migrating
- ✅ Only updates if relationships exist

### 4. Added Missing Columns Migration (2025_01_20_000014)
- ✅ Adds `partner_id` to hotels table if missing
- ✅ Adds `address` to hotels table if missing
- ✅ Safe to run multiple times

## Troubleshooting

### If migrations fail:

1. **Check migration status:**
   ```bash
   php artisan migrate:status
   ```

2. **Rollback specific migration:**
   ```bash
   php artisan migrate:rollback --step=1
   ```

3. **Check for column existence:**
   ```bash
   php artisan tinker
   >>> Schema::hasColumn('hotels', 'partner_id')
   ```

### Common Issues:

**Issue:** Column not found error
**Solution:** Run `2025_01_20_000014_add_missing_columns_to_hotels_table` first

**Issue:** Foreign key constraint fails
**Solution:** Ensure all referenced tables exist (partners, categories, programs)

**Issue:** Duplicate entry errors
**Solution:** The migrations now use `WHERE NOT EXISTS` to prevent duplicates

## Verification

After migrations complete, verify the structure:

```bash
php artisan tinker
```

```php
// Check tables exist
Schema::hasTable('properties'); // Should return true
Schema::hasTable('units'); // Should return true
Schema::hasTable('facility_categories'); // Should return true

// Check relationships
$property = App\Models\Property::first();
$property->units; // Should work
$property->facilities; // Should work
```

## Next Steps

1. ✅ All migrations should run successfully
2. ✅ Data from hotels will be migrated to properties
3. ✅ You can start using the new Property and Unit models
4. ✅ Old Hotel and HotelRoom models still work (backward compatible)

## Notes

- The `hotels` and `hotel_rooms` tables remain intact for backward compatibility
- New data should use `properties` and `units` tables
- Gradually migrate your controllers to use new models
- Old models will continue to work during transition period

