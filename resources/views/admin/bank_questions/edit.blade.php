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
                                            <i class="fas fa-file-pdf text-danger mr-2"></i>
                                            {{ $bankQuestion->pdf }}
                                            <span class="float-right">
                                                {{ $bankQuestion->pdf_size }}
                                                <a target='_blank' href="{{ $bankQuestion->pdf_path }}" 
                                                   class="btn btn-sm btn-outline-success ml-2">
                                                    <i class="fas fa-download"></i> {{ __('messages.download') }}
                                                </a>
                                            </span>
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
            
            // Reset child category
            childSelect.html('<option value="">{{ __('messages.select_child_category') }}</option>');
            childSelect.prop('disabled', true);
            
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
                    },
                    error: function() {
                        alert('{{ __('messages.error_loading_categories') }}');
                    }
                });
            }
        });

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