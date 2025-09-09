<?php

namespace App\Http\Controllers\Api\v1\User;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\User\AuthController;
use App\Http\Controllers\Api\v1\User\BannerController;
use App\Http\Controllers\Api\v1\User\SettingController;
use App\Http\Controllers\Api\v1\User\HomeController;
use App\Http\Controllers\Api\v1\User\PosController;

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

//Route unAuth

//Route unAuth
Route::group(['prefix' => 'v1/user'], function () {

    //---------------- Auth --------------------//
    Route::get('/classes', [AuthController::class, 'getClasses']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/banners', [BannerController::class, 'index']);
    Route::get('/settings', [SettingController::class, 'index']);
    Route::get('/onboardings', [OnBoardingController::class, 'index']);
    Route::get('/teachers', [TeacherController::class, 'index']);
    Route::post('/check-phone-reset', [ForgotPasswordController::class, 'checkPhoneForReset']);
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);

    Route::prefix('courses')->group(function () {
        // Get all courses with filters
        Route::get('/', [CourseController::class, 'index']);

        // Get courses by subject
        Route::get('/subject/{subjectId}', [CourseController::class, 'coursesBySubject']);

        // Special programs
        Route::get('/international-program/{program?}', [CourseController::class, 'internationalProgramCourses']);
        Route::get('/universities-program', [CourseController::class, 'universitiesProgramCourses']);
    });

    Route::prefix('categories')->group(function () {
        // Grade types
        Route::get('/grades/elementary', [CategoryController::class, 'getElementaryGrades']);
        Route::get('/grades/{gradeId}/semesters', [CategoryController::class, 'getGradeSemesters']);
        Route::get('/grades/tawjihi', [CategoryController::class, 'getTawjihiGrades']);

        // Tawjihi specific
        Route::get('/tawjihi/final-grade-fields', [CategoryController::class, 'getTawjihiFinalGradeFields']);
        Route::get('/tawjihi/first-grade-fields', [CategoryController::class, 'getTawjihiFirstYear']);

        // Special programs
        Route::get('/international-program', [CategoryController::class, 'getInternationalPrograms']);
        Route::get('/universities-program', [CategoryController::class, 'getUniversitiesProgram']);
    });


    Route::get('/exams', [ExamController::class, 'index']);

    Route::get('/pos', [PosController::class, 'index']);

    // Auth Route
    Route::group(['middleware' => ['auth:user-api']], function () {

        Route::get('/exams/{exam}/link', [ExamController::class, 'getExamLink']);
        Route::get('/home', HomeController::class);
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/update-profile', [AuthController::class, 'updateProfile']);
        Route::delete('/delete-account', [AuthController::class, 'deleteAccount']);

        // مجتمع كيودمي
        Route::apiResource('posts', PostController::class);
        // Comments for a post
        Route::get('posts/{post}/comments', [CommentController::class, 'index']);
        Route::post('posts/{post}/comments', [CommentController::class, 'store']);
        // Comments CRUD
        Route::delete('posts/comments/{id}', [CommentController::class, 'destroy']);

        Route::post('posts/{post}/like', [LikeController::class, 'like']);
        Route::delete('posts/{post}/unlike', [LikeController::class, 'unlike']);
        Route::get('posts/{post}/likes', [LikeController::class, 'index']);

        Route::get('/courses/{course}/{slug?}', [CourseController::class, 'show']);

        // bank questions
        Route::get('/bank-question', [BankQuestionsController::class, 'getBankQuestion']);
        Route::get('/ministerial-year-question', [BankQuestionsController::class, 'getMinisterialYearQuestion']);


        // notifications
        Route::get('/notifications', [NotificationApiController::class, 'index']);
        Route::post('/notifications/{id}/read', [NotificationApiController::class, 'read']);

        // Cart management
        Route::get('/cart', [EnrollmentController::class, 'index']);
        Route::post('/cart/add', [EnrollmentController::class, 'addToSession']);
        Route::delete('/cart/remove-course', [EnrollmentController::class, 'removeCourseFromCart']);

        // Package cart management
        Route::put('/cart/package/update', [EnrollmentController::class, 'updatePackageCart']);

        // Payment methods
        Route::post('/payment/course/card', [EnrollmentController::class, 'paymentForCourseWithCard']);
        Route::post('/payment/package/card', [EnrollmentController::class, 'paymentForPackageWithCard']);



        // Packages API Routes
        Route::group(['prefix' => 'packages'], function () {

            // Public routes (no authentication required)
            Route::get('/', [PackageAndOfferController::class, 'index']);
        });
    });
});
