@extends('layouts.admin')

@section('title', __('messages.edit_content'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.edit_content') }}</h3>
                    <p class="text-muted mb-0">{{ __('messages.course') }}: {{ $course->title_en }}</p>
                    <div class="card-tools">
                        <a href="{{ route('courses.sections.index', $course) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                        </a>
                    </div>
                </div>

                <form action="{{ route('courses.contents.update', [$course, $content]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <!-- English Title -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="title_en" class="form-label">
                                        {{ __('messages.content_title_en') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control @error('title_en') is-invalid @enderror"
                                           id="title_en"
                                           name="title_en"
                                           value="{{ old('title_en', $content->title_en) }}"
                                           placeholder="{{ __('messages.enter_content_title_en') }}">
                                    @error('title_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Arabic Title -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="title_ar" class="form-label">
                                        {{ __('messages.content_title_ar') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control @error('title_ar') is-invalid @enderror"
                                           id="title_ar"
                                           name="title_ar"
                                           value="{{ old('title_ar', $content->title_ar) }}"
                                           placeholder="{{ __('messages.enter_content_title_ar') }}"
                                           dir="rtl">
                                    @error('title_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Content Type -->
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="content_type" class="form-label">
                                        {{ __('messages.content_type') }} <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control @error('content_type') is-invalid @enderror"
                                            id="content_type"
                                            name="content_type"
                                            onchange="toggleContentFields()">
                                        <option value="">{{ __('messages.select_content_type') }}</option>
                                        <option value="video" {{ old('content_type', $content->content_type) == 'video' ? 'selected' : '' }}>
                                            {{ __('messages.video') }}
                                        </option>
                                        <option value="pdf" {{ old('content_type', $content->content_type) == 'pdf' ? 'selected' : '' }}>
                                            {{ __('messages.pdf') }}
                                        </option>
                                    </select>
                                    @error('content_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Access Type -->
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="is_free" class="form-label">
                                        {{ __('messages.access_type') }} <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control @error('is_free') is-invalid @enderror"
                                            id="is_free"
                                            name="is_free">
                                        <option value="">{{ __('messages.select_access_type') }}</option>
                                        <option value="1" {{ old('is_free', $content->is_free) == '1' ? 'selected' : '' }}>
                                            {{ __('messages.free') }}
                                        </option>
                                        <option value="2" {{ old('is_free', $content->is_free) == '2' ? 'selected' : '' }}>
                                            {{ __('messages.paid') }}
                                        </option>
                                    </select>
                                    @error('is_free')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="is_main_video" class="form-label">
                                        {{ __('messages.is it main video?') }} <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control @error('is_main_video') is-invalid @enderror"
                                            id="is_main_video"
                                            name="is_main_video">
                                        <option value="2" {{ old('is_main_video', $content->is_main_video) == '2' ? 'selected' : '' }}>
                                            {{ __('messages.no') }}
                                        </option>
                                         <option value="1" {{ old('is_main_video', $content->is_main_video) == '1' ? 'selected' : '' }}>
                                            {{ __('messages.yes') }}
                                        </option>
                                    </select>
                                    @error('is_main_video')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Order -->
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="order" class="form-label">
                                        {{ __('messages.order') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="number"
                                           class="form-control @error('order') is-invalid @enderror"
                                           id="order"
                                           name="order"
                                           value="{{ old('order', $content->order) }}"
                                           min="0"
                                           placeholder="1">
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Section -->
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="section_id" class="form-label">{{ __('messages.section') }}</label>
                                    <select class="form-control @error('section_id') is-invalid @enderror"
                                            id="section_id"
                                            name="section_id">
                                        <option value="">{{ __('messages.select_section_or_direct') }}</option>
                                        @php
                                            $parentSections = $sections->whereNull('parent_id');
                                        @endphp
                                        @foreach($parentSections as $section)
                                            @include('admin.courses.partials.section-option', [
                                                'section' => $section,
                                                'allSections' => $sections,
                                                'level' => 0,
                                                'selectedId' => old('section_id', $content->section_id)
                                            ])
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">
                                        {{ __('messages.section_help') }}
                                    </small>
                                    @error('section_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Video Fields -->
                        <div id="video-fields" class="row" style="display: none;">
                            <div class="col-12">
                                <h5 class="text-primary">{{ __('messages.video_details') }}</h5>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="video_type" class="form-label">
                                        {{ __('messages.video_type') }} <span class="text-danger">*</span>
                                    </label>
                                    <select onchange="toggleContentFields()" class="form-control @error('video_type') is-invalid @enderror"
                                            id="video_type"
                                            name="video_type">
                                        <option value="">{{ __('messages.select_video_type') }}</option>
                                        <option value="youtube" {{ old('video_type', $content->video_type) == 'youtube' ? 'selected' : '' }}>
                                            YouTube
                                        </option>
                                        <option value="bunny" {{ old('video_type', $content->video_type) == 'bunny' ? 'selected' : '' }}>
                                            Bunny CDN
                                        </option>
                                    </select>
                                    @error('video_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4" id="video_url_field">
                                <div class="form-group mb-3">
                                    <label for="video_url" class="form-label">
                                        {{ __('messages.video_url') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="url"
                                           class="form-control @error('video_url') is-invalid @enderror"
                                           id="video_url"
                                           name="video_url"
                                           value="{{ old('video_url', $content->video_url) }}"
                                           placeholder="https://example.com/video">
                                    @error('video_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6" id="upload_video_field">
                                <div class="form-group mb-3">
                                    <label for="upload_video" class="form-label">
                                        {{ __('messages.upload_video') }}
                                    </label>
                                    <input type="file"
                                           class="form-control @error('upload_video') is-invalid @enderror"
                                           id="upload_video"
                                           name="upload_video"
                                           accept="video/*">
                                    <small class="form-text text-muted">{{ __('messages.video_requirements_optional') }}</small>
                                    @error('upload_video')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Hidden field for Bunny path -->
                            <input type="hidden" name="bunny_video_path" id="bunny_video_path">

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="video_duration" class="form-label">{{ __('messages.video_duration') }}</label>
                                    <input type="number"
                                           class="form-control @error('video_duration') is-invalid @enderror"
                                           id="video_duration"
                                           name="video_duration"
                                           value="{{ old('video_duration', $content->video_duration) }}"
                                           min="0"
                                           placeholder="{{ __('messages.duration_seconds') }}">
                                    <small class="form-text text-muted">{{ __('messages.duration_seconds_help') }}</small>
                                    @error('video_duration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            @if($content->content_type === 'video' && $content->video_url)
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <strong>{{ __('messages.current_video') }}:</strong>
                                        @if($content->video_type === 'youtube')
                                            <a href="{{ $content->video_url }}" target="_blank" class="btn btn-sm btn-primary ms-2">
                                                <i class="fas fa-external-link-alt"></i> {{ __('messages.view_current_video') }}
                                            </a>
                                        @else
                                            <span class="badge bg-success">{{ __('messages.bunny_video_uploaded') }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- PDF Fields -->
                        <div id="pdf-fields" class="row" style="display: none;">
                            <div class="col-12">
                                <h5 class="text-primary">{{ __('messages.pdf_details') }}</h5>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="pdf_type" class="form-label">
                                        {{ __('messages.pdf_type') }} <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control @error('pdf_type') is-invalid @enderror"
                                            id="pdf_type"
                                            name="pdf_type">
                                        <option value="">{{ __('messages.select_pdf_type') }}</option>
                                        <option value="homework" {{ old('pdf_type', $content->pdf_type) == 'homework' ? 'selected' : '' }}>
                                            {{ __('messages.homework') }}
                                        </option>
                                        <option value="worksheet" {{ old('pdf_type', $content->pdf_type) == 'worksheet' ? 'selected' : '' }}>
                                            {{ __('messages.worksheet') }}
                                        </option>
                                        <option value="notes" {{ old('pdf_type', $content->pdf_type) == 'notes' ? 'selected' : '' }}>
                                            {{ __('messages.notes') }}
                                        </option>
                                        <option value="other" {{ old('pdf_type', $content->pdf_type) == 'other' ? 'selected' : '' }}>
                                            {{ __('messages.other') }}
                                        </option>
                                    </select>
                                    @error('pdf_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="file_path" class="form-label">
                                        {{ __('messages.new_pdf_file') }}
                                    </label>
                                    <input type="file"
                                           class="form-control @error('file_path') is-invalid @enderror"
                                           id="file_path"
                                           name="file_path"
                                           accept=".pdf">
                                    <small class="form-text text-muted">{{ __('messages.pdf_requirements_optional') }}</small>
                                    @error('file_path')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            @if($content->content_type != 'video' && $content->file_path)
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <strong>{{ __('messages.current_pdf') }}:</strong>
                                        <a href="{{ $content->file_path }}" target="_blank" class="btn btn-sm btn-primary ms-2">
                                            <i class="fas fa-download"></i> {{ __('messages.download_current_pdf') }}
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('courses.sections.index', $course) }}" class="btn btn-secondary">
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

<script>
function toggleContentFields() {
    const contentType = document.getElementById('content_type').value;
    const videoType   = document.getElementById('video_type').value;
    const videoFields = document.getElementById('video-fields');
    const videoUpload = document.getElementById('upload_video_field');
    const videoUrl    = document.getElementById('video_url_field');
    const pdfFields   = document.getElementById('pdf-fields');

    // Hide all fields
    videoFields.style.display = 'none';
    videoUpload.style.display = 'none';
    videoUrl.style.display    = 'none';
    pdfFields.style.display   = 'none';

    // Show relevant fields
    if (contentType === 'video') {
        videoFields.style.display = 'block';
    }
    if (contentType === 'video' && videoType == 'youtube') {
        videoUrl.style.display    = 'block';
    } else if (contentType === 'video' && videoType == 'bunny') {
        videoUpload.style.display = 'block';
    } else if (contentType === 'pdf') {
        pdfFields.style.display = 'block';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleContentFields();
});
</script>

<!-- Bunny Upload Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const videoInput = document.getElementById('upload_video');
    const videoTypeInput = document.getElementById('video_type');
    const videoUrlInput = document.getElementById('video_url');
    const bunnyVideoPathInput = document.getElementById('bunny_video_path');
    const videoDurationInput = document.getElementById('video_duration');

    // Create loading indicator
    const loadingDiv = document.createElement('div');
    loadingDiv.id = 'upload-loading';
    loadingDiv.style.position = 'fixed';
    loadingDiv.style.top = '0';
    loadingDiv.style.left = '0';
    loadingDiv.style.width = '100%';
    loadingDiv.style.height = '100%';
    loadingDiv.style.background = 'rgba(0,0,0,0.7)';
    loadingDiv.style.color = 'white';
    loadingDiv.style.fontSize = '1.5rem';
    loadingDiv.style.display = 'none';
    loadingDiv.style.justifyContent = 'center';
    loadingDiv.style.alignItems = 'center';
    loadingDiv.style.zIndex = '9999';
    loadingDiv.style.flexDirection = 'column';
    loadingDiv.innerHTML = `
        <div style="text-align: center;">
            <i class="fas fa-spinner fa-spin fa-3x mb-3"></i>
            <p>Uploading video... Please wait</p>
            <small>Do not close this window</small>
        </div>
    `;
    document.body.appendChild(loadingDiv);

    async function uploadToBunny(file, courseId) {
        const res = await fetch('/api/bunny/sign-upload', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                course_id: courseId
            })
        });

        const data = await res.json();

        if (!data.upload_url || !data.file_path) {
            throw new Error('Failed to get upload URL from server');
        }

        const uploadRes = await fetch(data.upload_url, {
            method: 'PUT',
            headers: {
                'AccessKey': data.access_key,
                'Content-Type': file.type
            },
            body: file
        });

        if (!uploadRes.ok) {
            throw new Error('Video upload failed at Bunny CDN');
        }

        return data.file_path;
    }

    form.addEventListener('submit', async function(e) {
        const contentType = document.getElementById('content_type').value;
        const videoType = videoTypeInput.value;

        // Only intercept if:
        // 1. It's a video content type
        // 2. Video type is bunny
        // 3. A new video file is being uploaded
        if (contentType === 'video' && videoType === 'bunny' && videoInput.files.length > 0) {
            e.preventDefault();

            const file = videoInput.files[0];
            loadingDiv.style.display = 'flex';

            try {
                // Upload video to Bunny CDN
                const path = await uploadToBunny(file, {{ $course->id }});

                // Set the hidden field with Bunny path
                bunnyVideoPathInput.value = path;
                
                // Clear the video_url field (it's for YouTube only)
                videoUrlInput.value = '';

                // Calculate duration if not provided
                if (!videoDurationInput.value) {
                    const video = document.createElement('video');
                    video.preload = 'metadata';
                    video.src = URL.createObjectURL(file);
                    video.onloadedmetadata = function() {
                        window.URL.revokeObjectURL(video.src);
                        videoDurationInput.value = Math.floor(video.duration);
                        
                        // Clear file input
                        videoInput.value = '';
                        
                        // Submit form
                        form.submit();
                    };
                    return;
                }

                // Clear file input
                videoInput.value = '';
                
                // Submit form
                form.submit();

            } catch (err) {
                alert('Video upload failed: ' + err.message + '. Please try again.');
                console.error(err);
                loadingDiv.style.display = 'none';
            }
        }
    });
});
</script>

@endsection