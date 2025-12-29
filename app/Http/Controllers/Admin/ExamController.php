<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Exam;
use App\Models\Course;
use App\Models\Question;
use App\Models\ExamAttempt;
use App\Models\ExamAnswer;
use App\Models\Subject;
use App\Models\CourseContent;
use App\Models\CourseSection;
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
        $subjects = Subject::active()
            ->with('grade:id,name_ar,name_en', 'semester:id,name_ar,name_en')
            ->ordered()
            ->get();
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

        // إذا كان في validation errors، حطها في session
        if (isset($data->errors) && is_object($data->errors)) {
            return redirect()->back()->withInput()->withErrors((array)$data->errors);
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
        $subjects = Subject::active()
            ->with('grade:id,name_ar,name_en', 'semester:id,name_ar,name_en')
            ->ordered()
            ->get();
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

        // إذا كان في validation errors، حطها في session
        if (isset($data->errors) && is_object($data->errors)) {
            return redirect()->back()->withInput()->withErrors((array)$data->errors);
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

    public function getSectionContents(CourseSection $section)
    {
        $contents = CourseContent::where('section_id', $section->id)
            ->select('id', 'title_ar', 'title_en')
            ->orderBy('order')
            ->get();

        return response()->json($contents);
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

    /**
     * Get attempt answers as JSON for AJAX modal
     */
    public function getAttemptAnswers(Exam $exam, ExamAttempt $attempt)
    {
        if ($attempt->exam_id !== $exam->id) {
            abort(404);
        }

        $attempt->load(['answers.question.options', 'user']);

        // Build HTML for modal
        $html = '<div class="answers-list">';

        if ($attempt->answers->count() === 0) {
            $html .= '<div class="alert alert-info">' . __('messages.no_answers') . '</div>';
        } else {
            foreach ($attempt->answers as $answer) {
                $question = $answer->question;
                $status = $answer->is_correct === null ? 'pending' : ($answer->is_correct ? 'correct' : 'incorrect');
                $statusBadge = $status === 'pending' ? 'warning' : ($status === 'correct' ? 'success' : 'danger');

                $html .= '<div class="answer-item mb-3 pb-3 border-bottom">';
                $html .= '<div class="d-flex justify-content-between align-items-start mb-2">';
                $html .= '<h6 class="mb-0">' . htmlspecialchars($question->question) . '</h6>';
                $html .= '<span class="badge badge-' . $statusBadge . '">';

                if ($status === 'pending') {
                    $html .= '<i class="fas fa-hourglass-half"></i> ' . __('messages.pending');
                } elseif ($status === 'correct') {
                    $html .= '<i class="fas fa-check"></i> ' . __('messages.correct');
                } else {
                    $html .= '<i class="fas fa-times"></i> ' . __('messages.incorrect');
                }

                $html .= '</span>';
                $html .= '</div>';

                // Answer content based on type
                if ($question->type === 'multiple_choice') {
                    $selectedOptions = $answer->selected_options ?? [];
                    $html .= '<div class="answer-content">';
                    foreach ($question->options as $option) {
                        $isSelected = in_array($option->id, (array)$selectedOptions);
                        $html .= '<div class="form-check">';
                        $html .= '<input class="form-check-input" type="checkbox" disabled ' . ($isSelected ? 'checked' : '') . '>';
                        $html .= '<label class="form-check-label">';
                        $html .= htmlspecialchars($option->option) . ($option->is_correct ? ' <strong class="text-success">(✓)</strong>' : '');
                        $html .= '</label>';
                        $html .= '</div>';
                    }
                    $html .= '</div>';
                } elseif ($question->type === 'true_false') {
                    $selectedAnswer = $answer->selected_options[0] ?? null;
                    $correctOption = $question->options()->where('is_correct', true)->first();
                    $correctAnswer = strtolower($correctOption?->option_en ?? '') === 'true';

                    $html .= '<div class="answer-content">';
                    $html .= '<p><strong>' . __('messages.student_answer') . ':</strong> ' . ($selectedAnswer ? __('messages.true') : __('messages.false')) . '</p>';
                    $html .= '<p><strong>' . __('messages.correct_answer') . ':</strong> ' . ($correctAnswer ? __('messages.true') : __('messages.false')) . '</p>';
                    $html .= '</div>';
                } elseif ($question->type === 'essay') {
                    $html .= '<div class="answer-content">';
                    $html .= '<p><strong>' . __('messages.student_answer') . ':</strong></p>';
                    $html .= '<div class="p-2 bg-light rounded">';
                    $html .= htmlspecialchars($answer->essay_answer ?? __('messages.no_answer'));
                    $html .= '</div>';
                    $html .= '</div>';
                }

                // Score info
                $html .= '<div class="mt-2">';
                $html .= '<small class="text-muted">';
                $html .= __('messages.score') . ': <strong>' . number_format($answer->score, 2) . '</strong> / ' . number_format($question->pivot->grade ?? $question->grade, 2);
                $html .= '</small>';
                $html .= '</div>';

                $html .= '</div>';
            }
        }

        $html .= '</div>';

        return response()->json([
            'html' => $html,
            'success' => true
        ]);
    }

    /**
     * Grade an essay answer
     */
    public function gradeAnswer(Exam $exam, ExamAttempt $attempt, ExamAnswer $answer)
    {
        if ($attempt->exam_id !== $exam->id || $answer->exam_attempt_id !== $attempt->id) {
            abort(404);
        }

        $request = request();
        $score = (float)$request->input('score', 0);
        $isCorrect = (int)$request->input('is_correct', 0) === 1;

        // Update the answer
        $answer->update([
            'score' => $score,
            'is_correct' => $isCorrect
        ]);

        // Recalculate attempt score and percentage
        $totalScore = (float)$attempt->answers()->sum('score');
        $totalPossible = (float)$exam->total_grade;
        if ($totalPossible <= 0) {
            $totalPossible = (float)$exam->questions()->sum('exam_questions.grade');
        }

        $percentage = $totalPossible > 0 ? (($totalScore / $totalPossible) * 100) : 0;
        $isPassed = $percentage >= $exam->passing_grade;

        // Update attempt
        $attempt->update([
            'score' => $totalScore,
            'percentage' => round($percentage, 2),
            'is_passed' => $isPassed
        ]);

        return response()->json([
            'success' => true,
            'message' => __('messages.graded_successfully'),
            'score' => $totalScore,
            'percentage' => round($percentage, 2),
            'isPassed' => $isPassed
        ]);
    }
}
