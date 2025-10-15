{{-- sections/exam-taking.blade.php --}}
@extends('layouts.front')

@section('content')
<div class="exam-taking-section" style="display: block;">
    <!-- Exam Header -->
    <div class="exam-header-bar">
        <div class="exam-info">
            <h2 class="exam-name">{{ $attempt->exam->name }}</h2>
            <div class="question-progress">
                السؤال {{ $progress['current'] }} من {{ $progress['total'] }}
                <div class="progress-bar">
                    <div class="progress-fill" style="width: {{ $progress['percentage'] }}%"></div>
                </div>
            </div>
        </div>
        
        <div class="exam-timer">
            <div class="timer-display">
                <i class="fas fa-clock"></i>
                <span id="timeDisplay">جاري التحميل...</span>
            </div>
            <div class="timer-warning" id="timeWarning" style="display: none;">
                <i class="fas fa-exclamation-triangle"></i>
                الوقت على وشك الانتهاء!
            </div>
        </div>
    </div>

    <!-- Question Container -->
    <div class="question-container">
        <div class="question-card">
            <div class="question-header">
                <div class="question-type">
                    <i class="fas fa-question-circle"></i>
                    {{ $currentQuestion->type_name }}
                </div>
                <div class="question-grade">
                    {{ $currentQuestion->grade }} نقطة
                </div>
            </div>

            <div class="question-content">
                @if($currentQuestion->question_image)
                    <div class="question-image">
                        <img src="{{ $currentQuestion->question_image_url }}" alt="صورة السؤال" />
                    </div>
                @endif

                <div class="question-text">
                    {!! nl2br(e($currentQuestion->question_text)) !!}
                </div>

                <!-- Answer Input Area -->
                <div class="answer-area">
                    @if($currentQuestion->type == 'multiple_choice')
                        @include('includes.question-types.multiple-choice')
                    @elseif($currentQuestion->type == 'true_false')
                        @include('includes.question-types.true-false')
                    @elseif($currentQuestion->type == 'essay')
                        @include('includes.question-types.essay')
                    @elseif($currentQuestion->type == 'fill_blank')
                        @include('includes.question-types.fill-blank')
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="exam-navigation">
        <div class="nav-buttons">
            @if($currentQuestionIndex > 0)
                <button class="btn-nav btn-prev" onclick="navigateQuestion({{ $currentQuestionIndex - 1 }})">
                    <i class="fas fa-chevron-right"></i>
                    السؤال السابق
                </button>
            @endif

            @if($currentQuestionIndex < $questions->count() - 1)
                <button class="btn-nav btn-next" onclick="navigateQuestion({{ $currentQuestionIndex + 1 }})">
                    السؤال التالي
                    <i class="fas fa-chevron-left"></i>
                </button>
            @else
                <button class="btn-nav btn-submit" onclick="showSubmitConfirmation()">
                    <i class="fas fa-check"></i>
                    إنهاء الامتحان
                </button>
            @endif
        </div>

        <!-- Question Navigator -->
        <div class="question-navigator">
            <h4>خريطة الأسئلة</h4>
            <div class="questions-grid">
                @foreach($questions as $index => $question)
                    <button class="question-btn {{ $index == $currentQuestionIndex ? 'active' : '' }}" 
                            onclick="navigateQuestion({{ $index }})"
                            data-question="{{ $index }}">
                        {{ $index + 1 }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Submit Confirmation Modal -->
    <div id="submitModal" class="exam-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>تأكيد إنهاء الامتحان</h3>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من إنهاء الامتحان؟</p>
                <p class="warning-text">لن تتمكن من العودة لتعديل إجاباتك بعد الإنهاء.</p>
            </div>
            <div class="modal-actions">
                <button class="btn-cancel" onclick="closeSubmitModal()">إلغاء</button>
                <form action="{{ route('exam.submit', [$attempt->exam_id, $attempt->id]) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-confirm">تأكيد الإنهاء</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Auto-save notification -->
    <div id="autoSaveNotification" class="auto-save-notification">
        <i class="fas fa-check"></i>
        تم حفظ الإجابة تلقائياً
    </div>
</div>
<script>
// Global variables
const attemptId = {{ $attempt->id }};
const examId = {{ $attempt->exam_id }};
let currentQuestionId = {{ $currentQuestion->id }};
const totalQuestions = {{ $questions->count() }};
let currentQuestionIndex = {{ $currentQuestionIndex }};
let timeRemaining = 0;
let timerInterval;
let autoSaveTimeout;

// Route URLs - Using Laravel named routes
const routes = {
    timeRemaining: "{{ route('exam.time-remaining', $attempt->id) }}",
    saveAnswer: "{{ route('exam.save-answer', [$attempt->exam_id, $attempt->id]) }}",
    takeExam: "{{ route('exam.take', [$attempt->exam_id, $attempt->id]) }}"
};

// Initialize exam when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeTimer();
    loadPreviousAnswer();
    setupAutoSave();
    
    // Prevent page refresh/close without warning
    window.addEventListener('beforeunload', function(e) {
        e.preventDefault();
        e.returnValue = 'هل أنت متأكد من مغادرة الامتحان؟ قد تفقد إجاباتك غير المحفوظة.';
    });
});

