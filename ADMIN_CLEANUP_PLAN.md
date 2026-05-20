# Admin Cleanup Plan

## Current Duplication Issues

### 1. Properties/Hotels Duplication
- **Old:** HotelsController + admin/hotels/ views
- **New:** AdminPropertiesController + admin/properties/ views
- **Action:** Deprecate old HotelsController routes, keep new structure

### 2. Units/Rooms Duplication  
- **Old:** RoomsController + admin/rooms/ views
- **New:** AdminUnitsController + admin/units/ views
- **Action:** Deprecate old RoomsController routes, keep new structure

### 3. Facilities vs Amenities
- **Old:** FacilitiesController (might be for Facility model with images)
- **New:** AmenitiesController (for Amenity model with categories)
- **Action:** Keep both if they serve different purposes, otherwise merge

## Cleanup Strategy

### Keep (New Structure)
✅ AdminPropertiesController - Modern, supports hotels & apartments
✅ AdminUnitsController - Modern, supports rooms & apartment units
✅ AdminBookingsController - Booking management
✅ PropertyImagesController - Property image management
✅ UnitImagesController - Unit image management
✅ UnitPricingController - Pricing management
✅ UnitAvailabilityController - Availability management
✅ AmenitiesController - Amenity management with categories

### Deprecate/Remove (Old Structure)
❌ HotelsController routes (keep controller for backward compatibility temporarily)
❌ RoomsController routes (keep controller for backward compatibility temporarily)
❌ admin/hotels/ views (replaced by admin/properties/)
❌ admin/rooms/ views (replaced by admin/units/)

### Rename/Move
- admin/properties/index.blade.php ✅ (already correct)
- admin/units/index.blade.php (needs to be created)
- admin/bookings/index.blade.php (needs to be created)

## Naming Convention

Controllers: PascalCase (AdminPropertiesController)
Views: kebab-case in folders (admin/properties/index.blade.php)
Routes: kebab-case (admin.properties.index)










