<?php

namespace App\Traits;

use App\Models\Post;
use App\Models\Comment;
use App\Models\PostLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait HasCommunity
{
    /**
     * Get community posts for display
     */
    public function getCommunityPosts($limit = 10)
    {
        return Post::with(['user', 'comments.user', 'comments.replies.user', 'likes'])
            ->where('is_approved', true)
            ->where('is_active', true)
            ->where('user_id',auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Handle creating a new post (trait method)
     */
    protected function handleCreatePost(Request $request)
    {
        try {
            Log::info('Creating post via trait', ['user_id' => Auth::id(), 'content' => $request->content]);
            
            $request->validate([
                'content' => 'required|string|max:1000',
            ]);

            $post = Post::create([
                'content' => $request->content,
                'user_id' => Auth::id(),
                'is_approved' => true,
                'is_active' => true,
            ]);

            Log::info('Post created successfully via trait', ['post_id' => $post->id]);

            return response()->json([
                'success' => true,
                'message' => __('panel.post_created_successfully'),
                'post' => $post
            ]);

        } catch (\Exception $e) {
            Log::error('Error creating post via trait', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error creating post: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle toggling like on a post (trait method)
     */
    protected function handleToggleLike(Request $request)
    {
        try {
            $request->validate([
                'post_id' => 'required|exists:posts,id',
            ]);

            $userId = Auth::id();
            $postId = $request->post_id;

            $existingLike = PostLike::where('user_id', $userId)
                ->where('post_id', $postId)
                ->first();

            if ($existingLike) {
                $existingLike->delete();
                $liked = false;
            } else {
                PostLike::create([
                    'user_id' => $userId,
                    'post_id' => $postId,
                ]);
                $liked = true;
            }

            $likesCount = PostLike::where('post_id', $postId)->count();

            return response()->json([
                'success' => true,
                'liked' => $liked,
                'likes_count' => $likesCount
            ]);

        } catch (\Exception $e) {
            Log::error('Error toggling like via trait', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Error toggling like: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle adding comment to a post (trait method)
     */
    protected function handleAddComment(Request $request)
    {
        try {
            $request->validate([
                'post_id' => 'required|exists:posts,id',
                'content' => 'required|string|max:500',
            ]);

            $comment = Comment::create([
                'content' => $request->content,
                'user_id' => Auth::id(),
                'post_id' => $request->post_id,
                'is_approved' => true,
                'is_active' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => __('panel.comment_added_successfully'),
                'comment' => $comment
            ]);

        } catch (\Exception $e) {
            Log::error('Error adding comment via trait', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Error adding comment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's avatar URL
     */
    protected function getUserAvatarUrl($user)
    {
        if ($user->photo) {
            return asset('assets/admin/uploads/' . $user->photo);
        }
        return asset('assets_front/images/Profile-picture.jpg');
    }

    /**
     * Handle adding reply to a comment (trait method)
     */
    protected function handleAddReply(Request $request)
    {
        try {
            $request->validate([
                'comment_id' => 'required|exists:comments,id',
                'content' => 'required|string|max:500',
            ]);

            // Check if comment_id is a parent comment (not a reply)
            $parentComment = Comment::where('id', $request->comment_id)
                ->whereNull('parent_id')
                ->first();

            if (!$parentComment) {
                return response()->json([
                    'success' => false,
                    'message' => __('panel.cannot_reply_to_reply')
                ], 400);
            }

            $reply = Comment::create([
                'content' => $request->content,
                'user_id' => Auth::id(),
                'post_id' => $parentComment->post_id,
                'parent_id' => $parentComment->id,
                'is_approved' => true,
                'is_active' => true,
            ]);

            // Load relationships for response
            $reply->load('user');

            return response()->json([
                'success' => true,
                'message' => __('panel.reply_added_successfully'),
                'reply' => $reply
            ]);

        } catch (\Exception $e) {
            Log::error('Error adding reply via trait', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Error adding reply: ' . $e->getMessage()
            ], 500);
        }
    }
}