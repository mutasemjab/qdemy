@extends('layouts.admin')

@section('title', $category->name_ar)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 d-flex align-items-center">
                @if($category->icon)
                    <span class="me-3" style="color: {{ $category->color }}">
                        <i class="{{ $category->icon }} fa-lg"></i>
                    </span>
                @endif
                {{ $category->name_ar }}
                @if(!$category->is_active)
                    <span class="badge bg-danger ms-2">{{ __('messages.Inactive') }}</span>
                @endif
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">{{ __('messages.Categories') }}</a></li>
                    @if($category->ancestors()->count() > 0)
                        @foreach($category->ancestors() as $ancestor)
                            <li class="breadcrumb-item">
                                <a href="{{ route('categories.show', $ancestor) }}">{{ $ancestor->name_ar }}</a>
                            </li>
                        @endforeach
                    @endif
                    <li class="breadcrumb-item active">{{ $category->name_ar }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            @can('category-edit')
                <a href="{{ route('categories.edit', $category) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>{{ __('messages.Edit') }}
                </a>
            @endcan

            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>{{ __('messages.Back') }}
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Category Details -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('messages.Category Details') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('messages.Arabic Name') }}</label>
                            <p class="form-control-plaintext">{{ $category->name_ar }}</p>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('messages.English Name') }}</label>
                            <p class="form-control-plaintext">{{ $category->name_en ?: '-' }}</p>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('messages.Parent Category') }}</label>
                            <p class="form-control-plaintext">
                                @if($category->parent)
                                    <a href="{{ route('categories.show', $category->parent) }}" class="text-decoration-none">
                                        @if($category->parent->icon)
                                            <i class="{{ $category->parent->icon }}" style="color: {{ $category->parent->color }}"></i>
                                        @endif
                                        {{ $category->parent->name_ar }}
                                    </a>
                                @else
                                    <span class="text-muted">{{ __('messages.Root Category') }}</span>
                                @endif
                            </p>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('messages.Level') }}</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-info">{{ __('messages.Level') }} {{ $category->depth }}</span>
                            </p>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('messages.Sort Order') }}</label>
                            <p class="form-control-plaintext">{{ $category->sort_order }}</p>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('messages.Status') }}</label>
                            <p class="form-control-plaintext">
                                @if($category->is_active)
                                    <span class="badge bg-success">{{ __('messages.Active') }}</span>
                                @else
                                    <span class="badge bg-danger">{{ __('messages.Inactive') }}</span>
                                @endif
                            </p>
                        </div>

                        @if($category->type == 'lesson')
                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('messages.is_optional') }}</label>
                            <p class="form-control-plaintext">
                               {!! $category->isOptional() !!}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('messages.is_ministry') }}</label>
                            <p class="form-control-plaintext">
                               {!! $category->isMinistry() !!}
                            </p>
                        </div>
                        @endif

                        @if($category->description_ar)
                            <div class="col-12">
                                <label class="form-label fw-bold">{{ __('messages.Arabic Description') }}</label>
                                <p class="form-control-plaintext">{{ $category->description_ar }}</p>
                            </div>
                        @endif

                        @if($category->description_en)
                            <div class="col-12">
                                <label class="form-label fw-bold">{{ __('messages.English Description') }}</label>
                                <p class="form-control-plaintext">{{ $category->description_en }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Subcategories -->
            @if($category->children->count() > 0)
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('messages.Subcategories') }} ({{ $category->children->count() }})</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('messages.Category') }}</th>
                                        <th>{{ __('messages.Children') }}</th>
                                        <th>{{ __('messages.Status') }}</th>
                                         @if($category->children->where('type','lesson')->count())
                                         <th>{{ __('messages.is_ministry') }}</th>
                                         <th>{{ __('messages.is_optional') }}</th>
                                         @endif
                                        <th>{{ __('messages.Sort Order') }}</th>
                                        <th>{{ __('messages.Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($category->children->sortBy('sort_order') as $child)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($child->icon)
                                                        <span class="me-2" style="color: {{ $child->color }}">
                                                            <i class="{{ $child->icon }}"></i>
                                                        </span>
                                                    @endif
                                                    <div>
                                                        <div class="fw-bold">
                                                            <a href="{{ route('categories.show', $child) }}" class="text-decoration-none">
                                                                {{ $child->name_ar }}
                                                            </a>
                                                        </div>
                                                        @if($child->name_en)
                                                            <small class="text-muted">{{ $child->name_en }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($child->children_count > 0)
                                                    <span class="badge bg-secondary">{{ $child->children_count }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($child->is_active)
                                                    <span class="badge bg-success">{{ __('messages.Active') }}</span>
                                                @else
                                                    <span class="badge bg-danger">{{ __('messages.Inactive') }}</span>
                                                @endif
                                            </td>
                                            @if($child->type == 'lesson')
                                             <td>{!! $child->isMinistry() !!}</td>
                                             <td>{!! $child->isOptional() !!}</td>
                                             @endif
                                            <td>{{ $child->sort_order }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @can('category-table')
                                                        <a href="{{ route('categories.show', $child) }}"
                                                           class="btn btn-sm btn-outline-info" title="{{ __('messages.View') }}">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    @endcan

                                                    @can('category-edit')
                                                        <a href="{{ route('categories.edit', $child) }}"
                                                           class="btn btn-sm btn-outline-primary" title="{{ __('messages.Edit') }}">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                        <h5>{{ __('messages.No subcategories found') }}</h5>
                        <p class="text-muted">{{ __('messages.This category has no subcategories yet') }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">{{ __('messages.Quick Actions') }}</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @can('category-edit')
                            <a href="{{ route('categories.edit', $category) }}" class="btn btn-outline-primary">
                                <i class="fas fa-edit me-2"></i>{{ __('messages.Edit Category') }}
                            </a>
                        @endcan

                        @can('category-edit')
                            <form method="POST" action="{{ route('categories.toggle-status', $category) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-{{ $category->is_active ? 'warning' : 'success' }} w-100">
                                    <i class="fas fa-{{ $category->is_active ? 'pause' : 'play' }} me-2"></i>
                                    {{ $category->is_active ? __('messages.Deactivate') : __('messages.Activate') }}
                                </button>
                            </form>
                        @endcan

                        @if($category->parent)
                            <a href="{{ route('categories.show', $category->parent) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-level-up-alt me-2"></i>{{ __('messages.Go to Parent') }}
                            </a>
                        @endif

                        @can('category-delete')
                            @if($category->children->count() === 0)
                                <form method="POST" action="{{ route('categories.destroy', $category) }}"
                                      onsubmit="return confirm('{{ __('messages.Are you sure you want to delete this category?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger w-100">
                                        <i class="fas fa-trash me-2"></i>{{ __('messages.Delete Category') }}
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-outline-danger w-100" disabled title="{{ __('messages.Cannot delete category with subcategories') }}">
                                    <i class="fas fa-trash me-2"></i>{{ __('messages.Delete Category') }}
                                </button>
                            @endif
                        @endcan
                    </div>
                </div>
            </div>

            <!-- Category Statistics -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">{{ __('messages.Statistics') }}</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary mb-0">{{ $category->children->count() }}</h4>
                                <small class="text-muted">{{ __('messages.Direct Children') }}</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-info mb-0">{{ $category->descendants()->count() }}</h4>
                            <small class="text-muted">{{ __('messages.All Descendants') }}</small>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-success mb-0">{{ $category->depth }}</h4>
                                <small class="text-muted">{{ __('messages.Depth Level') }}</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-warning mb-0">{{ $category->sort_order }}</h4>
                            <small class="text-muted">{{ __('messages.Sort Order') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category Path -->
            @if($category->ancestors()->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">{{ __('messages.Category Path') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column gap-2">
                            @foreach($category->ancestors() as $ancestor)
                                <div class="d-flex align-items-center">
                                    <div class="me-2" style="margin-left: {{ $loop->index * 20 }}px;">
                                        @if($ancestor->icon)
                                            <i class="{{ $ancestor->icon }}" style="color: {{ $ancestor->color }}"></i>
                                        @else
                                            <i class="fas fa-folder" style="color: {{ $ancestor->color }}"></i>
                                        @endif
                                    </div>
                                    <a href="{{ route('categories.show', $ancestor) }}" class="text-decoration-none">
                                        {{ $ancestor->name_ar }}
                                    </a>
                                </div>
                            @endforeach
                            <div class="d-flex align-items-center">
                                <div class="me-2" style="margin-left: {{ $category->ancestors()->count() * 20 }}px;">
                                    @if($category->icon)
                                        <i class="{{ $category->icon }}" style="color: {{ $category->color }}"></i>
                                    @else
                                        <i class="fas fa-folder" style="color: {{ $category->color }}"></i>
                                    @endif
                                </div>
                                <strong>{{ $category->name_ar }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Category Info -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">{{ __('messages.Category Information') }}</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>{{ __('messages.ID') }}:</strong></td>
                            <td>{{ $category->id }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('messages.Color') }}:</strong></td>
                            <td>
                                <span class="d-inline-block rounded"
                                      style="width: 20px; height: 20px; background-color: {{ $category->color }}"></span>
                                {{ $category->color }}
                            </td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('messages.Created') }}:</strong></td>
                            <td>{{ $category->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('messages.Updated') }}:</strong></td>
                            <td>{{ $category->updated_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 d-flex align-items-center">
                @if($category->icon)
                    <span class="me-3" style="color: {{ $category->color }}">
                        <i class="{{ $category->icon }} fa-lg"></i>
                    </span>
                @endif
                {{ $category->name_ar }}
                @if(!$category->is_active)
                    <span class="badge bg-danger ms-2">{{ __('Inactive') }}</span>
                @endif
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">{{ __('Categories') }}</a></li>
                    @if($category->ancestors()->count() > 0)
                        @foreach($category->ancestors() as $ancestor)
                            <li class="breadcrumb-item">
                                <a href="{{ route('categories.show', $ancestor) }}">{{ $ancestor->name_ar }}</a>
                            </li>
                        @endforeach
                    @endif
                    <li class="breadcrumb-item active">{{ $category->name_ar }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            @can('category-edit')
                <a href="{{ route('categories.edit', $category) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>{{ __('Edit') }}
                </a>
            @endcan

            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>{{ __('Back') }}
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Category Details -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Category Details') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Arabic Name') }}</label>
                            <p class="form-control-plaintext">{{ $category->name_ar }}</p>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('English Name') }}</label>
                            <p class="form-control-plaintext">{{ $category->name_en ?: '-' }}</p>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Parent Category') }}</label>
                            <p class="form-control-plaintext">
                                @if($category->parent)
                                    <a href="{{ route('categories.show', $category->parent) }}" class="text-decoration-none">
                                        @if($category->parent->icon)
                                            <i class="{{ $category->parent->icon }}" style="color: {{ $category->parent->color }}"></i>
                                        @endif
                                        {{ $category->parent->name_ar }}
                                    </a>
                                @else
                                    <span class="text-muted">{{ __('Root Category') }}</span>
                                @endif
                            </p>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Level') }}</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-info">{{ __('Level') }} {{ $category->depth }}</span>
                            </p>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Sort Order') }}</label>
                            <p class="form-control-plaintext">{{ $category->sort_order }}</p>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">{{ __('Status') }}</label>
                            <p class="form-control-plaintext">
                                @if($category->is_active)
                                    <span class="badge bg-success">{{ __('Active') }}</span>
                                @else
                                    <span class="badge bg-danger">{{ __('Inactive') }}</span>
                                @endif
                            </p>
                        </div>

                        @if($category->description_ar)
                            <div class="col-12">
                                <label class="form-label fw-bold">{{ __('Arabic Description') }}</label>
                                <p class="form-control-plaintext">{{ $category->description_ar }}</p>
                            </div>
                        @endif

                        @if($category->description_en)
                            <div class="col-12">
                                <label class="form-label fw-bold">{{ __('English Description') }}</label>
                                <p class="form-control-plaintext">{{ $category->description_en }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Subcategories -->
            @if($category->children->count() > 0)
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('Subcategories') }} ({{ $category->children->count() }})</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('Category') }}</th>
                                        <th>{{ __('Children') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Sort Order') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($category->children->sortBy('sort_order') as $child)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($child->icon)
                                                        <span class="me-2" style="color: {{ $child->color }}">
                                                            <i class="{{ $child->icon }}"></i>
                                                        </span>
                                                    @endif
                                                    <div>
                                                        <div class="fw-bold">
                                                            <a href="{{ route('categories.show', $child) }}" class="text-decoration-none">
                                                                {{ $child->name_ar }}
                                                            </a>
                                                        </div>
                                                        @if($child->name_en)
                                                            <small class="text-muted">{{ $child->name_en }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($child->children_count > 0)
                                                    <span class="badge bg-secondary">{{ $child->children_count }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($child->is_active)
                                                    <span class="badge bg-success">{{ __('Active') }}</span>
                                                @else
                                                    <span class="badge bg-danger">{{ __('Inactive') }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $child->sort_order }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @can('category-table')
                                                        <a href="{{ route('categories.show', $child) }}"
                                                           class="btn btn-sm btn-outline-info" title="{{ __('View') }}">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    @endcan

                                                    @can('category-edit')
                                                        <a href="{{ route('categories.edit', $child) }}"
                                                           class="btn btn-sm btn-outline-primary" title="{{ __('Edit') }}">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                        <h5>{{ __('No subcategories found') }}</h5>
                        <p class="text-muted">{{ __('This category has no subcategories yet') }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">{{ __('Quick Actions') }}</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @can('category-edit')
                            <a href="{{ route('categories.edit', $category) }}" class="btn btn-outline-primary">
                                <i class="fas fa-edit me-2"></i>{{ __('Edit Category') }}
                            </a>
                        @endcan

                        @can('category-edit')
                            <form method="POST" action="{{ route('categories.toggle-status', $category) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-{{ $category->is_active ? 'warning' : 'success' }} w-100">
                                    <i class="fas fa-{{ $category->is_active ? 'pause' : 'play' }} me-2"></i>
                                    {{ $category->is_active ? __('Deactivate') : __('Activate') }}
                                </button>
                            </form>
                        @endcan

                        @if($category->parent)
                            <a href="{{ route('categories.show', $category->parent) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-level-up-alt me-2"></i>{{ __('Go to Parent') }}
                            </a>
                        @endif

                        @can('category-delete')
                            @if($category->children->count() === 0)
                                <form method="POST" action="{{ route('categories.destroy', $category) }}"
                                      onsubmit="return confirm('{{ __('Are you sure you want to delete this category?') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger w-100">
                                        <i class="fas fa-trash me-2"></i>{{ __('Delete Category') }}
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-outline-danger w-100" disabled title="{{ __('Cannot delete category with subcategories') }}">
                                    <i class="fas fa-trash me-2"></i>{{ __('Delete Category') }}
                                </button>
                            @endif
                        @endcan
                    </div>
                </div>
            </div>

            <!-- Category Statistics -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">{{ __('Statistics') }}</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary mb-0">{{ $category->children->count() }}</h4>
                                <small class="text-muted">{{ __('Direct Children') }}</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-info mb-0">{{ $category->descendants()->count() }}</h4>
                            <small class="text-muted">{{ __('All Descendants') }}</small>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-success mb-0">{{ $category->depth }}</h4>
                                <small class="text-muted">{{ __('Depth Level') }}</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-warning mb-0">{{ $category->sort_order }}</h4>
                            <small class="text-muted">{{ __('Sort Order') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category Path -->
            @if($category->ancestors()->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">{{ __('Category Path') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column gap-2">
                            @foreach($category->ancestors() as $ancestor)
                                <div class="d-flex align-items-center">
                                    <div class="me-2" style="margin-left: {{ $loop->index * 20 }}px;">
                                        @if($ancestor->icon)
                                            <i class="{{ $ancestor->icon }}" style="color: {{ $ancestor->color }}"></i>
                                        @else
                                            <i class="fas fa-folder" style="color: {{ $ancestor->color }}"></i>
                                        @endif
                                    </div>
                                    <a href="{{ route('categories.show', $ancestor) }}" class="text-decoration-none">
                                        {{ $ancestor->name_ar }}
                                    </a>
                                </div>
                            @endforeach
                            <div class="d-flex align-items-center">
                                <div class="me-2" style="margin-left: {{ $category->ancestors()->count() * 20 }}px;">
                                    @if($category->icon)
                                        <i class="{{ $category->icon }}" style="color: {{ $category->color }}"></i>
                                    @else
                                        <i class="fas fa-folder" style="color: {{ $category->color }}"></i>
                                    @endif
                                </div>
                                <strong>{{ $category->name_ar }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Category Info -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">{{ __('Category Information') }}</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>{{ __('ID') }}:</strong></td>
                            <td>{{ $category->id }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('Color') }}:</strong></td>
                            <td>
                                <span class="d-inline-block rounded"
                                      style="width: 20px; height: 20px; background-color: {{ $category->color }}"></span>
                                {{ $category->color }}
                            </td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('Created') }}:</strong></td>
                            <td>{{ $category->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('Updated') }}:</strong></td>
                            <td>{{ $category->updated_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
