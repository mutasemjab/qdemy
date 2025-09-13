@extends('layouts.app')
@section('title', __('panel.create_section'))

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
                <h1>{{ __('panel.create_section') }}</h1>
                <p class="course-name">{{ $course->title_ar }}</p>
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

            <form method="POST" action="{{ route('teacher.courses.sections.store', $course) }}" class="section-form">
                @csrf
                
                <div class="form-section">
                    <h3>{{ __('panel.section_information') }}</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="title_ar">{{ __('panel.section_title_ar') }} *</label>
                            <input type="text" id="title_ar" name="title_ar" value="{{ old('title_ar') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="title_en">{{ __('panel.section_title_en') }} *</label>
                            <input type="text" id="title_en" name="title_en" value="{{ old('title_en') }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="parent_id">{{ __('panel.parent_section') }}</label>
                        <select id="parent_id" name="parent_id">
                            <option value="">{{ __('panel.no_parent_section') }}</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}" {{ old('parent_id') == $section->id ? 'selected' : '' }}>
                                    {{ $section->title_ar }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text">{{ __('panel.parent_section_help') }}</small>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('teacher.courses.sections.index', $course) }}" class="btn btn-secondary">
                        {{ __('panel.cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-save"></i>
                        {{ __('panel.create_section') }}
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
    margin: 0;
    font-size: 1.1em;
}

.section-form {
    max-width: 600px;
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
    
    .form-actions {
        flex-direction: column;
    }
}
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Form submission with loading state
    $('.section-form').on('submit', function(e) {
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> {{ __("panel.creating") }}...');
        
        // Re-enable button after 5 seconds to prevent permanent lock
        setTimeout(function() {
            submitBtn.prop('disabled', false).html('<i class="fa-solid fa-save"></i> {{ __("panel.create_section") }}');
        }, 5000);
    });
});
</script>
@endsection