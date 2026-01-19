@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">{{ __('messages.Edit Teacher') }}: {{ $teacher->name }}</h3>
                        <a href="{{ route('teachers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.Back to Teachers') }}
                        </a>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('teachers.update', $teacher) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Current Photo Display -->
                            @if ($teacher->photo)
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>{{ __('messages.Current Photo') }}</label>
                                            <div>
                                                <img src="{{ $teacher->photo
                                                    ? asset('assets/admin/uploads/' . $teacher->photo)
                                                    : asset('assets_front/images/Profile-picture.jpg') }}"
                                                    alt="{{ $teacher->name }}" class="img-thumbnail"
                                                    style="max-width: 200px; max-height: 200px; object-fit: cover;">
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Teacher Information -->
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">{{ __('messages.Teacher Information') }}</h5>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="required">{{ __('messages.Teacher Name') }}</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', $teacher->name) }}"
                                            required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name_of_lesson"
                                            class="required">{{ __('messages.Lesson/Subject') }}</label>
                                        <input type="text"
                                            class="form-control @error('name_of_lesson') is-invalid @enderror"
                                            id="name_of_lesson" name="name_of_lesson"
                                            value="{{ old('name_of_lesson', $teacher->name_of_lesson) }}" required>
                                        @error('name_of_lesson')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="photo">{{ __('messages.New Photo') }}</label>
                                        <input type="file" class="form-control-file @error('photo') is-invalid @enderror"
                                            id="photo" name="photo" accept="image/*">
                                        @error('photo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small
                                            class="form-text text-muted">{{ __('messages.Leave empty to keep current photo') }}</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    @if ($teacher->user)
                                        <div class="form-group">
                                            <label>{{ __('messages.User Account Status') }}</label>
                                            <div class="alert alert-success">
                                                <i class="fas fa-user-check"></i>
                                                {{ __('messages.This teacher has a user account') }}
                                                <br><small>{{ __('messages.Email') }}: {{ $teacher->user->email }}</small>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="update_user_info"
                                                    name="update_user_info" value="1"
                                                    {{ old('update_user_info') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="update_user_info">
                                                    {{ __('messages.Update user account information') }}
                                                </label>
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i>
                                            {{ __('messages.This teacher does not have a user account') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description_en">{{ __('messages.Description (English)') }}</label>
                                        <textarea class="form-control @error('description_en') is-invalid @enderror" id="description_en" name="description_en"
                                            rows="3">{{ old('description_en', $teacher->description_en) }}</textarea>
                                        @error('description_en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description_ar">{{ __('messages.Description (Arabic)') }}</label>
                                        <textarea class="form-control @error('description_ar') is-invalid @enderror" id="description_ar" name="description_ar"
                                            rows="3">{{ old('description_ar', $teacher->description_ar) }}</textarea>
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
                                            id="facebook" name="facebook"
                                            value="{{ old('facebook', $teacher->facebook) }}"
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
                                        <input type="url"
                                            class="form-control @error('instagram') is-invalid @enderror" id="instagram"
                                            name="instagram" value="{{ old('instagram', $teacher->instagram) }}"
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
                                            id="youtube" name="youtube"
                                            value="{{ old('youtube', $teacher->youtube) }}"
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
                                            id="whataspp" name="whataspp"
                                            value="{{ old('whataspp', $teacher->whataspp) }}"
                                            placeholder="https://wa.me/...">
                                        @error('whataspp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                            </div>

                            <hr>

                            <!-- User Account Information (conditionally shown) -->
                            @if ($teacher->user)
                                <div id="user-account-section" style="display: none;">
                                    <div class="row">
                                        <div class="col-12">
                                            <h5 class="text-success mb-3">
                                                {{ __('messages.Update User Account Information') }}</h5>
                                            <p class="text-muted">
                                                {{ __('messages.Update the login account details for this teacher') }}</p>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email">{{ __('messages.Email') }}</label>
                                                <input type="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    id="email" name="email"
                                                    value="{{ old('email', $teacher->user->email) }}">
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="phone">{{ __('messages.Phone') }}</label>
                                                <input type="text"
                                                    class="form-control @error('phone') is-invalid @enderror"
                                                    id="phone" name="phone"
                                                    value="{{ old('phone', $teacher->user->phone) }}">
                                                @error('phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="password">{{ __('messages.New Password') }}</label>
                                                <input type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    id="password" name="password">
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <small
                                                    class="form-text text-muted">{{ __('messages.Leave empty to keep current password') }}</small>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="activate"
                                                    class="required">{{ __('messages.Status') }}</label>
                                                <select class="form-control @error('activate') is-invalid @enderror"
                                                    id="activate" name="activate" required>
                                                    <option value="1"
                                                        {{ old('activate', $teacher->user->activate) == 1 ? 'selected' : '' }}>
                                                        {{ __('messages.Active') }}
                                                    </option>
                                                    <option value="2"
                                                        {{ old('activate', $teacher->user->activate) == 2 ? 'selected' : '' }}>
                                                        {{ __('messages.Inactive') }}
                                                    </option>
                                                </select>
                                                @error('activate')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
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

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('messages.Created At') }}</label>
                                        <input type="text" class="form-control"
                                            value="{{ $teacher->created_at->format('Y-m-d H:i:s') }}" readonly>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('messages.Last Updated') }}</label>
                                        <input type="text" class="form-control"
                                            value="{{ $teacher->updated_at->format('Y-m-d H:i:s') }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> {{ __('messages.Update Teacher') }}
                                </button>
                                <a href="{{ route('teachers.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> {{ __('messages.Cancel') }}
                                </a>
                                <a href="{{ route('teachers.show', $teacher) }}" class="btn btn-info">
                                    <i class="fas fa-eye"></i> {{ __('messages.View Teacher') }}
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($teacher->user)
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
    </style>
@endsection
