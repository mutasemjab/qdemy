<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parentt extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'parentts';


    /**
     * Get the user associated with the parent.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the students associated with the parent through the pivot table.
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'parent_students', 'parentt_id', 'user_id')
                    ->where('role_name', 'student');
    }

    /**
     * Get the parent-student relationships.
     */
    public function parentStudents()
    {
        return $this->hasMany(ParentStudent::class);
    }
  

    /**
     * Check if parent has any students.
     *
     * @return bool
     */
    public function hasStudents()
    {
        return $this->students()->count() > 0;
    }

    /**
     * Get students count.
     *
     * @return int
     */
    public function getStudentsCountAttribute()
    {
        return $this->students()->count();
    }

   
    /**
     * Add a student to this parent.
     *
     * @param int $studentId
     * @return bool
     */
    public function addStudent($studentId)
    {
        // Check if relationship already exists
        if ($this->students()->where('user_id', $studentId)->exists()) {
            return false;
        }

        ParentStudent::create([
            'parentt_id' => $this->id,
            'user_id' => $studentId,
        ]);

        return true;
    }

    /**
     * Remove a student from this parent.
     *
     * @param int $studentId
     * @return bool
     */
    public function removeStudent($studentId)
    {
        return ParentStudent::where('parentt_id', $this->id)
                           ->where('user_id', $studentId)
                           ->delete() > 0;
    }

}
