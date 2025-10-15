{{-- sections/exam-details.blade.php --}}
@extends('layouts.front')

@section('content')
<div class="exam-details-section" style="display: block;">
    <a href="javascript:history.back()" class="back-btn" style="display: block;">
        <i class="fas fa-arrow-right"></i>
        رجوع
    </a>

    <div class="exam-details-container">
        <div class="exam-header">
            <h1 class="exam-title">{{ $exam->name }}</h1>
            <div class="exam-status-badge status-{{ $exam->status }}">
                @if($exam->status == 'available')
                    <i class="fas fa-check-circle"></i> متاح للحل
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
                {{ $exam->description }}
            </div>
        @endif

        <div class="exam-info-grid">
            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="info-content">
                    <h3>المدة الزمنية</h3>
                    <p>{{ $exam->formatted_duration }}</p>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-list"></i>
                </div>
                <div class="info-content">
                    <h3>عدد الأسئلة</h3>
                    <p>{{ $exam->questions->count() }} سؤال</p>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="info-content">
                    <h3>الدرجة الكاملة</h3>
                    <p>{{ $exam->total_grade }} نقطة</p>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="info-content">
                    <h3>درجة النجاح</h3>
                    <p>{{ $exam->pass_grade }} نقطة</p>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon">
                    <i class="fas fa-redo"></i>
                </div>
                <div class="info-content">
                    <h3>المحاولات المتاحة</h3>
                    <p>{{ $exam->max_attempts - $userAttempts }} من {{ $exam->max_attempts }}</p>
                </div>
            </div>

            @if($bestScore !== null)
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-medal"></i>
                    </div>
                    <div class="info-content">
                        <h3>أفضل درجة</h3>
                        <p>{{ $bestScore }} / {{ $exam->total_grade }}</p>
                    </div>
                </div>
            @endif
        </div>

        @if($exam->instructions)
            <div class="exam-instructions">
                <h3><i class="fas fa-info-circle"></i> تعليمات الامتحان</h3>
                <div class="instructions-content">
                    @if(is_array($exam->instructions))
                        <ul>
                            @foreach($exam->instructions as $instruction)
                                <li>{{ $instruction }}</li>
                            @endforeach
                        </ul>
                    @else
                        {{ $exam->instructions }}
                    @endif
                </div>
            </div>
        @endif

        <div class="exam-actions">
            @if($exam->can_attempt)
                <form action="{{ route('exam.start', $exam->id) }}" method="POST" class="start-exam-form">
                    @csrf
                    <button type="submit" class="btn-start-exam">
                        <i class="fas fa-play"></i>
                        بدء الامتحان
                    </button>
                </form>
            @else
                @if($exam->status != 'available')
                    <button class="btn-disabled" disabled>
                        <i class="fas fa-lock"></i>
                        الامتحان غير متاح
                    </button>
                @else
                    <button class="btn-disabled" disabled>
                        <i class="fas fa-times"></i>
                        استنفدت المحاولات المتاحة
                    </button>
                @endif
            @endif
        </div>

        @if($userAttempts > 0)
            <div class="previous-attempts">
                <h3><i class="fas fa-history"></i> المحاولات السابقة</h3>
                <!-- You can add a table of previous attempts here -->
            </div>
        @endif
    </div>
</div>
@endsection