@extends('layouts.app')

@section('title', __('panel.questions'))

@section('page_title', __('panel.questions_management'))

@push('styles')
<style>
    .question-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        border: 1px solid #e3e6f0;
    }
    .question-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .question-type-badge {
        font-size: 0.75rem;
        padding: 0.35rem 0.7rem;
        border-radius: 50px;
    }
    .question-preview {
        max-height: 3em;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    .filter-card {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        border: none;
        border-radius: 15px;
    }
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        border: none;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <h2 class="text-dark fw-bold mb-2">{{ __('panel.questions_management') }}</h2>
            <p class="text-muted">{{ __('panel.manage_your_questions_desc') }}</p>
        </div>
        <div class="col-lg-4 text-end">
            <a href="{{ route('teacher.exams.exam_questions.create', $exam) }}" class="btn btn-primary btn-lg shadow-sm">
                <i class="fas fa-plus me-2"></i>{{ __('panel.create_question') }}
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stats-card">
                <div class="card-body text-center">
                    <div class="fs-2 mb-2">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <h4 class="mb-1">{{ $totalQuestions ?? 0 }}</h4>
                    <small class="opacity-75">{{ __('panel.total_questions') }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <div class="fs-2 mb-2">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h4 class="mb-1">{{ $multipleChoiceCount ?? 0 }}</h4>
                    <small class="opacity-75">{{ __('panel.multiple_choice') }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <div class="fs-2 mb-2">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                    <h4 class="mb-1">{{ $trueFalseCount ?? 0 }}</h4>
                    <small class="opacity-75">{{ __('panel.true_false') }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <div class="fs-2 mb-2">
                        <i class="fas fa-pen"></i>
                    </div>
                    <h4 class="mb-1">{{ $essayCount ?? 0 }}</h4>
                    <small class="opacity-75">{{ __('panel.essay') }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card filter-card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('teacher.exams.exam_questions.index', $exam) }}" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">{{ __('panel.course') }}</label>
                        <select name="course_id" class="form-select">
                            <option value="">{{ __('panel.all_courses') }}</option>
                            @if(isset($courses))
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                        {{ app()->getLocale() === 'ar' ? $course->title_ar : $course->title_en }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">{{ __('panel.type') }}</label>
                        <select name="type" class="form-select">
                            <option value="">{{ __('panel.all_types') }}</option>
                            <option value="multiple_choice" {{ request('type') === 'multiple_choice' ? 'selected' : '' }}>
                                {{ __('panel.multiple_choice') }}
                            </option>
                            <option value="true_false" {{ request('type') === 'true_false' ? 'selected' : '' }}>
                                {{ __('panel.true_false') }}
                            </option>
                            <option value="essay" {{ request('type') === 'essay' ? 'selected' : '' }}>
                                {{ __('panel.essay') }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">{{ __('panel.grade_range') }}</label>
                        <select name="grade_range" class="form-select">
                            <option value="">{{ __('panel.all_grades') }}</option>
                            <option value="0-1" {{ request('grade_range') === '0-1' ? 'selected' : '' }}>0-1</option>
                            <option value="1-5" {{ request('grade_range') === '1-5' ? 'selected' : '' }}>1-5</option>
                            <option value="5-10" {{ request('grade_range') === '5-10' ? 'selected' : '' }}>5-10</option>
                            <option value="10+" {{ request('grade_range') === '10+' ? 'selected' : '' }}>10+</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">{{ __('panel.search') }}</label>
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="{{ __('panel.search_questions_placeholder') }}" 
                                   value="{{ request('search') }}">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <a href="{{ route('teacher.exams.exam_questions.index',$exam) }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Questions Grid -->
    @if(isset($questions) && $questions->count() > 0)
        <div class="row">
            @foreach($questions as $question)
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="card question-card h-100">
                        <div class="card-body d-flex flex-column">
                            <!-- Header -->
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <span class="badge question-type-badge 
                                        {{ $question->type === 'multiple_choice' ? 'bg-success' : 
                                           ($question->type === 'true_false' ? 'bg-info' : 'bg-warning') }}">
                                        {{ __('panel.' . $question->type) }}
                                    </span>
                                    @if($question->course)
                                        <span class="badge bg-secondary ms-1">
                                            {{ app()->getLocale() === 'ar' ? $question->course->title_ar : $question->course->title_en }}
                                        </span>
                                    @endif
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-link p-0 text-muted" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item view-question" href="#" 
                                               data-question-id="{{ $question->id }}">
                                                <i class="fas fa-eye me-2"></i>{{ __('panel.view') }}
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('teacher.exams.exam_questions.edit', [$exam, $question]) }}">
                                                <i class="fas fa-edit me-2"></i>{{ __('panel.edit') }}
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('teacher.exams.exam_questions.destroy', [$exam, $question]) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger delete-question">
                                                    <i class="fas fa-trash me-2"></i>{{ __('panel.delete') }}
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Title -->
                            <h5 class="card-title mb-2">
                                {{ app()->getLocale() === 'ar' ? $question->title_ar : $question->title_en }}
                            </h5>

                            <!-- Question Preview -->
                            <div class="question-preview text-muted mb-3 flex-grow-1">
                                {{ strip_tags(app()->getLocale() === 'ar' ? $question->question_ar : $question->question_en) }}
                            </div>

                            <!-- Options Preview (for multiple choice and true/false) -->
                            @if($question->type === 'multiple_choice' && $question->options->count() > 0)
                                <div class="mb-3">
                                    <small class="text-muted fw-semibold">{{ __('panel.options') }}:</small>
                                    <div class="mt-1">
                                        @foreach($question->options->take(2) as $option)
                                            <div class="small text-muted d-flex align-items-center">
                                                <i class="fas fa-{{ $option->is_correct ? 'check-circle text-success' : 'circle text-muted' }} me-2"></i>
                                                {{ Str::limit(app()->getLocale() === 'ar' ? $option->option_ar : $option->option_en, 30) }}
                                            </div>
                                        @endforeach
                                        @if($question->options->count() > 2)
                                            <small class="text-muted">{{ __('panel.and_x_more', ['count' => $question->options->count() - 2]) }}</small>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Footer Info -->
                            <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-star text-warning me-1"></i>
                                    <small class="fw-semibold">{{ $question->grade }}</small>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $question->created_at->diffForHumans() }}
                                </small>
                            </div>

                            <!-- Usage Info -->
                            @if($question->exams_count > 0)
                                <div class="mt-2">
                                    <small class="text-info">
                                        <i class="fas fa-clipboard-list me-1"></i>
                                        {{ __('panel.used_in_x_exams', ['count' => $question->exams_count]) }}
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $questions->appends(request()->query())->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-question-circle fa-5x text-muted"></i>
            </div>
            <h3 class="text-muted mb-3">{{ __('panel.no_questions_found') }}</h3>
            <p class="text-muted mb-4">{{ __('panel.no_questions_desc') }}</p>
            <a href="{{ route('teacher.exams.exam_questions.create',$exam) }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus me-2"></i>{{ __('panel.create_first_question') }}
            </a>
        </div>
    @endif
</div>

<!-- Question Preview Modal -->
<div class="modal fade" id="questionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('panel.question_preview') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="questionPreview">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">{{ __('panel.loading') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-submit filter form on select change
    $('#filterForm select').on('change', function() {
        $('#filterForm').submit();
    });

    // Search with delay
    let searchTimeout;
    $('input[name="search"]').on('input', function() {
        clearTimeout(searchTimeout);
        const form = $('#filterForm');
        searchTimeout = setTimeout(function() {
            form.submit();
        }, 500);
    });

    // View question details
    $('.view-question').on('click', function(e) {
        e.preventDefault();
        const questionId = $(this).data('question-id');
        
        $('#questionModal').modal('show');
        $('#questionPreview').html(`
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">{{ __('panel.loading') }}</span>
                </div>
            </div>
        `);
        
       // Replace the problematic line with this
            const showUrl = '{{ route("teacher.exams.exam_questions.show", [$exam, ":questionId"]) }}'.replace(':questionId', questionId);
            $.get(showUrl)
            .done(function(response) {
                if (response.success) {
                    const question = response.data;
                    let optionsHtml = '';
                    
                    if (question.type === 'multiple_choice' || question.type === 'true_false') {
                        question.options.forEach((option, index) => {
                            const correctIcon = option.is_correct ? 
                                '<i class="fas fa-check-circle text-success me-2"></i>' : 
                                '<i class="far fa-circle text-muted me-2"></i>';
                            const optionText = '{{ app()->getLocale() }}' === 'ar' ? 
                                option.option_ar : option.option_en;
                            optionsHtml += `
                                <div class="d-flex align-items-center mb-2 p-2 border rounded">
                                    ${correctIcon}
                                    <span>${optionText}</span>
                                </div>
                            `;
                        });
                    }
                    
                    const questionText = '{{ app()->getLocale() }}' === 'ar' ? 
                        question.question_ar : question.question_en;
                    const title = '{{ app()->getLocale() }}' === 'ar' ? 
                        question.title_ar : question.title_en;
                    const explanation = '{{ app()->getLocale() }}' === 'ar' ? 
                        question.explanation_ar : question.explanation_en;
                    
                    const previewHtml = `
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="mb-0">${title}</h4>
                                <div>
                                    <span class="badge bg-primary">${question.type.replace('_', ' ').toUpperCase()}</span>
                                    <span class="badge bg-warning ms-1">{{ __('panel.grade') }}: ${question.grade}</span>
                                </div>
                            </div>
                            ${question.course ? `<span class="badge bg-secondary mb-2">${'{{ app()->getLocale() }}' === 'ar' ? question.course.title_ar : question.course.title_en}</span>` : ''}
                        </div>
                        
                        <div class="mb-4">
                            <h6 class="text-muted">{{ __('panel.question') }}:</h6>
                            <div class="p-3 bg-light rounded">${questionText}</div>
                        </div>
                        
                        ${optionsHtml ? `
                            <div class="mb-4">
                                <h6 class="text-muted">{{ __('panel.options') }}:</h6>
                                ${optionsHtml}
                            </div>
                        ` : ''}
                        
                        ${explanation ? `
                            <div class="mb-3">
                                <h6 class="text-muted">{{ __('panel.explanation') }}:</h6>
                                <div class="p-3 bg-light rounded text-muted">${explanation}</div>
                            </div>
                        ` : ''}
                    `;
                    
                    $('#questionPreview').html(previewHtml);
                } else {
                    $('#questionPreview').html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            ${response.message || '{{ __("panel.failed_to_load_question") }}'}
                        </div>
                    `);
                }
            })
            .fail(function() {
                $('#questionPreview').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ __('panel.failed_to_load_question') }}
                    </div>
                `);
            });
    });

    // Delete confirmation
    $('.delete-question').on('click', function(e) {
        e.preventDefault();
        
        const form = $(this).closest('form');
        const questionTitle = $(this).closest('.card').find('.card-title').text().trim();
        
        Swal.fire({
            title: '{{ __("panel.confirm_delete") }}',
            text: '{{ __("panel.delete_question_warning") }}',
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

    // Tooltip initialization
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush