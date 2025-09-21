@extends('layouts.app')

@section('title', __('panel.view_exam'))

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">{{ app()->getLocale() == 'ar' ? $exam->title_ar : $exam->title_en }}</h2>
                    <p class="text-muted mb-0">
                        @if ($exam->course)
                            {{ __('panel.course') }}: {{ app()->getLocale() == 'ar' ? $exam->course->title_ar : $exam->course->title_en }}
                        @endif
                        @if ($exam->subject)
                            | {{ __('panel.subject') }}: {{ app()->getLocale() == 'ar' ? $exam->subject->name_ar : $exam->subject->name_en }}
                        @endif
                    </p>
                </div>
                <div class="btn-group">
                    <a href="{{ route('teacher.exams.edit', $exam) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> {{ __('panel.edit') }}
                    </a>
                    <a href="{{ route('teacher.exams.exam_questions.index', $exam) }}" class="btn btn-primary">
                        <i class="fas fa-question"></i> {{ __('panel.manage_questions') }}
                    </a>
                    <a href="{{ route('teacher.exams.results', $exam) }}" class="btn btn-info">
                        <i class="fas fa-chart-bar"></i> {{ __('panel.view_results') }}
                    </a>
                    <a href="{{ route('teacher.exams.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('panel.back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Status and Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-{{ $exam->is_active ? 'success' : 'secondary' }}">
                    <i class="fas fa-{{ $exam->is_active ? 'check-circle' : 'pause-circle' }}"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('panel.status') }}</span>
                    <span class="info-box-number">{{ $exam->is_active ? __('panel.active') : __('panel.inactive') }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info">
                    <i class="fas fa-question"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('panel.total_questions') }}</span>
                    <span class="info-box-number">{{ $exam->questions->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-warning">
                    <i class="fas fa-star"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('panel.total_grade') }}</span>
                    <span class="info-box-number">{{ number_format($exam->total_grade, 2) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-primary">
                    <i class="fas fa-users"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('panel.total_attempts') }}</span>
                    <span class="info-box-number">{{ $exam->attempts->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Exam Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle"></i> {{ __('panel.exam_information') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th class="text-muted">{{ __('panel.title_en') }}:</th>
                                    <td>{{ $exam->title_en }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">{{ __('panel.title_ar') }}:</th>
                                    <td dir="rtl">{{ $exam->title_ar }}</td>
                                </tr>
                                @if ($exam->subject)
                                <tr>
                                    <th class="text-muted">{{ __('panel.subject') }}:</th>
                                    <td>
                                        <span class="text-primary">
                                            {{ app()->getLocale() == 'ar' ? $exam->subject->name_ar : $exam->subject->name_en }}
                                        </span>
                                    </td>
                                </tr>
                                @endif
                                @if ($exam->course)
                                <tr>
                                    <th class="text-muted">{{ __('panel.course') }}:</th>
                                    <td>
                                        <a href="{{ route('teacher.courses.show', $exam->course) }}" class="text-primary">
                                            {{ app()->getLocale() == 'ar' ? $exam->course->title_ar : $exam->course->title_en }}
                                        </a>
                                    </td>
                                </tr>
                                @endif
                                @if ($exam->section)
                                <tr>
                                    <th class="text-muted">{{ __('panel.section') }}:</th>
                                    <td>
                                        <span class="text-info">{{ $exam->section->title }}</span>
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <th class="text-muted">{{ __('panel.duration') }}:</th>
                                    <td>
                                        @if($exam->duration_minutes)
                                            {{ $exam->duration_minutes }} {{ __('panel.minutes') }}
                                        @else
                                            <span class="text-success">{{ __('panel.unlimited') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">{{ __('panel.attempts_allowed') }}:</th>
                                    <td>{{ $exam->attempts_allowed }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th class="text-muted">{{ __('panel.passing_grade') }}:</th>
                                    <td>{{ number_format($exam->passing_grade, 2) }}%</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">{{ __('panel.start_date') }}:</th>
                                    <td>
                                        @if($exam->start_date)
                                            {{ $exam->start_date->format('Y-m-d H:i') }}
                                        @else
                                            <span class="text-muted">{{ __('panel.no_restriction') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">{{ __('panel.end_date') }}:</th>
                                    <td>
                                        @if($exam->end_date)
                                            {{ $exam->end_date->format('Y-m-d H:i') }}
                                            @if($exam->end_date->isPast())
                                                <span class="badge bg-danger ms-1">{{ __('panel.expired') }}</span>
                                            @endif
                                        @else
                                            <span class="text-muted">{{ __('panel.no_restriction') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">{{ __('panel.created_by') }}:</th>
                                    <td>{{ $exam->creator->name ?? __('panel.unknown') }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">{{ __('panel.created_at') }}:</th>
                                    <td>{{ $exam->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">{{ __('panel.last_updated') }}:</th>
                                    <td>{{ $exam->updated_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($exam->description_en || $exam->description_ar)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="text-muted">{{ __('panel.description') }}:</h6>
                            @if(app()->getLocale() == 'ar' && $exam->description_ar)
                                <p dir="rtl">{{ $exam->description_ar }}</p>
                            @elseif($exam->description_en)
                                <p>{{ $exam->description_en }}</p>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Exam Settings -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cog"></i> {{ __('panel.exam_settings') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded">
                                <i class="fas fa-random fa-2x text-{{ $exam->shuffle_questions ? 'success' : 'muted' }} mb-2"></i>
                                <h6>{{ __('panel.shuffle_questions') }}</h6>
                                <span class="badge bg-{{ $exam->shuffle_questions ? 'success' : 'secondary' }}">
                                    {{ $exam->shuffle_questions ? __('panel.enabled') : __('panel.disabled') }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded">
                                <i class="fas fa-exchange-alt fa-2x text-{{ $exam->shuffle_options ? 'success' : 'muted' }} mb-2"></i>
                                <h6>{{ __('panel.shuffle_options') }}</h6>
                                <span class="badge bg-{{ $exam->shuffle_options ? 'success' : 'secondary' }}">
                                    {{ $exam->shuffle_options ? __('panel.enabled') : __('panel.disabled') }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded">
                                <i class="fas fa-eye fa-2x text-{{ $exam->show_results_immediately ? 'success' : 'muted' }} mb-2"></i>
                                <h6>{{ __('panel.show_results_immediately') }}</h6>
                                <span class="badge bg-{{ $exam->show_results_immediately ? 'success' : 'secondary' }}">
                                    {{ $exam->show_results_immediately ? __('panel.enabled') : __('panel.disabled') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Questions Preview -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list"></i> {{ __('panel.questions') }} ({{ $exam->questions->count() }})
                        </h5>
                        <a href="{{ route('teacher.exams.exam_questions.index', $exam) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i> {{ __('panel.manage') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($exam->questions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="60px">{{ __('panel.order') }}</th>
                                        <th>{{ __('panel.question') }}</th>
                                        <th width="100px">{{ __('panel.type') }}</th>
                                        <th width="80px">{{ __('panel.grade') }}</th>
                                        <th width="100px">{{ __('panel.options') }}</th>
                                        <th width="100px">{{ __('panel.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($exam->questions->sortBy('pivot.order') as $question)
                                    <tr>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark">{{ $question->pivot->order }}</span>
                                        </td>
                                        <td>
                                            <strong class="d-block">{{ app()->getLocale() == 'ar' ? $question->title_ar : $question->title_en }}</strong>
                                            <small class="text-muted">
                                                {{ Str::limit(app()->getLocale() == 'ar' ? $question->question_ar : $question->question_en, 100) }}
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ ucfirst(str_replace('_', ' ', $question->type)) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success">{{ number_format($question->pivot->grade, 2) }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if($question->type == 'multiple_choice')
                                                <span class="badge bg-secondary">{{ $question->options->count() }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('teacher.questions.show', $question) }}" class="btn btn-outline-info btn-sm" title="{{ __('panel.view') }}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('teacher.questions.edit', $question) }}" class="btn btn-outline-warning btn-sm" title="{{ __('panel.edit') }}">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">{{ __('panel.no_questions_added') }}</h5>
                            <p class="text-muted">{{ __('panel.add_questions_to_start') }}</p>
                            <a href="{{ route('teacher.exams.exam_questions.index', $exam) }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> {{ __('panel.add_questions') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Recent Attempts -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-clock"></i> {{ __('panel.recent_attempts') }}
                        </h6>
                        @if($exam->attempts->count() > 0)
                        <a href="{{ route('teacher.exams.results', $exam) }}" class="btn btn-sm btn-outline-info">
                            {{ __('panel.view_all') }}
                        </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if($exam->attempts->take(5)->count() > 0)
                        @foreach($exam->attempts->sortByDesc('started_at')->take(5) as $attempt)
                        <div class="d-flex align-items-center mb-3 pb-2 border-bottom">
                            <div class="flex-shrink-0">
                                <i class="fas fa-user-circle fa-2x text-muted"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">{{ $attempt->user->name }}</h6>
                                <small class="text-muted d-block">{{ $attempt->started_at->diffForHumans() }}</small>
                                <div class="mt-1">
                                    <span class="badge bg-{{ 
                                        $attempt->status == 'completed' ? 'success' : 
                                        ($attempt->status == 'in_progress' ? 'warning' : 'secondary') 
                                    }}">
                                        {{ __('panel.' . $attempt->status) }}
                                    </span>
                                    @if($attempt->status == 'completed')
                                        <span class="badge bg-light text-dark ms-1">{{ number_format($attempt->percentage, 1) }}%</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-clock fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">{{ __('panel.no_attempts_yet') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Statistics -->
            @if($exam->attempts->where('status', 'completed')->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-chart-pie"></i> {{ __('panel.statistics') }}
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $completedAttempts = $exam->attempts->where('status', 'completed');
                        $passedAttempts = $completedAttempts->where('is_passed', true);
                        $averageScore = $completedAttempts->avg('percentage');
                        $highestScore = $completedAttempts->max('percentage');
                        $lowestScore = $completedAttempts->min('percentage');
                    @endphp
                    
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <h4 class="text-success mb-1">{{ $passedAttempts->count() }}</h4>
                            <small class="text-muted">{{ __('panel.passed') }}</small>
                        </div>
                        <div class="col-6 mb-3">
                            <h4 class="text-danger mb-1">{{ $completedAttempts->count() - $passedAttempts->count() }}</h4>
                            <small class="text-muted">{{ __('panel.failed') }}</small>
                        </div>
                        <div class="col-12 mb-2">
                            <h5 class="text-primary mb-1">{{ number_format($averageScore, 1) }}%</h5>
                            <small class="text-muted">{{ __('panel.average_score') }}</small>
                        </div>
                    </div>
                    
                    <div class="progress mb-2" style="height: 8px;">
                        <div class="progress-bar bg-success" 
                             style="width: {{ $completedAttempts->count() > 0 ? ($passedAttempts->count() / $completedAttempts->count()) * 100 : 0 }}%"
                             title="{{ __('panel.pass_rate') }}: {{ $completedAttempts->count() > 0 ? number_format(($passedAttempts->count() / $completedAttempts->count()) * 100, 1) : 0 }}%">
                        </div>
                    </div>
                    <small class="text-muted">
                        {{ __('panel.pass_rate') }}: {{ $completedAttempts->count() > 0 ? number_format(($passedAttempts->count() / $completedAttempts->count()) * 100, 1) : 0 }}%
                    </small>
                    
                    <hr>
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">{{ __('panel.highest') }}: {{ number_format($highestScore, 1) }}%</small>
                        <small class="text-muted">{{ __('panel.lowest') }}: {{ number_format($lowestScore, 1) }}%</small>
                    </div>
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-bolt"></i> {{ __('panel.quick_actions') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('teacher.exams.edit', $exam) }}" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-edit"></i> {{ __('panel.edit_exam') }}
                        </a>
                        <a href="{{ route('teacher.exams.exam_questions.index', $exam) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-question"></i> {{ __('panel.manage_questions') }}
                        </a>
                        @if($exam->attempts->count() > 0)
                        <a href="{{ route('teacher.exams.results', $exam) }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-chart-bar"></i> {{ __('panel.view_results') }}
                        </a>
                        @endif
                     
                        @if($exam->is_active)
                        <button type="button" class="btn btn-outline-warning btn-sm" onclick="toggleExamStatus({{ $exam->id }}, false)">
                            <i class="fas fa-pause"></i> {{ __('panel.deactivate') }}
                        </button>
                        @else
                        <button type="button" class="btn btn-outline-success btn-sm" onclick="toggleExamStatus({{ $exam->id }}, true)">
                            <i class="fas fa-play"></i> {{ __('panel.activate') }}
                        </button>
                        @endif
                        
                        <hr>
                        <form action="{{ route('teacher.exams.destroy', $exam) }}" method="POST" class="delete-exam-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                <i class="fas fa-trash"></i> {{ __('panel.delete_exam') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete confirmation
    const deleteForm = document.querySelector('.delete-exam-form');
    if (deleteForm) {
        deleteForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (confirm('{{ __("panel.confirm_delete_exam") }}')) {
                this.submit();
            }
        });
    }
});

// Toggle exam status function
function toggleExamStatus(examId, status) {
    const action = status ? '{{ __("panel.activate") }}' : '{{ __("panel.deactivate") }}';
    const confirmMessage = `{{ __("panel.confirm_exam_status_change") }} ${action.toLowerCase()}?`;
    
    if (confirm(confirmMessage)) {
        // Create a form dynamically to submit the status change
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ url('panel/teacher/exams') }}/${examId}/toggle-status`;
        
        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);
        
        // Add method override
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PATCH';
        form.appendChild(methodInput);
        
        // Add status input
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'is_active';
        statusInput.value = status ? '1' : '0';
        form.appendChild(statusInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Auto-refresh statistics every 30 seconds if there are active attempts
@if($exam->attempts->where('status', 'in_progress')->count() > 0)
setInterval(function() {
    // Only refresh if user hasn't interacted for a while
    if (document.hidden === false) {
        location.reload();
    }
}, 30000);
@endif
</script>
@endpush