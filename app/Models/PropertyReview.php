<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyReview extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'guest_name',
        'guest_email',
        'hotel_id',
        'property_id',
        'reviewable_type',
        'rating',
        'comment',
        'title',
        'is_approved',
        'is_featured',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'is_featured' => 'boolean',
        'rating' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function reviewable()
    {
        if ($this->reviewable_type === 'property') {
            return $this->property();
        }
        return $this->hotel();
    }
}
