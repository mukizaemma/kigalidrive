<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnitPricing extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'unit_pricing';

    protected $fillable = [
        'unit_id',
        'price_per_night',
        'price_per_month',
        'weekend_price',
        'holiday_price',
        'start_date',
        'end_date',
        'min_nights',
        'max_nights',
        'min_guests',
        'max_guests',
        'is_active',
        'pricing_type',
        'notes',
    ];

    protected $casts = [
        'price_per_night' => 'decimal:2',
        'price_per_month' => 'decimal:2',
        'weekend_price' => 'decimal:2',
        'holiday_price' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'min_nights' => 'integer',
        'max_nights' => 'integer',
        'min_guests' => 'integer',
        'max_guests' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the unit this pricing belongs to
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Scope for active pricing
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for pricing valid on a specific date
     */
    public function scopeValidForDate($query, $date)
    {
        return $query->where('start_date', '<=', $date)
                     ->where(function($q) use ($date) {
                         $q->whereNull('end_date')
                           ->orWhere('end_date', '>=', $date);
                     });
    }
}










