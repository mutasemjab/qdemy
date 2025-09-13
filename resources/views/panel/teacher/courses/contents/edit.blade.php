@extends('layouts.app')
@section('title', __('panel.edit_content'))

@section('content')
<section class="ud-wrap">
    <aside class="ud-menu">
        <div class="ud-user">
            <img data-src="{{ auth()->user()->photo ? asset('assets/admin/uploads/' . auth()->user()->photo) : asset('assets_front/images/avatar-big.png') }}"
                alt="">
            <div>
                <h3>{{ auth()->user()->name }}</h3>
                <span>{{ auth()->user()->email }}</span>
            </div>
        </div>

        <a href="{{ route('teacher.courses.sections.index', $course) }}" class="ud-item">
            <i class="fa-solid fa-arrow-left"></i>
            <span>{{ __('panel.back_to_course') }}</span>
        </a>
    </aside>

    <div class="ud-content">
        <div class="ud-panel show">
            <div class="content-header">
                <h1>{{ __('panel.edit_content') }}</h1>
                <p class="course-name">{{ $course->title_ar }}</p>
                <div class="content-breadcrumb">
                    <span>{{ __('panel.current_content') }}: {{ $content->title_ar }}</span>
                    @if($content->section_id)
                        <small>({{ __('panel.in_section') }} {{ $content->section->title_ar }})</small>
                    @else
                        <small>({{ __('panel.direct_content') }})</small>
                    @endif
                </div>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('teacher.courses.contents.update', [$course, $content]) }}" 
                  enctype="multipart/form-data" class="content-form" id="contentForm">
                @csrf
                @method('PUT')
                
                <!-- Basic Information -->
                <div class="form-section">
                    <h3>{{ __('panel.basic_information') }}</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="title_ar">{{ __('panel.title_ar') }} *</label>
                            <input type="text" id="title_ar" name="title_ar" value="{{ old('title_ar', $content->title_ar) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="title_en">{{ __('panel.title_en') }} *</label>
                            <input type="text" id="title_en" name="title_en" value="{{ old('title_en', $content->title_en) }}" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="content_type">{{ __('panel.content_type') }} *</label>
                            <select id="content_type" name="content_type" required onchange="toggleContentFields()">
                                <option value="">{{ __('panel.select_content_type') }}</option>
                                <option value="video" {{ old('content_type', $content->content_type) == 'video' ? 'selected' : '' }}>
                                    {{ __('panel.video') }}
                                </option>
                                <option value="pdf" {{ old('content_type', $content->content_type) == 'pdf' ? 'selected' : '' }}>
                                    {{ __('panel.pdf_document') }}
                                </option>
                                <option value="quiz" {{ old('content_type', $content->content_type) == 'quiz' ? 'selected' : '' }}>
                                    {{ __('panel.quiz') }}
                                </option>
                                <option value="assignment" {{ old('content_type', $content->content_type) == 'assignment' ? 'selected' : '' }}>
                                    {{ __('panel.assignment') }}
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="section_id">{{ __('panel.section') }}</label>
                            <select id="section_id" name="section_id">
                                <option value="">{{ __('panel.no_section') }}</option>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}" 
                                            {{ old('section_id', $content->section_id) == $section->id ? 'selected' : '' }}>
                                        {{ $section->title_ar }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="is_free">{{ __('panel.access_type') }} *</label>
                            <select id="is_free" name="is_free" required>
                                <option value="2" {{ old('is_free', $content->is_free) == '2' ? 'selected' : '' }}>
                                    {{ __('panel.paid_content') }}
                                </option>
                                <option value="1" {{ old('is_free', $content->is_free) == '1' ? 'selected' : '' }}>
                                    {{ __('panel.free_content') }}
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="order">{{ __('panel.order') }}</label>
                            <input type="number" id="order" name="order" value="{{ old('order', $content->order) }}" min="1">
                        </div>
                    </div>
                </div>

                <!-- Current Content Display -->
                <div class="form-section">
                    <h3>{{ __('panel.current_content') }}</h3>
                    <div class="current-content-display">
                        <div class="content-type-badge">
                            <span class="badge badge-{{ $content->content_type === 'video' ? 'primary' : 'secondary' }}">
                                {{ ucfirst($content->content_type) }}
                            </span>
                        </div>
                        
                        @if($content->content_type === 'video')
                            <div class="current-video">
                                <div class="video-info">
                                    <h4>{{ __('panel.current_video') }}</h4>
                                    <p><strong>{{ __('panel.type') }}:</strong> {{ ucfirst($content->video_type) }}</p>
                                    @if($content->video_duration)
                                        <p><strong>{{ __('panel.duration') }}:</strong> {{ gmdate('H:i:s', $content->video_duration) }}</p>
                                    @endif
                                </div>
                                @if($content->video_url)
                                    <div class="video-preview">
                                        @if($content->video_type === 'youtube')
                                            <a href="{{ $content->video_url }}" target="_blank" class="btn btn-outline-primary">
                                                <i class="fa-solid fa-external-link-alt"></i> {{ __('panel.view_on_youtube') }}
                                            </a>
                                        @else
                                            <video controls width="300" height="200">
                                                <source src="{{ asset($content->video_url) }}" type="video/mp4">
                                                {{ __('panel.video_not_supported') }}
                                            </video>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @elseif($content->file_path)
                            <div class="current-file">
                                <div class="file-info">
                                    <h4>{{ __('panel.current_file') }}</h4>
                                    <p><strong>{{ __('panel.type') }}:</strong> {{ ucfirst($content->pdf_type ?? 'PDF') }}</p>
                                </div>
                                <div class="file-actions">
                                    <a href="{{ asset($content->file_path) }}" target="_blank" class="btn btn-outline-primary">
                                        <i class="fa-solid fa-download"></i> {{ __('panel.download_current_file') }}
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Video Settings -->
                <div class="form-section" id="video-fields" style="display: none;">
                    <h3>{{ __('panel.video_settings') }}</h3>
                    
                    <div class="form-group">
                        <label for="video_type">{{ __('panel.video_type') }} *</label>
                        <select id="video_type" name="video_type" onchange="toggleContentFields()">
                            <option value="">{{ __('panel.select_video_type') }}</option>
                            <option value="youtube" {{ old('video_type', $content->video_type) == 'youtube' ? 'selected' : '' }}>
                                {{ __('panel.youtube_video') }}
                            </option>
                            <option value="bunny" {{ old('video_type', $content->video_type) == 'bunny' ? 'selected' : '' }}>
                                {{ __('panel.upload_video') }}
                            </option>
                        </select>
                    </div>

                    <!-- YouTube URL -->
                    <div class="form-group" id="video_url_field" style="display: none;">
                        <label for="video_url">{{ __('panel.youtube_url') }} *</label>
                        <input type="url" id="video_url" name="video_url" value="{{ old('video_url', $content->video_url) }}" 
                               placeholder="https://www.youtube.com/watch?v=...">
                        <small class="form-text">{{ __('panel.youtube_url_help') }}</small>
                    </div>

                    <!-- Video Upload -->
                    <div class="form-group" id="upload_video_field" style="display: none;">
                        <label for="upload_video">{{ __('panel.upload_video_file') }}</label>
                        <div class="file-upload-wrapper">
                            <input type="file" id="upload_video" name="upload_video" 
                                   accept="video/mp4,video/avi,video/mov,video/wmv">
                            <div class="upload-preview" id="video-preview" style="display: none;">
                                <video controls style="max-width: 100%; height: 200px;"></video>
                            </div>
                        </div>
                        <small class="form-text">{{ __('panel.leave_empty_keep_current') }}</small>
                    </div>

                    <div class="form-group">
                        <label for="video_duration">{{ __('panel.video_duration') }} ({{ __('panel.seconds') }})</label>
                        <input type="number" id="video_duration" name="video_duration" 
                               value="{{ old('video_duration', $content->video_duration) }}" min="1">
                        <small class="form-text">{{ __('panel.video_duration_help') }}</small>
                    </div>
                </div>

                <!-- PDF Settings -->
                <div class="form-section" id="pdf-fields" style="display: none;">
                    <h3>{{ __('panel.document_settings') }}</h3>
                    
                    <div class="form-group">
                        <label for="pdf_type">{{ __('panel.document_type') }} *</label>
                        <select id="pdf_type" name="pdf_type">
                            <option value="">{{ __('panel.select_document_type') }}</option>
                            <option value="notes" {{ old('pdf_type', $content->pdf_type) == 'notes' ? 'selected' : '' }}>
                                {{ __('panel.notes') }}
                            </option>
                            <option value="homework" {{ old('pdf_type', $content->pdf_type) == 'homework' ? 'selected' : '' }}>
                                {{ __('panel.homework') }}
                            </option>
                            <option value="worksheet" {{ old('pdf_type', $content->pdf_type) == 'worksheet' ? 'selected' : '' }}>
                                {{ __('panel.worksheet') }}
                            </option>
                            <option value="other" {{ old('pdf_type', $content->pdf_type) == 'other' ? 'selected' : '' }}>
                                {{ __('panel.other') }}
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="file_path">{{ __('panel.upload_pdf') }}</label>
                        <div class="file-upload-wrapper">
                            <input type="file" id="file_path" name="file_path" accept=".pdf">
                            <div class="upload-preview" id="pdf-preview" style="display: none;">
                                <div class="pdf-info">
                                    <i class="fa-solid fa-file-pdf"></i>
                                    <span class="file-name"></span>
                                    <span class="file-size"></span>
                                </div>
                            </div>
                        </div>
                        <small class="form-text">{{ __('panel.leave_empty_keep_current') }}</small>
                    </div>
                </div>

                <!-- Quiz/Assignment Settings -->
                <div class="form-section" id="quiz-settings" style="display: none;">
                    <h3>{{ __('panel.quiz_assignment_settings') }}</h3>
                    <div class="alert alert-info">
                        <i class="fa-solid fa-info-circle"></i>
                        {{ __('panel.quiz_assignment_note') }}
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('teacher.courses.sections.index', $course) }}" class="btn btn-secondary">
                        {{ __('panel.cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fa-solid fa-save"></i>
                        {{ __('panel.update_content') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
.content-header {
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f0f0f0;
}

.content-header h1 {
    font-size: 1.8em;
    color: #333;
    margin: 0 0 5px 0;
}

.course-name {
    color: #666;
    margin: 0 0 10px 0;
    font-size: 1.1em;
}

.content-breadcrumb {
    color: #007bff;
    font-size: 0.9em;
}

.content-breadcrumb small {
    color: #666;
    margin-left: 5px;
}

.content-form {
    max-width: 800px;
}

.form-section {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 25px;
}

.form-section h3 {
    margin: 0 0 20px 0;
    color: #495057;
    font-size: 1.2em;
    border-bottom: 1px solid #e9ecef;
    padding-bottom: 10px;
}

.current-content-display {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.content-type-badge {
    margin-bottom: 15px;
}

.badge {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.85em;
    font-weight: 600;
}

.badge-primary {
    background: #007bff;
    color: white;
}

.badge-secondary {
    background: #6c757d;
    color: white;
}

.current-video,
.current-file {
    display: flex;
    gap: 20px;
    align-items: flex-start;
}

.video-info,
.file-info {
    flex: 1;
}

.video-info h4,
.file-info h4 {
    color: #333;
    font-size: 1.1em;
    margin: 0 0 10px 0;
}

.video-info p,
.file-info p {
    color: #666;
    font-size: 0.9em;
    margin: 5px 0;
}

.video-preview,
.file-actions {
    flex-shrink: 0;
}

.btn-outline-primary {
    border: 1px solid #007bff;
    color: #007bff;
    background: transparent;
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 0.9em;
    transition: all 0.3s ease;
}

.btn-outline-primary:hover {
    background: #007bff;
    color: white;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    color: #333;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.3s ease;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.file-upload-wrapper {
    position: relative;
}

.upload-preview {
    margin-top: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 6px;
}

.pdf-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.pdf-info i {
    font-size: 24px;
    color: #dc3545;
}

.file-name {
    font-weight: 600;
    color: #333;
}

.file-size {
    color: #666;
    font-size: 0.9em;
}

.form-text {
    color: #666;
    font-size: 12px;
    margin-top: 5px;
    display: block;
}

.form-actions {
    display: flex;
    gap: 15px;
    justify-content: flex-end;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover {
    background: #0056b3;
}

.btn-primary:disabled {
    background: #6c757d;
    cursor: not-allowed;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #545b62;
}

.alert {
    padding: 12px 15px;
    border-radius: 6px;
    margin-bottom: 20px;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert-info {
    background: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
}

.alert ul {
    margin: 0;
    padding-left: 20px;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .current-video,
    .current-file {
        flex-direction: column;
    }
    
    .video-preview video {
        width: 100%;
        height: auto;
    }
}
</style>
@endsection

@section('scripts')
<script>
// Using vanilla JavaScript - matching admin pattern exactly
function toggleContentFields() {
    const contentType = document.getElementById('content_type').value;
    const videoType = document.getElementById('video_type').value;
    const videoFields = document.getElementById('video-fields');
    const videoUpload = document.getElementById('upload_video_field');
    const videoUrl = document.getElementById('video_url_field');
    const pdfFields = document.getElementById('pdf-fields');
    const quizSettings = document.getElementById('quiz-settings');

    // Hide all fields first
    videoFields.style.display = 'none';
    videoUpload.style.display = 'none';
    videoUrl.style.display = 'none';
    pdfFields.style.display = 'none';
    quizSettings.style.display = 'none';
    
    // Show relevant fields based on content type
    if (contentType === 'video') {
        videoFields.style.display = 'block';
        
        // Show video type specific fields
        if (videoType === 'youtube') {
            videoUrl.style.display = 'block';
        } else if (videoType === 'bunny') {
            videoUpload.style.display = 'block';
        }
    } else if (contentType === 'pdf') {
        pdfFields.style.display = 'block';
    } else if (contentType === 'quiz' || contentType === 'assignment') {
        quizSettings.style.display = 'block';
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize field visibility
    toggleContentFields();
    
    // File upload handlers
    const uploadVideo = document.getElementById('upload_video');
    if (uploadVideo) {
        uploadVideo.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const videoPreview = document.getElementById('video-preview');
                const video = videoPreview.querySelector('video');
                
                video.src = URL.createObjectURL(file);
                videoPreview.style.display = 'block';
            }
        });
    }

    const filePath = document.getElementById('file_path');
    if (filePath) {
        filePath.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const pdfPreview = document.getElementById('pdf-preview');
                const fileName = file.name;
                const fileSize = (file.size / 1024 / 1024).toFixed(2) + ' MB';
                
                pdfPreview.querySelector('.file-name').textContent = fileName;
                pdfPreview.querySelector('.file-size').textContent = fileSize;
                pdfPreview.style.display = 'block';
            }
        });
    }

    // Form validation and submission
    const contentForm = document.getElementById('contentForm');
    if (contentForm) {
        contentForm.addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> {{ __("panel.updating") }}...';
                
                // Re-enable after 30 seconds to prevent permanent lock
                setTimeout(function() {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fa-solid fa-save"></i> {{ __("panel.update_content") }}';
                }, 30000);
            }
        });
    }
});
</script>
@endsection