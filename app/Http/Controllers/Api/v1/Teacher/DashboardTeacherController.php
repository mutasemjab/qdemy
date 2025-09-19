<?php

namespace App\Http\Controllers\Api\v1\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Exam;
use App\Models\Question;
use App\Models\ExamAttempt;
use App\Models\Notification;
use App\Traits\Responses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardTeacherController extends Controller
{
    use Responses;

    /**
     * Get teacher dashboard data
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            // Get basic counts
            $coursesCount = Course::where('teacher_id', $user->id)->count();
            $examsCount = Exam::where('created_by', $user->id)->count();
            $questionsCount = Question::where('created_by', $user->id)->count();
            $totalStudentsCount = DB::table('course_users')
                ->join('courses', 'course_users.course_id', '=', 'courses.id')
                ->where('courses.teacher_id', $user->id)
                ->distinct('course_users.user_id')
                ->count();

            // Get recent exam attempts
            $recentAttempts = ExamAttempt::join('exams', 'exam_attempts.exam_id', '=', 'exams.id')
                ->where('exams.created_by', $user->id)
                ->where('exam_attempts.status', 'completed')
                ->with(['user:id,name,email,photo', 'exam:id,title_en,title_ar'])
                ->orderBy('exam_attempts.submitted_at', 'desc')
                ->limit(5)
                ->get(['exam_attempts.*']);

            // Get recent courses activity
            $recentCourses = Course::where('teacher_id', $user->id)
                ->withCount(['students' => function($query) {
                    $query->where('course_users.created_at', '>=', now()->subDays(7));
                }])
                ->orderBy('updated_at', 'desc')
                ->limit(5)
                ->get();

            // Get active exams
            $activeExams = Exam::where('created_by', $user->id)
                ->where('is_active', true)
                ->where(function($query) {
                    $query->whereNull('start_date')
                          ->orWhere('start_date', '<=', now());
                })
                ->where(function($query) {
                    $query->whereNull('end_date')
                          ->orWhere('end_date', '>=', now());
                })
                ->withCount('attempts')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Get unread notifications count
            $unreadNotificationsCount = Notification::where('user_id', $user->id)
                ->whereNull('read_at')
                ->count();

            // Get monthly statistics (last 6 months)
            $monthlyStats = [];
            for ($i = 5; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $monthKey = $month->format('Y-m');
                
                $examAttempts = ExamAttempt::join('exams', 'exam_attempts.exam_id', '=', 'exams.id')
                    ->where('exams.created_by', $user->id)
                    ->whereYear('exam_attempts.submitted_at', $month->year)
                    ->whereMonth('exam_attempts.submitted_at', $month->month)
                    ->where('exam_attempts.status', 'completed')
                    ->count();

                $newStudents = DB::table('course_users')
                    ->join('courses', 'course_users.course_id', '=', 'courses.id')
                    ->where('courses.teacher_id', $user->id)
                    ->whereYear('course_users.created_at', $month->year)
                    ->whereMonth('course_users.created_at', $month->month)
                    ->count();

                $monthlyStats[] = [
                    'month' => $month->format('M Y'),
                    'exam_attempts' => $examAttempts,
                    'new_students' => $newStudents
                ];
            }

            // Get performance metrics
            $examStats = ExamAttempt::join('exams', 'exam_attempts.exam_id', '=', 'exams.id')
                ->where('exams.created_by', $user->id)
                ->where('exam_attempts.status', 'completed')
                ->selectRaw('
                    COUNT(*) as total_attempts,
                    AVG(percentage) as average_score,
                    COUNT(CASE WHEN is_passed = 1 THEN 1 END) as passed_attempts
                ')
                ->first();

            // Format dashboard data
            $dashboardData = [
                'overview' => [
                    'courses_count' => $coursesCount,
                    'exams_count' => $examsCount,
                    'questions_count' => $questionsCount,
                    'total_students' => $totalStudentsCount,
                    'unread_notifications' => $unreadNotificationsCount
                ],
                'performance_metrics' => [
                    'total_exam_attempts' => $examStats->total_attempts ?? 0,
                    'average_score' => round($examStats->average_score ?? 0, 2),
                    'pass_rate' => $examStats->total_attempts > 0 
                        ? round(($examStats->passed_attempts / $examStats->total_attempts) * 100, 2) 
                        : 0
                ],
                'recent_activity' => [
                    'exam_attempts' => $recentAttempts->map(function ($attempt) {
                        return [
                            'id' => $attempt->id,
                            'student' => [
                                'id' => $attempt->user->id,
                                'name' => $attempt->user->name,
                                'email' => $attempt->user->email,
                                'photo' => $attempt->user->photo ? asset('assets/admin/uploads/' . $attempt->user->photo) : null
                            ],
                            'exam' => [
                                'id' => $attempt->exam->id,
                                'title_en' => $attempt->exam->title_en,
                                'title_ar' => $attempt->exam->title_ar
                            ],
                            'score' => $attempt->score,
                            'percentage' => $attempt->percentage,
                            'is_passed' => $attempt->is_passed,
                            'submitted_at' => $attempt->submitted_at
                        ];
                    }),
                    'courses' => $recentCourses->map(function ($course) {
                        return [
                            'id' => $course->id,
                            'title_en' => $course->title_en,
                            'title_ar' => $course->title_ar,
                            'new_students_this_week' => $course->students_count,
                            'updated_at' => $course->updated_at
                        ];
                    })
                ],
                'active_exams' => $activeExams->map(function ($exam) {
                    return [
                        'id' => $exam->id,
                        'title_en' => $exam->title_en,
                        'title_ar' => $exam->title_ar,
                        'attempts_count' => $exam->attempts_count,
                        'end_date' => $exam->end_date,
                        'created_at' => $exam->created_at
                    ];
                }),
                'monthly_statistics' => $monthlyStats,
                'teacher_info' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'photo' => $user->photo ? asset('assets/admin/uploads/' . $user->photo) : null,
                    'member_since' => $user->created_at,
                    'last_login' => $user->last_login
                ]
            ];

            return $this->success_response('Dashboard data retrieved successfully', $dashboardData);

        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve dashboard data: ' . $e->getMessage(), null);
        }
    }
}