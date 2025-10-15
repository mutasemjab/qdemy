{{-- resources/views/admin/community/comments/index.blade.php --}}
@extends('layouts.admin')

@section('title', __('messages.community_comments'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.community_comments') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.community.posts.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-file-alt"></i> {{ __('messages.manage_posts') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ \App\Models\Comment::count() }}</h3>
                                    <p>{{ __('messages.total_comments') }}</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-comments"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ \App\Models\Comment::where('is_approved', false)->count() }}</h3>
                                    <p>{{ __('messages.pending_approval') }}</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ \App\Models\Comment::where('is_approved', true)->count() }}</h3>
                                    <p>{{ __('messages.approved_comments') }}</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="small-box bg-secondary">
                                <div class="inner">
                                    <h3>{{ \App\Models\Comment::whereDate('created_at', today())->count() }}</h3>
                                    <p>{{ __('messages.today_comments') }}</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-calendar-day"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <form method="GET" class="mb-3">
                        <div class="row">
                            <div class="col-md-2">
                                <select name="approval_status" class="form-control">
                                    <option value="">{{ __('messages.all_approval_status') }}</option>
                                    <option value="approved" {{ request('approval_status') == 'approved' ? 'selected' : '' }}>
                                        {{ __('messages.approved') }}
                                    </option>
                                    <option value="pending" {{ request('approval_status') == 'pending' ? 'selected' : '' }}>
                                        {{ __('messages.pending') }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="active_status" class="form-control">
                                    <option value="">{{ __('messages.all_status') }}</option>
                                    <option value="1" {{ request('active_status') == '1' ? 'selected' : '' }}>
                                        {{ __('messages.active') }}
                                    </option>
                                    <option value="0" {{ request('active_status') == '0' ? 'selected' : '' }}>
                                        {{ __('messages.inactive') }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="date_filter" class="form-control">
                                    <option value="">{{ __('messages.all_dates') }}</option>
                                    <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>
                                        {{ __('messages.today') }}
                                    </option>
                                    <option value="week" {{ request('date_filter') == 'week' ? 'selected' : '' }}>
                                        {{ __('messages.this_week') }}
                                    </option>
                                    <option value="month" {{ request('date_filter') == 'month' ? 'selected' : '' }}>
                                        {{ __('messages.this_month') }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="{{ __('messages.search_comments') }}" 
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-secondary btn-block">
                                    <i class="fas fa-search"></i> {{ __('messages.filter') }}
                                </button>
                            </div>
                        </div>
                    </form>

                    @if($comments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ __('messages.comment_content') }}</th>
                                        <th>{{ __('messages.comment_author') }}</th>
                                        <th>{{ __('messages.parent_post') }}</th>
                                        <th>{{ __('messages.status') }}</th>
                                        <th>{{ __('messages.approval') }}</th>
                                        <th>{{ __('messages.comment_date') }}</th>
                                        <th>{{ __('messages.comment_actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($comments as $comment)
                                        <tr>
                                            <td>
                                                <div class="comment-preview">
                                                    {{ Str::limit($comment->content, 80) }}
                                                </div>
                                                @if(strlen($comment->content) > 80)
                                                    <small class="text-muted">
                                                        <a href="#" onclick="showFullComment({{ $comment->id }})" 
                                                           class="text-primary">{{ __('messages.show_full') }}</a>
                                                    </small>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary rounded-circle text-white text-center mr-2" 
                                                         style="width: 32px; height: 32px; line-height: 32px;">
                                                        {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <div class="font-weight-bold">{{ $comment->user->name }}</div>
                                                        <small class="text-muted">{{ $comment->user->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.community.posts.show', $comment->post) }}" 
                                                   class="text-decoration-none" target="_blank">
                                                    {{ Str::limit($comment->post->title, 40) }}
                                                    <i class="fas fa-external-link-alt fa-xs ml-1"></i>
                                                </a>
                                                <br>
                                                <small class="text-muted">{{ __('messages.by') }} {{ $comment->post->user->name }}</small>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $comment->is_active ? 'success' : 'danger' }}">
                                                    {{ $comment->is_active ? __('messages.active') : __('messages.inactive') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $comment->is_approved ? 'success' : 'warning' }}">
                                                    {{ $comment->is_approved ? __('messages.approved') : __('messages.pending') }}
                                                </span>
                                            </td>
                                            <td>
                                                <div>{{ $comment->created_at->format('M d, Y') }}</div>
                                                <small class="text-muted">{{ $comment->created_at->format('H:i') }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <!-- View Comment Details -->
                                                    <button type="button" class="btn btn-sm btn-info" 
                                                            onclick="showCommentModal({{ $comment->id }})" 
                                                            title="{{ __('messages.view_details') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    
                                                    @can('community-edit')
                                                        <!-- Approve/Reject Toggle -->
                                                        @if(!$comment->is_approved)
                                                            <a href="{{ route('admin.community.comments.approve', $comment) }}" 
                                                               class="btn btn-sm btn-success" 
                                                               title="{{ __('messages.approve') }}">
                                                                <i class="fas fa-check"></i>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('admin.community.comments.reject', $comment) }}" 
                                                               class="btn btn-sm btn-warning" 
                                                               title="{{ __('messages.reject') }}">
                                                                <i class="fas fa-times"></i>
                                                            </a>
                                                        @endif
                                                        
                                                        <!-- Active/Inactive Toggle -->
                                                        <a href="{{ route('admin.community.comments.toggle-status', $comment) }}" 
                                                           class="btn btn-sm btn-{{ $comment->is_active ? 'secondary' : 'primary' }}" 
                                                           title="{{ $comment->is_active ? __('messages.deactivate') : __('messages.activate') }}">
                                                            <i class="fas fa-{{ $comment->is_active ? 'ban' : 'check-circle' }}"></i>
                                                        </a>
                                                    @endcan
                                                    
                                                    @can('community-delete')
                                                        <!-- Delete Comment -->
                                                        <form action="{{ route('admin.community.comments.destroy', $comment) }}" 
                                                              method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                                    onclick="return confirm('{{ __('messages.confirm_delete_comment') }}')"
                                                                    title="{{ __('messages.delete') }}">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                {{ __('messages.showing') }} {{ $comments->firstItem() }} {{ __('messages.to') }} 
                                {{ $comments->lastItem() }} {{ __('messages.of') }} {{ $comments->total() }} 
                                {{ __('messages.comments') }}
                            </div>
                            <div>
                                {{ $comments->withQueryString()->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-comment-slash fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">{{ __('messages.no_comments_found') }}</h4>
                            <p class="text-muted">{{ __('messages.no_comments_description') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Comment Details Modal -->
<div class="modal fade" id="commentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('messages.comment_details') }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="commentModalBody">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    {{ __('messages.close') }}
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function showCommentModal(commentId) {
    // Find the comment data from the table
    fetch(`/admin/community/comments/${commentId}/details`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('commentModalBody').innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>{{ __('messages.comment_author') }}</h6>
                        <p><strong>${data.user.name}</strong><br>
                        <small class="text-muted">${data.user.email}</small></p>
                        
                        <h6>{{ __('messages.comment_date') }}</h6>
                        <p>${data.created_at}</p>
                        
                        <h6>{{ __('messages.status') }}</h6>
                        <p>
                            <span class="badge badge-${data.is_active ? 'success' : 'danger'}">
                                ${data.is_active ? '{{ __('messages.active') }}' : '{{ __('messages.inactive') }}'}
                            </span>
                            <span class="badge badge-${data.is_approved ? 'success' : 'warning'}">
                                ${data.is_approved ? '{{ __('messages.approved') }}' : '{{ __('messages.pending') }}'}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6>{{ __('messages.parent_post') }}</h6>
                        <p><a href="/admin/community/posts/${data.post.id}" target="_blank">
                            ${data.post.title}
                        </a><br>
                        <small class="text-muted">{{ __('messages.by') }} ${data.post.user.name}</small></p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6>{{ __('messages.comment_content') }}</h6>
                        <div class="border p-3 bg-light rounded">
                            ${data.content}
                        </div>
                    </div>
                </div>
            `;
            $('#commentModal').modal('show');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __('messages.error_loading_comment') }}');
        });
}

function showFullComment(commentId) {
    // This would expand the comment in place or show in modal
    // Implementation depends on your preference
}
</script>
@endpush