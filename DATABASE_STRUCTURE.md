# Database Structure Documentation

## Overview

This document describes the improved, scalable database structure for the hospitality booking system. The new structure supports hotels and apartments, property management, unit/room definitions, facilities/amenities, pricing, and availability management.

## Architecture Principles

✅ **Normalized Design**: Proper relational structure with no data duplication
✅ **Backward Compatible**: Existing `hotels` and `hotel_rooms` tables remain intact
✅ **Scalable**: Easy to extend without redesign
✅ **Clean Relationships**: Proper foreign keys and pivot tables
✅ **Future-Proof**: Ready for booking system integration

## Core Tables

### 1. `facility_categories`
Categorizes amenities/facilities for better organization.

**Columns:**
- `id` - Primary key
- `name` - Category name (e.g., "Basic Amenities", "Room Features")
- `slug` - URL-friendly identifier
- `icon` - Icon class name
- `description` - Category description
- `sort_order` - Display order
- `is_active` - Active status
- `created_at`, `updated_at`, `deleted_at`

### 2. `amenities` (Enhanced)
Facilities/amenities that can be assigned to properties and units.

**New Columns Added:**
- `facility_category_id` - Foreign key to `facility_categories`
- `slug` - URL-friendly identifier
- `description` - Detailed description
- `sort_order` - Display order
- `is_active` - Active status

**Existing Columns:**
- `id`, `title`, `icon`, `created_at`, `updated_at`, `deleted_at`

### 3. `unit_types`
Classifies room/apartment types (Standard Room, Deluxe Room, Studio Apartment, etc.).

**Columns:**
- `id` - Primary key
- `name` - Type name
- `slug` - URL-friendly identifier
- `property_type` - Enum: 'hotel', 'apartment', 'both'
- `description` - Type description
- `sort_order` - Display order
- `is_active` - Active status
- `created_at`, `updated_at`, `deleted_at`

### 4. `properties` (NEW - Unified Table)
Unified table for hotels and apartments. Replaces the need for separate tables while maintaining backward compatibility.

**Columns:**
- `id` - Primary key (maps to existing hotels.id)
- `owner_id` - Foreign key to `users` (property owner)
- `category_id` - Foreign key to `categories` (destination)
- `program_id` - Foreign key to `programs` (service)
- `partner_id` - Foreign key to `partners`
- `name` - Property name
- `slug` - URL-friendly identifier
- `property_type` - Enum: 'hotel' or 'apartment'
- `stars` - Star rating (for hotels)
- `description` - Property description
- `address`, `city`, `location` - Location details
- `latitude`, `longitude` - GPS coordinates
- `phone`, `email`, `website` - Contact information
- `featured_image` - Main image path
- `status` - Enum: 'Active', 'Inactive', 'Pending'
- `is_featured` - Featured property flag
- `is_verified` - Verified property flag
- `meta_data` - JSON for flexible additional data
- `created_at`, `updated_at`, `deleted_at`

### 5. `property_images`
Images for properties (replaces `hotel_images`).

**Columns:**
- `id` - Primary key
- `property_id` - Foreign key to `properties`
- `uploaded_by` - Foreign key to `users`
- `image_path` - Image file path
- `caption` - Image caption
- `sort_order` - Display order
- `is_primary` - Primary image flag
- `created_at`, `updated_at`, `deleted_at`

### 6. `property_facilities` (Pivot)
Many-to-many relationship between properties and facilities.

**Columns:**
- `id` - Primary key
- `property_id` - Foreign key to `properties`
- `facility_id` - Foreign key to `amenities`
- `created_at`, `updated_at`
- Unique constraint on (`property_id`, `facility_id`)

### 7. `units` (NEW - Unified Table)
Unified table for hotel rooms and apartment units (replaces `hotel_rooms`).

