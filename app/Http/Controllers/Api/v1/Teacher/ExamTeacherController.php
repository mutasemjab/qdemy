<?php

namespace App\Http\Controllers\Api\v1\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Question;
use App\Models\ExamAttempt;
use App\Traits\ExamManagementTrait;
use App\Traits\Responses;
use Illuminate\Http\Request;

class ExamTeacherController extends Controller
{
    use ExamManagementTrait, Responses;

    /**
     * Get teacher's exams list
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            $query = Exam::with(['course:id,title_en,title_ar', 'subject:id,name_ar,name_en'])
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

            $exams = $query->orderBy('created_at', 'desc')->paginate(15);

            $examsData = $exams->map(function ($exam) {
                return [
                    'id' => $exam->id,
                    'title_en' => $exam->title_en,
                    'title_ar' => $exam->title_ar,
                    'description_en' => $exam->description_en,
                    'description_ar' => $exam->description_ar,
                    'duration_minutes' => $exam->duration_minutes,
                    'total_grade' => $exam->total_grade,
                    'passing_grade' => $exam->passing_grade,
                    'attempts_allowed' => $exam->attempts_allowed,
                    'start_date' => $exam->start_date,
                    'end_date' => $exam->end_date,
                    'is_active' => $exam->is_active,
                    'course' => $exam->course ? [
                        'id' => $exam->course->id,
                        'title_en' => $exam->course->title_en,
                        'title_ar' => $exam->course->title_ar,
                    ] : null,
                    'subject' => $exam->subject ? [
                        'id' => $exam->subject->id,
                        'name_ar' => $exam->subject->name_ar,
                        'name_en' => $exam->subject->name_en,
                    ] : null,
                    'questions_count' => $exam->questions()->count(),
                    'attempts_count' => $exam->attempts()->count(),
                    'created_at' => $exam->created_at,
                    'updated_at' => $exam->updated_at
                ];
            });

            return $this->success_response('Exams retrieved successfully', [
                'exams' => $examsData,
                'pagination' => [
                    'current_page' => $exams->currentPage(),
                    'last_page' => $exams->lastPage(),
                    'per_page' => $exams->perPage(),
                    'total' => $exams->total()
                ]
            ]);

        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve exams: ' . $e->getMessage(), null);
        }
    }

    /**
     * Get exam details
     */
    public function show(Request $request, $examId)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            $exam = Exam::with([
                'course:id,title_en,title_ar',
                'subject:id,name_ar,name_en',
                'section:id,title_en,title_ar',
                'questions.options'
            ])
            ->where('created_by', $user->id)
            ->findOrFail($examId);

            $examData = [
                'id' => $exam->id,
                'title_en' => $exam->title_en,
                'title_ar' => $exam->title_ar,
                'description_en' => $exam->description_en,
                'description_ar' => $exam->description_ar,
                'duration_minutes' => $exam->duration_minutes,
                'total_grade' => $exam->total_grade,
                'passing_grade' => $exam->passing_grade,
                'attempts_allowed' => $exam->attempts_allowed,
                'start_date' => $exam->start_date,
                'end_date' => $exam->end_date,
                'shuffle_questions' => $exam->shuffle_questions,
                'shuffle_options' => $exam->shuffle_options,
                'show_results_immediately' => $exam->show_results_immediately,
                'is_active' => $exam->is_active,
                'course' => $exam->course ? [
                    'id' => $exam->course->id,
                    'title_en' => $exam->course->title_en,
                    'title_ar' => $exam->course->title_ar,
                ] : null,
                'subject' => $exam->subject ? [
                    'id' => $exam->subject->id,
                    'name_ar' => $exam->subject->name_ar,
                    'name_en' => $exam->subject->name_en,
                ] : null,
                'section' => $exam->section ? [
                    'id' => $exam->section->id,
                    'title_en' => $exam->section->title_en,
                    'title_ar' => $exam->section->title_ar,
                ] : null,
                'questions' => $exam->questions->map(function ($question) {
                    return [
                        'id' => $question->id,
                        'title_en' => $question->title_en,
                        'title_ar' => $question->title_ar,
                        'question_en' => $question->question_en,
                        'question_ar' => $question->question_ar,
                        'type' => $question->type,
                        'grade' => $question->pivot->grade,
                        'order' => $question->pivot->order,
                        'options_count' => $question->options->count(),
                        'created_at' => $question->created_at
                    ];
                }),
                'statistics' => [
                    'total_questions' => $exam->questions->count(),
                    'total_attempts' => $exam->attempts()->count(),
                    'completed_attempts' => $exam->attempts()->where('status', 'completed')->count(),
                    'average_score' => round($exam->attempts()->where('status', 'completed')->avg('percentage') ?? 0, 2),
                ],
                'created_at' => $exam->created_at,
                'updated_at' => $exam->updated_at
            ];

