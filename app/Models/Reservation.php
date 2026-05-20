<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    protected $table='reservations';

    protected $fillable = [
        'service_type', // 'enquiry', 'hotel_booking', 'tour_booking', 'question'
        'names',
        'phone',
        'email',
        'nights',
        'guests',
        'message',
        'status',
        'facility_id',
        'room_id',
        // Hotel booking fields
        'checkin_date',
        'checkout_date',
        // Tour booking fields
        'tour_id',
        'tour_date',
        'tour_people',
        'selected_trip_ids',
        'trip_destination_id',
        'admin_response',
        'quoted_cost',
        'responded_at',
    ];

    public function room(){
        return $this->belongsTo(Room::class);
    }
    
    public function facility(){
        return $this->belongsTo(Facility::class);
    }
    
    public function tour(){
        return $this->belongsTo(\App\Models\Trip::class, 'tour_id');
    }

    public function tripDestination()
    {
        return $this->belongsTo(\App\Models\TripDestination::class, 'trip_destination_id');
    }
}
