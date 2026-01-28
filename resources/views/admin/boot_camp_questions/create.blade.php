@extends('layouts.admin')

@section('title', __('messages.add_new_boot_camp_question'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.add_new_boot_camp_question') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.boot-camp-questions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.boot-camp-questions.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <!-- Parent Category -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="parent_category">{{ __('messages.parent_category') }} <span class="text-danger">*</span></label>
                                    <select class="form-control @error('parent_category') is-invalid @enderror" 
                                            id="parent_category" name="parent_category">
                                        <option value="">{{ __('messages.select_parent_category') }}</option>
                                        @foreach($parentCategories as $category)
                                            <option value="{{ $category->id }}" {{ old('parent_category') == $category->id ? 'selected' : '' }}>
                                                @if($category->icon)
                                                    <i class="{{ $category->icon }}"></i>
                                                @endif
                                                {{ $category->localized_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('parent_category')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Child Category -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category_id">{{ __('messages.child_category') }} <span class="text-danger">*</span></label>
                                    <select class="form-control @error('category_id') is-invalid @enderror" 
                                            id="category_id" name="category_id" disabled>
                                        <option value="">{{ __('messages.select_child_category') }}</option>
                                    </select>
                                    @error('category_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">
                                        {{ __('messages.select_parent_first') }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Subject -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="subject_id">{{ __('messages.subject') }} <span class="text-danger">*</span></label>
                                    <select class="form-control @error('subject_id') is-invalid @enderror" 
                                            id="subject_id" name="subject_id" disabled>
                                        <option value="">{{ __('messages.select_subject') }}</option>
                                    </select>
                                    @error('subject_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">
                                        {{ __('messages.select_category_first') }}
                                    </small>
                                </div>
                            </div>

                            <!-- Display Name -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="display_name">{{ __('messages.display_name') }}</label>
                                    <input type="text" class="form-control @error('display_name') is-invalid @enderror" 
                                           id="display_name" name="display_name" value="{{ old('display_name') }}"
                                           placeholder="{{ __('messages.enter_display_name') }}">
                                    @error('display_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">
                                        {{ __('messages.leave_empty_to_use_filename') }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Sort Order -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sort_order">{{ __('messages.sort_order') }}</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                                    @error('sort_order')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">
                                        {{ __('messages.higher_number_appears_first') }}
                                    </small>
                                </div>
                            </div>

                            <!-- Active Status -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_active">{{ __('messages.status') }}</label>
                                    <div class="form-check mt-2">
                                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                                               value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            {{ __('messages.active') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- PDF File -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="pdf">{{ __('messages.pdf_file') }} <span class="text-danger">*</span></label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('pdf') is-invalid @enderror" 
                                               id="pdf" name="pdf" accept=".pdf">
                                        <label class="custom-file-label" for="pdf">{{ __('messages.choose_pdf_file') }}</label>
                                    </div>
                                    <small class="form-text text-muted">
                                        {{ __('messages.allowed_formats') }}: PDF. {{ __('messages.max_size') }}: 10MB
                                    </small>
                                    @error('pdf')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                    
                                    <!-- PDF Preview -->
                                    <div class="mt-3 d-none" id="pdf-preview">
                                        <div class="alert alert-info">
                                            <i class="fas fa-file-pdf text-danger mr-2"></i>
                                            <span id="pdf-name"></span>
                                            <span class="float-right" id="pdf-size"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> {{ __('messages.save') }}
                        </button>
                        <a href="{{ route('admin.boot-camp-questions.index') }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-times"></i> {{ __('messages.cancel') }}
                        </a>
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
                url: '{{ route('admin.boot-camp-questions.get-children', ':id') }}'.replace(':id', parentId),
                type: 'GET',
                success: function(data) {
                    // Reset the select with default option
                    childSelect.html('<option value="">{{ __('messages.select_child_category') }}</option>');
                    
                    // Add option to use parent category itself
                    childSelect.append('<option value="' + parentId + '">{{ __('messages.use_parent_category') }}</option>');
                    
                    // Add child categories if any exist
                    if (data.length > 0) {
                        $.each(data, function(key, category) {
                            childSelect.append('<option value="' + category.id + '">' + 
                                (category.name_ar || category.name_en) + '</option>');
                        });
                    }
                    
                    // Enable the child select
                    childSelect.prop('disabled', false);
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
            url: '{{ route('admin.boot-camp-questions.subjects-by-category') }}',
            type: 'GET',
            data: { category_id: categoryId },
            success: function(data) {
                // Reset with default option
                subjectSelect.html('<option value="">{{ __('messages.select_subject') }}</option>');
                
                if (data.length > 0) {
                    $.each(data, function(key, subject) {
                        subjectSelect.append('<option value="' + subject.id + '">' + subject.name + '</option>');
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

    // Handle file input change
    $('#pdf').change(function() {
        var file = this.files[0];
        var preview = $('#pdf-preview');
        var displayNameInput = $('#display_name');
        
        if (file) {
            if (file.type !== 'application/pdf') {
                alert('{{ __('messages.please_select_pdf_file') }}');
                $(this).val('');
                preview.addClass('d-none');
                return;
            }
            
            var fileName = file.name;
            var fileSize = (file.size / 1024 / 1024).toFixed(2) + ' MB';
            
            $('.custom-file-label').text(fileName);
            $('#pdf-name').text(fileName);
            $('#pdf-size').text(fileSize);
            preview.removeClass('d-none');
            
            // Auto-fill display name if empty
            if (!displayNameInput.val()) {
                displayNameInput.val(fileName.replace(/\.[^/.]+$/, "")); // Remove extension
            }
        } else {
            $('.custom-file-label').text('{{ __('messages.choose_pdf_file') }}');
            preview.addClass('d-none');
        }
    });

    // Restore old values if validation fails
    @if(old('parent_category'))
        $('#parent_category').trigger('change');
        setTimeout(function() {
            $('#category_id').val('{{ old('category_id') }}');
            $('#category_id').trigger('change');
            setTimeout(function() {
                $('#subject_id').val('{{ old('subject_id') }}');
            }, 500);
        }, 500);
    @endif
});
</script>
@endpush