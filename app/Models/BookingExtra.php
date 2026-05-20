<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingExtra extends Model
{
    protected $fillable = ['hotel_booking_id', 'unit_extra_charge_id', 'price_snapshot', 'charge_name'];

    protected $casts = ['price_snapshot' => 'decimal:2'];

    public function hotelBooking(): BelongsTo
    {
        return $this->belongsTo(HotelBooking::class);
    }

    public function unitExtraCharge(): BelongsTo
    {
        return $this->belongsTo(UnitExtraCharge::class);
    }
}
