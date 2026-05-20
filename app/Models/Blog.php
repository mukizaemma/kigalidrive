<?php

namespace App\Models;

use App\Support\SchemaHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'blogs';

    protected $fillable = [
        'title',
        'slug',
        'body',
        'description',
        'image',
        'status',
        'publish',
        'added_by',
        'user_id',
        'likes_count',
        'views',
        'published_at',
        'published_by',
        'created_by',
        'category_id',
    ];

    protected $casts = [
        'published_at' => 'date',
        'created_at' => 'date',
    ];

    public function getBodyAttribute(?string $value): string
    {
        if ($value !== null && $value !== '') {
            return $value;
        }

        return (string) ($this->attributes['description'] ?? '');
    }

    public function setBodyAttribute(?string $value): void
    {
        if (SchemaHelper::hasColumn('blogs', 'body')) {
            $this->attributes['body'] = $value;
        }
        if (SchemaHelper::hasColumn('blogs', 'description')) {
            $this->attributes['description'] = $value;
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function blogCategory()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(BlogComment::class);
    }

    public function commentsCount()
    {
        return $this->comments()->count();
    }
}
