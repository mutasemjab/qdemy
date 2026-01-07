{{-- resources/views/web/exam/exam-taking.blade.php --}}
@extends('layouts.app')

@php
    $queryParams = '';
    if (isset($isApi) && $isApi) {
        $queryParams = '?_mobile=1&_user_id=' . auth('user')->id();
    }
@endphp

@section('content')
    {{-- DEBUG: Student Info --}}
    <h2 style="color: #d9534f; padding: 15px; background: #f5f5f5; margin: 10px 0;">بيانات الطالب المسجل</h2>
    @dump(auth('user')->user())

    {{-- DEBUG: Session Data --}}
    <h2 style="color: #d9534f; padding: 15px; background: #f5f5f5; margin: 10px 0;">بيانات الجلسة الحالية</h2>
    @dump(session()->all())

    <div class="exam-taking-section" style="display: block;">
        <!-- Exam Header -->
        <div class="exam-header-bar">
            <div class="exam-info">
                <h2 class="exam-name">{{ $exam->title }}</h2>
                <div class="question-progress">
                    <span>{{ __('front.question_progress', ['current' => '0', 'total' => $allQuestions->count()]) }}</span>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 0%"></div>
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
        <div class="question-container">
            <div id="currentQuestionContainer">
                <!-- سيتم ملء هذا الـ div بواسطة JavaScript -->
            </div>
        </div>

        <!-- Navigation -->
        <div class="exam-navigation">
            <div class="nav-buttons">
                <button type="button" class="btn-nav btn-prev" onclick="goToPreviousQuestion()" id="prevBtn"
                    style="display: none;">
                    <i class="fas fa-chevron-right"></i>
                    {{ __('front.previous_question_btn') }}
                </button>

                <button type="button" class="btn-nav btn-next" onclick="goToNextQuestion()" id="nextBtn">
                    {{ __('front.next_question_btn') }}
                    <i class="fas fa-chevron-left"></i>
                </button>

                <button type="button" class="btn-nav btn-submit" onclick="showSubmitConfirmation()" id="submitBtn"
                    style="display: none;">
                    <i class="fas fa-check"></i>
                    {{ __('front.finish_exam_btn') }}
                </button>
            </div>

            <!-- Question Navigator -->
            <div class="question-navigator">
                <h4>{{ __('front.question_navigator') }}</h4>
                <div class="questions-grid">
                    <!-- سيتم ملؤها بواسطة JavaScript -->
                </div>
            </div>
        </div>
    </div>

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
                <form id="finishForm" action="{{ route($apiRoutePrefix . 'finish.exam', [$exam->id]) }}" method="POST"
                    style="display: inline;">
                    @csrf
                    @if ($isApi)
                        <input type="hidden" name="_mobile" value="1">
                        <input type="hidden" name="_user_id" value="{{ auth('user')->id() }}">
                    @endif
                    <button type="submit" class="btn-confirm"
                        onclick="disableWarningBeforeSubmit()">{{ __('front.confirm_finish') }}</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Auto-save notification -->
    <div id="autoSaveNotification" class="auto-save-notification">
        <i class="fas fa-check"></i>
        {{ __('front.auto_save_notification') }}
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
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
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
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
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

        .option-label input[type="radio"]:checked+.option-content {
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
            padding: 20px 20px 30px 20px;
        }

        .nav-buttons {
            display: flex;
            gap: 10px;
            padding: 0 10px;
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
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
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
            color: #1a1a1a;
        }

        .exam-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
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
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
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
        // ==================== EXAM DATA ====================

        const examData = {
            id: {{ $exam->id }},
            title: "{{ $exam->title }}",
            totalQuestions: {{ $allQuestions->count() }},
            remainingSeconds: {{ $remainingSeconds }},
            saveAnswerUrl: "{{ route($apiRoutePrefix . 'exam.save.answer.ajax', ['exam' => $exam->id]) . $queryParams }}",
            finishExamUrl: "{{ route($apiRoutePrefix . 'finish.exam', ['exam' => $exam->id]) . $queryParams }}",
            csrfToken: "{{ csrf_token() }}",
            isApi: {{ $isApi ? 'true' : 'false' }},
            userId: {{ auth('user')->id() ?? 'null' }}
        };


        // All questions with options
        const allQuestions = @json($allQuestions);

        // Saved answers (keyed by question_id)
        const savedAnswers = @json($savedAnswers);

        // Attempt data
        const attemptId = {{ $current_attempt->id }};

        // ==================== STATE MANAGEMENT ====================

        let currentState = {
            currentQuestionIndex: 0,
            answers: {},
            isSaving: false,
            examFinished: false
        };

        let timerInterval;

        // ==================== INITIALIZATION ====================

        function initializeAnswers() {
            if (!savedAnswers || typeof savedAnswers !== 'object') {
                return;
            }

            Object.keys(savedAnswers).forEach(questionId => {
                const saved = savedAnswers[questionId];
                const question = allQuestions.find(q => q.id == questionId);

                // Defensive checks
                if (!question) {
                    return;
                }

                if (!saved) {
                    return;
                }

                // Extract answer data based on question type
                let answerData;

                if (question.type === 'essay') {
                    answerData = saved.essay_answer;
                } else if (question.type === 'true_false') {
                    // True/False stores as array with single boolean value [true] or [false]
                    // Extract the first element
                    answerData = saved.selected_options && saved.selected_options.length > 0 ?
                        saved.selected_options[0] :
                        null;
                } else {
                    // Multiple choice - keep as array
                    answerData = saved.selected_options;
                }

                if (answerData !== null && answerData !== undefined && answerData !== '') {
                    currentState.answers[questionId] = {
                        type: question.type,
                        answer: answerData,
                        saved: true
                    };
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {

            initializeAnswers();
            renderQuestion(0);
            initializeTimer();

            // Prevent page refresh warning
            window.addEventListener('beforeunload', function(e) {
                if (!currentState.examFinished) {
                    e.preventDefault();
                    e.returnValue = '{{ __('front.leave_exam_warning') }}';
                }
            });
        });

        // ==================== TIMER ====================

        function initializeTimer() {
            updateTimeDisplay();

            if (examData.remainingSeconds > 0) {
                timerInterval = setInterval(function() {
                    if (examData.remainingSeconds > 0) {
                        examData.remainingSeconds--;
                        updateTimeDisplay();
                    } else {
                        clearInterval(timerInterval);
                        // Auto submit when time is up
                        document.getElementById('finishForm').submit();
                    }
                }, 1000);
            }
        }

        function updateTimeDisplay() {
            const hours = Math.floor(examData.remainingSeconds / 3600);
            const minutes = Math.floor((examData.remainingSeconds % 3600) / 60);
            const seconds = examData.remainingSeconds % 60;

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
                if (examData.remainingSeconds <= 300) {
                    timerElement.classList.add('timer-warning-active');
                }
                if (examData.remainingSeconds <= 60) {
                    timerElement.classList.add('timer-critical');
                }
            }
        }

        // ==================== RENDERING ====================

        // Helper function to escape HTML entities
        function escapeHtml(text) {
            if (!text || text === null || text === undefined) return '';
            const div = document.createElement('div');
            div.textContent = String(text);
            return div.innerHTML;
        }

        function getLocalizedValue(obj, arKey, enKey) {
            const locale = document.documentElement.lang || 'en';
            const value = locale === 'ar' ? obj[arKey] : obj[enKey];
            return value || obj[arKey] || obj[enKey] || '';
        }

        function renderQuestion(index) {
            if (index < 0 || index >= allQuestions.length) return;

            currentState.currentQuestionIndex = index;
            const question = allQuestions[index];

            // Defensive check: ensure question object has required properties
            if (!question || !question.id) {
                return;
            }

            const questionNumber = index + 1;
            const savedAnswer = currentState.answers[question.id];

            // Get localized question text with fallbacks
            const questionText = getLocalizedValue(question, 'question_ar', 'question_en');

            // Validate question text is not empty or undefined
            if (!questionText || questionText.trim() === '') {
                console.warn('Question text is empty for question:', question.id);
            }

            const questionType = question.type || 'unknown';
            const questionGrade = Number(question.grade) || 0;
            const photoUrl = question.photo ? `/assets/admin/uploads/${escapeHtml(String(question.photo))}` : '';

            let html = `
        <div class="question-card">
            <div class="question-header">
                <div class="question-type">
                    <i class="fas fa-question-circle"></i>
                    ${escapeHtml(String(questionType))}
                </div>
                <div class="question-grade">
                    ${questionGrade} {{ __('front.question_point') }}
                </div>
            </div>

            <div class="question-content">
                ${photoUrl ? `
                                        <div class="question-image">
                                            <img src="${photoUrl}" alt="Question Image" />
                                        </div>
                                    ` : ''}

                <div class="question-text">
                    ${escapeHtml(questionText) || '{{ __('front.question_text_not_available') }}'}
                </div>

                <div class="answer-area">
                    ${renderAnswerInput(question, savedAnswer)}
                </div>
            </div>
        </div>
    `;

            document.getElementById('currentQuestionContainer').innerHTML = html;
            attachAnswerListeners(question);
            updateProgress();
            updateQuestionNavigator();
            updateNavigationButtons();
        }

        function renderAnswerInput(question, savedAnswer) {
            if (question.type === 'multiple_choice') {
                return renderMultipleChoice(question, savedAnswer);
            } else if (question.type === 'true_false') {
                return renderTrueFalse(question, savedAnswer);
            } else if (question.type === 'essay') {
                return renderEssay(question, savedAnswer);
            }
        }

        function renderMultipleChoice(question, savedAnswer) {
            const selectedOptions = savedAnswer?.answer || [];

            let html = '<div class="multiple-choice-options">';

            if (!question.options || question.options.length === 0) {
                html +=
                    '<p style="color: #999; text-align: center; padding: 20px;">{{ __('front.no_options_available') }}</p>';
            } else {
                question.options.forEach((option, index) => {
                    // Defensive checks
                    if (!option || !option.id) return;

                    // Normalize comparison: convert both to strings for reliable comparison
                    const optionIdStr = String(option.id);
                    const isChecked = Array.isArray(selectedOptions) && selectedOptions.some(id => String(id) ===
                        optionIdStr);

                    const letter = String.fromCharCode(65 + index);
                    const optionText = getLocalizedValue(option, 'option_ar', 'option_en');

                    if (!optionText) {
                        return;
                    }

                    html += `
                <label class="option-label">
                    <input type="radio"
                           name="answer_${question.id}"
                           value="${escapeHtml(String(option.id))}"
                           class="option-radio"
                           data-question-id="${question.id}"
                           ${isChecked ? 'checked' : ''}>
                    <div class="option-content">
                        <div class="option-indicator">${letter}</div>
                        <div class="option-text">${escapeHtml(optionText)}</div>
                    </div>
                </label>
            `;
                });
            }

            html += '</div>';
            return html;
        }

        function renderTrueFalse(question, savedAnswer) {
            // Get the answer value - could be boolean true/false or string 'true'/'false'
            let selectedAnswer = savedAnswer?.answer;

            // Normalize the value to actual boolean
            let isTrue = false;
            let isFalse = false;

            if (selectedAnswer !== null && selectedAnswer !== undefined) {
                if (selectedAnswer === true || selectedAnswer === 'true' || selectedAnswer === 1 || selectedAnswer ===
                    '1') {
                    isTrue = true;
                } else if (selectedAnswer === false || selectedAnswer === 'false' || selectedAnswer === 0 ||
                    selectedAnswer === '0') {
                    isFalse = true;
                }
            }

            return `
        <div class="true-false-options">
            <label class="option-label">
                <input type="radio"
                       name="answer_${question.id}"
                       value="true"
                       data-question-id="${question.id}"
                       ${isTrue ? 'checked' : ''}>
                <div class="option-content">
                    <div class="option-text">{{ __('front.true') }}</div>
                </div>
            </label>

            <label class="option-label">
                <input type="radio"
                       name="answer_${question.id}"
                       value="false"
                       data-question-id="${question.id}"
                       ${isFalse ? 'checked' : ''}>
                <div class="option-content">
                    <div class="option-text">{{ __('front.false') }}</div>
                </div>
            </label>
        </div>
    `;
        }

        function renderEssay(question, savedAnswer) {
            const text = savedAnswer?.answer || '';

            // Escape HTML entities to prevent any display issues
            const escapedText = escapeHtml(text);

            return `
        <div class="essay-answer">
            <textarea
                id="essay_${question.id}"
                data-question-id="${question.id}"
                class="essay-textarea"
                placeholder="{{ __('front.write_your_answer') }}"
                rows="10"
            >${escapedText}</textarea>
        </div>
    `;
        }

        // ==================== EVENT LISTENERS ====================

        function attachAnswerListeners(question) {
            if (question.type === 'multiple_choice' || question.type === 'true_false') {
                const radios = document.querySelectorAll(`input[name="answer_${question.id}"]`);
                radios.forEach(radio => {
                    radio.addEventListener('change', function() {
                        handleAnswerChange(question, this.value);
                    });
                });
            } else if (question.type === 'essay') {
                const textarea = document.getElementById(`essay_${question.id}`);

                let debounceTimer;
                textarea.addEventListener('input', function() {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        handleAnswerChange(question, this.value);
                    }, 1000);
                });
            }
        }

        function handleAnswerChange(question, answer) {
            let processedAnswer;

            if (question.type === 'multiple_choice') {
                processedAnswer = [parseInt(answer)];
            } else if (question.type === 'true_false') {
                processedAnswer = answer === 'true';
            } else {
                processedAnswer = answer;
            }

            currentState.answers[question.id] = {
                type: question.type,
                answer: processedAnswer,
                saved: false
            };

            saveAnswerToBackend(question.id, question.type, processedAnswer);
            updateProgress();
        }

        // ==================== AJAX SAVE ====================

        async function saveAnswerToBackend(questionId, answerType, answer) {
            try {
                if (currentState.isSaving) {
                    return;
                }

                currentState.isSaving = true;

                const formData = new FormData();
                formData.append('_token', examData.csrfToken);
                @if ($isApi)
                    formData.append('_mobile', '1');
                    formData.append('_user_id', '{{ auth('user')->id() }}');
                @endif
                formData.append('question_id', questionId);
                formData.append('answer_type', answerType);

                if (answerType === 'essay') {
                    formData.append('answer', answer);
                } else if (answerType === 'true_false') {
                    formData.append('answer', answer);
                } else {
                    if (Array.isArray(answer)) {
                        answer.forEach(opt => formData.append('answer[]', opt));
                    }
                }



                const response = await fetch(examData.saveAnswerUrl, {
                    method: 'POST',
                    headers: new Headers({
                        'Accept': 'application/json'
                    }),
                    body: formData,
                    credentials: 'same-origin'
                });

                let contentType = '';
                try {
                    contentType = response.headers ? response.headers.get('content-type') : '';
                } catch (e) {
                    contentType = '';
                }



                // Check if response is HTML (error/redirect)
                if (contentType && contentType.includes('text/html')) {
                    const htmlContent = await response.text();

                    return;
                }

                const data = await response.json();


                if (data.success) {
                    currentState.answers[questionId].saved = true;
                    showAutoSaveNotification();
                } else {

                    if (data.expired) {
                        window.location.reload();
                    }
                }

            } catch (error) {
                console.error(error);

            } finally {
                currentState.isSaving = false;
            }
        }

        function showAutoSaveNotification() {
            const notification = document.getElementById('autoSaveNotification');
            notification.style.display = 'flex';
            setTimeout(() => {
                notification.style.display = 'none';
            }, 2000);
        }

        // ==================== NAVIGATION ====================

        function navigateToQuestion(index) {
            if (index < 0 || index >= allQuestions.length) return;
            renderQuestion(index);
        }

        function goToNextQuestion() {
            const nextIndex = currentState.currentQuestionIndex + 1;
            if (nextIndex < allQuestions.length) {
                navigateToQuestion(nextIndex);
            }
        }

        function goToPreviousQuestion() {
            const prevIndex = currentState.currentQuestionIndex - 1;
            if (prevIndex >= 0) {
                navigateToQuestion(prevIndex);
            }
        }

        // ==================== PROGRESS & NAVIGATOR ====================

        function updateProgress() {
            const answeredCount = Object.keys(currentState.answers).length;
            const totalQuestions = allQuestions.length;
            const percentage = (answeredCount / totalQuestions) * 100;

            const progressContainer = document.querySelector('.question-progress');
            if (progressContainer) {
                progressContainer.innerHTML = `
            <span>${answeredCount} / ${totalQuestions}</span>
            <div class="progress-bar">
                <div class="progress-fill" style="width: ${percentage}%"></div>
            </div>
        `;
            }
        }

        function updateQuestionNavigator() {
            const container = document.querySelector('.questions-grid');
            let html = '';

            allQuestions.forEach((q, index) => {
                const isActive = index === currentState.currentQuestionIndex;
                const isAnswered = currentState.answers[q.id] !== undefined;

                html += `
            <button type="button"
                    class="question-btn ${isActive ? 'active' : ''} ${isAnswered ? 'answered' : ''}"
                    onclick="navigateToQuestion(${index})">
                ${index + 1}
            </button>
        `;
            });

            container.innerHTML = html;
        }

        function updateNavigationButtons() {
            const isFirst = currentState.currentQuestionIndex === 0;
            const isLast = currentState.currentQuestionIndex === allQuestions.length - 1;

            document.getElementById('prevBtn').style.display = isFirst ? 'none' : 'flex';
            document.getElementById('nextBtn').style.display = isLast ? 'none' : 'flex';
            document.getElementById('submitBtn').style.display = isLast ? 'flex' : 'none';
        }

        // ==================== SUBMIT MODAL ====================

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

        function disableWarningBeforeSubmit() {
            currentState.examFinished = true;
            clearInterval(timerInterval);
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

        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                window.onbeforeunload = null;
            });
        });
    </script>
@endsection
