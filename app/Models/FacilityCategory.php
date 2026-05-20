<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FacilityCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'property_type', // 'hotel', 'apartment', or null for both
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get all facilities/amenities in this category
     */
    public function facilities()
    {
        return $this->hasMany(Amenity::class, 'facility_category_id');
    }
    
    /**
     * Alias for facilities (for consistency)
     */
    public function amenities()
    {
        return $this->facilities();
    }

    public function scopeActive($query)
    {
        if (\Illuminate\Support\Facades\Schema::hasColumn($this->getTable(), 'is_active')) {
            $query->where('is_active', true);
        }

        return $query;
    }
}







