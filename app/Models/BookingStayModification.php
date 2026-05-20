<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingStayModification extends Model
{
    protected $fillable = [
        'hotel_booking_id',
        'requested_by',
        'actual_check_out',
        'reason',
        'status',
        'reviewed_by',
        'reviewed_at',
        'admin_notes',
        'adjusted_total_amount',
        'adjusted_commission_amount',
    ];

    protected $casts = [
        'actual_check_out' => 'date',
        'reviewed_at' => 'datetime',
        'adjusted_total_amount' => 'decimal:2',
        'adjusted_commission_amount' => 'decimal:2',
    ];

    public function hotelBooking()
    {
        return $this->belongsTo(HotelBooking::class, 'hotel_booking_id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
