@extends('layouts.admin')

@section('title', __('messages.view_attempt'))

@section('css')
<style>
    .answer-item {
        padding: 1.5rem;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .answer-item:hover {
        background: #e9ecef;
    }

    .answer-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .answer-type {
        display: inline-block;
        font-size: 0.85em;
        padding: 0.4rem 0.8rem;
        background: #e3f2fd;
        color: #1976d2;
        border-radius: 4px;
        margin-right: 0.5rem;
    }

    .answer-status {
        padding: 0.4rem 0.8rem;
        border-radius: 4px;
        font-size: 0.85em;
        font-weight: 600;
    }

    .answer-status.correct {
        background: #c8e6c9;
        color: #2e7d32;
    }

    .answer-status.incorrect {
        background: #ffcdd2;
        color: #c62828;
    }

    .answer-status.pending {
        background: #fff9c4;
        color: #f57f17;
    }

    .question-text {
        font-size: 1.05rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #212121;
    }

    .answer-content {
        background: white;
        padding: 1rem;
        border-radius: 4px;
        margin-bottom: 1rem;
    }

    .option-item {
        padding: 0.75rem;
        margin-bottom: 0.5rem;
        border-left: 3px solid #ddd;
        background: #f5f5f5;
    }

    .option-item.selected {
        border-left-color: #1976d2;
        background: #e3f2fd;
    }

    .option-item.correct {
        border-left-color: #4caf50;
        background: #e8f5e9;
    }

    .option-item.incorrect {
        border-left-color: #f44336;
        background: #ffebee;
    }

    .essay-answer {
        padding: 1rem;
        background: white;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-family: 'Courier New', monospace;
        line-height: 1.6;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    .score-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid #ddd;
    }

    .score-badge {
        font-size: 1.2rem;
        font-weight: bold;
        color: #1976d2;
    }

    .info-box {
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .badge-lg {
        font-size: 0.9em;
        padding: 0.5em 0.75em;
    }

    .back-button {
        margin-bottom: 1.5rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('exams.results', $exam) }}" class="btn btn-secondary back-button">
                <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_results') }}
            </a>

            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">{{ __('messages.student_attempt') }}</h2>
                    <p class="text-muted mb-0">
                        <strong>{{ $attempt->user->name }}</strong> -
                        {{ app()->getLocale() == 'ar' ? $exam->title_ar : $exam->title_en }}
                    </p>
                </div>
                <div>
                    <span class="badge badge-lg badge-{{ $attempt->is_passed ? 'success' : 'danger' }}">
                        {{ $attempt->is_passed ? __('messages.passed') : __('messages.failed') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-primary">
                    <i class="fas fa-percentage"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('messages.percentage') }}</span>
                    <span class="info-box-number">{{ number_format($attempt->percentage, 1) }}%</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info">
                    <i class="fas fa-star"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('messages.score') }}</span>
                    <span class="info-box-number">{{ number_format($attempt->score, 2) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-warning">
                    <i class="fas fa-chart-pie"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('messages.total_grade') }}</span>
                    <span class="info-box-number">{{ number_format($exam->total_grade, 2) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-secondary">
                    <i class="fas fa-clock"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('messages.submitted_at') }}</span>
                    <span class="info-box-number text-sm">{{ $attempt->submitted_at ? $attempt->submitted_at->format('H:i') : '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Answers Section -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list"></i> {{ __('messages.student_answers') }}
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        // Get all questions in the order they were presented
                        $question_order = $attempt->question_order;
                        $allQuestions = \App\Models\Question::whereIn('id', $question_order)
                            ->with('options')
                            ->get()
                            ->keyBy('id');

                        // Get all answers
                        $answersList = $attempt->answers()->with(['question', 'question.options'])->get();
                        $answersMap = $answersList->keyBy('question_id');

                        // Build complete list of all questions with their answers
                        $answers = [];
                        foreach ($question_order as $question_id) {
                            if (isset($allQuestions[$question_id])) {
                                $answer = $answersMap[$question_id] ?? null;

                                if (!$answer) {
                                    // Create a dummy answer object for unanswered questions
                                    $answer = new \App\Models\ExamAnswer([
                                        'question_id' => $question_id,
                                        'is_correct' => false,
                                        'score' => 0,
                                        'answered_at' => null,
                                    ]);
                                    $answer->question = $allQuestions[$question_id];
                                }

                                $answers[] = $answer;
                            }
                        }
                    @endphp

                    @forelse($answers as $answer)
                        @php
                            $question = $answer->question;
                            $status = $answer->is_correct === null ? 'pending' : ($answer->is_correct ? 'correct' : 'incorrect');
                        @endphp

                        <div class="answer-item">
                            <div class="answer-header">
                                <div>
                                    <span class="answer-type">
                                        <i class="fas fa-tag"></i> {{ ucfirst($question->type) }}
                                    </span>
                                </div>
                                <span class="answer-status {{ $status }}">
                                    @if($status === 'correct')
                                        <i class="fas fa-check-circle"></i> {{ __('messages.correct') }}
                                    @elseif($status === 'incorrect')
                                        <i class="fas fa-times-circle"></i> {{ __('messages.incorrect') }}
                                    @else
                                        <i class="fas fa-hourglass-half"></i> {{ __('messages.pending_review') }}
                                    @endif
                                </span>
                            </div>

                            <div class="question-text">
                                {{ $question->question }}
                            </div>

                            {{-- Question Image --}}
                            @if($question->photo)
                                <div style="margin: 1rem 0;">
                                    <img src="{{ asset('assets/admin/uploads/' . $question->photo) }}" alt="{{ __('messages.question_image') }}" style="max-width: 100%; max-height: 300px; border-radius: 6px;" />
                                </div>
                            @endif

                            <div class="answer-content">
                                @if($question->type === 'multiple_choice')
                                    @php
                                        $selectedOptions = $answer->selected_options ?? [];
                                    @endphp

                                    <strong>{{ __('messages.student_answer') }}:</strong>
                                    <div class="mt-2">
                                        @foreach($question->options as $option)
                                            @php
                                                $isSelected = in_array($option->id, (array)$selectedOptions);
                                            @endphp
                                            <div class="option-item {{ $isSelected ? 'selected' : '' }} {{ $option->is_correct ? 'correct' : '' }}">
                                                <div class="form-check mb-0">
                                                    <input class="form-check-input" type="checkbox" disabled
                                                           {{ $isSelected ? 'checked' : '' }}>
                                                    <label class="form-check-label">
                                                        {{ $option->option }}
                                                        @if($option->is_correct)
                                                            <strong class="text-success">(✓ {{ __('messages.correct') }})</strong>
                                                        @endif
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                @elseif($question->type === 'true_false')
                                    @php
                                        $selectedAnswer = $answer->selected_options[0] ?? null;
                                        $correctOption = $question->options()->where('is_correct', true)->first();
                                        $correctAnswer = strtolower($correctOption?->option_en ?? '') === 'true';
                                    @endphp

                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>{{ __('messages.student_answer') }}:</strong>
                                            <div class="mt-2 p-2 bg-white border rounded">
                                                {{ $selectedAnswer ? __('messages.true') : __('messages.false') }}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>{{ __('messages.correct_answer') }}:</strong>
                                            <div class="mt-2 p-2 bg-success text-white border-success border rounded">
                                                {{ $correctAnswer ? __('messages.true') : __('messages.false') }}
                                            </div>
                                        </div>
                                    </div>

                                @elseif($question->type === 'essay')
                                    <strong>{{ __('messages.student_answer') }}:</strong>
                                    <div class="essay-answer mt-2">
                                        {{ !empty($answer->essay_answer) ? $answer->essay_answer : __('messages.no_answer') }}
                                    </div>

                                    @if($status === 'pending')
                                        <div class="alert alert-warning mt-2">
                                            <i class="fas fa-info-circle"></i>
                                            {{ __('messages.essay_pending_review') }}
                                        </div>
                                        <button type="button"
                                                class="btn btn-sm btn-primary mt-2"
                                                onclick="showGradingModal({{ $answer->id }}, {{ $question->pivot->grade ?? $question->grade }})">
                                            <i class="fas fa-edit"></i> {{ __('messages.grade_essay') }}
                                        </button>
                                    @else
                                        <div class="alert alert-info mt-2 mb-0">
                                            <i class="fas fa-check-circle"></i>
                                            {{ __('messages.already_graded') }}
                                        </div>
                                    @endif
                                @endif
                            </div>

                            <div class="score-info">
                                <div>
                                    <small class="text-muted">{{ __('messages.question_grade') }}:</small>
                                </div>
                                <div class="score-badge">
                                    {{ number_format($answer->score, 2) }} / {{ number_format($question->pivot->grade ?? $question->grade, 2) }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            {{ __('messages.no_answers') }}
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grading Modal -->
<div class="modal fade" id="gradingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('messages.grade_essay_answer') }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="gradingForm">
                @csrf
                <input type="hidden" id="answerId" name="answer_id">

                <div class="modal-body">
                    <div class="form-group">
                        <label for="score">{{ __('messages.score') }} *</label>
                        <div class="input-group">
                            <input type="number"
                                   class="form-control"
                                   id="score"
                                   name="score"
                                   min="0"
                                   step="0.01"
                                   required>
                            <div class="input-group-append">
                                <span class="input-group-text" id="maxScore">/ 10</span>
                            </div>
                        </div>
                        <small class="form-text text-muted">
                            {{ __('messages.enter_score_between_0_and_max') }}
                        </small>
                    </div>


                    <div class="form-group">
                        <label for="is_correct">{{ __('messages.is_answer_correct') }}</label>
                        <div class="custom-control custom-radio">
                            <input type="radio"
                                   class="custom-control-input"
                                   id="correctYes"
                                   name="is_correct"
                                   value="1">
                            <label class="custom-control-label" for="correctYes">
                                {{ __('messages.yes') }} ({{ __('messages.correct') }})
                            </label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio"
                                   class="custom-control-input"
                                   id="correctNo"
                                   name="is_correct"
                                   value="0"
                                   checked>
                            <label class="custom-control-label" for="correctNo">
                                {{ __('messages.no') }} ({{ __('messages.incorrect_or_partial') }})
                            </label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        {{ __('messages.cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ __('messages.save_grade') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function showGradingModal(answerId, maxScore) {
    document.getElementById('answerId').value = answerId;
    document.getElementById('maxScore').textContent = '/ ' + maxScore;
    document.getElementById('score').max = maxScore;
    document.getElementById('score').value = '';
    document.getElementById('correctNo').checked = true;

    $('#gradingModal').modal('show');
}

document.getElementById('gradingForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const answerId = document.getElementById('answerId').value;
    const score = document.getElementById('score').value;
    const isCorrect = document.querySelector('input[name="is_correct"]:checked').value;

    const formData = new FormData();
    formData.append('_token', document.querySelector('[name="_token"]').value);
    formData.append('score', score);
    formData.append('is_correct', isCorrect);

    fetch(`/admin/exams/{{ $exam->id }}/attempts/{{ $attempt->id }}/grade-answer/${answerId}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            alert('{{ __("messages.graded_successfully") }}');
            $('#gradingModal').modal('hide');

            // Reload the page to show updated scores
            setTimeout(() => {
                location.reload();
            }, 500);
        } else {
            alert(data.message || '{{ __("messages.error_occurred") }}');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('{{ __("messages.error_occurred") }}');
    });
});
</script>
@endpush