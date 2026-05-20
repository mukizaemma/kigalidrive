<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HotelRoom extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hotel_id',
        'added_by',
        'slug',
        'room_type',
        'image',
        'max_occupancy',
        'price_per_night',
        'price_per_month',
        'currency',
        'price_display_type',
        'total_rooms',
        'available_rooms',
        'description',
        'amenities',
        'status',
        'accepts_room_bookings',
    ];

    protected $casts = [
        'amenities' => 'array',
        'accepts_room_bookings' => 'boolean',
        'price_per_night' => 'decimal:2',
        'price_per_month' => 'decimal:2',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }

    public function getImagePathAttribute()
    {
        return $this->image ? asset('storage/images/rooms/' . $this->image) : asset('assets/img/tour/tour_3_1.jpg');
    }

    /**
     * Pivot-linked Amenity models (must not be named "amenities" — JSON column uses that name).
     */
    public function roomAmenities()
    {
        return $this->belongsToMany(Amenity::class, 'amenity_hotel_rooms', 'hotel_room_id', 'amenity_id');
    }

    


    public function bookings()
    {
        return $this->hasMany(HotelBooking::class, 'room_id');
    }
    public function images()
    {
        return $this->hasMany(HotelRoomImage::class, 'hotel_room_id');
    }




}

