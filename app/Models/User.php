<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;



class User extends Authenticatable
{
   use HasApiTokens, HasFactory, Notifiable;

   /**
    * The attributes that are mass assignable.
    *
    * @var array<int, string>
    */
   protected $guarded = [];

   /**
    * The attributes that should be hidden for serialization.
    *
    * @var array<int, string>
    */
   protected $hidden = [
      'password',
      'remember_token',
   ];

   public function clas()
   {
      return $this->belongsTo(Clas::class);
   }

   public function teacher()
   {
      return $this->hasOne(Teacher::class);
   }


   public function parentt()
   {
      return $this->hasOne(Parentt::class);
   }

   public function parentRelationships()
   {
      return $this->hasMany(ParentStudent::class);
   }
   public function examAttempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }

    /**
     * Get completed exam attempts for the user.
     */
    public function completedExamAttempts()
    {
        return $this->hasMany(ExamAttempt::class)->where('status', 'completed');
    }

    /**
     * Get the user's average exam score.
     */
    public function getAverageScoreAttribute()
    {
        return $this->examAttempts()
            ->where('status', 'completed')
            ->avg('percentage') ?? 0;
    }

    /**
     * Get the user's total completed attempts count.
     */
    public function getTotalAttemptsAttribute()
    {
        return $this->examAttempts()
            ->where('status', 'completed')
            ->count();
    }
}
