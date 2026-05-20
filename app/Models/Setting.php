<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table ='settings';    

    protected $fillable = [
        'user_id',
        'company',
        'tagline',
        'address',
        'email',
        'phone',
        'whatsapp',
        'whatsapp_enabled',
        'booking_email',
        'booking_email_enabled',
        'logo',
        'home_header_image',
        'home_background_image',
        'why_trust_background_image',
        'contact_us_middle_image',
        'map_embed',
        'google_business_url',
        'google_place_id',
        'google_rating',
        'google_review_count',
        'business_hours',
        'deliveryInfo',
        'facebook',
        'twitter',
        'instagram',
        'youtube',
        'linkedin',
        'quote',
    ];

    protected $casts = [
        'whatsapp_enabled' => 'boolean',
        'booking_email_enabled' => 'boolean',
        'google_rating' => 'float',
        'google_review_count' => 'integer',
    ];

    public function hasGoogleBusiness(): bool
    {
        return filled($this->google_business_url) || filled($this->google_place_id);
    }
}
