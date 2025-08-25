<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Exam;
use App\Models\Course;
use App\Models\Question;
use App\Models\ExamAttempt;
use App\Models\ExamAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExamController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:exam-table', ['only' => ['index', 'show']]);
        $this->middleware('permission:exam-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:exam-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:exam-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of exams.
     */
    public function index(Request $request)
    {
        $query = Exam::with(['course', 'creator']);

        // Filter by course
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
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
        $courses = Course::all();

        return view('admin.exams.index', compact('exams', 'courses'));
    }

    /**
     * Show the form for creating a new exam.
     */
    public function create()
    {
        $courses = Course::all();
        return view('admin.exams.create', compact('courses'));
    }

    /**
     * Store a newly created exam.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'course_id' => 'required|required_with:course_id|exists:course_sections,id',
            'section_id' => 'nullable|exists:course_sections,id',
            'duration_minutes' => 'nullable|integer|min:1',
            'attempts_allowed' => 'required|integer|min:1|max:10',
            'passing_grade' => 'required|numeric|min:0|max:100',
            'start_date' => 'nullable|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
            'shuffle_questions' => 'boolean',
            'shuffle_options' => 'boolean',
            'show_results_immediately' => 'boolean',
            'is_active' => 'boolean',
        ]);
        $exam = Exam::create([
            'title_en' => $request->title_en,
            'title_ar' => $request->title_ar,
            'description_en' => $request->description_en,
            'description_ar' => $request->description_ar,
            'course_id' => $request->course_id,
            'section_id' => $request->section_id,
            'duration_minutes' => $request->duration_minutes,
            'attempts_allowed' => $request->attempts_allowed,
            'passing_grade' => $request->passing_grade,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'shuffle_questions' => $request->has('shuffle_questions'),
            'shuffle_options' => $request->has('shuffle_options'),
            'show_results_immediately' => $request->has('show_results_immediately'),
            'is_active' => $request->has('is_active'),
            'created_by'         => auth('user')->user()?->id,
            'created_by_admin'   => auth('admin')->user()?->id,
        ]);

        return redirect()->route('exams.questions.manage', $exam)
            ->with('success', __('messages.exam_created_successfully'));
    }

    /**
     * Display the specified exam.
    */
    public function show(Exam $exam)
    {
        $exam->load(['course', 'creator', 'questions.options', 'attempts.user']);
        return view('admin.exams.show', compact('exam'));
    }

    /**
     * Show the form for editing the exam.
     */
    public function edit(Exam $exam)
    {
        $courses = Course::all();
        return view('admin.exams.edit', compact('exam', 'courses'));
    }

    /**
     * Update the specified exam.
     */
    public function update(Request $request, Exam $exam)
    {
        $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'course_id' => 'required|required_with:course_id|exists:course_sections,id',
            'section_id' => 'nullable|exists:course_sections,id',
            'duration_minutes' => 'nullable|integer|min:1',
            'attempts_allowed' => 'required|integer|min:1|max:10',
            'passing_grade' => 'required|numeric|min:0|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'shuffle_questions' => 'boolean',
            'shuffle_options' => 'boolean',
            'show_results_immediately' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $exam->update([
            'title_en' => $request->title_en,
            'title_ar' => $request->title_ar,
            'description_en' => $request->description_en,
            'description_ar' => $request->description_ar,
            'course_id' => $request->course_id,
            'section_id' => $request->section_id,
            'duration_minutes' => $request->duration_minutes,
            'attempts_allowed' => $request->attempts_allowed,
            'passing_grade' => $request->passing_grade,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'shuffle_questions' => $request->has('shuffle_questions'),
            'shuffle_options' => $request->has('shuffle_options'),
            'show_results_immediately' => $request->has('show_results_immediately'),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('exams.index')
            ->with('success', __('messages.exam_updated_successfully'));
    }

    /**
     * Remove the specified exam.
     */
    public function destroy(Exam $exam)
    {
        try {
            $exam->delete();
            return redirect()->route('exams.index')
                ->with('success', __('messages.exam_deleted_successfully'));
        } catch (\Exception $e) {
            return back()->with('error', __('messages.exam_deletion_failed'));
        }
    }

    /**
     * Manage exam questions
     */
    public function manageQuestions(Exam $exam)
    {
        $exam->load(['questions.options', 'course']);
        $availableQuestions = Question::where('course_id', $exam->course_id)
            ->whereNotIn('id', $exam->questions->pluck('id'))
            ->with('options')
            ->get();

        return view('admin.exams.manage-questions', compact('exam', 'availableQuestions'));
    }

    /**
     * Add questions to exam
     */
    public function addQuestions(Request $request, Exam $exam)
    {
        $request->validate([
            'questions' => 'required|array',
            'questions.*.id' => 'required|exists:questions,id',
            'questions.*.grade' => 'required|numeric|min:0.1|max:999.99',
        ]);

        DB::beginTransaction();
        try {
            $maxOrder = $exam->questions()->max('exam_questions.order') ?? 0;

            foreach ($request->questions as $questionData) {
                $exam->questions()->attach($questionData['id'], [
                    'order' => ++$maxOrder,
                    'grade' => $questionData['grade'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Update total grade
            $exam->update([
                'total_grade' => $exam->calculateTotalGrade()
            ]);

            DB::commit();
            return redirect()->route('exams.questions.manage', $exam)
                ->with('success', __('messages.questions_added_successfully'));

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', __('messages.questions_addition_failed'));
        }
    }

    /**
     * Remove question from exam
     */
    public function removeQuestion(Exam $exam, Question $question)
    {
        try {
            $exam->questions()->detach($question->id);

            // Update total grade
            $exam->update([
                'total_grade' => $exam->calculateTotalGrade()
            ]);

            return redirect()->route('exams.questions.manage', $exam)
                ->with('success', __('messages.question_removed_successfully'));
        } catch (\Exception $e) {
            return back()->with('error', __('messages.question_removal_failed'));
        }
    }

    /**
     * Update question order and grade in exam
     */
    public function updateQuestions(Request $request, Exam $exam)
    {
        $request->validate([
            'questions' => 'required|array',
            'questions.*.id' => 'required|exists:questions,id',
            'questions.*.order' => 'required|integer|min:1',
            'questions.*.grade' => 'required|numeric|min:0.1|max:999.99',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->questions as $questionData) {
                $exam->questions()->updateExistingPivot($questionData['id'], [
                    'order' => $questionData['order'],
                    'grade' => $questionData['grade'],
                    'updated_at' => now(),
                ]);
            }

            // Update total grade
            $exam->update([
                'total_grade' => $exam->calculateTotalGrade()
            ]);

            DB::commit();
            return redirect()->route('exams.questions.manage', $exam)
                ->with('success', __('messages.questions_updated_successfully'));

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', __('messages.questions_update_failed'));
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
            'average_score' => $exam->attempts()->where('status', 'completed')->avg('percentage'),
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
        $attempt->load(['user', 'answers.question.options']);
        return view('admin.exams.view-attempt', compact('exam', 'attempt'));
    }
}
