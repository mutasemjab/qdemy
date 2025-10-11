<?php

namespace App\Observers;

use App\Models\Comment;
use App\Services\ContentModerationService;

class CommentObserver
{
    private $moderationService;
    
    public function __construct(ContentModerationService $moderationService)
    {
        $this->moderationService = $moderationService;
    }
    
    public function saving(Comment $comment)
    {
        $moderation = $this->moderationService->moderate($comment->content);
        
        $comment->is_approved = $moderation['should_approve'];
        
        if (!$moderation['is_clean']) {
            $comment->moderation_flags = $moderation['violations'];
            $comment->violation_score = $moderation['violation_score'];
        }
    }
}