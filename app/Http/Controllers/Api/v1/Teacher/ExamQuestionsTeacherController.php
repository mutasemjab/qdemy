<?php

namespace App\Http\Controllers\Api\v1\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Course;
use App\Traits\ExamManagementTrait;
use App\Traits\Responses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamQuestionsTeacherController extends Controller
{
    use ExamManagementTrait, Responses;

    /**
     * Get questions for an exam
     */
    public function index(Request $request, $examId)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            $exam = Exam::where('created_by', $user->id)->findOrFail($examId);

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
            $questions = $query->orderBy('exam_questions.order')->get();

            $questionsData = $questions->map(function ($question) {
                return [
                    'id' => $question->id,
                    'title_en' => $question->title_en,
                    'title_ar' => $question->title_ar,
                    'question_en' => $question->question_en,
                    'question_ar' => $question->question_ar,
                    'type' => $question->type,
                    'grade' => $question->pivot->grade,
                    'order' => $question->pivot->order,
                    'explanation_en' => $question->explanation_en,
                    'explanation_ar' => $question->explanation_ar,
                    'options' => $question->options->map(function ($option) {
                        return [
                            'id' => $option->id,
                            'option_en' => $option->option_en,
                            'option_ar' => $option->option_ar,
                            'is_correct' => $option->is_correct,
                            'order' => $option->order,
                        ];
                    })->sortBy('order')->values(),
                    'created_at' => $question->created_at
                ];
            });

            return $this->success_response('Exam questions retrieved successfully', [
                'exam' => [
                    'id' => $exam->id,
                    'title_en' => $exam->title_en,
                    'title_ar' => $exam->title_ar,
                    'total_grade' => $exam->total_grade,
                ],
                'questions' => $questionsData,
                'questions_count' => $questionsData->count()
            ]);

        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve exam questions: ' . $e->getMessage(), null);
        }
    }

    /**
     * Get specific question details
     */
    public function show(Request $request, $examId, $questionId)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            $exam = Exam::where('created_by', $user->id)->findOrFail($examId);

            // Ensure the question belongs to this exam
            $question = $exam->questions()
                ->with(['options', 'course'])
                ->where('questions.id', $questionId)
                ->firstOrFail();

            $questionData = [
                'id' => $question->id,
                'title_en' => $question->title_en,
                'title_ar' => $question->title_ar,
                'question_en' => $question->question_en,
                'question_ar' => $question->question_ar,
                'type' => $question->type,
                'grade' => $question->pivot->grade,
                'order' => $question->pivot->order,
                'explanation_en' => $question->explanation_en,
                'explanation_ar' => $question->explanation_ar,
                'course' => $question->course ? [
                    'id' => $question->course->id,
                    'title_en' => $question->course->title_en,
                    'title_ar' => $question->course->title_ar,
                ] : null,
                'options' => $question->options->map(function ($option) {
                    return [
                        'id' => $option->id,
                        'option_en' => $option->option_en,
                        'option_ar' => $option->option_ar,
                        'is_correct' => $option->is_correct,
                        'order' => $option->order,
                    ];
                })->sortBy('order')->values(),
                'created_at' => $question->created_at,
                'updated_at' => $question->updated_at
            ];

            return $this->success_response('Question retrieved successfully', [
                'exam' => [
                    'id' => $exam->id,
                    'title_en' => $exam->title_en,
                    'title_ar' => $exam->title_ar,
                ],
                'question' => $questionData
            ]);

        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve question: ' . $e->getMessage(), null);
        }
    }

    /**
     * Create and add a new question to the exam
     */
    public function store(Request $request, $examId)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            $exam = Exam::where('created_by', $user->id)->findOrFail($examId);

            // Set course_id to match exam's course if not provided
            if (!$request->filled('course_id') && $exam->course_id) {
                $request->merge(['course_id' => $exam->course_id]);
            }

            // Use the trait to create the question
            $response = $this->storeQuestion($request, false); // false = not admin
            
            $data = json_decode($response->getContent(), true);
            
            if (!$data['success']) {
                return $response;
            }
            
            $question = Question::find($data['data']['id']);
            
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

                return $this->success_response('Question created and added to exam successfully', [
                    'question' => $question->load(['course', 'options']),
                    'exam' => $exam->fresh(['questions']),
                    'exam_total_grade' => $exam->total_grade
                ]);

            } catch (\Exception $e) {
                DB::rollback();
                
                // If adding to exam fails, delete the created question
                $question->delete();
                
                return $this->error_response('Failed to add question to exam: ' . $e->getMessage(), null);
            }

        } catch (\Exception $e) {
            return $this->error_response('Failed to create question: ' . $e->getMessage(), null);
        }
    }

    /**
     * Update a question in the exam
     */
    public function update(Request $request, $examId, $questionId)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            $exam = Exam::where('created_by', $user->id)->findOrFail($examId);

            // Ensure the question belongs to this exam and was created by this teacher
            $question = $exam->questions()
                ->where('questions.id', $questionId)
                ->where('questions.created_by', $user->id)
                ->firstOrFail();

            // Set course_id to match exam's course if not provided
            if (!$request->filled('course_id') && $exam->course_id) {
                $request->merge(['course_id' => $exam->course_id]);
            }

            // Use the trait to update the question
            $response = $this->updateQuestion($request, $question, false); // false = not admin
            
            $data = json_decode($response->getContent(), true);
            
            if ($data['success']) {
                // Update the total grade of the exam if the question grade changed
                $exam->update([
                    'total_grade' => $exam->questions()->sum('exam_questions.grade')
                ]);

                return $this->success_response('Question updated successfully', [
                    'question' => $question->fresh(['course', 'options']),
                    'exam_total_grade' => $exam->total_grade
                ]);
            }
            
            return $response;

        } catch (\Exception $e) {
            return $this->error_response('Failed to update question: ' . $e->getMessage(), null);
        }
    }

 


    /**
     * Remove question from exam (without deleting the question)
     */
    public function removeQuestion(Request $request, $examId, $questionId)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            $exam = Exam::where('created_by', $user->id)->findOrFail($examId);
            $question = Question::findOrFail($questionId);

            // Use the trait method
            return $this->removeQuestionFromExam($exam, $question, false); // false = not admin

        } catch (\Exception $e) {
            return $this->error_response('Failed to remove question: ' . $e->getMessage(), null);
        }
    }

    /**
     * Update questions order and grades
     */
    public function updateQuestions(Request $request, $examId)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            $exam = Exam::where('created_by', $user->id)->findOrFail($examId);

            // Use the trait method
            return $this->updateExamQuestions($request, $exam, false); // false = not admin

        } catch (\Exception $e) {
            return $this->error_response('Failed to update questions: ' . $e->getMessage(), null);
        }
    }


}