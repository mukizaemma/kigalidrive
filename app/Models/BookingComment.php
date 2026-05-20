<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingComment extends Model
{
    protected $fillable = ['hotel_booking_id', 'user_id', 'author_type', 'comment'];

    public function hotelBooking()
    {
        return $this->belongsTo(HotelBooking::class, 'hotel_booking_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isFromStaff(): bool
    {
        return $this->author_type === 'staff';
    }

    public function isFromOwner(): bool
    {
        return $this->author_type === 'owner';
    }
}
