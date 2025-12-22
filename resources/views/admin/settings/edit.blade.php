@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">{{ __('messages.Edit Setting') }} #{{ $setting->id }}</h3>
                        <a href="{{ route('settings.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.Back to Settings') }}
                        </a>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('settings.update', $setting) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Current Logo Display -->
                            @if ($setting->logo)
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>{{ __('messages.Current Logo') }}</label>
                                            <div>
                                                <img src="{{ asset('assets/admin/uploads/' . $setting->logo) }}"
                                                    alt="Current Logo" class="img-thumbnail"
                                                    style="max-width: 200px; max-height: 150px; object-fit: contain;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Logo and Basic Info -->
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">{{ __('messages.Basic Information') }}</h5>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="logo">{{ __('messages.New Logo') }}</label>
                                        <input type="file" class="form-control-file @error('logo') is-invalid @enderror"
                                            id="logo" name="logo" accept="image/*">
                                        @error('logo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small
                                            class="form-text text-muted">{{ __('messages.Leave empty to keep current logo') }}</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="required">{{ __('messages.Email') }}</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email', $setting->email) }}"
                                            required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone" class="required">{{ __('messages.Phone') }}</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" name="phone" value="{{ old('phone', $setting->phone) }}"
                                            required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="facebook" class="required">{{ __('messages.Facebook') }}</label>
                                        <input type="text" class="form-control @error('facebook') is-invalid @enderror"
                                            id="facebook" name="facebook"
                                            value="{{ old('facebook', $setting->facebook) }}" required>
                                        @error('facebook')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="instagram" class="required">{{ __('messages.Instagram') }}</label>
                                        <input type="text" class="form-control @error('instagram') is-invalid @enderror"
                                            id="instagram" name="instagram"
                                            value="{{ old('instagram', $setting->instagram) }}" required>
                                        @error('instagram')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="youtube" class="required">{{ __('messages.YouTube') }}</label>
                                        <input type="text" class="form-control @error('youtube') is-invalid @enderror"
                                            id="youtube" name="youtube" value="{{ old('youtube', $setting->youtube) }}" required>
                                        @error('youtube')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tiktok" class="required">{{ __('messages.TikTok') }}</label>
                                        <input type="text" class="form-control @error('tiktok') is-invalid @enderror"
                                            id="tiktok" name="tiktok" value="{{ old('tiktok', $setting->tiktok) }}" required>
                                        @error('tiktok')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address">{{ __('messages.Address') }}</label>
                                        <input type="text" class="form-control @error('address') is-invalid @enderror"
                                            id="address" name="address" value="{{ old('address', $setting->address) }}">
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="text_under_logo_in_footer"
                                            class="required">{{ __('messages.Footer Text') }}</label>
                                        <textarea class="form-control @error('text_under_logo_in_footer') is-invalid @enderror" id="text_under_logo_in_footer"
                                            name="text_under_logo_in_footer" rows="4" required>{{ old('text_under_logo_in_footer', $setting->text_under_logo_in_footer) }}</textarea>
                                        @error('text_under_logo_in_footer')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small
                                            class="form-text text-muted">{{ __('messages.Text that appears under the logo in footer') }}</small>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- App Store Links -->
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">{{ __('messages.App Store Links') }}</h5>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="google_play_link">{{ __('messages.Google Play Link') }}</label>
                                        <input type="url"
                                            class="form-control @error('google_play_link') is-invalid @enderror"
                                            id="google_play_link" name="google_play_link"
                                            value="{{ old('google_play_link', $setting->google_play_link) }}">
                                        @error('google_play_link')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="app_store_link">{{ __('messages.App Store Link') }}</label>
                                        <input type="url"
                                            class="form-control @error('app_store_link') is-invalid @enderror"
                                            id="app_store_link" name="app_store_link"
                                            value="{{ old('app_store_link', $setting->app_store_link) }}">
                                        @error('app_store_link')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="hawawi_link">{{ __('messages.Huawei AppGallery Link') }}</label>
                                        <input type="url"
                                            class="form-control @error('hawawi_link') is-invalid @enderror"
                                            id="hawawi_link" name="hawawi_link"
                                            value="{{ old('hawawi_link', $setting->hawawi_link) }}">
                                        @error('hawawi_link')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Minimum Versions -->
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">{{ __('messages.Minimum App Versions') }}</h5>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label
                                            for="min_version_google_play">{{ __('messages.Min Version Google Play') }}</label>
                                        <input type="text"
                                            class="form-control @error('min_version_google_play') is-invalid @enderror"
                                            id="min_version_google_play" name="min_version_google_play"
                                            value="{{ old('min_version_google_play', $setting->min_version_google_play) }}"
                                            placeholder="1.0.0">
                                        @error('min_version_google_play')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label
                                            for="min_version_app_store">{{ __('messages.Min Version App Store') }}</label>
                                        <input type="text"
                                            class="form-control @error('min_version_app_store') is-invalid @enderror"
                                            id="min_version_app_store" name="min_version_app_store"
                                            value="{{ old('min_version_app_store', $setting->min_version_app_store) }}"
                                            placeholder="1.0.0">
                                        @error('min_version_app_store')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="min_version_hawawi">{{ __('messages.Min Version Huawei') }}</label>
                                        <input type="text"
                                            class="form-control @error('min_version_hawawi') is-invalid @enderror"
                                            id="min_version_hawawi" name="min_version_hawawi"
                                            value="{{ old('min_version_hawawi', $setting->min_version_hawawi) }}"
                                            placeholder="1.0.0">
                                        @error('min_version_hawawi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Statistics -->
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="text-primary mb-3">{{ __('messages.Statistics') }}</h5>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="number_of_students">{{ __('messages.Number of Students') }}</label>
                                        <input type="text"
                                            class="form-control @error('number_of_students') is-invalid @enderror"
                                            id="number_of_students" name="number_of_students"
                                            value="{{ old('number_of_students', $setting->number_of_students) }}"
                                            placeholder="+3 Million">
                                        @error('number_of_students')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="number_of_teacher">{{ __('messages.Number of Teachers') }}</label>
                                        <input type="text"
                                            class="form-control @error('number_of_teacher') is-invalid @enderror"
                                            id="number_of_teacher" name="number_of_teacher"
                                            value="{{ old('number_of_teacher', $setting->number_of_teacher) }}"
                                            placeholder="+1 Thousand">
                                        @error('number_of_teacher')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="number_of_course">{{ __('messages.Number of Courses') }}</label>
                                        <input type="text"
                                            class="form-control @error('number_of_course') is-invalid @enderror"
                                            id="number_of_course" name="number_of_course"
                                            value="{{ old('number_of_course', $setting->number_of_course) }}"
                                            placeholder="+20 Thousand">
                                        @error('number_of_course')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label
                                            for="number_of_viewing_hour">{{ __('messages.Number of Viewing Hours') }}</label>
                                        <input type="text"
                                            class="form-control @error('number_of_viewing_hour') is-invalid @enderror"
                                            id="number_of_viewing_hour" name="number_of_viewing_hour"
                                            value="{{ old('number_of_viewing_hour', $setting->number_of_viewing_hour) }}"
                                            placeholder="+2 Million">
                                        @error('number_of_viewing_hour')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Read-only Information -->
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="text-secondary mb-3">{{ __('messages.Record Information') }}</h5>
                                </div>




                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> {{ __('messages.Update Setting') }}
                                </button>
                                <a href="{{ route('settings.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> {{ __('messages.Cancel') }}
                                </a>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .required:after {
            content: " *";
            color: red;
        }
    </style>
@endsection
