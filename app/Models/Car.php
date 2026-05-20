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
        'images',
        'description',
        'status',
    ];

    protected $casts = [
        'images' => 'array',
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

    public function getDisplayPriceAttribute()
    {
        if ($this->price_to_buy > 0) {
            return ['amount' => $this->price_to_buy, 'label' => 'For Sale'];
        }

        if ($this->price_per_day > 0) {
            return ['amount' => $this->price_per_day, 'label' => '/ day'];
        }

        if ($this->price_per_month > 0) {
            return ['amount' => $this->price_per_month, 'label' => '/ month'];
        }

        return null;
    }


}
