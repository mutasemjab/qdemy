<?php

use App\Http\Controllers\Web\BankQuestionController;
use App\Http\Controllers\Web\CommunityController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Web\AuthController;
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
use App\Http\Controllers\Web\TeacherController;
use App\Http\Controllers\Web\ContactUsController;
use App\Http\Controllers\Web\EnrollmentController;
use App\Http\Controllers\Web\VideoProgressController;
use App\Http\Controllers\Web\StudentAccountController;

use App\Http\Controllers\Web\PackageAndOfferController;
use App\Http\Controllers\Web\UniversityProgramController;
use App\Http\Controllers\Web\ElementaryProgrammController;
use App\Http\Controllers\Web\InternationalProgramController;
use App\Http\Controllers\Web\MinisterialYearsQuestionController;
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
    Route::get('/contacts', [ContactUsController::class, 'index'])->name('contacts');
    Route::post('/contacts/store', [ContactUsController::class, 'store'])->name('contacts.store');

    Route::get('/courses', [CourseController::class, 'index'])->name('courses');
    Route::get('/course/{course}/{slug?}', [CourseController::class, 'course'])->name('course');
    Route::get('/subject-courses/{subject}/{slug?}', [CourseController::class, 'subject_courses'])->name('subject');
    Route::get('/universities-programm/{programm?}/{slug?}', [UniversityProgramController::class, 'index'])->name('universities-programm');
    Route::get('/international-programm/{programm?}/{slug?}', [InternationalProgramController::class, 'index'])->name('international-programms');

    Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers');
    Route::get('/teacher/{id}', [TeacherController::class, 'show'])->name('teacher');


    Route::get('/download', [PagesController::class, 'download'])->name('download');
    Route::get('/sale-point', [PagesController::class, 'sale_point'])->name('sale-point');
    Route::get('/cards-order', [PagesController::class, 'cards_order'])->name('card-order');
    
    Route::prefix('bankQuestions')->name('bankQuestions.')->group(function () {
        Route::get('/', [BankQuestionController::class, 'index'])->name('index');
        Route::get('/download/{bankQuestion}', [BankQuestionController::class, 'download'])->name('download');
        Route::get('/subjects-by-category', [BankQuestionController::class, 'getSubjectsByCategory'])->name('subjects-by-category');
    });

    Route::prefix('ministerialQuestions')->name('ministerialQuestions.')->group(function () {
        Route::get('/', [MinisterialYearsQuestionController::class, 'index'])->name('index');
        Route::get('/download/{ministerialQuestion}', [MinisterialYearsQuestionController::class, 'download'])->name('download');
        Route::get('/subjects-by-category', [MinisterialYearsQuestionController::class, 'getSubjectsByCategory'])->name('subjects-by-category');
    });

    Route::get('/grades-basic-programm', [ElementaryProgrammController::class, 'grades_basic_programm'])->name('grades_basic-programm');
    Route::get('/grade/{grade}/{slug?}', [ElementaryProgrammController::class, 'grade_programm'])->name('grade');

    Route::group(['prefix'=>'tawjihi'], function () {
        Route::get('/tawjihi-programm',      [TawjihiController::class, 'tawjihi_programm'])->name('tawjihi-programm');
        Route::get('/tawjihi-first-year/{slug?}', [TawjihiController::class, 'tawjihi_first_year'])->name('tawjihi-first-year');
        Route::get('/tawjihi-last-year-fields/{slug?}', [TawjihiController::class, 'tawjihi_grade_last_year_fields'])->name('tawjihi-grade-year-fields');
        Route::get('/tawjihi-last-year-field/{field}/{slug?}', [TawjihiController::class, 'tawjihi_last_year_field'])->name('tawjihi-grade-field');
        Route::get('/tawjihi-last-year/{slug?}', [TawjihiController::class, 'tawjihi_last_year_fields'])->name('tawjihi-grade-year');
    });

    Route::group(['prefix'=>'video-progress'], function () {
        Route::post('/update', [VideoProgressController::class, 'updateVideoProgress'])
        ->name('video.progress.update');
        Route::post('/mark-as-complete', [VideoProgressController::class, 'markVideoComplete'])
        ->name('video.progress.complete');
    });

    Route::get('/student-account', [StudentAccountController::class, 'index'])->name('student.account');

    Route::group(['prefix' => 'cart'], function () {
        Route::get('/', [EnrollmentController::class, 'index'])->name('checkout');
        Route::post('/add-to-session', [EnrollmentController::class, 'addToSession'])->name('add.to.session');
        Route::get('/courses-count', [EnrollmentController::class, 'getCoursesCount'])->name('courses.count');
        Route::post('/activate-card', [EnrollmentController::class, 'activateCard'])->name('activate.card');
        Route::post('/payment-for-course-with-card', [EnrollmentController::class, 'paymentForCourseWithCard'])->name('payment.card');
        Route::post('/payment-for-package-with-card', [EnrollmentController::class, 'paymentForPackageWithCard'])->name('payment.package.card');
        Route::post('/remove-course', [EnrollmentController::class, 'removeCourseFromCart'])->name('remove.course');
        Route::post('/remove-package', [EnrollmentController::class, 'removeCartFromAnyPackage'])->name('remove.package');
        Route::post('/remove-course-from-package', [EnrollmentController::class, 'removeCourseFromPackage'])->name('remove.course.from.package');
        Route::post('/package/update', [EnrollmentController::class, 'updatePackageCart'])->name('cart.package.update');
        Route::get('/package/get', [EnrollmentController::class, 'getPackageCart'])->name('cart.package.get');
    });

    // packages routes
    Route::group(['prefix' => 'packages'], function () {
        Route::get('/{programm?}', [PackageAndOfferController::class, 'index'])->name('packages-offers');
        Route::get('/show/{package?}/{clas?}', [PackageAndOfferController::class, 'show'])->name('package');
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

    Route::prefix('page')->name('page.')->group(function () {
        Route::get('/privacy-policy', [PagesController::class, 'privacyPolicy'])->name('privacy-policy');
        Route::get('/terms-conditions', [PagesController::class, 'termsConditions'])->name('terms-conditions');
    });

    Route::group(['middleware' => 'auth:user'], function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('user.logout');
        Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
        Route::get('/profile/edit', [ProfileController::class, 'editProfile'])->name('profile.edit');
        Route::put('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
        Route::post('/community', [CommunityController::class, 'store'])->name('community.store');
        Route::post('/community/posts/{post}/comments', [CommunityController::class, 'storeComment'])->name('community.comments.store');
        Route::post('/community/posts/{post}/toggle-like', [CommunityController::class, 'toggleLike'])->name('community.posts.toggle-like');
        Route::post('/teacher/{teacher}/toggle-follow', [TeacherController::class, 'toggleFollow'])->name('teacher.toggle-follow');

    });

    // Community routes
    Route::get('/community', [CommunityController::class, 'index'])->name('community');
    // Public routes
    Route::get('/community/posts/{post}/comments', [CommunityController::class, 'loadMoreComments'])->name('community.posts.comments');

    Route::middleware('guest:user')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('user.login');
        Route::get('/register', [AuthController::class, 'showRegister'])->name('user.register');
        Route::post('/login', [AuthController::class, 'login'])->name('user.login.submit');
        Route::post('/register', [AuthController::class, 'register'])->name('user.register.submit');
        Route::post('/password/reset', [AuthController::class, 'resetPassword'])->name('password.reset');

          // AJAX routes for parent registration
        Route::post('/search-student', [AuthController::class, 'searchStudent'])->name('user.search.student');
        Route::get('/available-students', [AuthController::class, 'getAvailableStudents'])->name('user.available.students');
    });
});
    
