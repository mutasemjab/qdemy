@extends("layouts.admin")

@section('title', __('messages.Create Role'))

@section('css')
<style>
.permission-group {
    border: 1px solid #e3e6f0;
    border-radius: 0.35rem;
    margin-bottom: 1rem;
}

.permission-group-header {
    background-color: #f8f9fc;
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #e3e6f0;
    font-weight: 600;
    color: #5a5c69;
}

.permission-group-body {
    padding: 1rem;
}

.select-all-group {
    margin-bottom: 0.75rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #e3e6f0;
}

.permission-label {
    font-weight: 500;
    color: #495057;
    cursor: pointer;
}

.permission-description {
    font-size: 0.875rem;
    color: #6c757d;
    margin-left: 1.5rem;
}
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.role.index') }}">{{ __('messages.Roles') }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ __('messages.Create') }}</li>
                    </ol>
                </div>
                <h4 class="page-title">{{ __('messages.Create Role') }}</h4>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('messages.Role Information') }}</h5>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.role.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="name" class="form-label">{{ __('messages.Role Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           placeholder="{{ __('messages.Enter role name') }}" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5>{{ __('messages.Permissions') }} <span class="text-danger">*</span></h5>
                            <p class="text-muted">{{ __('messages.Select permissions for this role') }}</p>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="select-all-permissions">
                                    <label class="form-check-label fw-bold" for="select-all-permissions">
                                        {{ __('messages.Select All Permissions') }}
                                    </label>
                                </div>
                            </div>

                            @error('permissions')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror

                            <div class="row">
                                @foreach($permissionGroups as $groupName => $permissions)
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="permission-group">
                                            <div class="permission-group-header">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span>{{ __('messages.' . ucfirst($groupName)) }}</span>
                                                    <div class="form-check">
                                                        <input type="checkbox" 
                                                               class="form-check-input select-group" 
                                                               id="select-group-{{ $groupName }}" 
                                                               data-group="{{ $groupName }}">
                                                        <label class="form-check-label small" for="select-group-{{ $groupName }}">
                                                            {{ __('messages.Select All') }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="permission-group-body">
                                                @foreach($permissions as $permission)
                                                    <div class="permission-item">
                                                        <div class="form-check">
                                                            <input type="checkbox" 
                                                                   class="form-check-input permission-checkbox group-{{ $groupName }}" 
                                                                   name="permissions[]" 
                                                                   id="permission-{{ $permission->id }}" 
                                                                   value="{{ $permission->id }}"
                                                                   {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                                            <label class="form-check-label permission-label" 
                                                                   for="permission-{{ $permission->id }}">
                                                                {{ __('messages.' . str_replace('-', '_', $permission->name)) }}
                                                            </label>
                                                        </div>
                                                        
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.role.index') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-times"></i> {{ __('messages.Cancel') }}
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> {{ __('messages.Save Role') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    // Select all permissions
    $('#select-all-permissions').change(function() {
        $('.permission-checkbox').prop('checked', $(this).is(':checked'));
        $('.select-group').prop('checked', $(this).is(':checked'));
    });

    // Select group permissions
    $('.select-group').change(function() {
        const group = $(this).data('group');
        $(`.group-${group}`).prop('checked', $(this).is(':checked'));
        updateSelectAllStatus();
    });

    // Individual permission change
    $('.permission-checkbox').change(function() {
        const group = $(this).attr('class').match(/group-(\w+)/);
        if (group) {
            updateGroupSelectStatus(group[1]);
        }
        updateSelectAllStatus();
    });

    function updateGroupSelectStatus(groupName) {
        const totalInGroup = $(`.group-${groupName}`).length;
        const checkedInGroup = $(`.group-${groupName}:checked`).length;
        
        $(`#select-group-${groupName}`).prop('checked', totalInGroup === checkedInGroup);
    }

    function updateSelectAllStatus() {
        const totalPermissions = $('.permission-checkbox').length;
        const checkedPermissions = $('.permission-checkbox:checked').length;
        
        $('#select-all-permissions').prop('checked', totalPermissions === checkedPermissions);
        
        // Update group select statuses
        $('.select-group').each(function() {
            const groupName = $(this).data('group');
            updateGroupSelectStatus(groupName);
        });
    }
});
</script>
@endsection