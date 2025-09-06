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
     * List comments for a post
     */
    public function index($postId)
    {
        $post = Post::find($postId);
        
        if (!$post) {
            return $this->error_response(__('Post not found'), null);
        }
        
        $comments = $post->comments()->with('user')->latest()->get();
        
        // Add can_delete flag to each comment
        $comments->transform(function ($comment) {
            $comment->can_delete = $comment->canBeDeletedBy(auth('user-api')->id());
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
}
