<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'property_id',
        'unit_type_id',
        'added_by',
        'name',
        'slug',
        'description',
        'max_occupancy',
        'bedrooms',
        'bathrooms',
        'beds',
        'size_sqm',
        'total_units',
        'available_units',
        'base_price_per_night',
        'base_price_per_month',
        'currency',
        'price_display_type',
        'featured_image',
        'status',
        'is_active',
        'meta_data',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'max_occupancy' => 'integer',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'beds' => 'integer',
        'size_sqm' => 'integer',
        'total_units' => 'integer',
        'available_units' => 'integer',
        'base_price_per_night' => 'decimal:2',
        'base_price_per_month' => 'decimal:2',
        'meta_data' => 'array',
    ];

    /**
     * Get the property this unit belongs to
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the unit type
     */
    public function unitType()
    {
        return $this->belongsTo(UnitType::class);
    }

    /**
     * Get the user who added this unit
     */
    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    /**
     * Get all unit images
     */
    public function images()
    {
        return $this->hasMany(UnitImage::class);
    }

    /**
     * Get primary image
     */
    public function primaryImage()
    {
        return $this->hasOne(UnitImage::class)->where('is_primary', true);
    }

    /**
     * Get all facilities/amenities for this unit
     */
    public function facilities()
    {
        $foreignKey = \App\Support\SchemaHelper::hasColumn('unit_facilities', 'amenity_id') ? 'amenity_id' : 'facility_id';

        return $this->belongsToMany(Amenity::class, 'unit_facilities', 'unit_id', $foreignKey)
            ->withTimestamps();
    }

    /**
     * Get all pricing rules for this unit
     */
    public function pricing()
    {
        return $this->hasMany(UnitPricing::class);
    }

    /**
     * Get active pricing for a specific date
     */
    public function getPricingForDate($date)
    {
        return $this->pricing()
                    ->where('is_active', true)
                    ->where('start_date', '<=', $date)
                    ->where(function($query) use ($date) {
                        $query->whereNull('end_date')
                              ->orWhere('end_date', '>=', $date);
                    })
                    ->orderBy('start_date', 'desc')
                    ->first();
    }

    /**
     * Get availability calendar
     */
    public function availability()
    {
        return $this->hasMany(UnitAvailability::class);
    }

    /**
     * Get availability for a specific date
     */
    public function getAvailabilityForDate($date)
    {
        return $this->availability()->where('date', $date)->first();
    }

    /**
     * Check if unit is available for a date range
     */
    public function isAvailableForDates($checkIn, $checkOut)
    {
        $dates = $this->availability()
                     ->whereBetween('date', [$checkIn, $checkOut])
                     ->where('status', 'available')
                     ->count();
        
        $totalDays = \Carbon\Carbon::parse($checkIn)->diffInDays(\Carbon\Carbon::parse($checkOut));
        
        return $dates >= $totalDays && $this->available_units > 0;
    }

    /**
     * Get all bookings for this unit
     */
    public function bookings()
    {
        return $this->hasMany(HotelBooking::class, 'unit_id');
    }

    /**
     * Get additional/extra charges for this unit
     */
    public function extraCharges()
    {
        return $this->hasMany(UnitExtraCharge::class)->where('is_active', true)->with('extraChargeType');
    }

    /**
     * Get all extra charge assignments (including inactive) for admin
     */
    public function extraChargesAll()
    {
        return $this->hasMany(UnitExtraCharge::class)->with('extraChargeType');
    }

    /**
     * Scope for available units
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'Available')
                     ->where('is_active', true)
                     ->where('available_units', '>', 0);
    }

    /**
     * Scope for active units
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}










