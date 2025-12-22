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

   public function courses()
   {
      return $this->belongsToMany(Course::class ,'course_users');
   }


   public function teacherProfile()
   {
      return $this->hasOne(Teacher::class, 'user_id');
   }
   public function parentt()
   {
      return $this->hasOne(Parentt::class);
   }
   public function parent()
   {
      return $this->parentt();
   }
   public function getAvailableStudentsToAdd($search = null)
    {
        if ($this->role_name !== 'parent') {
            return collect();
        }

        $parentRecord = $this->parent;
        if (!$parentRecord) {
            return collect();
        }

        // Get students that are not already this parent's children
        $existingChildrenIds = $parentRecord->students()->pluck('user_id')->toArray();

        $query = self::where('role_name', 'student')
                    ->where('activate', 1)
                    ->whereNotIn('id', $existingChildrenIds);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('phone', 'LIKE', '%' . $search . '%');
            });
        }

        return $query->select('id', 'name', 'phone', 'clas_id')->get();
    }
    
   public function parentRelationships()
   {
      return $this->hasMany(ParentStudent::class);
   }

   public function examAttempts()
   {
        return $this->hasMany(ExamAttempt::class);
   }

    public function result_attempts()
    {
        return ExamAttempt::query()
            ->where('user_id', $this->id)
            ->where('status', 'completed')
            ->whereIn('id', function($query) {
                $query->selectRaw('MAX(id)')
                    ->from('exam_attempts as ea')
                    ->where('ea.user_id', $this->id)
                    ->where('ea.status', 'completed')
                    ->whereRaw('ea.score = (
                        SELECT MAX(score) 
                        FROM exam_attempts 
                        WHERE exam_id = ea.exam_id 
                        AND user_id = ea.user_id 
                        AND status = "completed"
                    )')
                    ->groupBy('ea.exam_id');
            })
            ->orderBy('score', 'desc')
            ->get();
    }
    /**
     * Get completed exam attempts for the user.
     */
    public function completedExamAttempts()
    {
        return $this->examAttempts->where('status', 'completed');
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

     public function getPhotoUrlAttribute()
    {
       return $this->photo ? asset('assets/admin/uploads/' . $this->photo) : asset('assets_front/images/Profile-picture.jpg');
    }


   public function followers()
   {
      return $this->hasMany(Follow::class, 'teacher_id');
   }

   public function following()
   {
      return $this->hasMany(Follow::class, 'user_id');
   }

   public function isFollowing($teacherId)
   {
      return $this->following()->where('teacher_id', $teacherId)->exists();
   }

  
}
