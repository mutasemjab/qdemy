<?php

namespace App\Traits;

use App\Models\Exam;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Course;
use App\Models\Subject;
use App\Models\CourseSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

trait ExamManagementTrait
{
    /**
     * Store a new exam
     */
    public function storeExam(Request $request, $isAdmin = false)
    {
        // Validate the request
        $validator = $this->validateExamRequest($request, $isAdmin);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => __('messages.validation_error'),
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        
        try {
            $examData = $this->prepareExamData($request, $isAdmin);
            
            $exam = Exam::create($examData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('messages.exam_created_successfully'),
                'data' => $exam->load(['course', 'subject', 'section', 'creator'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => __('messages.exam_creation_failed'),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing exam
     */
    public function updateExam(Request $request, Exam $exam, $isAdmin = false)
    {
        // Check permissions
        if (!$this->canManageExam($exam, $isAdmin)) {
            return response()->json([
                'success' => false,
                'message' => __('messages.unauthorized_action')
            ], 403);
        }

        // Validate the request
        $validator = $this->validateExamRequest($request, $isAdmin, $exam->id);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => __('messages.validation_error'),
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        
        try {
            $examData = $this->prepareExamData($request, $isAdmin, $exam);
            
            $exam->update($examData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('messages.exam_updated_successfully'),
                'data' => $exam->fresh()->load(['course', 'subject', 'section', 'creator'])
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => __('messages.exam_update_failed'),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new question
     */
    public function storeQuestion(Request $request, $isAdmin = false)
    {
        // Validate the request
        $validator = $this->validateQuestionRequest($request, $isAdmin);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => __('messages.validation_error'),
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        
        try {
            $questionData = $this->prepareQuestionData($request, $isAdmin);
            
            $question = Question::create($questionData);

            // Handle options based on question type
            if ($request->type === 'multiple_choice') {
                $this->createMultipleChoiceOptions($question, $request->options);
            } elseif ($request->type === 'true_false') {
                $this->createTrueFalseOptions($question, $request->true_false_answer);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('messages.question_created_successfully'),
                'data' => $question->load(['course', 'options', 'creator'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => __('messages.question_creation_failed'),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing question
     */
    public function updateQuestion(Request $request, Question $question, $isAdmin = false)
    {
        // Check permissions
        if (!$this->canManageQuestion($question, $isAdmin)) {
            return response()->json([
                'success' => false,
                'message' => __('messages.unauthorized_action')
            ], 403);
        }

        // Validate the request
        $validator = $this->validateQuestionRequest($request, $isAdmin, $question->id);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => __('messages.validation_error'),
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        
        try {
            $questionData = $this->prepareQuestionData($request, $isAdmin, $question);
            
            $question->update($questionData);

            // Delete existing options and create new ones
            $question->options()->delete();

            // Handle options based on question type
            if ($request->type === 'multiple_choice') {
                $this->createMultipleChoiceOptions($question, $request->options);
            } elseif ($request->type === 'true_false') {
                $this->createTrueFalseOptions($question, $request->true_false_answer);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('messages.question_updated_successfully'),
                'data' => $question->fresh()->load(['course', 'options', 'creator'])
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => __('messages.question_update_failed'),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an exam
     */
    public function deleteExam(Exam $exam, $isAdmin = false)
    {
        if (!$this->canManageExam($exam, $isAdmin)) {
            return response()->json([
                'success' => false,
                'message' => __('messages.unauthorized_action')
            ], 403);
        }

        // Check if exam has attempts
        if ($exam->attempts()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => __('messages.exam_has_attempts_cannot_delete')
            ], 422);
        }

        DB::beginTransaction();
        
        try {
            // Delete exam (cascade will handle related records)
            $exam->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('messages.exam_deleted_successfully')
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => __('messages.exam_deletion_failed'),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a question
     */
    public function deleteQuestion(Question $question, $isAdmin = false)
    {
        if (!$this->canManageQuestion($question, $isAdmin)) {
            return response()->json([
                'success' => false,
                'message' => __('messages.unauthorized_action')
            ], 403);
        }

        // Check if question is used in any exams
        if ($question->exams()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => __('messages.question_used_in_exams_cannot_delete')
            ], 422);
        }

        DB::beginTransaction();
        
        try {
            // Delete question (cascade will handle options)
            $question->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('messages.question_deleted_successfully')
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => __('messages.question_deletion_failed'),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate exam request
     */
    protected function validateExamRequest(Request $request, $isAdmin = false, $examId = null)
    {
        // Debug: Log the incoming request data
        \Log::info('Exam validation request data:', $request->all());
        
        $rules = [
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'subject_id' => 'required|exists:subjects,id',
            'course_id' => 'nullable|exists:courses,id',
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
        ];

        $validator = Validator::make($request->all(), $rules);
        
        // Debug: Log validation errors if any
        if ($validator->fails()) {
            \Log::error('Exam validation failed:', [
                'errors' => $validator->errors()->toArray(),
                'failed_rules' => $validator->failed()
            ]);
        }
        
        return $validator;
    }

    /**
     * Validate question request
     */
    protected function validateQuestionRequest(Request $request, $isAdmin = false, $questionId = null)
    {
        $rules = [
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'question_en' => 'required|string',
            'question_ar' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false,essay',
            'grade' => 'required|numeric|min:0.1|max:999.99',
            'course_id' => 'nullable|exists:courses,id',
            'explanation_en' => 'nullable|string',
            'explanation_ar' => 'nullable|string',
        ];

        // Type-specific validation
        if ($request->type === 'multiple_choice') {
            $rules['options'] = 'required|array|min:2|max:6';
            $rules['options.*.option_en'] = 'required|string';
            $rules['options.*.option_ar'] = 'required|string';
            $rules['options.*.is_correct'] = 'sometimes|boolean';
        } elseif ($request->type === 'true_false') {
            $rules['true_false_answer'] = 'required|boolean';
        }

        return Validator::make($request->all(), $rules);
    }

    /**
     * Prepare exam data for storage
     */
    protected function prepareExamData(Request $request, $isAdmin = false, $exam = null)
    {
        $data = $request->only([
            'title_en', 'title_ar', 'description_en', 'description_ar',
            'subject_id', 'course_id', 'section_id', 'duration_minutes',
            'attempts_allowed', 'passing_grade', 'start_date', 'end_date',
            'shuffle_questions', 'shuffle_options', 'show_results_immediately',
            'is_active'
        ]);

        // Handle boolean fields properly
        $data['shuffle_questions'] = $request->has('shuffle_questions') ? true : false;
        $data['shuffle_options'] = $request->has('shuffle_options') ? true : false;
        $data['show_results_immediately'] = $request->has('show_results_immediately') ? true : false;
        $data['is_active'] = $request->has('is_active') ? true : false;

        // Set creator based on user type
        if ($isAdmin) {
            $data['created_by_admin'] = auth('admin')->user()?->id;
            $data['created_by'] = null;
        } else {
            $data['created_by'] = auth()->user()->id;
            $data['created_by_admin'] = null;
        }

        return $data;
    }

    /**
     * Prepare question data for storage
     */
    protected function prepareQuestionData(Request $request, $isAdmin = false, $question = null)
    {
        $data = $request->only([
            'title_en', 'title_ar', 'question_en', 'question_ar',
            'type', 'grade', 'course_id', 'explanation_en', 'explanation_ar'
        ]);

        // Set creator based on user type
        if ($isAdmin) {
            $data['created_by_admin'] = auth('admin')->user()?->id;
            $data['created_by'] = null;
        } else {
            $data['created_by'] = auth()->user()->id;
            $data['created_by_admin'] = null;
        }

        return $data;
    }

    /**
     * Create multiple choice options
     */
    protected function createMultipleChoiceOptions(Question $question, array $options)
    {
        $hasCorrectAnswer = false;

        foreach ($options as $index => $optionData) {
            $isCorrect = isset($optionData['is_correct']) && $optionData['is_correct'];
            
            if ($isCorrect) {
                $hasCorrectAnswer = true;
            }

            QuestionOption::create([
                'option_en' => $optionData['option_en'],
                'option_ar' => $optionData['option_ar'],
                'is_correct' => $isCorrect,
                'order' => $index + 1,
                'question_id' => $question->id,
            ]);
        }

        // Ensure at least one correct answer for multiple choice
        if (!$hasCorrectAnswer && count($options) > 0) {
            $question->options()->first()->update(['is_correct' => true]);
        }
    }

    /**
     * Create true/false options
     */
    protected function createTrueFalseOptions(Question $question, bool $correctAnswer)
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
     * Check if user can manage exam
     */
    protected function canManageExam(Exam $exam, $isAdmin = false)
    {
        if ($isAdmin) {
            // Admin can manage any exam
            if (auth()->guard('admin')->check()) {
                return true;
            }
        } else {
            // Teacher can only manage their own exams
            $user = auth()->user();
            if ($user && $user->role_name === 'teacher') {
                return $exam->created_by === $user->id;
            }
        }
        
        return false;
    }

    /**
     * Check if user can manage question
     */
    protected function canManageQuestion(Question $question, $isAdmin = false)
    {
        if ($isAdmin) {
            // Admin can manage any question
            if (auth()->guard('admin')->check()) {
                return true;
            }
        } else {
            // Teacher can only manage their own questions
            $user = auth()->user();
            if ($user && $user->role_name === 'teacher') {
                return $question->created_by === $user->id;
            }
        }
        
        return false;
    }

    /**
     * Add questions to exam
     */
    // Update this method in your ExamManagementTrait
    public function addQuestionsToExam(Request $request, Exam $exam, $isAdmin = false)
    {
        // Check permissions
        if (!$this->canManageExam($exam, $isAdmin)) {
            return response()->json([
                'success' => false,
                'message' => __('messages.unauthorized_action')
            ], 403);
        }

        // Handle both JSON and form data formats
        $questionsData = [];
        
        if ($request->has('questions') && is_string($request->questions)) {
            // JSON format from AJAX
            $questionsData = json_decode($request->questions, true);
        } elseif ($request->has('questions') && is_array($request->questions)) {
            // Array format from form
            $questionsData = $request->questions;
        } elseif ($request->has('selected_questions')) {
            // Handle the checkbox format
            $selectedQuestions = $request->selected_questions;
            foreach ($selectedQuestions as $questionId) {
                $grade = $request->input("questions.{$questionId}.grade", 1.00);
                $questionsData[$questionId] = [
                    'id' => $questionId,
                    'grade' => $grade
                ];
            }
        }

        if (empty($questionsData)) {
            return response()->json([
                'success' => false,
                'message' => __('messages.no_questions_selected')
            ], 422);
        }

        // Validate the questions data
        $questionsArray = [];
        foreach ($questionsData as $questionId => $data) {
            if (is_array($data)) {
                $questionsArray[] = [
                    'id' => $data['id'] ?? $questionId,
                    'grade' => $data['grade'] ?? 1.00
                ];
            } else {
                // Handle simple array format
                $questionsArray[] = [
                    'id' => $questionId,
                    'grade' => 1.00
                ];
            }
        }

        $validator = Validator::make(['questions' => $questionsArray], [
            'questions' => 'required|array',
            'questions.*.id' => 'required|exists:questions,id',
            'questions.*.grade' => 'required|numeric|min:0.1|max:999.99',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => __('messages.validation_error'),
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        
        try {
            $maxOrder = $exam->questions()->max('exam_questions.order') ?? 0;
            $addedCount = 0;

            foreach ($questionsArray as $questionData) {
                // Check if question already exists in exam
                if ($exam->questions()->where('questions.id', $questionData['id'])->exists()) {
                    continue;
                }

                // Verify the question exists and can be added
                $question = Question::find($questionData['id']);
                if (!$question) {
                    continue;
                }

                $exam->questions()->attach($questionData['id'], [
                    'order' => ++$maxOrder,
                    'grade' => $questionData['grade'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $addedCount++;
            }

            if ($addedCount === 0) {
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => __('messages.no_new_questions_added')
                ], 422);
            }

            // Update total grade
            $exam->update([
                'total_grade' => $exam->questions()->sum('exam_questions.grade')
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('messages.questions_added_successfully', ['count' => $addedCount]),
                'data' => $exam->fresh(['questions'])
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => __('messages.questions_addition_failed'),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove question from exam
     */
    public function removeQuestionFromExam(Exam $exam, Question $question, $isAdmin = false)
    {
        // Check permissions
        if (!$this->canManageExam($exam, $isAdmin)) {
            return response()->json([
                'success' => false,
                'message' => __('messages.unauthorized_action')
            ], 403);
        }

        DB::beginTransaction();
        
        try {
            $exam->questions()->detach($question->id);

            // Update total grade
            $exam->update([
                'total_grade' => $exam->questions()->sum('exam_questions.grade')
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('messages.question_removed_successfully')
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => __('messages.question_removal_failed'),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update exam questions order and grades
     */
    public function updateExamQuestions(Request $request, Exam $exam, $isAdmin = false)
    {
        // Check permissions
        if (!$this->canManageExam($exam, $isAdmin)) {
            return response()->json([
                'success' => false,
                'message' => __('messages.unauthorized_action')
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'questions' => 'required|array',
            'questions.*.id' => 'required|exists:questions,id',
            'questions.*.order' => 'required|integer|min:1',
            'questions.*.grade' => 'required|numeric|min:0.1|max:999.99',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => __('messages.validation_error'),
                'errors' => $validator->errors()
            ], 422);
        }

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
                'total_grade' => $exam->questions()->sum('exam_questions.grade')
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('messages.questions_updated_successfully'),
                'data' => $exam->fresh(['questions'])
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => __('messages.questions_update_failed'),
                'error' => $e->getMessage()
            ], 500);
        }
    }
}