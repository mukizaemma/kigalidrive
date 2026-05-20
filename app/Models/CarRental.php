<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarRental extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'car_id',
        'booking_number',
        'booking_type',
        'name',
        'email',
        'phone',
        'full_address',
        'time_needed',
        'rental_duration',
        'with_driver',
        'pickup_location',
        'dropoff_location',
        'pickup_date',
        'dropoff_date',
        'preferred_date',
        'preferred_time',
        'message',
        'additional_request',
        'total_amount',
        'rental_status',
        'payment_status',
        'submission_channel',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
