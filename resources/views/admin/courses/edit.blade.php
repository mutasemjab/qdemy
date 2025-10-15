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
                                        <span class="input-group-text">JD</span>
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
                          
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="commission_of_admin" class="form-label">
                                        {{ __('messages.commission_of_admin') }} <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">%</span>
                                        <input type="number"
                                               class="form-control @error('commission_of_admin') is-invalid @enderror"
                                               id="commission_of_admin"
                                               name="commission_of_admin"
                                               value="{{ old('commission_of_admin', $course->commission_of_admin) }}"
                                               step="0.01"
                                               min="0"
                                               placeholder="0.00">
                                        @error('commission_of_admin')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Teacher -->
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="teacher_id" class="form-label">{{ __('messages.teacher') }}</label>
                                    <select class="form-control @error('teacher_id') is-invalid @enderror"
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

                            <!-- Parent Category -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="parent_category" class="form-label">
                                        {{ __('messages.parent_category') }} <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control @error('parent_category') is-invalid @enderror"
                                            id="parent_category" name="parent_category">
                                        <option value="">{{ __('messages.select_parent_category') }}</option>
                                        @foreach($parentCategories as $category)
                                            <option value="{{ $category->id }}" 
                                                {{ old('parent_category', $course->category ? $course->category->parent_id ?? $course->category_id : '') == $category->id ? 'selected' : '' }}>
                                                @if($category->icon)
                                                    <i class="{{ $category->icon }}"></i>
                                                @endif
                                                {{ $category->localized_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('parent_category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Child Category -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="category_id" class="form-label">
                                        {{ __('messages.child_category') }} <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control @error('category_id') is-invalid @enderror"
                                            id="category_id" name="category_id">
                                        <option value="">{{ __('messages.select_child_category') }}</option>
                                        @if($course->category)
                                            <option value="{{ $course->category_id }}" selected>
                                                {{ $course->category->localized_name }}
                                            </option>
                                        @endif
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        {{ __('messages.select_parent_first') }}
                                    </small>
                                </div>
                            </div>

                            <!-- Subject -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="subject_id" class="form-label">
                                        {{ __('messages.subject') }} <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control @error('subject_id') is-invalid @enderror"
                                            id="subject_id" name="subject_id">
                                        <option value="">{{ __('messages.select_subject') }}</option>
                                        @if($course->subject)
                                            <option value="{{ $course->subject_id }}" selected>
                                                {{ $course->subject->localized_name }}
                                            </option>
                                        @endif
                                    </select>
                                    @error('subject_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        {{ __('messages.select_category_first') }}
                                    </small>
                                </div>
                            </div>

                            <!-- Current Photo -->
                            @if($course->photo)
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
                            @endif

                            <!-- New Photo -->
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label for="photo" class="form-label">
                                        {{ $course->photo ? __('messages.new_photo') : __('messages.course_photo') }}
                                        @if(!$course->photo) <span class="text-danger">*</span> @endif
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
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Store initial values
    const initialParentCategory = '{{ old('parent_category', $course->category ? $course->category->parent_id ?? $course->category_id : '') }}';
    const initialCategoryId = '{{ old('category_id', $course->category_id) }}';
    const initialSubjectId = '{{ old('subject_id', $course->subject_id) }}';

    // Handle parent category change
    $('#parent_category').change(function() {
        var parentId = $(this).val();
        var childSelect = $('#category_id');
        var subjectSelect = $('#subject_id');
        
        // Reset child category and subject
        childSelect.html('<option value="">{{ __('messages.select_child_category') }}</option>');
        subjectSelect.html('<option value="">{{ __('messages.select_subject') }}</option>');
        childSelect.prop('disabled', true);
        subjectSelect.prop('disabled', true);
        
        if (parentId) {
            // Show loading state
            childSelect.html('<option value="">{{ __('messages.loading') }}...</option>');
            
            // Fetch child categories
            $.ajax({
                 url: '{{ route('courses.get-children', ':id') }}'.replace(':id', parentId),
                type: 'GET',
                success: function(data) {
                    // Reset the select with default option
                    childSelect.html('<option value="">{{ __('messages.select_child_category') }}</option>');
                    
                    // Add option to use parent category itself
                    childSelect.append('<option value="' + parentId + '">{{ __('messages.use_parent_category') }}</option>');
                    
                    // Add child categories if any exist
                    if (data.length > 0) {
                        $.each(data, function(key, category) {
                            var isSelected = category.id == initialCategoryId ? 'selected' : '';
                            childSelect.append('<option value="' + category.id + '" ' + isSelected + '>' + 
                                (category.name_ar || category.name_en) + '</option>');
                        });
                    }
                    
                    // Enable the child select
                    childSelect.prop('disabled', false);
                    
                    // If there's a selected child category, trigger its change event
                    if (initialCategoryId) {
                        childSelect.val(initialCategoryId).trigger('change');
                    }
                },
                error: function() {
                    alert('{{ __('messages.error_loading_categories') }}');
                    childSelect.html('<option value="">{{ __('messages.select_child_category') }}</option>');
                    childSelect.prop('disabled', false);
                }
            });
        }
    });

    // Handle child category change - this is where we load subjects
    $('#category_id').change(function() {
        var categoryId = $(this).val();
        var subjectSelect = $('#subject_id');
        
        // Reset subjects
        subjectSelect.html('<option value="">{{ __('messages.select_subject') }}</option>');
        subjectSelect.prop('disabled', true);
        
        if (categoryId) {
            loadSubjects(categoryId);
        }
    });

    // Function to load subjects based on selected category
    function loadSubjects(categoryId) {
        var subjectSelect = $('#subject_id');
        
        // Show loading state
        subjectSelect.html('<option value="">{{ __('messages.loading') }}...</option>');
        subjectSelect.prop('disabled', true);
        
        $.ajax({
            url: '{{ route('courses.subjects-by-category') }}',
            type: 'GET',
            data: { category_id: categoryId },
            success: function(data) {
                // Reset with default option
                subjectSelect.html('<option value="">{{ __('messages.select_subject') }}</option>');
                
                if (data.length > 0) {
                    $.each(data, function(key, subject) {
                        var isSelected = subject.id == initialSubjectId ? 'selected' : '';
                        subjectSelect.append('<option value="' + subject.id + '" ' + isSelected + '>' + 
                            subject.name + '</option>');
                    });
                    subjectSelect.prop('disabled', false);
                } else {
                    subjectSelect.append('<option value="" disabled>{{ __('messages.no_subjects_found') }}</option>');
                    subjectSelect.prop('disabled', true);
                }
            },
            error: function() {
                alert('{{ __('messages.error_loading_subjects') }}');
                subjectSelect.html('<option value="">{{ __('messages.select_subject') }}</option>');
                subjectSelect.prop('disabled', false);
            }
        });
    }

    // Handle photo preview
    $('#photo').change(function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // You can add image preview here if needed
            };
            reader.readAsDataURL(file);
        }
    });

    // Initialize the form with existing values
    if (initialParentCategory) {
        $('#parent_category').val(initialParentCategory).trigger('change');
    } else if (initialCategoryId) {
        // If there's a category but no parent, load subjects directly
        loadSubjects(initialCategoryId);
    }
});
</script>
@endpush