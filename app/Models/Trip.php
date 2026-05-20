<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;
    protected $table='trips';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'itinerary',
        'expectations',
        'recommendations',
        'inclusions',
        'exclusions',
        'location',
        'duration',
        'languages',
        'currency',
        'maxPeople',
        'minAge',
        'price',
        'couplePrice',
        'image',
        'status',
        'added_by',
        'trip_destination_id',
        'category_id', // Keep for backward compatibility
        'program_id',
    ];

    public function images(){
        return $this->hasMany(Tripimage::class);
    }

    public function tripDestination()
    {
        return $this->belongsTo(TripDestination::class, 'trip_destination_id');
    }

    public function destination()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function reviews()
    {
        return $this->hasMany(TripReview::class)->where('is_approved', true);
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getTotalReviewsAttribute()
    {
        return $this->reviews()->count();
    }
}
