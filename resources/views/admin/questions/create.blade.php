@extends('layouts.admin')

@section('title', __('messages.add_question'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.add_question') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('questions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                        </a>
                    </div>
                </div>

                <form action="{{ route('questions.store') }}" method="POST" id="questionForm">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <!-- Question Title English -->
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
                                           placeholder="{{ __('messages.enter_question_title_en') }}">
                                    @error('title_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Question Title Arabic -->
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
                                           placeholder="{{ __('messages.enter_question_title_ar') }}"
                                           dir="rtl">
                                    @error('title_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Question Text English -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="question_en" class="form-label">
                                        {{ __('messages.question_en') }} <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('question_en') is-invalid @enderror" 
                                              id="question_en" 
                                              name="question_en" 
                                              rows="4" 
                                              placeholder="{{ __('messages.enter_question_text_en') }}">{{ old('question_en') }}</textarea>
                                    @error('question_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Question Text Arabic -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="question_ar" class="form-label">
                                        {{ __('messages.question_ar') }} <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('question_ar') is-invalid @enderror" 
                                              id="question_ar" 
                                              name="question_ar" 
                                              rows="4" 
                                              placeholder="{{ __('messages.enter_question_text_ar') }}"
                                              dir="rtl">{{ old('question_ar') }}</textarea>
                                    @error('question_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Course -->
                            <div class="col-md-4">
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

                            <!-- Question Type -->
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="type" class="form-label">
                                        {{ __('messages.question_type') }} <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control @error('type') is-invalid @enderror" 
                                            id="type" 
                                            name="type"
                                            onchange="toggleQuestionType()">
                                        <option value="">{{ __('messages.select_question_type') }}</option>
                                        <option value="multiple_choice" {{ old('type') == 'multiple_choice' ? 'selected' : '' }}>
                                            {{ __('messages.multiple_choice') }}
                                        </option>
                                        <option value="true_false" {{ old('type') == 'true_false' ? 'selected' : '' }}>
                                            {{ __('messages.true_false') }}
                                        </option>
                                        <option value="essay" {{ old('type') == 'essay' ? 'selected' : '' }}>
                                            {{ __('messages.essay') }}
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
                                    <label for="grade" class="form-label">
                                        {{ __('messages.grade') }} <span class="text-danger">*</span>
                                    </label>
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
                                    <label for="explanation_en" class="form-label">
                                        {{ __('messages.explanation_en') }}
                                    </label>
                                    <textarea class="form-control @error('explanation_en') is-invalid @enderror" 
                                              id="explanation_en" 
                                              name="explanation_en" 
                                              rows="3" 
                                              placeholder="{{ __('messages.optional_explanation_en') }}">{{ old('explanation_en') }}</textarea>
                                    @error('explanation_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Explanation Arabic -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="explanation_ar" class="form-label">
                                        {{ __('messages.explanation_ar') }}
                                    </label>
                                    <textarea class="form-control @error('explanation_ar') is-invalid @enderror" 
                                              id="explanation_ar" 
                                              name="explanation_ar" 
                                              rows="3" 
                                              placeholder="{{ __('messages.optional_explanation_ar') }}"
                                              dir="rtl">{{ old('explanation_ar') }}</textarea>
                                    @error('explanation_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                         <div class="col-md-6">
                                <div class="form-group">
                                    <label for="photo">{{ __('messages.Teacher Photo') }}</label>
                                    <input type="file" class="form-control-file @error('photo') is-invalid @enderror" 
                                           id="photo" name="photo" accept="image/*">
                                    @error('photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('messages.Accepted formats: jpeg, png, jpg, gif. Max size: 2MB') }}</small>
                                </div>
                        </div>

                        <!-- Multiple Choice Options -->
                        <div id="multiple-choice-options" class="row" style="display: none;">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">{{ __('messages.answer_options') }}</h5>
                                <div id="options-container">
                                    <!-- Options will be added dynamically -->
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="addOption()">
                                    <i class="fas fa-plus"></i> {{ __('messages.add_option') }}
                                </button>
                            </div>
                        </div>

                        <!-- True/False Options -->
                        <div id="true-false-options" class="row" style="display: none;">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">{{ __('messages.correct_answer') }}</h5>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="true_false_answer" 
                                           id="answer_true" value="1" {{ old('true_false_answer') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="answer_true">
                                        <span class="badge bg-success">{{ __('messages.true') }}</span>
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="true_false_answer" 
                                           id="answer_false" value="0" {{ old('true_false_answer') == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="answer_false">
                                        <span class="badge bg-danger">{{ __('messages.false') }}</span>
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
                                    {{ __('messages.essay_question_note') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('questions.index') }}" class="btn btn-secondary">
                                {{ __('messages.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ __('messages.save_question') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
                <h6 class="mb-0">{{ __('messages.option') }} ${String.fromCharCode(64 + optionCount)}</h6>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeOption(${optionCount})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-md-5">
                    <label class="form-label">{{ __('messages.option_text_en') }}</label>
                    <input type="text" class="form-control" name="options[${optionCount-1}][option_en]" 
                           placeholder="{{ __('messages.enter_option_en') }}" required>
                </div>
                <div class="col-md-5">
                    <label class="form-label">{{ __('messages.option_text_ar') }}</label>
                    <input type="text" class="form-control" name="options[${optionCount-1}][option_ar]" 
                           placeholder="{{ __('messages.enter_option_ar') }}" dir="rtl" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">{{ __('messages.correct') }}</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" 
                               name="options[${optionCount-1}][is_correct]" value="1" 
                               id="correct-${optionCount}">
                        <label class="form-check-label" for="correct-${optionCount}">
                            {{ __('messages.correct_answer') }}
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
        // Update option letters
        updateOptionLetters();
    }
}

function updateOptionLetters() {
    const options = document.querySelectorAll('.option-item');
    options.forEach((option, index) => {
        const letter = String.fromCharCode(65 + index); // A, B, C, D...
        const header = option.querySelector('h6');
        if (header) {
            header.textContent = `{{ __('messages.option') }} ${letter}`;
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
            alert('{{ __("messages.minimum_two_options_required") }}');
            return;
        }
        
        const correctOptions = document.querySelectorAll('input[name*="[is_correct]"]:checked');
        if (correctOptions.length === 0) {
            e.preventDefault();
            alert('{{ __("messages.at_least_one_correct_answer_required") }}');
            return;
        }
    } else if (type === 'true_false') {
        const selectedAnswer = document.querySelector('input[name="true_false_answer"]:checked');
        if (!selectedAnswer) {
            e.preventDefault();
            alert('{{ __("messages.please_select_correct_answer") }}');
            return;
        }
    }
});
</script>
@endsection