// ==================== TIMER FUNCTIONS ====================

function initializeTimer() {
    updateTimeDisplay();
    
    timerInterval = setInterval(function() {
        fetchTimeRemaining();
    }, 1000);
}

function fetchTimeRemaining() {
    fetch(routes.timeRemaining)
        .then(response => response.json())
        .then(data => {
            if (data.timeUp) {
                clearInterval(timerInterval);
                alert('انتهى وقت الامتحان! سيتم إرسال إجاباتك تلقائياً.');
                window.location.href = data.redirect;
                return;
            }
            
            timeRemaining = data.remainingSeconds;
            updateTimeDisplay();
            
            // Show warning when 5 minutes left
            if (timeRemaining <= 300 && timeRemaining > 0) {
                const timeWarning = document.getElementById('timeWarning');
                if (timeWarning) {
                    timeWarning.style.display = 'block';
                }
            }
        })
        .catch(error => {
            console.error('Timer error:', error);
        });
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

// ==================== NAVIGATION FUNCTIONS (AJAX) ====================

function navigateQuestion(questionIndex) {
    // Save current answer before navigating
    saveCurrentAnswer(() => {
        loadQuestion(questionIndex);
    });
}

function loadQuestion(questionIndex) {
    // Show loading indicator
    showLoadingIndicator();
    
    // Fetch new question via AJAX
    fetch(`${routes.takeExam}?q=${questionIndex}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.timeUp) {
            clearInterval(timerInterval);
            alert('انتهى وقت الامتحان! سيتم إرسال إجاباتك تلقائياً.');
            window.location.href = data.redirect;
            return;
        }
        
        if (data.success) {
            // Update question content
            updateQuestionContent(data);
            
            // Update current question index and ID
            currentQuestionIndex = questionIndex;
            currentQuestionId = data.question.id;
            
            // Update progress bar
            updateProgressBar(data.progress);
            
            // Update navigation buttons
            updateNavigationButtons(questionIndex);
            
            // Update question navigator
            updateQuestionNavigator(questionIndex);
            
            // Load previous answer for this question
            loadAnswerForQuestion(data.previousAnswer);
            
            // Update URL without page reload
            updateURL(questionIndex);
            
        } else {
            console.error('Failed to load question:', data.message);
            alert('حدث خطأ في تحميل السؤال. يرجى المحاولة مرة أخرى.');
        }
    })
    .catch(error => {
        console.error('Error loading question:', error);
        alert('حدث خطأ في الاتصال. يرجى المحاولة مرة أخرى.');
    })
    .finally(() => {
        hideLoadingIndicator();
    });
}

function updateQuestionContent(data) {
    const questionCard = document.querySelector('.question-card');
    if (!questionCard) return;
    
    // Update question header
    const questionType = questionCard.querySelector('.question-type');
    if (questionType) {
        questionType.innerHTML = `<i class="fas fa-question-circle"></i> ${data.question.type_name}`;
    }
    
    const questionGrade = questionCard.querySelector('.question-grade');
    if (questionGrade) {
        questionGrade.textContent = `${data.question.grade} نقطة`;
    }
    
    // Update question image
    const existingImage = questionCard.querySelector('.question-image');
    if (existingImage) {
        existingImage.remove();
    }
    
    if (data.question.question_image_url) {
        const imageDiv = document.createElement('div');
        imageDiv.className = 'question-image';
        imageDiv.innerHTML = `<img src="${data.question.question_image_url}" alt="صورة السؤال" />`;
        
        const questionText = questionCard.querySelector('.question-text');
        questionText.parentNode.insertBefore(imageDiv, questionText);
    }
    
    // Update question text
    const questionText = questionCard.querySelector('.question-text');
    if (questionText) {
        questionText.innerHTML = data.question.question_text.replace(/\n/g, '<br>');
    }
    
    // Update answer area
    const answerArea = questionCard.querySelector('.answer-area');
    if (answerArea) {
        answerArea.innerHTML = data.answerHtml;
        
        // Re-setup auto-save for new inputs
        setupAutoSaveForNewInputs();
    }
}

function updateProgressBar(progress) {
    const progressText = document.querySelector('.question-progress');
    if (progressText) {
        progressText.innerHTML = `
            السؤال ${progress.current} من ${progress.total}
            <div class="progress-bar">
                <div class="progress-fill" style="width: ${progress.percentage}%"></div>
            </div>
        `;
    }
}

function updateNavigationButtons(questionIndex) {
    const navButtons = document.querySelector('.nav-buttons');
    if (!navButtons) return;
    
    let buttonsHtml = '';
    
    // Previous button
    if (questionIndex > 0) {
        buttonsHtml += `
            <button class="btn-nav btn-prev" onclick="navigateQuestion(${questionIndex - 1})">
                <i class="fas fa-chevron-right"></i>
                السؤال السابق
            </button>
        `;
    }
    
    // Next/Submit button
    if (questionIndex < totalQuestions - 1) {
        buttonsHtml += `
            <button class="btn-nav btn-next" onclick="navigateQuestion(${questionIndex + 1})">
                السؤال التالي
                <i class="fas fa-chevron-left"></i>
            </button>
        `;
    } else {
        buttonsHtml += `
            <button class="btn-nav btn-submit" onclick="showSubmitConfirmation()">
                <i class="fas fa-check"></i>
                إنهاء الامتحان
            </button>
        `;
    }
    
    navButtons.innerHTML = buttonsHtml;
}

function updateQuestionNavigator(activeIndex) {
    const questionBtns = document.querySelectorAll('.question-btn');
    questionBtns.forEach((btn, index) => {
        btn.classList.remove('active');
        if (index === activeIndex) {
            btn.classList.add('active');
        }
    });
}

function loadAnswerForQuestion(previousAnswer) {
    if (previousAnswer && previousAnswer.user_answer) {
        const answer = previousAnswer.user_answer;
        const questionType = getCurrentQuestionType();
        
        // Small delay to ensure DOM is updated
        setTimeout(() => {
            if (questionType === 'multiple_choice' || questionType === 'true_false') {
                // For multiple choice and true/false, user_answer is stored as the actual value
                const radio = document.querySelector(`input[value="${answer}"]`);
                if (radio) {
                    radio.checked = true;
                }
            } else if (questionType === 'essay') {
                const essayAnswer = document.getElementById('essayAnswer');
                if (essayAnswer) {
                    essayAnswer.value = answer || '';
                }
            } else if (questionType === 'fill_blank') {
                const fillBlankAnswer = document.getElementById('fillBlankAnswer');
                if (fillBlankAnswer) {
                    fillBlankAnswer.value = answer || '';
                }
            }
        }, 100);
    }
}

// Update the loadPreviousAnswer function as well
function loadPreviousAnswer() {
    @if($previousAnswer && $previousAnswer->user_answer)
        const answer = @json($previousAnswer->user_answer);
        const questionType = getCurrentQuestionType();
        
        console.log('Loading previous answer:', answer, 'for type:', questionType);
        
        if (questionType === 'multiple_choice' || questionType === 'true_false') {
            // For both multiple choice and true/false, user_answer is now stored as the actual value
            const radio = document.querySelector(`input[value="${answer}"]`);
            if (radio) {
                radio.checked = true;
            }
        } else if (questionType === 'essay') {
            const essayAnswer = document.getElementById('essayAnswer');
            if (essayAnswer) {
                essayAnswer.value = answer || '';
            }
        } else if (questionType === 'fill_blank') {
            const fillBlankAnswer = document.getElementById('fillBlankAnswer');
            if (fillBlankAnswer) {
                fillBlankAnswer.value = answer || '';
            }
        }
    @endif
}


function getCurrentQuestionType() {
    const questionTypeElement = document.querySelector('.question-type');
    if (!questionTypeElement) return null;
    
    const typeText = questionTypeElement.textContent.trim();
    
    if (typeText.includes('اختيار متعدد')) return 'multiple_choice';
    if (typeText.includes('صواب وخطأ')) return 'true_false';
    if (typeText.includes('مقالي')) return 'essay';
    if (typeText.includes('ملء الفراغات')) return 'fill_blank';
    
    return null;
}

function setupAutoSaveForNewInputs() {
    // Remove old event listeners and add new ones for dynamically loaded content
    const inputs = document.querySelectorAll('.answer-area input, .answer-area textarea');
    inputs.forEach(input => {
        // Add new listeners
        input.addEventListener('change', autoSaveHandler);
        
        if (input.tagName.toLowerCase() === 'textarea') {
            input.addEventListener('input', autoSaveInputHandler);
        }
    });
}

function autoSaveHandler() {
    clearTimeout(autoSaveTimeout);
    autoSaveTimeout = setTimeout(() => {
        saveCurrentAnswer();
    }, 2000);
}

function autoSaveInputHandler() {
    clearTimeout(autoSaveTimeout);
    autoSaveTimeout = setTimeout(() => {
        saveCurrentAnswer();
    }, 5000);
}

function updateURL(questionIndex) {
    // Update URL without page reload
    const newUrl = `${routes.takeExam}?q=${questionIndex}`;
    window.history.replaceState({}, '', newUrl);
}

function showLoadingIndicator() {
    // Create or show loading overlay
    let loadingOverlay = document.getElementById('loadingOverlay');
    if (!loadingOverlay) {
        loadingOverlay = document.createElement('div');
        loadingOverlay.id = 'loadingOverlay';
        loadingOverlay.className = 'loading-overlay';
        loadingOverlay.innerHTML = `
            <div class="loading-content">
                <i class="fas fa-spinner fa-spin"></i>
                <span>جاري تحميل السؤال...</span>
            </div>
        `;
        document.body.appendChild(loadingOverlay);
    }
    loadingOverlay.style.display = 'flex';
}

function hideLoadingIndicator() {
    const loadingOverlay = document.getElementById('loadingOverlay');
    if (loadingOverlay) {
        loadingOverlay.style.display = 'none';
    }
}

// ==================== ANSWER SAVING FUNCTIONS ====================

function saveCurrentAnswer(callback) {
    const answer = getCurrentAnswer();
    
    fetch(routes.saveAnswer, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            question_id: currentQuestionId,
            answer: answer
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.timeUp) {
            clearInterval(timerInterval);
            alert('انتهى وقت الامتحان!');
            window.location.href = data.redirect;
            return;
        }
        
        if (data.success) {
            showAutoSaveNotification();
            updateQuestionStatus(currentQuestionIndex, 'answered');
            
            // Execute callback if provided
            if (callback && typeof callback === 'function') {
                callback();
            }
        }
    })
    .catch(error => {
        console.error('Save error:', error);
        
        // Still execute callback even if save failed
        if (callback && typeof callback === 'function') {
            callback();
        }
    });
}

function getCurrentAnswer() {
    const questionType = getCurrentQuestionType();
    
    if (questionType === 'multiple_choice' || questionType === 'true_false') {
        const selected = document.querySelector('input[name="answer"]:checked');
        return selected ? selected.value : null;
    } else if (questionType === 'essay') {
        const essayAnswer = document.getElementById('essayAnswer');
        return essayAnswer ? essayAnswer.value : null;
    } else if (questionType === 'fill_blank') {
        const fillBlankAnswer = document.getElementById('fillBlankAnswer');
        return fillBlankAnswer ? fillBlankAnswer.value : null;
    }
    
    return null;
}

function setupAutoSave() {
    // Auto-save every 30 seconds
    setInterval(function() {
        saveCurrentAnswer();
    }, 30000);
    
    // Save on answer change with debouncing
    const inputs = document.querySelectorAll('input, textarea');
    inputs.forEach(input => {
        input.addEventListener('change', autoSaveHandler);
        
        // For textarea, also listen to input events for real-time saving
        if (input.tagName.toLowerCase() === 'textarea') {
            input.addEventListener('input', autoSaveInputHandler);
        }
    });
}



// ==================== MODAL FUNCTIONS ====================

function showSubmitConfirmation() {
    const modal = document.getElementById('submitModal');
    if (modal) {
        modal.style.display = 'flex';
        
        // Add animation class after a brief delay
        setTimeout(() => {
            modal.classList.add('show');
        }, 10);
        
        // Prevent body scroll when modal is open
        document.body.style.overflow = 'hidden';
    }
}

function closeSubmitModal() {
    const modal = document.getElementById('submitModal');
    if (modal) {
        modal.classList.remove('show');
        
        // Hide modal after animation completes
        setTimeout(() => {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }, 300);
    }
}

// ==================== UTILITY FUNCTIONS ====================

function showAutoSaveNotification() {
    const notification = document.getElementById('autoSaveNotification');
    if (notification) {
        notification.style.display = 'block';
        
        setTimeout(() => {
            notification.style.display = 'none';
        }, 3000);
    }
}

function updateQuestionStatus(questionIndex, status) {
    const questionBtn = document.querySelector(`[data-question="${questionIndex}"]`);
    if (questionBtn) {
        questionBtn.classList.remove('answered', 'current');
        
        if (status === 'answered') {
            questionBtn.classList.add('answered');
        }
        
        if (questionIndex === currentQuestionIndex) {
            questionBtn.classList.add('current');
        }
    }
}

// ==================== EVENT LISTENERS ====================

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('submitModal');
    if (modal && e.target === modal) {
        closeSubmitModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('submitModal');
        if (modal && modal.classList.contains('show')) {
            closeSubmitModal();
        }
    }
});

// Handle form submission to prevent accidental page leave warning
document.addEventListener('submit', function(e) {
    // Remove the beforeunload warning when submitting the exam
    window.removeEventListener('beforeunload', window.beforeUnloadHandler);
});

// Store the beforeunload handler for easy removal
window.beforeUnloadHandler = function(e) {
    e.preventDefault();
    e.returnValue = 'هل أنت متأكد من مغادرة الامتحان؟ قد تفقد إجاباتك غير المحفوظة.';
};

// ==================== DEBUGGING FUNCTIONS ====================

function debugExamState() {
    console.log('Exam Debug Info:', {
        attemptId: attemptId,
        examId: examId,
        currentQuestionId: currentQuestionId,
        currentQuestionIndex: currentQuestionIndex,
        totalQuestions: totalQuestions,
        timeRemaining: timeRemaining,
        currentAnswer: getCurrentAnswer()
    });
}

// Make debug function available globally
window.debugExam = debugExamState;
</script>
@endsection