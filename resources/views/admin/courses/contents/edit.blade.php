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
                                        <option value="quiz" {{ old('content_type', $content->content_type) == 'quiz' ? 'selected' : '' }}>
                                            {{ __('messages.quiz') }}
                                        </option>
                                        <option value="assignment" {{ old('content_type', $content->content_type) == 'assignment' ? 'selected' : '' }}>
                                            {{ __('messages.assignment') }}
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
                                    <select class="form-control @error('video_type') is-invalid @enderror" 
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

                            <div class="col-md-4">
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
                                        <a href="{{ $content->video_url }}" target="_blank" class="btn btn-sm btn-primary ms-2">
                                            <i class="fas fa-external-link-alt"></i> {{ __('messages.view_current_video') }}
                                        </a>
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

                            @if($content->content_type === 'pdf' && $content->file_path)
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <strong>{{ __('messages.current_pdf') }}:</strong>
                                        <a href="{{ $content->file_url }}" target="_blank" class="btn btn-sm btn-primary ms-2">
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
    const videoFields = document.getElementById('video-fields');
    const pdfFields = document.getElementById('pdf-fields');
    
    // Hide all fields
    videoFields.style.display = 'none';
    pdfFields.style.display = 'none';
    
    // Show relevant fields
    if (contentType === 'video') {
        videoFields.style.display = 'block';
    } else if (contentType === 'pdf') {
        pdfFields.style.display = 'block';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleContentFields();
});
</script>
@endsection