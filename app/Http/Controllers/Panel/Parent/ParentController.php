<?php

namespace App\Http\Controllers\Panel\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Parentt;
use App\Models\User;
use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\CourseUser;
use App\Models\ParentStudent;
use App\Traits\HasNotifications;

class ParentController extends Controller
{
    use HasNotifications;

    public function dashboard()
    {
        $user = Auth::user();
        $notifications = $this->getUserNotifications();

        // Get parent profile
        $parentProfile = $user->parentt;

        // Get children information with detailed progress
        $children = $this->getChildrenWithProgress($parentProfile);
        $childrenSummary = $this->getChildrenSummary($children);

        // Get overview statistics
        $overviewStats = $this->getOverviewStats($children);
        $availableStudents = $user->getAvailableStudentsToAdd();

        // Add course data for each child
        $children = $children->map(function ($child) {
            $child->courses = $this->getChildCourses($child->user_id)->take(3);
            $child->recentExams = $this->getChildExamsProgress($child->user_id)->take(3);
            return $child;
        });

        return view('panel.parent.dashboard', compact(
            'user',
            'children',
            'childrenSummary',
            'notifications',
            'overviewStats',
            'availableStudents'
        ));
    }

    public function children()
    {
        $user = Auth::user();
        $parentProfile = $user->parentt;

        $children = $this->getChildrenWithProgress($parentProfile);

        return view('panel.parent.children.index', compact('children'));
    }

    public function childDetail($childId)
    {
        $user = Auth::user();
        $parentProfile = $user->parentt;

        // Verify this child belongs to this parent
        $isMyChild = ParentStudent::where('parentt_id', $parentProfile->id)
            ->where('user_id', $childId)
            ->exists();

        if (!$isMyChild) {
            abort(404, 'Child not found');
        }

        $childUser = User::find($childId);
        $courses = $this->getChildCourses($childId);
        $examsProgress = $this->getChildExamsProgress($childId);
        $overallStats = $this->getChildOverallStats($childId);

        return view('panel.parent.children.detail', compact(
            'childUser',
            'courses',
            'examsProgress',
            'overallStats'
        ));
    }

    public function childCourses($childId)
    {
        $user = Auth::user();
        $parentProfile = $user->parentt;

        // Verify this child belongs to this parent
        $isMyChild = ParentStudent::where('parentt_id', $parentProfile->id)
            ->where('user_id', $childId)
            ->exists();

        if (!$isMyChild) {
            abort(404, 'Child not found');
        }

        $childUser = User::find($childId);
        $courses = $this->getChildCourses($childId);

        return view('panel.parent.children.courses', compact('childUser', 'courses'));
    }

    public function childExams($childId)
    {
        $user = Auth::user();
        $parentProfile = $user->parentt;

        // Verify this child belongs to this parent
        $isMyChild = ParentStudent::where('parentt_id', $parentProfile->id)
            ->where('user_id', $childId)
            ->exists();

        if (!$isMyChild) {
            abort(404, 'Child not found');
        }

        $childUser = User::find($childId);
        $examsProgress = $this->getChildExamsProgress($childId);

        return view('panel.parent.children.exams', compact('childUser', 'examsProgress'));
    }

    public function childReports()
    {
        $user = Auth::user();
        $parentProfile = $user->parentt;

        $children = $this->getChildrenWithProgress($parentProfile);
        $reportData = $this->generateChildrenReports($children);

        return view('panel.parent.children.reports', compact('children', 'reportData'));
    }

