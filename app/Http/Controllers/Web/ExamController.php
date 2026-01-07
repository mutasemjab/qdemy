<?php

namespace  App\Http\Controllers\Web;

use App\Models\Exam;
use App\Models\Subject;
use App\Models\Category;
use App\Models\Question;
use App\Models\ExamAnswer;
use App\Models\ExamAttempt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Traits\Responses;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ContentUserProgress;

class ExamController extends Controller
{

    use Responses;

    public $isApi          = false;
    public $apiRoutePrefix = '';

    protected function checkIfApi()
    {
        // Check if this is coming from mobile webview
        $hasUserId = request()->hasHeader("UserId");
        $expectsJson = request()->expectsJson();
        $isApiRoute = request()->is('api/*');
        $isMobileSession = session('is_mobile_app');

        if (
            $hasUserId ||
            $expectsJson ||
            $isApiRoute ||
            $isMobileSession
        ) {
            $this->isApi = true;
            $this->apiRoutePrefix = API_ROUTE_PREFIX;
            session(['is_mobile_app' => true]);
        }
    }


    public function __construct(Request $request)
    {
        $this->middleware(function ($request, $next) {

            $this->checkIfApi();

            // share isApi and api route prefix in all views 
            view()->share([
                'isApi' =>  $this->isApi,
                "apiRoutePrefix" => $this->apiRoutePrefix,
                'hideHeader' => $this->isApi,
                'hideFooter' => $this->isApi
            ]);

            return $next($request);
        });
        $this->checkIfApi();

        // API-specific authentication via UserId header
        if ($this->isApi && $request->hasHeader('UserId')) {
            $userId = $request->header('UserId');

            $user = \App\Models\User::where('id', $userId)
                ->where('role_name', 'student')
                ->first();

            if ($user) {
                auth('user')->login($user);
                session(['is_mobile_app' => true, 'mobile_user_id' => $userId]);
            }
        }

        // Handle _user_id from query/form parameters (try all sources)
        $paramUserId = $request->query('_user_id')
            ?? $request->post('_user_id')
            ?? $request->input('_user_id');
        if ($paramUserId && !auth('user')->check()) {
            $user = \App\Models\User::find($paramUserId);
            if ($user && $user->role_name === 'student') {
                auth('user')->login($user);
                session(['is_mobile_app' => true, 'mobile_user_id' => $paramUserId]);
            }
        }

        // Persist authentication across requests using session
        if (session('is_mobile_app')) {
            $this->isApi = true;
            $this->apiRoutePrefix = API_ROUTE_PREFIX;

            // إذا لم يكن mobile_user_id محفوظ، حاول الحصول عليه من auth()
            if (!session('mobile_user_id') && auth('user')->check()) {
                session(['mobile_user_id' => auth('user')->id()]);
            }

            // الآن حاول تسجيل الدخول إذا كان mobile_user_id موجود
            if (!auth('user')->check() && session('mobile_user_id')) {
                $user = \App\Models\User::find(session('mobile_user_id'));
                if ($user) {
                    auth('user')->login($user);
                }
            }
        }

        // Set language
        if ($request->hasHeader('Lang') || $request->hasHeader('Language')) {
            $lang = $request->header('Lang') ?? $request->header('Language');
            if (in_array($lang, ['en', 'ar'])) {
                app()->setLocale($lang);
                session(['locale' => $lang]);
            }
        }

        // Set apiRoutePrefix based on isApi flag
        if ($this->isApi) {
            $this->apiRoutePrefix = API_ROUTE_PREFIX;  // 'api.'
        } else {
            $this->apiRoutePrefix = '';  // Empty string for web routes
        }
    }

