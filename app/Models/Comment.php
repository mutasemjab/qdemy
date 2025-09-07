<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'is_approved' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

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
}
