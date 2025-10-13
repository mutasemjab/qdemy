<?php

namespace App\Observers;

use App\Models\Post;
use App\Services\ContentModerationService;
use App\Services\FollowerNotificationService;
use Illuminate\Support\Facades\Log;

class PostObserver
{
    private $moderationService;
    
    public function __construct(ContentModerationService $moderationService)
    {
        $this->moderationService = $moderationService;
    }
    
    /**
     * Handle the Post "saving" event.
     * This runs before the post is saved to the database
     */
    public function saving(Post $post)
    {
        // 🔥 Log للتأكد من عمل Observer
        Log::info('PostObserver: saving triggered', [
            'content' => $post->content
        ]);
        
        $moderation = $this->moderationService->moderate($post->content);
        
        // 🔥 Log للنتيجة
        Log::info('PostObserver: moderation result', $moderation);
        
        // تعيين حالة الموافقة
        $post->is_approved = $moderation['should_approve'];
        
        // حفظ بيانات المخالفات
        if (!$moderation['is_clean']) {
            $post->moderation_flags = $moderation['violations'];
            $post->violation_score = $moderation['violation_score'];
        } else {
            $post->moderation_flags = null;
            $post->violation_score = 0;
        }
    }
    
    /**
     * Handle the Post "created" event.
     * This runs after the post is successfully saved to the database
     */
    public function created(Post $post)
    {
        Log::info('PostObserver: created triggered', [
            'post_id' => $post->id,
            'is_approved' => $post->is_approved,
            'is_active' => $post->is_active,
            'user_id' => $post->user_id
        ]);
        
        // Only notify followers if the post is approved and active
        if ($post->is_approved && $post->is_active && $post->user_id) {
            try {
                // Check if the user is a teacher
                $user = $post->user;
                
                if ($user && $user->role_name === 'teacher') {
                    $result = FollowerNotificationService::notifyNewPost($post);
                    
                    Log::info('PostObserver: Follower notifications sent', [
                        'post_id' => $post->id,
                        'success' => $result['success'],
                        'failure' => $result['failure'],
                        'total' => $result['total']
                    ]);
                } else {
                    Log::info('PostObserver: User is not a teacher, skipping notifications', [
                        'post_id' => $post->id,
                        'user_role' => $user?->role_name
                    ]);
                }
            } catch (\Exception $e) {
                // Don't fail the post creation if notification fails
                Log::error('PostObserver: Failed to send follower notifications', [
                    'post_id' => $post->id,
                    'error' => $e->getMessage()
                ]);
            }
        } else {
            Log::info('PostObserver: Post not approved or not active, skipping notifications', [
                'post_id' => $post->id,
                'is_approved' => $post->is_approved,
                'is_active' => $post->is_active
            ]);
        }
    }
    
    /**
     * Handle the Post "updated" event.
     * Notify followers if post becomes approved after being rejected
     */
    public function updated(Post $post)
    {
        // Check if the post was just approved (changed from not approved to approved)
        if ($post->wasChanged('is_approved') && $post->is_approved && $post->is_active && $post->user_id) {
            try {
                $user = $post->user;
                
                if ($user && $user->role_name === 'teacher') {
                    $result = FollowerNotificationService::notifyNewPost($post);
                    
                    Log::info('PostObserver: Follower notifications sent after approval', [
                        'post_id' => $post->id,
                        'success' => $result['success'],
                        'failure' => $result['failure'],
                        'total' => $result['total']
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('PostObserver: Failed to send follower notifications on update', [
                    'post_id' => $post->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}