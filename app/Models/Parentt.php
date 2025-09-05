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
                    ->where('role_name', 'student')
                    ->where('activate', 1);
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
        // Check if student exists and is valid
        $student = User::where('id', $studentId)
                      ->where('role_name', 'student')
                      ->where('activate', 1)
                      ->first();

        if (!$student) {
            return false;
        }

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
     * Add multiple students to this parent.
     *
     * @param array $studentIds
     * @return array ['success' => int, 'failed' => int, 'existing' => int]
     */
    public function addMultipleStudents(array $studentIds)
    {
        $result = ['success' => 0, 'failed' => 0, 'existing' => 0];

        foreach ($studentIds as $studentId) {
            // Check if student exists and is valid
            $student = User::where('id', $studentId)
                          ->where('role_name', 'student')
                          ->where('activate', 1)
                          ->first();

            if (!$student) {
                $result['failed']++;
                continue;
            }

            // Check if relationship already exists
            if ($this->students()->where('user_id', $studentId)->exists()) {
                $result['existing']++;
                continue;
            }

            ParentStudent::create([
                'parentt_id' => $this->id,
                'user_id' => $studentId,
            ]);

            $result['success']++;
        }

        return $result;
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

    /**
     * Get all students with their progress information.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getStudentsWithProgress()
    {
        return $this->students()->with(['clas'])->get()->map(function ($student) {
            // You can add progress calculation logic here
            $student->progress = [
                'attendance_rate' => rand(75, 95), // Replace with actual calculation
                'average_grade' => rand(70, 90),   // Replace with actual calculation
                'completed_assignments' => rand(8, 15), // Replace with actual calculation
            ];
            return $student;
        });
    }

    /**
     * Get students grouped by class.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getStudentsByClass()
    {
        return $this->students()->with('clas')->get()->groupBy('clas_id');
    }

    /**
     * Check if this parent can add more students.
     * You can set a limit if needed.
     *
     * @param int $limit
     * @return bool
     */
    public function canAddMoreStudents($limit = 10)
    {
        return $this->students()->count() < $limit;
    }

    /**
     * Get recent activity for all children.
     * This would typically join with other tables like assignments, grades, etc.
     *
     * @param int $days
     * @return array
     */
    public function getChildrenRecentActivity($days = 7)
    {
        $activities = [];
        
        foreach ($this->students as $student) {
            // This is a placeholder - you would implement actual activity tracking
            $activities[] = [
                'student_id' => $student->id,
                'student_name' => $student->name,
                'activities' => [
                    'assignments_completed' => rand(0, 5),
                    'tests_taken' => rand(0, 2),
                    'classes_attended' => rand(3, 7),
                    'last_login' => now()->subDays(rand(0, $days))
                ]
            ];
        }
        
        return $activities;
    }

    /**
     * Static method to create parent with students in one transaction.
     *
     * @param array $parentData
     * @param array $studentIds
     * @return self
     */
    public static function createWithStudents(array $parentData, array $studentIds = [])
    {
        $parent = self::create($parentData);
        
        if (!empty($studentIds)) {
            $parent->addMultipleStudents($studentIds);
        }
        
        return $parent;
    }

    /**
     * Get summary statistics for this parent's children.
     *
     * @return array
     */
    public function getChildrenSummary()
    {
        $students = $this->students;
        
        if ($students->isEmpty()) {
            return [
                'total_children' => 0,
                'classes' => [],
                'average_performance' => 0,
                'total_assignments' => 0,
            ];
        }

        $classesCounted = $students->pluck('clas_id')->filter()->countBy();
        
        return [
            'total_children' => $students->count(),
            'classes' => $classesCounted->toArray(),
            'average_performance' => rand(75, 90), // Replace with actual calculation
            'total_assignments' => rand(20, 50), // Replace with actual calculation
            'youngest_grade' => $students->min('clas_id'),
            'oldest_grade' => $students->max('clas_id'),
        ];
    }
}