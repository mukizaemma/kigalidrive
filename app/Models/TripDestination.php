<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TripDestination extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'trip_destinations';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'location',
        'status',
        'added_by',
    ];

    public function trips()
    {
        return $this->hasMany(Trip::class, 'trip_destination_id');
    }

    public function images()
    {
        return $this->hasMany(TripDestinationImage::class, 'trip_destination_id');
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
