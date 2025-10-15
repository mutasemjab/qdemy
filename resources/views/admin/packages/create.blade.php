@extends('layouts.admin')

@section('title', __('messages.Add Package'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">{{ __('messages.Add Package') }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('packages.index') }}">{{ __('messages.Packages') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('messages.Add Package') }}</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('packages.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>{{ __('messages.Back') }}
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('messages.Package Information') }}</h5>
                </div>
                
                <form method="POST" action="{{ route('packages.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Package Name -->
                            <div class="col-md-6">
                                <label for="name" class="form-label required">{{ __('messages.Package Name') }}</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required maxlength="128">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Price -->
                            <div class="col-md-6">
                                <label for="price" class="form-label required">{{ __('messages.Price') }}</label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                           id="price" name="price" value="{{ old('price') }}" required min="0" step="0.001">
                                    <span class="input-group-text">{{ __('messages.Currency') }}</span>
                                </div>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Type -->
                            <div class="col-md-6">
                                <label for="type" class="form-label required">{{ __('messages.Package Type') }}</label>
                                <select class="form-control @error('type') is-invalid @enderror" 
                                        id="type" name="type" required onchange="loadCategories()">
                                    <option value="">{{ __('messages.Select Type') }}</option>
                                    <option value="class" {{ old('type') == 'class' ? 'selected' : '' }}>
                                        {{ __('messages.Class') }} - {{ __('messages.Organizational categories') }}
                                    </option>
                                    <option value="subject" {{ old('type') == 'subject' ? 'selected' : '' }}>
                                        {{ __('messages.Subject') }} - {{ __('messages.Academic subjects') }}
                                    </option>
                                </select>
                                <div class="form-text">
                                    {{ __('messages.Type determines which categories will be available for selection') }}
                                </div>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Course Selection Limit -->
                            <div class="col-md-6">
                                <label for="how_much_course_can_select" class="form-label required">{{ __('messages.Course Selection Limit') }}</label>
                                <input type="number" class="form-control @error('how_much_course_can_select') is-invalid @enderror" 
                                       id="how_much_course_can_select" name="how_much_course_can_select" 
                                       value="{{ old('how_much_course_can_select', 1) }}" required min="1">
                                <div class="form-text">
                                    {{ __('messages.How many courses can be selected from this package') }}
                                </div>
                                @error('how_much_course_can_select')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-md-6">
                                <label for="status" class="form-label required">{{ __('messages.Status') }}</label>
                                <select class="form-control @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                                        {{ __('messages.Active') }}
                                    </option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                        {{ __('messages.Inactive') }}
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Image -->
                            <div class="col-md-6">
                                <label for="image" class="form-label">{{ __('messages.Package Image') }}</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                       id="image" name="image" accept="image/*" onchange="previewImage(this)">
                                <div class="form-text">{{ __('messages.Supported formats: JPG, PNG, GIF (Max: 2MB)') }}</div>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                
                                <!-- Image Preview -->
                                <div id="image-preview" class="mt-3" style="display: none;">
                                    <img id="preview-img" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                                </div>
                            </div>

                            <!-- Categories Selection -->
                            <div class="col-12">
                                <label class="form-label">{{ __('messages.Available Categories') }}</label>
                                <div id="categories-container">
                                    <div class="text-muted text-center py-4">
                                        <i class="fas fa-info-circle me-2"></i>
                                        {{ __('messages.Please select a package type first to load available categories') }}
                                    </div>
                                </div>
                                @error('categories')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Subjects Selection -->
                            <div class="col-12" id="subjects-section" style="display: none;">
                                <label class="form-label">{{ __('messages.Available Subjects') }}</label>
                                <div id="subjects-container">
                                    <div class="text-muted text-center py-4">
                                        <i class="fas fa-info-circle me-2"></i>
                                        {{ __('messages.Please select categories first to load available subjects') }}
                                    </div>
                                </div>
                                @error('subjects')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <label for="description" class="form-label">{{ __('messages.Description') }}</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="4" 
                                          placeholder="{{ __('messages.Enter package description...') }}">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('packages.index') }}" class="btn btn-secondary">
                                {{ __('messages.Cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>{{ __('messages.Save Package') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Preview uploaded image
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('image-preview').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Load categories based on selected type
function loadCategories() {
    const type = document.getElementById('type').value;
    const categoriesContainer = document.getElementById('categories-container');
    const subjectsSection = document.getElementById('subjects-section');
    const subjectsContainer = document.getElementById('subjects-container');
    
    // Reset subjects
    subjectsSection.style.display = 'none';
    subjectsContainer.innerHTML = `
        <div class="text-muted text-center py-4">
            <i class="fas fa-info-circle me-2"></i>
            {{ __('messages.Please select categories first to load available subjects') }}
        </div>
    `;
    
    if (!type) {
        categoriesContainer.innerHTML = `
            <div class="text-muted text-center py-4">
                <i class="fas fa-info-circle me-2"></i>
                {{ __('messages.Please select a package type first to load available categories') }}
            </div>
        `;
        return;
    }

    // Show loading
    categoriesContainer.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border spinner-border-sm me-2"></div>
            {{ __('messages.Loading categories...') }}
        </div>
    `;

    // Fetch categories
    fetch(`{{ route('packages.get-categories-by-type') }}?type=${type}`)
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                categoriesContainer.innerHTML = `
                    <div class="text-muted text-center py-4">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ __('messages.No categories available for this type') }}
                    </div>
                `;
                return;
            }

            let html = `
                <div class="form-text mb-3">
                    <i class="fas fa-info-circle me-1"></i>
                    {{ __('messages.Select the categories that will be included in this package') }}
                </div>
                <div class="row g-2">
            `;

            data.forEach(category => {
                const isChecked = @json(old('categories', [])).includes(category.id.toString());
                html += `
                    <div class="col-md-6">
                        <div class="form-check p-3 border rounded category-item" 
                             style="cursor: pointer; transition: all 0.3s ease;">
                            <input class="form-check-input category-checkbox" type="checkbox" 
                                   value="${category.id}" id="category_${category.id}" 
                                   name="categories[]" ${isChecked ? 'checked' : ''} 
                                   onchange="loadSubjects()">
                            <label class="form-check-label d-flex align-items-center" 
                                   for="category_${category.id}" style="cursor: pointer;">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center">
                                        ${category.icon ? `<i class="${category.icon} me-2" style="color: ${category.color}"></i>` : ''}
                                        <strong>${category.name_ar}</strong>
                                    </div>
                                    <div class="text-muted small mt-1">
                                        ${category.display_name}
                                    </div>
                                    ${category.parent_name ? `<div class="text-info small"><i class="fas fa-arrow-up me-1"></i>${category.parent_name}</div>` : ''}
                                </div>
                            </label>
                        </div>
                    </div>
                `;
            });

            html += '</div>';
            categoriesContainer.innerHTML = html;

            // Add hover effects
            document.querySelectorAll('.category-item').forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = '#f8f9fa';
                    this.style.borderColor = '#0d6efd';
                });
                
                item.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = '';
                    this.style.borderColor = '';
                });

                item.addEventListener('click', function(e) {
                    if (e.target.type !== 'checkbox') {
                        const checkbox = this.querySelector('input[type="checkbox"]');
                        checkbox.checked = !checkbox.checked;
                        loadSubjects();
                    }
                });
            });

            // Show subjects section
            subjectsSection.style.display = 'block';
            loadSubjects();
        })
        .catch(error => {
            console.error('Error loading categories:', error);
            categoriesContainer.innerHTML = `
                <div class="text-danger text-center py-4">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ __('messages.Error loading categories. Please try again.') }}
                </div>
            `;
        });
}

// Load subjects based on selected categories
function loadSubjects() {
    const checkedCategories = document.querySelectorAll('.category-checkbox:checked');
    const subjectsContainer = document.getElementById('subjects-container');
    
    if (checkedCategories.length === 0) {
        subjectsContainer.innerHTML = `
            <div class="text-muted text-center py-4">
                <i class="fas fa-info-circle me-2"></i>
                {{ __('messages.Please select categories first to load available subjects') }}
            </div>
        `;
        return;
    }

    // Show loading
    subjectsContainer.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border spinner-border-sm me-2"></div>
            {{ __('messages.Loading subjects...') }}
        </div>
    `;

    // Get subjects for all selected categories
    const categoryIds = Array.from(checkedCategories).map(cb => cb.value);
    const promises = categoryIds.map(categoryId => 
        fetch(`{{ route('packages.get-subjects-by-category') }}?category_id=${categoryId}`)
            .then(response => response.json())
    );

    Promise.all(promises)
        .then(results => {
            // Combine all subjects and remove duplicates
            const allSubjects = results.flat();
            const uniqueSubjects = allSubjects.filter((subject, index, self) => 
                index === self.findIndex(s => s.id === subject.id)
            );

            if (uniqueSubjects.length === 0) {
                subjectsContainer.innerHTML = `
                    <div class="text-muted text-center py-4">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ __('messages.No subjects available for selected categories') }}
                    </div>
                `;
                return;
            }

            let html = `
                <div class="form-text mb-3">
                    <i class="fas fa-info-circle me-1"></i>
                    {{ __('messages.Select the subjects that will be included in this package') }}
                </div>
                <div class="row g-2">
            `;

            uniqueSubjects.forEach(subject => {
                const isChecked = @json(old('subjects', [])).includes(subject.id.toString());
                html += `
                    <div class="col-md-4">
                        <div class="form-check p-2 border rounded subject-item" 
                             style="cursor: pointer; transition: all 0.3s ease;">
                            <input class="form-check-input" type="checkbox" 
                                   value="${subject.id}" id="subject_${subject.id}" 
                                   name="subjects[]" ${isChecked ? 'checked' : ''}>
                            <label class="form-check-label d-flex align-items-center" 
                                   for="subject_${subject.id}" style="cursor: pointer;">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center">
                                        ${subject.icon ? `<i class="${subject.icon} me-2" style="color: ${subject.color}"></i>` : ''}
                                        <strong class="small">${subject.name_ar}</strong>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                `;
            });

            html += '</div>';
            subjectsContainer.innerHTML = html;

            // Add hover effects for subjects
            document.querySelectorAll('.subject-item').forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = '#f8f9fa';
                    this.style.borderColor = '#28a745';
                });
                
                item.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = '';
                    this.style.borderColor = '';
                });

                item.addEventListener('click', function(e) {
                    if (e.target.type !== 'checkbox') {
                        const checkbox = this.querySelector('input[type="checkbox"]');
                        checkbox.checked = !checkbox.checked;
                    }
                });
            });
        })
        .catch(error => {
            console.error('Error loading subjects:', error);
            subjectsContainer.innerHTML = `
                <div class="text-danger text-center py-4">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ __('messages.Error loading subjects. Please try again.') }}
                </div>
            `;
        });
}

// Load categories on page load if type is already selected
document.addEventListener('DOMContentLoaded', function() {
    const type = document.getElementById('type').value;
    if (type) {
        loadCategories();
    }
});
</script>
@endpush
@endsection