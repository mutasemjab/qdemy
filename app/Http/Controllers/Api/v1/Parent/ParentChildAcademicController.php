<?php
namespace App\Http\Controllers\Api\v1\Parent;

use App\Http\Controllers\Controller;
use App\Models\CourseUser;
use App\Models\ExamAttempt;
use App\Models\Notification;
use App\Models\ParentStudent;
use App\Models\User;
use App\Traits\Responses;
use App\Traits\HasNotifications;
use Illuminate\Http\Request;

class ParentChildAcademicController extends Controller
{
    use Responses;

    /**
     * Get child's enrolled courses with progress
     */
    public function getChildCourses(Request $request, $childId)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'parent') {
                return $this->error_response('Access denied. Parents only.', null);
            }

            // Verify this child belongs to the parent
            $parentProfile = $user->parentt;
            if (!$parentProfile) {
                return $this->error_response('Parent profile not found', null);
            }

            $isMyChild = ParentStudent::where('parentt_id', $parentProfile->id)
                ->where('user_id', $childId)
                ->exists();

            if (!$isMyChild) {
                return $this->error_response('This child is not associated with your account', null);
            }

            // Get child's enrolled courses
            $enrolledCourses = CourseUser::where('user_id', $childId)
                ->with(['course.teacher', 'course.subject'])
                ->get();

            $coursesData = $enrolledCourses->map(function ($enrollment) use ($childId) {
                $course = $enrollment->course;
                if (!$course) return null;

                // Calculate progress for this child
                $progress = $course->calculateCourseProgress($childId);

                return [
                    'course_id' => $course->id,
                    'title' => $course->title,
                    'description' => $course->description,
                    'photo_url' => $course->photo_url,
                    'teacher_name' => $course->teacher ? $course->teacher->name : 'N/A',
                    'subject_name' => $course->subject ? $course->subject->name : 'N/A',
                    'progress_percentage' => round($progress['total_progress'] ?? 0, 2),
                    'enrolled_at' => $enrollment->created_at,
                    'selling_price' => $course->selling_price
                ];
            })->filter()->values();

            // Get child info
            $child = User::find($childId);
            
            return $this->success_response('Child courses retrieved successfully', [
                'child' => [
                    'id' => $child->id,
                    'name' => $child->name,
                    'photo_url' => $child->photo_url
                ],
                'courses' => $coursesData,
                'courses_count' => $coursesData->count(),
                'total_progress' => $coursesData->avg('progress_percentage') ?? 0
            ]);

        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve child courses: ' . $e->getMessage(), null);
        }
    }

    /**
     * Get child's exam results
     */
    public function getChildExamResults(Request $request, $childId)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'parent') {
                return $this->error_response('Access denied. Parents only.', null);
            }

            // Verify this child belongs to the parent
            $parentProfile = $user->parentt;
            if (!$parentProfile) {
                return $this->error_response('Parent profile not found', null);
            }

            $isMyChild = ParentStudent::where('parentt_id', $parentProfile->id)
                ->where('user_id', $childId)
                ->exists();

            if (!$isMyChild) {
                return $this->error_response('This child is not associated with your account', null);
            }

            // Get child's best exam attempts (highest score per exam)
            $examResults = ExamAttempt::where('user_id', $childId)
                ->where('status', 'completed')
                ->with(['exam.course', 'exam.subject'])
                ->whereIn('id', function($query) use ($childId) {
                    $query->selectRaw('MAX(id)')
                        ->from('exam_attempts as ea')
                        ->where('ea.user_id', $childId)
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

            $resultsData = $examResults->map(function ($attempt) {
                $exam = $attempt->exam;
                return [
                    'exam_id' => $exam->id,
                    'exam_title' => $exam->title,
                    'course_title' => $exam->course ? $exam->course->title : 'N/A',
                    'subject_name' => $exam->subject ? $exam->subject->name : 'N/A',
                    'score' => $attempt->score,
                    'percentage' => $attempt->percentage,
                    'total_grade' => $exam->total_grade,
                    'passing_grade' => $exam->passing_grade,
                    'is_passed' => $attempt->percentage >= ($exam->passing_grade ?? 50),
                    'attempt_duration' => $attempt->duration_minutes,
                    'completed_at' => $attempt->submitted_at,
                    'status' => $attempt->status
                ];
            });

            // Calculate statistics
            $totalExams = $resultsData->count();
            $passedExams = $resultsData->where('is_passed', true)->count();
            $averageScore = $resultsData->avg('percentage') ?? 0;

            $child = User::find($childId);

            return $this->success_response('Child exam results retrieved successfully', [
                'child' => [
                    'id' => $child->id,
                    'name' => $child->name,
                    'photo_url' => $child->photo_url
                ],
                'exam_results' => $resultsData->values(),
                'statistics' => [
                    'total_exams' => $totalExams,
                    'passed_exams' => $passedExams,
                    'failed_exams' => $totalExams - $passedExams,
                    'pass_rate' => $totalExams > 0 ? round(($passedExams / $totalExams) * 100, 2) : 0,
                    'average_score' => round($averageScore, 2)
                ]
            ]);

        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve child exam results: ' . $e->getMessage(), null);
        }
    }

    /**
     * Get comprehensive academic overview for a child
     */
    public function getChildAcademicOverview(Request $request, $childId)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'parent') {
                return $this->error_response('Access denied. Parents only.', null);
            }

            // Verify this child belongs to the parent
            $parentProfile = $user->parentt;
            if (!$parentProfile) {
                return $this->error_response('Parent profile not found', null);
            }

            $isMyChild = ParentStudent::where('parentt_id', $parentProfile->id)
                ->where('user_id', $childId)
                ->exists();

            if (!$isMyChild) {
                return $this->error_response('This child is not associated with your account', null);
            }

            $child = User::find($childId);

            // Get enrolled courses with progress
            $enrolledCourses = CourseUser::where('user_id', $childId)
                ->with(['course.teacher', 'course.subject'])
                ->get();

            $coursesData = $enrolledCourses->map(function ($enrollment) use ($childId) {
                $course = $enrollment->course;
                if (!$course) return null;

                $progress = $course->calculateCourseProgress($childId);

                return [
                    'course_id' => $course->id,
                    'title' => $course->title,
                    'progress_percentage' => round($progress['total_progress'] ?? 0, 2),
                    'teacher_name' => $course->teacher ? $course->teacher->name : 'N/A',
                    'subject_name' => $course->subject ? $course->subject->name : 'N/A'
                ];
            })->filter()->values();

            // Get recent exam results (last 5)
            $recentExamResults = ExamAttempt::where('user_id', $childId)
                ->where('status', 'completed')
                ->with(['exam.course'])
                ->orderBy('submitted_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($attempt) {
                    return [
                        'exam_title' => $attempt->exam->title,
                        'course_title' => $attempt->exam->course ? $attempt->exam->course->title : 'N/A',
                        'score' => $attempt->score,
                        'percentage' => $attempt->percentage,
                        'is_passed' => $attempt->percentage >= ($attempt->exam->passing_grade ?? 50),
                        'completed_at' => $attempt->submitted_at
                    ];
                });

            // Calculate overall statistics
            $totalCourses = $coursesData->count();
            $averageProgress = $coursesData->avg('progress_percentage') ?? 0;
            
            $allExamResults = ExamAttempt::where('user_id', $childId)
                ->where('status', 'completed')
                ->with(['exam'])
                ->get();
                
            $totalExams = $allExamResults->count();
            $averageExamScore = $allExamResults->avg('percentage') ?? 0;
            $passedExams = $allExamResults->filter(function($attempt) {
                return $attempt->percentage >= ($attempt->exam->passing_grade ?? 50);
            })->count();

            return $this->success_response('Child academic overview retrieved successfully', [
                'child' => [
                    'id' => $child->id,
                    'name' => $child->name,
                    'photo_url' => $child->photo_url,
                    'class_id' => $child->clas_id,
                    'balance' => $child->balance
                ],
                'courses' => [
                    'total_enrolled' => $totalCourses,
                    'average_progress' => round($averageProgress, 2),
                    'courses_list' => $coursesData
                ],
                'exams' => [
                    'total_completed' => $totalExams,
                    'average_score' => round($averageExamScore, 2),
                    'passed_count' => $passedExams,
                    'pass_rate' => $totalExams > 0 ? round(($passedExams / $totalExams) * 100, 2) : 0,
                    'recent_results' => $recentExamResults
                ]
            ]);

        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve child academic overview: ' . $e->getMessage(), null);
        }
    }

    /**
     * Get all children with their academic summary
     */
    public function getAllChildrenAcademicSummary(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'parent') {
                return $this->error_response('Access denied. Parents only.', null);
            }

            $parentProfile = $user->parentt;
            if (!$parentProfile) {
                return $this->success_response('Children academic summary retrieved successfully', [
                    'children' => [],
                    'children_count' => 0
                ]);
            }

            $parentStudents = ParentStudent::where('parentt_id', $parentProfile->id)
                ->with(['student'])
                ->get();

            $childrenSummary = $parentStudents->filter(function($parentStudent) {
                return $parentStudent->student !== null;
            })->map(function ($parentStudent) {
                $child = $parentStudent->student;
                
                // Get course count and average progress
                $enrolledCourses = CourseUser::where('user_id', $child->id)->with('course')->get();
                $totalCourses = $enrolledCourses->count();
                
                $averageProgress = 0;
                if ($totalCourses > 0) {
                    $totalProgress = $enrolledCourses->sum(function($enrollment) use ($child) {
                        if (!$enrollment->course) return 0;
                        $progress = $enrollment->course->calculateCourseProgress($child->id);
                        return $progress['total_progress'] ?? 0;
                    });
                    $averageProgress = $totalProgress / $totalCourses;
                }

                // Get exam statistics
                $examResults = ExamAttempt::where('user_id', $child->id)
                    ->where('status', 'completed')
                    ->with(['exam'])
                    ->get();
                    
                $totalExams = $examResults->count();
                $averageScore = $examResults->avg('percentage') ?? 0;
                $passedExams = $examResults->filter(function($attempt) {
                    return $attempt->percentage >= ($attempt->exam->passing_grade ?? 50);
                })->count();

                return [
                    'id' => $child->id,
                    'name' => $child->name,
                    'photo_url' => $child->photo_url,
                    'class_id' => $child->clas_id,
                    'courses' => [
                        'total_enrolled' => $totalCourses,
                        'average_progress' => round($averageProgress, 2)
                    ],
                    'exams' => [
                        'total_completed' => $totalExams,
                        'average_score' => round($averageScore, 2),
                        'passed_count' => $passedExams,
                        'pass_rate' => $totalExams > 0 ? round(($passedExams / $totalExams) * 100, 2) : 0
                    ],
                    'added_at' => $parentStudent->created_at
                ];
            })->values();

            return $this->success_response('Children academic summary retrieved successfully', [
                'children' => $childrenSummary,
                'children_count' => $childrenSummary->count()
            ]);

        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve children academic summary: ' . $e->getMessage(), null);
        }
    }

}