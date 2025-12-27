{{-- resources/views/web/exam/exam-taking.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="exam-taking-section" style="display: block;">
    <!-- Exam Header -->
    <div class="exam-header-bar">
        <div class="exam-info">
            <h2 class="exam-name">{{ $exam->title }}</h2>
            <div class="question-progress">
                @php
                    $answered = $current_attempt->answers()->count();
                    $total = count($current_attempt->question_order);
                    $percentage = $total > 0 ? ($answered / $total) * 100 : 0;
                @endphp
                {{ __('front.question_progress', ['current' => $answered, 'total' => $total]) }}
                <div class="progress-bar">
                    <div class="progress-fill" style="width: {{ $percentage }}%"></div>
                </div>
            </div>
        </div>

        <div class="exam-timer">
            <div class="timer-display">
                <i class="fas fa-clock"></i>
                <span id="timeDisplay">{{ __('front.loading') }}</span>
            </div>
            <div class="timer-warning" id="timeWarning" style="display: none;">
                <i class="fas fa-exclamation-triangle"></i>
                {{ __('front.time_warning') }}
            </div>
        </div>
    </div>

    <!-- Question Container -->
    <form id="answerForm" action="{{ route('answer.question', ['exam' => $exam->id, 'question' => $question->id]) }}" method="POST">
        @csrf
        <input type="hidden" name="page" value="{{ $question_nm }}">
        <input type="hidden" name="next_page" id="nextPageInput" value="{{ $question_nm + 1 }}">

        <div class="question-container">
            <div class="question-card">
                <div class="question-header">
                    <div class="question-type">
                        <i class="fas fa-question-circle"></i>
                        {{ $question->type ?? 'Question' }}
                    </div>
                    <div class="question-grade">
                        {{ $question->grade }} {{ __('front.question_point') }}
                    </div>
                </div>

                <div class="question-content">
                    @if($question->photo)
                        <div class="question-image">
                            <img src="{{ asset('assets/admin/uploads/' . $question->photo) }}" alt="{{ __('front.question_image') }}" />
                        </div>
                    @endif

                    <div class="question-text">
                        {!! nl2br(e($question->question)) !!}
                    </div>

                    <!-- Answer Input Area -->
                    <div class="answer-area">
                        @if($question->type === 'multiple_choice')
                            @include('web.exam.includes.question-types.multiple-choice', ['currentAnswer' => $previousAnswer])
                        @elseif($question->type === 'true_false')
                            @include('web.exam.includes.question-types.true-false', ['currentAnswer' => $previousAnswer])
                        @elseif($question->type === 'essay')
                            @include('web.exam.includes.question-types.essay', ['currentAnswer' => $previousAnswer])
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="exam-navigation">
            <div class="nav-buttons">
                @if($question_nm > 1)
                    <button type="button" class="btn-nav btn-prev" onclick="navigateToQuestion({{ $question_nm - 1 }})">
                        <i class="fas fa-chevron-right"></i>
                        {{ __('front.previous_question_btn') }}
                    </button>
                @endif

                @if($question_nm < $allQuestions->count())
                    <button type="submit" class="btn-nav btn-next" onclick="setNextPage({{ $question_nm + 1 }})">
                        {{ __('front.next_question_btn') }}
                        <i class="fas fa-chevron-left"></i>
                    </button>
                @else
                    <button type="button" class="btn-nav btn-submit" onclick="showSubmitConfirmation()">
                        <i class="fas fa-check"></i>
                        {{ __('front.finish_exam_btn') }}
                    </button>
                @endif
            </div>

        <!-- Question Navigator -->
        <div class="question-navigator">
            <h4>{{ __('front.question_navigator') }}</h4>
            <div class="questions-grid">
                @foreach($allQuestions as $index => $q)
                    @php
                        $isAnswered = $current_attempt->answers()->where('question_id', $q->id)->exists();
                    @endphp
                    <button type="button" class="question-btn {{ $index + 1 == $question_nm ? 'active' : '' }} {{ $isAnswered ? 'answered' : '' }}"
                            onclick="navigateQuestion({{ $index }})"
                            data-question="{{ $index }}">
                        {{ $index + 1 }}
                    </button>
                @endforeach
            </div>
        </div>
        </div>
    </form>

    <!-- Submit Confirmation Modal -->
    <div id="submitModal" class="exam-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>{{ __('front.confirm_finish_exam') }}</h3>
            </div>
            <div class="modal-body">
                <p>{{ __('front.are_you_sure_finish') }}</p>
                <p class="warning-text">{{ __('front.cannot_return_after_finish') }}</p>
            </div>
            <div class="modal-actions">
                <button class="btn-cancel" onclick="closeSubmitModal()">{{ __('front.cancel') }}</button>
                <form id="finishForm" action="{{ route('finish.exam', [$exam->id]) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-confirm" onclick="disableWarningBeforeSubmit()">{{ __('front.confirm_finish') }}</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Auto-save notification -->
    <div id="autoSaveNotification" class="auto-save-notification">
        <i class="fas fa-check"></i>
        {{ __('front.auto_save_notification') }}
    </div>
</div>

<style>
.exam-taking-section {
    padding: 20px;
    background: #f8f9fa;
    min-height: 100vh;
}

.exam-header-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: white;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.exam-info {
    flex: 1;
}

.exam-name {
    font-size: 24px;
    font-weight: bold;
    margin: 0 0 15px 0;
    color: #333;
}

.question-progress {
    font-size: 14px;
    color: #666;
    margin-bottom: 10px;
}

.progress-bar {
    width: 100%;
    height: 6px;
    background: #e0e0e0;
    border-radius: 3px;
    overflow: hidden;
    margin-top: 5px;
}

.progress-fill {
    height: 100%;
    background: #007bff;
    transition: width 0.3s;
}

.exam-timer {
    text-align: center;
    min-width: 200px;
}

.timer-display {
    font-size: 28px;
    font-weight: bold;
    color: #007bff;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.timer-display.timer-warning-active {
    color: #ff9800;
}

.timer-display.timer-critical {
    color: #dc3545;
}

.timer-warning {
    color: #dc3545;
    margin-top: 10px;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.question-container {
    background: white;
    border-radius: 8px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.question-card {
    display: flex;
    flex-direction: column;
}

.question-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e0e0e0;
}

.question-type {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 16px;
    font-weight: bold;
    color: #007bff;
}

.question-grade {
    background: #e7f3ff;
    padding: 8px 12px;
    border-radius: 20px;
    color: #007bff;
    font-weight: bold;
}

.question-image {
    margin-bottom: 20px;
}

.question-image img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
}

.question-text {
    font-size: 16px;
    line-height: 1.8;
    color: #333;
    margin-bottom: 30px;
    white-space: pre-wrap;
}

.answer-area {
    margin-top: 20px;
}

/* Multiple Choice Options Styling */
.multiple-choice-options {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
    margin-top: 20px;
}

.option-label {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 15px;
    border: 2px solid #ddd;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
}

.option-label:hover {
    border-color: #007bff;
    background: #f8f9ff;
}

.option-label input[type="radio"]:checked + .option-content {
    color: #007bff;
}

.option-label input[type="radio"]:checked {
    accent-color: #007bff;
}

.option-label input[type="radio"] {
    margin-top: 3px;
    cursor: pointer;
    width: 18px;
    height: 18px;
    flex-shrink: 0;
}

.option-content {
    display: flex;
    gap: 10px;
    align-items: flex-start;
    flex: 1;
}

.option-indicator {
    font-weight: bold;
    font-size: 16px;
    color: #007bff;
    min-width: 25px;
}

.option-text {
    font-size: 15px;
    line-height: 1.5;
    color: #333;
}

/* True/False Options */
.true-false-options {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-top: 20px;
}

.true-false-options .option-label {
    justify-content: center;
    text-align: center;
}

/* Essay Answer */
.essay-answer {
    margin-top: 20px;
}

.essay-answer textarea {
    width: 100%;
    min-height: 200px;
    padding: 12px;
    border: 2px solid #ddd;
    border-radius: 8px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 14px;
    resize: vertical;
    transition: border-color 0.3s;
}

.essay-answer textarea:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
}

.exam-navigation {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 30px;
}

.nav-buttons {
    display: flex;
    gap: 10px;
}

.btn-nav {
    flex: 1;
    padding: 12px 20px;
    border: 2px solid #007bff;
    background: white;
    color: #007bff;
    border-radius: 6px;
    font-weight: bold;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.3s;
}

.btn-nav:hover {
    background: #007bff;
    color: white;
}

.btn-nav.btn-submit {
    border-color: #28a745;
    color: #28a745;
}

.btn-nav.btn-submit:hover {
    background: #28a745;
    color: white;
}

.question-navigator {
    background: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.question-navigator h4 {
    margin: 0 0 15px 0;
    color: #333;
    font-size: 14px;
}

.questions-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 8px;
}

.question-btn {
    width: 100%;
    aspect-ratio: 1;
    border: 2px solid #ddd;
    background: white;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
    font-size: 12px;
    transition: all 0.3s;
}

.question-btn:hover {
    border-color: #007bff;
}

.question-btn.active {
    background: #007bff;
    color: white;
    border-color: #007bff;
}

.question-btn.answered {
    border-color: #28a745;
    background: #f0fdf4;
}

.exam-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    z-index: 1000;
    align-items: center;
    justify-content: center;
}

