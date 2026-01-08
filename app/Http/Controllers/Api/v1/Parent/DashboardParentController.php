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
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardParentController extends Controller
{
    use Responses, HasNotifications;

    /**
     * Get parent dashboard overview
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'parent') {
                return $this->error_response('Access denied. Parents only.', null);
            }

            $parentProfile = $user->parentt;
            if (!$parentProfile) {
                return $this->success_response('Dashboard data retrieved successfully', [
                    'children_count' => 0,
                    'total_courses' => 0,
                    'total_exams' => 0,
                    'unread_notifications' => 0,
                    'children' => [],
                    'recent_activities' => [],
                    'statistics' => [
                        'average_progress' => 0,
                        'average_exam_score' => 0,
                        'total_passed_exams' => 0,
                        'total_failed_exams' => 0
                    ]
                ]);
            }

            // Get all children
            $parentStudents = ParentStudent::where('parentt_id', $parentProfile->id)
                ->with(['student'])
                ->get();

            $children = $parentStudents->filter(function($parentStudent) {
                return $parentStudent->student !== null;
            });

            $childrenCount = $children->count();

            if ($childrenCount === 0) {
                return $this->success_response('Dashboard data retrieved successfully', [
                    'children_count' => 0,
                    'total_courses' => 0,
                    'total_exams' => 0,
                    'unread_notifications' => $this->getUnreadNotificationsCount(),
                    'children' => [],
                    'recent_activities' => [],
                    'statistics' => [
                        'average_progress' => 0,
                        'average_exam_score' => 0,
                        'total_passed_exams' => 0,
                        'total_failed_exams' => 0
                    ]
                ]);
            }

            $childrenIds = $children->pluck('student.id')->toArray();

            // Calculate overall statistics
            $totalCourses = CourseUser::whereIn('user_id', $childrenIds)->count();
            $totalExamAttempts = ExamAttempt::whereIn('user_id', $childrenIds)
                ->where('status', 'completed')
                ->count();

            // Get all exam attempts for calculations
            $allExamAttempts = ExamAttempt::whereIn('user_id', $childrenIds)
                ->where('status', 'completed')
                ->with(['exam'])
                ->get();

            $averageExamScore = $allExamAttempts->avg('percentage') ?? 0;
            $passedExams = $allExamAttempts->filter(function($attempt) {
                return $attempt->percentage >= ($attempt->exam->passing_grade ?? 50);
            })->count();
            $failedExams = $totalExamAttempts - $passedExams;

            // Calculate average progress across all courses
            $enrolledCourses = CourseUser::whereIn('user_id', $childrenIds)->with('course')->get();
            $totalProgress = $enrolledCourses->sum(function($enrollment) {
                if (!$enrollment->course) return 0;
                $progress = $enrollment->course->calculateCourseProgress($enrollment->user_id);
                return $progress['total_progress'] ?? 0;
            });
            $averageProgress = $enrolledCourses->count() > 0 ? $totalProgress / $enrolledCourses->count() : 0;

            // Get children summary with quick stats
            $childrenSummary = $children->map(function ($parentStudent) {
                $child = $parentStudent->student;
                
                // Get recent course progress
                $recentCourse = CourseUser::where('user_id', $child->id)
                    ->with('course')
                    ->latest()
                    ->first();

                $recentProgress = 0;
                $recentCourseName = 'No courses enrolled';
                if ($recentCourse && $recentCourse->course) {
                    $progressData = $recentCourse->course->calculateCourseProgress($child->id);
                    $recentProgress = $progressData['total_progress'] ?? 0;
                    $recentCourseName = $recentCourse->course->title;
                }

                // Get recent exam result
                $recentExam = ExamAttempt::where('user_id', $child->id)
                    ->where('status', 'completed')
                    ->with(['exam'])
                    ->latest('submitted_at')
                    ->first();

                $recentExamScore = null;
                $recentExamName = null;
                if ($recentExam) {
                    $recentExamScore = $recentExam->percentage;
                    $recentExamName = $recentExam->exam->title;
                }

                return [
                    'id' => $child->id,
                    'name' => $child->name,
                    'photo_url' => $child->photo_url,
                    'class_id' => $child->clas_id,
                    'recent_course' => [
                        'name' => $recentCourseName,
                        'progress' => round($recentProgress, 1)
                    ],
                    'recent_exam' => $recentExam ? [
                        'name' => $recentExamName,
                        'score' => $recentExamScore,
                        'passed' => $recentExamScore >= ($recentExam->exam->passing_grade ?? 50),
                        'date' => $recentExam->submitted_at
                    ] : null
                ];
            })->values();


            // Get unread notifications count
            $unreadNotifications = $this->getUnreadNotificationsCount();

            return $this->success_response('Dashboard data retrieved successfully', [
                'children_count' => $childrenCount,
                'total_courses' => $totalCourses,
                'total_exams' => $totalExamAttempts,
                'unread_notifications' => $unreadNotifications,
                'children' => $childrenSummary,
                'statistics' => [
                    'average_progress' => round($averageProgress, 2),
                    'average_exam_score' => round($averageExamScore, 2),
                    'total_passed_exams' => $passedExams,
                    'total_failed_exams' => $failedExams,
                    'pass_rate' => $totalExamAttempts > 0 ? round(($passedExams / $totalExamAttempts) * 100, 2) : 0
                ]
            ]);

        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve dashboard data: ' . $e->getMessage(), null);
        }
    }

    /**
     * Get detailed statistics for dashboard
     */
    public function getStatistics(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'parent') {
                return $this->error_response('Access denied. Parents only.', null);
            }

            $parentProfile = $user->parentt;
            if (!$parentProfile) {
                return $this->success_response('Statistics retrieved successfully', [
                    'children_performance' => [],
                    'course_statistics' => [],
                    'exam_statistics' => [],
                    'monthly_progress' => []
                ]);
            }

            $childrenIds = ParentStudent::where('parentt_id', $parentProfile->id)
                ->pluck('user_id')
                ->toArray();

            if (empty($childrenIds)) {
                return $this->success_response('Statistics retrieved successfully', [
                    'children_performance' => [],
                    'course_statistics' => [],
                    'exam_statistics' => [],
                    'monthly_progress' => []
                ]);
            }

            // Children performance comparison
            $childrenPerformance = User::whereIn('id', $childrenIds)
                ->get()
                ->map(function ($child) {
                    $examResults = ExamAttempt::where('user_id', $child->id)
                        ->where('status', 'completed')
                        ->with(['exam'])
                        ->get();

                    $courseCount = CourseUser::where('user_id', $child->id)->count();
                    $averageScore = $examResults->avg('percentage') ?? 0;
                    $passedExams = $examResults->filter(function($attempt) {
                        return $attempt->percentage >= ($attempt->exam->passing_grade ?? 50);
                    })->count();

                    return [
                        'child_name' => $child->name,
                        'course_count' => $courseCount,
                        'exam_count' => $examResults->count(),
                        'average_score' => round($averageScore, 2),
                        'passed_exams' => $passedExams,
                        'pass_rate' => $examResults->count() > 0 ? round(($passedExams / $examResults->count()) * 100, 2) : 0
                    ];
                });

            // Course statistics by subject
            $courseStatistics = CourseUser::whereIn('user_id', $childrenIds)
                ->with(['course.subject'])
                ->get()
                ->groupBy('course.subject.name')
                ->map(function ($courses, $subjectName) {
                    $totalProgress = $courses->sum(function($courseUser) {
                        if (!$courseUser->course) return 0;
                        $progress = $courseUser->course->calculateCourseProgress($courseUser->user_id);
                        return $progress['total_progress'] ?? 0;
                    });
                    
                    return [
                        'subject_name' => $subjectName ?? 'Unknown',
                        'course_count' => $courses->count(),
                        'average_progress' => $courses->count() > 0 ? round($totalProgress / $courses->count(), 2) : 0
                    ];
                })
                ->values();

            // Exam statistics by month (last 6 months)
            $monthlyExamStats = [];
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $monthStart = $date->copy()->startOfMonth();
                $monthEnd = $date->copy()->endOfMonth();

                $monthlyAttempts = ExamAttempt::whereIn('user_id', $childrenIds)
                    ->where('status', 'completed')
                    ->whereBetween('submitted_at', [$monthStart, $monthEnd])
                    ->with(['exam'])
                    ->get();

                $passedCount = $monthlyAttempts->filter(function($attempt) {
                    return $attempt->percentage >= ($attempt->exam->passing_grade ?? 50);
                })->count();

                $monthlyExamStats[] = [
                    'month' => $date->format('M Y'),
                    'total_exams' => $monthlyAttempts->count(),
                    'passed_exams' => $passedCount,
                    'average_score' => $monthlyAttempts->count() > 0 ? round($monthlyAttempts->avg('percentage'), 2) : 0
                ];
            }

            // Monthly progress tracking (course completion over time)
            $monthlyProgress = [];
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $monthStart = $date->copy()->startOfMonth();
                $monthEnd = $date->copy()->endOfMonth();

                $newEnrollments = CourseUser::whereIn('user_id', $childrenIds)
                    ->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->count();

                $monthlyProgress[] = [
                    'month' => $date->format('M Y'),
                    'new_enrollments' => $newEnrollments
                ];
            }

            return $this->success_response('Statistics retrieved successfully', [
                'children_performance' => $childrenPerformance,
                'course_statistics' => $courseStatistics,
                'exam_statistics' => $monthlyExamStats,
                'monthly_progress' => $monthlyProgress
            ]);

        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve statistics: ' . $e->getMessage(), null);
        }
    }

  

 
}