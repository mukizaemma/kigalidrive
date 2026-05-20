<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Partner extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'partner_uid',
        'name',
        'type',
        'email',
        'phone',
        'address',
        'image',
        'city',
        'description',
        'status',
    ];

    public function hotels()
    {
        return $this->hasMany(Hotel::class);
    }

    public function cars()
    {
        return $this->hasMany(Car::class);
    }
}
