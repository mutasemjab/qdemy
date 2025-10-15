<div class="exams-grid">
    @foreach($exams as $exam)
        <div class="exam-card">
            @if($exam->has_active_attempt)
                <div class="active-attempt" title="لديك محاولة نشطة"></div>
            @endif
            
            <div class="exam-card-header">
                <h3 class="exam-title">{{ $exam->name }}</h3>
                <div class="exam-status-badge status-{{ $exam->status }}">
                    @if($exam->status == 'available')
                        @if($exam->has_active_attempt)
                            <i class="fas fa-play-circle"></i> قيد التنفيذ
                        @else
                            <i class="fas fa-check-circle"></i> متاح للحل
                        @endif
                    @elseif($exam->status == 'upcoming')
                        <i class="fas fa-clock"></i> قريباً
                    @elseif($exam->status == 'expired')
                        <i class="fas fa-times-circle"></i> منتهي الصلاحية
                    @else
                        <i class="fas fa-pause-circle"></i> غير نشط
                    @endif
                </div>
            </div>

            @if($exam->description)
                <div class="exam-description">
                    {{ Str::limit($exam->description, 120) }}
                </div>
            @endif

            <div class="exam-stats">
                <div class="stat-item">
                    <div class="stat-icon questions">
                        <i class="fas fa-list"></i>
                    </div>
                    <div class="stat-value">{{ $exam->questions->count() }}</div>
                    <div class="stat-label">سؤال</div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-icon duration">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-value">{{ $exam->duration_minutes }}</div>
                    <div class="stat-label">دقيقة</div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-icon attempts">
                        <i class="fas fa-redo"></i>
                    </div>
                    <div class="stat-value">{{ $exam->user_attempts_count }} من {{ $exam->max_attempts }}</div>
                    <div class="stat-label">محاولات</div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-icon grade">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-value">{{ number_format($exam->total_grade, 0) }}</div>
                    <div class="stat-label">نقطة</div>
                </div>
            </div>

            <div class="exam-progress">
                <div class="progress-info">
                    <span class="attempts-info">
                        @if($exam->has_active_attempt)
                            محاولة نشطة - اكملها الآن
                        @elseif($exam->user_attempts_count > 0)
                            المحاولات المستخدمة: {{ $exam->user_attempts_count }} من {{ $exam->max_attempts }}
                        @else
                            لم تجرب هذا الامتحان بعد
                        @endif
                    </span>
                    @if($exam->best_score !== null)
                        <span class="best-score">أفضل درجة: {{ number_format($exam->best_score, 1) }}/{{ number_format($exam->total_grade, 0) }}</span>
                    @endif
                </div>
                <div class="progress-bar">
                    @php
                        $progressPercentage = 0;
                        if ($exam->max_attempts > 0) {
                            $progressPercentage = ($exam->user_attempts_count / $exam->max_attempts) * 100;
                        }
                    @endphp
                    <div class="progress-fill" style="width: {{ min(100, $progressPercentage) }}%"></div>
                </div>
            </div>

            <div class="exam-actions">
                @if($exam->has_active_attempt)
                    <a href="{{ route('exam.take', ['examId' => $exam->id, 'attemptId' => $exam->getUserLastAttempt(session('user_id'))->id]) }}" class="btn-exam btn-continue">
                        <i class="fas fa-arrow-left"></i>
                        متابعة الامتحان
                    </a>
                @elseif($exam->can_attempt && $exam->status == 'available')
                    <a href="{{ route('exam.show', $exam->id) }}" class="btn-exam btn-primary">
                        <i class="fas fa-play"></i>
                        بدء الامتحان
                    </a>
                @else
                    <button class="btn-exam btn-disabled" disabled>
                        <i class="fas fa-lock"></i>
                        @if($exam->status != 'available')
                            الامتحان غير متاح
                        @else
                            استنفدت المحاولات
                        @endif
                    </button>
                @endif
                
                <a href="{{ route('exam.show', $exam->id) }}" class="btn-exam btn-secondary">
                    <i class="fas fa-info-circle"></i>
                    التفاصيل
                </a>
            </div>
        </div>
    @endforeach
</div>