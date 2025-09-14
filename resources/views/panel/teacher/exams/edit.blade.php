@extends('layouts.app')

@section('title', __('panel.edit_exam'))

@section('page_title', __('panel.edit_exam'))

@section('styles')
    <style>
        .form-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-section h5 {
            color: #495057;
            border-bottom: 2px solid #007bff;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
        }

        .required::after {
            content: " *";
            color: #dc3545;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="text-dark fw-bold">{{ __('panel.edit_exam') }}</h2>
                <p class="text-muted">{{ __('panel.edit_exam_desc') }}</p>
            </div>
            <a href="{{ route('teacher.exams.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>{{ __('panel.back_to_exams') }}
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('teacher.exams.update', $exam->id) }}" method="POST" id="examForm">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="form-section">
                <h5><i class="fas fa-info-circle me-2"></i>{{ __('panel.basic_information') }}</h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label required">{{ __('panel.exam_title_en') }}</label>
                        <input type="text" name="title_en" class="form-control @error('title_en') is-invalid @enderror"
                            value="{{ old('title_en', $exam->title_en) }}" placeholder="{{ __('panel.enter_exam_title_en') }}">
                        @error('title_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label required">{{ __('panel.exam_title_ar') }}</label>
                        <input type="text" name="title_ar" class="form-control @error('title_ar') is-invalid @enderror"
                            value="{{ old('title_ar', $exam->title_ar) }}" placeholder="{{ __('panel.enter_exam_title_ar') }}">
                        @error('title_ar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('panel.description_en') }}</label>
                        <textarea name="description_en" class="form-control @error('description_en') is-invalid @enderror" rows="3"
                            placeholder="{{ __('panel.enter_description_en') }}">{{ old('description_en', $exam->description_en) }}</textarea>
                        @error('description_en')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('panel.description_ar') }}</label>
                        <textarea name="description_ar" class="form-control @error('description_ar') is-invalid @enderror" rows="3"
                            placeholder="{{ __('panel.enter_description_ar') }}">{{ old('description_ar', $exam->description_ar) }}</textarea>
                        @error('description_ar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Course & Subject Information -->
            <div class="form-section">
                <h5><i class="fas fa-graduation-cap me-2"></i>{{ __('panel.course_subject_info') }}</h5>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label required">{{ __('panel.subject') }}</label>
                        <select name="subject_id" id="subject_id"
                            class="form-select @error('subject_id') is-invalid @enderror">
                            <option value="">{{ __('panel.select_subject') }}</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}"
                                    {{ old('subject_id', $exam->subject_id) == $subject->id ? 'selected' : '' }}>
                                    {{ app()->getLocale() === 'ar' ? $subject->name_ar : $subject->name_en }}
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">{{ __('panel.course') }}</label>
                        <select name="course_id" id="course_id"
                            class="form-select @error('course_id') is-invalid @enderror">
                            <option value="">{{ __('panel.select_course') }}</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}" data-subject="{{ $course->subject_id }}"
                                    {{ old('course_id', $exam->course_id) == $course->id ? 'selected' : '' }}>
                                    {{ app()->getLocale() === 'ar' ? $course->title_ar : $course->title_en }}
                                </option>
                            @endforeach
                        </select>
                        @error('course_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">{{ __('panel.section') }}</label>
                        <select name="section_id" id="section_id"
                            class="form-select @error('section_id') is-invalid @enderror">
                            <option value="">{{ __('panel.select_section') }}</option>
                            @if(isset($sections))
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}"
                                        {{ old('section_id', $exam->section_id) == $section->id ? 'selected' : '' }}>
                                        {{ $section->title }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('section_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Exam Settings -->
            <div class="form-section">
                <h5><i class="fas fa-cog me-2"></i>{{ __('panel.exam_settings') }}</h5>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">{{ __('panel.duration_minutes') }}</label>
                        <input type="number" name="duration_minutes"
                            class="form-control @error('duration_minutes') is-invalid @enderror"
                            value="{{ old('duration_minutes', $exam->duration_minutes) }}" min="1"
                            placeholder="{{ __('panel.unlimited') }}">
                        <small class="text-muted">{{ __('panel.leave_empty_unlimited') }}</small>
                        @error('duration_minutes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label required">{{ __('panel.attempts_allowed') }}</label>
                        <input type="number" name="attempts_allowed"
                            class="form-control @error('attempts_allowed') is-invalid @enderror"
                            value="{{ old('attempts_allowed', $exam->attempts_allowed) }}" min="1" max="10">
                        @error('attempts_allowed')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label required">{{ __('panel.passing_grade') }}</label>
                        <div class="input-group">
                            <input type="number" name="passing_grade"
                                class="form-control @error('passing_grade') is-invalid @enderror"
                                value="{{ old('passing_grade', $exam->passing_grade) }}" min="0" max="100" step="0.01">
                            <span class="input-group-text">%</span>
                        </div>
                        @error('passing_grade')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">{{ __('panel.start_date') }}</label>
                        <input type="datetime-local" name="start_date"
                            class="form-control @error('start_date') is-invalid @enderror"
                            value="{{ old('start_date', $exam->start_date ? \Carbon\Carbon::parse($exam->start_date)->format('Y-m-d\TH:i') : '') }}">
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">{{ __('panel.end_date') }}</label>
                        <input type="datetime-local" name="end_date"
                            class="form-control @error('end_date') is-invalid @enderror" 
                            value="{{ old('end_date', $exam->end_date ? \Carbon\Carbon::parse($exam->end_date)->format('Y-m-d\TH:i') : '') }}">
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Display Options -->
            <div class="form-section">
                <h5><i class="fas fa-eye me-2"></i>{{ __('panel.display_options') }}</h5>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   name="shuffle_questions"
                                   id="shuffle_questions" 
                                   value="1"
                                   {{ old('shuffle_questions', $exam->shuffle_questions) ? 'checked' : '' }}>
                            <label class="form-check-label" for="shuffle_questions">
                                <strong>{{ __('panel.shuffle_questions') }}</strong><br>
                                <small class="text-muted">{{ __('panel.shuffle_questions_desc') }}</small>
                            </label>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   name="shuffle_options" 
                                   id="shuffle_options"
                                   value="1"
                                   {{ old('shuffle_options', $exam->shuffle_options) ? 'checked' : '' }}>
                            <label class="form-check-label" for="shuffle_options">
                                <strong>{{ __('panel.shuffle_options') }}</strong><br>
                                <small class="text-muted">{{ __('panel.shuffle_options_desc') }}</small>
                            </label>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   name="show_results_immediately"
                                   id="show_results_immediately" 
                                   value="1"
                                   {{ old('show_results_immediately', $exam->show_results_immediately) ? 'checked' : '' }}>
                            <label class="form-check-label" for="show_results_immediately">
                                <strong>{{ __('panel.show_results_immediately') }}</strong><br>
                                <small class="text-muted">{{ __('panel.show_results_immediately_desc') }}</small>
                            </label>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   name="is_active" 
                                   id="is_active"
                                   value="1"
                                   {{ old('is_active', $exam->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                <strong>{{ __('panel.active') }}</strong><br>
                                <small class="text-muted">{{ __('panel.exam_active_desc') }}</small>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="d-flex justify-content-end gap-2 mb-4">
                <a href="{{ route('teacher.exams.index') }}" class="btn btn-outline-secondary">
                    {{ __('panel.cancel') }}
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>{{ __('panel.update_exam') }}
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Check if jQuery is loaded, if not, use vanilla JavaScript
    if (typeof $ !== 'undefined') {
        initializeWithJQuery();
    } else {
        initializeWithVanillaJS();
    }
});

function initializeWithJQuery() {
    $(document).ready(function() {
        // Initialize the form with current values
        initializeFormState();

        // Handle subject change to filter courses
        $('#subject_id').on('change', function() {
            const subjectId = $(this).val();
            const courseSelect = $('#course_id');
            const sectionSelect = $('#section_id');

            // Reset course and section dropdowns
            courseSelect.val('').find('option:not(:first)').hide();
            sectionSelect.val('').html('<option value="">{{ __('panel.select_section') }}</option>');

            if (subjectId) {
                // Show courses that belong to selected subject
                courseSelect.find(`option[data-subject="${subjectId}"]`).show();

                // Also make AJAX call to get courses (if routes exist)
                const coursesUrl = '{{ route('teacher.exams.subjects.courses', '') }}/' + subjectId;
                $.get(coursesUrl)
                    .done(function(courses) {
                        courseSelect.html('<option value="">{{ __('panel.select_course') }}</option>');
                        courses.forEach(course => {
                            const title = '{{ app()->getLocale() }}' === 'ar' ? course.title_ar : course.title_en;
                            const selected = course.id == '{{ old('course_id', $exam->course_id ?? '') }}' ? 'selected' : '';
                            courseSelect.append(`<option value="${course.id}" ${selected}>${title}</option>`);
                        });
                    })
                    .fail(function() {
                        console.warn('AJAX course loading failed, using static options');
                    });
            } else {
                courseSelect.find('option').show();
            }
        });

        // Handle course change to load sections
        $('#course_id').on('change', function() {
            const courseId = $(this).val();
            const sectionSelect = $('#section_id');

            sectionSelect.html('<option value="">{{ __('panel.select_section') }}</option>');

            if (courseId) {
                const sectionsUrl = '{{ route('teacher.exams.courses.sections', '') }}/' + courseId;
                $.get(sectionsUrl)
                    .done(function(sections) {
                        sections.forEach(section => {
                            const selected = section.id == '{{ old('section_id', $exam->section_id ?? '') }}' ? 'selected' : '';
                            sectionSelect.append(`<option value="${section.id}" ${selected}>${section.title}</option>`);
                        });
                    })
                    .fail(function() {
                        console.warn('AJAX section loading failed');
                    });
            }
        });

        // Form validation
        $('#examForm').on('submit', function(e) {
            let isValid = true;

            // Check required fields
            const requiredFields = ['title_en', 'title_ar', 'subject_id', 'attempts_allowed', 'passing_grade'];

            requiredFields.forEach(field => {
                const input = $(`[name="${field}"]`);
                if (input.length && !input.val().trim()) {
                    input.addClass('is-invalid');
                    isValid = false;
                } else if (input.length) {
                    input.removeClass('is-invalid');
                }
            });

            // Validate date range
            const startDateInput = $('[name="start_date"]');
            const endDateInput = $('[name="end_date"]');

            if (startDateInput.length && endDateInput.length &&
                startDateInput.val() && endDateInput.val()) {
                const startDate = new Date(startDateInput.val());
                const endDate = new Date(endDateInput.val());

                if (endDate <= startDate) {
                    endDateInput.addClass('is-invalid');
                    isValid = false;

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: '{{ __('panel.validation_error') }}',
                            text: '{{ __('panel.end_date_must_be_after_start_date') }}'
                        });
                    } else {
                        alert('{{ __('panel.end_date_must_be_after_start_date') }}');
                    }
                }
            }

            // Validate passing grade
            const passingGrade = parseFloat($('[name="passing_grade"]').val());
            if (passingGrade < 0 || passingGrade > 100) {
                $('[name="passing_grade"]').addClass('is-invalid');
                isValid = false;
                alert('{{ __('panel.passing_grade_must_be_between_0_100') }}');
            }

            if (!isValid) {
                e.preventDefault();
            }
        });

        // Auto-adjust textarea height
        $('textarea').on('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        // Trigger subject change to initialize course filtering
        $('#subject_id').trigger('change');
    });

    function initializeFormState() {
        // Filter courses based on the currently selected subject
        const selectedSubjectId = $('#subject_id').val();
        if (selectedSubjectId) {
            const courseSelect = $('#course_id');
            courseSelect.find('option:not(:first)').hide();
            courseSelect.find(`option[data-subject="${selectedSubjectId}"]`).show();
        }
    }
}

function initializeWithVanillaJS() {
    // Initialize the form with current values
    initializeFormStateVanilla();

    // Subject change handler
    const subjectSelect = document.getElementById('subject_id');
    if (subjectSelect) {
        subjectSelect.addEventListener('change', function() {
            const subjectId = this.value;
            const courseSelect = document.getElementById('course_id');
            const sectionSelect = document.getElementById('section_id');

            if (courseSelect && sectionSelect) {
                // Reset dropdowns
                courseSelect.value = '';
                sectionSelect.innerHTML = '<option value="">{{ __('panel.select_section') }}</option>';

                // Hide/show course options based on subject
                const courseOptions = courseSelect.querySelectorAll('option[data-subject]');
                courseOptions.forEach(option => {
                    if (subjectId && option.getAttribute('data-subject') === subjectId) {
                        option.style.display = 'block';
                    } else if (subjectId) {
                        option.style.display = 'none';
                    } else {
                        option.style.display = 'block';
                    }
                });
            }
        });

        // Trigger change to initialize
        subjectSelect.dispatchEvent(new Event('change'));
    }

    // Course change handler
    const courseSelect = document.getElementById('course_id');
    if (courseSelect) {
        courseSelect.addEventListener('change', function() {
            const courseId = this.value;
            const sectionSelect = document.getElementById('section_id');

            if (sectionSelect && courseId) {
                // Make fetch request for sections
                fetch(`/panel/teacher/exams/courses/${courseId}/sections`)
                    .then(response => response.json())
                    .then(sections => {
                        sectionSelect.innerHTML = '<option value="">{{ __('panel.select_section') }}</option>';
                        sections.forEach(section => {
                            const option = document.createElement('option');
                            option.value = section.id;
                            option.textContent = section.title;
                            // Check if this section should be selected
                            if (section.id == '{{ old('section_id', $exam->section_id ?? '') }}') {
                                option.selected = true;
                            }
                            sectionSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.warn('Failed to load sections:', error);
                    });
            }
        });
    }

    // Form validation
    const examForm = document.getElementById('examForm');
    if (examForm) {
        examForm.addEventListener('submit', function(e) {
            let isValid = true;

            // Check required fields
            const requiredFields = ['title_en', 'title_ar', 'subject_id', 'attempts_allowed', 'passing_grade'];

            requiredFields.forEach(fieldName => {
                const input = document.querySelector(`[name="${fieldName}"]`);
                if (input && !input.value.trim()) {
                    input.classList.add('is-invalid');
                    isValid = false;
                } else if (input) {
                    input.classList.remove('is-invalid');
                }
            });

            // Validate date range
            const startDateInput = document.querySelector('[name="start_date"]');
            const endDateInput = document.querySelector('[name="end_date"]');

            if (startDateInput && endDateInput &&
                startDateInput.value && endDateInput.value) {
                const startDate = new Date(startDateInput.value);
                const endDate = new Date(endDateInput.value);

                if (endDate <= startDate) {
                    endDateInput.classList.add('is-invalid');
                    isValid = false;
                    alert('{{ __('panel.end_date_must_be_after_start_date') }}');
                }
            }

            // Validate passing grade
            const passingGradeInput = document.querySelector('[name="passing_grade"]');
            if (passingGradeInput) {
                const passingGrade = parseFloat(passingGradeInput.value);
                if (passingGrade < 0 || passingGrade > 100) {
                    passingGradeInput.classList.add('is-invalid');
                    isValid = false;
                    alert('Passing grade must be between 0 and 100');
                }
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    }

    // Auto-adjust textarea height
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    });

    function initializeFormStateVanilla() {
        // Filter courses based on the currently selected subject
        const subjectSelect = document.getElementById('subject_id');
        const courseSelect = document.getElementById('course_id');
        
        if (subjectSelect && courseSelect) {
            const selectedSubjectId = subjectSelect.value;
            if (selectedSubjectId) {
                const courseOptions = courseSelect.querySelectorAll('option[data-subject]');
                courseOptions.forEach(option => {
                    if (option.getAttribute('data-subject') !== selectedSubjectId) {
                        option.style.display = 'none';
                    }
                });
            }
        }
    }
}
</script>
@endpush