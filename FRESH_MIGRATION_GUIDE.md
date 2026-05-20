# Fresh Database Migration Guide

## Overview
This guide provides instructions for resetting your database and running all migrations with complete table structures from the start.

## ✅ What's Been Fixed

### 1. Complete Table Structures
- **Hotels table**: Now includes `partner_id` and `address` from the start
- **Amenities table**: Now includes all columns (category_id, slug, description, sort_order, is_active) from the start
- **Hotel Bookings**: Now includes `property_id` and `unit_id` from the start for future compatibility
- **Properties table**: Complete with all attributes
- **Units table**: Complete with all attributes

### 2. Removed Redundant Migrations
- ❌ Removed "add missing columns" migrations
- ❌ Removed data migration migrations (not needed for fresh start)
- ✅ All attributes are now in base table creation

### 3. Safe Foreign Keys
- All foreign keys check if referenced tables exist
- Won't break if dependencies aren't ready
- Proper cascade/set null behaviors

## Migration Order

The migrations will run in this order:

### Core System Tables (Existing)
1. `01_create_roles_table`
2. `02_create_plans_table`
3. `03_create_countries_table`
4. `04_create_languages_table`
5. `06_create_users_table`
6. `11_create_settings_table`
7. `12_create_abouts_table`
8. `13_create_slides_table`
9. `14_create_programs_table`
10. `16_create_teams_table`

### Existing Business Tables
11. `2025_09_13000000_create_categories_table`
12. `2025_09_12000000_create_services_table`
13. `2025_11_13_000000_create_partners_table`
14. `2025_10_13000000_create_amenities_table` (✅ Now includes all columns)
15. `2025_11_14_122442_create_hotels_table` (✅ Now includes partner_id and address)
16. `2025_11_14_124138_create_hotel_rooms_table`
17. `2025_11_14_124819_create_hotel_bookings_table` (✅ Now includes property_id and unit_id)

### New Enhanced Structure
18. `2025_01_20_000001_create_facility_categories_table`
19. `2025_01_20_000002_add_category_to_amenities_table` (Only adds foreign key now)
20. `2025_01_20_000003_create_unit_types_table`
21. `2025_01_20_000004_create_properties_table`
22. `2025_01_20_000005_create_property_images_table`
23. `2025_01_20_000006_create_property_facilities_table`
24. `2025_01_20_000007_create_units_table`
25. `2025_01_20_000008_create_unit_images_table`
26. `2025_01_20_000009_create_unit_facilities_table`
27. `2025_01_20_000010_create_unit_pricing_table`
28. `2025_01_20_000012_create_unit_availability_table`

### Other Existing Tables
29. `2025_11_13_000000_create_trips_table`
30. `2025_11_14_125003_create_cars_table`
31. `2025_11_20_152653_create_hotel_images_table`
32. `2025_11_20_152920_create_hotel_room_images_table`
33. `2025_11_28_061953_create_amenity_hotel_rooms_table`
34. `2025_11_14_124819_create_hotel_bookings_table`
35. And other existing tables...

## Step-by-Step Reset Process

### Step 1: Backup (IMPORTANT!)
```bash
# Export your database
mysqldump -u root -p kjoseph > backup_$(date +%Y%m%d_%H%M%S).sql
```

### Step 2: Reset Database
```bash
# Drop all tables and re-run all migrations
php artisan migrate:fresh
```

### Step 3: Seed Initial Data
```bash
php artisan db:seed --class=FacilityCategorySeeder
php artisan db:seed --class=FacilitySeeder
php artisan db:seed --class=UnitTypeSeeder
```

## Key Improvements

### Hotels Table
- ✅ `partner_id` column included from start
- ✅ `address` column included from start
- ✅ Safe foreign key constraints

### Amenities Table
- ✅ `facility_category_id` included from start
- ✅ `slug` included from start
- ✅ `description` included from start
- ✅ `sort_order` included from start
- ✅ `is_active` included from start

### Hotel Bookings Table
- ✅ `property_id` column for new structure
- ✅ `unit_id` column for new structure
- ✅ Backward compatible with `hotel_id` and `room_id`
- ✅ All columns nullable to support both old and new

### Properties Table
- ✅ Complete structure from start
- ✅ All relationships properly defined
- ✅ Safe foreign key checks

### Units Table
- ✅ Complete structure from start
- ✅ All relationships properly defined
- ✅ Safe foreign key checks

## Verification

After migrations complete:

```bash
php artisan tinker
```

```php
// Check tables exist
Schema::hasTable('properties'); // true
Schema::hasTable('units'); // true
Schema::hasTable('facility_categories'); // true

// Check columns exist
Schema::hasColumn('hotels', 'partner_id'); // true
Schema::hasColumn('hotels', 'address'); // true
Schema::hasColumn('amenities', 'facility_category_id'); // true
Schema::hasColumn('hotel_bookings', 'property_id'); // true
Schema::hasColumn('hotel_bookings', 'unit_id'); // true

// Test relationships
$property = App\Models\Property::first();
$property->units; // Should work
$property->facilities; // Should work
```

## Notes

- ✅ All tables have complete structures from creation
- ✅ No "add missing columns" migrations needed
- ✅ Safe foreign key constraints that won't break
- ✅ Backward compatible with existing code
- ✅ Ready for future enhancements

## Troubleshooting

If you encounter issues:

1. **Check migration status:**
   ```bash
   php artisan migrate:status
   ```

2. **Rollback if needed:**
   ```bash
   php artisan migrate:rollback --step=1
   ```

3. **Check for table existence:**
   ```bash
   php artisan tinker
   >>> Schema::hasTable('properties')
   ```

All migrations are now complete and ready for a fresh database reset!










