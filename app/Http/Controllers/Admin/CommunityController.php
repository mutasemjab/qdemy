<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:community-table', ['only' => ['index', 'show']]);
        $this->middleware('permission:community-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:community-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:community-delete', ['only' => ['destroy']]);
    }
 

    // Display all posts
    public function index(Request $request)
    {
        $query = Post::with(['user', 'comments']);

        // Filter by approval status
        if ($request->has('approval_status')) {
            if ($request->approval_status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->approval_status === 'pending') {
                $query->where('is_approved', false);
            }
        }

        // Filter by active status
        if ($request->has('active_status')) {
            $query->where('is_active', $request->active_status);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        $posts = $query->latest()->paginate(15);

        return view('admin.community.posts.index', compact('posts'));
    }

    // Show single post with comments
    public function show(Post $post)
    {
        $post->load(['user', 'comments.user']);
        
        return view('admin.community.posts.show', compact('post'));
    }

    // Create new post (admin)
    public function create()
    {
        return view('admin.community.posts.create');
    }

    // Store new post (admin)
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10',
            'user_id' => 'required|exists:users,id',
            'is_approved' => 'boolean',
            'is_active' => 'boolean',
        ]);

        Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => $request->user_id,
            'is_approved' => $request->has('is_approved'),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.community.posts.index')
            ->with('success', __('messages.post_created_successfully'));
    }

    // Edit post
    public function edit(Post $post)
    {
        return view('admin.community.posts.edit', compact('post'));
    }

    // Update post
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:10',
            'is_approved' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $post->update([
            'title' => $request->title,
            'content' => $request->content,
            'is_approved' => $request->has('is_approved'),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.community.posts.index')
            ->with('success', __('messages.post_updated_successfully'));
    }

    // Delete post
    public function destroy(Post $post)
    {
        $post->delete();
        
        return redirect()->route('admin.community.posts.index')
            ->with('success', __('messages.post_deleted_successfully'));
    }

    // Approve post
    public function approve(Post $post)
    {
        $post->update(['is_approved' => true]);
        
        return redirect()->back()
            ->with('success', __('messages.post_approved_successfully'));
    }

    // Reject post
    public function reject(Post $post)
    {
        $post->update(['is_approved' => false]);
        
        return redirect()->back()
            ->with('success', __('messages.post_rejected_successfully'));
    }

    // Toggle post status
    public function toggleStatus(Post $post)
    {
        $post->update(['is_active' => !$post->is_active]);
        
        return redirect()->back()
            ->with('success', __('messages.post_status_updated'));
    }

    // Comments management
    public function comments(Request $request)
    {
        $query = Comment::with(['user', 'post']);

        // Filter by approval status
        if ($request->has('approval_status')) {
            if ($request->approval_status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->approval_status === 'pending') {
                $query->where('is_approved', false);
            }
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where('content', 'like', '%' . $request->search . '%');
        }

        $comments = $query->latest()->paginate(15);

        return view('admin.community.comments.index', compact('comments'));
    }

    // Approve comment
    public function approveComment(Comment $comment)
    {
        $comment->update(['is_approved' => true]);
        
        return redirect()->back()
            ->with('success', __('messages.comment_approved_successfully'));
    }

    // Reject comment
    public function rejectComment(Comment $comment)
    {
        $comment->update(['is_approved' => false]);
        
        return redirect()->back()
            ->with('success', __('messages.comment_rejected_successfully'));
    }

    // Delete comment
    public function destroyComment(Comment $comment)
    {
        $comment->delete();
        
        return redirect()->back()
            ->with('success', __('messages.comment_deleted_successfully'));
    }

    // Toggle comment status
    public function toggleCommentStatus(Comment $comment)
    {
        $comment->update(['is_active' => !$comment->is_active]);
        
        return redirect()->back()
            ->with('success', __('messages.comment_status_updated'));
    }
}