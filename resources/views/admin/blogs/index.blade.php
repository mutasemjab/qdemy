@extends('layouts.admin')

@section('title', __('messages.blogs'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.blogs') }}</h3>
                    @can('blog-add')
                    <a href="{{ route('blogs.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> {{ __('messages.add_new_blog') }}
                    </a>
                    @endcan
                </div>

                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('messages.photo') }}</th>
                                    <th>{{ __('messages.title') }}</th>
                                    <th>{{ __('messages.description') }}</th>
                                    <th>{{ __('messages.created_at') }}</th>
                                    <th width="200">{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($blogs as $blog)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if($blog->photo)
                                            <img src="{{ asset('assets/admin/uploads/' . $blog->photo) }}" alt="{{ $blog->title }}" 
                                                 class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                                 style="width: 60px; height: 60px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ Str::limit($blog->title, 40) }}</strong>
                                    </td>
                                    <td>{{ Str::limit($blog->description, 60) }}</td>
                                    <td>{{ $blog->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @can('blog-table')
                                            <a href="{{ route('blogs.show', $blog) }}" 
                                               class="btn btn-info btn-sm" title="{{ __('messages.view') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @endcan
                                            
                                            @can('blog-edit')
                                            <a href="{{ route('blogs.edit', $blog) }}" 
                                               class="btn btn-warning btn-sm" title="{{ __('messages.edit') }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endcan
                                            
                                            @can('blog-delete')
                                            <form action="{{ route('blogs.destroy', $blog) }}" method="POST" 
                                                  class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" 
                                                        title="{{ __('messages.delete') }}"
                                                        onclick="return confirm('{{ __('messages.confirm_delete') }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <div class="py-4">
                                            <i class="fas fa-blog fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">{{ __('messages.no_blogs_found') }}</p>
                                            @can('blog-add')
                                            <a href="{{ route('blogs.create') }}" class="btn btn-primary">
                                                {{ __('messages.create_first_blog') }}
                                            </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $blogs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


