@extends('layouts.admin')

@section('title', __('messages.exam_results'))

@section('css')
<style>
.info-box {
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.badge-lg {
    font-size: 0.9em;
    padding: 0.5em 0.75em;
}

.user-avatar {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.table th {
    border-top: none;
    font-weight: 600;
}

.progress {
    position: relative;
}

.progress .progress-bar {
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
}
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">{{ __('messages.exam_results') }}</h2>
                    <p class="text-muted mb-0">
                        {{ app()->getLocale() == 'ar' ? $exam->title_ar : $exam->title_en }} - 
                        {{ app()->getLocale() == 'ar' ? $exam->course?->name_ar : $exam->course?->name_en }}
                    </p>
                </div>
                <div class="btn-group">
                   
                    <a href="{{ route('exams.show', $exam) }}" class="btn btn-info">
                        <i class="fas fa-eye"></i> {{ __('messages.view_exam') }}
                    </a>
                    <a href="{{ route('exams.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="info-box">
                <span class="info-box-icon bg-info">
                    <i class="fas fa-users"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('messages.total_attempts') }}</span>
                    <span class="info-box-number">{{ $stats['total_attempts'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="info-box">
                <span class="info-box-icon bg-success">
                    <i class="fas fa-check-circle"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('messages.passed') }}</span>
                    <span class="info-box-number">{{ $stats['passed_attempts'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="info-box">
                <span class="info-box-icon bg-danger">
                    <i class="fas fa-times-circle"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('messages.failed') }}</span>
                    <span class="info-box-number">{{ $stats['total_attempts'] - $stats['passed_attempts'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="info-box">
                <span class="info-box-icon bg-warning">
                    <i class="fas fa-chart-line"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('messages.average_score') }}</span>
                    <span class="info-box-number">{{ $stats['average_score'] ? number_format($stats['average_score'], 1) : 0 }}%</span>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="info-box">
                <span class="info-box-icon bg-primary">
                    <i class="fas fa-arrow-up"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('messages.highest_score') }}</span>
                    <span class="info-box-number">{{ $stats['highest_score'] ? number_format($stats['highest_score'], 1) : 0 }}%</span>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="info-box">
                <span class="info-box-icon bg-secondary">
                    <i class="fas fa-arrow-down"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('messages.lowest_score') }}</span>
                    <span class="info-box-number">{{ $stats['lowest_score'] ? number_format($stats['lowest_score'], 1) : 0 }}%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Pass Rate Progress Bar -->
    @if($stats['total_attempts'] > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">{{ __('messages.pass_rate') }}</h6>
                    @php
                        $passRate = ($stats['passed_attempts'] / $stats['total_attempts']) * 100;
                    @endphp
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar bg-success" 
                             style="width: {{ $passRate }}%"
                             role="progressbar">
                            {{ number_format($passRate, 1) }}% ({{ $stats['passed_attempts'] }}/{{ $stats['total_attempts'] }})
                        </div>
                    </div>
                    <small class="text-muted mt-1 d-block">
                        {{ $stats['passed_attempts'] }} {{ __('messages.students_passed_out_of') }} {{ $stats['total_attempts'] }} {{ __('messages.total_attempts') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Results Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-table"></i> {{ __('messages.detailed_results') }}
                        </h5>
                        <div class="card-tools">
                            <!-- Filter Form -->
                            <form method="GET" class="form-inline">
                                <div class="input-group input-group-sm mr-2">
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="{{ __('messages.search_student') }}" 
                                           value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                                <select name="status" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
                                    <option value="">{{ __('messages.all_results') }}</option>
                                    <option value="passed" {{ request('status') == 'passed' ? 'selected' : '' }}>
                                        {{ __('messages.passed_only') }}
                                    </option>
                                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>
                                        {{ __('messages.failed_only') }}
                                    </option>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($attempts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ __('messages.student') }}</th>
                                    <th class="text-center">{{ __('messages.score') }}</th>
                                    <th class="text-center">{{ __('messages.percentage') }}</th>
                                    <th class="text-center">{{ __('messages.status') }}</th>
                                    <th class="text-center">{{ __('messages.time_taken') }}</th>
                                    <th class="text-center">{{ __('messages.submitted_at') }}</th>
                                    <th class="text-center">{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attempts as $attempt)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar mr-2">
                                                <i class="fas fa-user-circle fa-2x text-muted"></i>
                                            </div>
                                            <div>
                                                <strong>{{ $attempt->user->name }}</strong>
                                                <small class="d-block text-muted">{{ $attempt->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="font-weight-bold">{{ number_format($attempt->score, 2) }}</span>
                                        <small class="text-muted d-block">/ {{ number_format($exam->total_grade, 2) }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-lg badge-{{ $attempt->is_passed ? 'success' : 'danger' }}">
                                            {{ number_format($attempt->percentage, 1) }}%
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($attempt->is_passed)
                                            <span class="badge badge-success">
                                                <i class="fas fa-check"></i> {{ __('messages.passed') }}
                                            </span>
                                        @else
                                            <span class="badge badge-danger">
                                                <i class="fas fa-times"></i> {{ __('messages.failed') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($attempt->submitted_at)
                                            @php
                                                $timeTaken = $attempt->started_at->diffInMinutes($attempt->submitted_at);
                                                $hours = intval($timeTaken / 60);
                                                $minutes = $timeTaken % 60;
                                            @endphp
                                            <span class="text-muted">
                                                @if($hours > 0)
                                                    {{ $hours }}h {{ $minutes }}m
                                                @else
                                                    {{ $minutes }}m
                                                @endif
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <small class="text-muted">
                                            {{ $attempt->submitted_at ? $attempt->submitted_at->format('Y-m-d H:i') : '-' }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('exams.attempts.view', [$exam, $attempt]) }}" 
                                               class="btn btn-outline-primary" 
                                               title="{{ __('messages.view_details') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-outline-info" 
                                                    onclick="showAnswersModal({{ $attempt->id }})"
                                                    title="{{ __('messages.view_answers') }}">
                                                <i class="fas fa-list"></i>
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-outline-secondary" 
                                                    onclick="downloadResult({{ $attempt->id }})"
                                                    title="{{ __('messages.download_result') }}">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                {{ __('messages.showing') }} {{ $attempts->firstItem() ?? 0 }} {{ __('messages.to') }} {{ $attempts->lastItem() ?? 0 }} 
                                {{ __('messages.of') }} {{ $attempts->total() }} {{ __('messages.results') }}
                            </div>
                            <div>
                                {{ $attempts->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">{{ __('messages.no_completed_attempts') }}</h5>
                        <p class="text-muted">{{ __('messages.no_students_completed_exam') }}</p>
                        <a href="{{ route('exams.show', $exam) }}" class="btn btn-primary">
                            <i class="fas fa-eye"></i> {{ __('messages.view_exam_details') }}
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Answers Modal -->
<div class="modal fade" id="answersModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('messages.student_answers') }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="answersContent">
                <div class="text-center">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p class="mt-2">{{ __('messages.loading') }}...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    {{ __('messages.close') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Score Distribution Chart (if there are results) -->
@if($stats['total_attempts'] > 0)
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">{{ __('messages.score_distribution') }}</h6>
            </div>
            <div class="card-body">
                <canvas id="scoreChart" width="400" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">{{ __('messages.pass_fail_ratio') }}</h6>
            </div>
            <div class="card-body">
                <canvas id="passFailChart" width="400" height="300"></canvas>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Score Distribution Chart
@if($stats['total_attempts'] > 0)
const scoreCtx = document.getElementById('scoreChart').getContext('2d');
const scoreChart = new Chart(scoreCtx, {
    type: 'bar',
    data: {
        labels: ['0-20%', '21-40%', '41-60%', '61-80%', '81-100%'],
        datasets: [{
            label: '{{ __("messages.number_of_students") }}',
            data: [
                {{ $attempts->where('percentage', '<=', 20)->count() }},
                {{ $attempts->whereBetween('percentage', [21, 40])->count() }},
                {{ $attempts->whereBetween('percentage', [41, 60])->count() }},
                {{ $attempts->whereBetween('percentage', [61, 80])->count() }},
                {{ $attempts->where('percentage', '>=', 81)->count() }}
            ],
            backgroundColor: [
                'rgba(220, 53, 69, 0.8)',
                'rgba(255, 193, 7, 0.8)',
                'rgba(255, 159, 64, 0.8)',
                'rgba(54, 162, 235, 0.8)',
                'rgba(40, 167, 69, 0.8)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Pass/Fail Chart
const passFailCtx = document.getElementById('passFailChart').getContext('2d');
const passFailChart = new Chart(passFailCtx, {
    type: 'doughnut',
    data: {
        labels: ['{{ __("messages.passed") }}', '{{ __("messages.failed") }}'],
        datasets: [{
            data: [{{ $stats['passed_attempts'] }}, {{ $stats['total_attempts'] - $stats['passed_attempts'] }}],
            backgroundColor: [
                'rgba(40, 167, 69, 0.8)',
                'rgba(220, 53, 69, 0.8)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
@endif

// Show answers modal
function showAnswersModal(attemptId) {
    $('#answersModal').modal('show');
    
    // Load answers via AJAX
    fetch(`/admin/exams/{{ $exam->id }}/attempts/${attemptId}/answers`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('answersContent').innerHTML = data.html;
        })
        .catch(error => {
            document.getElementById('answersContent').innerHTML = 
                '<div class="alert alert-danger">{{ __("messages.error_loading_answers") }}</div>';
        });
}

// Download individual result
function downloadResult(attemptId) {
    window.location.href = `/admin/exams/{{ $exam->id }}/attempts/${attemptId}/download`;
}
</script>
@endpush
