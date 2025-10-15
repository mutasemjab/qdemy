@extends('layouts.front')

@section('content')
<div class="exam-history-section" style="display: block;">
    <div class="history-container">
        <!-- Header -->
        <div class="history-header">
            <div class="header-content">
                <h1><i class="fas fa-chart-line"></i> تاريخ امتحاناتي</h1>
                <p>عرض شامل لجميع الامتحانات التي أكملتها ونتائجها</p>
            </div>
            <a href="{{ route('dashboard') }}" class="btn-back">
                <i class="fas fa-arrow-right"></i>
                العودة للرئيسية
            </a>
        </div>

        <!-- Statistics Overview -->
        <div class="statistics-overview">
            <div class="stat-card">
                <div class="stat-icon total">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="stat-content">
                    <h3>إجمالي الامتحانات</h3>
                    <span class="stat-number">{{ $statistics['total_exams'] }}</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon passed">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>النجح</h3>
                    <span class="stat-number">{{ $statistics['passed_exams'] }}</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon failed">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>الراسب</h3>
                    <span class="stat-number">{{ $statistics['failed_exams'] }}</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon percentage">
                    <i class="fas fa-percentage"></i>
                </div>
                <div class="stat-content">
                    <h3>المعدل العام</h3>
                    <span class="stat-number">{{ number_format($statistics['overall_percentage'], 1) }}%</span>
                </div>
            </div>
        </div>

        <!-- Exam History List -->
        @if(count($examHistory) > 0)
            <div class="exam-history-list">
                <h2><i class="fas fa-history"></i> تفاصيل الامتحانات</h2>
                
                @foreach($examHistory as $examData)
                    @php
                        $bestAttempt = $examData['best_attempt'];
                        $exam = $examData['exam'];
                        $isPassed = $bestAttempt->score >= $exam->pass_grade;
                    @endphp
                    
                    <div class="exam-history-card {{ $isPassed ? 'passed' : 'failed' }}">
                        <div class="exam-header">
                            <div class="exam-info">
                                <h3>{{ $exam->name }}</h3>
                                <p class="exam-category">
                                    <i class="fas fa-folder"></i>
                                    {{ $exam->category->name ?? 'غير محدد' }}
                                </p>
                            </div>
                            <div class="exam-status">
                                @if($isPassed)
                                    <span class="status-badge passed">
                                        <i class="fas fa-trophy"></i>
                                        ناجح
                                    </span>
                                @else
                                    <span class="status-badge failed">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        راسب
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="exam-results">
                            <div class="result-item">
                                <span class="label">أفضل درجة:</span>
                                <span class="value">{{ $bestAttempt->score }} / {{ $exam->total_grade }}</span>
                            </div>
                            <div class="result-item">
                                <span class="label">النسبة المئوية:</span>
                                <span class="value">{{ number_format($bestAttempt->percentage, 1) }}%</span>
                            </div>
                            <div class="result-item">
                                <span class="label">عدد المحاولات:</span>
                                <span class="value">{{ $examData['attempts_count'] }}</span>
                            </div>
                            <div class="result-item">
                                <span class="label">تاريخ آخر محاولة:</span>
                                <span class="value">{{ $bestAttempt->submitted_at->format('Y-m-d H:i') }}</span>
                            </div>
                        </div>
                        
                        <div class="exam-actions">
                            <a href="{{ route('exam.result', $bestAttempt->id) }}" class="btn-view-result">
                                <i class="fas fa-eye"></i>
                                عرض التفاصيل
                            </a>
                            @if($exam->canUserAttempt(session('user_id')))
                                <a href="{{ route('exam.show', $exam->id) }}" class="btn-retry">
                                    <i class="fas fa-redo"></i>
                                    إعادة المحاولة
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="no-history">
                <div class="no-history-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h3>لا توجد امتحانات مكتملة</h3>
                <p>لم تكمل أي امتحان بعد. ابدأ في حل الامتحانات لترى تقدمك هنا.</p>
                <a href="{{ route('categories.show', 'exams') }}" class="btn-start-exams">
                    <i class="fas fa-play"></i>
                    ابدأ الامتحانات
                </a>
            </div>
        @endif
    </div>
