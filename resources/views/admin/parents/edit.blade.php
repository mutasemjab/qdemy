@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.Edit Parent') }}: {{ $parent->name }}</h3>
                    <a href="{{ route('parents.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('messages.Back to Parents') }}
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('parents.update', $parent) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Current Photo Display -->
                        @if($parent->user && $parent->user->photo)
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>{{ __('messages.Current Photo') }}</label>
                                        <div>
                                            <img src="{{ $parent->user->photo 
                                                ? asset('assets/admin/uploads/' . $parent->user->photo) 
                                                : asset('assets_front/images/Profile-picture.jpg') }}" 
                                                alt="{{ $parent->name }}" 
                                                class="img-thumbnail"
                                                style="max-width: 200px; max-height: 200px; object-fit: cover;">
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Parent Information -->
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">{{ __('messages.Parent Information') }}</h5>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="required">{{ __('messages.Parent Name') }}</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $parent->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                @if($parent->user)
                                    <div class="form-group">
                                        <label>{{ __('messages.User Account Status') }}</label>
                                        <div class="alert alert-success">
                                            <i class="fas fa-user-check"></i> {{ __('messages.This parent has a user account') }}
                                            <br><small>{{ __('messages.Email') }}: {{ $parent->user->email }}</small>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="update_user_info" 
                                                   name="update_user_info" value="1" {{ old('update_user_info') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="update_user_info">
                                                {{ __('messages.Update user account information') }}
                                            </label>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i> {{ __('messages.This parent does not have a user account') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <!-- Student Assignment -->
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-success mb-3">{{ __('messages.Manage Students') }}</h5>
                                <p class="text-muted">{{ __('messages.Select the students that belong to this parent') }}</p>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="student_ids">{{ __('messages.Students') }}</label>
                                    @if($students->count() > 0)
                                        <div class="row">
                                            @foreach($students as $student)
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" 
                                                               id="student_{{ $student->id }}" 
                                                               name="student_ids[]" 
                                                               value="{{ $student->id }}"
                                                               {{ in_array($student->id, old('student_ids', $selectedStudentIds)) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="student_{{ $student->id }}">
                                                            <div class="d-flex align-items-center">
                                                                @if($student->photo)
                                                                    <img src="{{ asset('storage/' . $student->photo) }}" 
                                                                         alt="{{ $student->name }}" 
                                                                         class="rounded me-2" 
                                                                         style="width: 30px; height: 30px; object-fit: cover;">
                                                                @else
                                                                    <div class="bg-info text-white rounded me-2 d-flex align-items-center justify-content-center" 
                                                                         style="width: 30px; height: 30px; font-size: 0.8rem;">
                                                                        {{ substr($student->name, 0, 1) }}
                                                                    </div>
                                                                @endif
                                                                <div>
                                                                    <strong>{{ $student->name }}</strong>
                                                                    @if($student->email)
                                                                        <br><small class="text-muted">{{ $student->email }}</small>
                                                                    @endif
                                                                    @if(in_array($student->id, $selectedStudentIds))
                                                                        <br><span class="badge badge-success badge-sm">{{ __('messages.Currently Assigned') }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i> {{ __('messages.No students available to assign') }}
                                        </div>
                                    @endif
                                    @error('student_ids')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- User Account Information (conditionally shown) -->
                        @if($parent->user)
                        <div id="user-account-section" style="display: none;">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="text-warning mb-3">{{ __('messages.Update User Account Information') }}</h5>
                                    <p class="text-muted">{{ __('messages.Update the login account details for this parent') }}</p>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">{{ __('messages.Email') }}</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ old('email', $parent->user->email) }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">{{ __('messages.Phone') }}</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" name="phone" value="{{ old('phone', $parent->user->phone) }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">{{ __('messages.New Password') }}</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                               id="password" name="password">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">{{ __('messages.Leave empty to keep current password') }}</small>
                                    </div>
                                </div>

                         

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="photo">{{ __('messages.New Photo') }}</label>
                                        <input type="file" class="form-control-file @error('photo') is-invalid @enderror" 
                                               id="photo" name="photo" accept="image/*">
                                        @error('photo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">{{ __('messages.Leave empty to keep current photo') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Read-only Information -->
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-secondary mb-3">{{ __('messages.Record Information') }}</h5>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('messages.Created At') }}</label>
                                    <input type="text" class="form-control" 
                                           value="{{ $parent->created_at->format('Y-m-d H:i:s') }}" readonly>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('messages.Last Updated') }}</label>
                                    <input type="text" class="form-control" 
                                           value="{{ $parent->updated_at->format('Y-m-d H:i:s') }}" readonly>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('messages.Total Students') }}</label>
                                    <input type="text" class="form-control" 
                                           value="{{ $parent->students->count() }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ __('messages.Update Parent') }}
                            </button>
                            <a href="{{ route('parents.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> {{ __('messages.Cancel') }}
                            </a>
                            <a href="{{ route('parents.show', $parent) }}" class="btn btn-info">
                                <i class="fas fa-eye"></i> {{ __('messages.View Parent') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@if($parent->user)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const updateUserCheckbox = document.getElementById('update_user_info');
    const userAccountSection = document.getElementById('user-account-section');

    function toggleUserAccountSection() {
        if (updateUserCheckbox.checked) {
            userAccountSection.style.display = 'block';
        } else {
            userAccountSection.style.display = 'none';
        }
    }

    updateUserCheckbox.addEventListener('change', toggleUserAccountSection);
    
    // Initialize on page load
    toggleUserAccountSection();
});
</script>
@endif

<style>
.required:after {
    content: " *";
    color: red;
}

.form-check-label {
    cursor: pointer;
}

.form-check {
    margin-bottom: 1rem;
    padding: 0.5rem;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    transition: background-color 0.15s ease-in-out;
}

.form-check:hover {
    background-color: #f8f9fa;
}

.form-check-input:checked + .form-check-label {
    font-weight: bold;
}

.badge-sm {
    font-size: 0.7rem;
}
</style>
@endsection