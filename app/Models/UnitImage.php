<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnitImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'unit_id',
        'uploaded_by',
        'image_path',
        'caption',
        'sort_order',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the unit this image belongs to
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Get the user who uploaded this image
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get full image URL
     */
    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->image_path);
    }
}