.exam-modal.show {
    display: flex;
}

.modal-content {
    background: white;
    border-radius: 12px;
    max-width: 500px;
    width: 90%;
    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
}

.modal-header {
    padding: 20px;
    border-bottom: 1px solid #e0e0e0;
}

.modal-header h3 {
    margin: 0;
    color: #333;
    font-size: 20px;
}

.modal-body {
    padding: 20px;
}

.modal-body p {
    margin: 0 0 10px 0;
    color: #666;
}

.warning-text {
    color: #dc3545;
    font-weight: bold;
}

.modal-actions {
    padding: 20px;
    border-top: 1px solid #e0e0e0;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

.btn-cancel,
.btn-confirm {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-cancel {
    background: #f0f0f0;
    color: #333;
}

.btn-cancel:hover {
    background: #e0e0e0;
}

.btn-confirm {
    background: #28a745;
    color: white;
}

.btn-confirm:hover {
    background: #218838;
}

.auto-save-notification {
    display: none;
    position: fixed;
    bottom: 30px;
    right: 30px;
    background: #28a745;
    color: white;
    padding: 15px 20px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: bold;
    z-index: 1001;
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        transform: translateX(400px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.loading-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    z-index: 999;
    align-items: center;
    justify-content: center;
}

.loading-content {
    background: white;
    padding: 30px;
    border-radius: 12px;
    text-align: center;
    display: flex;
    align-items: center;
    gap: 15px;
}

.loading-content i {
    font-size: 24px;
    color: #007bff;
}

@media (max-width: 768px) {
    .exam-header-bar {
        flex-direction: column;
        gap: 15px;
    }

    .exam-navigation {
        grid-template-columns: 1fr;
    }

    .nav-buttons {
        flex-direction: column;
    }

    .question-navigator {
        margin-top: 20px;
    }

    .questions-grid {
        grid-template-columns: repeat(4, 1fr);
    }

    .question-container {
        padding: 20px;
    }
}
</style>

<script>
// Global variables
const attemptId = {{ $current_attempt->id }};
const examId = {{ $exam->id }};
let currentQuestionId = {{ $question->id }};
const totalQuestions = {{ count($current_attempt->question_order) }};
let currentQuestionIndex = {{ $question_nm - 1 }};
let timeRemaining = {{ $remainingSeconds }};
let timerInterval;

// Route URLs
const routes = {
    takeExam: "{{ route('exam.take', $exam->id) }}"
};

// Track if we're in a safe navigation
let isSafeNavigation = false;

// Initialize exam when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeTimer();

    // Prevent page refresh/close without warning
    const handleBeforeUnload = function(e) {
        // Don't warn if we're doing safe navigation
        if (isSafeNavigation) {
            return;
        }
        e.preventDefault();
        e.returnValue = '{{ __("front.leave_exam_warning") }}';
    };

    window.addEventListener('beforeunload', handleBeforeUnload);

    // Mark when form is being submitted
    const answerForm = document.getElementById('answerForm');
    if (answerForm) {
        answerForm.addEventListener('submit', function() {
            isSafeNavigation = true;
        });
    }
});

// ==================== TIMER FUNCTIONS ====================

function initializeTimer() {
    updateTimeDisplay();

    if (timeRemaining > 0) {
        timerInterval = setInterval(function() {
            if (timeRemaining > 0) {
                timeRemaining--;
                updateTimeDisplay();
            } else {
                clearInterval(timerInterval);
                // Auto submit when time is up
                document.querySelector('form[action*="finish"]')?.submit();
            }
        }, 1000);
    }
}

function updateTimeDisplay() {
    const hours = Math.floor(timeRemaining / 3600);
    const minutes = Math.floor((timeRemaining % 3600) / 60);
    const seconds = timeRemaining % 60;

    let timeString = '';
    if (hours > 0) {
        timeString += hours.toString().padStart(2, '0') + ':';
    }
    timeString += minutes.toString().padStart(2, '0') + ':';
    timeString += seconds.toString().padStart(2, '0');

    const timeDisplay = document.getElementById('timeDisplay');
    if (timeDisplay) {
        timeDisplay.textContent = timeString;
    }

    // Change timer appearance when time is running low
    const timerElement = document.querySelector('.timer-display');
    if (timerElement) {
        if (timeRemaining <= 300) { // 5 minutes
            timerElement.classList.add('timer-warning-active');
        }
        if (timeRemaining <= 60) { // 1 minute
            timerElement.classList.add('timer-critical');
        }
    }
}

// ==================== NAVIGATION FUNCTIONS ====================

function navigateQuestion(questionIndex) {
    // Save current answer before navigating to another question
    const pageNumber = questionIndex + 1;
    document.getElementById('nextPageInput').value = pageNumber;
    isSafeNavigation = true;
    // Submit form to save answer, then navigate
    document.getElementById('answerForm').submit();
}

function navigateToQuestion(pageNumber) {
    // Mark as safe navigation to prevent beforeunload warning
    isSafeNavigation = true;
    // Submit form to save current answer before moving to next question
    document.getElementById('nextPageInput').value = pageNumber;
    document.getElementById('answerForm').submit();
}

function setNextPage(pageNumber) {
    // Set the next page value and submit form to save answer
    document.getElementById('nextPageInput').value = pageNumber;
    // Submit the form to save the answer before navigating
    isSafeNavigation = true;
    document.getElementById('answerForm').submit();
}

function disableWarningBeforeSubmit() {
    // Disable warning before submitting finish form
    isSafeNavigation = true;

    // First save the current answer, then submit finish form
    const answerForm = document.getElementById('answerForm');
    const finishForm = document.getElementById('finishForm');

    // Create a hidden form to save the answer first
    const saveAnswerForm = answerForm.cloneNode(true);

    // Use fetch to save answer and then submit finish form
    const formData = new FormData(answerForm);

    fetch(answerForm.action, {
        method: 'POST',
        body: formData
    }).then(response => {
        // After saving answer, submit the finish form
        finishForm.submit();
    }).catch(error => {
        // Even if there's an error, submit finish form
        console.error('Error saving answer:', error);
        finishForm.submit();
    });
}

// ==================== MODAL FUNCTIONS ====================

function showSubmitConfirmation() {
    const modal = document.getElementById('submitModal');
    if (modal) {
        modal.style.display = 'flex';

        setTimeout(() => {
            modal.classList.add('show');
        }, 10);

        document.body.style.overflow = 'hidden';
    }
}

function closeSubmitModal() {
    const modal = document.getElementById('submitModal');
    if (modal) {
        modal.classList.remove('show');

        setTimeout(() => {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }, 300);
    }
}

// ==================== EVENT LISTENERS ====================

document.addEventListener('click', function(e) {
    const modal = document.getElementById('submitModal');
    if (modal && e.target === modal) {
        closeSubmitModal();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('submitModal');
        if (modal && modal.classList.contains('show')) {
            closeSubmitModal();
        }
    }
});

// Remove beforeunload warning when submitting form
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function() {
        window.onbeforeunload = null;
    });
});
</script>
@endsection