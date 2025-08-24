@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.Add New Student Opinion') }}</h3>
                    <a href="{{ route('opinions.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('messages.Back to Opinions') }}
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('opinions.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="required">
                                        <i class="fas fa-user text-info"></i> {{ __('messages.Student Name') }}
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" 
                                           placeholder="{{ __('messages.Enter student name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="number_of_star" class="required">
                                        <i class="fas fa-star text-warning"></i> {{ __('messages.Rating') }}
                                    </label>
                                    <select class="form-control @error('number_of_star') is-invalid @enderror" 
                                            id="number_of_star" name="number_of_star" required>
                                        <option value="">{{ __('messages.Select Rating') }}</option>
                                        <option value="5" {{ old('number_of_star') == '5' ? 'selected' : '' }}>
                                            ⭐⭐⭐⭐⭐ (5.0) - {{ __('messages.Excellent') }}
                                        </option>
                                        <option value="4.5" {{ old('number_of_star') == '4.5' ? 'selected' : '' }}>
                                            ⭐⭐⭐⭐⭐ (4.5) - {{ __('messages.Very Good') }}
                                        </option>
                                        <option value="4" {{ old('number_of_star') == '4' ? 'selected' : '' }}>
                                            ⭐⭐⭐⭐ (4.0) - {{ __('messages.Good') }}
                                        </option>
                                        <option value="3.5" {{ old('number_of_star') == '3.5' ? 'selected' : '' }}>
                                            ⭐⭐⭐⭐ (3.5) - {{ __('messages.Above Average') }}
                                        </option>
                                        <option value="3" {{ old('number_of_star') == '3' ? 'selected' : '' }}>
                                            ⭐⭐⭐ (3.0) - {{ __('messages.Average') }}
                                        </option>
                                        <option value="2.5" {{ old('number_of_star') == '2.5' ? 'selected' : '' }}>
                                            ⭐⭐⭐ (2.5) - {{ __('messages.Below Average') }}
                                        </option>
                                        <option value="2" {{ old('number_of_star') == '2' ? 'selected' : '' }}>
                                            ⭐⭐ (2.0) - {{ __('messages.Poor') }}
                                        </option>
                                        <option value="1.5" {{ old('number_of_star') == '1.5' ? 'selected' : '' }}>
                                            ⭐⭐ (1.5) - {{ __('messages.Very Poor') }}
                                        </option>
                                        <option value="1" {{ old('number_of_star') == '1' ? 'selected' : '' }}>
                                            ⭐ (1.0) - {{ __('messages.Terrible') }}
                                        </option>
                                    </select>
                                    @error('number_of_star')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title" class="required">
                                        <i class="fas fa-heading text-primary"></i> {{ __('messages.Opinion Title') }}
                                    </label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title') }}" 
                                           placeholder="{{ __('messages.Brief title for the opinion') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="photo" class="required">
                                        <i class="fas fa-camera text-secondary"></i> {{ __('messages.Student Photo') }}
                                    </label>
                                    <input type="file" class="form-control-file @error('photo') is-invalid @enderror" 
                                           id="photo" name="photo" accept="image/*" required>
                                    @error('photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('messages.Accepted formats: jpeg, png, jpg, gif. Max size: 2MB') }}</small>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="description" class="required">
                                        <i class="fas fa-comment text-success"></i> {{ __('messages.Opinion Description') }}
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="5" 
                                              placeholder="{{ __('messages.Share the detailed opinion or feedback') }}" required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('messages.Describe the student experience in detail') }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ __('messages.Create Opinion') }}
                            </button>
                            <a href="{{ route('opinions.index') }}" class="btn btn-secondary">
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

.form-group label {
    font-weight: 600;
    color: #333;
}

.form-control {
    border-radius: 6px;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

textarea.form-control {
    resize: vertical;
    min-height: 120px;
}

.form-control-file {
    padding: 8px 0;
}
</style>
@endsection