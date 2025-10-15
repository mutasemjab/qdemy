{{-- resources/views/exams/attempts.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4>{{ __('messages.exam_attempts') }}: {{ $exam->name }}</h4>
                        <small class="text-muted">
                            {{ __('messages.total_attempts') }}: {{ $attempts->total() }} | 
                            {{ __('messages.completed') }}: {{ $exam->attempts()->where('status', 'completed')->count() }} |
                            {{ __('messages.in_progress') }}: {{ $exam->attempts()->where('status', 'in_progress')->count() }}
                        </small>
                    </div>
                    <div>
                        <a href="{{ route('exams.show', $exam) }}" class="btn btn-secondary">
                            {{ __('messages.back_to_exam') }}
                        </a>
                        <button class="btn btn-success" onclick="exportAttempts()">
                            <i class="fas fa-download"></i> {{ __('messages.export_csv') }}
                        </button>
                    </div>
                </div>

                <div class="card-body">
                
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="form-control" id="statusFilter" onchange="filterAttempts()">
                                <option value="">{{ __('messages.all_statuses') }}</option>
                                <option value="completed">{{ __('messages.completed') }}</option>
                                <option value="in_progress">{{ __('messages.in_progress') }}</option>
                                <option value="abandoned">{{ __('messages.abandoned') }}</option>
                                <option value="time_up">{{ __('messages.time_up') }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" id="gradeFilter" onchange="filterAttempts()">
                                <option value="">{{ __('messages.all_grades') }}</option>
                                <option value="passed">{{ __('messages.passed') }}</option>
                                <option value="failed">{{ __('messages.failed') }}</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="searchFilter" 
                                   placeholder="{{ __('messages.search_by_name_phone') }}" 
                                   onkeyup="filterAttempts()">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                                {{ __('messages.clear_filters') }}
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="attemptsTable">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.student_info') }}</th>
                                    <th>{{ __('messages.field') }}</th>
                                    <th>{{ __('messages.status') }}</th>
                                    <th>{{ __('messages.started_at') }}</th>
                                    <th>{{ __('messages.submitted_at') }}</th>
                                    <th>{{ __('messages.duration') }}</th>
                                    <th>{{ __('messages.score') }}</th>
                                    <th>{{ __('messages.percentage') }}</th>
                                    <th>{{ __('messages.result') }}</th>
                                    <th>{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attempts as $attempt)
                                    <tr data-status="{{ $attempt->status }}" 
                                        data-passed="{{ $attempt->score >= $exam->pass_grade ? 'passed' : 'failed' }}"
                                        data-student="{{ strtolower($attempt->user->name . ' ' . $attempt->user->phone) }}">
                                        
                                        <!-- Student Info -->
                                        <td>
                                            <div>
                                                <strong>{{ $attempt->user->name }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-phone"></i> {{ $attempt->user->phone }}
                                                    <br>
                                                    <i class="fas fa-school"></i> {{ $attempt->user->school_name }}
                                                </small>
                                            </div>
                                        </td>

                                        <!-- Field -->
                                        <td>
                                            <span class="badge bg-info">{{ $attempt->user->field->name ?? __('messages.no_field') }}</span>
                                        </td>

                                        <!-- Status -->
                                        <td>
                                            @switch($attempt->status)
                                                @case('completed')
                                                    <span class="badge bg-success">{{ __('messages.completed') }}</span>
                                                    @break
                                                @case('in_progress')
                                                    <span class="badge bg-warning">{{ __('messages.in_progress') }}</span>
                                                    @break
                                                @case('abandoned')
                                                    <span class="badge bg-secondary">{{ __('messages.abandoned') }}</span>
                                                    @break
                                                @case('time_up')
                                                    <span class="badge bg-danger">{{ __('messages.time_up') }}</span>
                                                    @break
                                            @endswitch
                                        </td>

                                        <!-- Started At -->
                                        <td>
                                            <div>
                                                {{ $attempt->started_at->format('Y-m-d') }}
                                                <br>
                                                <small class="text-muted">{{ $attempt->started_at->format('H:i:s') }}</small>
                                            </div>
                                        </td>

                                        <!-- Submitted At -->
                                        <td>
                                            @if($attempt->submitted_at)
                                                <div>
                                                    {{ $attempt->submitted_at->format('Y-m-d') }}
                                                    <br>
                                                    <small class="text-muted">{{ $attempt->submitted_at->format('H:i:s') }}</small>
                                                </div>
                                            @else
                                                <span class="text-muted">{{ __('messages.not_submitted') }}</span>
                                            @endif
                                        </td>

                                        <!-- Duration -->
                                        <td>
                                            @if($attempt->submitted_at)
                                                @php
                                                    $duration = $attempt->started_at->diffInMinutes($attempt->submitted_at);
                                                @endphp
                                                <div>
                                                    {{ floor($duration / 60) }}:{{ sprintf('%02d', $duration % 60) }}
                                                    <br>
                                                    <small class="text-muted">{{ $duration }} {{ __('messages.minutes') }}</small>
                                                </div>
                                            @elseif($attempt->status === 'in_progress')
                                                @php
                                                    $elapsed = $attempt->started_at->diffInMinutes(now());
                                                    $remaining = max(0, $exam->duration_minutes - $elapsed);
                                                @endphp
                                                <div>
                                                    <span class="text-warning">{{ $elapsed }} {{ __('messages.minutes') }}</span>
                                                    <br>
                                                    <small class="text-muted">{{ $remaining }} {{ __('messages.remaining') }}</small>
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>

                                        <!-- Score -->
                                        <td>
                                            @if($attempt->score !== null)
                                                <div>
                                                    <strong>{{ number_format($attempt->score, 2) }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ __('messages.out_of') }} {{ $exam->total_grade }}</small>
                                                </div>
                                            @else
                                                <span class="text-muted">{{ __('messages.not_graded') }}</span>
                                            @endif
                                        </td>

                                        <!-- Percentage -->
                                        <td>
                                            @if($attempt->percentage !== null)
                                                <div>
                                                    <strong 
                                                        class="{{ $attempt->percentage >= ($exam->pass_grade / $exam->total_grade * 100) ? 'text-success' : 'text-danger' }}">
                                                        {{ number_format($attempt->percentage, 1) }}%
                                                    </strong>
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>

                                        <!-- Result -->
                                        <td>
                                            @if($attempt->status === 'completed' && $attempt->score !== null)
                                                @if($attempt->score >= $exam->pass_grade)
                                                    <span class="badge bg-success fs-6">{{ __('messages.passed') }}</span>
                                                @else
                                                    <span class="badge bg-danger fs-6">{{ __('messages.failed') }}</span>
                                                @endif
                                            @elseif($attempt->status === 'in_progress')
                                                <span class="badge bg-warning fs-6">{{ __('messages.ongoing') }}</span>
                                            @else
                                                <span class="badge bg-secondary fs-6">{{ __('messages.incomplete') }}</span>
                                            @endif
                                        </td>

                                        <!-- Actions -->
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                {{-- @if($attempt->status === 'completed')
                                                    <a href="{{ route('exam.results', $attempt) }}" 
                                                       class="btn btn-info" title="{{ __('messages.view_results') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @endif
                                                 --}}
                                             

                                                {{-- <button class="btn btn-primary" 
                                                        onclick="viewAttemptDetails({{ $attempt->id }})"
                                                        title="{{ __('messages.view_details') }}">
                                                    <i class="fas fa-info"></i>
                                                </button> --}}

                                                @if($exam->questions()->where('type', 'essay')->exists())
                                                    <a href="{{ route('exam.grade-essays', $attempt) }}" 
                                                       class="btn btn-secondary" title="{{ __('messages.grade_essays') }}">
                                                        <i class="fas fa-pen"></i>
                                                    </a>
                                                @endif

                                                {{-- <button class="btn btn-outline-danger" 
                                                        onclick="deleteAttempt({{ $attempt->id }})"
                                                        title="{{ __('messages.delete_attempt') }}">
                                                    <i class="fas fa-trash"></i>
                                                </button> --}}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <i class="fas fa-info-circle text-muted"></i>
                                            {{ __('messages.no_attempts_found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            {{ __('messages.showing') }} {{ $attempts->firstItem() }} {{ __('messages.to') }} {{ $attempts->lastItem() }} 
                            {{ __('messages.of') }} {{ $attempts->total() }} {{ __('messages.results') }}
                        </div>
                        <div>
                            {{ $attempts->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Attempt Details Modal -->
<div class="modal fade" id="attemptDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('messages.attempt_details') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="attemptDetailsBody">
                <!-- Details will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
function filterAttempts() {
    const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
    const gradeFilter = document.getElementById('gradeFilter').value.toLowerCase();
    const searchFilter = document.getElementById('searchFilter').value.toLowerCase();
    const rows = document.querySelectorAll('#attemptsTable tbody tr[data-status]');
    
    rows.forEach(row => {
        const status = row.getAttribute('data-status').toLowerCase();
        const passed = row.getAttribute('data-passed').toLowerCase();
        const student = row.getAttribute('data-student').toLowerCase();
        
        let showRow = true;
        
        if (statusFilter && status !== statusFilter) {
            showRow = false;
        }
        
        if (gradeFilter && passed !== gradeFilter) {
            showRow = false;
        }
        
        if (searchFilter && !student.includes(searchFilter)) {
            showRow = false;
        }
        
        row.style.display = showRow ? '' : 'none';
    });
}

function clearFilters() {
    document.getElementById('statusFilter').value = '';
    document.getElementById('gradeFilter').value = '';
    document.getElementById('searchFilter').value = '';
    filterAttempts();
}

function viewAttemptDetails(attemptId) {
    // Fetch attempt details via AJAX
    fetch(`/exam-attempts/${attemptId}/details`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('attemptDetailsBody').innerHTML = data.html;
            new bootstrap.Modal(document.getElementById('attemptDetailsModal')).show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __("messages.error_loading_details") }}');
        });
}

function deleteAttempt(attemptId) {
    if (confirm('{{ __("messages.confirm_delete_attempt") }}')) {
        fetch(`/exam-attempts/${attemptId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || '{{ __("messages.error_deleting_attempt") }}');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __("messages.error_deleting_attempt") }}');
        });
    }
}

function exportAttempts() {
    window.location.href = `{{ route('exams.export-attempts', $exam) }}`;
}

// Auto-refresh for in-progress attempts every 30 seconds
setInterval(function() {
    const inProgressRows = document.querySelectorAll('tr[data-status="in_progress"]');
    if (inProgressRows.length > 0) {
        location.reload();
    }
}, 30000);
</script>

<style>
.table th {
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-group-sm .btn {
    margin: 1px;
}

.question-preview {
    font-size: 0.9em;
    line-height: 1.3;
}

@media (max-width: 768px) {
    .table-responsive table {
        font-size: 0.8rem;
    }
    
    .btn-group-sm .btn {
        padding: 0.2rem 0.4rem;
    }
}

.modal-lg {
    max-width: 900px;
}

.badge.fs-6 {
    font-size: 0.875rem !important;
}
</style>
@endsection