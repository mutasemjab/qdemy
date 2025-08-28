<?php

namespace  App\Http\Controllers\Web;

use App\Models\Exam;
use App\Models\Course;
use App\Models\Question;
use App\Models\ExamAttempt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExamController1 extends Controller
{
    public function e_exam(Request $request)
    {
        $programmsGrades             = CategoryRepository()->getProgrammsGrades();
        $subjectUnderProgrammsGrades = CategoryRepository()->getSubjectUnderProgrammsGrades();
        $gradesSemesters             = CategoryRepository()->getGradesSemesters();
        $query                       = Exam::Query();
        $exams                       = $query->paginate(PGN);
        return view('web.exam.e-exam',[
            'exams'           => $exams,
            'programmsGrades' => $programmsGrades,
            'gradesSemesters' => $gradesSemesters,
            'subjectUnderProgrammsGrades' => $subjectUnderProgrammsGrades,
        ]);
    }

    public function exam(Exam $exam,$slug = null,ExamAttempt $attempt = null)
    {
        // dump($slug ,$attempt);
        // $rr = Exam::whereHas('questions',function ($query) {
        //     $query->where('type','multiple_choice');
        // })->first();
        // dd($rr,$rr->questions->where('type','multiple_choice'));

        $_questions       = $exam->questions();
        // if($exam->shuffle_questions) (clone $_questions)->inRandomOrder()->get();
        $questions        = (clone $_questions)->paginate(1);
        $question         = $questions?->first();
        $question_nm      = $_GET['page'] ?? 1;

        $attempts         = $exam->attempts;
        $result           = $exam->result_attempt();
        $current_attempts = (clone $attempts)->where('submitted_at',null);
        $current_attempt  = $attempt ?? (clone $attempts)->where('submitted_at',null)->first();
        $last_attempts    = (clone $attempts)->where('status','!=','abandoned');
        $can_add_attempt  = $exam->can_add_attempt();

        // $remaining_time   = $current_attempt?->starred_at;

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

    public function start_exam (Exam $exam)
    {
        $user = auth_student()?->id;
        ExamAttempt::create([
            'started_at' => now(),
            'exam_id'    => $exam->id,
            'user_id'    => $user,
        ]);
        return redirect()->route('exam',['exam'=>$exam->id,'slug'=>$exam->slug]);
    }

    public function answer_question (Request $request) {

        // update if there id old answers
    }


}
