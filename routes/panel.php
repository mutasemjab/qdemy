<?php

use App\Http\Controllers\Panel\Teacher\TeacherController;
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
        Route::post('/update-account', [App\Http\Controllers\Panel\Student\StudentController::class, 'updateAccount'])->name('update.account');
        Route::post('/notifications/{id}/read', [App\Http\Controllers\Panel\Student\StudentController::class, 'markAsRead'])->name('notifications.read');
        
        // Community Routes
        Route::get('/community', [App\Http\Controllers\Panel\Student\StudentController::class, 'community'])->name('community');
        Route::post('/community/post', [App\Http\Controllers\Panel\Student\StudentController::class, 'createPost'])->name('create-post');
        Route::post('/community/like', [App\Http\Controllers\Panel\Student\StudentController::class, 'toggleLike'])->name('toggle-like');
        Route::post('/community/comment', [App\Http\Controllers\Panel\Student\StudentController::class, 'addComment'])->name('add-comment');
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
        Route::get('/create-course', [App\Http\Controllers\Panel\Teacher\TeacherController::class, 'createCourse'])->name('create-course');

        Route::post('/update-account', [App\Http\Controllers\Panel\Teacher\TeacherController::class, 'updateAccount'])->name('update.account');
        Route::post('/notifications/{id}/read', [App\Http\Controllers\Panel\Teacher\TeacherController::class, 'markAsRead'])->name('notifications.read');
        
        // Community Routes
        Route::get('/community', [App\Http\Controllers\Panel\Teacher\TeacherController::class, 'community'])->name('community');
        Route::post('/community/post', [App\Http\Controllers\Panel\Teacher\TeacherController::class, 'createPost'])->name('create-post');
        Route::post('/community/like', [App\Http\Controllers\Panel\Teacher\TeacherController::class, 'toggleLike'])->name('toggle-like');
        Route::post('/community/comment', [App\Http\Controllers\Panel\Teacher\TeacherController::class, 'addComment'])->name('add-comment');
    
        Route::prefix('courses')->name('courses.')->group(function () {
            Route::get('/', [TeacherController::class, 'courses'])->name('index');
            Route::get('/create', [TeacherController::class, 'createCourse'])->name('create');
            Route::post('/', [TeacherController::class, 'store'])->name('store'); // Fixed: use 'store' method
            Route::get('/{course}', [TeacherController::class, 'showCourse'])->name('show');
            Route::get('/{course}/edit', [TeacherController::class, 'editCourse'])->name('edit');
            Route::put('/{course}', [TeacherController::class, 'update'])->name('update'); // Fixed: use 'update' method
            Route::delete('/{course}', [TeacherController::class, 'destroy'])->name('destroy'); // Fixed: use 'destroy' method
            
            // AJAX Routes for course creation
            Route::get('/get-children/{id}', [TeacherController::class, 'getChildCategories'])->name('get-children');
            Route::get('/subjects-by-category', [TeacherController::class, 'getSubjectsByCategory'])->name('subjects-by-category');
            
            // Section Management
            Route::prefix('{course}/sections')->name('sections.')->group(function () {
                Route::get('/', [TeacherController::class, 'courseSections'])->name('index');
                Route::get('/create', [TeacherController::class, 'createSection'])->name('create');
                Route::post('/', [TeacherController::class, 'storeSection'])->name('store');
                Route::get('/{section}/edit', [TeacherController::class, 'editSection'])->name('edit');
                Route::put('/{section}', [TeacherController::class, 'updateSection'])->name('update');
                Route::delete('/{section}', [TeacherController::class, 'deleteSection'])->name('destroy');
            });
            
            // Content Management
            Route::prefix('{course}/contents')->name('contents.')->group(function () {
                Route::get('/create', [TeacherController::class, 'createContent'])->name('create');
                Route::post('/', [TeacherController::class, 'storeContent'])->name('store');
                Route::get('/{content}/edit', [TeacherController::class, 'editContent'])->name('edit');
                Route::put('/{content}', [TeacherController::class, 'updateContent'])->name('update');
                Route::delete('/{content}', [TeacherController::class, 'deleteContent'])->name('destroy');
            });
        });
        
        // AJAX Routes
         Route::get('/categories/{parentId}/children', [TeacherController::class, 'getChildCategories'])->name('categories.children');
         Route::get('/subjects-by-category', [TeacherController::class, 'getSubjectsByCategory'])->name('subjects.by-category');
    
    
          // Exam Management Routes - FIXED NAMING
        Route::prefix('exams')->name('exams.')->group(function () {
            Route::get('/', [TeacherController::class, 'examsMethod'])->name('index');
            Route::get('/create', [TeacherController::class, 'createExamMethod'])->name('create');
            Route::post('/', [TeacherController::class, 'storeExamMethod'])->name('store');
            Route::get('/{exam}', [TeacherController::class, 'showExamMethod'])->name('show');
            Route::get('/{exam}/edit', [TeacherController::class, 'editExamMethod'])->name('edit');
            Route::put('/{exam}', [TeacherController::class, 'updateExamMethod'])->name('update');
            Route::delete('/{exam}', [TeacherController::class, 'destroyExamMethod'])->name('destroy');
            
            // Exam-Specific Questions (like admin)
            Route::prefix('{exam}/exam-questions')->name('exam_questions.')->group(function () {
                Route::get('/', [TeacherController::class, 'examQuestionsIndex'])->name('index');
                Route::get('/create', [TeacherController::class, 'examQuestionsCreate'])->name('create');
                Route::post('/', [TeacherController::class, 'examQuestionsStore'])->name('store');
                Route::get('/{question}', [TeacherController::class, 'examQuestionsShow'])->name('show');
                Route::get('/{question}/edit', [TeacherController::class, 'examQuestionsEdit'])->name('edit');
                Route::put('/{question}', [TeacherController::class, 'examQuestionsUpdate'])->name('update');
                Route::delete('/{question}', [TeacherController::class, 'examQuestionsDestroy'])->name('destroy');
            });
            
            // Results Routes
            Route::get('/{exam}/results', [TeacherController::class, 'examResults'])->name('results');
            Route::get('/{exam}/attempts/{attempt}', [TeacherController::class, 'viewExamAttempt'])->name('attempts.view');
            
            // AJAX Routes for exam creation
            Route::get('/subjects/{subject}/courses', [TeacherController::class, 'getSubjectCoursesForExam'])->name('subjects.courses');
            Route::get('/courses/{course}/sections', [TeacherController::class, 'getCourseSectionsForExam'])->name('courses.sections');
        });
        
        
    });
    
    // General panel route that redirects based on role
    Route::get('/', [App\Http\Controllers\Panel\PanelController::class, 'index'])->name('panel.index');

 
});