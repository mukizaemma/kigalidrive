<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class InventoryDayCap extends Model
{
    protected $fillable = [
        'bookable_type',
        'bookable_id',
        'date',
        'max_remaining',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function bookable(): MorphTo
    {
        return $this->morphTo();
    }
}
