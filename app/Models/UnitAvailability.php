<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitAvailability extends Model
{
    use HasFactory;

    protected $table = 'unit_availability';

    protected $fillable = [
        'unit_id',
        'date',
        'available_units',
        'status',
        'price_override',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'available_units' => 'integer',
        'price_override' => 'decimal:2',
    ];

    /**
     * Get the unit this availability belongs to
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Scope for available dates
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')
                     ->where('available_units', '>', 0);
    }

    /**
     * Scope for a date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }
}










