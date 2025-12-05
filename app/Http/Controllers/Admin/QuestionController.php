<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Course;
use App\Models\QuestionOption;
use App\Traits\ExamManagementTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    use ExamManagementTrait;

    public function __construct()
    {
        $this->middleware('permission:question-table', ['only' => ['index', 'show']]);
        $this->middleware('permission:question-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:question-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:question-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of questions
     */
    public function index(Request $request)
    {
        $query = Question::with(['course.subject', 'creator']);

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
        $courses = Course::with('subject')->get();

        return view('admin.questions.index', compact('questions', 'courses'));
    }

    /**
     * Show the form for creating a new question
     */
    public function create()
    {
        $courses = Course::with('subject')->get();
        return view('admin.questions.create', compact('courses'));
    }

    /**
     * Store a newly created question - USING TRAIT
     */
    public function store(Request $request)
    {
        $response = $this->storeQuestion($request, true); // true = isAdmin
        
        if ($request->expectsJson()) {
            return $response;
        }
        
        $data = $response->getData();
        if ($data->success) {
            return redirect()->route('questions.index')
                ->with('success', $data->message);
        }
        
        return redirect()->back()->withInput()->with('error', $data->message);
    }

    /**
     * Display the specified question
     */
    public function show(Question $question)
    {
        $question->load(['course.subject', 'creator', 'options', 'exams']);
        return view('admin.questions.show', compact('question'));
    }

    /**
     * Show the form for editing the question
     */
    public function edit(Question $question)
    {
        $question->load('options');
        $courses = Course::with('subject')->get();
        return view('admin.questions.edit', compact('question', 'courses'));
    }

    /**
     * Update the specified question - USING TRAIT
     */
    public function update(Request $request, Question $question)
    {
        $response = $this->updateQuestion($request, $question, true); // true = isAdmin
        
        if ($request->expectsJson()) {
            return $response;
        }
        
        $data = $response->getData();
        if ($data->success) {
            return redirect()->route('questions.index')
                ->with('success', $data->message);
        }
        
        return redirect()->back()->withInput()->with('error', $data->message);
    }

    /**
     * Remove the specified question - USING TRAIT
     */
    public function destroy(Question $question)
    {
        $response = $this->deleteQuestion($question, true); // true = isAdmin
        
        if (request()->expectsJson()) {
            return $response;
        }
        
        $data = $response->getData();
        if ($data->success) {
            return redirect()->route('admin.questions.index')
                ->with('success', $data->message);
        }
        
        return redirect()->back()->with('error', $data->message);
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
