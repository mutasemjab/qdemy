<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


Route::group([
    'prefix' => LaravelLocalization::setLocale() . '/panel',
    'middleware' => [
        'localeSessionRedirect', 
        'localizationRedirect', 
        'localeViewPath',
        'auth' // Make sure user is authenticated
    ]
], function () {
    
    // Student Panel Routes
    Route::group([
        'prefix' => 'student',
        'middleware' => 'role:student',
        'as' => 'student.'
    ], function () {
        Route::get('/dashboard', [App\Http\Controllers\Panel\Student\StudentController::class, 'dashboard'])->name('dashboard');
        Route::get('/courses', [App\Http\Controllers\Panel\Student\StudentController::class, 'courses'])->name('courses');
        Route::get('/profile', [App\Http\Controllers\Panel\Student\StudentController::class, 'profile'])->name('profile');
        // Add more student routes here
    });
    
    // Parent Panel Routes
    Route::group([
        'prefix' => 'parent',
        'middleware' => 'role:parent',
        'as' => 'parent.'
    ], function () {
        Route::get('/dashboard', [App\Http\Controllers\Panel\Parent\ParentController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [App\Http\Controllers\Panel\Parent\ParentController::class, 'profile'])->name('profile');
        Route::get('/children', [App\Http\Controllers\Panel\Parent\ParentController::class, 'children'])->name('children');
        Route::get('/child-reports', [App\Http\Controllers\Panel\Parent\ParentController::class, 'childReports'])->name('child-reports');
        Route::get('/payment-history', [App\Http\Controllers\Panel\Parent\ParentController::class, 'paymentHistory'])->name('payment-history');
        Route::get('/add-child', [App\Http\Controllers\Panel\Parent\ParentController::class, 'addChild'])->name('add-child');
        
        // AJAX routes for child management
        Route::post('/add-child-submit', [App\Http\Controllers\Panel\Parent\ParentController::class, 'addChildSubmit'])->name('add-child-submit');
        Route::delete('/remove-child', [App\Http\Controllers\Panel\Parent\ParentController::class, 'removeChild'])->name('remove-child');
        Route::get('/search-students', [App\Http\Controllers\Panel\Parent\ParentController::class, 'searchStudents'])->name('search-students');
    });
    
    // Teacher Panel Routes
    Route::group([
        'prefix' => 'teacher',
        'middleware' => 'role:teacher',
        'as' => 'teacher.'
    ], function () {
        Route::get('/dashboard', [App\Http\Controllers\Panel\Teacher\TeacherController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [App\Http\Controllers\Panel\Teacher\TeacherController::class, 'profile'])->name('profile');
        Route::get('/courses', [App\Http\Controllers\Panel\Teacher\TeacherController::class, 'courses'])->name('courses');
        Route::get('/students', [App\Http\Controllers\Panel\Teacher\TeacherController::class, 'students'])->name('students');
        Route::get('/class-schedule', [App\Http\Controllers\Panel\Teacher\TeacherController::class, 'classSchedule'])->name('class-schedule');
        Route::get('/attendance', [App\Http\Controllers\Panel\Teacher\TeacherController::class, 'attendance'])->name('attendance');
        Route::get('/grade-assignments', [App\Http\Controllers\Panel\Teacher\TeacherController::class, 'gradeAssignments'])->name('grade-assignments');
        Route::get('/student-reports', [App\Http\Controllers\Panel\Teacher\TeacherController::class, 'studentReports'])->name('student-reports');
        Route::get('/create-course', [App\Http\Controllers\Panel\Teacher\TeacherController::class, 'createCourse'])->name('create-course');
    });
    
    // General panel route that redirects based on role
    Route::get('/', [App\Http\Controllers\Panel\PanelController::class, 'index'])->name('panel.index');
});