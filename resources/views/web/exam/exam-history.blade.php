{{-- resources/views/web/exam/exam-history.blade.php --}}
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

    <div class="exam-history-section">
        <div class="history-container">
            {{-- Header --}}
            <div class="history-header">
                <h1>{{ __('front.exam_history') }}</h1>
                <p>{{ __('front.previous_attempts_history') }}</p>
            </div>

            {{-- Statistics --}}
            <div class="statistics">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-list-check"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-label">{{ __('front.total_exams') }}</div>
                        <div class="stat-number">{{ $totalExams }}</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-label">{{ __('front.passed_exams') }}</div>
                        <div class="stat-number">{{ $passedExams }}</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon danger">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-label">{{ __('front.failed_exams') }}</div>
                        <div class="stat-number">{{ $failedExams }}</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon primary">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-label">{{ __('front.overall_percentage') }}</div>
                        <div class="stat-number">{{ round(($passedExams / max($totalExams, 1)) * 100, 1) }}%</div>
                    </div>
                </div>
            </div>

            {{-- Exams Table --}}
            @if ($attempts->count() > 0)
                <div class="exams-table-wrapper">
                    <table class="exams-table">
                        <thead>
                            <tr>
                                <th>{{ __('front.exam_title') }}</th>
                                <th>{{ __('front.attempt_date') }}</th>
                                <th>{{ __('front.score') }}</th>
                                <th>{{ __('front.percentage') }}</th>
                                <th>{{ __('front.attempt_status') }}</th>
                                <th>{{ __('front.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attempts as $attempt)
                                @php
                                    $exam = $attempt->exam;
                                    $isPassed = $attempt->score >= $exam->passing_grade;
                                @endphp
                                <tr class="{{ $isPassed ? 'passed-row' : 'failed-row' }}">
                                    <td class="exam-name">
                                        <strong>{{ $exam->title }}</strong>
                                    </td>
                                    <td>{{ $attempt->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="score-cell">
                                        <span class="score-badge">{{ $attempt->score }} / {{ $exam->total_grade }}</span>
                                    </td>
                                    <td>
                                        <div class="progress-mini">
                                            @php
                                                $percentage = round(($attempt->score / $exam->total_grade) * 100, 0);
                                            @endphp
                                            <div class="progress-bar" style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <span class="percentage-text">{{ $percentage }}%</span>
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $isPassed ? 'success' : 'danger' }}">
                                            @if ($isPassed)
                                                <i class="fas fa-check-circle"></i>
                                                {{ __('front.passed_exams') }}
                                            @else
                                                <i class="fas fa-times-circle"></i>
                                                {{ __('front.failed_exams') }}
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $reviewUrl = route('exam.result', [
                                                'exam' => $exam->id,
                                                'attempt' => $attempt->id,
                                            ]);
                                            if (isset($isApi) && $isApi) {
                                                $reviewUrl .= '?_mobile=1&_user_id=' . auth('user')->id();
                                            }
                                        @endphp
                                        <a href="{{ $reviewUrl }}" class="action-btn">
                                            <i class="fas fa-eye"></i>
                                            {{ __('front.review') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="pagination-wrapper">
                    {{ $attempts->links() }}
                </div>
            @else
                {{-- Empty State --}}
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <h3>{{ __('front.no_exams_found') }}</h3>
                    <p>{{ __('front.no_exams_description') }}</p>
                    <a href="{{ route('exams') . $queryParams }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i>
                        {{ __('front.go_to_exams') }}
                    </a>
                </div>
            @endif
        </div>
    </div>

    <style>
        .exam-history-section {
            padding: 40px 20px;
            background: #f8f9fa;
            min-height: 100vh;
        }

        .history-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .history-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .history-header h1 {
            font-size: 32px;
            font-weight: bold;
            margin: 0 0 8px 0;
            color: #333;
        }

        .history-header p {
            margin: 0;
            color: #666;
            font-size: 16px;
        }

        .statistics {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            display: flex;
            gap: 16px;
            align-items: center;
            transition: all 0.3s;
        }

        .stat-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: #007bff;
            color: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }

        .stat-icon.success {
            background: #28a745;
        }

        .stat-icon.danger {
            background: #dc3545;
        }

        .stat-icon.primary {
            background: #6c757d;
        }

        .stat-info {
            flex: 1;
        }

        .stat-label {
            font-size: 13px;
            color: #999;
            margin-bottom: 4px;
        }

        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        .exams-table-wrapper {
            background: white;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            overflow: hidden;
            margin-bottom: 30px;
        }

        .exams-table {
            width: 100%;
            border-collapse: collapse;
        }

        .exams-table thead {
            background: #f8f9fa;
            border-bottom: 2px solid #e0e0e0;
        }

        .exams-table th {
            padding: 16px;
            text-align: left;
            font-weight: bold;
            color: #333;
            font-size: 14px;
        }

        .exams-table tbody tr {
            border-bottom: 1px solid #e0e0e0;
            transition: all 0.3s;
        }

        .exams-table tbody tr:hover {
            background: #f8f9fa;
        }

        .exams-table tbody tr.passed-row {
            background: #f8fff9;
        }

        .exams-table tbody tr.failed-row {
            background: #fff8f8;
        }

        .exams-table td {
            padding: 16px;
            vertical-align: middle;
        }

        .exam-name {
            font-weight: bold;
            color: #333;
        }

        .score-cell {
            text-align: center;
        }

        .score-badge {
            display: inline-block;
            padding: 6px 12px;
            background: #e7f3ff;
            color: #007bff;
            border-radius: 20px;
            font-weight: bold;
            font-size: 13px;
        }

        .progress-mini {
            position: relative;
            height: 6px;
            background: #e0e0e0;
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 6px;
        }

        .progress-bar {
            height: 100%;
            background: #007bff;
            border-radius: 3px;
            transition: width 0.3s;
        }

        .percentage-text {
            font-size: 13px;
            font-weight: bold;
            color: #333;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
            white-space: nowrap;
        }

        .status-badge.success {
            background: #d4edda;
            color: #155724;
        }

        .status-badge.danger {
            background: #f8d7da;
            color: #721c24;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 12px;
            font-weight: bold;
            transition: all 0.3s;
        }

        .action-btn:hover {
            background: #0056b3;
        }

        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }

        .empty-icon {
            font-size: 64px;
            color: #ccc;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 24px;
            color: #333;
            margin: 0 0 10px 0;
        }

        .empty-state p {
            color: #666;
            margin: 0 0 20px 0;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background: #0056b3;
        }

        @media (max-width: 768px) {
            .statistics {
                grid-template-columns: repeat(2, 1fr);
            }

            .exams-table {
                font-size: 13px;
            }

            .exams-table th,
            .exams-table td {
                padding: 12px;
            }
        }
    </style>
@endsection
