<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Exam;
use App\Models\Course;
use App\Models\Question;
use App\Models\ExamAttempt;
use App\Models\ExamAnswer;
use App\Models\Subject;
use App\Traits\ExamManagementTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExamController extends Controller
{
    use ExamManagementTrait;

    public function __construct()
    {
        $this->middleware('permission:exam-table', ['only' => ['index', 'show']]);
        $this->middleware('permission:exam-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:exam-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:exam-delete', ['only' => ['destroy']]);
    }

    

    /**
     * Display a listing of exams
     */
    public function index(Request $request)
    {
        $query = Exam::with(['course', 'subject', 'section', 'creator']);

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
        $courses = Course::with('subject')->get();
        $subjects = Subject::active()->ordered()->get();

        return view('admin.exams.index', compact('exams', 'courses', 'subjects'));
    }

    /**
     * Show the form for creating a new exam
     */
    public function create()
    {
        $courses = Course::with(['subject', 'sections'])->get();
        $subjects = Subject::active()->ordered()->get();
        return view('admin.exams.create', compact('courses', 'subjects'));
    }

    /**
     * Store a newly created exam - USING TRAIT
     */
    public function store(Request $request)
    {
        $response = $this->storeExam($request, true); // true = isAdmin
        
        if ($request->expectsJson()) {
            return $response;
        }
        
        $data = $response->getData();
        if ($data->success) {
            return redirect()->route('exams.questions.manage', $data->data->id)
                ->with('success', $data->message);
        }
        
        return redirect()->back()->withInput()->with('error', $data->message);
    }

    /**
     * Display the specified exam
     */
    public function show(Exam $exam)
    {
        $exam->load(['course', 'subject', 'section', 'creator', 'questions.options', 'attempts.user']);
        return view('admin.exams.show', compact('exam'));
    }

    /**
     * Show the form for editing the exam
     */
    public function edit(Exam $exam)
    {
        $courses = Course::with(['subject', 'sections'])->get();
        $subjects = Subject::active()->ordered()->get();
        return view('admin.exams.edit', compact('exam', 'courses', 'subjects'));
    }

    /**
     * Update the specified exam - USING TRAIT
     */
    public function update(Request $request, Exam $exam)
    {
        $response = $this->updateExam($request, $exam, true); // true = isAdmin
        
        if ($request->expectsJson()) {
            return $response;
        }
        
        $data = $response->getData();
        if ($data->success) {
            return redirect()->route('exams.index')
                ->with('success', $data->message);
        }
        
        return redirect()->back()->withInput()->with('error', $data->message);
    }

    /**
     * Remove the specified exam - USING TRAIT
     */
    public function destroy(Exam $exam)
    {
        $response = $this->deleteExam($exam, true); // true = isAdmin
        
        if (request()->expectsJson()) {
            return $response;
        }
        
        $data = $response->getData();
        if ($data->success) {
            return redirect()->route('exams.index')
                ->with('success', $data->message);
        }
        
        return redirect()->back()->with('error', $data->message);
    }

    //Ajax
    public function getSubjectCourses(Subject $subject)
    {
        $courses = Course::where('subject_id', $subject->id)
            ->select('id', 'title_en', 'title_ar')
            ->get();
            
        return response()->json($courses);
    }

    public function getCourseSections(Course $course)
    {
        $sections = $course->sections()->get(['id', 'title_en', 'title_ar', 'parent_id']);

        // Build hierarchical structure (same as before)
        $parentSections = $sections->whereNull('parent_id');
        $formattedSections = [];

        foreach ($parentSections as $section) {
            $formattedSections[] = [
                'id' => $section->id,
                'title' => app()->getLocale() === 'ar' ? $section->title_ar : $section->title_en,
                'level' => 0
            ];

            $this->addChildSections($sections, $section->id, $formattedSections, 1);
        }

        return response()->json($formattedSections);
    }


    public function manageQuestions(Request $request, Exam $exam)
    {
        $exam->load(['questions.options', 'course', 'subject']);
        
        // Build query for available questions
        $availableQuestionsQuery = Question::whereNotIn('id', $exam->questions->pluck('id'))
            ->with(['options', 'course']);
        
        // Apply filters
        if ($request->filled('course_filter')) {
            $availableQuestionsQuery->where('course_id', $request->course_filter);
        }
        
        if ($request->filled('type_filter')) {
            $availableQuestionsQuery->where('type', $request->type_filter);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $availableQuestionsQuery->where(function($q) use ($search) {
                $q->where('title_en', 'LIKE', "%{$search}%")
                ->orWhere('title_ar', 'LIKE', "%{$search}%")
                ->orWhere('question_en', 'LIKE', "%{$search}%")
                ->orWhere('question_ar', 'LIKE', "%{$search}%");
            });
        }
        
        $availableQuestions = $availableQuestionsQuery->get();
        
        // Get courses for filter dropdown
        $courses = Course::orderBy('title_en')->get();
        
        // Question types for filter
        $questionTypes = [
            'multiple_choice' => 'Multiple Choice',
            'true_false' => 'True/False',
            'essay' => 'Essay'
        ];
        
        return view('admin.exams.manage-questions', compact(
            'exam', 
            'availableQuestions', 
            'courses', 
            'questionTypes'
        ));
    }

    // Add this new method for question details
    public function getQuestionDetails(Question $question)
    {
        $question->load(['options', 'course']);
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $question->id,
                'title_en' => $question->title_en,
                'title_ar' => $question->title_ar,
                'question_en' => $question->question_en,
                'question_ar' => $question->question_ar,
                'type' => $question->type,
                'grade' => $question->grade,
                'explanation_en' => $question->explanation_en,
                'explanation_ar' => $question->explanation_ar,
                'course' => $question->course ? [
                    'name_en' => $question->course->name_en,
                    'name_ar' => $question->course->name_ar
                ] : null,
                'options' => $question->options->map(function($option) {
                    return [
                        'option_en' => $option->option_en,
                        'option_ar' => $option->option_ar,
                        'is_correct' => $option->is_correct,
                        'order' => $option->order
                    ];
                })->sortBy('order')->values()
            ]
        ]);
    }

    /**
     * Add questions to exam - USING TRAIT
     */
    public function addQuestions(Request $request, Exam $exam)
    {
        $response = $this->addQuestionsToExam($request, $exam, true); // true = isAdmin
        
        if ($request->expectsJson()) {
            return $response;
        }
        
        $data = $response->getData();
        if ($data->success) {
            return redirect()->route('exams.questions.manage', $exam)
                ->with('success', $data->message);
        }
        
        return redirect()->back()->with('error', $data->message);
    }

    /**
     * Remove question from exam - USING TRAIT
     */
    public function removeQuestion(Exam $exam, Question $question)
    {
        $response = $this->removeQuestionFromExam($exam, $question, true); // true = isAdmin
        
        if (request()->expectsJson()) {
            return $response;
        }
        
        $data = $response->getData();
        if ($data->success) {
            return redirect()->route('exams.questions.manage', $exam)
                ->with('success', $data->message);
        }
        
        return redirect()->back()->with('error', $data->message);
    }

    /**
     * Update question order and grade in exam - USING TRAIT
     */
    public function updateQuestions(Request $request, Exam $exam)
    {
        $response = $this->updateExamQuestions($request, $exam, true); // true = isAdmin
        
        if ($request->expectsJson()) {
            return $response;
        }
        
        $data = $response->getData();
        if ($data->success) {
            return redirect()->route('exams.questions.manage', $exam)
                ->with('success', $data->message);
        }
        
        return redirect()->back()->with('error', $data->message);
    }

  

    /**
     * Helper function to add child sections recursively
     */
    private function addChildSections($allSections, $parentId, &$formattedSections, $level)
    {
        $children = $allSections->where('parent_id', $parentId);

        foreach ($children as $child) {
            $formattedSections[] = [
                'id' => $child->id,
                'title' => str_repeat('— ', $level) . (app()->getLocale() === 'ar' ? $child->title_ar : $child->title_en),
                'level' => $level
            ];

            // Recursively add children
            $this->addChildSections($allSections, $child->id, $formattedSections, $level + 1);
        }
    }

    /**
     * View exam results
     */
    public function results(Exam $exam)
    {
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

        return view('admin.exams.results', compact('exam', 'attempts', 'stats'));
    }

    /**
     * View specific attempt details
     */
    public function viewAttempt(Exam $exam, ExamAttempt $attempt)
    {
        if ($attempt->exam_id !== $exam->id) {
            abort(404);
        }

        $attempt->load(['user', 'answers.question.options']);
        return view('admin.exams.view-attempt', compact('exam', 'attempt'));
    }
}
