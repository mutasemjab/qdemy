@extends('layouts.app')
@section('title', __('panel.create_course'))

@section('content')
<section class="ud-wrap">
    <aside class="ud-menu">
        <div class="ud-user">
            <img data-src="{{ auth()->user()->photo ? asset('assets/admin/uploads/' . auth()->user()->photo) : asset('assets_front/images/avatar-big.png') }}"
                alt="">
            <div>
                <h3>{{ auth()->user()->name }}</h3>
                <span>{{ auth()->user()->email }}</span>
            </div>
        </div>

        <a href="{{ route('teacher.courses.index') }}" class="ud-item">
            <i class="fa-solid fa-arrow-left"></i>
            <span>{{ __('panel.back_to_courses') }}</span>
        </a>
    </aside>

    <div class="ud-content">
        <div class="ud-panel show">
            <div class="ud-title">{{ __('panel.create_course') }}</div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('teacher.courses.store') }}" enctype="multipart/form-data" class="course-form" id="courseForm">
                @csrf
                
                <!-- Course Photo -->
                <div class="form-group">
                    <label for="photo">{{ __('panel.course_photo') }} *</label>
                    <div class="photo-upload-wrapper">
                        <input type="file" class="form-control" id="photo" name="photo" accept="image/*" required>
                    </div>
                    <small class="form-text">{{ __('panel.supported_formats') }}: JPG, PNG, GIF. {{ __('panel.max_size') }}: 2MB</small>
                </div>

                <!-- Course Basic Info -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="title_ar">{{ __('panel.course_title_ar') }} *</label>
                        <input type="text" id="title_ar" name="title_ar" value="{{ old('title_ar') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="title_en">{{ __('panel.course_title_en') }} *</label>
                        <input type="text" id="title_en" name="title_en" value="{{ old('title_en') }}" required>
                    </div>
                </div>

                <!-- Course Descriptions -->
                <div class="form-group">
                    <label for="description_ar">{{ __('panel.description_ar') }} *</label>
                    <textarea id="description_ar" name="description_ar" rows="4" required>{{ old('description_ar') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="description_en">{{ __('panel.description_en') }} *</label>
                    <textarea id="description_en" name="description_en" rows="4" required>{{ old('description_en') }}</textarea>
                </div>

                <!-- Category & Subject Selection -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="parent_category">{{ __('panel.select_program') }} *</label>
                        <select id="parent_category" name="parent_category" required>
                            <option value="">{{ __('panel.select_program') }}</option>
                            @foreach($parentCategories as $category)
                                <option value="{{ $category->id }}" {{ old('parent_category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name_ar }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="category_id">{{ __('panel.select_grade') }} *</label>
                        <select id="category_id" name="category_id" disabled required>
                            <option value="">{{ __('panel.select_grade_first') }}</option>
                        </select>
                        <small class="form-text">{{ __('panel.select_parent_first') }}</small>
                    </div>
                </div>

                <div class="form-group">
                    <label for="subject_id">{{ __('panel.select_subject') }} *</label>
                    <select id="subject_id" name="subject_id" disabled required>
                        <option value="">{{ __('panel.select_category_first') }}</option>
                    </select>
                    <small class="form-text">{{ __('panel.select_category_first') }}</small>
                </div>

                <!-- Price -->
                <div class="form-group">
                    <label for="selling_price">{{ __('panel.course_price') }} *</label>
                    <div class="price-input-wrapper">
                        <input type="number" id="selling_price" name="selling_price" 
                               value="{{ old('selling_price', 0) }}" min="0" step="0.01" required>
                        <span class="currency">{{ __('panel.currency') }}</span>
                    </div>
                    <small class="form-text">{{ __('panel.enter_zero_for_free') }}</small>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('teacher.courses.index') }}" class="btn btn-secondary">
                        {{ __('panel.cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fa-solid fa-save"></i>
                        {{ __('panel.create_course') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
.course-form {
    max-width: 800px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    color: #333;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.3s ease;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
}

.form-group select:disabled {
    background-color: #f8f9fa;
    color: #6c757d;
    cursor: not-allowed;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.price-input-wrapper {
    position: relative;
}

.price-input-wrapper .currency {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #666;
    font-weight: 600;
}

.price-input-wrapper input {
    padding-right: 60px;
}

.form-text {
    color: #666;
    font-size: 12px;
    margin-top: 5px;
}

.form-actions {
    display: flex;
    gap: 15px;
    justify-content: flex-end;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover {
    background: #0056b3;
}

.btn-primary:disabled {
    background: #6c757d;
    cursor: not-allowed;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #545b62;
}

.alert {
    padding: 12px 15px;
    border-radius: 6px;
    margin-bottom: 20px;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert ul {
    margin: 0;
    padding-left: 20px;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const parentCategorySelect = document.getElementById('parent_category');
    const childCategorySelect = document.getElementById('category_id');
    const subjectSelect = document.getElementById('subject_id');
    const submitBtn = document.getElementById('submitBtn');
    const courseForm = document.getElementById('courseForm');

    // Handle parent category change
    parentCategorySelect.addEventListener('change', function() {
        const parentId = this.value;
        
        // Reset child category and subject
        resetSelect(childCategorySelect, '{{ __("panel.select_grade_first") }}');
        resetSelect(subjectSelect, '{{ __("panel.select_subject") }}');
        childCategorySelect.disabled = true;
        subjectSelect.disabled = true;
        
        if (parentId) {
            // Show loading state
            setLoadingState(childCategorySelect, '{{ __("panel.loading") }}...');
            
            // Build URL for child categories
            const childrenUrl = "{{ route('teacher.categories.children', ':id') }}".replace(':id', parentId);
            
            // Fetch child categories
            fetch(childrenUrl)
                .then(response => response.json())
                .then(data => {
                    // Reset the select with default option
                    resetSelect(childCategorySelect, '{{ __("panel.select_grade_first") }}');
                    
                    // Add option to use parent category itself
                    addOption(childCategorySelect, parentId, '{{ __("panel.use_parent_category") }}');
                    
                    // Add child categories if any exist
                    if (data && data.length > 0) {
                        data.forEach(function(category) {
                            const name = category.name_ar || category.name_en || 'Unknown';
                            addOption(childCategorySelect, category.id, name);
                        });
                    }
                    
                    // Enable the child select
                    childCategorySelect.disabled = false;
                })
                .catch(error => {
                    console.error('Error loading categories:', error);
                    alert('{{ __("panel.error_loading_categories") }}');
                    resetSelect(childCategorySelect, '{{ __("panel.select_grade_first") }}');
                    childCategorySelect.disabled = false;
                });
        }
    });

    // Handle child category change - this loads subjects
    childCategorySelect.addEventListener('change', function() {
        const categoryId = this.value;
        
        // Reset subjects
        resetSelect(subjectSelect, '{{ __("panel.select_subject") }}');
        subjectSelect.disabled = true;
        
        if (categoryId) {
            loadSubjects(categoryId);
        }
    });

    // Function to load subjects based on selected category
    function loadSubjects(categoryId) {
        // Show loading state
        setLoadingState(subjectSelect, '{{ __("panel.loading") }}...');
        subjectSelect.disabled = true;
        
        // Build URL with query parameter
        const subjectsUrl = "{{ route('teacher.subjects.by-category') }}?category_id=" + categoryId;
        
        fetch(subjectsUrl)
            .then(response => response.json())
            .then(data => {
                // Reset with default option
                resetSelect(subjectSelect, '{{ __("panel.select_subject") }}');
                
                if (data && data.length > 0) {
                    data.forEach(function(subject) {
                        const subjectName = subject.name_ar || subject.name_en || subject.name || 'Unknown';
                        addOption(subjectSelect, subject.id, subjectName);
                    });
                    subjectSelect.disabled = false;
                } else {
                    addOption(subjectSelect, '', '{{ __("panel.no_subjects_available") }}', true);
                    subjectSelect.disabled = true;
                }
            })
            .catch(error => {
                console.error('Error loading subjects:', error);
                alert('{{ __("panel.error_loading_subjects") }}');
                resetSelect(subjectSelect, '{{ __("panel.select_subject") }}');
                subjectSelect.disabled = false;
            });
    }

    // Helper function to reset select element
    function resetSelect(selectElement, defaultText) {
        selectElement.innerHTML = '';
        addOption(selectElement, '', defaultText);
    }

    // Helper function to set loading state
    function setLoadingState(selectElement, loadingText) {
        selectElement.innerHTML = '';
        addOption(selectElement, '', loadingText);
    }

    // Helper function to add option to select
    function addOption(selectElement, value, text, disabled = false) {
        const option = document.createElement('option');
        option.value = value;
        option.textContent = text;
        if (disabled) option.disabled = true;
        selectElement.appendChild(option);
    }

    // Form submission with loading state
    courseForm.addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> {{ __("panel.creating") }}...';
        
        // Re-enable button after 10 seconds to prevent permanent lock
        setTimeout(function() {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fa-solid fa-save"></i> {{ __("panel.create_course") }}';
        }, 10000);
    });

    // Restore old values if validation fails
    @if(old('parent_category'))
        parentCategorySelect.value = '{{ old("parent_category") }}';
        parentCategorySelect.dispatchEvent(new Event('change'));
        
        setTimeout(function() {
            @if(old('category_id'))
                childCategorySelect.value = '{{ old("category_id") }}';
                childCategorySelect.dispatchEvent(new Event('change'));
                
                setTimeout(function() {
                    @if(old('subject_id'))
                        subjectSelect.value = '{{ old("subject_id") }}';
                    @endif
                }, 1000);
            @endif
        }, 1000);
    @endif
});
</script>
@endsection