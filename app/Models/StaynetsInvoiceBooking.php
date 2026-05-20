<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Legacy invoice booking pivot (StayNets). Tables are dropped in KDR migrations.
 */
class StaynetsInvoiceBooking extends Model
{
    protected $table = 'staynets_invoice_bookings';

    protected $fillable = [
        'staynets_invoice_id',
        'hotel_booking_id',
        'booking_total',
        'commission',
    ];

    protected $casts = [
        'booking_total' => 'decimal:2',
        'commission' => 'decimal:2',
    ];
}
