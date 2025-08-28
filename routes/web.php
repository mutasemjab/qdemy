<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\ExamController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\PagesController;
use App\Http\Controllers\Web\CourseController;
use App\Http\Controllers\Web\LessonController;
use App\Http\Controllers\Web\ContactController;
use App\Http\Controllers\Web\PackageController;

use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\TawjihiController;
use App\Http\Controllers\Web\CheckoutController;
use App\Http\Controllers\Web\EnrollmentController;
use App\Http\Controllers\Web\VideoProgressController;
use App\Http\Controllers\Web\StudentAccountController;

use App\Http\Controllers\Web\PackageAndOfferController;
use App\Http\Controllers\Web\ElementaryProgrammController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group whichf
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/migrate-refresh', function () {
//     // Run the migration command
//     Artisan::call('migrate:fresh --seed');

//     // Get the output of the command
//     $output = Artisan::output();

//     // Return a response with the output
//     return response()->json(['message' => 'Migration and seeding completed successfully', 'output' => $output]);
// });

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']], function () {

    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/courses', [CourseController::class, 'index'])->name('courses');
    Route::get('/course/{course}/{slug?}' , [CourseController::class, 'course'])->name('course');
    Route::get('/subject-courses/{subject}/{slug?}', [CourseController::class, 'subject_courses'])->name('subject');
    Route::get('/universities-programm', [CourseController::class, 'universities_programm_courses'])->name('universities-programm');
    Route::get('/international-programm/{programm?}/{slug?}',[CourseController::class, 'international_programm_courses'])->name('international-programms');

    Route::get('/teachers', [PagesController::class, 'teachers'])->name('teachers');
    Route::get('/teacher/{teacher}', [PagesController::class, 'teacher'])->name('teacher');

    Route::get('/download', [PagesController::class, 'download'])->name('download');
    Route::get('/contacts', [PagesController::class, 'contacts'])->name('contacts');
    Route::get('/community', [PagesController::class, 'community'])->name('community');
    Route::get('/sale-point', [PagesController::class, 'sale_point'])->name('sale-point');
    Route::get('/cards-order', [PagesController::class, 'cards_order'])->name('card-order');
    Route::get('/bank-questions', [PagesController::class, 'bank_questions'])->name('bank-questions');
    Route::get('/ex-questions', [PagesController::class, 'ex_questions'])->name('ex-questions');

    Route::get('/grades-basic-programm', [ElementaryProgrammController::class, 'grades_basic_programm'])->name('grades_basic-programm');
    Route::get('/grade/{grade}/{slug?}', [ElementaryProgrammController::class, 'grade_programm'])->name('grade');

    Route::get('/tawjihi-programm',      [TawjihiController::class, 'tawjihi_programm'])->name('tawjihi-programm');
    Route::get('/tawjihi-first-year/{slug?}', [TawjihiController::class, 'tawjihi_first_year'])->name('tawjihi-first-year');
    Route::get('/tawjihi-grade-year-fields/{slug?}', [TawjihiController::class, 'tawjihi_grade_year_fields'])->name('tawjihi-grade-year-fields');
    Route::get('/tawjihi-grade-year-field/{field}/{slug?}', [TawjihiController::class, 'tawjihi_grade_year_field'])->name('tawjihi-grade-field');
    Route::get('/tawjihi-grade-year/{slug?}', [TawjihiController::class, 'tawjihi_grade_year_fields'])->name('tawjihi-grade-year');


    Route::post('/update-video-progress', [VideoProgressController::class, 'updateVideoProgress'])
    ->name('video.progress.update');

    Route::post('/mark-video-complete', [VideoProgressController::class, 'markVideoComplete'])
    ->name('video.progress.complete');

    Route::get('/student-account', [StudentAccountController::class, 'index'])->name('student.account');

    Route::group(['prefix'=>'cart'], function () {
        Route::get('/checkout', [EnrollmentController::class, 'index'])->name('checkout');
        Route::post('/add-to-session', [EnrollmentController::class, 'addToSession'])->name('add.to.session');
        Route::get('/add-to-session/{courseId?}', [EnrollmentController::class, 'addToSession'])->name('add.to.session');
        Route::get('/courses-count', [EnrollmentController::class, 'getCoursesCount'])->name('courses.count');
        Route::post('/activate-card', [EnrollmentController::class, 'activateCard'])->name('activate.card');
        Route::post('/payment-with-card', [EnrollmentController::class, 'paymentWithCard'])->name('payment.card');
        Route::post('/remove-course', [EnrollmentController::class, 'removeCourse'])->name('remove.course');
        Route::post('/process-payment', [EnrollmentController::class, 'processPayment'])->name('process.payment');
        Route::get('/payment-success', [EnrollmentController::class, 'paymentSuccess'])->name('payment.success');
    });

    // packages routes
    Route::group(['prefix'=>'packages'], function () {
        Route::get('/packages-and-offers/{programm?}', [PackageAndOfferController::class, 'index'])->name('packages-offers');
    });

    // enrollment routes
    // عرض قائمة الامتحانات
    Route::get('exam/', [ExamController::class, 'e_exam'])->name('e-exam');
    // عرض الامتحان
    Route::get('exam/{exam}/{slug?}', [ExamController::class, 'exam'])->name('exam');

    Route::middleware(['auth:user'])->prefix('exam')->group(function () {


        // بدء الامتحان
        Route::post('/{exam}/{slug?}/start', [ExamController::class, 'start_exam'])->name('start.exam');

        // الإجابة على سؤال
        Route::post('/{exam}/question/{question}/answer', [ExamController::class, 'answer_question'])->name('answer.question');

        // تسليم الامتحان
        Route::post('/{exam}/finish', [ExamController::class, 'finish_exam'])->name('finish.exam');

        // عرض النتائج
        Route::get('/{exam}/results', [ExamController::class, 'exam_results'])->name('exam.results');

        // مراجعة محاولة معينة
        Route::get('/{exam}/attempt/{attempt}/review', [ExamController::class, 'review_attempt'])->name('review.attempt');
    });

    Route::group(['middleware' => 'auth:user'], function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
        Route::get('/profile/edit', [ProfileController::class, 'editProfile'])->name('profile.edit');
        Route::put('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    });

    Route::middleware('guest:user')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('user.login');
        Route::get('/register', [AuthController::class, 'showRegister'])->name('user.register');
        Route::post('/login', [AuthController::class, 'login'])->name('user.login.submit');
        Route::post('/register', [AuthController::class, 'register'])->name('user.register.submit');
        Route::post('/password/reset', [AuthController::class, 'resetPassword'])->name('password.reset');

        // Google Login
        Route::get('login/google', [AuthController::class, 'redirectToGoogle'])->name('login.google');
        Route::get('login/google/callback', [AuthController::class, 'handleGoogleCallback']);
    });

});
