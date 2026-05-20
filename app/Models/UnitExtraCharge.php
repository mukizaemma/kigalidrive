<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitExtraCharge extends Model
{
    protected $fillable = ['unit_id', 'extra_charge_type_id', 'price', 'is_active'];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function extraChargeType(): BelongsTo
    {
        return $this->belongsTo(ExtraChargeType::class, 'extra_charge_type_id');
    }

    public function bookingExtras(): HasMany
    {
        return $this->hasMany(BookingExtra::class, 'unit_extra_charge_id');
    }
}
