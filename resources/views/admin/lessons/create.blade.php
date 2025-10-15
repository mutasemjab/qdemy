
{{-- resources/views/lessons/create.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('messages.add_new_lesson') }}</h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('lessons.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('messages.lesson_name') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="category_lesson_id" class="form-label">{{ __('messages.category') }} <span class="text-danger">*</span></label>
                            <select class="form-control @error('category_lesson_id') is-invalid @enderror" 
                                    id="category_lesson_id" name="category_lesson_id" required>
                                <option value="">{{ __('messages.select_category') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_lesson_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_lesson_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="link_youtube" class="form-label">{{ __('messages.youtube_link') }} <span class="text-danger">*</span></label>
                            <input type="url" class="form-control @error('link_youtube') is-invalid @enderror" 
                                   id="link_youtube" name="link_youtube" value="{{ old('link_youtube') }}" 
                                   required onblur="validateYoutubeUrl()" placeholder="https://www.youtube.com/watch?v=...">
                            <div class="form-text">{{ __('messages.youtube_url_help') }}</div>
                            @error('link_youtube')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="videoPreview" class="mb-3" style="display: none;">
                            <div class="card">
                                <div class="card-header">
                                    <h6>{{ __('messages.video_preview') }}</h6>
                                </div>
                                <div class="card-body text-center">
                                    <img id="videoThumbnail" src="" alt="Video Thumbnail" class="img-fluid" style="max-width: 200px;">
                                    <p class="mt-2"><strong id="videoId"></strong></p>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('lessons.index') }}" class="btn btn-secondary">
                                {{ __('messages.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                {{ __('messages.create_lesson') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function validateYoutubeUrl() {
    const url = document.getElementById('link_youtube').value;
    const preview = document.getElementById('videoPreview');
    
    if (!url) {
        preview.style.display = 'none';
        return;
    }
    
    fetch(`{{ route('lessons.validate-youtube') }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ url: url })
    })
    .then(response => response.json())
    .then(data => {
        if (data.valid) {
            document.getElementById('videoThumbnail').src = data.thumbnail;
            document.getElementById('videoId').textContent = `{{ __('messages.video_id') }}: ${data.video_id}`;
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        preview.style.display = 'none';
    });
}
</script>
@endsection