{{-- resources/views/lessons/edit.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('messages.edit_lesson') }}: {{ $lesson->name }}</h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('lessons.update', $lesson) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('messages.lesson_name') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $lesson->name) }}" required>
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
                                    <option value="{{ $category->id }}" 
                                        {{ old('category_lesson_id', $lesson->category_lesson_id) == $category->id ? 'selected' : '' }}>
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
                                   id="link_youtube" name="link_youtube" value="{{ old('link_youtube', $lesson->link_youtube) }}" 
                                   required onblur="validateYoutubeUrl()" placeholder="https://www.youtube.com/watch?v=...">
                            <div class="form-text">{{ __('messages.youtube_url_help') }}</div>
                            @error('link_youtube')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Current Video Preview -->
                        @if($lesson->getYoutubeThumbnailAttribute())
                            <div class="mb-3">
                                <label class="form-label">{{ __('messages.current_video') }}</label>
                                <div class="card">
                                    <div class="card-body text-center">
                                        <img src="{{ $lesson->getYoutubeThumbnailAttribute() }}" 
                                             alt="Current Video" class="img-fluid" style="max-width: 200px;">
                                        <p class="mt-2">
                                            <strong>{{ __('messages.video_id') }}:</strong> {{ $lesson->getYoutubeIdAttribute() }}
                                        </p>
                                        <a href="{{ route('lessons.watch', $lesson) }}" target="_blank" class="btn btn-sm btn-primary">
                                            <i class="fas fa-play"></i> {{ __('messages.watch_current') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div id="videoPreview" class="mb-3" style="display: none;">
                            <div class="card">
                                <div class="card-header">
                                    <h6>{{ __('messages.new_video_preview') }}</h6>
                                </div>
                                <div class="card-body text-center">
                                    <img id="videoThumbnail" src="" alt="Video Thumbnail" class="img-fluid" style="max-width: 200px;">
                                    <p class="mt-2"><strong id="videoId"></strong></p>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('lessons.show', $lesson) }}" class="btn btn-secondary">
                                {{ __('messages.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                {{ __('messages.update_lesson') }}
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
    const currentUrl = '{{ $lesson->link_youtube }}';
    const preview = document.getElementById('videoPreview');
    
    if (!url || url === currentUrl) {
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