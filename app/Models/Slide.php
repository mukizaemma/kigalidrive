<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    use HasFactory;

    protected $table = 'slides';

    protected $fillable = [
        'caption',
        'heading',
        'subheading',
        'image',
        'status',
        'sort_order',
        'button',
        'link',
    ];

    public function imageUrl(): string
    {
        $image = ltrim((string) ($this->image ?? ''), '/');

        return $image !== ''
            ? asset('storage/images/slides/' . $image)
            : asset('assets/img/bg/breadcumb-bg-1.jpg');
    }

    public function scopeActive($query)
    {
        if (\Illuminate\Support\Facades\Schema::hasColumn('slides', 'status')) {
            return $query->where('status', 'Active');
        }

        return $query;
    }
}
