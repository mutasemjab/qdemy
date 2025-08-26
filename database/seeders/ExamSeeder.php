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
            $this->command->error('يجب وجود بيانات أساسية أولاً!');
            $this->command->info('الحد الأدنى المطلوب:');
            $this->command->info('- 1 مقررات دراسية');
            $this->command->info('- 1 مستخدمين');
            $this->command->info('- 1 مشرفين');
            $this->command->info('- 1 سؤال');
            return;
        }

        $this->main_exams();

        // إنشاء 60 امتحان
        $exams = Exam::factory()->count(60)->create();

        foreach ($exams as $exam) {
            // اختيار 12 سؤال عشوائي من الأسئلة الموجودة
            $oldExamQuestions =  ExamQuestion::where('exam_id',$exam->id)->pluck('question_id');
            $questions = Question::whereNotIn('id',$oldExamQuestions)->inRandomOrder()->limit(12)->get();

            foreach ($questions as $index => $question) {
                if(ExamQuestion::where('exam_id',$exam->id)->where('question_id', $question->id)->exists()) return 0;
                ExamQuestion::create([
                    'exam_id'     => $exam->id,
                    'question_id' => $question->id,
                    'order'       => $index + 1,
                    'grade'       => rand(1, 10),
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

    public function main_exams()
    {
        $courses = Course::with('sections')->inRandomOrder()->take(15)->get();
        $questions = Question::all();
        $user = User::inRandomOrder()->first();
        $admin = Admin::inRandomOrder()->first();

        $examTitles = [
            ['en' => 'Final Exam', 'ar' => 'الامتحان النهائي'],
            ['en' => '1 Quiz', 'ar' => 'الاختبار 1'],
            ['en' => 'English Midterm', 'ar' => 'إمتحان الإنجليزيى النصفي'],
            ['en' => 'Practice Test', 'ar' => 'الاختبار العملي'],
            ['en' => 'Monthly Exam', 'ar' => 'الامتحان الشهري'],
            ['en' => 'Computer Basics Test', 'ar' => 'اختبار أساسيات الكمبيوتر'],
            ['en' => 'Ready Test Quiz', 'ar' => 'الاختبار التجهيزي'],
            ['en' => 'Final exam', 'ar' => 'الامتحان النهائي'],
            ['en' => 'Practice Exam', 'ar' => 'الامتحان التدريبي'],
            ['en' => 'Midterm exam', 'ar' => '2 الامتحان النصفي'],
        ];

        for ($i = 0; $i < 60; $i++) {

            if($i < 6){ $_index = 1; }else{ $_index = $i % $courses->count();}

            $course = $courses[$_index];
            $section = $course->sections()->inRandomOrder()->first();

            $examTitle = $examTitles[$i % count($examTitles)];

            $exam = Exam::create([
                'title_en' => $examTitle['en'] . " - " . $course->title_en,
                'title_ar' => $examTitle['ar'] . " - " . $course->title_ar,
                'description_en' => "This is a real exam for " . $course->title_en . ".",
                'description_ar' => "هذا امتحان حقيقي لمادة " . $course->title_ar . ".",
                'total_grade' => 10.00,
                'duration_minutes' => rand(30, 90),
                'attempts_allowed' => rand(1, 3),
                'passing_grade' => 5.00,
                'shuffle_questions' => true,
                'shuffle_options' => false,
                'show_results_immediately' => true,
                'is_active' => true,
                'start_date' => now()->subDays(rand(0, 30)),
                'end_date' => now()->addDays(rand(1, 30)),
                'course_id'  => $course->id,
                'section_id' => $section ? $section->id : null,
                'created_by' => $user ? $user->id : null,
                'created_by_admin' => $admin ? $admin->id : null,
            ]);

            // اختر مجموعة أسئلة متنوعة للامتحان (ما بين 6 و 12)
            $examQuestions = $questions->where('course_id', $course->id)->shuffle()->take(rand(6, 12));
            $perQGrade = round(10.0 / max(1, $examQuestions->count()), 2);
            $qOrder = 1;
            foreach ($examQuestions as $q) {
                if(ExamQuestion::where('exam_id',$exam->id)->where('question_id', $q->id)->exists()) return 0;
                ExamQuestion::create([
                    'exam_id' => $exam->id,
                    'question_id' => $q->id,
                    'order' => $qOrder++,
                    'grade' => $perQGrade,
                ]);
            }
        }
    }

}
