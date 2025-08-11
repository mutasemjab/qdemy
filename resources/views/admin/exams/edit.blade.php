@extends('layouts.admin')

@section('title', __('messages.edit_exam'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.edit_exam') }}</h3>
                    <div class="card-tools">
                        <div class="btn-group">
                            <a href="{{ route('exams.show', $exam) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> {{ __('messages.view_exam') }}
                            </a>
                            <a href="{{ route('exams.questions.manage', $exam) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-question"></i> {{ __('messages.manage_questions') }}
                            </a>
                            <a href="{{ route('exams.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                            </a>
                        </div>
                    </div>
                </div>

                <form action="{{ route('exams.update', $exam) }}" method="POST" id="examForm">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <!-- Exam Info Header -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-light border">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ __('messages.exam_information') }}</h6>
                                            <small class="text-muted">
                                                {{ __('messages.created_at') }}: {{ $exam->created_at->format('Y-m-d H:i') }} | 
                                                {{ __('messages.total_questions') }}: {{ $exam->questions->count() }} |
                                                {{ __('messages.total_grade') }}: {{ number_format($exam->total_grade, 2) }}
                                            </small>
                                        </div>
                                        <div>
                                            <span class="badge badge-{{ $exam->is_active ? 'success' : 'secondary' }}">
                                                {{ $exam->is_active ? __('messages.active') : __('messages.inactive') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

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
                                           value="{{ old('title_en', $exam->title_en) }}" 
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
                                           value="{{ old('title_ar', $exam->title_ar) }}" 
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
                                              placeholder="{{ __('messages.enter_description_en') }}">{{ old('description_en', $exam->description_en) }}</textarea>
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
                                              dir="rtl">{{ old('description_ar', $exam->description_ar) }}</textarea>
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
                                                    {{ old('course_id', $exam->course_id) == $course->id ? 'selected' : '' }}>
                                                {{ $course->title_en }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('course_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($exam->questions->count() > 0)
                                        <small class="form-text text-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            {{ __('messages.changing_course_warning') }}
                                        </small>
                                    @endif
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
                                               value="{{ old('duration_minutes', $exam->duration_minutes) }}" 
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
                                           value="{{ old('attempts_allowed', $exam->attempts_allowed) }}" 
                                           min="1" 
                                           max="10">
                                    @error('attempts_allowed')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($exam->attempts->count() > 0)
                                        <small class="form-text text-info">
                                            <i class="fas fa-info-circle"></i>
                                            {{ __('messages.current_attempts_count', ['count' => $exam->attempts->count()]) }}
                                        </small>
                                    @endif
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
                                               value="{{ old('passing_grade', $exam->passing_grade) }}" 
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
                                               {{ old('is_active', $exam->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            {{ __('messages.active') }}
                                        </label>
                                    </div>
                                    @if($exam->attempts->where('status', 'in_progress')->count() > 0)
                                        <small class="form-text text-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            {{ __('messages.active_attempts_warning') }}
                                        </small>
                                    @endif
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
                                           value="{{ old('start_date', $exam->start_date ? $exam->start_date->format('Y-m-d\TH:i') : '') }}">
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
                                           value="{{ old('end_date', $exam->end_date ? $exam->end_date->format('Y-m-d\TH:i') : '') }}">
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
                                                   {{ old('shuffle_questions', $exam->shuffle_questions) ? 'checked' : '' }}>
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
                                                   {{ old('shuffle_options', $exam->shuffle_options) ? 'checked' : '' }}>
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
                                                   {{ old('show_results_immediately', $exam->show_results_immediately) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="show_results_immediately">
                                                <strong>{{ __('messages.show_results_immediately') }}</strong><br>
                                                <small class="text-muted">{{ __('messages.show_results_after_submission') }}</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Warning Messages -->
                        @if($exam->attempts->count() > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <strong>{{ __('messages.warning') }}:</strong> 
                                    {{ __('messages.exam_has_attempts_warning', ['count' => $exam->attempts->count()]) }}
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Information Note -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>{{ __('messages.note') }}:</strong> {{ __('messages.exam_edit_note') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <div>
                                <a href="{{ route('exams.index') }}" class="btn btn-secondary">
                                    {{ __('messages.cancel') }}
                                </a>
                                <a href="{{ route('exams.show', $exam) }}" class="btn btn-outline-info ml-2">
                                    {{ __('messages.view_exam') }}
                                </a>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> {{ __('messages.update_exam') }}
                                </button>
                                <a href="{{ route('exams.questions.manage', $exam) }}" class="btn btn-warning ml-2">
                                    <i class="fas fa-question"></i> {{ __('messages.manage_questions') }}
                                </a>
                            </div>
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

    // Warning for course change
    const originalCourseId = '{{ $exam->course_id }}';
    const newCourseId = document.getElementById('course_id').value;
    const questionsCount = {{ $exam->questions->count() }};
    
    if (originalCourseId !== newCourseId && questionsCount > 0) {
        if (!confirm('{{ __("messages.confirm_course_change_warning") }}')) {
            e.preventDefault();
            return;
        }
    }

    // Warning for status change if there are active attempts
    const hasActiveAttempts = {{ $exam->attempts->where('status', 'in_progress')->count() }};
    const isActive = document.getElementById('is_active').checked;
    
    if (hasActiveAttempts > 0 && !isActive) {
        if (!confirm('{{ __("messages.confirm_deactivate_with_active_attempts") }}')) {
            e.preventDefault();
            return;
        }
    }
});

// Update end_date minimum when start_date changes
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('start_date').addEventListener('change', function() {
        const startDate = this.value;
        if (startDate) {
            document.getElementById('end_date').setAttribute('min', startDate);
        }
    });

    // Set initial min for end_date if start_date has value
    const startDateValue = document.getElementById('start_date').value;
    if (startDateValue) {
        document.getElementById('end_date').setAttribute('min', startDateValue);
    }
});

// Course change warning
document.getElementById('course_id').addEventListener('change', function() {
    const originalCourseId = '{{ $exam->course_id }}';
    const questionsCount = {{ $exam->questions->count() }};
    
    if (this.value !== originalCourseId && questionsCount > 0) {
        const warningDiv = document.createElement('div');
        warningDiv.className = 'alert alert-danger mt-2';
        warningDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> {{ __("messages.course_change_will_remove_questions") }}';
        
        // Remove existing warning
        const existingWarning = this.parentNode.querySelector('.alert-danger');
        if (existingWarning) {
            existingWarning.remove();
        }
        
        // Add new warning
        this.parentNode.appendChild(warningDiv);
    } else {
        // Remove warning if course is reverted
        const existingWarning = this.parentNode.querySelector('.alert-danger');
        if (existingWarning) {
            existingWarning.remove();
        }
    }
});
</script>
@endsection