<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostLike;
use Illuminate\Http\Request;
use App\Traits\Responses;

class LikeController extends Controller
{
    use Responses;

    /**
     * Like a post
     */
    public function like($postId)
    {
        $post = Post::find($postId);
        if (!$post) {
            return $this->error_response(__('Post not found'), null);
        }

        $userId = auth()->id();

        if ($post->isLikedBy($userId)) {
            return $this->error_response(__('You already liked this post'), null);
        }

        $like = PostLike::create([
            'post_id' => $post->id,
            'user_id' => $userId,
        ]);

        return $this->success_response(__('Post liked successfully'), $like);
    }

    /**
     * Unlike a post
     */
    public function unlike($postId)
    {
        $post = Post::find($postId);
        if (!$post) {
            return $this->error_response(__('Post not found'), null);
        }

        $userId = auth()->id();

        $like = PostLike::where('post_id', $post->id)->where('user_id', $userId)->first();

        if (!$like) {
            return $this->error_response(__('You did not like this post'), null);
        }

        $like->delete();

        return $this->success_response(__('Post unliked successfully'), null);
    }

    /**
     * Get all likes for a post
     */
    public function index($postId)
    {
        $post = Post::with('likes.user')->find($postId);
        if (!$post) {
            return $this->error_response(__('Post not found'), null);
        }

        return $this->success_response(__('Likes fetched successfully'), $post->likes);
    }
}