    /**
     * Get children with progress - corrected logic based on API
     */
    private function getChildrenWithProgress($parentProfile)
    {
        if (!$parentProfile) {
            return collect();
        }

        // Get parent-student relationships
        $parentStudents = ParentStudent::where('parentt_id', $parentProfile->id)
            ->with(['student'])
            ->get();

        return $parentStudents->filter(function ($parentStudent) {
            return $parentStudent->student !== null;
        })->map(function ($parentStudent) {
            $child = $parentStudent->student;

            // Get course count and average progress
            $enrolledCourses = CourseUser::where('user_id', $child->id)->with('course')->get();
            $totalCourses = $enrolledCourses->count();

            $averageProgress = 0;
            if ($totalCourses > 0) {
                $totalProgress = $enrolledCourses->sum(function ($enrollment) use ($child) {
                    return $enrollment->course ? $enrollment->course->calculateCourseProgress($child->id) : 0;
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
            $passedExams = $examResults->filter(function ($attempt) {
                return $attempt->percentage >= ($attempt->exam->passing_grade ?? 50);
            })->count();

            // Get recent activity
            $recentActivity = $this->getRecentActivity($child->id);

            return (object) [
                'user_id' => $child->id,
                'name' => $child->name,
                'email' => $child->email,
                'phone' => $child->phone,
                'photo_url' => $child->photo ? asset('assets/admin/uploads/' . $child->photo) : asset('assets_front/images/Profile-picture.jpg'),
                'clas' => $child->clas,
                'enrolledCoursesCount' => $totalCourses,
                'completedExamsCount' => $totalExams,
                'averageScore' => round($averageScore, 1),
                'averageProgress' => round($averageProgress, 1),
                'recentActivity' => $recentActivity,
                'added_at' => $parentStudent->created_at
            ];
        });
    }

    private function getChildrenSummary($children)
    {
        return [
            'total_children' => $children->count(),
            'total_courses' => $children->sum('enrolledCoursesCount'),
            'total_exams' => $children->sum('completedExamsCount'),
            'average_score' => $children->avg('averageScore'),
            'average_progress' => $children->avg('averageProgress')
        ];
    }

    private function getOverviewStats($children)
    {
        $totalChildren = $children->count();
        $totalCourses = $children->sum('enrolledCoursesCount');
        $totalExams = $children->sum('completedExamsCount');
        $averageProgress = $children->avg('averageProgress') ?? 0;

        return [
            'total_children' => $totalChildren,
            'total_courses' => $totalCourses,
            'total_exams' => $totalExams,
            'average_progress' => round($averageProgress, 1)
        ];
    }

    private function getChildCourses($childId)
    {
        // Get child's enrolled courses - same logic as API
        $enrolledCourses = CourseUser::where('user_id', $childId)
            ->with(['course.teacher', 'course.subject'])
            ->get();

        return $enrolledCourses->map(function ($enrollment) use ($childId) {
            $course = $enrollment->course;
            if (!$course) return null;

            // Calculate progress for this child
            $progress = $course->calculateCourseProgress($childId);

            return [
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
                'photo_url' => $course->photo_url,
                'teacher_name' => $course->teacher ? $course->teacher->name : 'N/A',
                'subject_name' => $course->subject ? $course->subject->name : 'N/A',
                'progress' => round($progress, 2),
                'enrolled_at' => $enrollment->created_at,
                'selling_price' => $course->selling_price,
                'total_sections' => $course->sections()->count(),
                'completed_sections' => $this->getCompletedSections($course->id, $childId)
            ];
        })->filter()->values();
    }

    private function getChildExamsProgress($childId)
    {
        // Get child's best exam attempts (highest score per exam) - same logic as API
        $examResults = ExamAttempt::where('user_id', $childId)
            ->where('status', 'completed')
            ->with(['exam.course', 'exam.subject'])
            ->whereIn('id', function ($query) use ($childId) {
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

        return $examResults->map(function ($attempt) {
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
                'total_questions' => $attempt->total_questions ?? 0,
                'correct_answers' => $attempt->correct_answers ?? 0,
                'passed' => $attempt->percentage >= ($exam->passing_grade ?? 50),
                'completed_at' => $attempt->submitted_at,
                'status' => $attempt->status
            ];
        });
    }

    private function getChildOverallStats($childId)
    {
        // Get enrolled courses count
        $totalCourses = CourseUser::where('user_id', $childId)->count();

        // Get completed exams count
        $completedExams = ExamAttempt::where('user_id', $childId)
            ->where('status', 'completed')->count();

        // Get all exam results for average
        $allExamResults = ExamAttempt::where('user_id', $childId)
            ->where('status', 'completed')
            ->with(['exam'])
            ->get();

        $averageScore = $allExamResults->avg('percentage') ?? 0;
        $totalAttempts = $allExamResults->count();

        return [
            'total_courses' => $totalCourses,
            'completed_exams' => $completedExams,
            'average_score' => round($averageScore, 1),
            'total_attempts' => $totalAttempts,
            'best_subject' => $this->getBestSubject($childId),
            'recent_activity' => $this->getRecentActivity($childId)
        ];
    }

    private function getBestSubject($childId)
    {
        $bestSubject = ExamAttempt::where('user_id', $childId)
            ->where('status', 'completed')
            ->join('exams', 'exam_attempts.exam_id', '=', 'exams.id')
            ->join('subjects', 'exams.subject_id', '=', 'subjects.id')
            ->selectRaw('subjects.name_ar, AVG(exam_attempts.percentage) as avg_score')
            ->groupBy('subjects.id', 'subjects.name_ar')
            ->orderBy('avg_score', 'desc')
            ->first();

        return $bestSubject ? [
            'name' => $bestSubject->name_ar,
            'average_score' => round($bestSubject->avg_score, 1)
        ] : null;
    }

    private function getRecentActivity($childId)
    {
        return ExamAttempt::where('user_id', $childId)
            ->where('status', 'completed')
            ->with('exam')
            ->orderBy('submitted_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($attempt) {
                return [
                    'type' => 'exam',
                    'title' => $attempt->exam->title,
                    'score' => $attempt->percentage,
                    'date' => $attempt->submitted_at
                ];
            });
    }

    private function getCompletedSections($courseId, $userId)
    {
        // This would need to be implemented based on your content progress tracking
        return 0;
    }

    private function generateChildrenReports($children)
    {
        return $children->map(function ($child) {
            $courses = $this->getChildCourses($child->user_id);
            $exams = $this->getChildExamsProgress($child->user_id);

            return [
                'child' => $child,
                'courses_count' => $courses->count(),
                'average_course_progress' => $courses->avg('progress') ?? 0,
                'exams_count' => $exams->count(),
                'average_exam_score' => $exams->avg('percentage') ?? 0,
                'recent_performance' => $exams->take(5)
            ];
        });
    }

    public function markAsRead($id)
    {
        return $this->markNotificationAsRead($id);
    }

    public function updateAccount(Request $request)
    {
        try {
            $user = Auth::guard('user')->user();

            $request->validate([
                'name'   => 'required|string|max:255',
                'email'  => 'required|email|unique:users,email,' . $user->id,
                'phone'  => 'nullable|string|max:20|unique:users,phone,' . $user->id,
                'photo'  => 'nullable|image|mimes:jpg,jpeg,png',
            ], [
                'name.required' => 'الاسم مطلوب',
                'name.max' => 'الاسم يجب أن لا يتجاوز 255 حرف',
                'email.required' => 'البريد الإلكتروني مطلوب',
                'email.email' => 'البريد الإلكتروني غير صحيح',
                'email.unique' => 'البريد الإلكتروني مستخدم من قبل',
                'phone.unique' => 'رقم الهاتف مستخدم من قبل',
                'phone.max' => 'رقم الهاتف يجب أن لا يتجاوز 20 رقم',
                'photo.image' => 'الملف يجب أن يكون صورة',
                'photo.mimes' => 'الصورة يجب أن تكون من نوع: jpg, jpeg, png',
            ]);

            $user->name  = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;

            // Upload photo
            if ($request->hasFile('photo')) {
                $filename = uploadImage('assets/admin/uploads', $request->photo);
                $user->photo = $filename;
            }

            $user->save();

            // Update parent profile
            if ($request->name) {
                $parent = Parentt::where('user_id', $user->id)->first();
                if ($parent) {
                    $parent->name = $request->name;
                    $parent->save();
                }
            }

            return back()->with('success', 'تم تحديث الحساب بنجاح');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء تحديث الحساب: ' . $e->getMessage())->withInput();
        }
    }

    public function addChild()
    {
        $user = Auth::user();
        $availableStudents = $user->getAvailableStudentsToAdd();

        return view('panel.parent.add-child', compact('user', 'availableStudents'));
    }

    public function addChildSubmit(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
        ]);

        $user = Auth::user();

        // Get or create parent profile - same logic as API
        $parentProfile = $user->parentt;
        if (!$parentProfile) {
            $parentProfile = Parentt::create([
                'name' => $user->name,
                'user_id' => $user->id
            ]);
        }

        // Verify the student exists and is active
        $student = User::where('id', $request->student_id)
            ->where('role_name', 'student')
            ->where('activate', 1)
            ->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'الطالب غير موجود أو غير نشط'
            ]);
        }

        // Check if student is already added
        $existingRelation = ParentStudent::where('parentt_id', $parentProfile->id)
            ->where('user_id', $student->id)
            ->first();

        if ($existingRelation) {
            return response()->json([
                'success' => false,
                'message' => 'الطالب مضاف مسبقاً'
            ]);
        }

        // Add student as child
        ParentStudent::create([
            'parentt_id' => $parentProfile->id,
            'user_id' => $student->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة الطالب بنجاح',
            'student' => $student
        ]);
    }

    public function removeChild(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
        ]);

        $user = Auth::user();
        $parentProfile = $user->parentt;

        if (!$parentProfile) {
            return response()->json([
                'success' => false,
                'message' => 'لم يتم العثور على سجل الوالد'
            ]);
        }

        // Find and remove the relation - same logic as API
        $parentStudent = ParentStudent::where('parentt_id', $parentProfile->id)
            ->where('user_id', $request->student_id)
            ->first();

        if (!$parentStudent) {
            return response()->json([
                'success' => false,
                'message' => 'الطالب غير موجود في قائمة الأطفال'
            ]);
        }

        $parentStudent->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الطالب بنجاح'
        ]);
    }

    public function searchStudents(Request $request)
    {
        $search = $request->get('search', '');
        $user = Auth::user();

        $students = $user->getAvailableStudentsToAdd($search);

        return response()->json([
            'success' => true,
            'students' => $students
        ]);
    }
}
