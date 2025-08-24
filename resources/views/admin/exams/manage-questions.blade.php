@extends('layouts.admin')

@section('title', __('messages.manage_exam_questions'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title">{{ __('messages.manage_exam_questions') }}</h3>
                            <p class="text-muted mb-0">
                                {{ __('messages.exam') }}: {{ $exam->title_en }} |
                                {{ __('messages.total_grade') }}: {{ $exam->total_grade }}
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('exams.index') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_exams') }}
                            </a>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addQuestionsModal">
                                <i class="fas fa-plus"></i> {{ __('messages.add_questions') }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">


                    <!-- Current Questions -->
                    @if($exam->questions->count() > 0)
                        <div class="mb-4">
                            <h5>{{ __('messages.current_questions') }} ({{ $exam->questions->count() }})</h5>

                            <form action="{{ route('exams.questions.update', $exam) }}" method="POST" id="updateQuestionsForm">
                                @csrf
                                @method('PUT')

                                <div class="table-responsive">
                                    <table class="table table-striped" id="questionsTable">
                                        <thead>
                                            <tr>
                                                <th width="5%">{{ __('messages.order') }}</th>
                                                <th width="30%">{{ __('messages.question') }}</th>
                                                <th width="15%">{{ __('messages.type') }}</th>
                                                <th width="10%">{{ __('messages.default_grade') }}</th>
                                                <th width="10%">{{ __('messages.exam_grade') }}</th>
                                                <th width="20%">{{ __('messages.options_preview') }}</th>
                                                <th width="10%">{{ __('messages.actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="questionsTableBody">
                                            @foreach($exam->questions->sortBy('pivot.order') as $question)
                                                <tr data-question-id="{{ $question->id }}">
                                                    <td>
                                                        <input type="hidden" name="questions[{{ $loop->index }}][id]" value="{{ $question->id }}">
                                                        <input type="number"
                                                               class="form-control form-control-sm"
                                                               name="questions[{{ $loop->index }}][order]"
                                                               value="{{ $question->pivot->order }}"
                                                               min="1"
                                                               style="width: 60px;">
                                                    </td>
                                                    <td>
                                                        <strong>{{ $question->title_en }}</strong><br>
                                                        <small class="text-muted">{{ $question->title_ar }}</small>
                                                    </td>
                                                    <td>
                                                        @if($question->type === 'multiple_choice')
                                                            <span class="badge bg-primary">
                                                                <i class="fas fa-list"></i> {{ __('messages.multiple_choice') }}
                                                            </span>
                                                        @elseif($question->type === 'true_false')
                                                            <span class="badge bg-success">
                                                                <i class="fas fa-check-circle"></i> {{ __('messages.true_false') }}
                                                            </span>
                                                        @else
                                                            <span class="badge bg-warning">
                                                                <i class="fas fa-edit"></i> {{ __('messages.essay') }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-secondary">{{ $question->grade }}</span>
                                                    </td>
                                                    <td>
                                                        <input type="number"
                                                               class="form-control form-control-sm"
                                                               name="questions[{{ $loop->index }}][grade]"
                                                               value="{{ $question->pivot->grade }}"
                                                               step="0.25"
                                                               min="0.25"
                                                               style="width: 80px;">
                                                    </td>
                                                    <td>
                                                        @if($question->options->count() > 0)
                                                            <div class="small">
                                                                @foreach($question->options->take(2) as $option)
                                                                    <div class="mb-1">
                                                                        <span class="badge {{ $option->is_correct ? 'bg-success' : 'bg-light text-dark' }} badge-sm">
                                                                            {{ $option->letter }}
                                                                        </span>
                                                                        {{ Str::limit($option->option_en, 20) }}
                                                                    </div>
                                                                @endforeach
                                                                @if($question->options->count() > 2)
                                                                    <small class="text-muted">+{{ $question->options->count() - 2 }} more</small>
                                                                @endif
                                                            </div>
                                                        @else
                                                            <span class="text-muted">{{ __('messages.no_options') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <button type="button"
                                                                class="btn btn-sm btn-outline-info me-1"
                                                                onclick="previewQuestion({{ $question->id }})"
                                                                title="{{ __('messages.preview') }}">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <a href="{{ route('exams.questions.remove', [$exam, $question]) }}"
                                                           class="btn btn-sm btn-outline-danger"
                                                           onclick="return confirm('{{ __('messages.confirm_remove_question') }}')"
                                                           title="{{ __('messages.remove') }}">
                                                            <i class="fas fa-times"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> {{ __('messages.update_questions') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle fa-2x mb-2"></i>
                                <h5>{{ __('messages.no_questions_added') }}</h5>
                                <p>{{ __('messages.add_questions_to_exam') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Question Preview Modal -->
<div class="modal fade" id="questionPreviewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('messages.question_preview') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="questionPreviewContent">
                <!-- Question preview content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
// Select all functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.question-checkbox');
    const gradeInputs = document.querySelectorAll('.grade-input');

    checkboxes.forEach((checkbox, index) => {
        checkbox.checked = this.checked;
        gradeInputs[index].disabled = !this.checked;
    });

    updateAddButton();
});

// Individual checkbox functionality
document.querySelectorAll('.question-checkbox').forEach((checkbox, index) => {
    checkbox.addEventListener('change', function() {
        const gradeInputs = document.querySelectorAll('.grade-input');
        gradeInputs[index].disabled = !this.checked;
        updateAddButton();
    });
});

// Update add button state
function updateAddButton() {
    const checkedBoxes = document.querySelectorAll('.question-checkbox:checked');
    const addBtn = document.getElementById('addSelectedBtn');
    addBtn.disabled = checkedBoxes.length === 0;
}

// Preview question
function previewQuestion(questionId) {
    fetch(`/admin/questions/${questionId}`)
        .then(response => response.text())
        .then(html => {
            // Extract question content from response
            const parser = new DOMParser();
            const doc = parser.parseFromHTML(html);
            const questionContent = doc.querySelector('.question-preview');

            if (questionContent) {
                document.getElementById('questionPreviewContent').innerHTML = questionContent.innerHTML;
            } else {
                document.getElementById('questionPreviewContent').innerHTML =
                    '<div class="alert alert-warning">{{ __("messages.preview_not_available") }}</div>';
            }

            const modal = new bootstrap.Modal(document.getElementById('questionPreviewModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('questionPreviewContent').innerHTML =
                '<div class="alert alert-danger">{{ __("messages.error_loading_preview") }}</div>';
        });
}

// Form validation for add questions
document.getElementById('addQuestionsForm').addEventListener('submit', function(e) {
    const checkedBoxes = document.querySelectorAll('.question-checkbox:checked');

    if (checkedBoxes.length === 0) {
        e.preventDefault();
        alert('{{ __("messages.please_select_at_least_one_question") }}');
        return;
    }

    // Validate grades
    let valid = true;
    checkedBoxes.forEach((checkbox, index) => {
        const gradeInput = document.querySelectorAll('.grade-input')[checkbox.closest('tr').rowIndex - 1];
        if (!gradeInput.value || parseFloat(gradeInput.value) < 0.25) {
            valid = false;
        }
    });

    if (!valid) {
        e.preventDefault();
        alert('{{ __("messages.please_enter_valid_grades") }}');
        return;
    }
});

// Sortable functionality for questions table
document.addEventListener('DOMContentLoaded', function() {
    // You can add drag-and-drop sorting here if needed
    // using libraries like Sortable.js
});
</script>

@endsection
