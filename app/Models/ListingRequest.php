<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListingRequest extends Model
{
    protected $fillable = [
        'product_type',
        'ad_type',
        'contact_name',
        'phone',
        'email',
        'amount',
        'location',
        'details',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];
}
