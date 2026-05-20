<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Review extends Model
{
    use HasFactory;
    
    protected $table = "reviews";
    
    protected $fillable = [
        'testimony',
        'names',
        'email',
        'website',
        'user_id',
        'rating',
        'is_approved',
        'is_featured',
        'admin_response',
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

    public function images()
    {
        return $this->hasMany(ReviewImage::class)->orderBy('sort_order');
    }
}
