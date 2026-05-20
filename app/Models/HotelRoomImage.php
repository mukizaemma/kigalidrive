<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelRoomImage extends Model
{
    use HasFactory;
    protected $table = 'hotel_room_images';

    protected $fillable = [
        'image','caption','hotel_room_id','added_by'
    ];

    public function room()
    {
        return $this->belongsTo(HotelRoom::class, 'hotel_room_id');
    }

    

}
