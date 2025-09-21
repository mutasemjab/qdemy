@extends('layouts.app')

@section('title', __('panel.create_question'))

@section('page_title', __('panel.create_question'))

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
    .option-item {
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-dark fw-bold">{{ __('panel.create_question') }}</h2>
            <p class="text-muted">
                {{ __('panel.exam') }}: {{ app()->getLocale() == 'ar' ? $exam->title_ar : $exam->title_en }}
            </p>
        </div>
        <a href="{{ route('teacher.exams.exam_questions.index', $exam) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>{{ __('panel.back_to_questions') }}
        </a>
    </div>

    <form action="{{ route('teacher.exams.exam_questions.store', $exam) }}" method="POST" id="questionForm">
        @csrf
        
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <!-- Question Title English -->
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="title_en" class="form-label required">{{ __('panel.question_title_en') }}</label>
                            <input type="text" 
                                   class="form-control @error('title_en') is-invalid @enderror" 
                                   id="title_en" 
                                   name="title_en" 
                                   value="{{ old('title_en') }}" 
                                   placeholder="{{ __('panel.enter_question_title_en') }}">
                            @error('title_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Question Title Arabic -->
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="title_ar" class="form-label required">{{ __('panel.question_title_ar') }}</label>
                            <input type="text" 
                                   class="form-control @error('title_ar') is-invalid @enderror" 
                                   id="title_ar" 
                                   name="title_ar" 
                                   value="{{ old('title_ar') }}" 
                                   placeholder="{{ __('panel.enter_question_title_ar') }}"
                                   dir="rtl">
                            @error('title_ar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Question Text English -->
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="question_en" class="form-label required">{{ __('panel.question_text_en') }}</label>
                            <textarea class="form-control @error('question_en') is-invalid @enderror" 
                                      id="question_en" 
                                      name="question_en" 
                                      rows="4" 
                                      placeholder="{{ __('panel.enter_question_text_en') }}">{{ old('question_en') }}</textarea>
                            @error('question_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Question Text Arabic -->
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="question_ar" class="form-label required">{{ __('panel.question_text_ar') }}</label>
                            <textarea class="form-control @error('question_ar') is-invalid @enderror" 
                                      id="question_ar" 
                                      name="question_ar" 
                                      rows="4" 
                                      placeholder="{{ __('panel.enter_question_text_ar') }}"
                                      dir="rtl">{{ old('question_ar') }}</textarea>
                            @error('question_ar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Course (Auto-filled from exam) -->
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="course_id" class="form-label">{{ __('panel.course') }}</label>
                            <input type="hidden" name="course_id" value="{{ $exam->course_id }}">
                            <input type="text" class="form-control" 
                                   value="{{ $exam->course ? (app()->getLocale() === 'ar' ? $exam->course->title_ar : $exam->course->title_en) : __('panel.no_course_assigned') }}" 
                                   readonly>
                            <small class="text-muted">{{ __('panel.auto_assigned_from_exam') }}</small>
                        </div>
                    </div>

                    <!-- Question Type -->
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="type" class="form-label required">{{ __('panel.question_type') }}</label>
                            <select class="form-control @error('type') is-invalid @enderror" 
                                    id="type" 
                                    name="type"
                                    onchange="toggleQuestionType()">
                                <option value="">{{ __('panel.select_question_type') }}</option>
                                <option value="multiple_choice" {{ old('type') == 'multiple_choice' ? 'selected' : '' }}>
                                    {{ __('panel.multiple_choice') }}
                                </option>
                                <option value="true_false" {{ old('type') == 'true_false' ? 'selected' : '' }}>
                                    {{ __('panel.true_false') }}
                                </option>
                                <option value="essay" {{ old('type') == 'essay' ? 'selected' : '' }}>
                                    {{ __('panel.essay') }}
                                </option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Grade -->
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="grade" class="form-label required">{{ __('panel.grade') }}</label>
                            <input type="number" 
                                   class="form-control @error('grade') is-invalid @enderror" 
                                   id="grade" 
                                   name="grade" 
                                   value="{{ old('grade', 1) }}" 
                                   step="0.25" 
                                   min="0.25"
                                   placeholder="1.00">
                            @error('grade')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Explanation English -->
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="explanation_en" class="form-label">{{ __('panel.explanation_en') }}</label>
                            <textarea class="form-control @error('explanation_en') is-invalid @enderror" 
                                      id="explanation_en" 
                                      name="explanation_en" 
                                      rows="3" 
                                      placeholder="{{ __('panel.enter_explanation_en') }}">{{ old('explanation_en') }}</textarea>
                            @error('explanation_en')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Explanation Arabic -->
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="explanation_ar" class="form-label">{{ __('panel.explanation_ar') }}</label>
                            <textarea class="form-control @error('explanation_ar') is-invalid @enderror" 
                                      id="explanation_ar" 
                                      name="explanation_ar" 
                                      rows="3" 
                                      placeholder="{{ __('panel.enter_explanation_ar') }}"
                                      dir="rtl">{{ old('explanation_ar') }}</textarea>
                            @error('explanation_ar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Multiple Choice Options -->
                <div id="multiple-choice-options" class="row" style="display: none;">
                    <div class="col-12">
                        <h5 class="text-primary mb-3">{{ __('panel.answer_options') }}</h5>
                        <div id="options-container">
                            <!-- Options will be added dynamically -->
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="addOption()">
                            <i class="fas fa-plus"></i> {{ __('panel.add_option') }}
                        </button>
                    </div>
                </div>

                <!-- True/False Options -->
                <div id="true-false-options" class="row" style="display: none;">
                    <div class="col-12">
                        <h5 class="text-primary mb-3">{{ __('panel.correct_answer') }}</h5>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="true_false_answer" 
                                   id="answer_true" value="1" {{ old('true_false_answer') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="answer_true">
                                <span class="badge bg-success">{{ __('panel.true') }}</span>
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="true_false_answer" 
                                   id="answer_false" value="0" {{ old('true_false_answer') == '0' ? 'checked' : '' }}>
                            <label class="form-check-label" for="answer_false">
                                <span class="badge bg-danger">{{ __('panel.false') }}</span>
                            </label>
                        </div>
                        @error('true_false_answer')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Essay Note -->
                <div id="essay-note" class="row" style="display: none;">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            {{ __('panel.essay_question_note') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('teacher.exams.exam_questions.index', $exam) }}" class="btn btn-secondary">
                        {{ __('panel.cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ __('panel.create_question') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
let optionCount = 0;

function toggleQuestionType() {
    const type = document.getElementById('type').value;
    const multipleChoiceDiv = document.getElementById('multiple-choice-options');
    const trueFalseDiv = document.getElementById('true-false-options');
    const essayDiv = document.getElementById('essay-note');
    
    // Hide all option divs
    multipleChoiceDiv.style.display = 'none';
    trueFalseDiv.style.display = 'none';
    essayDiv.style.display = 'none';
    
    // Show relevant div
    if (type === 'multiple_choice') {
        multipleChoiceDiv.style.display = 'block';
        if (optionCount === 0) {
            // Add default 4 options
            for (let i = 0; i < 4; i++) {
                addOption();
            }
        }
    } else if (type === 'true_false') {
        trueFalseDiv.style.display = 'block';
    } else if (type === 'essay') {
        essayDiv.style.display = 'block';
    }
}

function addOption() {
    optionCount++;
    const container = document.getElementById('options-container');
    const optionHtml = `
        <div class="option-item mb-3 p-3 border rounded" id="option-${optionCount}">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">{{ __('panel.option') }} ${String.fromCharCode(64 + optionCount)}</h6>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeOption(${optionCount})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-md-5">
                    <label class="form-label">{{ __('panel.option_text_en') }}</label>
                    <input type="text" class="form-control" name="options[${optionCount-1}][option_en]" 
                           placeholder="{{ __('panel.enter_option_en') }}" required>
                </div>
                <div class="col-md-5">
                    <label class="form-label">{{ __('panel.option_text_ar') }}</label>
                    <input type="text" class="form-control" name="options[${optionCount-1}][option_ar]" 
                           placeholder="{{ __('panel.enter_option_ar') }}" dir="rtl" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">{{ __('panel.correct') }}</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" 
                               name="options[${optionCount-1}][is_correct]" value="1" 
                               id="correct-${optionCount}">
                        <label class="form-check-label" for="correct-${optionCount}">
                            {{ __('panel.correct_answer') }}
                        </label>
                    </div>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', optionHtml);
}

function removeOption(optionId) {
    const option = document.getElementById(`option-${optionId}`);
    if (option) {
        option.remove();
        updateOptionLetters();
    }
}

function updateOptionLetters() {
    const options = document.querySelectorAll('.option-item');
    options.forEach((option, index) => {
        const letter = String.fromCharCode(65 + index);
        const header = option.querySelector('h6');
        if (header) {
            header.textContent = `{{ __('panel.option') }} ${letter}`;
        }
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const currentType = document.getElementById('type').value;
    if (currentType) {
        toggleQuestionType();
    }
});

// Form validation
document.getElementById('questionForm').addEventListener('submit', function(e) {
    const type = document.getElementById('type').value;
    
    if (type === 'multiple_choice') {
        const options = document.querySelectorAll('.option-item');
        if (options.length < 2) {
            e.preventDefault();
            alert('{{ __("panel.minimum_two_options_required") }}');
            return;
        }
        
        const correctOptions = document.querySelectorAll('input[name*="[is_correct]"]:checked');
        if (correctOptions.length === 0) {
            e.preventDefault();
            alert('{{ __("panel.at_least_one_correct_answer_required") }}');
            return;
        }
    } else if (type === 'true_false') {
        const selectedAnswer = document.querySelector('input[name="true_false_answer"]:checked');
        if (!selectedAnswer) {
            e.preventDefault();
            alert('{{ __("panel.please_select_correct_answer") }}');
            return;
        }
    }
});
</script>
@endsection