<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseUser;
use App\Models\CoursePayment;
use App\Models\CoursePaymentDetail;
use App\Models\User;
use App\Models\Subject;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CourseEnrollmentsReportExport;
use DB;

class CourseEnrollmentReportController extends Controller
{
    /**
     * Display course enrollment reports with filters
     */
    public function index(Request $request)
    {
        $query = CourseUser::with(['course.teacher', 'course.subject', 'user']);

        // Filter by Course
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        // Filter by Student
        if ($request->filled('student_id')) {
            $query->where('user_id', $request->student_id);
        }

        // Filter by Teacher
        if ($request->filled('teacher_id')) {
            $query->whereHas('course', function($q) use ($request) {
                $q->where('teacher_id', $request->teacher_id);
            });
        }

        // Filter by Subject
        if ($request->filled('subject_id')) {
            $query->whereHas('course', function($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            });
        }

        // Filter by Date Range
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Filter by Payment Status
        if ($request->filled('payment_status')) {
            switch ($request->payment_status) {
                case 'paid':
                    $query->whereHas('course', function($q) use ($request) {
                        $q->whereHas('paymentDetails', function($q2) use ($request) {
                            $q2->where('user_id', DB::raw('course_users.user_id'));
                        });
                    });
                    break;
                case 'unpaid':
                    $query->whereDoesntHave('course', function($q) use ($request) {
                        $q->whereHas('paymentDetails', function($q2) use ($request) {
                            $q2->where('user_id', DB::raw('course_users.user_id'));
                        });
                    });
                    break;
            }
        }

        // Search by student name, email or phone
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        $enrollments = $query->latest()->paginate(20);

        // Calculate statistics
        $statistics = $this->calculateStatistics($request);

        // Get filter options
        $courses = Course::with('teacher')->orderBy('title_ar')->get();
        $students = User::where('role_name', 'student')->orderBy('name')->get();
        $teachers = User::where('role_name', 'teacher')->orderBy('name')->get();
        $subjects = Subject::orderBy('name_ar')->get();

        return view('admin.reports.enrollments.index', compact(
            'enrollments', 
            'statistics', 
            'courses', 
            'students', 
            'teachers', 
            'subjects'
        ));
    }

