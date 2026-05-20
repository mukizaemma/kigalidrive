<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Legacy invoice model (StayNets). Tables are dropped in KDR migrations;
 * class kept only so autoload does not fatal if referenced.
 */
class StaynetsInvoice extends Model
{
    protected $table = 'staynets_invoices';

    protected $fillable = [
        'invoice_number',
        'property_id',
        'period_start',
        'period_end',
        'total_booking_amount',
        'commission_amount',
        'status',
        'created_by',
        'sent_at',
        'notes',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'sent_at' => 'datetime',
        'total_booking_amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
    ];
}
