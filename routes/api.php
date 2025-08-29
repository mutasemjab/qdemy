<?php

namespace App\Http\Controllers\Api\v1\User;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\User\AuthController;
use App\Http\Controllers\Api\v1\User\BannerController;
use App\Http\Controllers\Api\v1\User\SettingController;
use App\Http\Controllers\Api\v1\User\HomeController;

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
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/banners', [BannerController::class, 'index']);
    Route::get('/settings', [SettingController::class, 'index']);
    Route::get('/onboardings', [OnBoardingController::class, 'index']);
    Route::get('/teachers', [TeacherController::class, 'index']);
    Route::post('/check-phone-reset', [ForgotPasswordController::class, 'checkPhoneForReset']);
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);

    Route::prefix('courses')->group(function () {
        Route::get('/', [CourseController::class, 'index']);
        Route::get('/subject/{subjectId}', [CourseController::class, 'coursesBySubject']);
        Route::get('/international/{program?}', [CourseController::class, 'internationalProgramCourses']);
        Route::get('/universities', [CourseController::class, 'universitiesProgramCourses']);
    });

    Route::get('/exams', [ExamController::class, 'getElectronicExams']);

    // Auth Route
    Route::group(['middleware' => ['auth:user-api']], function () {

        
        Route::get('/home', HomeController::class);
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/update-profile', [AuthController::class, 'updateProfile']);
        Route::delete('/delete-account', [AuthController::class, 'deleteAccount']);


        Route::get('/courses/{course}/{slug?}', [CourseController::class, 'show']);
    });
});
   