@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.Add New Parent') }}</h3>
                    <a href="{{ route('parents.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('messages.Back to Parents') }}
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('parents.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Parent Information -->
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">{{ __('messages.Parent Information') }}</h5>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="required">{{ __('messages.Parent Name') }}</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('messages.Create User Account') }}</label>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="create_user_account" 
                                               name="create_user_account" value="1" {{ old('create_user_account') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="create_user_account">
                                            {{ __('messages.Create login account for this parent') }}
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">{{ __('messages.This will allow the parent to log in to the system') }}</small>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Student Assignment -->
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-success mb-3">{{ __('messages.Assign Students') }}</h5>
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
                                                               {{ in_array($student->id, old('student_ids', [])) ? 'checked' : '' }}>
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
                        <div id="user-account-section" style="display: none;">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="text-warning mb-3">{{ __('messages.User Account Information') }}</h5>
                                    <p class="text-muted">{{ __('messages.Fill in the details to create a login account for this parent') }}</p>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="required-conditional">{{ __('messages.Email') }}</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ old('email') }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">{{ __('messages.Phone') }}</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" name="phone" value="{{ old('phone') }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password" class="required-conditional">{{ __('messages.Password') }}</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                               id="password" name="password">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">{{ __('messages.Minimum 6 characters') }}</small>
                                    </div>
                                </div>

                      

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="photo">{{ __('messages.Parent Photo') }}</label>
                                        <input type="file" class="form-control-file @error('photo') is-invalid @enderror" 
                                               id="photo" name="photo" accept="image/*">
                                        @error('photo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">{{ __('messages.Accepted formats: jpeg, png, jpg, gif. Max size: 2MB') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ __('messages.Create Parent') }}
                            </button>
                            <a href="{{ route('parents.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> {{ __('messages.Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const createUserCheckbox = document.getElementById('create_user_account');
    const userAccountSection = document.getElementById('user-account-section');
    const emailField = document.getElementById('email');
    const passwordField = document.getElementById('password');

    function toggleUserAccountSection() {
        if (createUserCheckbox.checked) {
            userAccountSection.style.display = 'block';
            emailField.required = true;
            passwordField.required = true;
        } else {
            userAccountSection.style.display = 'none';
            emailField.required = false;
            passwordField.required = false;
        }
    }

    createUserCheckbox.addEventListener('change', toggleUserAccountSection);
    
    // Initialize on page load
    toggleUserAccountSection();
});
</script>

<style>
.required:after,
.required-conditional:after {
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
</style>
@endsection