<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user (student) that the notification belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the teacher that the notification belongs to.
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Get the recipient of the notification (user or teacher).
     */
    public function getRecipientAttribute()
    {
        return $this->user ?? $this->teacher;
    }

    /**
     * Check if notification is read.
     */
    public function getIsReadAttribute()
    {
        return !is_null($this->read_at);
    }

    /**
     * Get the time since notification was created.
     */
    public function getTimeSinceAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Scope for unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope for read notifications.
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Scope for notifications sent today.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope for notifications sent this week.
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    /**
     * Scope for notifications sent this month.
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month);
    }

    /**
     * Scope for notifications sent to students.
     */
    public function scopeForStudents($query)
    {
        return $query->whereNotNull('user_id');
    }

    /**
     * Scope for notifications sent to teachers.
     */
    public function scopeForTeachers($query)
    {
        return $query->whereNotNull('teacher_id')->whereNull('user_id');
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }

    /**
     * Mark notification as unread.
     */
    public function markAsUnread()
    {
        $this->update(['read_at' => null]);
    }

    /**
     * Get notification type based on recipient.
     */
    public function getTypeAttribute()
    {
        if ($this->user_id && $this->teacher_id) {
            return 'student';
        } elseif ($this->teacher_id && !$this->user_id) {
            return 'teacher';
        } else {
            return 'general';
        }
    }

    /**
     * Get notification priority (you can customize this logic).
     */
    public function getPriorityAttribute()
    {
        // You can add priority logic based on title or body content
        $highPriorityKeywords = ['urgent', 'important', 'emergency', 'exam', 'deadline'];
        
        foreach ($highPriorityKeywords as $keyword) {
            if (stripos($this->title, $keyword) !== false || stripos($this->body, $keyword) !== false) {
                return 'high';
            }
        }
        
        return 'normal';
    }
}
