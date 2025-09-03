<?php

use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\Admin\POSController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CardController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ParentController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\ContactUsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CardNumberController;
use App\Http\Controllers\Admin\OnBoardingController;
use App\Http\Controllers\Admin\BankQuestionController;
use App\Http\Controllers\Admin\SpecialQdemyController;
use App\Http\Controllers\Admin\CourseSectionController;
use App\Http\Controllers\Admin\OpinionStudentController;
use App\Http\Controllers\Admin\QuestionWebsiteController;
use App\Http\Controllers\Admin\WalletTransactionController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\Admin\MinisterialYearsQuestionController;
use App\Http\Controllers\Admin\NotificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

define('PAGINATION_COUNT', 11);
Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']], function () {



    Route::resource('notifications', NotificationController::class);

    Route::post('notifications/{notification}/resend', [NotificationController::class, 'resend'])
        ->name('notifications.resend');

    Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function () {
        Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('logout', [LoginController::class, 'logout'])->name('admin.logout');


        /*         start  update login admin                 */
        Route::get('/admin/edit/{id}', [LoginController::class, 'editlogin'])->name('admin.login.edit');
        Route::post('/admin/update/{id}', [LoginController::class, 'updatelogin'])->name('admin.login.update');
        /*         end  update login admin                */

        /// Role and permission
        Route::resource('employee', 'App\Http\Controllers\Admin\EmployeeController', ['as' => 'admin']);
        Route::get('role', 'App\Http\Controllers\Admin\RoleController@index')->name('admin.role.index');
        Route::get('role/create', 'App\Http\Controllers\Admin\RoleController@create')->name('admin.role.create');
        Route::get('role/{id}/edit', 'App\Http\Controllers\Admin\RoleController@edit')->name('admin.role.edit');
        Route::patch('role/{id}', 'App\Http\Controllers\Admin\RoleController@update')->name('admin.role.update');
        Route::post('role', 'App\Http\Controllers\Admin\RoleController@store')->name('admin.role.store');
        Route::post('admin/role/delete', 'App\Http\Controllers\Admin\RoleController@delete')->name('admin.role.delete');

        Route::get('/permissions/{guard_name}', function ($guard_name) {
            return response()->json(Permission::where('guard_name', $guard_name)->get());
        });


        Route::prefix('pages')->group(function () {
            Route::get('/', [PageController::class, 'index'])->name('pages.index');
            Route::get('/create', [PageController::class, 'create'])->name('pages.create');
            Route::post('/store', [PageController::class, 'store'])->name('pages.store');
            Route::get('/edit/{id}', [PageController::class, 'edit'])->name('pages.edit');
            Route::put('/update/{id}', [PageController::class, 'update'])->name('pages.update');
            Route::delete('/delete/{id}', [PageController::class, 'destroy'])->name('pages.destroy');
        });



        //Reports


        //  End Report




        // Resource Route
        Route::resource('contactUs', ContactUsController::class);
        Route::resource('onboardings', OnBoardingController::class);
        Route::resource('special-qdemies', SpecialQdemyController::class);
        Route::resource('users', UserController::class);
        Route::resource('banners', BannerController::class);
        Route::resource('settings', SettingController::class);
        Route::resource('teachers', TeacherController::class);
        Route::resource('questionWebsites', QuestionWebsiteController::class);
        Route::resource('opinions', OpinionStudentController::class);
        Route::resource('parents', ParentController::class);
        Route::delete('parents/{parent}/students/{student}', [ParentController::class, 'removeStudent'])->name('parents.remove-student');
        Route::post('parents/{parent}/students', [ParentController::class, 'addStudent'])->name('parents.add-student');

        // بداية المواد والتقسميات
        Route::resource('categories', CategoryController::class);

        // Additional category routes
        Route::get('categories/tree/{parent_id?}', [CategoryController::class, 'tree'])
            ->name('categories.tree');

        Route::patch('categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])
            ->name('categories.toggle-status');

        Route::get('categories/subcategories/{parent_id?}', [CategoryController::class, 'getSubcategories'])
            ->name('categories.subcategories');

        Route::post('categories/bulk-action', [CategoryController::class, 'bulkAction'])
            ->name('categories.bulk-action');

        Route::resource('subjects', SubjectController::class);
        Route::post('subjects/getGrades', [SubjectController::class, 'getGrades'])->name('admin.subjects.getGrades');
        Route::post('subjects/getSemesters', [SubjectController::class, 'getSemesters'])->name('admin.subjects.getSemesters');
        Route::post('subjects/getFields', [SubjectController::class, 'getFields'])->name('admin.subjects.getFields');

        Route::post('subjects/{subject}/toggle-status', [SubjectController::class, 'toggleStatus'])->name('subjects.toggleStatus');
        Route::post('subjects/{subject}/move-up', [SubjectController::class, 'moveUp'])->name('subjects.moveUp');
        Route::post('subjects/{subject}/move-down', [SubjectController::class, 'moveDown'])->name('subjects.moveDown');
        // end المواد والتقسيمات



        Route::resource('blogs', BlogController::class);
        Route::resource('bank-questions', BankQuestionController::class);
        Route::get('bank-question/{bankQuestion}/download', [BankQuestionController::class, 'downloadPdf'])->name('bank-questions.download');
        Route::get('/bank-questions/{parentId}/children', [BankQuestionController::class, 'getChildCategories'])->name('bank-questions.get-children');
        Route::resource('ministerial-questions', MinisterialYearsQuestionController::class);
        Route::get('/ministerial-categories/{parentId}/children', [MinisterialYearsQuestionController::class, 'getChildCategories'])->name('ministerial-questions.get-children');

        Route::resource('pos', POSController::class);
        Route::resource('cards', CardController::class);

        // Additional Cards Routes
        Route::post('cards/{card}/regenerate-numbers', [CardController::class, 'regenerateNumbers'])->name('cards.regenerate-numbers');
        Route::get('cards/{card}/card-numbers', [CardController::class, 'showNumbers'])->name('cards.card-numbers');

        // Card Numbers Routes
        Route::patch('card-numbers/{cardNumber}/toggle-status', [CardNumberController::class, 'toggleStatus'])->name('card-numbers.toggle-status');
        Route::patch('card-numbers/{cardNumber}/toggle-activate', [CardNumberController::class, 'toggleActivate'])->name('card-numbers.toggle-activate');


        Route::resource('courses', CourseController::class);
        Route::prefix('courses/{course}')->name('courses.')->group(function () {
            // Section routes
            Route::get('sections', [CourseSectionController::class, 'index'])->name('sections.index');
            Route::get('sections/create', [CourseSectionController::class, 'create'])->name('sections.create');
            Route::post('sections', [CourseSectionController::class, 'store'])->name('sections.store');
            Route::get('sections/{section}/edit', [CourseSectionController::class, 'edit'])->name('sections.edit');
            Route::put('sections/{section}', [CourseSectionController::class, 'update'])->name('sections.update');
            Route::delete('sections/{section}', [CourseSectionController::class, 'destroy'])->name('sections.destroy');

            // Content routes
            Route::get('contents/create', [CourseSectionController::class, 'createContent'])->name('contents.create');
            Route::post('contents', [CourseSectionController::class, 'storeContent'])->name('contents.store');
            Route::get('contents/{content}/edit', [CourseSectionController::class, 'editContent'])->name('contents.edit');
            Route::put('contents/{content}', [CourseSectionController::class, 'updateContent'])->name('contents.update');
            Route::delete('contents/{content}', [CourseSectionController::class, 'destroyContent'])->name('contents.destroy');
        });




        Route::resource('questions', QuestionController::class);
        Route::get('courses/{course}/questions', [QuestionController::class, 'getByCourse'])
            ->name('courses.questions');

        // Exam Management Routes
        Route::resource('exams', ExamController::class);

        // Exam Questions Management
        Route::prefix('exams/{exam}')->name('exams.')->group(function () {
            Route::get('questions', [ExamController::class, 'manageQuestions'])->name('questions.manage');
            Route::post('questions', [ExamController::class, 'addQuestions'])->name('questions.add');
            Route::put('questions', [ExamController::class, 'updateQuestions'])->name('questions.update');
            Route::delete('questions/{question}', [ExamController::class, 'removeQuestion'])->name('questions.remove');
            // Exam Results
            Route::get('results', [ExamController::class, 'results'])->name('results');
            Route::get('attempts/{attempt}', [ExamController::class, 'viewAttempt'])->name('attempts.view');
        });

        // Add this route for question details
        Route::get('questions/{question}/details', [ExamController::class, 'getQuestionDetails'])->name('questions.details');
     
     
        Route::post('courses/sections-ajax/{course?}', [ExamController::class, 'getCourseSections'])
            ->name('sections.ajax');


        Route::prefix('community')->name('admin.community.')->group(function () {

            // Posts Management
            Route::prefix('posts')->name('posts.')->group(function () {
                Route::get('/', [App\Http\Controllers\Admin\CommunityController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\Admin\CommunityController::class, 'create'])->name('create');
                Route::post('/', [App\Http\Controllers\Admin\CommunityController::class, 'store'])->name('store');
                Route::get('/{post}', [App\Http\Controllers\Admin\CommunityController::class, 'show'])->name('show');
                Route::get('/{post}/edit', [App\Http\Controllers\Admin\CommunityController::class, 'edit'])->name('edit');
                Route::put('/{post}', [App\Http\Controllers\Admin\CommunityController::class, 'update'])->name('update');
                Route::delete('/{post}', [App\Http\Controllers\Admin\CommunityController::class, 'destroy'])->name('destroy');
                Route::get('/{post}/approve', [App\Http\Controllers\Admin\CommunityController::class, 'approve'])->name('approve');
                Route::get('/{post}/reject', [App\Http\Controllers\Admin\CommunityController::class, 'reject'])->name('reject');
                Route::get('/{post}/toggle-status', [App\Http\Controllers\Admin\CommunityController::class, 'toggleStatus'])->name('toggle-status');
            });

            // Comments Management
            Route::prefix('comments')->name('comments.')->group(function () {
                Route::get('/', [App\Http\Controllers\Admin\CommunityController::class, 'comments'])->name('index');
                Route::get('/{comment}', [App\Http\Controllers\Admin\CommunityController::class, 'showComment'])->name('show');
                Route::get('/{comment}/details', [App\Http\Controllers\Admin\CommunityController::class, 'commentDetails'])->name('details');
                Route::get('/{comment}/approve', [App\Http\Controllers\Admin\CommunityController::class, 'approveComment'])->name('approve');
                Route::get('/{comment}/reject', [App\Http\Controllers\Admin\CommunityController::class, 'rejectComment'])->name('reject');
                Route::delete('/{comment}', [App\Http\Controllers\Admin\CommunityController::class, 'destroyComment'])->name('destroy');
                Route::get('/{comment}/toggle-status', [App\Http\Controllers\Admin\CommunityController::class, 'toggleCommentStatus'])->name('toggle-status');
            });
        });



       



        Route::resource('wallet_transactions', WalletTransactionController::class);
        Route::resource('packages', PackageController::class);
        Route::patch('packages/{package}/toggle-status', [PackageController::class, 'toggleStatus'])->name('packages.toggle-status');
        Route::post('packages/bulk-action', [PackageController::class, 'bulkAction'])->name('packages.bulk-action');
        Route::get('packages/categories/by-type', [PackageController::class, 'getCategoriesByType'])->name('packages.get-categories-by-type');
   
   
  
        // Ajax
        Route::get('admin/subjects/{subject}/courses', [ExamController::class, 'getSubjectCourses'])
            ->name('admin.subjects.courses');

        Route::get('admin/courses/{course}/sections', [ExamController::class, 'getCourseSections'])
            ->name('admin.courses.sections');
   
    });
});



Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => 'guest:admin'], function () {
    Route::get('login', [LoginController::class, 'show_login_view'])->name('admin.showlogin');
    Route::post('login', [LoginController::class, 'login'])->name('admin.login');
});
