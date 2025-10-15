@extends('layouts.admin')

@section('title', __('messages.Packages'))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">{{ __('messages.Packages') }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('messages.Dashboard') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('messages.Packages') }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            @can('package-add')
                <a href="{{ route('packages.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>{{ __('messages.Add Package') }}
                </a>
            @endcan
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('packages.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">{{ __('messages.Search') }}</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="{{ __('messages.Search packages...') }}">
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">{{ __('messages.Status') }}</label>
                    <select class="form-control" id="status" name="status">
                        <option value="">{{ __('messages.All Statuses') }}</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                            {{ __('messages.Active') }}
                        </option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>
                            {{ __('messages.Inactive') }}
                        </option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="type" class="form-label">{{ __('messages.Type') }}</label>
                    <select class="form-control" id="type" name="type">
                        <option value="">{{ __('messages.All Types') }}</option>
                        <option value="class" {{ request('type') === 'class' ? 'selected' : '' }}>
                            {{ __('messages.Class') }}
                        </option>
                        <option value="subject" {{ request('type') === 'subject' ? 'selected' : '' }}>
                            {{ __('messages.Subject') }}
                        </option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <div class="d-flex gap-2 w-100">
                        <button type="submit" class="btn btn-outline-primary flex-grow-1">
                            <i class="fas fa-search"></i>
                        </button>
                        <a href="{{ route('packages.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Packages Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('messages.Packages List') }}</h5>
            @if($packages->count() > 0)
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-success" onclick="bulkAction('activate')">
                        <i class="fas fa-check"></i> {{ __('messages.Activate Selected') }}
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-warning" onclick="bulkAction('deactivate')">
                        <i class="fas fa-pause"></i> {{ __('messages.Deactivate Selected') }}
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="bulkAction('delete')">
                        <i class="fas fa-trash"></i> {{ __('messages.Delete Selected') }}
                    </button>
                </div>
            @endif
        </div>
        <div class="card-body p-0">
            @if($packages->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="30">
                                    <input type="checkbox" id="select-all" class="form-check-input">
                                </th>
                                <th width="80">{{ __('messages.Image') }}</th>
                                <th>{{ __('messages.Name') }}</th>
                                <th>{{ __('messages.Price') }}</th>
                                <th>{{ __('messages.Type') }}</th>
                                <th>{{ __('messages.Course Selection') }}</th>
                                <th>{{ __('messages.Categories') }}</th>
                                <th>{{ __('messages.Subjects') }}</th>
                                <th>{{ __('messages.Status') }}</th>
                                <th width="150">{{ __('messages.Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($packages as $package)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="selected_packages[]" 
                                               value="{{ $package->id }}" class="form-check-input package-checkbox">
                                    </td>
                                    <td>
                                        @if($package->image)
                                            <img src="{{ $package->image_url }}" alt="{{ $package->name }}" 
                                                 class="img-thumbnail" width="50" height="50" style="object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                                 style="width: 50px; height: 50px; border-radius: 0.375rem;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $package->name }}</strong>
                                            @if($package->description)
                                                <br><small class="text-muted">{{ Str::limit($package->description, 50) }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ $package->formatted_price }}</span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $package->type_badge_class }}">
                                            {{ ucfirst($package->type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">
                                            {{ $package->how_much_course_can_select }} {{ __('messages.Courses') }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($package->categories->count() > 0)
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach($package->categories->take(2) as $category)
                                                    <span class="badge badge-secondary small">
                                                        {{ $category->name_ar }}
                                                    </span>
                                                @endforeach
                                                @if($package->categories->count() > 2)
                                                    <span class="badge badge-light small">
                                                        +{{ $package->categories->count() - 2 }}
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted">{{ __('messages.No categories') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($package->subjects->count() > 0)
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach($package->subjects->take(2) as $subject)
                                                    <span class="badge badge-primary small">
                                                        {{ $subject->name_ar }}
                                                    </span>
                                                @endforeach
                                                @if($package->subjects->count() > 2)
                                                    <span class="badge badge-light small">
                                                        +{{ $package->subjects->count() - 2 }}
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted">{{ __('messages.No subjects') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $package->status_badge_class }}">
                                            {{ ucfirst($package->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @can('package-table')
                                                <a href="{{ route('packages.show', $package) }}" 
                                                   class="btn btn-sm btn-outline-info" title="{{ __('messages.View') }}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endcan
                                            
                                            @can('package-edit')
                                                <a href="{{ route('packages.edit', $package) }}" 
                                                   class="btn btn-sm btn-outline-primary" title="{{ __('messages.Edit') }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endcan
                                            
                                            @can('package-edit')
                                                <form method="POST" action="{{ route('packages.toggle-status', $package) }}" 
                                                      class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-{{ $package->status === 'active' ? 'warning' : 'success' }}" 
                                                            title="{{ $package->status === 'active' ? __('messages.Deactivate') : __('messages.Activate') }}">
                                                        <i class="fas fa-{{ $package->status === 'active' ? 'pause' : 'play' }}"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                            
                                            @can('package-delete')
                                                <form method="POST" action="{{ route('packages.destroy', $package) }}" 
                                                      class="d-inline" 
                                                      onsubmit="return confirm('{{ __('messages.Are you sure you want to delete this package?') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                            title="{{ __('messages.Delete') }}">
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
            @else
                <div class="text-center py-5">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">{{ __('messages.No packages found') }}</h5>
                    <p class="text-muted">{{ __('messages.Try adjusting your search criteria or add a new package') }}</p>
                    @can('package-add')
                        <a href="{{ route('packages.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>{{ __('messages.Add Package') }}
                        </a>
                    @endcan
                </div>
            @endif
        </div>
        
        @if($packages->hasPages())
            <div class="card-footer">
                {{ $packages->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Bulk Action Form -->
<form id="bulk-action-form" method="POST" action="{{ route('packages.bulk-action') }}" style="display: none;">
    @csrf
    <input type="hidden" name="action" id="bulk-action">
    <div id="bulk-packages"></div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all checkbox functionality
    const selectAllCheckbox = document.getElementById('select-all');
    const packageCheckboxes = document.querySelectorAll('.package-checkbox');

    selectAllCheckbox.addEventListener('change', function() {
        packageCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    packageCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedBoxes = document.querySelectorAll('.package-checkbox:checked');
            selectAllCheckbox.checked = checkedBoxes.length === packageCheckboxes.length;
        });
    });
});

function bulkAction(action) {
    const checkedBoxes = document.querySelectorAll('.package-checkbox:checked');
    
    if (checkedBoxes.length === 0) {
        alert('{{ __('messages.Please select at least one package') }}');
        return;
    }

    const actionMessages = {
        'activate': '{{ __('messages.Are you sure you want to activate selected packages?') }}',
        'deactivate': '{{ __('messages.Are you sure you want to deactivate selected packages?') }}',
        'delete': '{{ __('messages.Are you sure you want to delete selected packages? This action cannot be undone.') }}'
    };

    if (confirm(actionMessages[action])) {
        const form = document.getElementById('bulk-action-form');
        const actionInput = document.getElementById('bulk-action');
        const packagesContainer = document.getElementById('bulk-packages');
        
        actionInput.value = action;
        packagesContainer.innerHTML = '';
        
        checkedBoxes.forEach(checkbox => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'packages[]';
            input.value = checkbox.value;
            packagesContainer.appendChild(input);
        });
        
        form.submit();
    }
}
</script>
@endpush
@endsection