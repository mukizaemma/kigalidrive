<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExtraChargeType extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function unitExtraCharges(): HasMany
    {
        return $this->hasMany(UnitExtraCharge::class, 'extra_charge_type_id');
    }
}
