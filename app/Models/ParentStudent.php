<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentStudent extends Model
{
    use HasFactory;
     protected $table = 'parent_students';
    protected $fillable = ['parentt_id', 'user_id'];

    /**
     * Get the parent that owns this relationship.
     */
    public function parent()
    {
        return $this->belongsTo(Parentt::class, 'parentt_id');
    }

    /**
     * Get the student that belongs to this relationship.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope to get active relationships only.
     */
    public function scopeActive($query)
    {
        return $query->whereHas('student', function($q) {
            $q->where('activate', 1);
        });
    }

    /**
     * Check if the relationship is valid (student is active).
     */
    public function isValid()
    {
        return $this->student && $this->student->activate == 1 && $this->student->role_name === 'student';
    }

    /**
     * Get relationships for a specific parent.
     */
    public static function getByParent($parentId)
    {
        return self::where('parentt_id', $parentId)
                  ->with(['student' => function($query) {
                      $query->where('activate', 1)->where('role_name', 'student');
                  }])
                  ->get();
    }

    /**
     * Get relationships for a specific student.
     */
    public static function getByStudent($studentId)
    {
        return self::where('user_id', $studentId)
                  ->with('parent.user')
                  ->get();
    }

}