            return $this->success_response('Exam retrieved successfully', $examData);

        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve exam: ' . $e->getMessage(), null);
        }
    }


    /**
     * Create a new exam
     */
    public function store(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            // If course_id is provided, verify teacher owns the course
            if ($request->filled('course_id')) {
                $course = Course::where('id', $request->course_id)
                    ->where('teacher_id', $user->id)
                    ->first();
                
                if (!$course) {
                    return $this->error_response('You can only create exams for your own courses.', null);
                }
            }

            // Use the trait method (isAdmin = false)
            return $this->storeExam($request, false);

        } catch (\Exception $e) {
            return $this->error_response('Failed to create exam: ' . $e->getMessage(), null);
        }
    }

    /**
     * Update an exam
     */
    public function update(Request $request, $examId)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            $exam = Exam::where('created_by', $user->id)->findOrFail($examId);

            // If updating course_id, verify teacher owns the new course
            if ($request->filled('course_id') && $request->course_id != $exam->course_id) {
                $course = Course::where('id', $request->course_id)
                    ->where('teacher_id', $user->id)
                    ->first();
                
                if (!$course) {
                    return $this->error_response('You can only assign your own courses to exams.', null);
                }
            }

            // Use the trait method (isAdmin = false)
            return $this->updateExam($request, $exam, false);

        } catch (\Exception $e) {
            return $this->error_response('Failed to update exam: ' . $e->getMessage(), null);
        }
    }

    /**
     * Delete an exam
     */
    public function destroy(Request $request, $examId)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            $exam = Exam::where('created_by', $user->id)->findOrFail($examId);

            // Use the trait method (isAdmin = false)
            return $this->deleteExam($exam, false);

        } catch (\Exception $e) {
            return $this->error_response('Failed to delete exam: ' . $e->getMessage(), null);
        }
    }

    /**
     * Get exam results/statistics
     */
    public function getResults(Request $request, $examId)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            $exam = Exam::where('created_by', $user->id)->findOrFail($examId);

            $attempts = $exam->attempts()
                ->with(['user:id,name,email,photo'])
                ->where('status', 'completed')
                ->orderBy('submitted_at', 'desc')
                ->paginate(20);

            $stats = [
                'total_attempts' => $exam->attempts()->where('status', 'completed')->count(),
                'passed_attempts' => $exam->attempts()->where('is_passed', true)->count(),
                'failed_attempts' => $exam->attempts()->where('is_passed', false)->count(),
                'average_score' => round($exam->attempts()->where('status', 'completed')->avg('percentage') ?? 0, 2),
                'highest_score' => $exam->attempts()->where('status', 'completed')->max('percentage') ?? 0,
                'lowest_score' => $exam->attempts()->where('status', 'completed')->min('percentage') ?? 0,
                'pass_rate' => 0
            ];

            if ($stats['total_attempts'] > 0) {
                $stats['pass_rate'] = round(($stats['passed_attempts'] / $stats['total_attempts']) * 100, 2);
            }

            $attemptsData = $attempts->map(function ($attempt) {
                return [
                    'id' => $attempt->id,
                    'student' => [
                        'id' => $attempt->user->id,
                        'name' => $attempt->user->name,
                        'email' => $attempt->user->email,
                        'photo' => $attempt->user->photo ? asset('assets/admin/uploads/' . $attempt->user->photo) : null,
                    ],
                    'score' => $attempt->score,
                    'total_score' => $attempt->total_score,
                    'percentage' => $attempt->percentage,
                    'is_passed' => $attempt->is_passed,
                    'duration_minutes' => $attempt->duration_minutes,
                    'started_at' => $attempt->started_at,
                    'submitted_at' => $attempt->submitted_at,
                    'attempt_number' => $attempt->attempt_number,
                ];
            });

            return $this->success_response('Exam results retrieved successfully', [
                'exam' => [
                    'id' => $exam->id,
                    'title_en' => $exam->title_en,
                    'title_ar' => $exam->title_ar,
                    'total_grade' => $exam->total_grade,
                    'passing_grade' => $exam->passing_grade,
                ],
                'statistics' => $stats,
                'attempts' => $attemptsData,
                'pagination' => [
                    'current_page' => $attempts->currentPage(),
                    'last_page' => $attempts->lastPage(),
                    'per_page' => $attempts->perPage(),
                    'total' => $attempts->total()
                ]
            ]);

        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve exam results: ' . $e->getMessage(), null);
        }
    }

    /**
     * View specific attempt details
     */
    public function viewAttempt(Request $request, $examId, $attemptId)
    {
        try {
            $user = $request->user();

            if ($user->role_name !== 'teacher') {
                return $this->error_response('Access denied. Teachers only.', null);
            }

            $exam = Exam::where('created_by', $user->id)->findOrFail($examId);
            $attempt = ExamAttempt::where('exam_id', $exam->id)
                ->with(['user:id,name,email', 'answers.question.options'])
                ->findOrFail($attemptId);

            $attemptData = [
                'id' => $attempt->id,
                'student' => [
                    'id' => $attempt->user->id,
                    'name' => $attempt->user->name,
                    'email' => $attempt->user->email,
                ],
                'score' => $attempt->score,
                'total_score' => $attempt->total_score,
                'percentage' => $attempt->percentage,
                'is_passed' => $attempt->is_passed,
                'duration_minutes' => $attempt->duration_minutes,
                'started_at' => $attempt->started_at,
                'submitted_at' => $attempt->submitted_at,
                'attempt_number' => $attempt->attempt_number,
                'answers' => $attempt->answers->map(function ($answer) {
                    return [
                        'question' => [
                            'id' => $answer->question->id,
                            'title_en' => $answer->question->title_en,
                            'title_ar' => $answer->question->title_ar,
                            'question_en' => $answer->question->question_en,
                            'question_ar' => $answer->question->question_ar,
                            'type' => $answer->question->type,
                            'options' => $answer->question->options->map(function ($option) {
                                return [
                                    'id' => $option->id,
                                    'option_en' => $option->option_en,
                                    'option_ar' => $option->option_ar,
                                    'is_correct' => $option->is_correct,
                                ];
                            })
                        ],
                        'selected_option_id' => $answer->selected_option_id,
                        'answer_text' => $answer->answer_text,
                        'is_correct' => $answer->is_correct,
                        'points_earned' => $answer->points_earned,
                        'points_possible' => $answer->points_possible,
                    ];
                })
            ];

            return $this->success_response('Attempt details retrieved successfully', [
                'exam' => [
                    'id' => $exam->id,
                    'title_en' => $exam->title_en,
                    'title_ar' => $exam->title_ar,
                ],
                'attempt' => $attemptData
            ]);

        } catch (\Exception $e) {
            return $this->error_response('Failed to retrieve attempt details: ' . $e->getMessage(), null);
        }
    }

  

  
}