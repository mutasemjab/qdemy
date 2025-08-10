<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentStudent extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'parent_students';

    /**
     * Get the parent associated with this relationship.
     */
    public function parent()
    {
        return $this->belongsTo(Parentt::class, 'parentt_id');
    }

    /**
     * Get the student (user) associated with this relationship.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope to get relationships for a specific parent.
     */
    public function scopeForParent($query, $parentId)
    {
        return $query->where('parentt_id', $parentId);
    }

    /**
     * Scope to get relationships for a specific student.
     */
    public function scopeForStudent($query, $studentId)
    {
        return $query->where('user_id', $studentId);
    }

    /**
     * Check if a relationship exists between a parent and student.
     *
     * @param int $parentId
     * @param int $studentId
     * @return bool
     */
    public static function relationshipExists($parentId, $studentId)
    {
        return static::where('parentt_id', $parentId)
                    ->where('user_id', $studentId)
                    ->exists();
    }

    /**
     * Create a relationship if it doesn't exist.
     *
     * @param int $parentId
     * @param int $studentId
     * @return static|bool
     */
    public static function createIfNotExists($parentId, $studentId)
    {
        if (static::relationshipExists($parentId, $studentId)) {
            return false;
        }

        return static::create([
            'parentt_id' => $parentId,
            'user_id' => $studentId,
        ]);
    }

    /**
     * Get all parents for a specific student.
     *
     * @param int $studentId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getParentsForStudent($studentId)
    {
        return static::where('user_id', $studentId)
                    ->with('parent')
                    ->get()
                    ->pluck('parent');
    }

    /**
     * Get all students for a specific parent.
     *
     * @param int $parentId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getStudentsForParent($parentId)
    {
        return static::where('parentt_id', $parentId)
                    ->with('student')
                    ->get()
                    ->pluck('student');
    }
}
