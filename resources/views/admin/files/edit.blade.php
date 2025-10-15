{{-- resources/views/files/edit.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('messages.edit_file') }}: {{ $file->name }}</h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('files.update', $file) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('messages.file_name') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $file->name) }}" required>
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
                                    <option value="{{ $category->id }}" 
                                        {{ old('category_file_id', $file->category_file_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_file_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="pdf" class="form-label">{{ __('messages.pdf_file') }}</label>
                            
                            <!-- Current File Display -->
                            @if($file->pdf)
                                <div class="current-file mb-3">
                                    <div class="alert alert-info">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <i class="fas fa-file-pdf text-danger me-2"></i>
                                                <strong>{{ __('messages.current_file') }}:</strong> {{ $file->pdf }}
                                                <br>
                                               
                                            </div>
                                          
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <input type="file" class="form-control @error('pdf') is-invalid @enderror" 
                                   id="pdf" name="pdf" accept=".pdf" onchange="previewNewFile(this)">
                            <div class="form-text">{{ __('messages.leave_empty_keep_current_file') }}</div>
                            @error('pdf')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="newFilePreview" class="mb-3" style="display: none;">
                            <div class="alert alert-warning">
                                <strong>{{ __('messages.new_file_preview') }}:</strong>
                                <br>
                                <i class="fas fa-file-pdf text-danger"></i>
                                <strong id="newFileName"></strong>
                                <br>
                                <small id="newFileSize"></small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('files.show', $file) }}" class="btn btn-secondary">
                                {{ __('messages.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                {{ __('messages.update_file') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewNewFile(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const preview = document.getElementById('newFilePreview');
        const fileName = document.getElementById('newFileName');
        const fileSize = document.getElementById('newFileSize');
        
        fileName.textContent = file.name;
        fileSize.textContent = formatBytes(file.size);
        preview.style.display = 'block';
    } else {
        document.getElementById('newFilePreview').style.display = 'none';
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

