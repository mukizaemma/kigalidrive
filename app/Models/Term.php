<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'type',
        'user_id',
        'terms',
        'privacy',
        'privacy_details',
        'cookies',
        'refunds',
        'booking_cancellation',
        'listing_commission',
        'payment_methods',
        'support',
        'return',
        'added_by',
    ];
}
