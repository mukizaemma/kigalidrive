<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarRentalRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'car_type',
        'people',
        'rental_date',
        'message',
        'status',       // pending, responded
        'admin_reply',
    ];
}

