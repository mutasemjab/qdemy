<?php

namespace App\Http\Controllers\Panel\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseContent;
use App\Models\CourseSection;
use App\Models\CourseUser;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\Question;
use App\Models\Subject;
use App\Traits\CourseManagementTrait;
use App\Traits\ExamManagementTrait;
use App\Traits\HasCommunity;
use App\Traits\HasNotifications;
use App\Traits\SubjectCategoryTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    use HasNotifications, HasCommunity, CourseManagementTrait, SubjectCategoryTrait,ExamManagementTrait;


    public function dashboard()
    {
        $user = Auth::user();
        $notifications = $this->getUserNotifications();
        
        // Get teacher's courses count
        $coursesCount = Course::where('teacher_id', $user->id)->count();
        
        // Get recent courses with student count
        $recentCourses = Course::where('teacher_id', $user->id)
            ->with(['subject:id,name_ar,name_en'])
            ->withCount('students') // Add student count
            ->latest()
            ->take(5)
            ->get();

        // Get total enrolled students across all teacher's courses
        $totalStudents = Course::where('teacher_id', $user->id)
            ->withCount('students')
            ->get()
            ->sum('students_count');

        // Get recent enrollments (last 10)
        $recentEnrollments = CourseUser::whereIn('course_id', function($query) use ($user) {
                $query->select('id')
                    ->from('courses')
                    ->where('teacher_id', $user->id);
            })
            ->with(['course:id,title_ar,title_en', 'user:id,name,email,photo'])
            ->latest()
            ->take(10)
            ->get();

        // Get courses with their enrolled students for detailed view
        $coursesWithStudents = Course::where('teacher_id', $user->id)
            ->with(['subject:id,name_ar,name_en', 'students:id,name,email,photo,created_at'])
            ->withCount('students')
            ->orderBy('students_count', 'desc')
            ->take(5)
            ->get();

        // Get community posts for the dashboard
        $posts = $this->getCommunityPosts(20);

        return view('panel.teacher.dashboard', compact(
            'user', 
            'notifications', 
            'posts', 
            'coursesCount', 
            'recentCourses',
            'totalStudents',
            'recentEnrollments',
            'coursesWithStudents'
        ));
    }

    /**
     * Display teacher's courses
     */
    public function courses(Request $request)
    {
        $user = Auth::user();

        $query = Course::where('teacher_id', $user->id)
            ->with(['subject:id,name_ar,name_en']);

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by subject
        if ($request->filled('subject_id') && $request->subject_id !== 'all') {
            $query->where('subject_id', $request->subject_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title_ar', 'like', "%{$search}%")
                  ->orWhere('title_en', 'like', "%{$search}%")
                  ->orWhere('description_ar', 'like', "%{$search}%")
                  ->orWhere('description_en', 'like', "%{$search}%");
            });
        }

        $courses = $query->latest()->paginate(10)->withQueryString();

        return view('panel.teacher.courses.index', compact('courses'));
    }

    /**
     * Show form to create new course
     */
    public function createCourse()
    {
        $parentCategories = Category::roots()
            ->active()
            ->ordered()
            ->get();

        $subjects = Subject::active()
            ->with('grade:id,name_ar,name_en', 'semester:id,name_ar,name_en')
            ->ordered()
            ->get();

        return view('panel.teacher.courses.create', compact('parentCategories', 'subjects'));
    }

    public function store(Request $request)
    {
        // Use the trait method with isAdmin = false (teacher mode)
        $response = $this->storeCourse($request, false);

        // For web requests (non-API), redirect to create page instead of back
        if (!request()->expectsJson() && $response instanceof \Illuminate\Http\RedirectResponse) {
            $session = $response->getSession();
            if ($session && $session->has('success')) {
                return redirect()->route('teacher.courses.create')
                    ->with('success', $session->get('success'));
            }
        }

        return $response;
    }

    /**
     * Update course - FIXED METHOD
     */
    public function update(Request $request, Course $course)
    {
        // Check if teacher owns this course
        if ($course->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this course');
        }

        // Use the trait method with isAdmin = false (teacher mode)
        return $this->updateCourse($request, $course, false);
    }

    /**
     * Delete course - FIXED METHOD  
     */
    public function destroy(Course $course)
    {
        // Check if teacher owns this course
        if ($course->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this course');
        }

        return $this->deleteCourse($course);
    }

    /**
     * Show specific course details
     */
    public function showCourse(Course $course)
    {
        // Check if teacher owns this course
        if ($course->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this course');
        }

        $course->load([
            'subject:id,name_ar,name_en',
            'sections' => function($query) {
                $query->whereNull('parent_id')->orderBy('created_at');
            },
            'sections.contents' => function($query) {
                $query->orderBy('order');
            }
        ]);

        // Get direct contents (not in sections)
        $directContents = $course->contents()
            ->whereNull('section_id')
            ->orderBy('order')
            ->get();

        return view('panel.teacher.courses.show', compact('course', 'directContents'));
    }

    /**
     * Show form to edit course
     */
    public function editCourse(Course $course)
    {
        // Check if teacher owns this course
        if ($course->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this course');
        }

        $parentCategories = Category::roots()
            ->active()
            ->ordered()
            ->get();

        $subjects = Subject::active()
            ->with('grade:id,name_ar,name_en', 'semester:id,name_ar,name_en')
            ->ordered()
            ->get();

        return view('panel.teacher.courses.edit', compact('course', 'parentCategories', 'subjects'));
    }

    

    // === SECTION MANAGEMENT ===

    /**
     * Show course sections
     */
    public function courseSections(Course $course)
    {
        // Check if teacher owns this course
        if ($course->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this course');
        }

        // Load course with nested relationships - EXACTLY like admin
        $course->load([
            'sections' => function($query) {
                $query->whereNull('parent_id')->orderBy('order');
            },
            'sections.contents' => function($query) {
                $query->orderBy('order', 'asc')->orderBy('created_at');
            },
            'sections.children', // Load child sections
            'sections.children.contents' => function($query) {
                $query->orderBy('order', 'asc')->orderBy('created_at');
            }
        ]);

        // Get direct contents (contents not assigned to any section) - EXACTLY like admin
        $directContents = $course->contents()
            ->whereNull('section_id')
            ->orderBy('order', 'asc')
            ->orderBy('created_at')
            ->get();

        return view('panel.teacher.courses.sections.index', compact('course', 'directContents'));
    }
    /**
     * Create new section
     */
    public function createSection(Course $course)
    {
        // Check if teacher owns this course
        if ($course->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this course');
        }

        $sections = $course->sections;
        // Calculate next order value (max + 1)
        $maxOrder = (CourseSection::where('course_id', $course->id)->max('order') ?? 0) + 1;

        return view('panel.teacher.courses.sections.create', compact('course', 'sections', 'maxOrder'));
    }

    /**
     * Store new section
     */
    public function storeSection(Request $request, Course $course)
    {
        // Check if teacher owns this course
        if ($course->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this course');
        }

        return $this->storeCourseSection($request, $course);
    }

    /**
     * Edit section
     */
    public function editSection(Course $course, CourseSection $section)
    {
        // Check if teacher owns this course
        if ($course->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this course');
        }

        // Ensure section belongs to this course
        if ($section->course_id !== $course->id) {
            abort(404, 'Section not found for this course');
        }

        $sections = $course->sections()
            ->where('id', '!=', $section->id)
            ->whereNull('parent_id')
            ->get();

        return view('panel.teacher.courses.sections.edit', compact('course', 'section', 'sections'));
    }

    /**
     * Update section
     */
    public function updateSection(Request $request, Course $course, CourseSection $section)
    {
        // Check if teacher owns this course
        if ($course->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this course');
        }

        return $this->updateCourseSection($request, $course, $section);
    }

    /**
     * Delete section
     */
    public function deleteSection(Course $course, CourseSection $section)
    {
        // Check if teacher owns this course
        if ($course->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this course');
        }

        return $this->deleteCourseSection($course, $section);
    }

    // === CONTENT MANAGEMENT ===

    /**
     * Create new content
     */
    public function createContent(Request $request, Course $course)
    {
        // Check if teacher owns this course
        if ($course->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this course');
        }

        $sections = $course->sections;
        $selectedSectionId = $request->get('section_id');
        // Calculate max order for this course to help users set proper order values
        $maxOrder = CourseContent::where('course_id', $course->id)->max('order') ?? 0;

        return view('panel.teacher.courses.contents.create', compact('course', 'sections', 'selectedSectionId', 'maxOrder'));
    }

    /**
     * Store new content
     */
    public function storeContent(Request $request, Course $course)
    {
        // Check if teacher owns this course
        if ($course->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this course');
        }

        return $this->storeCourseContent($request, $course);
    }

    /**
     * Edit content
     */
    public function editContent(Course $course, CourseContent $content)
    {
        // Check if teacher owns this course
        if ($course->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this course');
        }

        if ($content->course_id !== $course->id) {
            abort(404, 'Content not found for this course');
        }

        $sections = $course->sections;
        // Calculate max order for this course to help users set proper order values
        $maxOrder = CourseContent::where('course_id', $course->id)->max('order') ?? 0;

        return view('panel.teacher.courses.contents.edit', compact('course', 'content', 'sections', 'maxOrder'));
    }

    /**
     * Update content
     */
    public function updateContent(Request $request, Course $course, CourseContent $content)
    {
        // Check if teacher owns this course
        if ($course->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this course');
        }

        if ($content->course_id !== $course->id) {
            abort(404, 'Content not found for this course');
        }

        return $this->updateCourseContent($request, $content);
    }

    /**
     * Delete content
     */
    public function deleteContent(Course $course, CourseContent $content)
    {
        // Check if teacher owns this course
        if ($course->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this course');
        }

        return $this->deleteCourseContent($content);
    }

    // === AJAX METHODS ===

    /**
     * Get child categories (AJAX)
     */
     public function getChildCategories($parentId)
    {
        try {
            $categories = Category::where('parent_id', $parentId)
                ->active()
                ->ordered()
                ->get(['id', 'name_ar', 'name_en']);

            return response()->json($categories);
        } catch (\Exception $e) {
            \Log::error('Error in getChildCategories: ' . $e->getMessage());
            return response()->json([]);
        }
    }

    /**
     * Get subjects by category ID (AJAX) - Updated to match admin pattern
     */
    public function getSubjectsByCategory(Request $request)
    {
        $categoryId = $request->get('category_id');
        
        if (!$categoryId) {
            return response()->json([]);
        }

        try {
            // Use the trait method that returns formatted data for API
            $subjects = $this->getSubjectsByCategoryForApi($categoryId);
            
            // Transform data to match expected format in JavaScript
            $formattedSubjects = $subjects->map(function($subject) {
                return [
                    'id' => $subject['id'],
                    'name' => $subject['name_ar'], // Use Arabic name as primary
                    'name_ar' => $subject['name_ar'],
                    'name_en' => $subject['name_en'] ?? '',
                ];
            });
            
            return response()->json($formattedSubjects->toArray());
            
        } catch (\Exception $e) {
            \Log::error('Error in getSubjectsByCategory: ' . $e->getMessage());
            \Log::error('Category ID: ' . $categoryId);
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([]);
        }
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
                'name'           => 'required|string|max:255',
                'email'          => 'required|email|unique:users,email,'.$user->id,
                'phone'          => 'nullable|string|max:20|unique:users,phone,'.$user->id,
                'photo'          => 'nullable|image|mimes:jpg,jpeg,png',
                'name_of_lesson' => 'nullable|string|max:255',
                'description_en' => 'nullable|string',
                'description_ar' => 'nullable|string',
                'facebook'       => 'nullable|url',
                'instagram'      => 'nullable|url',
                'youtube'        => 'nullable|url',
                'whatsapp'       => 'nullable|string|max:20',
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
                'facebook.url' => 'رابط فيسبوك غير صحيح',
                'instagram.url' => 'رابط انستقرام غير صحيح',
                'youtube.url' => 'رابط يوتيوب غير صحيح',
            ]);

            // Update user basic info
            $user->name  = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;

            // Handle photo upload
            if ($request->hasFile('photo')) {
                $filename = uploadImage('assets/admin/uploads', $request->photo);
                $user->photo = $filename;
            }

            $user->save();

            // Update or create teacher record
            $teacherData = [
                'name'           => $request->name,
                'name_of_lesson' => $request->name_of_lesson,
                'description_en' => $request->description_en,
                'description_ar' => $request->description_ar,
                'facebook'       => $request->facebook,
                'instagram'      => $request->instagram,
                'youtube'        => $request->youtube,
                'whataspp'       => $request->whatsapp, // Note: keeping your typo "whataspp" to match your schema
                'photo'          => $user->photo,
                'user_id'        => $user->id,
            ];

            // Update or create teacher record
            $user->teacher()->updateOrCreate(
                ['user_id' => $user->id],
                $teacherData
            );

            return back()->with('success', 'تم تحديث الحساب بنجاح');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء تحديث الحساب: ' . $e->getMessage())->withInput();
        }
    }
        
   
      // Community methods
     public function createPost(Request $request)
    {
        return $this->handleCreatePost($request);
    }

    public function toggleLike(Request $request)
    {
        return $this->handleToggleLike($request);
    }

    public function addComment(Request $request)
    {
        return $this->handleAddComment($request);
    }


     // === EXAM MANAGEMENT ===

    /**
     * Display a listing of teacher's exams
     */
    public function examsMethod(Request $request)
    {
        $user = Auth::user();

        $query = Exam::with(['course', 'subject', 'section'])
                     ->where(function($q) use ($user) {
                         $q->where('created_by', $user->id)
                           ->orWhereHas('course', function($courseQ) use ($user) {
                               $courseQ->where('teacher_id', $user->id);
                           });
                     });

        // Filter by course
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        // Filter by subject  
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title_en', 'like', "%{$search}%")
                  ->orWhere('title_ar', 'like', "%{$search}%");
            });
        }

        $exams = $query->paginate(15);
        
        // Get teacher's courses and subjects
        $courses = Course::where('teacher_id', $user->id)->with('subject')->get();
        $subjects = Subject::active()->ordered()->get();

        return view('panel.teacher.exams.index', compact('exams', 'courses', 'subjects'));
    }

    /**
     * Show the form for creating a new exam
     */
    public function createExamMethod()
    {
        $user = Auth::user();

        $courses = Course::where('teacher_id', $user->id)
                        ->with(['subject', 'sections'])
                        ->get();
        $subjects = Subject::active()
            ->with('grade:id,name_ar,name_en', 'semester:id,name_ar,name_en')
            ->ordered()
            ->get();

        return view('panel.teacher.exams.create', compact('courses', 'subjects'));
    }

    /**
     * Store a newly created exam - USING TRAIT
     */
    public function storeExamMethod(Request $request)
    {
        // Add debugging
        \Log::info('storeExamMethod called');
        \Log::info('Request data:', $request->all());
        
        try {
            $response = $this->storeExam($request, false);
            
            // Fix the logging - convert stdClass to array
            $responseData = $response->getData();
            \Log::info('Trait response:', json_decode(json_encode($responseData), true));
            
            if ($request->expectsJson()) {
                return $response;
            }
            
            if ($responseData->success) {
               return redirect()->route('teacher.exams.exam_questions.index', $responseData->data->id)
                        ->with('success', $responseData->message);
            }
            
            \Log::error('Exam creation failed:', [
                'success' => $responseData->success,
                'message' => $responseData->message ?? 'No message',
                'errors' => isset($responseData->errors) ? json_decode(json_encode($responseData->errors), true) : []
            ]);
            
            return redirect()->back()->withInput()->with('error', $responseData->message ?? 'Unknown error');
            
        } catch (\Exception $e) {
            \Log::error('Exception in storeExamMethod:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified exam
     */
    public function showExamMethod(Exam $exam)
    {
        // Check if teacher owns this exam or the course it belongs to
        if (!$exam->isOwnedByTeacher()) {
            abort(403, __('messages.unauthorized_access'));
        }

        $exam->load(['course', 'subject', 'section', 'questions.options', 'attempts.user']);
        return view('panel.teacher.exams.show', compact('exam'));
    }

    /**
     * Show the form for editing the exam
     */
    public function editExamMethod(Exam $exam)
    {
        // Check if teacher owns this exam or the course it belongs to
        if (!$exam->isOwnedByTeacher()) {
            abort(403, __('messages.unauthorized_access'));
        }

        $user = Auth::user();
        $courses = Course::where('teacher_id', $user->id)
                        ->with(['subject', 'sections'])
                        ->get();
        $subjects = Subject::active()
            ->with('grade:id,name_ar,name_en', 'semester:id,name_ar,name_en')
            ->ordered()
            ->get();
        $sections = CourseSection::get();
        return view('panel.teacher.exams.edit', compact('exam', 'courses', 'subjects','sections'));
    }

    /**
     * Update the specified exam - USING TRAIT
     */
    public function updateExamMethod(Request $request, Exam $exam)
    {
        // Check if teacher owns this exam or the course it belongs to
        if (!$exam->isOwnedByTeacher()) {
            abort(403, __('messages.unauthorized_access'));
        }

        $response = $this->updateExam($request, $exam, false); // false = isTeacher
        
        if ($request->expectsJson()) {
            return $response;
        }
        
        $data = $response->getData();
        if ($data->success) {
            return redirect()->route('teacher.exams.index')
                ->with('success', $data->message);
        }
        
        return redirect()->back()->withInput()->with('error', $data->message);
    }

    /**
     * Remove the specified exam - USING TRAIT
     */
    public function destroyExamMethod(Exam $exam)
    {
        // Check if teacher owns this exam or the course it belongs to
        if (!$exam->isOwnedByTeacher()) {
            abort(403, __('messages.unauthorized_access'));
        }

        $response = $this->deleteExam($exam, false); // false = isTeacher
        
        if (request()->expectsJson()) {
            return $response;
        }
        
        $data = $response->getData();
        if ($data->success) {
            return redirect()->route('teacher.exams.index')
                ->with('success', $data->message);
        }
        
        return redirect()->back()->with('error', $data->message);
    }

  // === EXAM-SPECIFIC QUESTIONS MANAGEMENT ===

    /**
     * Display questions for specific exam
     */
    public function examQuestionsIndex(Request $request, Exam $exam)
    {
        // Check if teacher owns this exam or the course it belongs to
        if (!$exam->isOwnedByTeacher()) {
            abort(403, __('messages.unauthorized_access'));
        }

        $query = $exam->questions()->with(['options']);

        // Apply filters
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title_en', 'like', "%{$search}%")
                ->orWhere('title_ar', 'like', "%{$search}%")
                ->orWhere('question_en', 'like', "%{$search}%")
                ->orWhere('question_ar', 'like', "%{$search}%");
            });
        }

        // Order by the order in exam
        $questions = $query->orderBy('exam_questions.order')->paginate(15);

        return view('panel.teacher.exams.questions.index', compact('exam', 'questions'));
    }

    /**
     * Show form to create question for specific exam
     */
    public function examQuestionsCreate(Exam $exam)
    {
        // Check if teacher owns this exam or the course it belongs to
        if (!$exam->isOwnedByTeacher()) {
            abort(403, __('messages.unauthorized_access'));
        }

        return view('panel.teacher.exams.questions.create', compact('exam'));
    }

    /**
     * Store question and add to exam
     */
    public function examQuestionsStore(Request $request, Exam $exam)
    {
        // Check if teacher owns this exam or the course it belongs to
        if (!$exam->isOwnedByTeacher()) {
            abort(403, __('messages.unauthorized_access'));
        }

        // Set course_id to match exam's course if not provided
        if (!$request->filled('course_id') && $exam->course_id) {
            $request->merge(['course_id' => $exam->course_id]);
        }

        // Use the trait to create the question
        $response = $this->storeQuestion($request, false); // false = isTeacher
        
        $data = $response->getData();
        
        if (!$data->success) {
            if ($request->expectsJson()) {
                return $response;
            }
            return redirect()->back()->withInput()->with('error', $data->message);
        }
        
        $question = Question::find($data->data->id);
        
        // Add the question to the exam
        DB::beginTransaction();
        
        try {
            $maxOrder = $exam->questions()->max('exam_questions.order') ?? 0;
            
            $exam->questions()->attach($question->id, [
                'order' => $maxOrder + 1,
                'grade' => $question->grade,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update total grade
            $exam->update([
                'total_grade' => $exam->questions()->sum('exam_questions.grade')
            ]);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('messages.question_created_and_added_to_exam'),
                    'data' => [
                        'question' => $question->load(['course', 'options']),
                        'exam' => $exam->fresh(['questions'])
                    ]
                ], 201);
            }

            return redirect()->route('teacher.exams.exam_questions.index', $exam)
                ->with('success', __('messages.question_created_and_added_to_exam'));

        } catch (\Exception $e) {
            DB::rollback();
            
            // If adding to exam fails, delete the created question
            $question->delete();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.question_creation_failed'),
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()->withInput()
                ->with('error', __('messages.question_creation_failed'));
        }
    }

    /**
     * Show specific exam question
     */
    public function examQuestionsShow(Exam $exam, Question $question)
    {
        // Check if teacher owns this exam or the course it belongs to
        if (!$exam->isOwnedByTeacher()) {
            abort(403, __('messages.unauthorized_access'));
        }

        // Ensure the question belongs to this exam
        if (!$exam->questions()->where('questions.id', $question->id)->exists()) {
            abort(404, __('messages.question_not_found_in_exam'));
        }

        $question->load(['course', 'options']);
        
        // Get the question's details in this exam (order, grade)
        $examQuestion = $exam->questions()->where('questions.id', $question->id)->first();
        
        return view('panel.teacher.exams.questions.show', compact('exam', 'question', 'examQuestion'));
    }

    /**
     * Edit exam question
     */
    public function examQuestionsEdit(Exam $exam, Question $question)
    {
        // Check if teacher owns this exam or the course it belongs to
        if (!$exam->isOwnedByTeacher()) {
            abort(403, __('messages.unauthorized_access'));
        }

        // Ensure the question belongs to this exam
        if (!$exam->questions()->where('questions.id', $question->id)->exists()) {
            abort(404, __('messages.question_not_found_in_exam'));
        }

        $question->load('options');
        
        return view('panel.teacher.exams.questions.edit', compact('exam', 'question'));
    }

    /**
     * Update exam question
     */
    public function examQuestionsUpdate(Request $request, Exam $exam, Question $question)
    {
        // Check if teacher owns this exam or the course it belongs to
        if (!$exam->isOwnedByTeacher()) {
            abort(403, __('messages.unauthorized_access'));
        }

        // Ensure the question belongs to this exam
        if (!$exam->questions()->where('questions.id', $question->id)->exists()) {
            abort(404, __('messages.question_not_found_in_exam'));
        }

        // Set course_id to match exam's course if not provided
        if (!$request->filled('course_id') && $exam->course_id) {
            $request->merge(['course_id' => $exam->course_id]);
        }

        // Use the trait to update the question
        $response = $this->updateQuestion($request, $question, false); // false = isTeacher
        
        if ($request->expectsJson()) {
            return $response;
        }
        
        $data = $response->getData();
        if ($data->success) {
            // Update the total grade of the exam if the question grade changed
            $exam->update([
                'total_grade' => $exam->questions()->sum('exam_questions.grade')
            ]);

            return redirect()->route('teacher.exams.exam_questions.index', $exam)
                ->with('success', $data->message);
        }
        
        return redirect()->back()->withInput()->with('error', $data->message);
    }

    /**
     * Delete exam question
     */
    public function examQuestionsDestroy(Exam $exam, Question $question)
    {
        // Check if teacher owns this exam or the course it belongs to
        if (!$exam->isOwnedByTeacher()) {
            abort(403, __('messages.unauthorized_access'));
        }

        // Ensure the question belongs to this exam
        if (!$exam->questions()->where('questions.id', $question->id)->exists()) {
            abort(404, __('messages.question_not_found_in_exam'));
        }

        DB::beginTransaction();
        
        try {
            // First detach from exam
            $exam->questions()->detach($question->id);
            
            // Check if this question is used in other exams
            $otherExams = $question->exams()->where('exams.id', '!=', $exam->id)->count();
            
            // If not used in other exams, delete the question completely
            if ($otherExams === 0) {
                $question->delete();
                $message = __('messages.question_deleted_successfully');
            } else {
                $message = __('messages.question_removed_from_exam');
            }

            // Update total grade
            $exam->update([
                'total_grade' => $exam->questions()->sum('exam_questions.grade')
            ]);

            DB::commit();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }
            
            return redirect()->route('teacher.exams.exam_questions.index', $exam)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.question_deletion_failed'),
                    'error' => $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', __('messages.question_deletion_failed'));
        }
    }

 

  

    /**
     * View exam results
     */
    public function examResults(Exam $exam)
    {
        // Check if teacher owns this exam or the course it belongs to
        if (!$exam->isOwnedByTeacher()) {
            abort(403, __('messages.unauthorized_access'));
        }

        $attempts = $exam->attempts()
            ->with(['user', 'answers.question'])
            ->where('status', 'completed')
            ->orderBy('submitted_at', 'desc')
            ->paginate(20);

        $stats = [
            'total_attempts' => $exam->attempts()->where('status', 'completed')->count(),
            'passed_attempts' => $exam->attempts()->where('is_passed', true)->count(),
            'average_score' => round($exam->attempts()->where('status', 'completed')->avg('percentage'), 2),
            'highest_score' => $exam->attempts()->where('status', 'completed')->max('percentage'),
            'lowest_score' => $exam->attempts()->where('status', 'completed')->min('percentage'),
        ];

        return view('panel.teacher.exams.results', compact('exam', 'attempts', 'stats'));
    }

    /**
     * View specific attempt details
     */
    public function viewExamAttempt(Exam $exam, ExamAttempt $attempt)
    {
        // Check if teacher owns this exam or the course it belongs to
        if (!$exam->isOwnedByTeacher()) {
            abort(403, __('messages.unauthorized_access'));
        }

        if ($attempt->exam_id !== $exam->id) {
            abort(404);
        }

        $attempt->load(['user', 'answers.question.options']);
        return view('panel.teacher.exams.view-attempt', compact('exam', 'attempt'));
    }

    /**
     * Ajax methods for exam creation
     */
    public function getSubjectCoursesForExam(Subject $subject)
    {
        $user = Auth::user();
        
        $courses = Course::where('subject_id', $subject->id)
            ->where('teacher_id', $user->id)
            ->select('id', 'title_en', 'title_ar')
            ->get();
            
        return response()->json($courses);
    }

    public function getCourseSectionsForExam(Course $course)
    {
        // Check if teacher owns this course
        if ($course->teacher_id !== Auth::id()) {
            return response()->json([]);
        }

        $sections = $course->sections()->get(['id', 'title_en', 'title_ar', 'parent_id']);

        // Build hierarchical structure
        $parentSections = $sections->whereNull('parent_id');
        $formattedSections = [];

        foreach ($parentSections as $section) {
            $formattedSections[] = [
                'id' => $section->id,
                'title' => app()->getLocale() === 'ar' ? $section->title_ar : $section->title_en,
                'level' => 0
            ];

            $this->addChildSectionsForExam($sections, $section->id, $formattedSections, 1);
        }

        return response()->json($formattedSections);
    }

    /**
     * Helper function to add child sections recursively
     */
    private function addChildSectionsForExam($allSections, $parentId, &$formattedSections, $level)
    {
        $children = $allSections->where('parent_id', $parentId);

        foreach ($children as $child) {
            $formattedSections[] = [
                'id' => $child->id,
                'title' => str_repeat('— ', $level) . (app()->getLocale() === 'ar' ? $child->title_ar : $child->title_en),
                'level' => $level
            ];

            // Recursively add children
            $this->addChildSectionsForExam($allSections, $child->id, $formattedSections, $level + 1);
        }
    }

    /**
     * Get section contents (lessons) for exam creation
     */
    public function getSectionContentsForExam(CourseSection $section)
    {
        // Get the course to verify teacher owns it
        $course = Course::find($section->course_id);

        if (!$course || $course->teacher_id !== Auth::id()) {
            return response()->json([]);
        }

        $contents = CourseContent::where('section_id', $section->id)
            ->select('id', 'title_ar', 'title_en')
            ->orderBy('order')
            ->get();

        return response()->json($contents);
    }

    /**
     * Get attempt answers as JSON for AJAX modal
     */
    public function getAttemptAnswers(Exam $exam, ExamAttempt $attempt)
    {
        // Check if teacher owns this exam
        if ($exam->created_by !== Auth::id()) {
            return response()->json(['success' => false, 'message' => __('messages.unauthorized_access')], 403);
        }

        if ($attempt->exam_id !== $exam->id) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }

        $attempt->load(['answers.question.options', 'user']);

        $html = view('panel.teacher.exams.partials.attempt-answers', compact('attempt'))->render();

        return response()->json([
            'html' => $html,
            'success' => true
        ]);
    }

    // === COMMUNITY METHODS ===

    public function community()
    {
        $user = Auth::user();
        $posts = $this->getCommunityPosts(20);

        return view('panel.teacher.community', compact('user', 'posts'));
    }

    public function createPost(Request $request)
    {
        return $this->handleCreatePost($request);
    }

    public function toggleLike(Request $request)
    {
        return $this->handleToggleLike($request);
    }

    public function addComment(Request $request)
    {
        return $this->handleAddComment($request);
    }

    public function addReply(Request $request)
    {
        return $this->handleAddReply($request);
    }

}