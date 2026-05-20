# Complete Database Structure - All Attributes from Start

## Overview
All tables now have complete structures from their initial creation. No "add missing columns" migrations needed.

## Core Tables with Complete Structures

### 1. `hotels` Table (Updated)
**File:** `2025_11_14_122442_create_hotels_table.php`

**Complete Columns:**
- `id` - Primary key
- `added_by` - Foreign key to users
- `category_id` - Foreign key to categories
- `program_id` - Foreign key to programs
- `partner_id` - Foreign key to partners (✅ Added from start)
- `name` - Hotel name
- `slug` - URL-friendly identifier
- `type` - Hotel type
- `stars` - Star rating
- `address` - Address (✅ Added from start)
- `location` - Location
- `phone` - Phone number
- `email` - Email address
- `city` - City
- `latitude` - GPS latitude
- `longitude` - GPS longitude
- `image` - Featured image
- `description` - Description
- `status` - Active/Inactive
- `created_at`, `updated_at`, `deleted_at`

### 2. `amenities` Table (Updated)
**File:** `2025_10_13000000_create_amenities_table.php`

**Complete Columns:**
- `id` - Primary key
- `facility_category_id` - Foreign key to facility_categories (✅ Added from start)
- `title` - Facility name
- `slug` - URL-friendly identifier (✅ Added from start)
- `icon` - Icon class
- `description` - Description (✅ Added from start)
- `sort_order` - Display order (✅ Added from start)
- `is_active` - Active status (✅ Added from start)
- `created_at`, `updated_at`, `deleted_at`

### 3. `hotel_bookings` Table (Updated)
**File:** `2025_11_14_124819_create_hotel_bookings_table.php`

**Complete Columns:**
- `id` - Primary key
- `user_id` - Foreign key to users
- `hotel_id` - Foreign key to hotels (backward compatible)
- `property_id` - Foreign key to properties (✅ Added from start)
- `room_id` - Foreign key to hotel_rooms (backward compatible)
- `unit_id` - Foreign key to units (✅ Added from start)
- `check_in` - Check-in date
- `check_out` - Check-out date
- `guests_count` - Number of guests
- `total_amount` - Total booking amount
- `description` - Booking notes
- `payment_status` - Payment status
- `booking_status` - Booking status
- `reference_number` - Unique reference
- `created_at`, `updated_at`, `deleted_at`

### 4. `properties` Table (New - Complete)
**File:** `2025_01_20_000004_create_properties_table.php`

**All Columns from Start:**
- `id`, `owner_id`, `category_id`, `program_id`, `partner_id`
- `name`, `slug`, `property_type`, `stars`, `description`
- `address`, `city`, `location`, `latitude`, `longitude`
- `phone`, `email`, `website`
- `featured_image`
- `status`, `is_featured`, `is_verified`
- `meta_data` (JSON)
- `created_at`, `updated_at`, `deleted_at`

### 5. `units` Table (New - Complete)
**File:** `2025_01_20_000007_create_units_table.php`

**All Columns from Start:**
- `id`, `property_id`, `unit_type_id`, `added_by`
- `name`, `slug`, `description`
- `max_occupancy`, `bedrooms`, `bathrooms`, `beds`, `size_sqm`
- `total_units`, `available_units`
- `base_price_per_night`, `base_price_per_month`
- `featured_image`
- `status`, `is_active`
- `meta_data` (JSON)
- `created_at`, `updated_at`, `deleted_at`

### 6. `facility_categories` Table (New)
**File:** `2025_01_20_000001_create_facility_categories_table.php`

**Complete Structure:**
- `id`, `name`, `slug`, `icon`, `description`
- `sort_order`, `is_active`
- `created_at`, `updated_at`, `deleted_at`

### 7. `unit_types` Table (New)
**File:** `2025_01_20_000003_create_unit_types_table.php`

**Complete Structure:**
- `id`, `name`, `slug`, `property_type`, `description`
- `sort_order`, `is_active`
- `created_at`, `updated_at`, `deleted_at`

### 8. Supporting Tables (All Complete)
- `property_images` - Complete
- `property_facilities` - Complete pivot table
- `unit_images` - Complete
- `unit_facilities` - Complete pivot table
- `unit_pricing` - Complete
- `unit_availability` - Complete

## Migration Execution Order

### Phase 1: Core System (Must Run First)
1. Users, Roles, Plans, Countries, Languages
2. Settings, Abouts, Slides, Programs, Teams
3. Categories, Services, Partners

### Phase 2: Base Business Tables
4. Amenities (with all columns)
5. Hotels (with partner_id and address)
6. Hotel Rooms
7. Hotel Bookings (with property_id and unit_id)

### Phase 3: Enhanced Structure
8. Facility Categories
9. Add foreign key to amenities (facility_category_id)
10. Unit Types
11. Properties
12. Property Images
13. Property Facilities
14. Units
15. Unit Images
16. Unit Facilities
17. Unit Pricing
18. Unit Availability

## Key Features

✅ **No Missing Columns**: All tables have complete structures from creation
✅ **No Add-Column Migrations**: Everything included from start
✅ **Safe Foreign Keys**: Check for table existence before creating
✅ **Backward Compatible**: Old tables remain, new tables work alongside
✅ **Future Ready**: Ready for booking system integration

## Reset Command

```bash
# Fresh start - drops all tables and recreates
php artisan migrate:fresh

# Seed initial data
php artisan db:seed --class=FacilityCategorySeeder
php artisan db:seed --class=FacilitySeeder
php artisan db:seed --class=UnitTypeSeeder
```

## Verification Checklist

After running migrations, verify:

- [ ] `hotels` table has `partner_id` column
- [ ] `hotels` table has `address` column
- [ ] `amenities` table has `facility_category_id` column
- [ ] `amenities` table has `slug`, `description`, `sort_order`, `is_active` columns
- [ ] `hotel_bookings` table has `property_id` column
- [ ] `hotel_bookings` table has `unit_id` column
- [ ] `properties` table exists with all columns
- [ ] `units` table exists with all columns
- [ ] All foreign keys are properly set
- [ ] All pivot tables exist
- [ ] No migration errors

## Notes

- All migrations are idempotent (safe to run multiple times)
- Foreign keys check for table existence
- No data loss during migration
- Application remains functional during transition










