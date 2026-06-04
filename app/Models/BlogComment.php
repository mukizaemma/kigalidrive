<?php

namespace App\Models;

use App\Support\SchemaHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogComment extends Model
{
    use HasFactory;

    protected $table = 'blog_comments';

    protected $fillable = [
        'blog_id',
        'added_by',
        'names',
        'email',
        'comment',
        'status',
        'published_at',
        'ip_address',
        'rejection_reason',
    ];

    public function scopePublished($query)
    {
        return $query->where(function ($q) {
            $q->where('status', 'Published');
            if (SchemaHelper::hasColumn('blog_comments', 'is_approved')) {
                $q->orWhere('is_approved', true);
            }
        });
    }

    public function scopePending($query)
    {
        return $query->where('status', 'Unpublished');
    }

    public function syncApprovalFlags(): void
    {
        if (\App\Support\SchemaHelper::hasColumn('blog_comments', 'is_approved')) {
            $this->is_approved = $this->status === 'Published';
        }
    }

    protected static function booted(): void
    {
        static::saving(function (BlogComment $comment) {
            $comment->syncApprovalFlags();
        });
    }  

    public function blog(){
        return $this->belongsTo(Blog::class,'blog_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'created_by');
    }
    
    
}
