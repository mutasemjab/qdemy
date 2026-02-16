<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\Responses;

class CommentController extends Controller
{
    use Responses;

    /**
     * List comments for a post (parent comments only with replies data)
     */
    public function index($postId)
    {
        $post = Post::find($postId);

        if (!$post) {
            return $this->error_response(__('Post not found'), null);
        }

        $comments = $post->comments()
            ->whereNull('parent_id')  // Only get parent comments, not replies
            ->with(['user', 'replies.user'])
            ->withCount('replies')
            ->latest()
            ->get();

        // Add can_delete flag and nested replies data
        $comments->transform(function ($comment) {
            $comment->can_delete = $comment->canBeDeletedBy(auth('user-api')->id());

            // Add can_delete flag to each reply
            if ($comment->relationLoaded('replies')) {
                $comment->replies->transform(function ($reply) {
                    $reply->can_delete = $reply->canBeDeletedBy(auth('user-api')->id());
                    return $reply;
                });
            }

            return $comment;
        });

        return $this->success_response(__('Comments fetched successfully'), $comments);
    }

    /**
     * Add comment to a post
     */
    public function store(Request $request, $postId)
    {
        $post = Post::find($postId);

        if (!$post) {
            return $this->error_response(__('Post not found'), null);
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->error_response(__('Validation failed'), $validator->errors());
        }

        $comment = Comment::create([
            'content' => $request->content,
            'user_id' => auth('user-api')->id(),
            'post_id' => $post->id,
        ]);

        return $this->success_response(__('Comment added successfully'), $comment);
    }

    /**
     * Delete a comment
     */
    public function destroy($id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return $this->error_response(__('Comment not found'), null);
        }

        if ($comment->user_id !== auth('user-api')->id()) {
            return $this->error_response(__('Unauthorized'), null);
        }

        $comment->delete();

        return $this->success_response(__('Comment deleted successfully'), null);
    }

    /**
     * Add a reply to a comment
     */
    public function storeReply(Request $request, Comment $comment)
    {
        // Check if comment is a parent comment (not a reply)
        if ($comment->parent_id !== null) {
            return $this->error_response(__('Cannot reply to a reply'), null);
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return $this->error_response(__('Validation failed'), $validator->errors());
        }

        $reply = Comment::create([
            'content' => $request->content,
            'user_id' => auth('user-api')->id(),
            'post_id' => $comment->post_id,
            'parent_id' => $comment->id,
            'is_approved' => true,
            'is_active' => true,
        ]);

        $reply->load('user');

        // Add can_delete flag
        $reply->can_delete = $reply->canBeDeletedBy(auth('user-api')->id());

        return $this->success_response(__('Reply added successfully'), $reply);
    }

    /**
     * Get all replies for a comment
     */
    public function getReplies(Comment $comment)
    {
        $replies = $comment->replies()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        // Add can_delete flag to each reply
        $replies->transform(function ($reply) {
            $reply->can_delete = $reply->canBeDeletedBy(auth('user-api')->id());
            return $reply;
        });

        return $this->success_response(__('Replies fetched successfully'), $replies);
    }

    /**
     * Delete a reply
     */
    public function destroyReply($id)
    {
        $reply = Comment::find($id);

        if (!$reply) {
            return $this->error_response(__('Reply not found'), null);
        }

        if ($reply->user_id !== auth('user-api')->id()) {
            return $this->error_response(__('Unauthorized'), null);
        }

        $reply->delete();

        return $this->success_response(__('Reply deleted successfully'), null);
    }
}
