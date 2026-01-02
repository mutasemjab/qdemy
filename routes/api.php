<?php

namespace App\Http\Controllers\Api\v1\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// User Controllers
use App\Http\Controllers\Api\v1\User\AuthController;
use App\Http\Controllers\Api\v1\User\BannerController;
use App\Http\Controllers\Api\v1\User\SettingController;
use App\Http\Controllers\Api\v1\User\HomeController;
use App\Http\Controllers\Api\v1\User\PosController;
use App\Http\Controllers\Api\v1\User\NotificationUserController;

// Teacher Controllers
use App\Http\Controllers\Api\v1\Teacher\AuthTeacherController;
use App\Http\Controllers\Api\v1\Teacher\DashboardTeacherController;
use App\Http\Controllers\Api\v1\Teacher\CourseTeacherController;
use App\Http\Controllers\Api\v1\Teacher\ExamTeacherController;
use App\Http\Controllers\Api\v1\Teacher\NotificationTeacherController;

// Parent Controllers
use App\Http\Controllers\Api\v1\Parent\AuthParentController;
use App\Http\Controllers\Api\v1\Parent\DashboardParentController;
use App\Http\Controllers\Api\v1\Parent\NotificationParentController;
use App\Http\Controllers\Api\v1\Parent\ParentChildAcademicController;
use App\Http\Controllers\Api\v1\Teacher\CourseSectionTeacherController;
use App\Http\Controllers\Api\v1\Teacher\ExamQuestionsTeacherController;
use App\Http\Controllers\Api\v1\NotificationController;
use App\Http\Controllers\BunnyUploadController;
use App\Http\Controllers\Web\CardController;
use App\Http\Controllers\Web\DoseyatController;
use App\Http\Controllers\Web\ExamController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// ================================
// STUDENT/USER ROUTES
// ================================
Route::group(['prefix' => 'v1/user'], function () {

    Route::get('/payment-flag', [AuthController::class, 'flagPaymentForAppstore']);

    Route::get('/cards-order', [CardController::class, 'cards_order']);
    Route::get('/doseyat', [DoseyatController::class, 'doseyat']);


    Route::get('/getSubjectsFromCategory', [CategoryController::class, 'getSubjectsFromCategory']); // api to get the subject from all categories

    // Public routes
    Route::get('/classes', [AuthController::class, 'getClasses']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/banners', [BannerController::class, 'index']);
    Route::get('/settings', [SettingController::class, 'index']);
    Route::get('/onboardings', [OnBoardingController::class, 'index']);
    Route::get('/teachers', [TeacherController::class, 'index']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('/resend-otp', [AuthController::class, 'resendOtp']);

    Route::post('/check-phone-reset', [ForgotPasswordController::class, 'checkPhoneForReset']);
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);

    Route::prefix('courses')->group(function () {
        Route::get('/', [CourseController::class, 'index']);
        Route::get('/subject/{subjectId}', [CourseController::class, 'coursesBySubject']);
        Route::get('/international-program/{program?}', [CourseController::class, 'internationalProgramCourses']);
        Route::get('/universities-program', [CourseController::class, 'universitiesProgramCourses']);
    });

    Route::prefix('categories')->group(function () {
        Route::get('/grades/elementary', [CategoryController::class, 'getElementaryGrades']);
        Route::get('/grades/{gradeId}/semesters', [CategoryController::class, 'getGradeSemesters']);
        Route::get('/grades/tawjihi', [CategoryController::class, 'getTawjihiGrades']);
        Route::get('/tawjihi/final-grade-fields', [CategoryController::class, 'getTawjihiFinalGradeFields']);
        Route::get('/tawjihi/first-grade-fields', [CategoryController::class, 'getTawjihiFirstYear']);
        Route::get('/international-program', [CategoryController::class, 'getInternationalPrograms']);
        Route::get('/universities-program', [CategoryController::class, 'getUniversitiesProgram']);
    });

    Route::get('/pos', [PosController::class, 'index']);
    Route::get('/home', HomeController::class);

    Route::group(['middleware' => ['auth:user-api']], function () {
        Route::prefix('progress')->group(function () {
            // Get progress for a specific course
            Route::get('/courses/{courseId}', [ProgressController::class, 'getCourseProgress']);

            // Get detailed progress (videos + exams breakdown)
            Route::get('/courses/{courseId}/detailed', [ProgressController::class, 'getCourseDetailedProgress']);

            // Update video watch progress
            Route::post('/video/update', [ProgressController::class, 'updateVideoProgress']);

            // Mark content (PDF, etc.) as completed
            Route::post('/content/complete', [ProgressController::class, 'markContentCompleted']);
        });


        Route::prefix('notifications')->group(function () {
            // Send notification to a single user
            Route::post('/send-to-user', [NotificationController::class, 'sendToUser']);
        });

        Route::post('/follow/toggle', [FollowController::class, 'toggleFollow']);
        Route::get('/teachers/{teacherId}', [TeacherController::class, 'show']);

        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/update-profile', [AuthController::class, 'updateProfile']);
        Route::delete('/delete-account', [AuthController::class, 'deleteAccount']);

        // Community features
        Route::apiResource('posts', PostController::class);
        Route::get('posts/{post}/comments', [CommentController::class, 'index']);
        Route::post('posts/{post}/comments', [CommentController::class, 'store']);
        Route::delete('posts/comments/{id}', [CommentController::class, 'destroy']);
        Route::post('posts/{post}/like', [LikeController::class, 'like']);
        Route::delete('posts/{post}/unlike', [LikeController::class, 'unlike']);
        Route::get('posts/{post}/likes', [LikeController::class, 'index']);

        Route::get('/courses/{course}/{slug?}', [CourseController::class, 'show']);

        Route::get('/bank-question', [BankQuestionsController::class, 'getBankQuestion']);

        Route::get('/ministerial-year-question', [BankQuestionsController::class, 'getMinisterialYearQuestion']);


        // Cart & Payment
        Route::get('/cart', [EnrollmentController::class, 'index']);
        Route::post('/cart/add', [EnrollmentController::class, 'addToSession']);
        Route::post('/cart/package/update', [EnrollmentController::class, 'updatePackageCart']);
        Route::delete('/cart/remove-course', [EnrollmentController::class, 'removeCourseFromCart']);
        Route::delete('/cart/clear', [EnrollmentController::class, 'clearCart']);

        // Payment & Enrollment
        Route::post('/payment/course/card', [EnrollmentController::class, 'paymentForCourseWithCard']);
        Route::post('/payment/package/card', [EnrollmentController::class, 'paymentForPackageWithCard']);
        Route::get('/enrolled-courses', [EnrollmentController::class, 'getUserEnrolledCourses']);


        // Packages
        Route::prefix('packages')->group(function () {
            Route::get('/', [PackageAndOfferController::class, 'index'])->name('api.packages.index');
            Route::get('/{package}/details', [PackageAndOfferController::class, 'show'])->name('api.packages.show');
        });


        Route::prefix('notifications')->group(function () {
            Route::get('/', [NotificationUserController::class, 'index']);
            Route::get('/unread-count', [NotificationUserController::class, 'getUnreadCount']);
            Route::post('/{notification}/read', [NotificationUserController::class, 'markAsRead']);
            Route::post('/mark-all-read', [NotificationUserController::class, 'markAllAsRead']);
        });
    });
});

// ================================
// TEACHER ROUTES
// ================================
Route::group(['prefix' => 'v1/teacher'], function () {

    // Public routes
    Route::post('/login', [AuthTeacherController::class, 'login']);
    Route::post('/register', [AuthTeacherController::class, 'register']);
    Route::post('/forgot-password', [AuthTeacherController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthTeacherController::class, 'resetPassword']);
    Route::get('/subjects', [AuthTeacherController::class, 'getSubjects']);
    Route::get('/teaching-levels', [AuthTeacherController::class, 'getTeachingLevels']);

    // Protected routes (same auth middleware, but with role check in controllers)
    Route::group(['middleware' => ['auth:user-api']], function () {

        // Profile & Auth
        Route::get('/profile', [AuthTeacherController::class, 'profile']);
        Route::post('/update-profile', [AuthTeacherController::class, 'updateProfile']);
        Route::post('/change-password', [AuthTeacherController::class, 'changePassword']);
        Route::post('/logout', [AuthTeacherController::class, 'logout']);
        Route::delete('/delete-account', [AuthTeacherController::class, 'deleteAccount']);

        // Dashboard
        Route::get('/Teacherdashboard', [DashboardTeacherController::class, 'index']);

        // Course Management
        Route::prefix('courses')->group(function () {
            Route::get('/', [CourseTeacherController::class, 'index']);
            Route::post('/', [CourseTeacherController::class, 'store']);
            Route::get('/{course}', [CourseTeacherController::class, 'show']);
            Route::post('/{course}', [CourseTeacherController::class, 'update']);
            Route::delete('/{course}', [CourseTeacherController::class, 'destroy']);

            // Course enrollment
            Route::get('/{course}/students', [CourseTeacherController::class, 'getEnrolledStudents']);

            // Course Sections & Contents Management
            Route::get('/{course}/sections', [CourseSectionTeacherController::class, 'index']);
            Route::post('/{course}/sections', [CourseSectionTeacherController::class, 'store']);
            Route::get('/{course}/sections/{section}', [CourseSectionTeacherController::class, 'show']);
            Route::put('/{course}/sections/{section}', [CourseSectionTeacherController::class, 'update']);
            Route::delete('/{course}/sections/{section}', [CourseSectionTeacherController::class, 'destroy']);

            // Content Management
            Route::post('/{course}/contents', [CourseSectionTeacherController::class, 'storeContent']);
            Route::get('/{course}/contents', [CourseSectionTeacherController::class, 'getContents']);
            Route::get('/{course}/contents/{content}', [CourseSectionTeacherController::class, 'showContent']);
            Route::put('/{course}/contents/{content}', [CourseSectionTeacherController::class, 'updateContent']);
            Route::delete('/{course}/contents/{content}', [CourseSectionTeacherController::class, 'destroyContent']);


            // Statistics
            Route::get('/{course}/statistics', [CourseSectionTeacherController::class, 'getCourseStatistics']);
        });


        // Exam Management
        Route::prefix('exams')->group(function () {
            Route::get('/', [ExamTeacherController::class, 'index']);
            Route::post('/', [ExamTeacherController::class, 'store']);
            Route::get('/{exam}', [ExamTeacherController::class, 'show']);
            Route::put('/{exam}', [ExamTeacherController::class, 'update']);
            Route::delete('/{exam}', [ExamTeacherController::class, 'destroy']);

            // Exam Results & Analytics
            Route::get('/{exam}/results', [ExamTeacherController::class, 'getResults']);
            Route::get('/{exam}/attempts/{attempt}', [ExamTeacherController::class, 'viewAttempt']);

            // Exam Questions Management
            Route::get('/{exam}/questions', [ExamQuestionsTeacherController::class, 'index']);
            Route::post('/{exam}/questions', [ExamQuestionsTeacherController::class, 'store']);
            Route::put('/{exam}/questions/update-order', [ExamQuestionsTeacherController::class, 'updateQuestions']);
            Route::get('/{exam}/questions/{question}', [ExamQuestionsTeacherController::class, 'show']);
            Route::put('/{exam}/questions/{question}', [ExamQuestionsTeacherController::class, 'update']);
            Route::delete('/{exam}/questions/{question}/remove', [ExamQuestionsTeacherController::class, 'removeQuestion']);
        });


        // Notifications
        Route::prefix('notifications')->group(function () {
            Route::get('/', [NotificationTeacherController::class, 'index']);
            Route::get('/unread-count', [NotificationTeacherController::class, 'getUnreadCount']);
            Route::post('/{notification}/read', [NotificationTeacherController::class, 'markAsRead']);
            Route::post('/mark-all-read', [NotificationTeacherController::class, 'markAllAsRead']);
        });
    });
});


// ================================
// PARENT ROUTES
// ================================
Route::group(['prefix' => 'v1/parent'], function () {

    // Public routes
    Route::post('/login', [AuthParentController::class, 'login']);
    Route::post('/register', [AuthParentController::class, 'register']);
    Route::post('/forgot-password', [AuthParentController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthParentController::class, 'resetPassword']);
    Route::post('/verify-otp', [AuthParentController::class, 'verifyOtp']);
    Route::post('/resend-otp', [AuthParentController::class, 'resendOtp']);

    // Protected routes (same auth middleware, but with role check in controllers)
    Route::group(['middleware' => ['auth:user-api']], function () {

        // Profile & Auth
        Route::get('/profile', [AuthParentController::class, 'profile']);
        Route::post('/update-profile', [AuthParentController::class, 'updateProfile']);
        Route::post('/change-password', [AuthParentController::class, 'changePassword']);
        Route::post('/logout', [AuthParentController::class, 'logout']);
        Route::delete('/delete-account', [AuthParentController::class, 'deleteAccount']);

        // Children Management
        Route::post('/search-student', [AuthParentController::class, 'searchStudent']);
        Route::post('/add-child', [AuthParentController::class, 'addChild']);
        Route::delete('/remove-child', [AuthParentController::class, 'removeChild']);
        Route::get('/children', [AuthParentController::class, 'getChildren']);

        // Dashboard
        Route::get('/dashboard', [DashboardParentController::class, 'index']);
        Route::get('/statistics', [DashboardParentController::class, 'getStatistics']);



        Route::get('/children/academic-summary', [ParentChildAcademicController::class, 'getAllChildrenAcademicSummary']);
        Route::get('/child/{childId}/courses', [ParentChildAcademicController::class, 'getChildCourses']);
        Route::get('/child/{childId}/exam-results', [ParentChildAcademicController::class, 'getChildExamResults']);
        Route::get('/child/{childId}/academic-overview', [ParentChildAcademicController::class, 'getChildAcademicOverview']);



        // Notifications
        Route::prefix('notifications')->group(function () {
            Route::get('/', [NotificationParentController::class, 'index']);
            Route::get('/unread-count', [NotificationParentController::class, 'getUnreadCount']);
            Route::post('/{notification}/read', [NotificationParentController::class, 'markAsRead']);
            Route::post('/mark-all-read', [NotificationParentController::class, 'markAllAsRead']);
        });
    });
});


// exam routes starts
Route::get('v1/exam/', [ExamController::class, 'index'])->name(API_ROUTE_PREFIX . 'exam.index');
Route::get('v1/exam/{exam}/{slug?}', [ExamController::class, 'show'])->name(API_ROUTE_PREFIX . 'exam');

Route::prefix('v1/exam')
    ->middleware(['web'])
    ->name(API_ROUTE_PREFIX . 'exam.')  // Add 'exam.' here to match web routes
    ->group(function () {
        Route::post('/{exam}/start', [ExamController::class, 'start_exam'])->name('start'); // This becomes 'api.v1.exam.start'
        Route::get('/{exam}/take', [ExamController::class, 'take'])->name('take');
        Route::post('/{exam}/question/{question}/answer', [ExamController::class, 'answer_question'])->name('answer.question');
        Route::post('/{exam}/finish', [ExamController::class, 'finish_exam'])->name('finish');
        Route::get('/{exam}/attempt/{attempt}/review', [ExamController::class, 'review_attempt'])->name('review.attempt');
    });


Route::post('/bunny/sign-upload', [BunnyUploadController::class, 'sign']);
