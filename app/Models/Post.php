<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $guarded = [];
        protected $casts = [
        'is_approved' => 'boolean',
        'is_active' => 'boolean',
    ];

     public function canBeDeletedBy($userId)
    {
        return $this->user_id == $userId;
    }

    // Add this accessor to include the flag in JSON responses
    public function getCanDeleteAttribute()
    {
        $userId = auth()->id();
        return $userId ? $this->canBeDeletedBy($userId) : false;
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function approvedComments()
    {
        return $this->hasMany(Comment::class)->where('is_approved', true)->where('is_active', true);
    }

      public function likes()
    {
        return $this->hasMany(PostLike::class);
    }

    public function isLikedBy($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    public function likesCount()
    {
        return $this->likes()->count();
    }

    public function commentsCount()
    {
        return $this->comments()->count();
    }
}
