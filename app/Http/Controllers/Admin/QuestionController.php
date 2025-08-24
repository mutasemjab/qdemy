<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Course;
use App\Models\QuestionOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:question-table', ['only' => ['index', 'show']]);
        $this->middleware('permission:question-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:question-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:question-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of questions.
     */
    public function index(Request $request)
    {
        $query = Question::with(['course', 'creator']);

        // Filter by course
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title_en', 'like', "%{$search}%")
                  ->orWhere('title_ar', 'like', "%{$search}%")
                  ->orWhere('question_en', 'like', "%{$search}%")
                  ->orWhere('question_ar', 'like', "%{$search}%");
            });
        }

        $questions = $query->paginate(15);
        $courses = Course::all();

        return view('admin.questions.index', compact('questions', 'courses'));
    }

    /**
     * Show the form for creating a new question.
     */
    public function create()
    {
        $courses = Course::all();
        return view('admin.questions.create', compact('courses'));
    }

    /**
     * Store a newly created question.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'question_en' => 'required|string',
            'question_ar' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false,essay',
            'grade' => 'required|numeric|min:0.1|max:999.99',
            'course_id' => 'required|exists:courses,id',
            'explanation_en' => 'nullable|string',
            'explanation_ar' => 'nullable|string',

            // Options validation
            'options.*.option_en' => 'required_if:type,multiple_choice|string',
            'options.*.option_ar' => 'required_if:type,multiple_choice|string',
            'options.*.is_correct' => 'sometimes|boolean',

            // True/False validation
            'true_false_answer' => 'required_if:type,true_false|boolean',
        ]);

        DB::beginTransaction();
        try {
            $question = Question::create([
                'title_en'    => $request->title_en,
                'title_ar'    => $request->title_ar,
                'question_en' => $request->question_en,
                'question_ar' => $request->question_ar,
                'type'        => $request->type,
                'grade'       => $request->grade,
                'course_id'   => $request->course_id,
                'explanation_en'   => $request->explanation_en,
                'explanation_ar'   => $request->explanation_ar,
                'created_by'       => auth('user')->user()?->id,
                'created_by_admin' => auth('admin')->user()?->id,
            ]);

            // Handle options based on question type
            if ($request->type === 'multiple_choice') {
                $this->createMultipleChoiceOptions($question, $request->options);
            } elseif ($request->type === 'true_false') {
                $this->createTrueFalseOptions($question, $request->true_false_answer);
            }

            DB::commit();
            return redirect()->route('questions.index')
                ->with('success', __('messages.question_created_successfully'));

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', __('messages.question_creation_failed'));
        }
    }

    /**
     * Display the specified question.
     */
    public function show(Question $question)
    {
        $question->load(['course', 'creator', 'options']);
        return view('admin.questions.show', compact('question'));
    }

    /**
     * Show the form for editing the question.
     */
    public function edit(Question $question)
    {
        $question->load('options');
        $courses = Course::all();
        return view('admin.questions.edit', compact('question', 'courses'));
    }

    /**
     * Update the specified question.
     */
    public function update(Request $request, Question $question)
    {
        $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'question_en' => 'required|string',
            'question_ar' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false,essay',
            'grade' => 'required|numeric|min:0.1|max:999.99',
            'course_id' => 'required|exists:courses,id',
            'explanation_en' => 'nullable|string',
            'explanation_ar' => 'nullable|string',

            // Options validation
            'options.*.option_en' => 'required_if:type,multiple_choice|string',
            'options.*.option_ar' => 'required_if:type,multiple_choice|string',
            'options.*.is_correct' => 'sometimes|boolean',

            // True/False validation
            'true_false_answer' => 'required_if:type,true_false|boolean',
        ]);

        DB::beginTransaction();
        try {
            $question->update([
                'title_en' => $request->title_en,
                'title_ar' => $request->title_ar,
                'question_en' => $request->question_en,
                'question_ar' => $request->question_ar,
                'type' => $request->type,
                'grade' => $request->grade,
                'course_id' => $request->course_id,
                'explanation_en' => $request->explanation_en,
                'explanation_ar' => $request->explanation_ar,
            ]);

            // Delete existing options
            $question->options()->delete();

            // Create new options based on type
            if ($request->type === 'multiple_choice') {
                $this->createMultipleChoiceOptions($question, $request->options);
            } elseif ($request->type === 'true_false') {
                $this->createTrueFalseOptions($question, $request->true_false_answer);
            }

            DB::commit();
            return redirect()->route('questions.index')
                ->with('success', __('messages.question_updated_successfully'));

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', __('messages.question_update_failed'));
        }
    }

    /**
     * Remove the specified question.
     */
    public function destroy(Question $question)
    {
        try {
            $question->delete();
            return redirect()->route('questions.index')
                ->with('success', __('messages.question_deleted_successfully'));
        } catch (\Exception $e) {
            return back()->with('error', __('messages.question_deletion_failed'));
        }
    }

    /**
     * Create multiple choice options
     */
    private function createMultipleChoiceOptions(Question $question, array $options)
    {
        foreach ($options as $index => $optionData) {
            QuestionOption::create([
                'option_en' => $optionData['option_en'],
                'option_ar' => $optionData['option_ar'],
                'is_correct' => isset($optionData['is_correct']) ? true : false,
                'order' => $index + 1,
                'question_id' => $question->id,
            ]);
        }
    }

    /**
     * Create true/false options
     */
    private function createTrueFalseOptions(Question $question, bool $correctAnswer)
    {
        // Create True option
        QuestionOption::create([
            'option_en' => 'True',
            'option_ar' => 'صحيح',
            'is_correct' => $correctAnswer === true,
            'order' => 1,
            'question_id' => $question->id,
        ]);

        // Create False option
        QuestionOption::create([
            'option_en' => 'False',
            'option_ar' => 'خطأ',
            'is_correct' => $correctAnswer === false,
            'order' => 2,
            'question_id' => $question->id,
        ]);
    }

    /**
     * Get questions by course (AJAX)
     */
    public function getByCourse(Course $course)
    {
        $questions = $course->questions()->with('options')->get();
        return response()->json($questions);
    }
}
