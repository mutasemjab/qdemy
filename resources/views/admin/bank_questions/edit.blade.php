@extends('layouts.admin')

@section('title', __('messages.edit_bank_question'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.edit_bank_question') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('bank-questions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                        </a>
                    </div>
                </div>

                <form action="{{ route('bank-questions.update', $bankQuestion) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
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
                                            <option value="{{ $category->id }}" 
                                                {{ (old('parent_category', $selectedParent) == $category->id) ? 'selected' : '' }}>
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
                                            id="category_id" name="category_id" {{ !$selectedParent ? 'disabled' : '' }}>
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
                                            id="subject_id" name="subject_id" {{ !$selectedParent ? 'disabled' : '' }}>
                                        <option value="">{{ __('messages.select_subject') }}</option>
                                    </select>
                                    @error('subject_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Display Name -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="display_name">{{ __('messages.display_name') }}</label>
                                    <input type="text" class="form-control @error('display_name') is-invalid @enderror" 
                                           id="display_name" name="display_name" 
                                           value="{{ old('display_name', $bankQuestion->display_name) }}"
                                           placeholder="{{ __('messages.enter_display_name') }}">
                                    @error('display_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Sort Order -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sort_order">{{ __('messages.sort_order') }}</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" 
                                           value="{{ old('sort_order', $bankQuestion->sort_order ?? 0) }}" min="0">
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
                                               value="1" {{ old('is_active', $bankQuestion->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            {{ __('messages.active') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Download Count (Read Only) -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="download_count">{{ __('messages.download_count') }}</label>
                                    <input type="text" class="form-control" id="download_count" 
                                           value="{{ $bankQuestion->download_count ?? 0 }}" readonly>
                                    <small class="form-text text-muted">
                                        {{ __('messages.download_count_info') }}
                                    </small>
                                </div>
                            </div>

                            <!-- Created Date (Read Only) -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="created_at">{{ __('messages.created_at') }}</label>
                                    <input type="text" class="form-control" id="created_at" 
                                           value="{{ $bankQuestion->created_at->format('Y-m-d H:i:s') }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- PDF File -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="pdf">{{ __('messages.pdf_file') }}</label>
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
                                    
                                    <!-- Current PDF -->
                                    @if($bankQuestion->pdf && $bankQuestion->pdfExists())
                                    <div class="mt-3">
                                        <label class="form-label">{{ __('messages.current_pdf') }}:</label>
                                        <div class="alert alert-success current-pdf">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="fas fa-file-pdf text-danger mr-2"></i>
                                                    <strong>{{ $bankQuestion->display_name ?: basename($bankQuestion->pdf) }}</strong>
                                                    @if($bankQuestion->pdf_size)
                                                        <span class="badge badge-info ml-2">{{ $bankQuestion->formatted_file_size }}</span>
                                                    @endif
                                                </div>
                                                <div>
                                                    <a target='_blank' href="{{ $bankQuestion->pdf_path }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i> {{ __('messages.view') }}
                                                    </a>
                                                    <a href="{{ route('bank-questions.download-pdf', $bankQuestion) }}" 
                                                       class="btn btn-sm btn-outline-success ml-1">
                                                        <i class="fas fa-download"></i> {{ __('messages.download') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @elseif($bankQuestion->pdf)
                                    <div class="mt-3">
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle mr-2"></i>
                                            {{ __('messages.current_file_missing') }}: {{ $bankQuestion->pdf }}
                                        </div>
                                    </div>
                                    @endif
                                    
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
                            <i class="fas fa-save"></i> {{ __('messages.update') }}
                        </button>
                        <a href="{{ route('bank-questions.index') }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-times"></i> {{ __('messages.cancel') }}
                        </a>
                        @if($bankQuestion->pdf && $bankQuestion->pdfExists())
                            <a href="{{ route('bank-questions.download-pdf', $bankQuestion) }}" class="btn btn-info ml-2">
                                <i class="fas fa-download"></i> {{ __('messages.download_current') }}
                            </a>
                        @endif
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
                // Enable the parent category as selectable
                childSelect.append('<option value="' + parentId + '">{{ __('messages.use_parent_category') }}</option>');
                
                // Fetch child categories
                $.ajax({
                    url: '{{ route('bank-questions.get-children', ':id') }}'.replace(':id', parentId),
                    type: 'GET',
                    success: function(data) {
                        if (data.length > 0) {
                            $.each(data, function(key, category) {
                                childSelect.append('<option value="' + category.id + '">' + 
                                    (category.name_ar || category.name_en) + '</option>');
                            });
                        }
                        childSelect.prop('disabled', false);
                        
                        // Set selected value if editing
                        var selectedChild = '{{ old('category_id', $selectedChild) }}';
                        if (selectedChild) {
                            childSelect.val(selectedChild);
                        }
                        
                        // Load subjects for parent category
                        loadSubjects(parentId);
                    },
                    error: function() {
                        alert('{{ __('messages.error_loading_categories') }}');
                    }
                });
            }
        });

        // Handle child category change
        $('#category_id').change(function() {
            var categoryId = $(this).val();
            if (categoryId) {
                loadSubjects(categoryId);
            }
        });

        // Function to load subjects
        function loadSubjects(categoryId) {
            var subjectSelect = $('#subject_id');
            
            subjectSelect.html('<option value="">{{ __('messages.loading') }}...</option>');
            subjectSelect.prop('disabled', true);
            
            $.ajax({
                url: '{{ route('bank-questions.subjects-by-category') }}',
                type: 'GET',
                data: { category_id: categoryId },
                success: function(data) {
                    subjectSelect.html('<option value="">{{ __('messages.select_subject') }}</option>');
                    
                    if (data.length > 0) {
                        $.each(data, function(key, subject) {
                            subjectSelect.append('<option value="' + subject.id + '">' + subject.name + '</option>');
                        });
                    } else {
                        subjectSelect.append('<option value="" disabled>{{ __('messages.no_subjects_found') }}</option>');
                    }
                    
                    subjectSelect.prop('disabled', false);
                    
                    // Set selected subject if editing
                    var selectedSubject = '{{ old('subject_id', $bankQuestion->subject_id) }}';
                    if (selectedSubject) {
                        subjectSelect.val(selectedSubject);
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
            var currentPdf = $('.current-pdf');
            
            if (file) {
                if (file.type !== 'application/pdf') {
                    alert('{{ __('messages.please_select_pdf_file') }}');
                    $(this).val('');
                    preview.addClass('d-none');
                    currentPdf.removeClass('opacity-50');
                    return;
                }
                
                var fileName = file.name;
                var fileSize = (file.size / 1024 / 1024).toFixed(2) + ' MB';
                
                $('.custom-file-label').text(fileName);
                $('#pdf-name').text(fileName);
                $('#pdf-size').text(fileSize);
                preview.removeClass('d-none');
                currentPdf.addClass('opacity-50');
            } else {
                $('.custom-file-label').text('{{ __('messages.choose_pdf_file') }}');
                preview.addClass('d-none');
                currentPdf.removeClass('opacity-50');
            }
        });

        // Initialize on page load
        @if($selectedParent)
            $('#parent_category').trigger('change');
        @endif
    });
</script>
@endpush