<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hotel extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'partner_id',
        'category_id',
        'program_id',
        'added_by',
        'type',
        'slug',
        'name',
        'address',
        'phone',
        'email',
        'description',
        'cancellation_free_period',
        'cancellation_refund_conditions',
        'cancellation_no_show_policy',
        'listing_terms',
        'image',
        'city',
        'stars',
        'location',
        'latitude',
        'longitude',
        'description',
        'website',
        'status',
        'is_listing_visible',
        'accepts_bookings',
    ];

    protected $casts = [
        'is_listing_visible' => 'boolean',
        'accepts_bookings' => 'boolean',
    ];

    public function scopePublishedForGuests($query)
    {
        return $query->where('status', 'Active')->where('is_listing_visible', true);
    }

    public function hotelRooms()
    {
        return $this->hasMany(HotelRoom::class, 'hotel_id');
    }

    public function getImagePathAttribute()
    {
        return $this->image ? asset('storage/images/hotels/' . $this->image) : asset('assets/img/tour/tour_3_1.jpg');
    }

    public function destination()
    {
        return $this->belongsTo(Category::class);
    }
    public function service()
    {
        return $this->belongsTo(Program::class);
    }
    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function images()
    {
        return $this->hasMany(HotelImage::class);
    }

    public function rooms()
    {
        return $this->hasMany(HotelRoom::class);
    }

    public function bookings()
    {
        return $this->hasMany(HotelBooking::class);
    }

    public function reviews()
    {
        return $this->hasMany(PropertyReview::class, 'hotel_id')->where('reviewable_type', 'hotel')->where('is_approved', true);
    }

    public function getMinPriceAttribute()
    {
        $cheapestRoom = $this->rooms()->where('status', 'Active')->orderBy('price_per_night', 'asc')->first();
        return $cheapestRoom ? $cheapestRoom->price_per_night : null;
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getTotalReviewsAttribute()
    {
        return $this->reviews()->count();
    }

    /**
     * Get the user who added this hotel (owner).
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    /**
     * Get all amenities for this hotel
     */
    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'hotel_amenities', 'hotel_id', 'amenity_id')
                    ->withTimestamps();
    }

    public function listingAgreementSignature()
    {
        return $this->morphOne(\App\Models\ListingAgreementSignature::class, 'signable');
    }
}
