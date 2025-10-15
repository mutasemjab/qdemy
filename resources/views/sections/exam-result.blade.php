{{-- sections/exam-result.blade.php --}}
@extends('layouts.front')

@section('content')
<div class="exam-result-section" style="display: block;">
    <div class="result-container">
        <!-- Result Header -->
        <div class="result-header">
            <div class="result-icon {{ $attempt->isPassed() ? 'success' : 'failure' }}">
                @if($attempt->isPassed())
                    <i class="fas fa-trophy"></i>
                @else
                    <i class="fas fa-times-circle"></i>
                @endif
            </div>
            
            <h1 class="result-title">
                @if($attempt->isPassed())
                    مبروك! لقد نجحت في الامتحان
                @else
                    للأسف، لم تحقق درجة النجاح
                @endif
            </h1>
            
            <div class="exam-name">{{ $attempt->exam->name }}</div>
        </div>

        <!-- Score Summary -->
        <div class="score-summary">
            <div class="score-circle">
                <div class="score-progress" data-percentage="{{ $attempt->percentage }}">
                    <div class="score-value">
                        <span class="percentage">{{ number_format($attempt->percentage, 1) }}%</span>
                        <span class="fraction">{{ $attempt->score }} / {{ $attempt->exam->total_grade }}</span>
                    </div>
                </div>
            </div>
            
            <div class="score-details">
                <div class="score-item">
                    <span class="label">درجتك:</span>
                    <span class="value">{{ $attempt->score }} نقطة</span>
                </div>
                <div class="score-item">
                    <span class="label">النسبة المئوية:</span>
                    <span class="value">{{ number_format($attempt->percentage, 1) }}%</span>
                </div>
                <div class="score-item">
                    <span class="label">درجة النجاح:</span>
                    <span class="value">{{ $attempt->exam->pass_grade }} نقطة</span>
                </div>
                <div class="score-item">
                    <span class="label">الحالة:</span>
                    <span class="value {{ $attempt->isPassed() ? 'passed' : 'failed' }}">
                        {{ $attempt->isPassed() ? 'ناجح' : 'راسب' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Exam Statistics -->
        <div class="exam-statistics">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-list"></i>
                </div>
                <div class="stat-content">
                    <h3>إجمالي الأسئلة</h3>
                    <p>{{ $attempt->questionAnswers->count() }} سؤال</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon correct">
                    <i class="fas fa-check"></i>
                </div>
                <div class="stat-content">
                    <h3>إجابات صحيحة</h3>
                    <p>{{ $attempt->questionAnswers->where('is_correct', true)->count() }} سؤال</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon incorrect">
                    <i class="fas fa-times"></i>
                </div>
                <div class="stat-content">
                    <h3>إجابات خاطئة</h3>
                    <p>{{ $attempt->questionAnswers->where('is_correct', false)->count() }} سؤال</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3>وقت الإنجاز</h3>
                    <p>
                        @if($attempt->submitted_at)
                            {{ $attempt->started_at->diffInMinutes($attempt->submitted_at) }} دقيقة
                        @else
                            {{ $attempt->exam->duration_minutes }} دقيقة (انتهى الوقت)
                        @endif
                    </p>
                </div>
            </div>
        </div>

       {{-- Update the detailed results section in exam-result.blade.php --}}
        @if($attempt->exam->show_results_immediately)
            <!-- Detailed Results -->
            <div class="detailed-results">
                <h2><i class="fas fa-list-ul"></i> تفاصيل الإجابات</h2>
                
                <div class="questions-review">
                    @foreach($attempt->questionAnswers as $index => $questionAnswer)
                        <div class="question-review {{ $questionAnswer->is_correct ? 'correct' : ($questionAnswer->is_correct === false ? 'incorrect' : 'ungraded') }}">
                            <div class="question-header">
                                <div class="question-number">
                                    السؤال {{ $index + 1 }}
                                    @if($questionAnswer->is_correct)
                                        <i class="fas fa-check-circle correct"></i>
                                    @elseif($questionAnswer->is_correct === false)
                                        <i class="fas fa-times-circle incorrect"></i>
                                    @else
                                        <i class="fas fa-question-circle pending"></i>
                                    @endif
                                </div>
                                <div class="question-grade">
                                    {{ $questionAnswer->awarded_grade }} / {{ $questionAnswer->question->grade }} نقطة
                                </div>
                            </div>

                            <div class="question-content">
                                <p class="question-text">{{ $questionAnswer->question->question_text }}</p>
                                
                                @if($questionAnswer->question->type == 'multiple_choice')
                                    <div class="user-answer">
                                        <strong>إجابتك:</strong> 
                                        @if($questionAnswer->user_answer !== null && $questionAnswer->user_answer !== '')
                                            {{ $questionAnswer->user_answer }}
                                        @else
                                            <span class="no-answer">لم تجب على هذا السؤال</span>
                                        @endif
                                    </div>
                                    
                                    @if($questionAnswer->question->correct_answers && is_array($questionAnswer->question->correct_answers))
                                        <div class="correct-answer">
                                            <strong>الإجابة الصحيحة:</strong>
                                            @foreach($questionAnswer->question->correct_answers as $correctIndex)
                                                @if(is_array($questionAnswer->question->options) && isset($questionAnswer->question->options[$correctIndex]))
                                                    {{ $questionAnswer->question->options[$correctIndex] }}
                                                    @if(!$loop->last), @endif
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                    
                                @elseif($questionAnswer->question->type == 'true_false')
                                    <div class="user-answer">
                                        <strong>إجابتك:</strong> 
                                        @if($questionAnswer->user_answer !== null && $questionAnswer->user_answer !== '')
                                            {{ $questionAnswer->user_answer == 'true' ? 'صحيح' : 'خطأ' }}
                                        @else
                                            <span class="no-answer">لم تجب على هذا السؤال</span>
                                        @endif
                                    </div>
                                    
                                    @if($questionAnswer->question->correct_answers && is_array($questionAnswer->question->correct_answers))
                                        <div class="correct-answer">
                                            <strong>الإجابة الصحيحة:</strong>
                                            {{ $questionAnswer->question->correct_answers[0] == 'true' ? 'صحيح' : 'خطأ' }}
                                        </div>
                                    @endif
                                    
                                @else
                                    <div class="user-answer">
                                        <strong>إجابتك:</strong> 
                                        @if($questionAnswer->user_answer)
                                            {{ $questionAnswer->user_answer }}
                                        @else
                                            <span class="no-answer">لم تجب على هذا السؤال</span>
                                        @endif
                                    </div>
                                    
                                    @if($questionAnswer->question->correct_answers && is_array($questionAnswer->question->correct_answers))
                                        <div class="correct-answer">
                                            <strong>الإجابة الصحيحة:</strong>
                                            @foreach($questionAnswer->question->correct_answers as $correctAnswer)
                                                {{ $correctAnswer }}
                                                @if(!$loop->last), @endif
                                            @endforeach
                                        </div>
                                    @endif
                                @endif

                                @if($questionAnswer->question->explanation)
                                    <div class="explanation">
                                        <strong>الشرح:</strong> {{ $questionAnswer->question->explanation }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Actions -->
        <div class="result-actions">
            <a href="{{ route('category.exams', $attempt->exam->category_exam_id) }}" class="btn-action btn-back">
                <i class="fas fa-arrow-right"></i>
                العودة للامتحانات
            </a>
            
            @if($attempt->exam->canUserAttempt(session('user_id')))
                <a href="{{ route('exam.show', $attempt->exam_id) }}" class="btn-action btn-retry">
                    <i class="fas fa-redo"></i>
                    إعادة المحاولة
                </a>
            @endif
            
            <button onclick="window.print()" class="btn-action btn-print">
                <i class="fas fa-print"></i>
                طباعة النتائج
            </button>
        </div>
    </div>
</div>

<style>
/* Print styles */
@media print {
    .result-actions,
    .back-btn,
    .animated-bg {
        display: none !important;
    }
    
    .exam-result-section {
        background: white !important;
        color: black !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate score circle
    const scoreCircle = document.querySelector('.score-progress');
    const percentage = scoreCircle.getAttribute('data-percentage');
    
    setTimeout(() => {
        scoreCircle.style.background = `conic-gradient(
            #27ae60 0deg ${percentage * 3.6}deg,
            #ecf0f1 ${percentage * 3.6}deg 360deg
        )`;
    }, 500);
    
    // Animate statistics cards
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 100 * index);
    });
});
</script>
@endsection