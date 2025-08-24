<?php

namespace Database\Seeders;

use App\Models\QuestionOption;
use Illuminate\Database\Seeder;
use App\Models\{Exam, ExamQuestion, ExamAttempt, ExamAnswer, User, Question, Course, Admin};

class ExamSeeder extends Seeder
{
    public function run()
    {
        // التأكد من وجود بيانات أساسية
        if (Course::count() < 1 || User::count() < 1 || Admin::count() < 1 || Question::count() < 1) {
        // if (Course::count() < 1 || User::count() < 1 || Admin::count() < 1 || Question::count() < 1) {
            $this->command->error('يجب وجود بيانات أساسية أولاً!');
            $this->command->info('الحد الأدنى المطلوب:');
            $this->command->info('- 1 مقررات دراسية');
            $this->command->info('- 1 مستخدمين');
            $this->command->info('- 1 مشرفين');
            $this->command->info('- 1 سؤال');
            return;
        }

        // إنشاء 50 امتحان
        $exams = Exam::factory()->count(50)->create();

        foreach ($exams as $exam) {
            // اختيار 20 سؤال عشوائي من الأسئلة الموجودة
            $oldExamQuestions =  ExamQuestion::where('exam_id',$exam->id)->pluck('question_id');
            $questions = Question::whereNotIn('id',$oldExamQuestions)->inRandomOrder()->limit(20)->get();

            foreach ($questions as $index => $question) {
                ExamQuestion::create([
                    'exam_id' => $exam->id,
                    'question_id' => $question->id,
                    'order' => $index + 1,
                    'grade' => rand(1, 10),
                ]);
            }

            // تحديث المجموع الكلي للدرجات
            $exam->update(['total_grade' => $exam->questions()->sum('exam_questions.grade')]);

            // // إنشاء 1-3 محاولات لكل امتحان
            // $attemptsCount = rand(1, 3);
            // $users = User::where('role_name','student')->limit($attemptsCount)->get();

            // foreach ($users as $user) {


            //     $attempt = ExamAttempt::create([
            //         'exam_id' => $exam->id,
            //         'user_id' => $user->id,
            //         'started_at' => now()->subDays(rand(1, 30)),
            //         'submitted_at' => rand(0, 1) ? now()->subDays(rand(0, 29)) : null,
            //         'status' => 'completed',
            //     ]);

            //     // إنشاء إجابات لكل سؤال
            //     foreach ($exam->questions as $examQuestion) {
            //         $isCorrect = (bool)rand(0, 1);
            //         $score            = $isCorrect ? $examQuestion->grade : 0;
            //         $examOptions      = $examQuestion->options->pluck('id');
            //         $correctOptions   = $examQuestion->correctOptions->pluck('id');
            //         // $availableQuestions = Question::where('course_id', $exam->course_id)
            //         //     ->whereNotIn('id', $exam->questions->pluck('id'))
            //         //     ->with('options')
            //         //     ->get();
            //         // dd($correctOptions,$examOptions);
            //         ExamAnswer::create([
            //             'exam_attempt_id'  => $attempt->id,
            //             'question_id'      => $examQuestion->id,
            //             'selected_options' => $isCorrect ? $correctOptions : $examOptions,
            //             'is_correct'       => $isCorrect,
            //             'score'            => $score,
            //             'answered_at'      => $attempt->started_at->addMinutes(rand(1, 59)),
            //         ]);
            //     }

            //     // حساب النتيجة إذا كانت المحاولة مكتملة
            //     if ($attempt->submitted_at) {
            //         $totalScore = $attempt->answers()->sum('score');
            //         $percentage = ($totalScore / $exam->total_grade) * 100;

            //         $attempt->update([
            //             'score' => $totalScore,
            //             'percentage' => round($percentage, 2),
            //             'is_passed' => $percentage >= $exam->passing_grade,
            //         ]);
            //     }
            // }
        }
    }
}
