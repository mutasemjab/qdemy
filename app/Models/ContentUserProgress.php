<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentUserProgress extends Model
{
    use HasFactory;

    protected $guarded = [];
        protected $casts = [
        'completed' => 'boolean',
        'viewed_at' => 'datetime',
        'watch_time' => 'integer'
    ];

    /**
     * علاقة مع المستخدم
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * علاقة مع محتوى الكورس
     */
    public function courseContent()
    {
        return $this->belongsTo(CourseContent::class);
    }

    /**
     * حساب نسبة المشاهدة
     */
    // public function getWatchPercentageAttribute()
    // {
    //     if (!$this->courseContent || !$this->courseContent->video_duration) {
    //         return 0;
    //     }

    //     return min(100, ($this->watch_time / $this->courseContent->video_duration) * 100);
    // }
}
