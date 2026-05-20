<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    protected $table='rooms';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'status',
        'user_id',
        'price',
        'amenity_id',
    ];

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class);
    }

    public function images(){
        return $this->hasMany(Roomimage::class);
    }
}
