<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnitType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'property_type',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get all units of this type
     */
    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    /**
     * Scope for hotel room types
     */
    public function scopeForHotels($query)
    {
        return $query->whereIn('property_type', ['hotel', 'both']);
    }

    /**
     * Scope for apartment types
     */
    public function scopeForApartments($query)
    {
        return $query->whereIn('property_type', ['apartment', 'both']);
    }

    public function scopeActive($query)
    {
        if (\Illuminate\Support\Facades\Schema::hasColumn($this->getTable(), 'is_active')) {
            $query->where('is_active', true);
        }

        return $query;
    }
}










