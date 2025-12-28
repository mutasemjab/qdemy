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
                        <a href="{{ route('exams.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                        </a>
                    </div>
                </div>

                <form action="{{ route('exams.update', $exam) }}" method="POST" id="examForm">
                    @csrf
                    @method('PUT')
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

                            <!-- Subject (ADDED REQUIRED FIELD) -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="subject_id" class="form-label">
                                        {{ __('messages.subject') }} <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select form-control @error('subject_id') is-invalid @enderror"
                                            id="subject_id"
                                            name="subject_id"
                                            onchange="loadSubjectCourses(this.value)">
                                        <option value="">{{ __('messages.select_subject') }}</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}"
                                                    {{ old('subject_id', $exam->subject_id) == $subject->id ? 'selected' : '' }}>
                                                {{ app()->getLocale() === 'ar' ? $subject->name_ar : $subject->name_en }}
                                                @if($subject->grade)
                                                    - {{ app()->getLocale() === 'ar' ? $subject->grade->name_ar : $subject->grade->name_en }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('subject_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Course -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="course_id" class="form-label">
                                        {{ __('messages.course') }}
                                    </label>
                                    <select class="form-select form-control @error('course_id') is-invalid @enderror"
                                            id="course_id"
                                            name="course_id"
                                            onchange="loadCourseSections(this.value)"
                                            {{ $exam->subject_id ? '' : 'disabled' }}>
                                        <option value="">{{ __('messages.select_course_optional') }}</option>
                                        @if($exam->subject_id && isset($courses))
                                            @foreach($courses as $course)
                                                <option value="{{ $course->id }}"
                                                        {{ old('course_id', $exam->course_id) == $course->id ? 'selected' : '' }}>
                                                    {{ $course->title_en }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <small class="form-text text-muted">{{ __('messages.select_subject_first') }}</small>
                                    @error('course_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Section -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="section_id" class="form-label">
                                        {{ __('messages.section') }}
                                    </label>
                                    <select class="form-select form-control @error('section_id') is-invalid @enderror"
                                            id="section_id"
                                            name="section_id"
                                            onchange="loadSectionContents(this.value)"
                                            {{ $exam->course_id ? '' : 'disabled' }}>
                                        <option value="">{{ __('messages.select_section_optional') }}</option>
                                        @if($exam->course_id && isset($sections))
                                            @foreach($sections as $section)
                                                <option value="{{ $section->id }}"
                                                        {{ old('section_id', $exam->section_id) == $section->id ? 'selected' : '' }}>
                                                    {{ app()->getLocale() === 'ar' ? $section->title_ar : $section->title_en }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <small class="form-text text-muted">{{ __('messages.select_course_first') }}</small>
                                    @error('section_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Lesson (Course Content) -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="course_content_id" class="form-label">
                                        {{ __('messages.lesson') }}
                                    </label>
                                    <select class="form-select form-control @error('course_content_id') is-invalid @enderror"
                                            id="course_content_id"
                                            name="course_content_id"
                                            disabled>
                                        <option value="">{{ __('messages.select_lesson_optional') }}</option>
                                        @if($exam->section_id && isset($contents))
                                            @foreach($contents as $content)
                                                <option value="{{ $content->id }}"
                                                        {{ old('course_content_id', $exam->course_content_id) == $content->id ? 'selected' : '' }}>
                                                    {{ app()->getLocale() === 'ar' ? $content->title_ar : $content->title_en }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <small class="form-text text-muted">{{ __('messages.select_section_first') }}</small>
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
                            <div class="col-md-6">
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
                                </div>
                            </div>

                            <!-- Passing Grade -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="passing_grade" class="form-label">
                                        {{ __('messages.passing_grade') }} (%) <span class="text-danger">*</span>
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
                                           value="{{ old('start_date', $exam->start_date ? \Carbon\Carbon::parse($exam->start_date)->format('Y-m-d\TH:i') : '') }}">
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
                                           value="{{ old('end_date', $exam->end_date ? \Carbon\Carbon::parse($exam->end_date)->format('Y-m-d\TH:i') : '') }}">
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
                                    <div class="col-md-3">
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
                                    <div class="col-md-3">
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
                                    <div class="col-md-3">
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
                                    <div class="col-md-3">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input"
                                                   type="checkbox"
                                                   id="is_active"
                                                   name="is_active"
                                                   value="1"
                                                   {{ old('is_active', $exam->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">
                                                <strong>{{ __('messages.is_active') }}</strong><br>
                                                <small class="text-muted">{{ __('messages.exam_available_for_students') }}</small>
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
                                    <strong>{{ __('messages.note') }}:</strong> {{ __('messages.exam_update_note') }}
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
                                <i class="fas fa-save"></i> {{ __('messages.update_exam') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Function to load courses when subject is selected
function loadSubjectCourses(subjectId) {
    const courseSelect = document.getElementById('course_id');
    const sectionSelect = document.getElementById('section_id');
    
    // Clear current options
    courseSelect.innerHTML = '<option value="">{{ __('messages.select_course_optional') }}</option>';
    sectionSelect.innerHTML = '<option value="">{{ __('messages.select_section_optional') }}</option>';
    sectionSelect.disabled = true;

    if (!subjectId) {
        courseSelect.disabled = true;
        return;
    }

    // Enable the course select
    courseSelect.disabled = false;

    // Fetch courses by subject via AJAX using named route
    fetch(`{{ route('admin.subjects.courses', ':subject') }}`.replace(':subject', subjectId), {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
        })
        .then(response => response.json())
        .then(courses => {
            courses.forEach(course => {
                const option = new Option(course.title_en || course.title_ar, course.id);
                courseSelect.add(option);
            });
            
            // Restore selected course if editing
            const selectedCourseId = '{{ old('course_id', $exam->course_id) }}';
            if (selectedCourseId) {
                courseSelect.value = selectedCourseId;
                loadCourseSections(selectedCourseId);
            }
        })
        .catch(error => {
            console.error('Error loading courses:', error);
            courseSelect.disabled = true;
        });
}

// Function to load sections when course is selected
function loadCourseSections(courseId, selectedSectionId = null) {
    const sectionSelect = document.getElementById('section_id');
    const contentSelect = document.getElementById('course_content_id');

    // Clear current options
    sectionSelect.innerHTML = '<option value="">{{ __('messages.select_section_optional') }}</option>';
    contentSelect.innerHTML = '<option value="">{{ __('messages.select_lesson_optional') }}</option>';
    contentSelect.disabled = true;

    if (!courseId) {
        sectionSelect.disabled = true;
        return;
    }

    // Enable the section select
    sectionSelect.disabled = false;

    // Fetch sections via AJAX using named route
    fetch(`{{ route('admin.courses.sections', ':course') }}`.replace(':course', courseId), {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
        })
        .then(response => response.json())
        .then(sections => {
            sections.forEach(section => {
                const option = new Option(section.title, section.id);
                if (selectedSectionId && section.id == selectedSectionId) {
                    option.selected = true;
                }
                sectionSelect.add(option);
            });

            // Load contents for selected section if editing
            if (selectedSectionId) {
                loadSectionContents(selectedSectionId);
            }
        })
        .catch(error => {
            console.error('Error loading sections:', error);
            sectionSelect.disabled = true;
        });
}

// Function to load contents (lessons) when section is selected
function loadSectionContents(sectionId) {
    const contentSelect = document.getElementById('course_content_id');
    contentSelect.innerHTML = '<option value="">{{ __('messages.select_lesson_optional') }}</option>';
    if (!sectionId) {
        contentSelect.disabled = true;
        return;
    }
    contentSelect.disabled = false;
    fetch(`{{ route('admin.sections.contents', ':section') }}`.replace(':section', sectionId), {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
    })
    .then(response => response.json())
    .then(contents => {
        contents.forEach(content => {
            const title = content.title_en || content.title_ar;
            const option = new Option(title, content.id);
            contentSelect.add(option);
        });

        // Restore selected content if editing
        const selectedContentId = '{{ old('course_content_id', $exam->course_content_id) }}';
        if (selectedContentId) {
            contentSelect.value = selectedContentId;
        }
    })
    .catch(error => {
        console.error('Error loading contents:', error);
        contentSelect.disabled = true;
    });
}

// Load courses and sections on page load
document.addEventListener('DOMContentLoaded', function() {
    const subjectId = document.getElementById('subject_id').value;
    const courseId = document.getElementById('course_id').value;
    const selectedSectionId = '{{ old('section_id', $exam->section_id) }}';

    if (subjectId) {
        // If courses are already loaded from server (on edit), don't reload unless needed
        const courseSelect = document.getElementById('course_id');
        if (!courseId || courseSelect.options.length <= 1) {
            loadSubjectCourses(subjectId);
        } else if (courseId) {
            // If sections are already loaded from server (on edit), don't reload
            const sectionSelect = document.getElementById('section_id');
            if (sectionSelect.options.length <= 1) {
                loadCourseSections(courseId, selectedSectionId);
            }
        }
    }
});

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
        alert('{{ __("messages.passing_grade_must_be_between_0_100") }}');
        return;
    }
});
</script>
@endsection