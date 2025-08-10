@extends('layouts.admin')

@section('title', __('messages.Package Details') . ' - ' . $package->name)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">{{ __('messages.Package Details') }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('packages.index') }}">{{ __('messages.Packages') }}</a></li>
                    <li class="breadcrumb-item active">{{ $package->name }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            @can('package-edit')
                <a href="{{ route('packages.edit', $package) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>{{ __('messages.Edit') }}
                </a>
            @endcan
            <a href="{{ route('packages.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>{{ __('messages.Back') }}
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Package Information -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('messages.Package Information') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            @if($package->image)
                                <img src="{{ $package->image_url }}" alt="{{ $package->name }}" 
                                     class="img-fluid rounded mb-3" style="max-height: 300px; width: 100%; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center rounded mb-3" 
                                     style="height: 200px;">
                                    <div class="text-center text-muted">
                                        <i class="fas fa-image fa-3x mb-2"></i>
                                        <p>{{ __('messages.No image') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="150">{{ __('messages.Name') }}:</th>
                                    <td><strong>{{ $package->name }}</strong></td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.Price') }}:</th>
                                    <td>
                                        <span class="h5 text-success mb-0">{{ $package->formatted_price }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.Type') }}:</th>
                                    <td>
                                        <span class="badge {{ $package->type_badge_class }} fs-6">
                                            {{ ucfirst($package->type) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.Status') }}:</th>
                                    <td>
                                        <span class="badge {{ $package->status_badge_class }} fs-6">
                                            {{ ucfirst($package->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.Course Selection') }}:</th>
                                    <td>
                                        <span class="badge badge-info fs-6">
                                            {{ $package->how_much_course_can_select }} {{ __('messages.Courses') }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.Categories') }}:</th>
                                    <td>
                                        <span class="badge badge-secondary fs-6">
                                            {{ $package->categories->count() }} {{ __('messages.Categories') }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.Created') }}:</th>
                                    <td>{{ $package->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('messages.Updated') }}:</th>
                                    <td>{{ $package->updated_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    @if($package->description)
                        <hr>
                        <div>
                            <h6>{{ __('messages.Description') }}</h6>
                            <p class="text-muted">{{ $package->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Package Categories -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('messages.Package Categories') }}</h5>
                    <span class="badge badge-primary">{{ $package->categories->count() }} {{ __('messages.Categories') }}</span>
                </div>
                <div class="card-body">
                    @if($package->categories->count() > 0)
                        <div class="row g-3">
                            @foreach($package->categories as $category)
                                <div class="col-md-6">
                                    <div class="card border h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start">
                                                @if($category->icon)
                                                    <div class="me-3">
                                                        <i class="{{ $category->icon }} fa-2x" style="color: {{ $category->color }}"></i>
                                                    </div>
                                                @endif
                                                <div class="flex-grow-1">
                                                    <h6 class="card-title mb-1">{{ $category->name_ar }}</h6>
                                                    @if($category->name_en)
                                                        <p class="text-muted small mb-2">{{ $category->name_en }}</p>
                                                    @endif
                                                    
                                                    <!-- Parent Category -->
                                                    @if($category->parent)
                                                        <div class="mb-2">
                                                            <span class="badge badge-light">
                                                                <i class="fas fa-arrow-up me-1"></i>{{ $category->parent->name_ar }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                    
                                                    <!-- Category Type -->
                                                    <div class="d-flex gap-2">
                                                        @if($category->type == 'lesson')
                                                            <span class="badge badge-success">
                                                                <i class="fas fa-book-open me-1"></i>{{ __('messages.Lesson') }}
                                                            </span>
                                                        @elseif($category->type == 'major')
                                                            <span class="badge badge-primary">
                                                                <i class="fas fa-star me-1"></i>{{ __('messages.Major') }}
                                                            </span>
                                                        @else
                                                            <span class="badge badge-secondary">
                                                                <i class="fas fa-folder me-1"></i>{{ __('messages.Class') }}
                                                            </span>
                                                        @endif
                                                        
                                                        @if($category->is_active)
                                                            <span class="badge badge-success">{{ __('messages.Active') }}</span>
                                                        @else
                                                            <span class="badge badge-danger">{{ __('messages.Inactive') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">{{ __('messages.No categories assigned') }}</h5>
                            <p class="text-muted">{{ __('messages.This package has no categories assigned to it yet.') }}</p>
                            @can('package-edit')
                                <a href="{{ route('packages.edit', $package) }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>{{ __('messages.Add Categories') }}
                                </a>
                            @endcan
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Package Actions & Stats -->
        <div class="col-md-4">
            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('messages.Quick Actions') }}</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @can('package-edit')
                            <a href="{{ route('packages.edit', $package) }}" class="btn btn-primary">
                                <i class="fas fa-edit me-2"></i>{{ __('messages.Edit Package') }}
                            </a>
                        @endcan
                        
                        @can('package-edit')
                            <form method="POST" action="{{ route('packages.toggle-status', $package) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-{{ $package->status === 'active' ? 'warning' : 'success' }} w-100">
                                    <i class="fas fa-{{ $package->status === 'active' ? 'pause' : 'play' }} me-2"></i>
                                    {{ $package->status === 'active' ? __('messages.Deactivate') : __('messages.Activate') }}
                                </button>
                            </form>
                        @endcan
                        
                        @can('package-delete')
                            <form method="POST" action="{{ route('packages.destroy', $package) }}" 
                                  onsubmit="return confirm('{{ __('messages.Are you sure you want to delete this package?') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    <i class="fas fa-trash me-2"></i>{{ __('messages.Delete Package') }}
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>

            <!-- Package Statistics -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('messages.Package Statistics') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h4 class="mb-0">{{ $package->categories->count() }}</h4>
                                    <small>{{ __('messages.Total Categories') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h4 class="mb-0">{{ $package->categories->where('is_active', true)->count() }}</h4>
                                    <small>{{ __('messages.Active Categories') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h4 class="mb-0">{{ $package->categories->where('type', 'lesson')->count() }}</h4>
                                    <small>{{ __('messages.Lessons') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h4 class="mb-0">{{ $package->how_much_course_can_select }}</h4>
                                    <small>{{ __('messages.Max Selection') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection