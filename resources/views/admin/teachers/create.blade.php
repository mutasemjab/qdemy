@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.Add New Teacher') }}</h3>
                    <a href="{{ route('teachers.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('messages.Back to Teachers') }}
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('teachers.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Teacher Information -->
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">{{ __('messages.Teacher Information') }}</h5>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="required">{{ __('messages.Teacher Name') }}</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name_of_lesson" class="required">{{ __('messages.Lesson/Subject') }}</label>
                                    <input type="text" class="form-control @error('name_of_lesson') is-invalid @enderror" 
                                           id="name_of_lesson" name="name_of_lesson" value="{{ old('name_of_lesson') }}" required>
                                    @error('name_of_lesson')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="photo">{{ __('messages.Teacher Photo') }}</label>
                                    <input type="file" class="form-control-file @error('photo') is-invalid @enderror" 
                                           id="photo" name="photo" accept="image/*">
                                    @error('photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('messages.Accepted formats: jpeg, png, jpg, gif. Max size: 2MB') }}</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('messages.Create User Account') }}</label>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="create_user_account" 
                                               name="create_user_account" value="1" {{ old('create_user_account') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="create_user_account">
                                            {{ __('messages.Create login account for this teacher') }}
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">{{ __('messages.This will allow the teacher to log in to the system') }}</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description_en">{{ __('messages.Description (English)') }}</label>
                                    <textarea class="form-control @error('description_en') is-invalid @enderror" 
                                              id="description_en" name="description_en" rows="3">{{ old('description_en') }}</textarea>
                                    @error('description_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description_ar">{{ __('messages.Description (Arabic)') }}</label>
                                    <textarea class="form-control @error('description_ar') is-invalid @enderror" 
                                              id="description_ar" name="description_ar" rows="3">{{ old('description_ar') }}</textarea>
                                    @error('description_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Social Media Links -->
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">{{ __('messages.Social Media Links') }}</h5>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="facebook">
                                        <i class="fab fa-facebook text-primary"></i> {{ __('messages.Facebook') }}
                                    </label>
                                    <input type="url" class="form-control @error('facebook') is-invalid @enderror" 
                                           id="facebook" name="facebook" value="{{ old('facebook') }}" 
                                           placeholder="https://facebook.com/username">
                                    @error('facebook')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="instagram">
                                        <i class="fab fa-instagram text-danger"></i> {{ __('messages.Instagram') }}
                                    </label>
                                    <input type="url" class="form-control @error('instagram') is-invalid @enderror" 
                                           id="instagram" name="instagram" value="{{ old('instagram') }}" 
                                           placeholder="https://instagram.com/username">
                                    @error('instagram')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="youtube">
                                        <i class="fab fa-youtube text-danger"></i> {{ __('messages.YouTube') }}
                                    </label>
                                    <input type="url" class="form-control @error('youtube') is-invalid @enderror" 
                                           id="youtube" name="youtube" value="{{ old('youtube') }}" 
                                           placeholder="https://youtube.com/channel/...">
                                    @error('youtube')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="whataspp">
                                        <i class="fab fa-whataspp text-danger"></i> {{ __('messages.whataspp') }}
                                    </label>
                                    <input type="url" class="form-control @error('whataspp') is-invalid @enderror" 
                                           id="whataspp" name="whataspp" value="{{ old('whataspp') }}" 
                                           placeholder="https://wa.me/...">
                                    @error('whataspp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- User Account Information (conditionally shown) -->
                        <div id="user-account-section" style="display: none;">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="text-success mb-3">{{ __('messages.User Account Information') }}</h5>
                                    <p class="text-muted">{{ __('messages.Fill in the details to create a login account for this teacher') }}</p>
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

                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ __('messages.Create Teacher') }}
                            </button>
                            <a href="{{ route('teachers.index') }}" class="btn btn-secondary">
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
</style>
@endsection