<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\Question;
use App\Models\Notification;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Users Statistics
        $totalStudents = User::where('role_name', 'student')->count();
        $totalTeachers = User::where('role_name', 'teacher')->count();
        $activeStudents = User::where('role_name', 'student')->where('activate', 1)->count();
        $activeTeachers = User::where('role_name', 'teacher')->where('activate', 1)->count();
        $newStudentsThisMonth = User::where('role_name', 'student')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Course Statistics
        $totalCourses = Course::count();
        $activeCourses = Course::count();
        // $coursesWithExams = Course::has('exams')->count();

        // Exam Statistics
        $totalExams = Exam::count();
        $activeExams = Exam::where('is_active', true)->count();
        $completedExams = Exam::whereHas('attempts', function($query) {
            $query->where('status', 'completed');
        })->count();

        // Question Statistics
        $totalQuestions = Question::count();
        $multipleChoiceQuestions = Question::where('type', 'multiple_choice')->count();
        $trueFalseQuestions = Question::where('type', 'true_false')->count();
        $essayQuestions = Question::where('type', 'essay')->count();

        // Exam Attempts Statistics
        $totalAttempts = ExamAttempt::count();
        $completedAttempts = ExamAttempt::where('status', 'completed')->count();
        $passedAttempts = ExamAttempt::where('is_passed', true)->count();
        $inProgressAttempts = ExamAttempt::where('status', 'in_progress')->count();
        $averageScore = ExamAttempt::where('status', 'completed')->avg('percentage') ?? 0;

        // Notification Statistics
        $totalNotifications = Notification::count();
        $unreadNotifications = Notification::whereNull('read_at')->count();
        $notificationsToday = Notification::whereDate('created_at', today())->count();

        // Recent Activities
        $recentExamAttempts = ExamAttempt::with(['user', 'exam'])
            ->where('status', 'completed')
            ->orderBy('submitted_at', 'desc')
            ->limit(5)
            ->get();

        $recentStudents = User::where('role_name', 'student')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentExams = Exam::with('course')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Charts Data
        $monthlyStudents = $this->getMonthlyStudentsData();
        $examsByMonth = $this->getExamsByMonthData();
        $passRateByExam = $this->getPassRateByExamData();
        $questionTypeDistribution = [
            'multiple_choice' => $multipleChoiceQuestions,
            'true_false' => $trueFalseQuestions,
            'essay' => $essayQuestions
        ];

        // Performance Metrics
        $passRate = $completedAttempts > 0 ? ($passedAttempts / $completedAttempts) * 100 : 0;
        $activeUserRate = $totalStudents > 0 ? ($activeStudents / $totalStudents) * 100 : 0;
        $examCompletionRate = $totalExams > 0 ? ($completedExams / $totalExams) * 100 : 0;

        // Top Performing Students (Laravel 8+ method using withAvg)
        $topStudents = User::where('role_name', 'student')
            ->whereHas('examAttempts', function($query) {
                $query->where('status', 'completed');
            })
            ->withAvg(['examAttempts as avg_score' => function($query) {
                $query->where('status', 'completed');
            }], 'percentage')
            ->withCount(['examAttempts as total_attempts' => function($query) {
                $query->where('status', 'completed');
            }])
            ->orderBy('avg_score', 'desc')
            ->limit(5)
            ->get();

        // System Health Indicators
        $systemHealth = [
            'database_connection' => $this->checkDatabaseConnection(),
            'storage_usage' => $this->getStorageUsage(),
            'cache_status' => $this->getCacheStatus(),
        ];

        return view('admin.dashboard', compact(
            // User Statistics
            'totalStudents',
            'totalTeachers',
            'activeStudents',
            'activeTeachers',
            'newStudentsThisMonth',

            // Course Statistics
            'totalCourses',
            'activeCourses',
            // 'coursesWithExams',

            // Exam Statistics
            'totalExams',
            'activeExams',
            'completedExams',

            // Question Statistics
            'totalQuestions',
            'multipleChoiceQuestions',
            'trueFalseQuestions',
            'essayQuestions',

            // Attempt Statistics
            'totalAttempts',
            'completedAttempts',
            'passedAttempts',
            'inProgressAttempts',
            'averageScore',

            // Notification Statistics
            'totalNotifications',
            'unreadNotifications',
            'notificationsToday',

            // Recent Activities
            'recentExamAttempts',
            'recentStudents',
            'recentExams',

            // Charts Data
            'monthlyStudents',
            'examsByMonth',
            'passRateByExam',
            'questionTypeDistribution',

            // Performance Metrics
            'passRate',
            'activeUserRate',
            'examCompletionRate',
            'topStudents',

            // System Health
            'systemHealth'
        ));
    }

    private function getMonthlyStudentsData()
    {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $count = User::where('role_name', 'student')
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();

            $data[] = [
                'month' => $month->format('M Y'),
                'count' => $count
            ];
        }
        return $data;
    }

    private function getExamsByMonthData()
    {
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $count = Exam::whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();

            $data[] = [
                'month' => $month->format('M Y'),
                'count' => $count
            ];
        }
        return $data;
    }

    private function getPassRateByExamData()
    {
        return Exam::select('exams.title_en',
            DB::raw('COUNT(exam_attempts.id) as total_attempts'),
            DB::raw('COUNT(CASE WHEN exam_attempts.is_passed = 1 THEN 1 END) as passed_attempts'),
            DB::raw('ROUND((COUNT(CASE WHEN exam_attempts.is_passed = 1 THEN 1 END) / COUNT(exam_attempts.id)) * 100, 2) as pass_rate')
        )
        ->leftJoin('exam_attempts', 'exams.id', '=', 'exam_attempts.exam_id')
        ->where('exam_attempts.status', 'completed')
        ->groupBy('exams.id', 'exams.title_en')
        ->having('total_attempts', '>', 0)
        ->orderBy('pass_rate', 'desc')
        ->limit(10)
        ->get();
    }

    private function checkDatabaseConnection()
    {
        try {
            DB::connection()->getPdo();
            return 'healthy';
        } catch (\Exception $e) {
            return 'error';
        }
    }

    private function getStorageUsage()
    {
        // Simple storage check - you can enhance this
        $totalSpace = disk_total_space(storage_path());
        $freeSpace = disk_free_space(storage_path());
        $usedSpace = $totalSpace - $freeSpace;
        $usagePercentage = ($usedSpace / $totalSpace) * 100;

        return [
            'percentage' => round($usagePercentage, 2),
            'status' => $usagePercentage > 90 ? 'warning' : 'healthy'
        ];
    }

    private function getCacheStatus()
    {
        try {
            cache()->put('health_check', 'ok', 60);
            $result = cache()->get('health_check');
            return $result === 'ok' ? 'healthy' : 'error';
        } catch (\Exception $e) {
            return 'error';
        }
    }

    public function getChartData(Request $request)
    {
        $type = $request->get('type');

        switch ($type) {
            case 'monthly_students':
                return response()->json($this->getMonthlyStudentsData());
            case 'exams_by_month':
                return response()->json($this->getExamsByMonthData());
            case 'pass_rate':
                return response()->json($this->getPassRateByExamData());
            default:
                return response()->json(['error' => 'Invalid chart type']);
        }
    }
}
