<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HotelImage extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'hotel_images';

    protected $fillable = [
        'image',
        'caption',
        'hotel_id',
        'added_by',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