    public function index(Request $request)
    {
        // Get filter data
        $programms = CategoryRepository()->getMajors();
        $grades    = collect();
        $subjects  = collect();

        // Build main query
        $query = Exam::query()
            ->where('is_active', 1)
            ->where(function ($q) {
                $now = now();
                $q->where(function ($q) use ($now) {
                    $q->whereNull('start_date')
                        ->orWhere('start_date', '<=', $now);
                })
                    ->where(function ($q) use ($now) {
                        $q->whereNull('end_date')
                            ->orWhere('end_date', '>=', $now);
                    });
            });

        // Program filter
        if ($request->filled('programm_id')) {
            $selectedProgram = Category::find($request->programm_id);

            if ($selectedProgram) {
                // Check if program needs grades
                if (in_array($selectedProgram->ctg_key, ['tawjihi-and-secondary-program', 'elementary-grades-program'])) {
                    // Load grades for this program
                    if ($selectedProgram->ctg_key == 'elementary-grades-program') {
                        $grades = CategoryRepository()->getElementryProgramGrades();
                    } else {
                        $grades = CategoryRepository()->getTawjihiProgrammGrades();
                    }

                    // Filter exams by program through subjects
                    $query->whereHas('subject', function ($q) use ($request) {
                        $q->where('programm_id', $request->programm_id);
                    });
                } else {
                    // Programs without grades - program_id acts as grade_id
                    $query->whereHas('subject', function ($q) use ($request) {
                        $q->where('programm_id', $request->programm_id);
                    });

                    // Load subjects for this program directly
                    $subjects = Subject::where('programm_id', $request->programm_id)
                        ->where('is_active', 1)
                        ->get();
                }
            }
        }

        // Grade filter
        if ($request->filled('grade_id')) {
            $query->whereHas('subject', function ($q) use ($request) {
                $q->where('grade_id', $request->grade_id);
            });

            // Load subjects for selected grade
            $subjects = Subject::where('grade_id', $request->grade_id)
                ->where('is_active', 1)
                ->get();
        }

        // Subject filter
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        // Search filter
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title_ar', 'like', "%{$searchTerm}%")
                    ->orWhere('title_en', 'like', "%{$searchTerm}%")
                    ->orWhere('description_ar', 'like', "%{$searchTerm}%")
                    ->orWhere('description_en', 'like', "%{$searchTerm}%")
                    ->orWhereHas('subject', function ($sq) use ($searchTerm) {
                        $sq->where('name_ar', 'like', "%{$searchTerm}%")
                            ->orWhere('name_en', 'like', "%{$searchTerm}%");
                    });
            });
        }

        $exams = $query->paginate(PGN)->withQueryString();

        return view('web.exam.exams', [
            'exams' => $exams,
            'programms' => $programms,
            'grades' => $grades,
            'subjects' => $subjects,
            'apiRoutePrefix' => $this->apiRoutePrefix,
        ]);
    }

    public function show(Exam $exam, $slug = null, ?ExamAttempt $attempt)
    {
        $user = auth_student();

        // Check if exam is active and within date range
        if (!$exam->is_available()) {
            return redirect()->route($this->apiRoutePrefix . 'exam.index')->with('error', 'الامتحان غير متاح حاليا');
        }

        $attempts         = $exam->user_attempts();

        // FIXED: Get the LATEST completed attempt from collection
        $result           = $exam->user_attempts()
            ->where('submitted_at', '!=', null)
            ->where('status', 'completed')
            ->sortByDesc('created_at')
            ->first();

        $current_attempts = $exam->current_user_attempts();

        $last_attempts    = $attempts->where('status', '!=', 'abandoned');
        $can_add_attempt  = $exam->can_add_attempt($user?->id);

        // Get current attempt or create one if needed
        $current_attempt = $attempt ?? $exam->current_user_attempt();

        // Check time limit
        if ($current_attempt && $exam->duration_minutes && $current_attempt->started_at) {
            $elapsed_minutes = $current_attempt->started_at->diffInMinutes(now());
            if ($elapsed_minutes >= $exam->duration_minutes) {
                $this->auto_submit_exam($current_attempt);
                return redirect()->route($this->apiRoutePrefix . 'exam', ['exam' => $exam->id, 'slug' => $exam->slug])
                    ->with('error', 'تم انتهاء الوقت المحدد وتم تسليم الامتحان تلقائيا');
            }
        }

        $_questions   = $exam->questions();
        $pgQquestions = null;
        $questions    = null;
        $question     = null;
        $question_nm  = 1;

        if ($current_attempt) {

            // Get questions in the order stored for this attempt
            $question_order = $current_attempt->question_order;
            if ($question_order) {
                $questions = Question::whereIn('id', $question_order)
                    ->orderByRaw('FIELD(id, ' . implode(',', $question_order) . ')');
            } else {
                $questions = (clone $_questions);
            }
            $pgQquestions = (clone $questions)->paginate(1);

            if ($current_attempt->answers?->count() && !request()->get('page')) {
                // Redirect to exam.take page instead of exam details
                return redirect()->route($this->apiRoutePrefix . 'exam.take', ['exam' => $exam->id]);
            } else {
                $question_nm = request()->get('page');
                $question    = $pgQquestions?->first();
            }
        }

        return view('web.exam.exam-details', [
            'exam'            => $exam,
            'questions'       => $pgQquestions,
            'question'        => $question,
            'attempts'        => $attempts,
            'result'          => $result,
            'current_attempts' => $current_attempts,
            'current_attempt' => $current_attempt,
            'last_attempts'   => $last_attempts,
            'can_add_attempt' => $can_add_attempt,
            'question_nm'     => $question_nm,
            '_questions'      => $_questions,
        ]);
    }

    public function start_exam(Request $request, Exam $exam)
    {
        // Try to authenticate from _user_id parameter first (for mobile WebView)
        $userId = $request->input('_user_id');
        if ($userId && !auth('user')->check()) {
            $user = \App\Models\User::find($userId);
            if ($user && $user->role_name === 'student') {
                auth('user')->login($user);
            }
        }

        $user = auth_student();

        // Check if user can start new attempt
        if (!$exam->can_add_attempt($user?->id)) {
            return redirect()->route($this->apiRoutePrefix . 'exam', ['exam' => $exam->id, 'slug' => $exam->slug])
                ->with('error', translate_lang('لقد استنفدت عدد المحاولات المسموحة'));
        }

        // Check if there's already an active attempt (not submitted)
        $active_attempt = $exam->current_user_attempts()
            ->where('submitted_at', null)
            ->first();

        if ($active_attempt) {
            return redirect()->route($this->apiRoutePrefix . 'exam', ['exam' => $exam->id, 'slug' => $exam->slug])
                ->with('error', translate_lang('لديك محاولة جارية بالفعل'));
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
            'user_id' => $user?->id,
            'question_order' => $question_order,
            'status' => 'in_progress'
        ]);

        // Redirect to exam taking page
        return redirect()->route($this->apiRoutePrefix . 'exam.take', ['exam' => $exam->id]);
    }

    // تصحيح سؤال
    // وذلك حسب نوع السؤال if multiple_choice || true_false auto correct answer
    // if essay make it null now && score = 0
    // $answered_questions >= $total_questions سلم الامتحان
    public function answer_question(Request $request, Exam $exam, Question $question)
    {
        // Get current attempt
        $current_attempt = $exam->current_user_attempt();
        if (!$current_attempt) {
            return redirect()->route($this->apiRoutePrefix . 'exam', ['exam' => $exam->id, 'slug' => $exam->slug])->with('error', translate_lang('لا توجد محاولة جارية'));
        }

        // Check time limit
        if ($exam->duration_minutes && $current_attempt->started_at) {
            $elapsed_minutes = $current_attempt->started_at->diffInMinutes(now());
            if ($elapsed_minutes >= $exam->duration_minutes) {
                $this->auto_submit_exam($current_attempt);
                return redirect()->route($this->apiRoutePrefix . 'exam', ['exam' => $exam->id, 'slug' => $exam->slug])->with('error', translate_lang('تم انتهاء الوقت المحدد'));
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
            $rules['answer'] = 'nullable|string';
        }

        if ($this->isApi) {
            $validator = Validator::make($request->all(),  $rules);
            if ($validator->fails()) {
                return $this->error_response('Validation failed', $validator->errors());
            }
        } else {
            $request->validate($rules);
        }


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
            $total_questions = count($current_attempt->question_order);
            $answered_questions = ExamAnswer::where('exam_attempt_id', $current_attempt->id)->count();

            if ($answered_questions >= $total_questions) {
                // Auto-submit exam if all questions are answered
                $this->submit_exam($current_attempt);
                DB::commit();
                return redirect()->route($this->apiRoutePrefix . 'exam', ['exam' => $exam->id, 'slug' => $exam->slug])->with('success', translate_lang('تم تسليم الامتحان بنجاح'));
            } else {
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollback();
            $error          = $e->getMessage();
            $message_status = 'error';
        }
        // Redirect to next question
        $next_page = $request->get('next_page');
        if (!$next_page) {
            $next_page = $request->get('page', 1) + 1;
        }

        return redirect()->route($this->apiRoutePrefix . 'exam.take', ['exam' => $exam->id])->with($error ?? '', $message_status ?? '');
    }

    // تسليم الامتحان
    // وجعل submitted_at = now(),
    // if exam_attempts.show_results_immediately == true يتم التصحيح الاليكتروني و جعل ال status = completed - وعرض النتيجة فورا
    // if exam_attempts.show_results_immediately == false يتم التصحيح الاليكتروني للاسئله غير المقالية وال status = in_progress ولا يتم عرض النتيجة
    // ماذا ان كان if exam_attempts.show_results_immediately == true وف نفس الوقت توجد اسئلة مقالية ؟ هذه ايعني ان واضع الامتحان حمار وهو المسؤول عن ظهور النتيجة خاطئة لان الاسئلة المقالية لا يجري تصيحيها اليكترونيا
    public function submit_exam(ExamAttempt $attempt)
    {

        if ($attempt->status !== 'in_progress') {
            return abort(403, 'unavailable exam');
        }

        $exam    = $attempt->exam;
        $answers = $attempt->answers;

        // Calculate total score
        $total_score = (float)$answers->sum('score');

        // Get total possible score: use exam->total_grade, or calculate from questions if not set
        $total_possible = (float)$exam->total_grade;
        if ($total_possible <= 0) {
            // Fallback: calculate from actual questions in exam
            $total_possible = (float)$exam->questions()->sum('exam_questions.grade');
        }

        $percentage     = $total_possible > 0 ? (($total_score / $total_possible) * 100) : 0;
        $is_passed      = $percentage >= $exam->passing_grade;

        // Update attempt
        if ($exam->show_results_immediately == true) {
            $attempt->update([
                'submitted_at' => now(),
                'score'        => $total_score,
                'percentage'   => round($percentage, 2),
                'is_passed'    => $is_passed,
                'status'       => 'completed',
            ]);

            // فقط إذا كان الامتحان مرتبط بدرس، نحدث progress
            // لا نحدث progress للامتحانات العادية
            if ($exam->course_content_id) {
                // استخدام updateOrCreate مع الـ keys الصحيحة
                // user_id + course_content_id هي الـ unique constraint
                ContentUserProgress::updateOrCreate(
                    [
                        'user_id' => $attempt->user_id,
                        'course_content_id' => $exam->course_content_id,
                    ],
                    [
                        'exam_id' => $exam->id,
                        'exam_attempt_id' => $attempt->id,
                        'completed' => true,
                        'score' => $total_score,
                        'percentage' => round($percentage, 2),
                        'is_passed' => $is_passed,
                        'viewed_at' => now(),
                        'watch_time' => null,
                    ]
                );
            }
        } else {
            $attempt->update([
                'submitted_at' => now(),
            ]);
        }
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
        // Try to authenticate from _user_id parameter first (for mobile WebView)
        $userId = $request->input('_user_id');
        if ($userId && !auth('user')->check()) {
            $user = \App\Models\User::find($userId);
            if ($user && $user->role_name === 'student') {
                auth('user')->login($user);
            }
        }

        $user = auth_student();

        $current_attempt = ExamAttempt::where('user_id', $user?->id)
            ->where('exam_id', $exam->id)
            ->where('submitted_at', null)
            ->first();

        if (!$current_attempt) {
            return redirect()->route($this->apiRoutePrefix . 'exam', ['exam' => $exam->id, 'slug' => $exam->slug])
                ->with('error', 'لا توجد محاولة جارية');
        }

        $this->submit_exam($current_attempt);

        return redirect()->route($this->apiRoutePrefix . 'exam.result', ['exam' => $exam->id, 'attempt' => $current_attempt->id])->with('success', 'تم تسليم الامتحان بنجاح');
    }

    /**
     * Show exam taking page
     */
    public function take(Exam $exam)
    {
        $user = auth_student();

        if (!$exam->is_available()) {
            return redirect()->route('exams')->with('error', __('front.unavailable'));
        }

        // Get current attempt
        $current_attempt = $exam->current_user_attempt();

        if (!$current_attempt) {
            return redirect()->route('exam', ['exam' => $exam->id, 'slug' => $exam->slug])->with('error', __('front.no_ongoing_attempt'));
        }

        // Check time limit
        if ($exam->duration_minutes && $current_attempt->started_at) {
            $elapsed_minutes = $current_attempt->started_at->diffInMinutes(now());
            if ($elapsed_minutes >= $exam->duration_minutes) {
                $this->auto_submit_exam($current_attempt);
                return redirect()->route('exam.result', ['exam' => $exam->id, 'attempt' => $current_attempt->id]);
            }
        }

        // Get questions with options (eager load)
        $question_order = $current_attempt->question_order;

        // Ensure question_order is a valid array
        if (!is_array($question_order) || empty($question_order)) {
            return redirect()->route('exams')->with('error', __('front.exam_data_invalid'));
        }

        $allQuestions = Question::whereIn('id', $question_order)
            ->with('options')
            ->orderByRaw('FIELD(id, ' . implode(',', $question_order) . ')')
            ->get();

        // Get saved answers in JSON format
        $savedAnswers = $current_attempt->answers()
            ->get()
            ->keyBy('question_id')
            ->map(function ($answer) {
                return [
                    'selected_options' => $answer->selected_options ?? [],
                    'essay_answer' => $answer->essay_answer,
                    'is_correct' => $answer->is_correct,
                    'answered_at' => $answer->answered_at
                ];
            });

        // Calculate remaining time in seconds
        $remainingSeconds = 0;
        if ($exam->duration_minutes && $current_attempt->started_at) {
            $elapsed_seconds = $current_attempt->started_at->diffInSeconds(now());
            $total_seconds = $exam->duration_minutes * 60;
            $remainingSeconds = max(0, $total_seconds - $elapsed_seconds);
        }

        return view('web.exam.exam-taking', [
            'exam' => $exam,
            'allQuestions' => $allQuestions,
            'savedAnswers' => $savedAnswers,
            'current_attempt' => $current_attempt,
            'remainingSeconds' => $remainingSeconds,
        ]);
    }

    /**
     * Save answer via AJAX
     */
    public function save_answer_ajax(Request $request, Exam $exam)
    {
        // Try to authenticate from _user_id parameter first (for mobile WebView)
        $userId = $request->input('_user_id');
        if ($userId && !auth('user')->check()) {
            $user = \App\Models\User::find($userId);
            if ($user && $user->role_name === 'student') {
                auth('user')->login($user);
            }
        }

        $user = auth_student();

        // If still not authenticated, return error
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized - no active user session'], 401);
        }

        // Get current attempt for the specific user
        $current_attempt = $exam->attempts()
            ->where('user_id', $user->id)
            ->where('status', 'in_progress')
            ->whereNull('submitted_at')
            ->first();

        if (!$current_attempt) {
            return response()->json(['success' => false, 'message' => 'No active attempt'], 404);
        }

        // Validate request
        $validator = Validator::make($request->all(), [
            'question_id' => 'required|exists:questions,id',
            'answer_type' => 'required|in:multiple_choice,true_false,essay',
            'answer' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $question = Question::findOrFail($request->question_id);

        // Check time limit (in seconds)
        if ($exam->duration_minutes && $current_attempt->started_at) {
            $elapsed_seconds = $current_attempt->started_at->diffInSeconds(now());
            $total_seconds = $exam->duration_minutes * 60;

            if ($elapsed_seconds >= $total_seconds) {
                $this->auto_submit_exam($current_attempt);
                return response()->json(['success' => false, 'message' => 'Time expired', 'expired' => true], 403);
            }
        }

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

            // Process based on type
            if ($question->type === 'multiple_choice') {
                $selected_options = is_array($request->answer) ? $request->answer : [$request->answer];
                // Cast to integers to ensure consistency when retrieving
                $selected_options = array_map(function ($opt) {
                    return (int)$opt;
                }, $selected_options);
                $exam_answer->selected_options = $selected_options;

                $correct_options = $question->options()->where('is_correct', true)->pluck('id')->toArray();
                $is_correct = count($selected_options) === count($correct_options) &&
                    empty(array_diff($selected_options, $correct_options));

                $exam_answer->is_correct = $is_correct;
                $exam_answer->score = $is_correct ? $question->grade : 0;
            } elseif ($question->type === 'true_false') {
                $selected_answer = $request->answer === 'true' || $request->answer === true;
                $exam_answer->selected_options = [$selected_answer];

                $correct_option = $question->options()->where('is_correct', true)->first();
                $is_correct = false;

                if ($correct_option) {
                    $correct_answer = strtolower($correct_option->option_en) === 'true';
                    $is_correct = $selected_answer === $correct_answer;
                }

                $exam_answer->is_correct = $is_correct;
                $exam_answer->score = $is_correct ? $question->grade : 0;
            } elseif ($question->type === 'essay') {
                $exam_answer->essay_answer = $request->answer;
                $exam_answer->is_correct = null;
                $exam_answer->score = 0;
            }

            $exam_answer->save();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Answer saved'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Show exam result
     */
    public function result(Exam $exam, ExamAttempt $attempt)
    {
        $user = auth_student();

        // Check if user owns this attempt
        if ($attempt->user_id !== $user?->id) {
            abort(403);
        }

        // Ensure mobile query params are preserved
        if ($this->isApi) {
            $redirectUrl = route($this->apiRoutePrefix . 'exam.result', ['exam' => $exam->id, 'attempt' => $attempt->id]);
            return redirect($redirectUrl);
        }

        // Get all questions in the order they were presented
        $question_order = $attempt->question_order;
        $allQuestions = Question::whereIn('id', $question_order)
            ->with('options')
            ->get()
            ->keyBy('id');

        // Get all answers
        $answersList = $attempt->answers()->with(['question', 'question.options'])->get();
        $answersMap = $answersList->keyBy('question_id');

        // Build complete list of all questions with their answers
        $answers = [];
        foreach ($question_order as $question_id) {
            if (isset($allQuestions[$question_id])) {
                $answer = $answersMap[$question_id] ?? null;

                if (!$answer) {
                    // Create a dummy answer object for unanswered questions
                    $answer = new ExamAnswer([
                        'question_id' => $question_id,
                        'is_correct' => false,
                        'score' => 0,
                        'answered_at' => null,
                    ]);
                    $answer->question = $allQuestions[$question_id];
                }

                $answers[] = $answer;
            }
        }

        // Calculate stats (only count actually answered questions)
        $correctCount = $answersList->where('is_correct', true)->count();
        $wrongCount = $answersList->where('is_correct', false)->count();
        $passed = $attempt->is_passed ?? false;
        $canRetake = $exam->can_add_attempt($user?->id);

        // Get all attempts for this exam by this user (newest to oldest)
        $allAttempts = ExamAttempt::where('exam_id', $exam->id)
            ->where('user_id', $user?->id)
            ->where('submitted_at', '!=', null)
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('web.exam.exam-result', [
            'exam' => $exam,
            'attempt' => $attempt,
            'answers' => collect($answers),
            'correctCount' => $correctCount,
            'wrongCount' => $wrongCount,
            'passed' => $passed,
            'canRetake' => $canRetake,
            'allAttempts' => $allAttempts,

        ]);
    }

    /**
     * Show exam history for authenticated user
     */
    public function history(Request $request)
    {

        $user = auth_student();

        // Get all exam attempts for the user
        $attempts = ExamAttempt::where('user_id', $user?->id)
            ->where('submitted_at', '!=', null)
            ->with(['exam'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Calculate statistics
        $totalExams = $attempts->count();
        $passedExams = $attempts->where('is_passed', true)->count();
        $failedExams = $attempts->where('is_passed', false)->count();
        $overallPercentage = $totalExams > 0
            ? round(($passedExams / $totalExams) * 100, 1)
            : 0;

        return view('web.exam.exam-history', [
            'attempts' => $attempts,
            'totalExams' => $totalExams,
            'passedExams' => $passedExams,
            'failedExams' => $failedExams,
            'overallPercentage' => $overallPercentage,

        ]);
    }

    // review attempts answers (قديم)
    public function review_attempt(Exam $exam, ExamAttempt $attempt)
    {

        $user = auth_student();

        // // Check if user owns this attempt
        // if ($attempt->user_id !== $user?->id) {
        //     abort(403);
        // }

        $answers = $attempt->answers()->with(['question', 'question.options'])->get();

        return view('web.exam.review', [
            'exam'    => $exam,
            'attempt' => $attempt,
            'answers' => $answers,
        ]);
    }
}
