# Database Migrations Guide

## Migration Order

All migrations are numbered sequentially and will run in this order:

### Phase 1: Core Structure (000001-000010)
1. **000001** - `create_facility_categories_table` - Categories for amenities
2. **000002** - `add_category_to_amenities_table` - Enhance amenities with categories
3. **000003** - `create_unit_types_table` - Room/apartment type classifications
4. **000004** - `create_properties_table` - Unified properties table (hotels + apartments)
5. **000005** - `create_property_images_table` - Property image management
6. **000006** - `create_property_facilities_table` - Property-amenity pivot
7. **000007** - `create_units_table` - Unified units table (rooms + apartments)
8. **000008** - `create_unit_images_table` - Unit image management
9. **000009** - `create_unit_facilities_table` - Unit-amenity pivot
10. **000010** - `create_unit_pricing_table` - Dynamic pricing rules

### Phase 2: Preparation & Data Migration (000011-000015)
11. **000011** - `add_missing_columns_to_hotels_table` - Adds partner_id and address to hotels
12. **000012** - `create_unit_availability_table` - Availability calendar
13. **000013** - `migrate_hotels_to_properties` - Migrates existing data (SAFE - handles missing columns)
14. **000014** - `update_bookings_for_units` - Updates bookings table (SAFE - with error handling)
15. **000015** - `verify_migration_integrity` - Verifies and fixes data integrity

## Key Features

### ✅ Safe Migrations
- All data migrations check for column existence before use
- Missing columns are handled gracefully (NULL values)
- Duplicate prevention with `WHERE NOT EXISTS`
- Try-catch blocks for critical operations

### ✅ Backward Compatible
- Old `hotels` and `hotel_rooms` tables remain intact
- Existing code continues to work
- Gradual migration path available

### ✅ Relationship Integrity
- All foreign keys properly defined
- Cascade deletes where appropriate
- Nullable foreign keys for optional relationships

## Running Migrations

### Fresh Start (Development)
```bash
php artisan migrate:fresh
php artisan db:seed --class=FacilityCategorySeeder
php artisan db:seed --class=FacilitySeeder
php artisan db:seed --class=UnitTypeSeeder
```

### Production (Preserve Data)
```bash
php artisan migrate
```

## Troubleshooting

### If migration 000013 fails:
- Check if `hotels` table exists
- Verify `partners` table exists (created earlier)
- Check database connection

### If foreign key errors:
- Ensure all referenced tables exist
- Check that referenced IDs exist in parent tables
- Use `php artisan migrate:status` to see what's been run

### If duplicate entry errors:
- Migrations use `WHERE NOT EXISTS` to prevent duplicates
- Safe to re-run migrations
- Check migration status first

## Migration Dependencies

```
facility_categories (000001)
    ↓
amenities (000002) - adds category_id
    ↓
properties (000004)
    ↓
property_facilities (000006) - needs properties + amenities
    ↓
units (000007) - needs properties
    ↓
unit_facilities (000009) - needs units + amenities
    ↓
hotels (000011) - adds missing columns
    ↓
migrate data (000013) - migrates hotels → properties
    ↓
update bookings (000014) - links bookings to new structure
```

## Verification

After migrations, verify structure:

```php
// Check tables
Schema::hasTable('properties'); // true
Schema::hasTable('units'); // true
Schema::hasTable('facility_categories'); // true

// Check relationships
$property = Property::first();
$property->units; // Should work
$property->facilities; // Should work
```

## Notes

- Migration 000011 adds missing columns BEFORE data migration (000013)
- Migration 000013 safely handles missing columns
- Migration 000014 safely updates bookings with error handling
- All migrations are idempotent (safe to run multiple times)










