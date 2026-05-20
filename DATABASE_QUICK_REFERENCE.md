# Database Quick Reference Guide

## Quick Model Usage

### Property Management

```php
// Get user's properties
$properties = auth()->user()->properties;

// Create property
$property = Property::create([
    'owner_id' => auth()->id(),
    'name' => 'My Hotel',
    'slug' => 'my-hotel',
    'property_type' => 'hotel', // or 'apartment'
    'status' => 'Active',
]);

// Get property with all relationships
$property = Property::with(['units', 'images', 'facilities', 'owner'])->find($id);

// Filter properties
$hotels = Property::hotels()->active()->featured()->get();
$apartments = Property::apartments()->active()->get();
```

### Unit Management

```php
// Create unit
$unit = Unit::create([
    'property_id' => $property->id,
    'unit_type_id' => 1,
    'slug' => 'room-101',
    'max_occupancy' => 2,
    'base_price_per_night' => 100.00,
    'status' => 'Available',
]);

// Get available units
$availableUnits = Unit::available()->get();

// Check availability
$isAvailable = $unit->isAvailableForDates($checkIn, $checkOut);

// Get pricing for date
$pricing = $unit->getPricingForDate($date);
```

### Facilities/Amenities

```php
// Get all facilities by category
$categories = FacilityCategory::with('facilities')->get();

// Add facilities to property
$property->facilities()->attach([1, 2, 3]);

// Add facilities to unit
$unit->facilities()->sync([4, 5, 6]);

// Get property facilities
$facilities = $property->facilities;
```

### Pricing

```php
// Create pricing rule
UnitPricing::create([
    'unit_id' => $unit->id,
    'price_per_night' => 150.00,
    'start_date' => '2025-06-01',
    'end_date' => '2025-08-31',
    'pricing_type' => 'seasonal',
    'min_nights' => 2,
]);

// Get active pricing for date
$pricing = $unit->pricing()
    ->active()
    ->validForDate($date)
    ->first();
```

### Availability

```php
// Set availability for date
UnitAvailability::create([
    'unit_id' => $unit->id,
    'date' => '2025-06-15',
    'available_units' => 3,
    'status' => 'available',
]);

// Block date
UnitAvailability::updateOrCreate(
    ['unit_id' => $unit->id, 'date' => $date],
    ['status' => 'blocked', 'available_units' => 0]
);

// Get availability for date range
$availability = $unit->availability()
    ->dateRange($checkIn, $checkOut)
    ->available()
    ->get();
```

### Images

```php
// Add property image
PropertyImage::create([
    'property_id' => $property->id,
    'image_path' => 'properties/hotel-1.jpg',
    'is_primary' => true,
]);

// Add unit image
UnitImage::create([
    'unit_id' => $unit->id,
    'image_path' => 'units/room-101-1.jpg',
    'is_primary' => true,
]);

// Get primary image
$primaryImage = $property->primaryImage;
```

## Common Queries

### Search Properties
```php
Property::where('name', 'like', "%{$search}%")
    ->orWhere('city', 'like', "%{$search}%")
    ->active()
    ->get();
```

### Filter by Facilities
```php
Property::whereHas('facilities', function($q) {
    $q->whereIn('amenities.id', [1, 2, 3]);
})->get();
```

### Get Units with Pricing
```php
Unit::with(['pricing' => function($q) use ($date) {
    $q->active()->validForDate($date);
}])->available()->get();
```

### Check Unit Availability
```php
$unit = Unit::find($id);
if ($unit->isAvailableForDates($checkIn, $checkOut)) {
    // Unit is available
}
```

## Model Relationships Summary

| Model | Relationship | Target | Method |
|-------|-------------|--------|--------|
| Property | belongsTo | User (owner) | `owner()` |
| Property | hasMany | Unit | `units()` |
| Property | hasMany | PropertyImage | `images()` |
| Property | belongsToMany | Amenity | `facilities()` |
| Unit | belongsTo | Property | `property()` |
| Unit | belongsTo | UnitType | `unitType()` |
| Unit | hasMany | UnitImage | `images()` |
| Unit | belongsToMany | Amenity | `facilities()` |
| Unit | hasMany | UnitPricing | `pricing()` |
| Unit | hasMany | UnitAvailability | `availability()` |
| Amenity | belongsTo | FacilityCategory | `category()` |
| Amenity | belongsToMany | Property | `properties()` |
| Amenity | belongsToMany | Unit | `units()` |

## Migration Order

1. `2025_01_20_000001_create_facility_categories_table.php`
2. `2025_01_20_000002_add_category_to_amenities_table.php`
3. `2025_01_20_000003_create_unit_types_table.php`
4. `2025_01_20_000004_create_properties_table.php`
5. `2025_01_20_000005_create_property_images_table.php`
6. `2025_01_20_000006_create_property_facilities_table.php`
7. `2025_01_20_000007_create_units_table.php`
8. `2025_01_20_000008_create_unit_images_table.php`
9. `2025_01_20_000009_create_unit_facilities_table.php`
10. `2025_01_20_000010_create_unit_pricing_table.php`
11. `2025_01_20_000011_create_unit_availability_table.php`
12. `2025_01_20_000012_migrate_hotels_to_properties.php` (Data migration)
13. `2025_01_20_000013_update_bookings_for_units.php` (Update bookings)

## Seeding Order

```bash
php artisan db:seed --class=FacilityCategorySeeder
php artisan db:seed --class=FacilitySeeder
php artisan db:seed --class=UnitTypeSeeder
```










