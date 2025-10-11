@extends('layouts.admin')

@section('title', __('messages.post_details'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.post_details') }}</h3>
                    <a href="{{ route('admin.community.posts.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                    </a>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h4>{{ $post->title }}</h4>
                            <div class="mb-3">
                                <span class="badge badge-{{ $post->is_active ? 'success' : 'danger' }}">
                                    {{ $post->is_active ? __('messages.active') : __('messages.inactive') }}
                                </span>
                                <span class="badge badge-{{ $post->is_approved ? 'success' : 'warning' }}">
                                    {{ $post->is_approved ? __('messages.approved') : __('messages.pending') }}
                                </span>
                            </div>
                            <div class="post-content">
                                {!! nl2br(e($post->content)) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">{{ __('messages.post_info') }}</div>
                                <div class="card-body">
                                    <p><strong>{{ __('messages.author') }}:</strong> {{ $post->user->name }}</p>
                                    <p><strong>{{ __('messages.created_at') }}:</strong> {{ $post->created_at->format('Y-m-d H:i') }}</p>
                                    <p><strong>{{ __('messages.updated_at') }}:</strong> {{ $post->updated_at->format('Y-m-d H:i') }}</p>
                                    <p><strong>{{ __('messages.comments_count') }}:</strong> {{ $post->comments->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Comments Section -->
                    <div class="mt-4">
                        <h5>{{ __('messages.comments') }} ({{ $post->comments->count() }})</h5>
                        @if($post->comments->count() > 0)
                            @foreach($post->comments as $comment)
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <strong>{{ $comment->user->name }}</strong>
                                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                            </div>
                                            <div>
                                                <span class="badge badge-{{ $comment->is_approved ? 'success' : 'warning' }}">
                                                    {{ $comment->is_approved ? __('messages.approved') : __('messages.pending') }}
                                                </span>
                                                <span class="badge badge-{{ $comment->is_active ? 'success' : 'danger' }}">
                                                    {{ $comment->is_active ? __('messages.active') : __('messages.inactive') }}
                                                </span>
                                            </div>
                                        </div>
                                        <p class="mt-2">{{ $comment->content }}</p>
                                        
                                        @can('community-edit')
                                            <div class="btn-group btn-group-sm">
                                                @if(!$comment->is_approved)
                                                    <a href="{{ route('admin.community.comments.approve', $comment) }}" 
                                                       class="btn btn-success">{{ __('messages.approve') }}</a>
                                                @else
                                                    <a href="{{ route('admin.community.comments.reject', $comment) }}" 
                                                       class="btn btn-warning">{{ __('messages.reject') }}</a>
                                                @endif
                                                
                                                <a href="{{ route('admin.community.comments.toggle-status', $comment) }}" 
                                                   class="btn btn-{{ $comment->is_active ? 'danger' : 'success' }}">
                                                    {{ $comment->is_active ? __('messages.deactivate') : __('messages.activate') }}
                                                </a>
                                            </div>
                                        @endcan
                                        
                                        @can('community-delete')
                                            <form action="{{ route('admin.community.comments.destroy', $comment) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                        onclick="return confirm('{{ __('messages.confirm_delete') }}')">
                                                    {{ __('messages.delete') }}
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">{{ __('messages.no_comments_found') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection