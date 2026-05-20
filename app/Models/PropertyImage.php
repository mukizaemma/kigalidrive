<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class PropertyImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'property_id',
        'uploaded_by',
        'image',
        'image_path',
        'caption',
        'sort_order',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function setImagePathAttribute(?string $value): void
    {
        if (Schema::hasColumn($this->getTable(), 'image_path')) {
            $this->attributes['image_path'] = $value;
        }
        if (Schema::hasColumn($this->getTable(), 'image')) {
            $this->attributes['image'] = $value;
        }
    }

    public function getImagePathAttribute(?string $value): ?string
    {
        if ($value !== null && $value !== '') {
            return $value;
        }

        return $this->attributes['image'] ?? null;
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getImageUrlAttribute()
    {
        $path = $this->image_path ?? $this->image ?? '';

        return $path ? asset('storage/images/properties/'.$path) : '';
    }
}
