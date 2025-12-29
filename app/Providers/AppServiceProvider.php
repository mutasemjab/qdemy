<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\FirebaseChatService;
use Kreait\Firebase\Factory;
use App\Models\{Post, Comment};
use App\Observers\{PostObserver, CommentObserver};
use App\Models\Course;
use App\Models\Exam;
use App\Models\Doseyat;
use App\Observers\CourseObserver;
use App\Observers\ExamObserver;
use App\Observers\DoseyatObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Your existing registrations...
        
        // Add Firebase Chat Service registration
        $this->app->singleton(FirebaseChatService::class, function ($app) {
            return new FirebaseChatService();
        });
        
        $this->app->singleton('firebase.factory', function ($app) {
            $factory = new Factory();
            
            // Use service account file
            if (config('firebase.credentials')) {
                $factory = $factory->withServiceAccount(config('firebase.credentials'));
            }
            
            // Use service account JSON string
            if (config('firebase.credentials_json')) {
                $factory = $factory->withServiceAccount(json_decode(config('firebase.credentials_json'), true));
            }
            
            return $factory->withProjectId(config('firebase.project_id'));
        });
        
        $this->app->singleton('firebase.firestore', function ($app) {
            return $app->make('firebase.factory')->createFirestore()->database();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         // Set the default pagination view to Bootstrap 4
        \Illuminate\Pagination\Paginator::useBootstrapFour();

         Course::observe(CourseObserver::class);
         Exam::observe(ExamObserver::class);
         Doseyat::observe(DoseyatObserver::class);
         Post::observe(PostObserver::class);
         Comment::observe(CommentObserver::class);
    }
}