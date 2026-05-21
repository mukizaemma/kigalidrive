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
        'submission_channel',
        'admin_notes',
    ];

    public function submissionChannelLabel(): string
    {
        return match ($this->submission_channel) {
            'whatsapp' => 'WhatsApp',
            'email' => 'Email',
            'form' => 'Online form',
            default => '—',
        };
    }

    protected $casts = [
        'amount' => 'decimal:2',
    ];
}
