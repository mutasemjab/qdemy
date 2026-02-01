{{-- resources/views/web/exam/exam-result.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="exam-result-section">
        <div class="result-container">
            {{-- Back Button --}}
            <a href="{{ route($apiRoutePrefix . 'exams') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                {{ __('front.back') }}
            </a>

            {{-- Result Summary --}}
            <div class="result-summary">
                <div class="result-header">
                    <h1>{{ $exam->title }}</h1>
                    <p class="result-date">{{ __('front.attempt_date') }}: {{ $attempt->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>

                {{-- Score Card --}}
                <div class="score-card {{ $passed ? 'passed' : 'failed' }}">
                    <div class="score-icon">
                        @if ($passed)
                            <i class="fas fa-check-circle"></i>
                        @else
                            <i class="fas fa-times-circle"></i>
                        @endif
                    </div>
                    <div class="score-content">
                        <h2 class="score-title">
                            @if ($passed)
                                {{ __('front.exam_passed') }}
                            @else
                                {{ __('front.exam_failed') }}
                            @endif
                        </h2>
                        <div class="score-display">
                            <span class="score-number">{{ $attempt->score }}</span>
                            <span class="score-total">/ {{ $exam->total_grade }}</span>
                        </div>
                        <div class="score-percentage">
                            {{ round(($attempt->score / $exam->total_grade) * 100, 1) }}%
                        </div>
                    </div>
                </div>

                {{-- Stats Grid --}}
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-label">{{ __('front.correct_answers') }}</div>
                        <div class="stat-value">{{ $correctCount }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">{{ __('front.wrong_answers') }}</div>
                        <div class="stat-value">{{ $wrongCount }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">{{ __('front.passing_grade') }}</div>
                        <div class="stat-value">{{ $exam->passing_grade }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">{{ __('front.attempt_date') }}</div>
                        <div class="stat-value">
                            @if ($attempt->created_at)
                                {{ $attempt->created_at->diffForHumans() }}@else{{ __('front.unknown') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- All Previous Attempts Section --}}
            @if (isset($allAttempts) && $allAttempts->count() > 1)
                <div class="previous-attempts-section">
                    <h2 class="section-title">{{ __('front.all_attempts') }}</h2>
                    <div class="attempts-list">
                        @foreach ($allAttempts as $index => $attemptItem)
                            <div class="attempt-item {{ $attemptItem->id === $attempt->id ? 'current-attempt' : '' }}">
                                <div class="attempt-header">
                                    <div class="attempt-info">
                                        <span class="attempt-number">{{ __('front.attempt') }}
                                            #{{ $allAttempts->count() - $index }}</span>
                                        <span
                                            class="attempt-date">{{ $attemptItem->created_at->format('d/m/Y H:i') }}</span>
                                        @if ($attemptItem->id === $attempt->id)
                                            <span class="current-badge">{{ __('front.current') }}</span>
                                        @endif
                                    </div>
                                    <div class="attempt-result">
                                        <span class="attempt-score {{ $attemptItem->is_passed ? 'passed' : 'failed' }}">
                                            {{ $attemptItem->score }} / {{ $exam->total_grade }}
                                            ({{ round(($attemptItem->score / $exam->total_grade) * 100, 1) }}%)
                                        </span>
                                        @if ($attemptItem->is_passed)
                                            <i class="fas fa-check-circle text-success"></i>
                                        @else
                                            <i class="fas fa-times-circle text-danger"></i>
                                        @endif
                                    </div>
                                </div>
                                @if ($attemptItem->id !== $attempt->id)
                                    <a href="{{ route($apiRoutePrefix . 'exam.result', ['exam' => $exam->id, 'attempt' => $attemptItem->id]) }}"
                                        class="view-attempt-btn">
                                        {{ __('front.view_details') }}
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Questions Review --}}
            <div class="questions-review">
                <h2>{{ __('front.مراجعة الامتحان') }}</h2>

                @foreach ($answers as $answer)
                    @php
                        $question = $answer->question;
                        $isCorrect = $answer->is_correct;
                    @endphp
                    <div class="question-review {{ $isCorrect ? 'correct' : 'incorrect' }}">
                        <div class="question-header">
                            <h3 class="question-text">{{ $question->question }}</h3>
                            <span class="question-status">
                                @if ($isCorrect)
                                    <i class="fas fa-check"></i> {{ __('front.صحيح') }}
                                @elseif($isCorrect === false)
                                    <i class="fas fa-times"></i> {{ __('front.خطأ') }}
                                @else
                                    <i class="fas fa-hourglass-half"></i> {{ __('front.قيد التصحيح') }}
                                @endif
                            </span>
                        </div>

                        {{-- Question Image --}}
                        @if ($question->photo)
                            <div class="question-image" style="margin: 16px 0;">
                                <img src="{{ asset('assets/admin/uploads/' . $question->photo) }}"
                                    alt="{{ __('front.question_image') }}"
                                    style="max-width: 100%; max-height: 300px; border-radius: 6px;" />
                            </div>
                        @endif

                        {{-- Display Based on Question Type --}}
                        @if ($question->type === 'multiple_choice')
                            <div class="question-options">
                                @foreach ($question->options as $option)
                                    <div
                                        class="option-item {{ in_array($option->id, (array) $answer->selected_options) ? 'selected' : '' }} {{ $option->is_correct ? 'correct-answer' : '' }}">
                                        <input type="checkbox" disabled
                                            {{ in_array($option->id, (array) $answer->selected_options) ? 'checked' : '' }}>
                                        <label>{{ $option->option }}</label>
                                        @if ($option->is_correct)
                                            <span class="correct-badge">✓ الإجابة الصحيحة</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @elseif($question->type === 'true_false')
                            @php
                                $correctOption = $question->options()->where('is_correct', true)->first();
                                $correctAnswer = strtolower($correctOption->option_en ?? '') === 'true';
                                $userSelectedTrue =
                                    isset($answer->selected_options[0]) && $answer->selected_options[0] === true;
                            @endphp
                            <div class="true-false-review">
                                <div
                                    class="answer-item {{ $userSelectedTrue ? 'selected' : '' }} {{ $correctAnswer ? 'correct-answer' : '' }}">
                                    <i class="fas fa-check"></i> {{ __('front.صحيح') }}
                                    @if ($correctAnswer)
                                        <span class="correct-badge">✓</span>
                                    @endif
                                </div>
                                <div
                                    class="answer-item {{ !$userSelectedTrue && $answer->selected_options ? 'selected' : '' }} {{ !$correctAnswer ? 'correct-answer' : '' }}">
                                    <i class="fas fa-times"></i> {{ __('front.خطأ') }}
                                    @if (!$correctAnswer)
                                        <span class="correct-badge">✓</span>
                                    @endif
                                </div>
                            </div>
                        @elseif($question->type === 'essay')
                            <div class="essay-review">
                                <div class="user-answer">
                                    <strong>{{ __('front.إجابتك') }}:</strong>
                                    <p>{{ !empty($answer->essay_answer) ? $answer->essay_answer : __('front.لم يتم الإجابة') }}
                                    </p>
                                </div>
                                @if ($question->explanation)
                                    <div class="correct-answer-text">
                                        <strong>{{ __('front.الشرح') }}:</strong>
                                        <p>{{ $question->explanation }}</p>
                                    </div>
                                @endif
                            </div>
                        @endif

                        {{-- Feedback Message --}}
                        @if ($isCorrect === true)
                            <div class="feedback success-feedback">
                                <i class="fas fa-check-circle"></i>
                                <p>
                                    <strong>{{ $question->correct_feedback ?? '' }}</strong> ✓
                                </p>
                            </div>
                        @elseif($isCorrect === false)
                            <div class="feedback error-feedback">
                                <i class="fas fa-times-circle"></i>
                                <p>
                                    <strong>{{ $question->incorrect_feedback ?? '' }}</strong>
                                    @if ($question->type !== 'essay' && $question->explanation)
                                        <br><small>{{ $question->explanation }}</small>
                                    @elseif($question->type === 'multiple_choice')
                                        <br><small>الإجابات الصحيحة:
                                            {{ $question->options()->where('is_correct', true)->get()->pluck('option')->join(' و ') }}</small>
                                    @elseif($question->type === 'true_false')
                                        <br><small>الإجابة الصحيحة:
                                            {{ $correctAnswer ? __('front.صحيح') : __('front.خطأ') }}</small>
                                    @endif
                                </p>
                            </div>
                        @else
                            <div class="feedback pending-feedback">
                                <i class="fas fa-hourglass-half"></i>
                                <p><strong>{{ __('front.في انتظار التصحيح اليدوي') }}</strong></p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Actions --}}
            <div class="result-actions">
                <a href="{{ route($apiRoutePrefix . 'exams') }}" class="btn btn-secondary">
                    <i class="fas fa-list"></i>
                    {{ __('front.back') }}
                </a>
                @if ($canRetake)
                    <a href="{{ route($apiRoutePrefix . 'exam.start', ['exam' => $exam->id]) }}" class="btn btn-primary">
                        <i class="fas fa-redo"></i>
                        {{ __('front.محاولة جديدة') }}
                    </a>
                @endif
            </div>
        </div>
    </div>

    <style>
        .exam-result-section {
            padding: 40px 20px;
            background: #f8f9fa;
            min-height: 100vh;
        }

        .exam-result-section .result-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .exam-result-section .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: white;
            color: #333;
            text-decoration: none;
            border-radius: 6px;
            margin-bottom: 20px;
            transition: all 0.3s;
            border: 1px solid #e0e0e0;
        }

        .exam-result-section .back-btn:hover {
            background: #f0f0f0;
        }

        .exam-result-section .result-summary {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 30px;
            border: 1px solid #e0e0e0;
        }

        .exam-result-section .result-header {
            padding: 30px;
            background: #f8f9fa;
            border-bottom: 1px solid #e0e0e0;
        }

        .exam-result-section .result-header h1 {
            font-size: 24px;
            margin: 0 0 8px 0;
            color: #333;
        }

        .exam-result-section .result-date {
            margin: 0;
            color: #666;
            font-size: 20px;
        }

        .exam-result-section .score-card {
            padding: 40px;
            display: flex;
            gap: 30px;
            align-items: center;
            border-bottom: 1px solid #e0e0e0;
        }

        .exam-result-section .score-card.passed {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        }

        .exam-result-section .score-card.failed {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        }

        .exam-result-section .score-icon {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
        }

        .exam-result-section .score-card.passed .score-icon {
            background: rgba(40, 167, 69, 0.2);
            color: #28a745;
        }

        .exam-result-section .score-card.failed .score-icon {
            background: rgba(220, 53, 69, 0.2);
            color: #dc3545;
        }

        .exam-result-section .score-content {
            flex: 1;
        }

        .exam-result-section .score-title {
            font-size: 28px;
            margin: 0 0 12px 0;
            font-weight: bold;
        }

        .exam-result-section .score-card.passed .score-title {
            color: #155724;
        }

        .exam-result-section .score-card.failed .score-title {
            color: #721c24;
        }

        .exam-result-section .score-display {
            display: flex;
            align-items: baseline;
            gap: 4px;
            margin-bottom: 12px;
        }

        .exam-result-section .score-number {
            font-size: 40px;
            font-weight: bold;
        }

        .exam-result-section .score-total {
            font-size: 40px;
            font-weight: bold;
            opacity: 0.8;
        }

        .exam-result-section .score-percentage {
            font-size: 30px;
            font-weight: bold;
            opacity: 0.9;
        }

        .exam-result-section .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            padding: 30px;
            gap: 20px;
        }

        .exam-result-section .stat-item {
            text-align: center;
        }

        .exam-result-section .stat-label {
            font-size: 20px;
            color: #999;
            margin-bottom: 8px;
        }

        .exam-result-section .stat-value {
            font-size: 28px;
            font-weight: bold;
            color: #007bff;
        }

        /* Previous Attempts Section */
        .exam-result-section .previous-attempts-section {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            border: 1px solid #e0e0e0;
        }

        .exam-result-section .section-title {
            font-size: 22px;
            margin: 0 0 20px 0;
            color: #333;
        }

        .exam-result-section .attempts-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .exam-result-section .attempt-item {
            background: #f8f9fa;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 16px;
            transition: all 0.3s;
        }

        .exam-result-section .attempt-item.current-attempt {
            border-color: #007bff;
            background: #f0f7ff;
        }

        .exam-result-section .attempt-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .exam-result-section .attempt-info {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .exam-result-section .attempt-number {
            font-weight: bold;
            color: #333;
            font-size: 20px;
        }

        .exam-result-section .attempt-date {
            color: #666;
            font-size: 20px;
        }

        .exam-result-section .current-badge {
            background: #007bff;
            color: white;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 20px;
            font-weight: bold;
        }

        .exam-result-section .attempt-result {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .exam-result-section .attempt-score {
            font-weight: bold;
            font-size: 20px;
        }

        .exam-result-section .attempt-score.passed {
            color: #28a745;
        }

        .exam-result-section .attempt-score.failed {
            color: #dc3545;
        }

        .exam-result-section .view-attempt-btn {
            display: inline-block;
            padding: 8px 16px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 20px;
            transition: all 0.3s;
        }

        .exam-result-section .view-attempt-btn:hover {
            background: #0056b3;
        }

        .exam-result-section .text-success {
            color: #28a745;
        }

        .exam-result-section .text-danger {
            color: #dc3545;
        }

        .exam-result-section .questions-review {
            margin-top: 30px;
        }

        .exam-result-section .questions-review h2 {
            font-size: 22px;
            margin: 0 0 20px 0;
            color: #333;
        }

        .exam-result-section .question-review {
            background: white;
            border-left: 4px solid #e0e0e0;
            padding: 24px;
            margin-bottom: 16px;
            border-radius: 8px;
        }

        .exam-result-section .question-review.correct {
            border-left-color: #28a745;
            background: #f8fff9;
        }

        .exam-result-section .question-review.incorrect {
            border-left-color: #dc3545;
            background: #fff8f8;
        }

        .exam-result-section .question-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 16px;
        }

        .exam-result-section .question-text {
            font-size: 24px;
            margin: 0;
            color: #333;
            flex: 1;
            line-height: 1.5;
        }

        .exam-result-section .question-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 20px;
            font-weight: bold;
            white-space: nowrap;
        }

        .exam-result-section .question-review.correct .question-status {
            background: #d4edda;
            color: #155724;
        }

        .exam-result-section .question-review.incorrect .question-status {
            background: #f8d7da;
            color: #721c24;
        }

        .exam-result-section .question-options {
            margin: 16px 0;
        }

        .exam-result-section .option-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            margin-bottom: 8px;
            cursor: not-allowed;
        }

        .exam-result-section .option-item.selected {
            border-color: #007bff;
            background: #f0f7ff;
        }

        .exam-result-section .option-item.correct-answer {
            border-color: #28a745;
            background: #f8fff9;
        }

        .exam-result-section .option-item label {
            margin: 0;
            cursor: not-allowed;
            flex: 1;
        }

        .exam-result-section .correct-badge {
            color: #28a745;
            font-weight: bold;
        }

        .exam-result-section .true-false-review {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin: 16px 0;
        }

        .exam-result-section .answer-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            text-align: center;
        }

        .exam-result-section .essay-review {
            margin: 16px 0;
            padding: 16px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .exam-result-section .user-answer {
            margin: 0;
        }

        .exam-result-section .user-answer strong {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        .exam-result-section .user-answer p {
            margin: 0;
            color: #666;
            line-height: 1.6;
        }

        .exam-result-section .feedback {
            display: flex;
            gap: 12px;
            padding: 12px;
            border-radius: 6px;
            margin-top: 12px;
            font-size: 22px;
        }

        .exam-result-section .feedback i {
            flex-shrink: 0;
            margin-top: 2px;
        }

        .exam-result-section .success-feedback {
            background: #d4edda;
            color: #155724;
            border-left: 3px solid #28a745;
        }

        .exam-result-section .error-feedback {
            background: #f8d7da;
            color: #721c24;
            border-left: 3px solid #dc3545;
        }

        .exam-result-section .pending-feedback {
            background: #fff3cd;
            color: #856404;
            border-left: 3px solid #ffc107;
        }

        .exam-result-section .result-actions {
            display: flex;
            gap: 12px;
            margin-top: 30px;
            justify-content: center;
        }

        .exam-result-section .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            font-size: 20px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
        }

        .exam-result-section .btn-primary {
            background: #007bff;
            color: white;
        }

        .exam-result-section .btn-primary:hover {
            background: #0056b3;
        }

        .exam-result-section .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .exam-result-section .btn-secondary:hover {
            background: #5a6268;
        }
    </style>
@endsection
