<?php

namespace App\Observers;

use App\Models\Post;
use App\Services\ContentModerationService;
use Illuminate\Support\Facades\Log;

class PostObserver
{
    private $moderationService;
    
    public function __construct(ContentModerationService $moderationService)
    {
        $this->moderationService = $moderationService;
    }
    
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
}