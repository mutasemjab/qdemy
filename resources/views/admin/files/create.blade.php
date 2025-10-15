{{-- resources/views/files/create.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('messages.add_new_file') }}</h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('messages.file_name') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="category_file_id" class="form-label">{{ __('messages.category') }} <span class="text-danger">*</span></label>
                            <select class="form-control @error('category_file_id') is-invalid @enderror" 
                                    id="category_file_id" name="category_file_id" required>
                                <option value="">{{ __('messages.select_category') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_file_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_file_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="pdf" class="form-label">{{ __('messages.pdf_file') }} <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('pdf') is-invalid @enderror" 
                                   id="pdf" name="pdf" accept=".pdf" required onchange="previewFile(this)">
                            <div class="form-text">{{ __('messages.pdf_file_help') }}</div>
                            @error('pdf')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="filePreview" class="mb-3" style="display: none;">
                            <div class="alert alert-info">
                                <i class="fas fa-file-pdf text-danger"></i>
                                <strong id="fileName"></strong>
                                <br>
                                <small id="fileSize"></small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('files.index') }}" class="btn btn-secondary">
                                {{ __('messages.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                {{ __('messages.create_file') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewFile(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const preview = document.getElementById('filePreview');
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');
        
        fileName.textContent = file.name;
        fileSize.textContent = formatBytes(file.size);
        preview.style.display = 'block';
        
        // Auto-fill name field if empty
        const nameField = document.getElementById('name');
        if (!nameField.value) {
            nameField.value = file.name.replace('.pdf', '');
        }
    }
}

function formatBytes(bytes, decimals = 2) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}
</script>
@endsection