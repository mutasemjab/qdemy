@extends('layouts.admin')

@section('title', __('messages.question_details'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.question_details') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('questions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                        </a>
                        @can('question-edit')
                        <a href="{{ route('questions.edit', $question) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> {{ __('messages.edit') }}
                        </a>
                        @endcan
                        @can('question-delete')
                        <form action="{{ route('questions.destroy', $question) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('{{ __('messages.confirm_delete_question') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> {{ __('messages.delete') }}
                            </button>
                        </form>
                        @endcan
                    </div>
                </div>

                <div class="card-body">
                    <!-- Question Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-left-primary h-100">
                                <div class="card-body">
                                    <h5 class="text-primary">{{ __('messages.question_title_en') }}</h5>
                                    <p class="h6">{{ $question->title_en }}</p>

                                    <h5 class="text-primary mt-3">{{ __('messages.question_text_en') }}</h5>
                                    <div class="border p-3 bg-light rounded">
                                        {{ $question->question_en }}
                                    </div>

                                    @if($question->explanation_en)
                                    <h5 class="text-primary mt-3">{{ __('messages.explanation_en') }}</h5>
                                    <div class="border p-3 bg-info text-white rounded">
                                        <i class="fas fa-info-circle"></i> {{ $question->explanation_en }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        

                        <div class="col-md-6">
                            <div class="card border-left-success h-100">
                                <div class="card-body">
                                    <h5 class="text-success">{{ __('messages.question_title_ar') }}</h5>
                                    <p class="h6" dir="rtl">{{ $question->title_ar }}</p>

                                    <h5 class="text-success mt-3">{{ __('messages.question_text_ar') }}</h5>
                                    <div class="border p-3 bg-light rounded" dir="rtl">
                                        {{ $question->question_ar }}
                                    </div>

                                    @if($question->explanation_ar)
                                    <h5 class="text-success mt-3">{{ __('messages.explanation_ar') }}</h5>
                                    <div class="border p-3 bg-info text-white rounded" dir="rtl">
                                        <i class="fas fa-info-circle"></i> {{ $question->explanation_ar }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                     @if($question->photo)
                                <img src="{{ asset('assets/admin/uploads/' . $question->photo) }}" 
                                     alt="{{ $question->name }}" 
                                     class="img-fluid rounded-circle shadow" 
                                     style="max-width: 200px; max-height: 200px; object-fit: cover;">
                    @else
                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded-circle shadow mx-auto" 
                                     style="width: 200px; height: 200px; font-size: 4rem;">
                                    {{ substr($question->name, 0, 1) }}
                                </div>
                    @endif
                    <!-- Question Metadata -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong>{{ __('messages.course') }}:</strong><br>
                                            <span class="badge bg-primary">{{ $question->course?->title_en }}</span>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>{{ __('messages.question_type') }}:</strong><br>
                                            @if($question->type === 'multiple_choice')
                                                <span class="badge bg-info">{{ __('messages.multiple_choice') }}</span>
                                            @elseif($question->type === 'true_false')
                                                <span class="badge bg-warning">{{ __('messages.true_false') }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ __('messages.essay') }}</span>
                                            @endif
                                        </div>
                                        <div class="col-md-3">
                                            <strong>{{ __('messages.grade') }}:</strong><br>
                                            <span class="badge bg-success">{{ $question->grade }} {{ __('messages.points') }}</span>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>{{ __('messages.created_by') }}:</strong><br>
                                            <span class="badge bg-dark">{{ $question->creator->name ?? __('messages.system') }}</span>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <strong>{{ __('messages.created_at') }}:</strong><br>
                                            <small class="text-muted">{{ $question->created_at->format('Y-m-d H:i:s') }}</small>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>{{ __('messages.updated_at') }}:</strong><br>
                                            <small class="text-muted">{{ $question->updated_at->format('Y-m-d H:i:s') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Question Options -->
                    @if($question->type === 'multiple_choice' || $question->type === 'true_false')
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-list"></i> {{ __('messages.answer_options') }}
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if($question->options->isNotEmpty())
                                        <div class="row">
                                            @foreach($question->options->sortBy('order') as $index => $option)
                                            <div class="col-md-6 mb-3">
                                                <div class="card {{ $option->is_correct ? 'border-success' : 'border-secondary' }}">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <h6 class="mb-0">
                                                                <span class="badge {{ $option->is_correct ? 'bg-success' : 'bg-secondary' }}">
                                                                    {{ __('messages.option') }} {{ chr(65 + $index) }}
                                                                </span>
                                                            </h6>
                                                            @if($option->is_correct)
                                                                <span class="badge bg-success">
                                                                    <i class="fas fa-check"></i> {{ __('messages.correct') }}
                                                                </span>
                                                            @endif
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <strong>{{ __('messages.english') }}:</strong>
                                                                <p class="mb-1">{{ $option->option_en }}</p>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <strong>{{ __('messages.arabic') }}:</strong>
                                                                <p class="mb-1" dir="rtl">{{ $option->option_ar }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            {{ __('messages.no_options_found') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($question->type === 'essay')
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-secondary text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-pen"></i> {{ __('messages.essay_question') }}
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        {{ __('messages.essay_question_grading_note') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Usage in Exams -->
                  @if($question->exams->isNotEmpty())
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-clipboard-list"></i> {{ __('messages.used_in_exams') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.exam_title') }}</th>
                                    <th>{{ __('messages.order') }}</th>
                                    <th>{{ __('messages.grade_in_exam') }}</th>
                                    <th>{{ __('messages.exam_status') }}</th>
                                    <th>{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($question->exams as $exam)
                                <tr>
                                    <td>
                                        <strong>{{ $exam->title_en }}</strong><br>
                                        <small class="text-muted">{{ $exam->title_ar }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $exam->pivot->order }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $exam->pivot->grade }} {{ __('messages.points') }}</span>
                                    </td>
                                    <td>
                                        @if($exam->is_active)
                                            <span class="badge bg-success">{{ __('messages.active') }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ __('messages.inactive') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @can('exam-view')
                                        <a href="{{ route('exams.show', $exam) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> {{ __('messages.view_exam') }}
                                        </a>
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
                </div>

                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('questions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_questions') }}
                        </a>
                        <div>
                            @can('question-edit')
                            <a href="{{ route('questions.edit', $question) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> {{ __('messages.edit_question') }}
                            </a>
                            @endcan
                            @can('question-add')
                            <a href="{{ route('questions.create') }}" class="btn btn-success">
                                <i class="fas fa-plus"></i> {{ __('messages.add_new_question') }}
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Question Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('messages.question_preview') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">{{ $question->title_en }}</h6>
                        <small>{{ __('messages.grade') }}: {{ $question->grade }} {{ __('messages.points') }}</small>
                    </div>
                    <div class="card-body">
                        <div class="question-text mb-3">
                            <p><strong>{{ $question->question_en }}</strong></p>
                        </div>

                        @if($question->type === 'multiple_choice')
                            <div class="options">
                                @foreach($question->options->sortBy('order') as $index => $option)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="preview_answer" disabled>
                                    <label class="form-check-label">
                                        <strong>{{ chr(65 + $index) }}.</strong> {{ $option->option_en }}
                                        @if($option->is_correct)
                                            <span class="badge bg-success ms-2">{{ __('messages.correct') }}</span>
                                        @endif
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        @elseif($question->type === 'true_false')
                            <div class="options">
                                @foreach($question->options->sortBy('order') as $option)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="preview_answer" disabled>
                                    <label class="form-check-label">
                                        {{ $option->option_en }}
                                        @if($option->is_correct)
                                            <span class="badge bg-success ms-2">{{ __('messages.correct') }}</span>
                                        @endif
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-pen"></i> {{ __('messages.essay_answer_area') }}
                                <textarea class="form-control mt-2" rows="4" disabled placeholder="{{ __('messages.student_will_type_answer_here') }}"></textarea>
                            </div>
                        @endif

                        @if($question->explanation_en)
                        <div class="mt-3">
                            <div class="alert alert-info">
                                <strong>{{ __('messages.explanation') }}:</strong><br>
                                {{ $question->explanation_en }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 4px solid #007bff !important;
}

.border-left-success {
    border-left: 4px solid #28a745 !important;
}

.card.border-success {
    border-color: #28a745 !important;
    border-width: 2px !important;
}

.card.border-secondary {
    border-color: #6c757d !important;
}

.option-preview {
    transition: all 0.3s ease;
}

.option-preview:hover {
    background-color: #f8f9fa;
    transform: translateX(5px);
}
</style>

<script>
// Add any additional JavaScript for interactions
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(function(alert) {
            const closeBtn = alert.querySelector('.btn-close');
            if (closeBtn) {
                closeBtn.click();
            }
        });
    }, 5000);
});

// Question preview function
function showPreview() {
    const modal = new bootstrap.Modal(document.getElementById('previewModal'));
    modal.show();
}
</script>
@endsection
