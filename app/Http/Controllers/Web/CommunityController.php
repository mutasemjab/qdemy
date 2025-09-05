<?php

namespace  App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Comment;
use App\Models\PostLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunityController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'likes', 'approvedComments.user'])
            ->where('is_approved', true)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('web.community', compact('posts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        Post::create([
            'content' => $request->content,
            'user_id' => Auth::id(),
            'is_approved' => false, // Admin needs to approve
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', __('community.post_submitted'));
    }

    public function storeComment(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        Comment::create([
            'content' => $request->content,
            'user_id' => Auth::id(),
            'post_id' => $post->id,
            'is_approved' => true, // Admin not needs to approve
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', __('community.comment_submitted'));
    }

    public function toggleLike(Post $post)
    {
        $userId = Auth::id();
        
        $existingLike = PostLike::where('user_id', $userId)
            ->where('post_id', $post->id)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $liked = false;
        } else {
            PostLike::create([
                'user_id' => $userId,
                'post_id' => $post->id,
            ]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'likes_count' => $post->likes()->count(),
        ]);
    }

    public function loadMoreComments(Post $post)
    {
        $comments = $post->approvedComments()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'comments' => $comments->map(function($comment) {
                return [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'user_name' => $comment->user->name,
                    'user_avatar' => $comment->user->photo_url ?? asset('assets_front/images/Profile-picture.png'),
                    'created_at' => $comment->created_at->diffForHumans(),
                ];
            })
        ]);
    }
}
