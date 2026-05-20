<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TripDestinationImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'trip_destination_images';

    protected $fillable = [
        'image',
        'caption',
        'trip_destination_id',
        'added_by',
    ];

    public function destination()
    {
        return $this->belongsTo(TripDestination::class, 'trip_destination_id');
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