**Columns:**
- `id` - Primary key
- `property_id` - Foreign key to `properties`
- `unit_type_id` - Foreign key to `unit_types`
- `added_by` - Foreign key to `users`
- `name` - Optional unit name
- `slug` - URL-friendly identifier
- `description` - Unit description
- `max_occupancy` - Maximum guests
- `bedrooms`, `bathrooms`, `beds` - Room details
- `size_sqm` - Size in square meters
- `total_units` - Total inventory
- `available_units` - Currently available
- `base_price_per_night` - Base nightly rate
- `base_price_per_month` - Base monthly rate (apartments)
- `featured_image` - Main image path
- `status` - Enum: 'Available', 'Unavailable', 'Maintenance'
- `is_active` - Active status
- `meta_data` - JSON for flexible data
- `created_at`, `updated_at`, `deleted_at`

### 8. `unit_images`
Images for units (replaces `hotel_room_images`).

**Columns:**
- `id` - Primary key
- `unit_id` - Foreign key to `units`
- `uploaded_by` - Foreign key to `users`
- `image_path` - Image file path
- `caption` - Image caption
- `sort_order` - Display order
- `is_primary` - Primary image flag
- `created_at`, `updated_at`, `deleted_at`

### 9. `unit_facilities` (Pivot)
Many-to-many relationship between units and facilities (replaces JSON amenities).

**Columns:**
- `id` - Primary key
- `unit_id` - Foreign key to `units`
- `facility_id` - Foreign key to `amenities`
- `created_at`, `updated_at`
- Unique constraint on (`unit_id`, `facility_id`)

### 10. `unit_pricing`
Dynamic pricing rules for units (seasonal, weekend, holiday rates).

**Columns:**
- `id` - Primary key
- `unit_id` - Foreign key to `units`
- `price_per_night` - Nightly rate
- `price_per_month` - Monthly rate
- `weekend_price` - Weekend rate override
- `holiday_price` - Holiday rate override
- `start_date` - Pricing start date
- `end_date` - Pricing end date (null = ongoing)
- `min_nights` - Minimum stay
- `max_nights` - Maximum stay
- `min_guests` - Minimum guests
- `max_guests` - Maximum guests
- `is_active` - Active status
- `pricing_type` - Type: 'standard', 'seasonal', 'promotional'
- `notes` - Additional notes
- `created_at`, `updated_at`, `deleted_at`

### 11. `unit_availability`
Availability calendar for units (date-based availability tracking).

**Columns:**
- `id` - Primary key
- `unit_id` - Foreign key to `units`
- `date` - Date
- `available_units` - Available inventory for this date
- `status` - Enum: 'available', 'booked', 'blocked', 'maintenance'
- `price_override` - Price override for this date
- `notes` - Additional notes
- `created_at`, `updated_at`
- Unique constraint on (`unit_id`, `date`)

## Relationships

### Property Relationships
```
User (owner) → hasMany → Properties
Property → belongsTo → User (owner)
Property → belongsTo → Category (destination)
Property → belongsTo → Program (service)
Property → belongsTo → Partner
Property → hasMany → Units
Property → hasMany → PropertyImages
Property → belongsToMany → Amenities (via property_facilities)
Property → hasMany → Bookings
```

### Unit Relationships
```
Unit → belongsTo → Property
Unit → belongsTo → UnitType
Unit → belongsTo → User (added_by)
Unit → hasMany → UnitImages
Unit → belongsToMany → Amenities (via unit_facilities)
Unit → hasMany → UnitPricing
Unit → hasMany → UnitAvailability
Unit → hasMany → Bookings
```

### Facility Relationships
```
FacilityCategory → hasMany → Amenities
Amenity → belongsTo → FacilityCategory
Amenity → belongsToMany → Properties (via property_facilities)
Amenity → belongsToMany → Units (via unit_facilities)
```

## Migration Strategy

### Step 1: Run New Migrations
```bash
php artisan migrate
```

This will create all new tables without affecting existing data.