    /**
     * Calculate statistics for enrollments
     */
    private function calculateStatistics($request)
    {
        $enrollmentQuery = CourseUser::query();
        
        // Apply same filters
        if ($request->filled('course_id')) {
            $enrollmentQuery->where('course_id', $request->course_id);
        }
        if ($request->filled('student_id')) {
            $enrollmentQuery->where('user_id', $request->student_id);
        }
        if ($request->filled('teacher_id')) {
            $enrollmentQuery->whereHas('course', function($q) use ($request) {
                $q->where('teacher_id', $request->teacher_id);
            });
        }
        if ($request->filled('subject_id')) {
            $enrollmentQuery->whereHas('course', function($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            });
        }
        if ($request->filled('from_date')) {
            $enrollmentQuery->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $enrollmentQuery->whereDate('created_at', '<=', $request->to_date);
        }

        $enrollments = $enrollmentQuery->with('course')->get();
        
        // Calculate total revenue from course payments
        $paymentQuery = CoursePaymentDetail::query();
        if ($request->filled('course_id')) {
            $paymentQuery->where('course_id', $request->course_id);
        }
        if ($request->filled('student_id')) {
            $paymentQuery->where('user_id', $request->student_id);
        }
        if ($request->filled('teacher_id')) {
            $paymentQuery->where('teacher_id', $request->teacher_id);
        }
        if ($request->filled('from_date')) {
            $paymentQuery->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $paymentQuery->whereDate('created_at', '<=', $request->to_date);
        }

        return [
            'total_enrollments' => $enrollments->count(),
            'unique_students' => $enrollments->pluck('user_id')->unique()->count(),
            'unique_courses' => $enrollments->pluck('course_id')->unique()->count(),
            'total_revenue' => $paymentQuery->sum('amount'),
            'average_enrollment_per_course' => $enrollments->count() > 0 ? 
                round($enrollments->count() / $enrollments->pluck('course_id')->unique()->count(), 2) : 0,
            'most_popular_course' => $this->getMostPopularCourse($enrollments),
            'enrollments_this_month' => CourseUser::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)->count(),
            'revenue_this_month' => CoursePaymentDetail::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)->sum('amount'),
        ];
    }

    /**
     * Get most popular course
     */
    private function getMostPopularCourse($enrollments)
    {
        $courseEnrollments = $enrollments->groupBy('course_id');
        if ($courseEnrollments->isEmpty()) {
            return null;
        }
        
        $mostPopularCourseId = $courseEnrollments->sortByDesc(function($items) {
            return $items->count();
        })->keys()->first();
        
        return Course::find($mostPopularCourseId);
    }

    /**
     * Export enrollment reports to Excel
     */
    public function exportExcel(Request $request)
    {
        $enrollments = $this->getFilteredEnrollments($request)->get();
        
        return Excel::download(
            new CourseEnrollmentsReportExport($enrollments), 
            'course-enrollments-report-' . now()->format('Y-m-d-H-i-s') . '.xlsx'
        );
    }

    /**
     * Print enrollment reports
     */
    public function print(Request $request)
    {
        $enrollments = $this->getFilteredEnrollments($request)->get();
        $statistics = $this->calculateStatistics($request);
        
        return view('admin.reports.enrollments.print', compact('enrollments', 'statistics'));
    }

    /**
     * Get filtered enrollments query
     */
    private function getFilteredEnrollments(Request $request)
    {
        $query = CourseUser::with(['course.teacher', 'course.subject', 'user']);

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }
        if ($request->filled('student_id')) {
            $query->where('user_id', $request->student_id);
        }
        if ($request->filled('teacher_id')) {
            $query->whereHas('course', function($q) use ($request) {
                $q->where('teacher_id', $request->teacher_id);
            });
        }
        if ($request->filled('subject_id')) {
            $query->whereHas('course', function($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            });
        }
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        return $query->latest();
    }

    /**
     * Show detailed enrollment report for a student
     */
    public function showStudent(User $student)
    {
        $student->load(['courseEnrollments.course.teacher', 'courseEnrollments.course.subject']);
        
        $enrollmentStats = [
            'total_enrollments' => $student->courseEnrollments->count(),
            'total_spent' => CoursePaymentDetail::where('user_id', $student->id)->sum('amount'),
            'active_courses' => $student->courseEnrollments()
                ->whereHas('course', function($q) {
                    $q->where('is_active', 1);
                })->count(),
        ];
        
        return view('admin.reports.enrollments.show-student', compact('student', 'enrollmentStats'));
    }

    /**
     * Show detailed enrollment report for a course
     */
    public function showCourse(Course $course)
    {
        $course->load(['enrollments.user', 'teacher', 'subject']);
        
        $courseStats = [
            'total_enrollments' => $course->enrollments->count(),
            'total_revenue' => CoursePaymentDetail::where('course_id', $course->id)->sum('amount'),
            'average_progress' => $this->calculateAverageProgress($course),
        ];
        
        return view('admin.reports.enrollments.show-course', compact('course', 'courseStats'));
    }

    /**
     * Calculate average progress for a course
     */
    private function calculateAverageProgress($course)
    {
        $enrollments = $course->enrollments;
        if ($enrollments->isEmpty()) {
            return 0;
        }
        
        $totalProgress = 0;
        foreach ($enrollments as $enrollment) {
            $progress = $course->calculateCourseProgress($enrollment->user_id);
            $totalProgress += $progress['total_progress'] ?? 0;
        }
        
        return round($totalProgress / $enrollments->count(), 2);
    }
}