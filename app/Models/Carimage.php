<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carimage extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_id',
        'image',
        'caption',
        'added_by',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
