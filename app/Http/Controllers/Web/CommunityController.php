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
        $posts = Post::with(['user', 'likes', 'approvedComments.user', 'approvedComments.replies.user'])
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
            'is_approved' => true, // Admin needs to approve
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
            ->whereNull('parent_id')  // Only get parent comments, not replies
            ->with('user', 'replies.user')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'comments' => $comments->map(function($comment) {
                $repliesData = $comment->replies->map(function($reply) {
                    return [
                        'id' => $reply->id,
                        'content' => $reply->content,
                        'user_name' => $reply->user->name,
                        'user_avatar' => $reply->user->photo_url ?? asset('assets_front/images/Profile-picture.jpg'),
                        'created_at' => $reply->created_at->diffForHumans(),
                        'can_delete' => $reply->canBeDeletedBy(Auth::id()),
                    ];
                });

                return [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'user_name' => $comment->user->name,
                    'user_avatar' => $comment->user->photo_url ?? asset('assets_front/images/Profile-picture.jpg'),
                    'created_at' => $comment->created_at->diffForHumans(),
                    'can_delete' => $comment->canBeDeletedBy(Auth::id()),
                    'replies' => $repliesData,
                    'replies_count' => $comment->replies->count(),
                ];
            })
        ]);
    }

    public function destroyComment(Comment $comment)
    {
        // Check if the authenticated user is the comment owner
        if (!$comment->canBeDeletedBy(Auth::id())) {
            return response()->json([
                'success' => false,
                'message' => __('front.unauthorized_delete_comment')
            ], 403);
        }

        try {
            $comment->delete();
            return response()->json([
                'success' => true,
                'message' => __('front.comment_deleted_successfully')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('front.error_deleting_comment')
            ], 500);
        }
    }

    public function storeReply(Request $request, Comment $comment)
    {
        // Check if comment is a parent comment (not a reply)
        if ($comment->parent_id !== null) {
            return response()->json([
                'success' => false,
                'message' => __('front.cannot_reply_to_reply')
            ], 400);
        }

        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $reply = Comment::create([
            'content' => $request->content,
            'user_id' => Auth::id(),
            'post_id' => $comment->post_id,
            'parent_id' => $comment->id,
            'is_approved' => true,
            'is_active' => true,
        ]);

        $reply->load('user');

        return response()->json([
            'success' => true,
            'message' => __('front.reply_submitted'),
            'reply' => [
                'id' => $reply->id,
                'content' => $reply->content,
                'user_name' => $reply->user->name,
                'user_avatar' => $reply->user->photo_url ?? asset('assets_front/images/Profile-picture.jpg'),
                'created_at' => $reply->created_at->diffForHumans(),
                'can_delete' => $reply->canBeDeletedBy(Auth::id()),
            ]
        ]);
    }

    public function loadReplies(Comment $comment)
    {
        $replies = $comment->replies()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'replies' => $replies->map(function($reply) {
                return [
                    'id' => $reply->id,
                    'content' => $reply->content,
                    'user_name' => $reply->user->name,
                    'user_avatar' => $reply->user->photo_url ?? asset('assets_front/images/Profile-picture.jpg'),
                    'created_at' => $reply->created_at->diffForHumans(),
                    'can_delete' => $reply->canBeDeletedBy(Auth::id()),
                ];
            })
        ]);
    }
}
