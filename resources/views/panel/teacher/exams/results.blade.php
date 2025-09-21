@extends('layouts.app')

@section('title', __('panel.exam_results'))

@section('styles')
<style>
.info-box {
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    background: white;
    border: 1px solid #e9ecef;
    margin-bottom: 1rem;
}

.info-box-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 70px;
    height: 70px;
    border-radius: 8px 0 0 8px;
    color: white;
    font-size: 1.5rem;
}

.info-box-content {
    padding: 0.75rem;
}

.info-box-text {
    font-size: 0.875rem;
    color: #6c757d;
    display: block;
}

.info-box-number {
    font-size: 1.5rem;
    font-weight: 600;
    display: block;
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
    background-color: #f8f9fa;
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

.chart-container {
    position: relative;
    height: 300px;
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
                    <h2 class="mb-1">{{ __('panel.exam_results') }}</h2>
                    <p class="text-muted mb-0">
                        {{ app()->getLocale() == 'ar' ? $exam->title_ar : $exam->title_en }} - 
                        @if($exam->course)
                            {{ app()->getLocale() == 'ar' ? $exam->course->title_ar : $exam->course->title_en }}
                        @endif
                    </p>
                </div>
                <div class="btn-group">
                
                    <a href="{{ route('teacher.exams.show', $exam) }}" class="btn btn-info">
                        <i class="fas fa-eye"></i> {{ __('panel.view_exam') }}
                    </a>
                    <a href="{{ route('teacher.exams.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('panel.back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="info-box d-flex">
                <span class="info-box-icon bg-info">
                    <i class="fas fa-users"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('panel.total_attempts') }}</span>
                    <span class="info-box-number">{{ $stats['total_attempts'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="info-box d-flex">
                <span class="info-box-icon bg-success">
                    <i class="fas fa-check-circle"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('panel.passed') }}</span>
                    <span class="info-box-number">{{ $stats['passed_attempts'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="info-box d-flex">
                <span class="info-box-icon bg-danger">
                    <i class="fas fa-times-circle"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('panel.failed') }}</span>
                    <span class="info-box-number">{{ $stats['total_attempts'] - $stats['passed_attempts'] }}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="info-box d-flex">
                <span class="info-box-icon bg-warning">
                    <i class="fas fa-chart-line"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('panel.average_score') }}</span>
                    <span class="info-box-number">{{ $stats['average_score'] ? number_format($stats['average_score'], 1) : 0 }}%</span>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="info-box d-flex">
                <span class="info-box-icon bg-primary">
                    <i class="fas fa-arrow-up"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('panel.highest_score') }}</span>
                    <span class="info-box-number">{{ $stats['highest_score'] ? number_format($stats['highest_score'], 1) : 0 }}%</span>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="info-box d-flex">
                <span class="info-box-icon bg-secondary">
                    <i class="fas fa-arrow-down"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('panel.lowest_score') }}</span>
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
                    <h6 class="card-title">{{ __('panel.pass_rate') }}</h6>
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
                        {{ $stats['passed_attempts'] }} {{ __('panel.students_passed_out_of') }} {{ $stats['total_attempts'] }} {{ __('panel.total_attempts') }}
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
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-table"></i> {{ __('panel.detailed_results') }}
                        </h5>
                        <div class="card-tools d-flex gap-2">
                            <!-- Filter Form -->
                            <form method="GET" class="d-flex gap-2 align-items-center">
                                <div class="input-group input-group-sm">
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="{{ __('panel.search_student') }}" 
                                           value="{{ request('search') }}">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="">{{ __('panel.all_results') }}</option>
                                    <option value="passed" {{ request('status') == 'passed' ? 'selected' : '' }}>
                                        {{ __('panel.passed_only') }}
                                    </option>
                                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>
                                        {{ __('panel.failed_only') }}
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
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('panel.student') }}</th>
                                    <th class="text-center">{{ __('panel.score') }}</th>
                                    <th class="text-center">{{ __('panel.percentage') }}</th>
                                    <th class="text-center">{{ __('panel.status') }}</th>
                                    <th class="text-center">{{ __('panel.time_taken') }}</th>
                                    <th class="text-center">{{ __('panel.submitted_at') }}</th>
                                    <th class="text-center">{{ __('panel.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attempts as $attempt)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar me-2">
                                                <i class="fas fa-user-circle fa-2x text-muted"></i>
                                            </div>
                                            <div>
                                                <strong>{{ $attempt->user->name }}</strong>
                                                <small class="d-block text-muted">{{ $attempt->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-bold">{{ number_format($attempt->score, 2) }}</span>
                                        <small class="text-muted d-block">/ {{ number_format($exam->total_grade, 2) }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-lg bg-{{ $attempt->is_passed ? 'success' : 'danger' }}">
                                            {{ number_format($attempt->percentage, 1) }}%
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($attempt->is_passed)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check"></i> {{ __('panel.passed') }}
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times"></i> {{ __('panel.failed') }}
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
                                            <a href="{{ route('teacher.exams.attempts.view', [$exam, $attempt]) }}" 
                                               class="btn btn-outline-primary" 
                                               title="{{ __('panel.view_details') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-outline-info" 
                                                    onclick="showAnswersModal({{ $attempt->id }})"
                                                    title="{{ __('panel.view_answers') }}">
                                                <i class="fas fa-list"></i>
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-outline-success" 
                                                    onclick="downloadResult({{ $attempt->id }})"
                                                    title="{{ __('panel.download_result') }}">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            @if(auth()->user()->can('grade_manually'))
                                            <button type="button" 
                                                    class="btn btn-outline-warning" 
                                                    onclick="manualGradeModal({{ $attempt->id }})"
                                                    title="{{ __('panel.manual_grade') }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            @endif
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
                                {{ __('panel.showing') }} {{ $attempts->firstItem() ?? 0 }} {{ __('panel.to') }} {{ $attempts->lastItem() ?? 0 }} 
                                {{ __('panel.of') }} {{ $attempts->total() }} {{ __('panel.results') }}
                            </div>
                            <div>
                                {{ $attempts->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">{{ __('panel.no_completed_attempts') }}</h5>
                        <p class="text-muted">{{ __('panel.no_students_completed_exam') }}</p>
                        <a href="{{ route('teacher.exams.show', $exam) }}" class="btn btn-primary">
                            <i class="fas fa-eye"></i> {{ __('panel.view_exam_details') }}
                        </a>
                    </div>
                    @endif
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
                    <h6 class="card-title mb-0">{{ __('panel.score_distribution') }}</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="scoreChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">{{ __('panel.pass_fail_ratio') }}</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="passFailChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Answers Modal -->
<div class="modal fade" id="answersModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('panel.student_answers') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="answersContent">
                <div class="text-center">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p class="mt-2">{{ __('panel.loading') }}...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    {{ __('panel.close') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Manual Grade Modal -->
<div class="modal fade" id="manualGradeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('panel.manual_grading') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="manualGradeForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('panel.student_name') }}</label>
                        <input type="text" class="form-control" id="studentName" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('panel.current_score') }}</label>
                        <input type="text" class="form-control" id="currentScore" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">{{ __('panel.new_score') }}</label>
                        <input type="number" class="form-control" id="newScore" step="0.01" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('panel.grade_reason') }}</label>
                        <textarea class="form-control" id="gradeReason" rows="3" placeholder="{{ __('panel.enter_reason_for_grade_change') }}"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('panel.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('panel.update_grade') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Score Distribution Chart
@if($stats['total_attempts'] > 0)
document.addEventListener('DOMContentLoaded', function() {
    // Score Distribution Chart
    const scoreCtx = document.getElementById('scoreChart').getContext('2d');
    const scoreChart = new Chart(scoreCtx, {
        type: 'bar',
        data: {
            labels: ['0-20%', '21-40%', '41-60%', '61-80%', '81-100%'],
            datasets: [{
                label: '{{ __("panel.number_of_students") }}',
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
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Pass/Fail Chart
    const passFailCtx = document.getElementById('passFailChart').getContext('2d');
    const passFailChart = new Chart(passFailCtx, {
        type: 'doughnut',
        data: {
            labels: ['{{ __("panel.passed") }}', '{{ __("panel.failed") }}'],
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
});
@endif

// Show answers modal
function showAnswersModal(attemptId) {
    const modal = new bootstrap.Modal(document.getElementById('answersModal'));
    modal.show();
    
    // Reset content
    document.getElementById('answersContent').innerHTML = `
        <div class="text-center">
            <i class="fas fa-spinner fa-spin fa-2x"></i>
            <p class="mt-2">{{ __("panel.loading") }}...</p>
        </div>
    `;
    
    // Load answers via AJAX
    fetch(`{{ route('teacher.exams.index') }}/{{ $exam->id }}/attempts/${attemptId}/answers`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('answersContent').innerHTML = data.html;
        })
        .catch(error => {
            document.getElementById('answersContent').innerHTML = 
                '<div class="alert alert-danger">{{ __("panel.error_loading_answers") }}</div>';
        });
}

// Download individual result
function downloadResult(attemptId) {
    window.location.href = `{{ route('teacher.exams.index') }}/{{ $exam->id }}/attempts/${attemptId}/download`;
}



// Manual grading modal
function manualGradeModal(attemptId) {
    // Find the attempt data in the table
    const row = event.target.closest('tr');
    const studentName = row.querySelector('strong').textContent;
    const currentScore = row.querySelector('.fw-bold').textContent;
    
    // Set form data
    document.getElementById('studentName').value = studentName;
    document.getElementById('currentScore').value = currentScore;
    document.getElementById('newScore').value = '';
    document.getElementById('gradeReason').value = '';
    
    // Store attempt ID for form submission
    document.getElementById('manualGradeForm').dataset.attemptId = attemptId;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('manualGradeModal'));
    modal.show();
}

// Handle manual grade form submission
document.getElementById('manualGradeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const attemptId = this.dataset.attemptId;
    const newScore = document.getElementById('newScore').value;
    const reason = document.getElementById('gradeReason').value;
    
    // Send AJAX request to update grade
    fetch(`{{ route('teacher.exams.index') }}/{{ $exam->id }}/attempts/${attemptId}/manual-grade`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            score: newScore,
            reason: reason
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || '{{ __("panel.error_updating_grade") }}');
        }
    })
    .catch(error => {
        alert('{{ __("panel.error_updating_grade") }}');
    });
});
</script>
@endpush