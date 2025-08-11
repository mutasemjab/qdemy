@extends('layouts.admin')

@section('title', __('messages.add_exam'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.add_exam') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('exams.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                        </a>
                    </div>
                </div>

                <form action="{{ route('exams.store') }}" method="POST" id="examForm">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <!-- Exam Title English -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="title_en" class="form-label">
                                        {{ __('messages.title_en') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('title_en') is-invalid @enderror" 
                                           id="title_en" 
                                           name="title_en" 
                                           value="{{ old('title_en') }}" 
                                           placeholder="{{ __('messages.enter_exam_title_en') }}">
                                    @error('title_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Exam Title Arabic -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="title_ar" class="form-label">
                                        {{ __('messages.title_ar') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('title_ar') is-invalid @enderror" 
                                           id="title_ar" 
                                           name="title_ar" 
                                           value="{{ old('title_ar') }}" 
                                           placeholder="{{ __('messages.enter_exam_title_ar') }}"
                                           dir="rtl">
                                    @error('title_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Description English -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="description_en" class="form-label">
                                        {{ __('messages.description_en') }}
                                    </label>
                                    <textarea class="form-control @error('description_en') is-invalid @enderror" 
                                              id="description_en" 
                                              name="description_en" 
                                              rows="4" 
                                              placeholder="{{ __('messages.enter_description_en') }}">{{ old('description_en') }}</textarea>
                                    @error('description_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Description Arabic -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="description_ar" class="form-label">
                                        {{ __('messages.description_ar') }}
                                    </label>
                                    <textarea class="form-control @error('description_ar') is-invalid @enderror" 
                                              id="description_ar" 
                                              name="description_ar" 
                                              rows="4" 
                                              placeholder="{{ __('messages.enter_description_ar') }}"
                                              dir="rtl">{{ old('description_ar') }}</textarea>
                                    @error('description_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Course -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="course_id" class="form-label">
                                        {{ __('messages.course') }} <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control @error('course_id') is-invalid @enderror" 
                                            id="course_id" 
                                            name="course_id">
                                        <option value="">{{ __('messages.select_course') }}</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id }}" 
                                                    {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                                {{ $course->title_en }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('course_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Duration -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="duration_minutes" class="form-label">
                                        {{ __('messages.duration_minutes') }}
                                    </label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control @error('duration_minutes') is-invalid @enderror" 
                                               id="duration_minutes" 
                                               name="duration_minutes" 
                                               value="{{ old('duration_minutes') }}" 
                                               min="1"
                                               placeholder="{{ __('messages.leave_blank_for_unlimited') }}">
                                        <span class="input-group-text">{{ __('messages.minutes') }}</span>
                                    </div>
                                    <small class="form-text text-muted">{{ __('messages.leave_blank_for_unlimited_time') }}</small>
                                    @error('duration_minutes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Attempts Allowed -->
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="attempts_allowed" class="form-label">
                                        {{ __('messages.attempts_allowed') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('attempts_allowed') is-invalid @enderror" 
                                           id="attempts_allowed" 
                                           name="attempts_allowed" 
                                           value="{{ old('attempts_allowed', 1) }}" 
                                           min="1" 
                                           max="10">
                                    @error('attempts_allowed')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Passing Grade -->
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="passing_grade" class="form-label">
                                        {{ __('messages.passing_grade') }} <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control @error('passing_grade') is-invalid @enderror" 
                                               id="passing_grade" 
                                               name="passing_grade" 
                                               value="{{ old('passing_grade', 50) }}" 
                                               min="0" 
                                               max="100" 
                                               step="0.01">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    @error('passing_grade')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label">{{ __('messages.status') }}</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="is_active" 
                                               name="is_active" 
                                               value="1"
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            {{ __('messages.active') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Start Date -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="start_date" class="form-label">
                                        {{ __('messages.start_date') }}
                                    </label>
                                    <input type="datetime-local" 
                                           class="form-control @error('start_date') is-invalid @enderror" 
                                           id="start_date" 
                                           name="start_date" 
                                           value="{{ old('start_date') }}">
                                    <small class="form-text text-muted">{{ __('messages.leave_blank_for_no_restriction') }}</small>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- End Date -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="end_date" class="form-label">
                                        {{ __('messages.end_date') }}
                                    </label>
                                    <input type="datetime-local" 
                                           class="form-control @error('end_date') is-invalid @enderror" 
                                           id="end_date" 
                                           name="end_date" 
                                           value="{{ old('end_date') }}">
                                    <small class="form-text text-muted">{{ __('messages.leave_blank_for_no_restriction') }}</small>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Exam Settings -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">{{ __('messages.exam_settings') }}</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   id="shuffle_questions" 
                                                   name="shuffle_questions" 
                                                   value="1"
                                                   {{ old('shuffle_questions') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="shuffle_questions">
                                                <strong>{{ __('messages.shuffle_questions') }}</strong><br>
                                                <small class="text-muted">{{ __('messages.randomize_question_order') }}</small>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   id="shuffle_options" 
                                                   name="shuffle_options" 
                                                   value="1"
                                                   {{ old('shuffle_options') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="shuffle_options">
                                                <strong>{{ __('messages.shuffle_options') }}</strong><br>
                                                <small class="text-muted">{{ __('messages.randomize_answer_options') }}</small>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   id="show_results_immediately" 
                                                   name="show_results_immediately" 
                                                   value="1"
                                                   {{ old('show_results_immediately', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="show_results_immediately">
                                                <strong>{{ __('messages.show_results_immediately') }}</strong><br>
                                                <small class="text-muted">{{ __('messages.show_results_after_submission') }}</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Information Note -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>{{ __('messages.note') }}:</strong> {{ __('messages.exam_creation_note') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('exams.index') }}" class="btn btn-secondary">
                                {{ __('messages.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ __('messages.create_exam') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Form validation
document.getElementById('examForm').addEventListener('submit', function(e) {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    
    // Validate date range
    if (startDate && endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        
        if (start >= end) {
            e.preventDefault();
            alert('{{ __("messages.end_date_must_be_after_start_date") }}');
            return;
        }
    }
    
    // Validate passing grade
    const passingGrade = parseFloat(document.getElementById('passing_grade').value);
    if (passingGrade < 0 || passingGrade > 100) {
        e.preventDefault();
        alert('{{ __("messages.passing_grade_must_be_between_0_and_100") }}');
        return;
    }
    
    // Validate attempts
    const attempts = parseInt(document.getElementById('attempts_allowed').value);
    if (attempts < 1 || attempts > 10) {
        e.preventDefault();
        alert('{{ __("messages.attempts_must_be_between_1_and_10") }}');
        return;
    }
});

// Set minimum date for start_date to today
document.addEventListener('DOMContentLoaded', function() {
    const now = new Date();
    const isoString = now.toISOString().slice(0, 16);
    document.getElementById('start_date').setAttribute('min', isoString);
    
    // Update end_date minimum when start_date changes
    document.getElementById('start_date').addEventListener('change', function() {
        const startDate = this.value;
        if (startDate) {
            document.getElementById('end_date').setAttribute('min', startDate);
        }
    });
});

// Auto-fill Arabic title when English title is entered (optional helper)
document.getElementById('title_en').addEventListener('blur', function() {
    const titleAr = document.getElementById('title_ar');
    if (!titleAr.value && this.value) {
        // You can add auto-translation logic here if needed
        // For now, just focus on the Arabic field
        titleAr.focus();
    }
});
</script>
@endsection