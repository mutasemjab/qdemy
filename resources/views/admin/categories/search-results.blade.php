@extends('layouts.admin')

@section('title', __('messages.Search Results'))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">{{ __('messages.Search Results') }}</h1>
            <p class="text-muted">{{ __('messages.Found') }} {{ $categories->total() }} {{ __('messages.results for') }} "{{ request('search') }}"</p>
        </div>
        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>{{ __('messages.Back to Categories') }}
        </a>
    </div>

    <!-- Search Bar -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('categories.index') }}" class="row g-3">
                <div class="col-md-8">
                    <input type="text" name="search" class="form-control" 
                           value="{{ request('search') }}" 
                           placeholder="{{ __('messages.Search categories...') }}">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-search me-1"></i>{{ __('messages.Search') }}
                    </button>
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>{{ __('messages.Clear') }}
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Search Results -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ __('messages.Search Results') }}</h5>
        </div>
        
        <div class="card-body p-0">
            @if($categories->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('messages.Category') }}</th>
                                <th>{{ __('messages.Parent') }}</th>
                                <th>{{ __('messages.Path') }}</th>
                                <th>{{ __('messages.Level') }}</th>
                                <th>{{ __('messages.Status') }}</th>
                                <th>{{ __('messages.Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($category->icon)
                                                <span class="me-2" style="color: {{ $category->color }}">
                                                    <i class="{{ $category->icon }}"></i>
                                                </span>
                                            @endif
                                            <div>
                                                <div class="fw-bold">{{ $category->name_ar }}</div>
                                                @if($category->name_en)
                                                    <small class="text-muted">{{ $category->name_en }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($category->parent)
                                            <a href="{{ route('categories.show', $category->parent) }}" class="text-decoration-none">
                                                {{ $category->parent->name_ar }}
                                            </a>
                                        @else
                                            <span class="text-muted">{{ __('messages.Root') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $category->breadcrumb }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ __('messages.Level') }} {{ $category->depth }}</span>
                                    </td>
                                    <td>
                                        @if($category->is_active)
                                            <span class="badge bg-success">{{ __('messages.Active') }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ __('messages.Inactive') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @can('category-table')
                                                <a href="{{ route('categories.show', $category) }}" 
                                                   class="btn btn-sm btn-outline-info" title="{{ __('messages.View') }}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endcan
                                            
                                            @can('category-edit')
                                                <a href="{{ route('categories.edit', $category) }}" 
                                                   class="btn btn-sm btn-outline-primary" title="{{ __('messages.Edit') }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endcan
                                            
                                            @can('category-add')
                                                <a href="{{ route('categories.create', ['parent_id' => $category->id]) }}" 
                                                   class="btn btn-sm btn-outline-success" title="{{ __('messages.Add Child') }}">
                                                    <i class="fas fa-plus"></i>
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="card-footer">
                    {{ $categories->withQueryString()->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h5>{{ __('messages.No results found') }}</h5>
                    <p class="text-muted">{{ __('messages.Try searching with different keywords') }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection