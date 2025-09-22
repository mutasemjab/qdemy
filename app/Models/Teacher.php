<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

   public function courses()
    {
        return $this->hasMany(Course::class, 'teacher_id', 'user_id');
    }

    
    public function getPhotoUrlAttribute()
    {
       return $this->photo ? asset('assets/admin/uploads/' . $this->photo) : asset('assets_front/images/teacher1.png');
    }

    public function followers()
    {
        return $this->hasMany(Follow::class);
    }

    public function isFollowedBy($userId)
    {
        return $this->followers()->where('user_id', $userId)->exists();
    }

    public function followersCount()
    {
        return $this->followers()->count();
    }
}
