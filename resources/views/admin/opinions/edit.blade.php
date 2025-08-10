@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.Edit Student Opinion') }}</h3>
                    <div>
                      
                        <a href="{{ route('opinions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.Back to Opinions') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Current Opinion Preview -->
                        <div class="col-md-4">
                            <div class="current-opinion-preview">
                                <h5 class="text-primary mb-3">{{ __('messages.Current Opinion') }}</h5>
                                <div class="opinion-preview-card">
                                    <div class="preview-header">
                                        <div class="preview-avatar">
                                            @if($opinion->photo)
                                                <img src="{{ asset('assets/admin/uploads/' . $opinion->photo) }}" 
                                                     alt="{{ $opinion->name }}">
                                            @else
                                                <div class="avatar-placeholder">
                                                    {{ substr($opinion->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="preview-info">
                                            <h6>{{ $opinion->name }}</h6>
                                            <div class="preview-rating">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= floor($opinion->number_of_star))
                                                        <i class="fas fa-star text-warning"></i>
                                                    @elseif($i - 0.5 <= $opinion->number_of_star)
                                                        <i class="fas fa-star-half-alt text-warning"></i>
                                                    @else
                                                        <i class="far fa-star text-muted"></i>
                                                    @endif
                                                @endfor
                                                <span class="rating-text">({{ $opinion->number_of_star }})</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="preview-content">
                                        <h6 class="preview-title">{{ $opinion->title }}</h6>
                                        <p class="preview-description">{{ $opinion->description }}</p>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>

                        <!-- Edit Form -->
                        <div class="col-md-8">
                            <form action="{{ route('opinions.update', $opinion) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="required">
                                                <i class="fas fa-user text-info"></i> {{ __('messages.Student Name') }}
                                            </label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" name="name" value="{{ old('name', $opinion->name) }}" 
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
                                                <option value="5" {{ old('number_of_star', $opinion->number_of_star) == '5' ? 'selected' : '' }}>
                                                    ⭐⭐⭐⭐⭐ (5.0) - {{ __('messages.Excellent') }}
                                                </option>
                                                <option value="4.5" {{ old('number_of_star', $opinion->number_of_star) == '4.5' ? 'selected' : '' }}>
                                                    ⭐⭐⭐⭐⭐ (4.5) - {{ __('messages.Very Good') }}
                                                </option>
                                                <option value="4" {{ old('number_of_star', $opinion->number_of_star) == '4' ? 'selected' : '' }}>
                                                    ⭐⭐⭐⭐ (4.0) - {{ __('messages.Good') }}
                                                </option>
                                                <option value="3.5" {{ old('number_of_star', $opinion->number_of_star) == '3.5' ? 'selected' : '' }}>
                                                    ⭐⭐⭐⭐ (3.5) - {{ __('messages.Above Average') }}
                                                </option>
                                                <option value="3" {{ old('number_of_star', $opinion->number_of_star) == '3' ? 'selected' : '' }}>
                                                    ⭐⭐⭐ (3.0) - {{ __('messages.Average') }}
                                                </option>
                                                <option value="2.5" {{ old('number_of_star', $opinion->number_of_star) == '2.5' ? 'selected' : '' }}>
                                                    ⭐⭐⭐ (2.5) - {{ __('messages.Below Average') }}
                                                </option>
                                                <option value="2" {{ old('number_of_star', $opinion->number_of_star) == '2' ? 'selected' : '' }}>
                                                    ⭐⭐ (2.0) - {{ __('messages.Poor') }}
                                                </option>
                                                <option value="1.5" {{ old('number_of_star', $opinion->number_of_star) == '1.5' ? 'selected' : '' }}>
                                                    ⭐⭐ (1.5) - {{ __('messages.Very Poor') }}
                                                </option>
                                                <option value="1" {{ old('number_of_star', $opinion->number_of_star) == '1' ? 'selected' : '' }}>
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
                                                   id="title" name="title" value="{{ old('title', $opinion->title) }}" 
                                                   placeholder="{{ __('messages.Brief title for the opinion') }}" required>
                                            @error('title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="photo">
                                                <i class="fas fa-camera text-secondary"></i> {{ __('messages.New Student Photo') }}
                                            </label>
                                            <input type="file" class="form-control-file @error('photo') is-invalid @enderror" 
                                                   id="photo" name="photo" accept="image/*">
                                            @error('photo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">{{ __('messages.Leave empty to keep current photo') }}</small>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="description" class="required">
                                                <i class="fas fa-comment text-success"></i> {{ __('messages.Opinion Description') }}
                                            </label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                                      id="description" name="description" rows="5" 
                                                      placeholder="{{ __('messages.Share the detailed opinion or feedback') }}" required>{{ old('description', $opinion->description) }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">{{ __('messages.Describe the student experience in detail') }}</small>
                                        </div>
                                    </div>
                                </div>

                              

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> {{ __('messages.Update Opinion') }}
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

.current-opinion-preview {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    height: fit-content;
    position: sticky;
    top: 20px;
}

.opinion-preview-card {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 12px;
    padding: 20px;
    margin-top: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.preview-header {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.preview-avatar {
    width: 50px;
    height: 50px;
    margin-right: 12px;
    flex-shrink: 0;
}

.preview-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #f8f9fa;
}

.avatar-placeholder {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    font-weight: bold;
}

.preview-info h6 {
    margin: 0 0 5px 0;
    color: #333;
    font-weight: 600;
}

.preview-rating {
    display: flex;
    align-items: center;
    gap: 2px;
}

.rating-text {
    margin-left: 8px;
    font-size: 0.9rem;
    color: #666;
    font-weight: 500;
}

.preview-content {
    margin-bottom: 15px;
}

.preview-title {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 8px;
    font-size: 1rem;
}

.preview-description {
    color: #555;
    line-height: 1.6;
    margin: 0;
    font-size: 0.9rem;
}

.preview-meta {
    padding-top: 15px;
    border-top: 1px solid #f8f9fa;
}

.record-info {
    background: #f8f9fa;
    border-radius: 6px;
    padding: 15px;
    margin-top: 20px;
}

.record-info h6 {
    margin-bottom: 10px;
}

@media (max-width: 768px) {
    .current-opinion-preview {
        position: static;
        margin-bottom: 20px;
    }
}
</style>
@endsection