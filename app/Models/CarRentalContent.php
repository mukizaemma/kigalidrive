<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarRentalContent extends Model
{
    use HasFactory;

    protected $table = 'car_rental_contents';

    protected $fillable = [
        'heading',
        'subheading',
        'description',
        'fleet_content',
        'why_content',
        'services_content',
        'booking_content',
        'cta_book_label',
        'cta_quote_label',
        'hero_image',
    ];
}

