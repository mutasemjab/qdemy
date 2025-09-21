@extends('layouts.app')
@section('title', __('panel.edit_section'))

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
            <div class="section-header">
                <h1>{{ __('panel.edit_section') }}</h1>
                <p class="course-name">{{ $course->title_ar }}</p>
                <div class="section-breadcrumb">
                    <span>{{ __('panel.current_section') }}: {{ $section->title_ar }}</span>
                    @if($section->parent_id)
                        <small>({{ __('panel.subsection_of') }} {{ $section->parent->title_ar }})</small>
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

            <form method="POST" action="{{ route('teacher.courses.sections.update', [$course, $section]) }}" class="section-form">
                @csrf
                @method('PUT')
                
                <div class="form-section">
                    <h3>{{ __('panel.section_information') }}</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="title_ar">{{ __('panel.section_title_ar') }} *</label>
                            <input type="text" id="title_ar" name="title_ar" value="{{ old('title_ar', $section->title_ar) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="title_en">{{ __('panel.section_title_en') }} *</label>
                            <input type="text" id="title_en" name="title_en" value="{{ old('title_en', $section->title_en) }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="parent_id">{{ __('panel.parent_section') }}</label>
                        <select id="parent_id" name="parent_id">
                            <option value="">{{ __('panel.no_parent_section') }}</option>
                            @foreach($sections as $parentSection)
                                <option value="{{ $parentSection->id }}" {{ old('parent_id', $section->parent_id) == $parentSection->id ? 'selected' : '' }}>
                                    {{ $parentSection->title_ar }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text">{{ __('panel.parent_section_help') }}</small>
                    </div>

                    <!-- Section Statistics -->
                    <div class="section-stats">
                        <div class="stats-row">
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fa-solid fa-play"></i>
                                </div>
                                <div class="stat-info">
                                    <h4>{{ $section->contents->count() }}</h4>
                                    <p>{{ __('panel.direct_contents') }}</p>
                                </div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fa-solid fa-folder"></i>
                                </div>
                                <div class="stat-info">
                                    <h4>{{ $section->children->count() }}</h4>
                                    <p>{{ __('panel.subsections') }}</p>
                                </div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fa-solid fa-list"></i>
                                </div>
                                <div class="stat-info">
                                    @php
                                        $totalContents = $section->contents->count() + $section->children->sum(function($child) {
                                            return $child->contents->count();
                                        });
                                    @endphp
                                    <h4>{{ $totalContents }}</h4>
                                    <p>{{ __('panel.total_contents') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content Preview -->
                @if($section->contents->count() > 0 || $section->children->count() > 0)
                    <div class="form-section">
                        <h3>{{ __('panel.section_contents_preview') }}</h3>
                        
                        <!-- Direct Contents -->
                        @if($section->contents->count() > 0)
                            <div class="content-preview">
                                <h4>{{ __('panel.direct_contents') }}</h4>
                                <div class="content-list">
                                    @foreach($section->contents as $content)
                                        <div class="content-preview-item">
                                            <div class="content-icon {{ $content->content_type }}">
                                                <i class="fa-solid fa-{{ $content->content_type === 'video' ? 'play' : 'file-pdf' }}"></i>
                                            </div>
                                            <div class="content-info">
                                                <h5>{{ $content->title_ar }}</h5>
                                                <div class="content-meta">
                                                    <span class="content-type">{{ ucfirst($content->content_type) }}</span>
                                                    <span class="content-access">{{ $content->is_free == 1 ? __('panel.free') : __('panel.paid') }}</span>
                                                </div>
                                            </div>
                                            <div class="content-actions">
                                                <a href="{{ route('teacher.courses.contents.edit', [$course, $content]) }}" class="btn-small">
                                                    <i class="fa-solid fa-edit"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Child Sections -->
                        @if($section->children->count() > 0)
                            <div class="subsections-preview">
                                <h4>{{ __('panel.subsections') }}</h4>
                                <div class="subsections-list">
                                    @foreach($section->children as $childSection)
                                        <div class="subsection-preview-item">
                                            <div class="subsection-header">
                                                <h5>
                                                    <i class="fa-solid fa-folder-open"></i>
                                                    {{ $childSection->title_ar }}
                                                </h5>
                                                <span class="content-count">{{ $childSection->contents->count() }} {{ __('panel.items') }}</span>
                                            </div>
                                            @if($childSection->contents->count() > 0)
                                                <div class="child-contents">
                                                    @foreach($childSection->contents as $content)
                                                        <div class="child-content-item">
                                                            <i class="fa-solid fa-{{ $content->content_type === 'video' ? 'play' : 'file-pdf' }}"></i>
                                                            <span>{{ $content->title_ar }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('teacher.courses.sections.index', $course) }}" class="btn btn-secondary">
                        {{ __('panel.cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-save"></i>
                        {{ __('panel.update_section') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
.section-header {
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f0f0f0;
}

.section-header h1 {
    font-size: 1.8em;
    color: #333;
    margin: 0 0 5px 0;
}

.course-name {
    color: #666;
    margin: 0 0 10px 0;
    font-size: 1.1em;
}

.section-breadcrumb {
    color: #007bff;
    font-size: 0.9em;
}

.section-breadcrumb small {
    color: #666;
    margin-left: 5px;
}

.section-form {
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
.form-group select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.3s ease;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.form-text {
    color: #666;
    font-size: 12px;
    margin-top: 5px;
    display: block;
}

.section-stats {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #e9ecef;
}

.stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: #007bff;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
}

.stat-info h4 {
    font-size: 1.4em;
    font-weight: 700;
    color: #333;
    margin: 0;
}

.stat-info p {
    color: #666;
    font-size: 0.85em;
    margin: 0;
}

.content-preview,
.subsections-preview {
    margin-bottom: 25px;
}

.content-preview h4,
.subsections-preview h4 {
    color: #495057;
    font-size: 1.1em;
    margin: 0 0 15px 0;
    padding-bottom: 8px;
    border-bottom: 1px solid #e9ecef;
}

.content-list,
.subsections-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.content-preview-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.content-icon {
    width: 35px;
    height: 35px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 14px;
}

.content-icon.video {
    background: #dc3545;
}

.content-icon.pdf {
    background: #28a745;
}

.content-info {
    flex: 1;
}

.content-info h5 {
    margin: 0 0 3px 0;
    color: #333;
    font-size: 0.95em;
}

.content-meta {
    display: flex;
    gap: 10px;
    font-size: 0.8em;
    color: #666;
}

.content-actions {
    display: flex;
    gap: 5px;
}

.btn-small {
    width: 28px;
    height: 28px;
    border-radius: 4px;
    background: #007bff;
    color: white;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 12px;
    transition: all 0.3s ease;
}

.btn-small:hover {
    background: #0056b3;
    color: white;
}

.subsection-preview-item {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    overflow: hidden;
    background: #fff;
}

.subsection-header {
    background: #f8f9fa;
    padding: 12px 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #e9ecef;
}

.subsection-header h5 {
    margin: 0;
    color: #495057;
    font-size: 0.95em;
    display: flex;
    align-items: center;
    gap: 8px;
}

.content-count {
    background: #007bff;
    color: white;
    padding: 2px 6px;
    border-radius: 10px;
    font-size: 0.75em;
}

.child-contents {
    padding: 10px 15px;
}

.child-content-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 5px 0;
    color: #666;
    font-size: 0.85em;
}

.child-content-item i {
    color: #007bff;
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

.alert ul {
    margin: 0;
    padding-left: 20px;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .stats-row {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .content-preview-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .subsection-header {
        flex-direction: column;
        gap: 8px;
        align-items: flex-start;
    }
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form submission with loading state
    const sectionForm = document.querySelector('.section-form');
    if (sectionForm) {
        sectionForm.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> {{ __("panel.updating") }}...';
            
            // Re-enable button after 5 seconds to prevent permanent lock
            setTimeout(function() {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fa-solid fa-save"></i> {{ __("panel.update_section") }}';
            }, 5000);
        });
    }
});
</script>
@endsection