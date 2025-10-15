
{{-- resources/views/questions/create.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('messages.add_question') }}: {{ $exam->name }}</h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('questions.store', $exam) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="type" class="form-label">{{ __('messages.question_type') }} <span class="text-danger">*</span></label>
                                <select class="form-control @error('type') is-invalid @enderror" 
                                        id="type" name="type" required onchange="toggleQuestionFields()">
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
                                    <option value="fill_blank" {{ old('type') == 'fill_blank' ? 'selected' : '' }}>
                                        {{ __('messages.fill_blank') }}
                                    </option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="grade" class="form-label">{{ __('messages.grade') }} <span class="text-danger">*</span></label>
                                <input type="number" step="0.1" class="form-control @error('grade') is-invalid @enderror" 
                                       id="grade" name="grade" value="{{ old('grade', 1) }}" min="0.1" required>
                                @error('grade')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="order" class="form-label">{{ __('messages.order') }} <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                       id="order" name="order" value="{{ old('order', $nextOrder) }}" min="1" required>
                                @error('order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="question_text" class="form-label">{{ __('messages.question_text') }} <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('question_text') is-invalid @enderror" 
                                      id="question_text" name="question_text" rows="4" required>{{ old('question_text') }}</textarea>
                            @error('question_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="question_image" class="form-label">{{ __('messages.question_image') }}</label>
                            <input type="file" class="form-control @error('question_image') is-invalid @enderror" 
                                   id="question_image" name="question_image" accept="image/*">
                            @error('question_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Multiple Choice Options -->
                        <div id="multiple_choice_fields" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label">{{ __('messages.answer_options') }} <span class="text-danger">*</span></label>
                                <div id="options_container">
                                    <div class="row mb-2">
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" name="options[]" placeholder="{{ __('messages.option') }} 1">
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="correct_answers[]" value="0">
                                                <label class="form-check-label">{{ __('messages.correct') }}</label>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-sm btn-danger" onclick="removeOption(this)">×</button>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-secondary" onclick="addOption()">{{ __('messages.add_option') }}</button>
                            </div>
                        </div>

                        <!-- True/False Options -->
                        <div id="true_false_fields" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label">{{ __('messages.correct_answer') }} <span class="text-danger">*</span></label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="correct_answers" id="true_answer" value="true">
                                        <label class="form-check-label" for="true_answer">{{ __('messages.true') }}</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="correct_answers" id="false_answer" value="false">
                                        <label class="form-check-label" for="false_answer">{{ __('messages.false') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="explanation" class="form-label">{{ __('messages.explanation') }}</label>
                            <textarea class="form-control @error('explanation') is-invalid @enderror" 
                                      id="explanation" name="explanation" rows="2" 
                                      placeholder="{{ __('messages.explanation_placeholder') }}">{{ old('explanation') }}</textarea>
                            @error('explanation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('questions.index', $exam) }}" class="btn btn-secondary">
                                {{ __('messages.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                {{ __('messages.create_question') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let optionCount = 1;

function toggleQuestionFields() {
    const type = document.getElementById('type').value;
    const mcFields = document.getElementById('multiple_choice_fields');
    const tfFields = document.getElementById('true_false_fields');
    
    // Hide all fields first
    mcFields.style.display = 'none';
    tfFields.style.display = 'none';
    
    // Show relevant fields
    if (type === 'multiple_choice') {
        mcFields.style.display = 'block';
    } else if (type === 'true_false') {
        tfFields.style.display = 'block';
    }
}

function addOption() {
    optionCount++;
    const container = document.getElementById('options_container');
    const newOption = document.createElement('div');
    newOption.className = 'row mb-2';
    newOption.innerHTML = `
        <div class="col-md-8">
            <input type="text" class="form-control" name="options[]" placeholder="{{ __('messages.option') }} ${optionCount}">
        </div>
        <div class="col-md-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="correct_answers[]" value="${optionCount - 1}">
                <label class="form-check-label">{{ __('messages.correct') }}</label>
            </div>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-sm btn-danger" onclick="removeOption(this)">×</button>
        </div>
    `;
    container.appendChild(newOption);
}

function removeOption(button) {
    if (document.getElementById('options_container').children.length > 1) {
        button.closest('.row').remove();
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleQuestionFields();
});
</script>
@endsection