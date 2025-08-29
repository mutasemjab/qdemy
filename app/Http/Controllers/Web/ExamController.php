<?php

namespace  App\Http\Controllers\Web;

use App\Models\Exam;
use App\Models\Course;
use App\Models\Question;
use App\Models\ExamAnswer;
use App\Models\ExamAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ExamController extends Controller
{
    public function e_exam(Request $request)
    {
        $programmsGrades             = CategoryRepository()->getProgrammsGrades();
        $gradesSemesters             = CategoryRepository()->getGradesSemesters();
        // $query                       = Exam::Query()->where('is_active',1);
        // $exams                       = $query->paginate(PGN);
        $query = Exam::query()
            ->where('is_active', 1)
            ->where(function($q) {
                $now = now();
                $q->where(function($q) use ($now) {
                    $q->whereNull('start_date')
                    ->orWhere('start_date', '<=', $now);
                })
                ->where(function($q) use ($now) {
                    $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', $now);
                });
            })
            ->where('course_id',null);

        $exams = $query->paginate(PGN);

        return view('web.exam.e-exam',[
            'exams'           => $exams,
            'programmsGrades' => $programmsGrades,
            'gradesSemesters' => $gradesSemesters,
        ]);
    }

    public function exam(Exam $exam, $slug = null, ExamAttempt $attempt = null)
    {
        $user = auth_student();

        // Check if exam is active and within date range
        if (!$exam->is_available()) {
            return redirect()->route('e-exam')->with('error', 'الامتحان غير متاح حاليا');
        }

        $_questions = $exam->questions();

        $attempts         = $exam->user_attempts();
        $result           = $exam->result_attempt($user->id);
        $current_attempts = $exam->current_user_attempts();
        $last_attempts    = $attempts->where('status', '!=', 'abandoned');
        $can_add_attempt  = $exam->can_add_attempt($user->id);

        // Get current attempt or create one if needed
        $current_attempt = $attempt ?? $exam->current_user_attempt();

        // Check time limit
        if ($current_attempt && $exam->duration_minutes) {
            $elapsed_minutes = $current_attempt->started_at->diffInMinutes(now());
            if ($elapsed_minutes >= $exam->duration_minutes) {
                $this->auto_submit_exam($current_attempt);
                return redirect()->route('exam', ['exam' => $exam->id, 'slug' => $exam->slug])
                    ->with('error', 'تم انتهاء الوقت المحدد وتم تسليم الامتحان تلقائيا');
            }
        }

        $questions = null;
        $question = null;
        $question_nm = $_GET['page'] ?? 1;

        if ($current_attempt) {
            // Get questions in the order stored for this attempt
            $question_order = $current_attempt->question_order;
            if ($question_order) {
                $questions = Question::whereIn('id', $question_order)
                    ->orderByRaw('FIELD(id, ' . implode(',', $question_order) . ')')
                    ->paginate(1);
            } else {
                $questions = $_questions->paginate(1);
            }
            $question = $questions?->first();
        }

        return view('web.exam.exam',[
            'exam'            => $exam,
            'questions'       => $questions,
            'question'        => $question,
            'attempts'        => $attempts,
            'result'          => $result,
            'current_attempts'=> $current_attempts,
            'current_attempt' => $current_attempt,
            'last_attempts'   => $last_attempts,
            'can_add_attempt' => $can_add_attempt,
            'question_nm'     => $question_nm,
            '_questions'      => $_questions,
        ]);
    }

    public function start_exam(Exam $exam)
    {
        $user = auth_student();

        // Check if user can start new attempt
        if (!$exam->can_add_attempt($user->id)) {
            return redirect()->route('exam', ['exam' => $exam->id, 'slug' => $exam->slug])
                ->with('error', 'لقد استنفدت عدد المحاولات المسموحة');
        }

        // Check if there's already an active attempt
        $active_attempt = ExamAttempt::where('user_id', $user->id)
            ->where('exam_id', $exam->id)
            ->where('status', 'in_progress')
            ->first();

        if ($active_attempt) {
            return redirect()->route('exam', ['exam' => $exam->id, 'slug' => $exam->slug])
                ->with('error', 'لديك محاولة جارية بالفعل');
        }

        // Get questions and shuffle if needed
        $questions = $exam->questions;
        $question_order = $questions->pluck('id')->toArray();

        if ($exam->shuffle_questions) {
            shuffle($question_order);
        }

        // Create new attempt
        $attempt = ExamAttempt::create([
            'started_at' => now(),
            'exam_id' => $exam->id,
            'user_id' => $user->id,
            'question_order' => $question_order,
            'status' => 'in_progress'
        ]);

        return redirect()->route('exam', ['exam' => $exam->id, 'slug' => $exam->slug]);
    }

    // تصحيح سؤال
    // وذلك حسب نوع السؤال if multiple_choice || true_false auto correct answer
    // if essay make it null now && score = 0
    // $answered_questions >= $total_questions سلم الامتحان
    public function answer_question(Request $request, Exam $exam, Question $question)
    {
        $user = auth_student();

        // Get current attempt
        $current_attempt = $exam->current_user_attempt();
        //  ExamAttempt::where('user_id', $user->id)
        //     ->where('exam_id', $exam->id)
        //     ->where('status', 'in_progress')
        //     ->first();

        if (!$current_attempt) {
            return redirect()->route('exam', ['exam' => $exam->id, 'slug' => $exam->slug])
                ->with('error', 'لا توجد محاولة جارية');
        }

        // Check time limit
        if ($exam->duration_minutes) {
            $elapsed_minutes = $current_attempt->started_at->diffInMinutes(now());
            if ($elapsed_minutes >= $exam->duration_minutes) {
                $this->auto_submit_exam($current_attempt);
                return redirect()->route('exam', ['exam' => $exam->id, 'slug' => $exam->slug])
                    ->with('error', 'تم انتهاء الوقت المحدد');
            }
        }

        // Validate answer based on question type
        $rules = [];
        if ($question->type === 'multiple_choice') {
            $rules['answer'] = 'required|array';
            $rules['answer.*'] = 'exists:question_options,id';
        } elseif ($question->type === 'true_false') {
            $rules['answer'] = 'required|in:true,false';
        } elseif ($question->type === 'essay') {
            $rules['answer'] = 'required|string|min:10';
        }

        $request->validate($rules);

        // Find or create exam answer
        DB::beginTransaction();
        try {
            $exam_answer = ExamAnswer::updateOrCreate(
                [
                    'exam_attempt_id' => $current_attempt->id,
                    'question_id' => $question->id
                ],
                [
                    'answered_at' => now()
                ]
            );

            // Process answer based on type
            if ($question->type === 'multiple_choice') {
                $selected_options = $request->answer;
                $exam_answer->selected_options = $selected_options;

                // Check if answer is correct
                $correct_options = $question->options()->where('is_correct', true)->pluck('id')->toArray();
                $is_correct = count($selected_options) === count($correct_options) &&
                             empty(array_diff($selected_options, $correct_options));

                $exam_answer->is_correct = $is_correct;
                $exam_answer->score = $is_correct ? $question->grade : 0;

            } elseif ($question->type === 'true_false') {
                $selected_answer = $request->answer === 'true';
                $exam_answer->selected_options = [$selected_answer];

                // Assuming true/false questions have one correct option
                $correct_option = $question->options()->where('is_correct', true)->first();
                $is_correct = false;

                if ($correct_option) {
                    // Check if the correct option text matches the selected answer
                    $correct_answer = strtolower($correct_option->option_en) === 'true';
                    $is_correct = $selected_answer === $correct_answer;
                }

                $exam_answer->is_correct = $is_correct;
                $exam_answer->score = $is_correct ? $question->grade : 0;

            } elseif ($question->type === 'essay') {
                $exam_answer->essay_answer = $request->answer;
                // Essay questions need manual grading
                $exam_answer->is_correct = null;
                $exam_answer->score = 0; // Will be updated after manual grading
            }

            $exam_answer->save();

            // Check if this is the last question
            $total_questions = count($current_attempt->question_order, true);
            $answered_questions = ExamAnswer::where('exam_attempt_id', $current_attempt->id)->count();

            if ($answered_questions >= $total_questions) {
                // Auto-submit exam if all questions are answered
                $this->submit_exam($current_attempt);
                DB::commit();
                return redirect()->route('exam', ['exam' => $exam->id, 'slug' => $exam->slug])
                    ->with('success', 'تم تسليم الامتحان بنجاح');
            }else {
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollback();
            $error          = $e->getMessage();
            $message_status = 'error';
        }
        // Redirect to next question
        $next_page = $request->get('page', 1) + 1;
        return redirect()->route('exam', ['exam' => $exam->id, 'slug' => $exam->slug, 'page' => $next_page])
              ->with($error ?? '',$message_status ?? '');
    }

    // تسليم الامتحان
    // وجعل submitted_at = now(), status =  completed
    public function submit_exam(ExamAttempt $attempt)
    {
        if ($attempt->status !== 'in_progress') {
            return abort(403,'unavailable exam');
        }

        $exam    = $attempt->exam;
        $answers = $attempt->answers;

        // Calculate total score
        $total_score    = $answers->sum('score');
        $total_possible = $exam->total_grade;
        $percentage     = $total_possible > 0 ? ($total_score / $total_possible) * 100 : 0;
        $is_passed      = $percentage >= $exam->passing_grade;

        // Update attempt
        $attempt->update([
            'submitted_at' => now(),
            'score' => $total_score,
            'percentage' => round($percentage, 2),
            'is_passed' => $is_passed,
            'status' => 'completed',   // update here when add teacher role
        ]);
    }

    // تسليم الامتحان اجباريا حال انتهاء الوقت
    // وذلك بنداء فنكشن submit_exam()
    // بعد التصحيح الالكتروني للاسئله غير المجابة وجعل نتيجتها is_correct = false
    public function auto_submit_exam(ExamAttempt $attempt)
    {
        // Mark unanswered questions as incorrect
        $exam = $attempt->exam;
        $question_order = $attempt->question_order;
        $answered_question_ids = $attempt->answers->pluck('question_id')->toArray();

        foreach ($question_order as $question_id) {
            if (!in_array($question_id, $answered_question_ids)) {
                ExamAnswer::create([
                    'exam_attempt_id' => $attempt->id,
                    'question_id' => $question_id,
                    'is_correct' => false,
                    'score' => 0,
                    'answered_at' => null,
                ]);
            }
        }

        $this->submit_exam($attempt);
    }

    // تسليم الامتحان
    public function finish_exam(Request $request, Exam $exam)
    {
        $user = auth_student();

        $current_attempt = ExamAttempt::where('user_id', $user->id)
            ->where('exam_id', $exam->id)
            ->where('status', 'in_progress')
            ->first();

        if (!$current_attempt) {
            return redirect()->route('exam', ['exam' => $exam->id, 'slug' => $exam->slug])
                ->with('error', 'لا توجد محاولة جارية');
        }

        $this->submit_exam($current_attempt);

        return redirect()->route('exam', ['exam' => $exam->id, 'slug' => $exam->slug])
            ->with('success', 'تم تسليم الامتحان بنجاح');
    }

    // get exam completed attempts
    public function exam_results(Exam $exam)
    {
        $user = auth_student();
        $attempts = $exam->result_attempts();

        return view('web.exam.results', [
            'exam'     => $exam,
            'attempts' => $attempts
        ]);
    }

    // review attempts answers
    public function review_attempt(Exam $exam, ExamAttempt $attempt)
    {
        $user = auth_student();

        // Check if user owns this attempt
        if ($attempt->user_id !== $user->id) {
            abort(403);
        }

        $answers = $attempt->answers()->with(['question', 'question.options'])->get();

        return view('web.exam.review', [
            'exam' => $exam,
            'attempt' => $attempt,
            'answers' => $answers
        ]);
    }
}