### Step 2: Run Data Migration
The migration `2025_01_20_000012_migrate_hotels_to_properties.php` will:
- Copy existing hotels to properties table
- Copy hotel_rooms to units table
- Copy hotel_images to property_images
- Copy hotel_room_images to unit_images
- Update bookings to reference new structure

### Step 3: Seed Data
```bash
php artisan db:seed --class=FacilityCategorySeeder
php artisan db:seed --class=FacilitySeeder
php artisan db:seed --class=UnitTypeSeeder
```

### Step 4: Migrate JSON Amenities (Manual)
Create an artisan command to migrate JSON amenities from `hotel_rooms.amenities` to `unit_facilities` pivot table.

## Backward Compatibility

### Existing Tables Preserved
- ✅ `hotels` - Still exists, can be used alongside `properties`
- ✅ `hotel_rooms` - Still exists, can be used alongside `units`
- ✅ `hotel_images` - Still exists
- ✅ `hotel_room_images` - Still exists
- ✅ `hotel_bookings` - Updated with `unit_id` and `property_id` columns

### Migration Path
1. **Phase 1**: New tables created, data migrated
2. **Phase 2**: Update controllers to use new models
3. **Phase 3**: Gradually deprecate old tables (optional)

## Usage Examples

### Creating a Property
```php
$property = Property::create([
    'owner_id' => auth()->id(),
    'name' => 'Grand Hotel',
    'slug' => 'grand-hotel',
    'property_type' => 'hotel',
    'stars' => '5',
    'description' => 'Luxury hotel...',
    'status' => 'Active',
]);

// Add facilities
$property->facilities()->attach([1, 2, 3, 4]);

// Add images
PropertyImage::create([
    'property_id' => $property->id,
    'image_path' => 'properties/grand-hotel-1.jpg',
    'is_primary' => true,
]);
```

### Creating a Unit
```php
$unit = Unit::create([
    'property_id' => $property->id,
    'unit_type_id' => 1, // Standard Room
    'added_by' => auth()->id(),
    'slug' => 'standard-room-101',
    'max_occupancy' => 2,
    'bedrooms' => 1,
    'bathrooms' => 1,
    'beds' => 1,
    'total_units' => 5,
    'available_units' => 5,
    'base_price_per_night' => 100.00,
    'status' => 'Available',
]);

// Add facilities
$unit->facilities()->attach([5, 6, 7, 8]);

// Add pricing rule
UnitPricing::create([
    'unit_id' => $unit->id,
    'price_per_night' => 120.00,
    'start_date' => '2025-06-01',
    'end_date' => '2025-08-31',
    'pricing_type' => 'seasonal',
]);
```

### Querying Properties
```php
// Get all hotels
$hotels = Property::hotels()->active()->get();

// Get all apartments
$apartments = Property::apartments()->active()->get();

// Get property with relationships
$property = Property::with(['units', 'images', 'facilities'])->find(1);
```

### Querying Units
```php
// Get available units
$units = Unit::available()->get();

// Get unit with pricing
$unit = Unit::with(['pricing', 'facilities'])->find(1);

// Check availability
$isAvailable = $unit->isAvailableForDates('2025-06-01', '2025-06-05');

// Get pricing for date
$pricing = $unit->getPricingForDate('2025-06-15');
```

## Benefits

1. **Unified Structure**: Hotels and apartments use the same tables
2. **Normalized Data**: No JSON storage, proper relationships
3. **Scalable**: Easy to add new property types or features
4. **Flexible Pricing**: Support for dynamic pricing rules
5. **Availability Management**: Date-based availability tracking
6. **Better Queries**: Efficient filtering and searching
7. **Future-Proof**: Ready for booking system integration

## Next Steps

1. Run migrations
2. Seed initial data
3. Update controllers to use new models
4. Migrate JSON amenities to pivot tables
5. Update views to use new structure
6. Test thoroughly
7. Deploy










