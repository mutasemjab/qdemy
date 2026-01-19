<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Course;
use App\Traits\ExamManagementTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamQuestionsController extends Controller
{
    use ExamManagementTrait;

    public function __construct()
    {
        $this->middleware('permission:exam-edit', ['only' => ['index', 'show', 'create', 'store', 'edit', 'update']]);
        $this->middleware('permission:exam-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of questions for the exam
     */
    public function index(Request $request, Exam $exam)
    {
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

        return view('admin.exams.questions.index', compact('exam', 'questions'));
    }

    /**
     * Show the form for creating a new question for this exam
     */
    public function create(Exam $exam)
    {
        return view('admin.exams.questions.create', compact('exam'));
    }

    /**
     * Store a newly created question and assign it to this exam
     */
    public function store(Request $request, Exam $exam)
    {
        // Set course_id to match exam's course if not provided
        if (!$request->filled('course_id') && $exam->course_id) {
            $request->merge(['course_id' => $exam->course_id]);
        }

        // Use the trait to create the question
        $response = $this->storeQuestion($request, true); // true = isAdmin
        
        // Handle redirect response (validation failed)
        if ($response instanceof \Illuminate\Http\RedirectResponse) {
            return $response;
        }
        
        // Handle JSON response
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

            return redirect()->route('exams.exam_questions.index', $exam)
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
     * Display the specified question
     */
    public function show(Exam $exam, Question $question)
    {
        // Ensure the question belongs to this exam
        if (!$exam->questions()->where('questions.id', $question->id)->exists()) {
            abort(404, __('messages.question_not_found_in_exam'));
        }

        $question->load(['course', 'options', 'creator']);
        
        // Get the question's details in this exam (order, grade)
        $examQuestion = $exam->questions()->where('questions.id', $question->id)->first();
        
        return view('admin.exams.questions.show', compact('exam', 'question', 'examQuestion'));
    }

    /**
     * Show the form for editing the specified question
     */
    public function edit(Exam $exam, Question $question)
    {
        // Ensure the question belongs to this exam
        if (!$exam->questions()->where('questions.id', $question->id)->exists()) {
            abort(404, __('messages.question_not_found_in_exam'));
        }

        $question->load('options');
        
        return view('admin.exams.questions.edit', compact('exam', 'question'));
    }

    /**
     * Update the specified question
     */
    public function update(Request $request, Exam $exam, Question $question)
    {
        // Ensure the question belongs to this exam
        if (!$exam->questions()->where('questions.id', $question->id)->exists()) {
            abort(404, __('messages.question_not_found_in_exam'));
        }

        // Set course_id to match exam's course if not provided
        if (!$request->filled('course_id') && $exam->course_id) {
            $request->merge(['course_id' => $exam->course_id]);
        }

        // Use the trait to update the question
        $response = $this->updateQuestion($request, $question, true); // true = isAdmin
        
        // Handle redirect response (validation failed)
        if ($response instanceof \Illuminate\Http\RedirectResponse) {
            return $response;
        }
        
        // Handle JSON response
        if ($request->expectsJson()) {
            return $response;
        }
        
        $data = $response->getData();
        if ($data->success) {
            // Update the total grade of the exam if the question grade changed
            $exam->update([
                'total_grade' => $exam->questions()->sum('exam_questions.grade')
            ]);

            return redirect()->route('exams.exam_questions.index', $exam)
                ->with('success', $data->message);
        }
        
        return redirect()->back()->withInput()->with('error', $data->message);
    }

    /**
     * Remove the specified question from the exam and delete it
     */
    public function destroy(Exam $exam, Question $question)
    {
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
            
            return redirect()->route('exams.exam_questions.index', $exam)
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
}