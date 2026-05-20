<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TripBooking extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'package_id',
        'number_of_people',
        'total_amount',
        'booking_status',
        'payment_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Trip::class);
    }
}
