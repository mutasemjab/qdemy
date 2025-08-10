
@extends('layouts.admin')

@section('title', __('messages.edit_course'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.edit_course') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('courses.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                        </a>
                    </div>
                </div>

                <form action="{{ route('courses.update', $course) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <!-- English Title -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="title_en" class="form-label">
                                        {{ __('messages.title_en') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('title_en') is-invalid @enderror" 
                                           id="title_en" 
                                           name="title_en" 
                                           value="{{ old('title_en', $course->title_en) }}" 
                                           placeholder="{{ __('messages.enter_title_en') }}">
                                    @error('title_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Arabic Title -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="title_ar" class="form-label">
                                        {{ __('messages.title_ar') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('title_ar') is-invalid @enderror" 
                                           id="title_ar" 
                                           name="title_ar" 
                                           value="{{ old('title_ar', $course->title_ar) }}" 
                                           placeholder="{{ __('messages.enter_title_ar') }}"
                                           dir="rtl">
                                    @error('title_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- English Description -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="description_en" class="form-label">
                                        {{ __('messages.description_en') }} <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('description_en') is-invalid @enderror" 
                                              id="description_en" 
                                              name="description_en" 
                                              rows="4" 
                                              placeholder="{{ __('messages.enter_description_en') }}">{{ old('description_en', $course->description_en) }}</textarea>
                                    @error('description_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Arabic Description -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="description_ar" class="form-label">
                                        {{ __('messages.description_ar') }} <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('description_ar') is-invalid @enderror" 
                                              id="description_ar" 
                                              name="description_ar" 
                                              rows="4" 
                                              placeholder="{{ __('messages.enter_description_ar') }}"
                                              dir="rtl">{{ old('description_ar', $course->description_ar) }}</textarea>
                                    @error('description_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="selling_price" class="form-label">
                                        {{ __('messages.selling_price') }} <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" 
                                               class="form-control @error('selling_price') is-invalid @enderror" 
                                               id="selling_price" 
                                               name="selling_price" 
                                               value="{{ old('selling_price', $course->selling_price) }}" 
                                               step="0.01" 
                                               min="0"
                                               placeholder="0.00">
                                        @error('selling_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Teacher -->
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="teacher_id" class="form-label">{{ __('messages.teacher') }}</label>
                                    <select class="form-select @error('teacher_id') is-invalid @enderror" 
                                            id="teacher_id" 
                                            name="teacher_id">
                                        <option value="">{{ __('messages.select_teacher') }}</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" 
                                                    {{ old('teacher_id', $course->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('teacher_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Category -->
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="category_id" class="form-label">{{ __('messages.category') }}</label>
                                    <select class="form-select @error('category_id') is-invalid @enderror" 
                                            id="category_id" 
                                            name="category_id">
                                        <option value="">{{ __('messages.select_category') }}</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                    {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Current Photo -->
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label class="form-label">{{ __('messages.current_photo') }}</label>
                                    <div class="mb-2">
                                        <img src="{{ $course->photo_url }}" 
                                             alt="{{ $course->title }}" 
                                             class="img-thumbnail" 
                                             style="width: 150px; height: 150px; object-fit: cover;">
                                    </div>
                                </div>
                            </div>

                            <!-- New Photo -->
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="photo" class="form-label">
                                        {{ __('messages.new_photo') }}
                                    </label>
                                    <input type="file" 
                                           class="form-control @error('photo') is-invalid @enderror" 
                                           id="photo" 
                                           name="photo" 
                                           accept="image/*">
                                    <small class="form-text text-muted">
                                        {{ __('messages.photo_requirements') }}
                                    </small>
                                    @error('photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('courses.index') }}" class="btn btn-secondary">
                                {{ __('messages.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ __('messages.update') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('photo').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // You can add image preview here if needed
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection