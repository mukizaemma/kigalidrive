<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    protected $fillable = [
        'form_type',
        'submission_channel',
        'names',
        'email',
        'phone',
        'subject',
        'message',
        'meta',
        'status',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public const FORM_CONTACT = 'contact';
    public const FORM_CAR_ENQUIRY = 'car_enquiry';
    public const FORM_APARTMENT_ENQUIRY = 'apartment_enquiry';

    public static function formTypeLabels(): array
    {
        return [
            self::FORM_CONTACT => 'Contact us',
            self::FORM_CAR_ENQUIRY => 'Car enquiry',
            self::FORM_APARTMENT_ENQUIRY => 'Apartment enquiry',
            'car_reservation' => 'Car reservation',
            'apartment_reservation' => 'Apartment reservation',
        ];
    }

    public function formTypeLabel(): string
    {
        return self::formTypeLabels()[$this->form_type] ?? ucfirst(str_replace('_', ' ', $this->form_type));
    }

    public function channelLabel(): string
    {
        return match ($this->submission_channel) {
            'whatsapp' => 'WhatsApp',
            'email' => 'Email',
            'form' => 'Online form',
            default => ucfirst($this->submission_channel),
        };
    }
}
