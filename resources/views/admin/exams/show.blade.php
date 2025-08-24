@extends('layouts.admin')

@section('title', __('messages.view_exam'))

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">{{ app()->getLocale() == 'ar' ? $exam->title_ar : $exam->title_en }}</h2>
                    <p class="text-muted mb-0">
                        {{ __('messages.course') }}: {{ app()->getLocale() == 'ar' ? $exam->course->name_ar : $exam->course->name_en }}
                    </p>
                </div>
                <div class="btn-group">
                    <a href="{{ route('exams.edit', $exam) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> {{ __('messages.edit') }}
                    </a>
                    <a href="{{ route('exams.questions.manage', $exam) }}" class="btn btn-primary">
                        <i class="fas fa-question"></i> {{ __('messages.manage_questions') }}
                    </a>
                    <a href="{{ route('exams.results', $exam) }}" class="btn btn-info">
                        <i class="fas fa-chart-bar"></i> {{ __('messages.view_results') }}
                    </a>
                    <a href="{{ route('exams.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
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
                    <span class="info-box-text">{{ __('messages.status') }}</span>
                    <span class="info-box-number">{{ $exam->is_active ? __('messages.active') : __('messages.inactive') }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info">
                    <i class="fas fa-question"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('messages.total_questions') }}</span>
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
                    <span class="info-box-text">{{ __('messages.total_grade') }}</span>
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
                    <span class="info-box-text">{{ __('messages.total_attempts') }}</span>
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
                        <i class="fas fa-info-circle"></i> {{ __('messages.exam_information') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th class="text-muted">{{ __('messages.title_en') }}:</th>
                                    <td>{{ $exam->title_en }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">{{ __('messages.title_ar') }}:</th>
                                    <td dir="rtl">{{ $exam->title_ar }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">{{ __('messages.course') }}:</th>
                                    <td>
                                        <a href="{{ route('courses.show', $exam->course) }}" class="text-primary">
                                            {{ app()->getLocale() == 'ar' ? $exam->course->name_ar : $exam->course->name_en }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">{{ __('messages.duration') }}:</th>
                                    <td>
                                        @if($exam->duration_minutes)
                                            {{ $exam->duration_minutes }} {{ __('messages.minutes') }}
                                        @else
                                            <span class="text-success">{{ __('messages.unlimited') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">{{ __('messages.attempts_allowed') }}:</th>
                                    <td>{{ $exam->attempts_allowed }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th class="text-muted">{{ __('messages.passing_grade') }}:</th>
                                    <td>{{ number_format($exam->passing_grade, 2) }}%</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">{{ __('messages.start_date') }}:</th>
                                    <td>
                                        @if($exam->start_date)
                                            {{ $exam->start_date->format('Y-m-d H:i') }}
                                        @else
                                            <span class="text-muted">{{ __('messages.no_restriction') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">{{ __('messages.end_date') }}:</th>
                                    <td>
                                        @if($exam->end_date)
                                            {{ $exam->end_date->format('Y-m-d H:i') }}
                                            @if($exam->end_date->isPast())
                                                <span class="badge badge-danger ml-1">{{ __('messages.expired') }}</span>
                                            @endif
                                        @else
                                            <span class="text-muted">{{ __('messages.no_restriction') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">{{ __('messages.created_by') }}:</th>
                                    <td>{{ $exam->creator->name ?? __('messages.unknown') }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">{{ __('messages.created_at') }}:</th>
                                    <td>{{ $exam->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($exam->description_en || $exam->description_ar)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="text-muted">{{ __('messages.description') }}:</h6>
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
                        <i class="fas fa-cog"></i> {{ __('messages.exam_settings') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded">
                                <i class="fas fa-random fa-2x text-{{ $exam->shuffle_questions ? 'success' : 'muted' }} mb-2"></i>
                                <h6>{{ __('messages.shuffle_questions') }}</h6>
                                <span class="badge badge-{{ $exam->shuffle_questions ? 'success' : 'secondary' }}">
                                    {{ $exam->shuffle_questions ? __('messages.enabled') : __('messages.disabled') }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded">
                                <i class="fas fa-exchange-alt fa-2x text-{{ $exam->shuffle_options ? 'success' : 'muted' }} mb-2"></i>
                                <h6>{{ __('messages.shuffle_options') }}</h6>
                                <span class="badge badge-{{ $exam->shuffle_options ? 'success' : 'secondary' }}">
                                    {{ $exam->shuffle_options ? __('messages.enabled') : __('messages.disabled') }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded">
                                <i class="fas fa-eye fa-2x text-{{ $exam->show_results_immediately ? 'success' : 'muted' }} mb-2"></i>
                                <h6>{{ __('messages.show_results_immediately') }}</h6>
                                <span class="badge badge-{{ $exam->show_results_immediately ? 'success' : 'secondary' }}">
                                    {{ $exam->show_results_immediately ? __('messages.enabled') : __('messages.disabled') }}
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
                            <i class="fas fa-list"></i> {{ __('messages.questions') }} ({{ $exam->questions->count() }})
                        </h5>
                        <a href="{{ route('exams.questions.manage', $exam) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i> {{ __('messages.manage') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($exam->questions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="60px">{{ __('messages.order') }}</th>
                                        <th>{{ __('messages.question') }}</th>
                                        <th width="100px">{{ __('messages.type') }}</th>
                                        <th width="80px">{{ __('messages.grade') }}</th>
                                        <th width="100px">{{ __('messages.options') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($exam->questions->sortBy('pivot.order') as $question)
                                    <tr>
                                        <td class="text-center">
                                            <span class="badge badge-light">{{ $question->pivot->order }}</span>
                                        </td>
                                        <td>
                                            <strong class="d-block">{{ app()->getLocale() == 'ar' ? $question->title_ar : $question->title_en }}</strong>
                                            <small class="text-muted">
                                                {{ Str::limit(app()->getLocale() == 'ar' ? $question->question_ar : $question->question_en, 100) }}
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">
                                                {{ ucfirst(str_replace('_', ' ', $question->type)) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-success">{{ number_format($question->pivot->grade, 2) }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if($question->type == 'multiple_choice')
                                                <span class="badge badge-secondary">{{ $question->options->count() }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">{{ __('messages.no_questions_added') }}</h5>
                            <p class="text-muted">{{ __('messages.add_questions_to_start') }}</p>
                            <a href="{{ route('exams.questions.manage', $exam) }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> {{ __('messages.add_questions') }}
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
                            <i class="fas fa-clock"></i> {{ __('messages.recent_attempts') }}
                        </h6>
                        @if($exam->attempts->count() > 0)
                        <a href="{{ route('exams.results', $exam) }}" class="btn btn-sm btn-outline-info">
                            {{ __('messages.view_all') }}
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
                            <div class="flex-grow-1 ml-3">
                                <h6 class="mb-1">{{ $attempt->user->name }}</h6>
                                <small class="text-muted d-block">{{ $attempt->started_at->diffForHumans() }}</small>
                                <div class="mt-1">
                                    <span class="badge badge-{{ 
                                        $attempt->status == 'completed' ? 'success' : 
                                        ($attempt->status == 'in_progress' ? 'warning' : 'secondary') 
                                    }}">
                                        {{ __('messages.' . $attempt->status) }}
                                    </span>
                                    @if($attempt->status == 'completed')
                                        <span class="badge badge-light ml-1">{{ number_format($attempt->percentage, 1) }}%</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-clock fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">{{ __('messages.no_attempts_yet') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Statistics -->
            @if($exam->attempts->where('status', 'completed')->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-chart-pie"></i> {{ __('messages.statistics') }}
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
                            <small class="text-muted">{{ __('messages.passed') }}</small>
                        </div>
                        <div class="col-6 mb-3">
                            <h4 class="text-danger mb-1">{{ $completedAttempts->count() - $passedAttempts->count() }}</h4>
                            <small class="text-muted">{{ __('messages.failed') }}</small>
                        </div>
                        <div class="col-12 mb-2">
                            <h5 class="text-primary mb-1">{{ number_format($averageScore, 1) }}%</h5>
                            <small class="text-muted">{{ __('messages.average_score') }}</small>
                        </div>
                    </div>
                    
                    <div class="progress mb-2" style="height: 8px;">
                        <div class="progress-bar bg-success" 
                             style="width: {{ $completedAttempts->count() > 0 ? ($passedAttempts->count() / $completedAttempts->count()) * 100 : 0 }}%"
                             title="{{ __('messages.pass_rate') }}: {{ $completedAttempts->count() > 0 ? number_format(($passedAttempts->count() / $completedAttempts->count()) * 100, 1) : 0 }}%">
                        </div>
                    </div>
                    <small class="text-muted">
                        {{ __('messages.pass_rate') }}: {{ $completedAttempts->count() > 0 ? number_format(($passedAttempts->count() / $completedAttempts->count()) * 100, 1) : 0 }}%
                    </small>
                    
                    <hr>
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">{{ __('messages.highest') }}: {{ number_format($highestScore, 1) }}%</small>
                        <small class="text-muted">{{ __('messages.lowest') }}: {{ number_format($lowestScore, 1) }}%</small>
                    </div>
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-bolt"></i> {{ __('messages.quick_actions') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('exams.edit', $exam) }}" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-edit"></i> {{ __('messages.edit_exam') }}
                        </a>
                        <a href="{{ route('exams.questions.manage', $exam) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-question"></i> {{ __('messages.manage_questions') }}
                        </a>
                        @if($exam->attempts->count() > 0)
                        <a href="{{ route('exams.results', $exam) }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-chart-bar"></i> {{ __('messages.view_results') }}
                        </a>
                        @endif
                     
                        <hr>
                        <form action="{{ route('exams.destroy', $exam) }}" method="POST" class="delete-exam-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                <i class="fas fa-trash"></i> {{ __('messages.delete_exam') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
}

// Delete confirmation
document.querySelector('.delete-exam-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (confirm('{{ __("messages.confirm_delete_exam") }}')) {
        this.submit();
    }
});
</script>
@endsection