</div>

<style>
/* Exam History Section Styling */
.exam-history-section {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 2rem 0;
}

.history-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

.history-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    background: rgba(255,255,255,0.95);
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.1);
}

.header-content h1 {
    color: #1f2937;
    font-size: 2rem;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.header-content p {
    color: #6b7280;
    font-size: 1.1rem;
    margin: 0;
}

.btn-back {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.btn-back:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 25px rgba(102, 126, 234, 0.4);
    color: white;
    text-decoration: none;
}

/* Statistics Overview */
.statistics-overview {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
}

.stat-card {
    background: rgba(255,255,255,0.95);
    border-radius: 15px;
    padding: 2rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    box-shadow: 0 8px 30px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.stat-icon.total {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.stat-icon.passed {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.stat-icon.failed {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
}

.stat-icon.percentage {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
}

.stat-content h3 {
    color: #374151;
    font-size: 1rem;
    margin: 0 0 0.5rem 0;
    font-weight: 600;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #1f2937;
}

/* Exam History List */
.exam-history-list h2 {
    color: white;
    font-size: 1.5rem;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.exam-history-card {
    background: rgba(255,255,255,0.95);
    border-radius: 15px;
    margin-bottom: 1.5rem;
    overflow: hidden;
    box-shadow: 0 8px 30px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.exam-history-card:hover {
    transform: translateY(-3px);
}

.exam-history-card.passed {
    border-left: 5px solid #10b981;
}

.exam-history-card.failed {
    border-left: 5px solid #ef4444;
}

.exam-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 2rem;
    background: rgba(0,0,0,0.02);
    border-bottom: 1px solid #e5e7eb;
}

.exam-info h3 {
    color: #1f2937;
    font-size: 1.3rem;
    margin: 0 0 0.5rem 0;
    font-weight: 600;
}

.exam-category {
    color: #6b7280;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.status-badge.passed {
    background: #ecfdf5;
    color: #059669;
    border: 2px solid #10b981;
}

.status-badge.failed {
    background: #fef2f2;
    color: #dc2626;
    border: 2px solid #ef4444;
}

.exam-results {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    padding: 1.5rem 2rem;
}

.result-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem;
    background: #f8fafc;
    border-radius: 8px;
}

.result-item .label {
    color: #6b7280;
    font-weight: 500;
}

.result-item .value {
    color: #1f2937;
    font-weight: 600;
}

.exam-actions {
    padding: 1.5rem 2rem;
    background: rgba(0,0,0,0.02);
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
}

.btn-view-result,
.btn-retry {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.btn-view-result {
    background: #667eea;
    color: white;
}

.btn-view-result:hover {
    background: #5a67d8;
    color: white;
    text-decoration: none;
}

.btn-retry {
    background: #10b981;
    color: white;
}

.btn-retry:hover {
    background: #059669;
    color: white;
    text-decoration: none;
}

/* No History State */
.no-history {
    text-align: center;
    padding: 4rem 2rem;
    background: rgba(255,255,255,0.95);
    border-radius: 15px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.1);
}

.no-history-icon i {
    font-size: 4rem;
    color: #9ca3af;
    margin-bottom: 1rem;
}

.no-history h3 {
    color: #374151;
    font-size: 1.5rem;
    margin-bottom: 1rem;
}

.no-history p {
    color: #6b7280;
    font-size: 1.1rem;
    margin-bottom: 2rem;
}

.btn-start-exams {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    padding: 1rem 2rem;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.btn-start-exams:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 25px rgba(16, 185, 129, 0.3);
    color: white;
    text-decoration: none;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .history-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .header-content h1 {
        font-size: 1.5rem;
    }
    
    .statistics-overview {
        grid-template-columns: 1fr;
    }
    
    .exam-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .exam-results {
        grid-template-columns: 1fr;
    }
    
    .exam-actions {
        flex-direction: column;
        align-items: stretch;
    }
    
    .result-item {
        flex-direction: column;
        gap: 0.5rem;
        text-align: center;
    }
}
</style>
@endsection