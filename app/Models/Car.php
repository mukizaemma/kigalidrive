<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'added_by',
        'name',
        'slug',
        'brand',
        'listing_type',
        'model',
        'fuel_type',
        'seats',
        'transmission',
        'driver_available',
        'self_drive',
        'price_per_day',
        'price_per_week',
        'price_per_month',
        'price_to_buy',
        'image',
        'description',
        'status',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function rentals()
    {
        return $this->hasMany(CarRental::class);
    }

    public function images()
    {
        return $this->hasMany(Carimage::class);
    }

    public function reviews()
    {
        return $this->hasMany(CarReview::class)->where('is_approved', true);
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getTotalReviewsAttribute()
    {
        return $this->reviews()->count();
    }

    public function scopeForRent($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('listing_type')
                ->orWhereIn('listing_type', ['rent', 'both']);
        })->where(function ($q) {
            $q->where('price_per_day', '>', 0)
                ->orWhere('price_per_week', '>', 0)
                ->orWhere('price_per_month', '>', 0);
        });
    }

    public function isRentable(): bool
    {
        if ($this->listing_type === 'sale') {
            return false;
        }

        return (
            ($this->price_per_day ?? 0) > 0
            || ($this->price_per_week ?? 0) > 0
            || ($this->price_per_month ?? 0) > 0
        );
    }

    public function getDisplayPriceAttribute()
    {
        if ($this->price_per_day > 0) {
            return ['amount' => $this->price_per_day, 'label' => '/ day'];
        }

        if ($this->price_per_week > 0) {
            return ['amount' => $this->price_per_week, 'label' => '/ week'];
        }

        if ($this->price_per_month > 0) {
            return ['amount' => $this->price_per_month, 'label' => '/ month'];
        }

        return null;
    }
}
