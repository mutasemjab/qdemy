<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\Responses;



class PostController extends Controller
{
    use Responses;

    /**
     * List all posts
     */
    public function index()
    {
        $posts = Post::with(['user', 'comments', 'likesCount', 'commentsCount'])
            ->latest()
            ->paginate(10);
        
        // Add can_delete flag to each post
        $posts->getCollection()->transform(function ($post) {
            $post->can_delete = $post->canBeDeletedBy(auth('user-api')->id());
            return $post;
        });
        
        return $this->success_response(__('Posts fetched successfully'), $posts);
    }

    /**
     * Create a new post
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->error_response(__('Validation failed'), $validator->errors());
        }

        $post = Post::create([
            'content' => $request->content,
            'user_id' => auth('user-api')->id(), // Use the correct guard
        ]);

        return $this->success_response(__('Post created successfully'), $post);
    }

    /**
     * Update post
     */
    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return $this->error_response(__('Post not found'), null);
        }

        // Use the correct guard for authorization
        if ($post->user_id !== auth('user-api')->id()) {
            return $this->error_response(__('Unauthorized'), null);
        }

        $validator = Validator::make($request->all(), [
            'title'   => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
        ]);

        if ($validator->fails()) {
            return $this->error_response(__('Validation failed'), $validator->errors());
        }

        $post->update($request->only(['title', 'content']));

        return $this->success_response(__('Post updated successfully'), $post);
    }

    /**
     * Delete post
     */
    public function destroy($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return $this->error_response(__('Post not found'), null);
        }

        // Use the correct guard for authorization
        if ($post->user_id !== auth('user-api')->id()) {
            return $this->error_response(__('Unauthorized'), null);
        }

        $post->delete();

        return $this->success_response(__('Post deleted successfully'), null);
    }
}
