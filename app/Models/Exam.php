<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Exam extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'shuffle_questions' => 'boolean',
        'shuffle_options' => 'boolean',
        'show_results_immediately' => 'boolean',
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'total_grade' => 'decimal:2',
        'passing_grade' => 'decimal:2',
    ];


    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the course that owns the exam
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the section that owns the exam
     */
    public function section()
    {
        return $this->belongsTo(CourseSection::class, 'section_id');
    }

    /**
     * Get the user who created this exam (teacher)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by')->where('role_name', 'teacher');
    }

    public function admin_creator()
    {
        return $this->belongsTo(Admin::class, 'created_by_admin');
    }

    /**
     * Get the questions for the exam.
     */
    public function questions()
    {
        return $this->belongsToMany(Question::class, 'exam_questions')
            ->withPivot('order', 'grade')
            ->orderBy('exam_questions.order')
            ->withTimestamps();
    }

    /**
     * Get the total questions correct options grade.
     */
    public function getQuestionsSumGradeAttribute()
    {
        return $this->questions->sum('grade');
    }

    /**
     * Get exam attempts.
     */
    public function attempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }

    // exam current attempt for current login student
    public function current_user_attempt()
    {
        return $this->user_attempts()->where('status', 'in_progress')->where('submitted_at', null)?->first();
    }

    // exam last submited attempt for current login student
    public function last_submitted_attempt()
    {
        return $this->user_attempts()->where('submitted_at','!=', null)?->first();
    }

    // exam attempts for current login student
    public function current_user_attempts()
    {
        return $this->user_attempts()->where('status', 'in_progress')->where('submitted_at', null);
    }

    /**
     * Get exam title based on current locale
     */
    public function getTitleAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->attributes['title_ar'] : $this->attributes['title_en'];
    }

    /**
     * Get exam description based on current locale
     */
    public function getDescriptionAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->attributes['description_ar'] : $this->attributes['description_en'];
    }

    public function getSlugAttribute()
    {
        return Str::slug(app()->getLocale() === 'ar' ? $this->attributes['title_ar'] : $this->attributes['title_en']);
    }
    /**
     * Check if exam is available now
     */
    public function isAvailable()
    {
        $now = Carbon::now();

        if (!$this->is_active) {
            return false;
        }

        if ($this->start_date && $now->lt($this->start_date)) {
            return false;
        }

        if ($this->end_date && $now->gt($this->end_date)) {
            return false;
        }

        return true;
    }

    /**
     * Calculate total grade from questions
     */
    public function calculateTotalGrade()
    {
        return $this->questions()->sum('exam_questions.grade');
    }

    // get exam attempts for current login student
    public function user_attempts($user_id = null)
    {
        $user_id = $user_id ?? auth_student()?->id;
        return $this->attempts()
            ->where('user_id', $user_id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // return boolean
    // true if exam is acivte
    // and current date betweent its start and end date
    public function is_available()
    {
        if (!$this->is_active) return false;

        $now = now();
        if ($this->start_date && $now < $this->start_date) return false;
        if ($this->end_date && $now > $this->end_date) return false;

        return true;
    }

    // return boolean
    // true if user can add new attempt becuase he has attempts_allowed and there is no current one
    // false if user cant add new attempt becuase he hasnot attempts_allowed and there is exist current one
    // for current login student
    public function can_add_attempt()
    {
        $attempts_allowed = $this->attempts_allowed;
        $user_id = auth_student()?->id;
        $attempts = $this->attempts()->where('user_id', $user_id);
        return ($attempts->count() < $attempts_allowed && !$attempts->where('submitted_at', null)->count());
    }

    // return last completed attmept to exam for current login student
    public function result_attempt()
    {
        $user_id = auth_student()?->id;
        return $this->attempts()->where('user_id', $user_id)
        ->where('status', 'completed')->orderBy('score', 'desc')->first();
    }

    // return all completed attmept to exam for current login student
    public function result_attempts()
    {
        $user_id = auth_student()?->id;
        return $this->attempts()->where('user_id', $user_id)->where('status', 'completed')->orderBy('score', 'desc')->get();
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDurationAttribute()
    {
        if (!$this->attributes['duration_minutes']) {
            return __('messages.unlimited');
        }

        $hours = floor($this->attributes['duration_minutes'] / 60);
        $minutes = $this->attributes['duration_minutes'] % 60;

        if ($hours > 0) {
            return sprintf('%d:%02d', $hours, $minutes);
        }

        return sprintf('%d %s', $minutes, __('messages.minutes'));
    }
}
