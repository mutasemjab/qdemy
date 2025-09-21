@extends('layouts.app')

@section('title', __('panel.exams'))

@section('page_title', __('panel.exams_management'))

@section('styles')
<style>
    .exam-card {
        transition: transform 0.2s ease-in-out;
    }
    .exam-card:hover {
        transform: translateY(-2px);
    }
    .exam-status {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
    }
    .exam-status.active {
        background-color: #d1fae5;
        color: #065f46;
    }
    .exam-status.inactive {
        background-color: #fee2e2;
        color: #991b1b;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <h2 class="text-dark fw-bold">{{ __('panel.exams_management') }}</h2>
            <p class="text-muted">{{ __('panel.manage_your_exams_desc') }}</p>
        </div>
        <div class="col-lg-6 text-end">
            <a href="{{ route('teacher.exams.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus me-2"></i>{{ __('panel.create_exam') }}
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('teacher.exams.index') }}" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">{{ __('panel.course') }}</label>
                        <select name="course_id" class="form-select">
                            <option value="">{{ __('panel.all_courses') }}</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ app()->getLocale() === 'ar' ? $course->title_ar : $course->title_en }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('panel.subject') }}</label>
                        <select name="subject_id" class="form-select">
                            <option value="">{{ __('panel.all_subjects') }}</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ app()->getLocale() === 'ar' ? $subject->name_ar : $subject->name_en }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">{{ __('panel.status') }}</label>
                        <select name="status" class="form-select">
                            <option value="">{{ __('panel.all_status') }}</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
                                {{ __('panel.active') }}
                            </option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>
                                {{ __('panel.inactive') }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('panel.search') }}</label>
                        <input type="text" name="search" class="form-control" placeholder="{{ __('panel.search_exams') }}" 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Exams Grid -->
    @if($exams->count() > 0)
        <div class="row">
            @foreach($exams as $exam)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card exam-card shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="exam-status {{ $exam->is_active ? 'active' : 'inactive' }}">
                                    {{ $exam->is_active ? __('panel.active') : __('panel.inactive') }}
                                </span>
                                <div class="dropdown">
                                    <button class="btn btn-link p-0" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v text-muted"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('teacher.exams.show', $exam) }}">
                                                <i class="fas fa-eye me-2"></i>{{ __('panel.view') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('teacher.exams.edit', $exam) }}">
                                                <i class="fas fa-edit me-2"></i>{{ __('panel.edit') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('teacher.exams.exam_questions.index', $exam) }}">
                                                <i class="fas fa-question-circle me-2"></i>{{ __('panel.manage_questions') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('teacher.exams.results', $exam) }}">
                                                <i class="fas fa-chart-bar me-2"></i>{{ __('panel.results') }}
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('teacher.exams.destroy', $exam) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger delete-exam">
                                                    <i class="fas fa-trash me-2"></i>{{ __('panel.delete') }}
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <h5 class="card-title mb-2">
                                {{ app()->getLocale() === 'ar' ? $exam->title_ar : $exam->title_en }}
                            </h5>

                            @if($exam->description_en || $exam->description_ar)
                                <p class="card-text text-muted small mb-3">
                                    {{ Str::limit(app()->getLocale() === 'ar' ? $exam->description_ar : $exam->description_en, 100) }}
                                </p>
                            @endif

                            <div class="exam-info small text-muted mb-3 flex-grow-1">
                                @if($exam->subject)
                                    <div class="mb-1">
                                        <i class="fas fa-book me-1"></i>
                                        <strong>{{ __('panel.subject') }}:</strong> 
                                        {{ app()->getLocale() === 'ar' ? $exam->subject->name_ar : $exam->subject->name_en }}
                                    </div>
                                @endif
                                
                                @if($exam->course)
                                    <div class="mb-1">
                                        <i class="fas fa-graduation-cap me-1"></i>
                                        <strong>{{ __('panel.course') }}:</strong> 
                                        {{ app()->getLocale() === 'ar' ? $exam->course->title_ar : $exam->course->title_en }}
                                    </div>
                                @endif

                                <div class="mb-1">
                                    <i class="fas fa-clock me-1"></i>
                                    <strong>{{ __('panel.duration') }}:</strong> 
                                    {{ $exam->duration_minutes ? $exam->duration_minutes . ' ' . __('panel.minutes') : __('panel.unlimited') }}
                                </div>

                                <div class="mb-1">
                                    <i class="fas fa-question-circle me-1"></i>
                                    <strong>{{ __('panel.questions') }}:</strong> 
                                    {{ $exam->questions_count ?? $exam->questions()->count() }}
                                </div>

                                <div class="mb-1">
                                    <i class="fas fa-star me-1"></i>
                                    <strong>{{ __('panel.total_grade') }}:</strong> 
                                    {{ $exam->total_grade ?? 0 }}
                                </div>

                                @if($exam->attempts()->count() > 0)
                                    <div class="mb-1">
                                        <i class="fas fa-users me-1"></i>
                                        <strong>{{ __('panel.attempts') }}:</strong> 
                                        {{ $exam->attempts()->count() }}
                                    </div>
                                @endif
                            </div>

                            <div class="d-flex gap-2 mt-auto">
                                <a href="{{ route('teacher.exams.exam_questions.index', $exam) }}" 
                                   class="btn btn-sm btn-outline-primary flex-fill">
                                    <i class="fas fa-cogs me-1"></i>{{ __('panel.manage') }}
                                </a>
                                <a href="{{ route('teacher.exams.results', $exam) }}" 
                                   class="btn btn-sm btn-outline-info flex-fill">
                                    <i class="fas fa-chart-line me-1"></i>{{ __('panel.results') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $exams->appends(request()->query())->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-5">
            <div class="mb-3">
                <i class="fas fa-clipboard-list fa-4x text-muted"></i>
            </div>
            <h4 class="text-muted mb-3">{{ __('panel.no_exams_found') }}</h4>
            <p class="text-muted mb-4">{{ __('panel.no_exams_created_yet') }}</p>
            <a href="{{ route('teacher.exams.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>{{ __('panel.create_first_exam') }}
            </a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-submit filter form on change
    $('#filterForm select').on('change', function() {
        $('#filterForm').submit();
    });

    // Delete confirmation
    $('.delete-exam').on('click', function(e) {
        e.preventDefault();
        
        const form = $(this).closest('form');
        const examTitle = $(this).closest('.card').find('.card-title').text();
        
        Swal.fire({
            title: '{{ __("panel.confirm_delete") }}',
            text: '{{ __("panel.delete_exam_warning") }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '{{ __("panel.yes_delete") }}',
            cancelButtonText: '{{ __("panel.cancel") }}'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Search input delay
    let searchTimeout;
    $('input[name="search"]').on('input', function() {
        clearTimeout(searchTimeout);
        const form = $('#filterForm');
        searchTimeout = setTimeout(function() {
            form.submit();
        }, 500);
    });
});
</script>
@endpush