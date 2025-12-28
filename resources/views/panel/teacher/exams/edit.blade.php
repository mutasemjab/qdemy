@extends('layouts.app')

@section('title', __('panel.edit_exam'))

@section('content')
<section class="ud-wrap">
    <aside class="ud-menu">
        <div class="ud-user">
            <img data-src="{{ auth()->user()->photo ? asset('assets/admin/uploads/' . auth()->user()->photo) : asset('assets_front/images/avatar-big.png') }}" alt="">
            <div>
                <h3>{{ auth()->user()->name }}</h3>
                <span>{{ auth()->user()->email }}</span>
            </div>
        </div>
        <a href="{{ route('teacher.exams.index') }}" class="ud-item">
            <i class="fa-solid fa-arrow-left"></i>
            <span>{{ __('panel.back_to_exams') }}</span>
        </a>
    </aside>

    <div class="ud-content">
        <div class="ud-panel show">
            <div class="ud-title">{{ __('panel.edit_exam') }}</div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="ud-errors">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('teacher.exams.update', $exam->id) }}" class="exam-form" id="examForm">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="form-section">
                    <h5><i class="fas fa-info-circle me-2"></i>{{ __('panel.basic_information') }}</h5>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="title_en">{{ __('panel.exam_title_en') }} *</label>
                            <input type="text" id="title_en" name="title_en" value="{{ old('title_en', $exam->title_en) }}" placeholder="{{ __('panel.enter_exam_title_en') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="title_ar">{{ __('panel.exam_title_ar') }} *</label>
                            <input type="text" id="title_ar" name="title_ar" value="{{ old('title_ar', $exam->title_ar) }}" placeholder="{{ __('panel.enter_exam_title_ar') }}" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="description_en">{{ __('panel.description_en') }}</label>
                            <textarea id="description_en" name="description_en" rows="3" placeholder="{{ __('panel.enter_description_en') }}">{{ old('description_en', $exam->description_en) }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="description_ar">{{ __('panel.description_ar') }}</label>
                            <textarea id="description_ar" name="description_ar" rows="3" placeholder="{{ __('panel.enter_description_ar') }}">{{ old('description_ar', $exam->description_ar) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Course & Subject Information -->
                <div class="form-section">
                    <h5><i class="fas fa-graduation-cap me-2"></i>{{ __('panel.course_subject_info') }}</h5>

                    <div class="form-row form-row.three-cols">
                        <div class="form-group">
                            <label for="subject_id">{{ __('panel.subject') }} *</label>
                            <select id="subject_id" name="subject_id" required>
                                <option value="">{{ __('panel.select_subject') }}</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id', $exam->subject_id) == $subject->id ? 'selected' : '' }}>
                                        {{ app()->getLocale() === 'ar' ? $subject->name_ar : $subject->name_en }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="course_id">{{ __('panel.course') }}</label>
                            <select id="course_id" name="course_id">
                                <option value="">{{ __('panel.select_course') }}</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" data-subject="{{ $course->subject_id }}" {{ old('course_id', $exam->course_id) == $course->id ? 'selected' : '' }}>
                                        {{ app()->getLocale() === 'ar' ? $course->title_ar : $course->title_en }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="section_id">{{ __('panel.section') }}</label>
                            <select id="section_id" name="section_id">
                                <option value="">{{ __('panel.select_section') }}</option>
                                @if(isset($sections))
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}" {{ old('section_id', $exam->section_id) == $section->id ? 'selected' : '' }}>
                                            {{ $section->title }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="course_content_id">{{ __('messages.lesson') }}</label>
                            <select id="course_content_id" name="course_content_id" {{ !$exam->section_id ? 'disabled' : '' }}>
                                <option value="">{{ __('messages.select_lesson_optional') }}</option>
                            </select>
                            <small class="form-text">{{ __('messages.select_section_first') }}</small>
                        </div>
                    </div>
                </div>

                <!-- Exam Settings -->
                <div class="form-section">
                    <h5><i class="fas fa-cog me-2"></i>{{ __('panel.exam_settings') }}</h5>

                    <div class="form-row form-row.three-cols">
                        <div class="form-group">
                            <label for="duration_minutes">{{ __('panel.duration_minutes') }}</label>
                            <input type="number" id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', $exam->duration_minutes) }}" min="1" placeholder="{{ __('panel.unlimited') }}">
                            <small class="form-text">{{ __('panel.leave_empty_unlimited') }}</small>
                        </div>

                        <div class="form-group">
                            <label for="attempts_allowed">{{ __('panel.attempts_allowed') }} *</label>
                            <input type="number" id="attempts_allowed" name="attempts_allowed" value="{{ old('attempts_allowed', $exam->attempts_allowed) }}" min="1" max="10" required>
                        </div>

                        <div class="form-group">
                            <label for="passing_grade">{{ __('panel.passing_grade') }} *</label>
                            <div class="input-wrapper">
                                <input type="number" id="passing_grade" name="passing_grade" value="{{ old('passing_grade', $exam->passing_grade) }}" min="0" max="100" step="0.01" required>
                                <span class="suffix">%</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="start_date">{{ __('panel.start_date') }}</label>
                            <input type="datetime-local" id="start_date" name="start_date" value="{{ old('start_date', $exam->start_date ? \Carbon\Carbon::parse($exam->start_date)->format('Y-m-d\TH:i') : '') }}">
                        </div>

                        <div class="form-group">
                            <label for="end_date">{{ __('panel.end_date') }}</label>
                            <input type="datetime-local" id="end_date" name="end_date" value="{{ old('end_date', $exam->end_date ? \Carbon\Carbon::parse($exam->end_date)->format('Y-m-d\TH:i') : '') }}">
                        </div>
                    </div>
                </div>

                <!-- Display Options -->
                <div class="form-section">
                    <h5><i class="fas fa-eye me-2"></i>{{ __('panel.display_options') }}</h5>

                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="shuffle_questions" value="1" {{ old('shuffle_questions', $exam->shuffle_questions) ? 'checked' : '' }}>
                            <span>{{ __('panel.shuffle_questions') }}</span>
                        </label>
                        <small class="form-text">{{ __('panel.shuffle_questions_desc') }}</small>
                    </div>

                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="shuffle_options" value="1" {{ old('shuffle_options', $exam->shuffle_options) ? 'checked' : '' }}>
                            <span>{{ __('panel.shuffle_options') }}</span>
                        </label>
                        <small class="form-text">{{ __('panel.shuffle_options_desc') }}</small>
                    </div>

                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="show_results_immediately" value="1" {{ old('show_results_immediately', $exam->show_results_immediately) ? 'checked' : '' }}>
                            <span>{{ __('panel.show_results_immediately') }}</span>
                        </label>
                        <small class="form-text">{{ __('panel.show_results_immediately_desc') }}</small>
                    </div>

                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $exam->is_active) ? 'checked' : '' }}>
                            <span>{{ __('panel.active') }}</span>
                        </label>
                        <small class="form-text">{{ __('panel.exam_active_desc') }}</small>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('teacher.exams.index') }}" class="btn btn-secondary">{{ __('panel.cancel') }}</a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fa-solid fa-save"></i>
                        {{ __('panel.update_exam') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
.ud-wrap{display:grid;grid-template-columns:320px 1fr;gap:24px;padding:16px 0}
.ud-menu{margin:10px;background:#fff;border:1px solid #eef0f3;border-radius:14px;padding:16px;position:sticky;top:88px;height:max-content}
.ud-user{display:flex;align-items:center;gap:12px;margin-bottom:12px}
.ud-user img{width:56px;height:56px;border-radius:50%;object-fit:cover;border:2px solid #f1f5f9}
.ud-user h3{font-size:16px;margin:0 0 2px 0}
.ud-user span{font-size:12px;color:#6b7280}
.ud-item{display:flex;align-items:center;gap:10px;padding:12px 14px;border:1px solid #e5e7eb;border-radius:10px;text-decoration:none;color:#0f172a;transition:all .18s}
.ud-item:hover{border-color:#0055D2;box-shadow:0 6px 18px rgba(0,85,210,.12);transform:translateY(-2px)}
.ud-content{min-width:0}
.ud-panel{background:#fff;border:1px solid #eef0f3;border-radius:14px;padding:18px}
.ud-title{font-size:20px;font-weight:900;margin-bottom:16px;color:#0f172a}

.exam-form{max-width:900px}
.form-section{margin-bottom:24px}
.section-title{font-size:14px;font-weight:800;color:#0f172a;margin-bottom:16px;padding-bottom:12px;border-bottom:2px solid #0055D2}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.form-row.three-cols{grid-template-columns:1fr 1fr 1fr}
.form-group{display:flex;flex-direction:column;gap:8px;margin-bottom:0}
.form-group label{font-weight:800;color:#0f172a;font-size:14px}
.form-group .form-text{color:#6b7280;font-size:12px}
.form-group input,.form-group select,.form-group textarea{border:1px solid #e5e7eb;border-radius:10px;padding:12px 14px;font-size:14px;background:#fff;transition:border-color .16s,box-shadow .16s;font-family:inherit}
.form-group textarea{min-height:80px;resize:vertical}
.form-group input:disabled,.form-group select:disabled{background:#f8fafc;color:#9aa3af}
.form-group input:focus,.form-group select:focus,.form-group textarea:focus{outline:none;border-color:#0055D2;box-shadow:0 0 0 3px rgba(0,85,210,.12)}

.input-wrapper{position:relative}
.input-wrapper .suffix{position:absolute;right:12px;top:50%;transform:translateY(-50%);color:#6b7280;font-weight:800;pointer-events:none}
.input-wrapper input{padding-right:40px}

.checkbox-group{margin-bottom:12px;padding:12px;background:#f9fafb;border-radius:8px}
.checkbox-label{display:flex;align-items:center;gap:8px;cursor:pointer;font-weight:800;color:#0f172a;margin:0}
.checkbox-label input[type="checkbox"]{margin:0;cursor:pointer;width:18px;height:18px}
.checkbox-label span{margin:0;font-size:14px}

.ud-errors{margin:0;padding-left:20px}

.form-actions{display:flex;gap:12px;justify-content:flex-end;margin-top:20px;padding-top:16px;border-top:1px solid #eef0f3}
.btn{display:inline-flex;align-items:center;gap:8px;border-radius:12px;padding:12px 16px;font-weight:900;font-size:14px;text-decoration:none;cursor:pointer;transition:transform .16s,box-shadow .16s,border-color .16s;border:1px solid transparent}
.btn:hover{transform:translateY(-1px)}
.btn-primary{background:#0055D2;color:#fff;border:1px solid #0048b3}
.btn-primary:hover{box-shadow:0 10px 22px rgba(0,85,210,.22)}
.btn-primary:disabled{opacity:.6;cursor:not-allowed}
.btn-secondary{background:#111827;color:#fff;border:1px solid #0b1220}
.btn-secondary:hover{box-shadow:0 10px 22px rgba(17,24,39,.22)}

.alert{padding:12px 14px;border-radius:12px;margin-bottom:16px}
.alert-danger{background:#fef2f2;color:#991b1b;border:1px solid #fee2e2}

@media (max-width:992px){
  .ud-wrap{grid-template-columns:1fr}
  .ud-menu{position:static}
}
@media (max-width:768px){
  .form-row{grid-template-columns:1fr}
  .form-row.three-cols{grid-template-columns:1fr}
  .form-actions{flex-direction:column}
  .exam-form{max-width:100%}
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof $ !== 'undefined') {
        initializeWithJQuery();
    } else {
        initializeWithVanillaJS();
    }
});

function initializeWithJQuery() {
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

    // Section change handler to load contents (lessons)
    const sectionSelect = document.getElementById('section_id');
    const contentSelect = document.getElementById('course_content_id');
    if (sectionSelect && contentSelect) {
        sectionSelect.addEventListener('change', function() {
            const sectionId = this.value;

            // Reset contents select
            contentSelect.innerHTML = '<option value="">{{ __('messages.select_lesson_optional') }}</option>';

            if (!sectionId) {
                contentSelect.disabled = true;
                return;
            }

            contentSelect.disabled = false;

            // Make fetch request for contents
            fetch(`/panel/teacher/exams/sections/${sectionId}/contents`)
                .then(response => response.json())
                .then(contents => {
                    contentSelect.innerHTML = '<option value="">{{ __('messages.select_lesson_optional') }}</option>';
                    contents.forEach(content => {
                        const title = content.title_en || content.title_ar;
                        const option = document.createElement('option');
                        option.value = content.id;
                        option.textContent = title;
                        // Check if this content should be selected
                        if (content.id == '{{ old('course_content_id', $exam->course_content_id ?? '') }}') {
                            option.selected = true;
                        }
                        contentSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.warn('Failed to load contents:', error);
                    contentSelect.innerHTML = '<option value="">{{ __('messages.select_lesson_optional') }}</option>';
                    contentSelect.disabled = true;
                });
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
@endsection