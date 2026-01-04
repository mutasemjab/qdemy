{{-- resources/views/web/exam/exam-details.blade.php --}}
@extends('layouts.exam')

@section('content')
    <div class="exam-details-section">
        <div class="exam-details-container">
            {{-- Header --}}
            <div class="exam-details-header">
                <a href="{{ route('exams') }}" class="back-btn">
                    <i class="fas fa-arrow-right"></i>
                    {{ __('front.back') }}
                </a>
                <h1 class="exam-title">{{ $exam->title }}</h1>
            </div>

            {{-- Main Content Row --}}
            <div class="exam-details-content">
                {{-- Left: Exam Info --}}
                <div class="exam-info">
                    @if ($exam->description)
                        <div class="description-section">
                            <h3>{{ __('front.exam_description') }}</h3>
                            <p>{{ $exam->description }}</p>
                        </div>
                    @endif

                    {{-- Exam Stats Grid --}}
                    <div class="stats-grid">
                        <div class="stat-box">
                            <div class="stat-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-label">{{ __('front.exam_duration') }}</div>
                                <div class="stat-value">{{ $exam->duration_minutes }} {{ __('front.minutes') }}</div>
                            </div>
                        </div>

                        <div class="stat-box">
                            <div class="stat-icon">
                                <i class="fas fa-list"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-label">{{ __('front.exam_questions_count') }}</div>
                                <div class="stat-value">{{ $exam->questions()->count() }}</div>
                            </div>
                        </div>

                        <div class="stat-box">
                            <div class="stat-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-label">{{ __('front.exam_total_grade') }}</div>
                                <div class="stat-value">{{ $exam->total_grade }}</div>
                            </div>
                        </div>

                        <div class="stat-box">
                            <div class="stat-icon">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-label">{{ __('front.exam_passing_grade') }}</div>
                                <div class="stat-value">{{ $exam->passing_grade }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Attempts Info --}}
                    @if ($attempts && $attempts->count() > 0)
                        <div class="attempts-info">
                            <h3>{{ __('front.attempts_allowed') }}</h3>
                            <div class="attempts-detail">
                                <span class="attempts-label">{{ __('front.total_attempts') }}:</span>
                                <span class="attempts-value">{{ $attempts->count() }}</span>
                            </div>
                            <div class="best-attempt">
                                <span class="best-label">{{ __('front.best_score') }}:</span>
                                <span class="best-value">{{ $attempts->max('score') ?? 0 }} /
                                    {{ $exam->total_grade }}</span>
                            </div>
                        </div>
                    @endif

                </div>

                {{-- Right: Action Panel --}}
                <div class="action-panel">
                    <div class="action-card">
                        <h3 class="action-title">{{ __('front.exam_details_info') }}</h3>

                        @if ($current_attempt && !$current_attempt->submitted_at)
                            {{-- Continue Button --}}
                            <a href="{{ route('exam.take', ['exam' => $exam->id]) }}" class="btn btn-primary btn-large">
                                <i class="fas fa-play"></i>
                                {{ __('front.continue_exam') }}
                            </a>
                            <p class="attempt-info">
                                {{ __('front.in_progress') }} - {{ $current_attempt->created_at->diffForHumans() }}
                            </p>
                        @elseif($result && $exam->show_results_immediately && $result->status === 'completed')
                            {{-- View Results Button --}}
                            <a href="{{ route('exam.result', ['exam' => $exam->id, 'attempt' => $result->id]) }}"
                                class="btn btn-info btn-large">
                                <i class="fas fa-chart-bar"></i>
                                {{ __('front.view_results') }}
                            </a>
                            <p class="attempt-info">
                                {{ __('front.exam_completed') }} - {{ $result->created_at->diffForHumans() }}
                            </p>
                            @if ($can_add_attempt)
                                <form method="POST"
                                    action="{{ route($apiRoutePrefix . 'exam.start', ['exam' => $exam->id]) }}"
                                    style="margin-top: 12px;">
                                    @if (!$isApi)
                                        @csrf
                                    @endif
                                    <button type="submit" class="btn btn-success btn-large">
                                        <i class="fas fa-redo"></i>
                                        {{ __('front.محاولة جديدة') }}
                                    </button>
                                </form>
                            @endif
                        @elseif(!$can_add_attempt)
                            {{-- No More Attempts --}}
                            <button class="btn btn-disabled btn-large" disabled>
                                <i class="fas fa-ban"></i>
                                {{ __('front.cannot_start_new_attempt') }}
                            </button>
                            <p class="attempt-info">
                                {{ __('front.cannot_add_attempt') }}
                            </p>
                        @else
                            {{-- Start Button --}}


                            {{-- Start Button --}}
                            <form method="POST" action="{{ route($apiRoutePrefix . 'exam.start', ['exam' => $exam->id]) }}">
                                @if (!$isApi)
                                    @csrf
                                @endif
                                <button type="submit" class="btn btn-success btn-large">
                                    <i class="fas fa-play-circle"></i>
                                    {{ __('front.start_exam') }}
                                </button>
                            </form>
                        @endif

                        {{-- Info Card --}}
                        <div class="info-box">
                            <div class="info-item">
                                <span class="info-label">{{ __('front.duration') }}:</span>
                                <span class="info-value">{{ $exam->duration_minutes }} {{ __('front.minutes') }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">{{ __('front.total_questions') }}:</span>
                                <span class="info-value">{{ $exam->questions()->count() }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">{{ __('front.total_grade') }}:</span>
                                <span class="info-value">{{ $exam->total_grade }} {{ __('front.points') }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">{{ __('front.passing_grade') }}:</span>
                                <span class="info-value">{{ $exam->passing_grade }} {{ __('front.points') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Previous Attempts --}}
                    @if ($attempts && $attempts->count() > 0)
                        <div class="previous-attempts">
                            <h3>{{ __('front.previous_attempts') }}</h3>
                            <div class="attempts-list">
                                @foreach ($attempts->take(3) as $attempt)
                                    <div class="attempt-item">
                                        <div class="attempt-number">
                                            {{ __('front.attempt') }} #{{ $loop->iteration }}
                                        </div>
                                        <div class="attempt-score">
                                            <span class="score-value">{{ $attempt->score }}</span>
                                            <span class="score-total">/ {{ $exam->total_grade }}</span>
                                        </div>
                                        <div class="attempt-status {{ strtolower($attempt->status) }}">
                                            {{ ucfirst($attempt->status) }}
                                        </div>
                                        <div class="attempt-date">
                                            {{ $attempt->created_at->format('d/m/Y') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .exam-details-section {
            padding: 40px 20px;
            background: #f8f9fa;
            min-height: 100vh;
        }

        .exam-details-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .exam-details-header {
            margin-bottom: 30px;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: white;
            color: #333;
            text-decoration: none;
            border-radius: 6px;
            margin-bottom: 15px;
            transition: all 0.3s;
            border: 1px solid #e0e0e0;
        }

        .back-btn:hover {
            background: #f0f0f0;
        }

        .exam-title {
            font-size: 32px;
            font-weight: bold;
            color: #333;
            margin: 0;
        }

        .exam-details-content {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 30px;
            margin-top: 30px;
        }

        @media (max-width: 968px) {
            .exam-details-content {
                grid-template-columns: 1fr;
            }
        }

        .exam-info {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .description-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }

        .description-section h3 {
            font-size: 16px;
            font-weight: bold;
            margin: 0 0 12px 0;
            color: #333;
        }

        .description-section p {
            margin: 0;
            color: #666;
            line-height: 1.6;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .stat-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            display: flex;
            gap: 16px;
            align-items: center;
            transition: all 0.3s;
        }

        .stat-box:hover {
            border-color: #007bff;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.1);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            background: #007bff;
            color: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .stat-content {
            flex: 1;
        }

        .stat-label {
            font-size: 13px;
            color: #999;
            margin-bottom: 4px;
        }

        .stat-value {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }

        .attempts-info {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }

        .attempts-info h3 {
            font-size: 16px;
            font-weight: bold;
            margin: 0 0 16px 0;
            color: #333;
        }

        .attempts-detail,
        .best-attempt {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .attempts-detail:last-child,
        .best-attempt {
            border-bottom: none;
        }

        .attempts-label,
        .best-label {
            color: #666;
            font-size: 14px;
        }

        .attempts-value,
        .best-value {
            font-weight: bold;
            color: #333;
        }

        .action-panel {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .action-card {
            background: white;
            padding: 24px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            position: sticky;
            top: 20px;
        }

        .action-title {
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 20px 0;
            color: #333;
        }

        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-large {
            width: 100%;
            padding: 16px 20px;
            font-size: 15px;
            margin-bottom: 12px;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background: #0056b3;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #1e7e34;
        }

        .btn-info {
            background: #17a2b8;
            color: white;
        }

        .btn-info:hover {
            background: #138496;
        }

        .btn-disabled {
            background: #e0e0e0;
            color: #999;
            cursor: not-allowed;
        }

        .attempt-info {
            text-align: center;
            color: #666;
            font-size: 13px;
            margin: 8px 0;
        }

        .info-box {
            background: #f8f9fa;
            padding: 16px;
            border-radius: 6px;
            margin-top: 16px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
            font-size: 13px;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #666;
        }

        .info-value {
            font-weight: bold;
            color: #333;
        }

        .previous-attempts {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }

        .previous-attempts h3 {
            font-size: 16px;
            font-weight: bold;
            margin: 0 0 16px 0;
            color: #333;
        }

        .attempts-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .attempt-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 6px;
            font-size: 13px;
        }

        .attempt-number {
            font-weight: bold;
            color: #333;
        }

        .attempt-score {
            display: flex;
            gap: 4px;
            color: #007bff;
            font-weight: bold;
        }

        .attempt-status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            width: fit-content;
        }

        .attempt-status.completed {
            background: #d4edda;
            color: #155724;
        }

        .attempt-status.in_progress {
            background: #fff3cd;
            color: #856404;
        }

        .attempt-date {
            color: #999;
            font-size: 12px;
        }
    </style>
@endsection
