@extends('layouts.admin')

@section('title', __('messages.community_posts'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.community_posts') }}</h3>
                    @can('community-add')
                        <a href="{{ route('admin.community.posts.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> {{ __('messages.add_post') }}
                        </a>
                    @endcan
                </div>

                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" class="mb-3">
                        <div class="row">
                            <div class="col-md-3">
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
                            <div class="col-md-3">
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
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="{{ __('messages.search_posts') }}" 
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-secondary">{{ __('messages.filter') }}</button>
                            </div>
                        </div>
                    </form>

                    @if($posts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ __('messages.title') }}</th>
                                        <th>{{ __('messages.author') }}</th>
                                        <th>{{ __('messages.comments_count') }}</th>
                                        <th>{{ __('messages.status') }}</th>
                                        <th>{{ __('messages.approval') }}</th>
                                        <th>{{ __('messages.created_at') }}</th>
                                        <th>{{ __('messages.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($posts as $post)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.community.posts.show', $post) }}">
                                                    {{ Str::limit($post->title, 50) }}
                                                </a>
                                            </td>
                                            <td>{{ $post->user->name }}</td>
                                            <td>{{ $post->comments->count() }}</td>
                                            <td>
                                                <span class="badge badge-{{ $post->is_active ? 'success' : 'danger' }}">
                                                    {{ $post->is_active ? __('messages.active') : __('messages.inactive') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $post->is_approved ? 'success' : 'warning' }}">
                                                    {{ $post->is_approved ? __('messages.approved') : __('messages.pending') }}
                                                </span>
                                            </td>
                                            <td>{{ $post->created_at->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @can('community-table')
                                                        <a href="{{ route('admin.community.posts.show', $post) }}" 
                                                           class="btn btn-sm btn-info">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    @endcan
                                                    
                                                    @can('community-edit')
                                                        <a href="{{ route('admin.community.posts.edit', $post) }}" 
                                                           class="btn btn-sm btn-primary">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        
                                                        @if(!$post->is_approved)
                                                            <a href="{{ route('admin.community.posts.approve', $post) }}" 
                                                               class="btn btn-sm btn-success">
                                                                <i class="fas fa-check"></i>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('admin.community.posts.reject', $post) }}" 
                                                               class="btn btn-sm btn-warning">
                                                                <i class="fas fa-times"></i>
                                                            </a>
                                                        @endif
                                                        
                                                        <a href="{{ route('admin.community.posts.toggle-status', $post) }}" 
                                                           class="btn btn-sm btn-{{ $post->is_active ? 'danger' : 'success' }}">
                                                            <i class="fas fa-{{ $post->is_active ? 'ban' : 'check' }}"></i>
                                                        </a>
                                                    @endcan
                                                    
                                                    @can('community-delete')
                                                        <form action="{{ route('admin.community.comments.destroy', $comment) }}" 
                                                              method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                                    onclick="return confirm('{{ __('messages.confirm_delete') }}')">
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
                        
                        {{ $comments->withQueryString()->links() }}
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted">{{ __('messages.no_comments_found') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection