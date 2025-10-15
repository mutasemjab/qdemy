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
                            <h3 class="card-title mb-0">{{ __('messages.manage_exam_questions') }}</h3>
                            <p class="text-muted mb-0">
                                {{ __('messages.exam') }}: {{ app()->getLocale() == 'ar' ? $exam->title_ar : $exam->title_en }}
                                | {{ __('messages.course') }}:
                                 @if ($exam->course)
                                    {{ app()->getLocale() == 'ar' ? $exam->course->name_ar : $exam->course->name_en }}
                                @endif
                            </p>
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('exams.show', $exam) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-eye"></i> {{ __('messages.view_exam') }}
                            </a>
                            <a href="{{ route('exams.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_exams') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Exam Summary -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-question"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ __('messages.total_questions') }}</span>
                                    <span class="info-box-number">{{ $exam->questions->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-star"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ __('messages.total_grade') }}</span>
                                    <span class="info-box-number">{{ number_format($exam->total_grade, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-percentage"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ __('messages.passing_grade') }}</span>
                                    <span class="info-box-number">{{ number_format($exam->passing_grade, 2) }}%</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary"><i class="fas fa-clock"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">{{ __('messages.duration') }}</span>
                                    <span class="info-box-number">
                                        {{ $exam->duration_minutes ? $exam->duration_minutes . ' ' . __('messages.minutes') : __('messages.unlimited') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add Questions Section -->
                    @if($availableQuestions->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#addQuestionsCollapse">
                                    <i class="fas fa-plus"></i> {{ __('messages.add_questions') }}
                                    <span class="badge badge-info ml-2" id="availableCount">{{ $availableQuestions->count() }} {{ __('messages.available') }}</span>
                                </button>
                            </h5>
                        </div>
                        <div id="addQuestionsCollapse" class="collapse">
                            <div class="card-body">
                                <!-- Filters -->
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label>{{ __('messages.search') }}</label>
                                        <input type="text" id="questionSearch" class="form-control" placeholder="{{ __('messages.search_questions') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label>{{ __('messages.course') }}</label>
                                        <select id="courseFilter" class="form-control">
                                            <option value="">{{ __('messages.all_courses') }}</option>
                                            @foreach($courses as $course)
                                                <option value="{{ $course->id }}">
                                                    {{ app()->getLocale() == 'ar' ? $course->name_ar : $course->name_en }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label>{{ __('messages.type') }}</label>
                                        <select id="typeFilter" class="form-control">
                                            <option value="">{{ __('messages.all_types') }}</option>
                                            @foreach($questionTypes as $type => $label)
                                                <option value="{{ $type }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>&nbsp;</label>
                                        <button type="button" class="btn btn-outline-secondary btn-block" id="clearFilters">
                                            <i class="fas fa-times"></i> {{ __('messages.clear') }}
                                        </button>
                                    </div>
                                </div>

                                <form action="{{ route('exams.questions.add', $exam) }}" method="POST" id="addQuestionsForm">
                                    @csrf
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th width="40px">
                                                        <input type="checkbox" id="selectAll" class="form-check-input">
                                                    </th>
                                                    <th>{{ __('messages.question') }}</th>
                                                    <th width="100px">{{ __('messages.type') }}</th>
                                                    <th width="120px">{{ __('messages.default_grade') }}</th>
                                                    <th width="120px">{{ __('messages.exam_grade') }}</th>
                                                    <th width="80px">{{ __('messages.actions') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody id="questionsTableBody">
                                                @foreach($availableQuestions as $question)
                                                <tr class="question-row" 
                                                    data-course="{{ $question->course_id }}" 
                                                    data-type="{{ $question->type }}"
                                                    data-search="{{ strtolower($question->title_en . ' ' . $question->title_ar . ' ' . $question->question_en . ' ' . $question->question_ar) }}">
                                                    <td>
                                                        <input type="checkbox" name="selected_questions[]" value="{{ $question->id }}"
                                                               class="form-check-input question-checkbox">
                                                    </td>
                                                    <td>
                                                        <div class="question-preview">
                                                            <strong>{{ app()->getLocale() == 'ar' ? $question->title_ar : $question->title_en }}</strong>
                                                            <p class="text-muted small mb-0">
                                                                {{ Str::limit(app()->getLocale() == 'ar' ? $question->question_ar : $question->question_en, 100) }}
                                                            </p>
                                                            @if($question->course)
                                                            <small class="text-info">
                                                                <i class="fas fa-book"></i> {{ app()->getLocale() == 'ar' ? $question->course->name_ar : $question->course->name_en }}
                                                            </small>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-secondary">
                                                            {{ ucfirst(str_replace('_', ' ', $question->type)) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-info">{{ number_format($question->grade, 2) }}</span>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="questions[{{ $question->id }}][grade]"
                                                               value="{{ $question->grade }}" step="0.01" min="0.1" max="999.99"
                                                               class="form-control form-control-sm grade-input" disabled>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-outline-info"
                                                                onclick="showQuestionDetails({{ $question->id }})"
                                                                title="{{ __('messages.view_details') }}">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div id="noQuestionsMessage" class="text-center py-3" style="display: none;">
                                            <p class="text-muted">{{ __('messages.no_questions_found') }}</p>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <div>
                                            <span id="selectedCount">0</span> {{ __('messages.questions_selected') }}
                                        </div>
                                        <button type="submit" class="btn btn-primary" id="addSelectedBtn" disabled>
                                            <i class="fas fa-plus"></i> {{ __('messages.add_selected_questions') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Current Exam Questions -->
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-list"></i> {{ __('messages.current_questions') }}
                                    <span class="badge badge-primary ml-2">{{ $exam->questions->count() }}</span>
                                </h5>
                                @if($exam->questions->count() > 0)
                                <button type="button" class="btn btn-sm btn-outline-primary" id="editModeBtn">
                                    <i class="fas fa-edit"></i> {{ __('messages.edit_mode') }}
                                </button>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            @if($exam->questions->count() > 0)
                            <form action="{{ route('exams.questions.update', $exam) }}" method="POST" id="updateQuestionsForm" style="display: none;">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-success mr-2">
                                        <i class="fas fa-save"></i> {{ __('messages.save_changes') }}
                                    </button>
                                    <button type="button" class="btn btn-secondary" id="cancelEditBtn">
                                        <i class="fas fa-times"></i> {{ __('messages.cancel') }}
                                    </button>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table" id="questionsTable">
                                    <thead>
                                        <tr>
                                            <th width="40px" class="drag-handle-header" style="display: none;">
                                                <i class="fas fa-arrows-alt"></i>
                                            </th>
                                            <th width="60px">{{ __('messages.order') }}</th>
                                            <th>{{ __('messages.question') }}</th>
                                            <th width="100px">{{ __('messages.type') }}</th>
                                            <th width="120px">{{ __('messages.grade') }}</th>
                                            <th width="120px">{{ __('messages.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="questionsList" class="sortable">
                                        @foreach($exam->questions->sortBy('pivot.order') as $question)
                                        <tr data-question-id="{{ $question->id }}">
                                            <td class="drag-handle" style="display: none;">
                                                <i class="fas fa-grip-vertical text-muted"></i>
                                            </td>
                                            <td>
                                                <span class="order-display">{{ $question->pivot->order }}</span>
                                                <input type="hidden" name="questions[{{ $loop->index }}][id]" value="{{ $question->id }}" class="question-id-input">
                                                <input type="number" name="questions[{{ $loop->index }}][order]" value="{{ $question->pivot->order }}"
                                                       class="form-control form-control-sm order-input" style="display: none;" min="1">
                                            </td>
                                            <td>
                                                <div class="question-info">
                                                    <strong>{{ app()->getLocale() == 'ar' ? $question->title_ar : $question->title_en }}</strong>
                                                    <p class="text-muted small mb-1">
                                                        {{ Str::limit(app()->getLocale() == 'ar' ? $question->question_ar : $question->question_en, 150) }}
                                                    </p>
                                                    @if($question->type == 'multiple_choice')
                                                        <div class="options-preview">
                                                            @foreach($question->options->take(2) as $option)
                                                                <small class="text-muted d-block">
                                                                    {{ chr(65 + $loop->index) }}. {{ Str::limit(app()->getLocale() == 'ar' ? $option->option_ar : $option->option_en, 50) }}
                                                                    @if($option->is_correct) <i class="fas fa-check text-success"></i> @endif
                                                                </small>
                                                            @endforeach
                                                            @if($question->options->count() > 2)
                                                                <small class="text-muted">... {{ $question->options->count() - 2 }} {{ __('messages.more_options') }}</small>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-secondary">
                                                    {{ ucfirst(str_replace('_', ' ', $question->type)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="grade-display">{{ number_format($question->pivot->grade, 2) }}</span>
                                                <input type="number" name="questions[{{ $loop->index }}][grade]" value="{{ $question->pivot->grade }}"
                                                       step="0.01" min="0.1" max="999.99" class="form-control form-control-sm grade-input" style="display: none;">
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-info"
                                                            onclick="showQuestionDetails({{ $question->id }})"
                                                            title="{{ __('messages.view_details') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <form action="{{ route('exams.questions.remove', [$exam, $question]) }}"
                                                          method="POST" class="d-inline remove-question-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger"
                                                                title="{{ __('messages.remove_question') }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
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
                                <h5 class="text-muted">{{ __('messages.no_questions_added') }}</h5>
                                <p class="text-muted">{{ __('messages.add_questions_to_exam_message') }}</p>
                                @if($availableQuestions->count() > 0)
                                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#addQuestionsCollapse">
                                    <i class="fas fa-plus"></i> {{ __('messages.add_questions') }}
                                </button>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Question Details Modal -->
<div class="modal fade" id="questionDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('messages.question_details') }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="questionDetailsContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    {{ __('messages.close') }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.info-box {
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.question-preview {
    max-width: 400px;
}

.sortable-ghost {
    opacity: 0.5;
    background-color: #f8f9fa;
}

.sortable-chosen {
    background-color: #e3f2fd;
}

.drag-handle {
    cursor: grab;
}

.drag-handle:active {
    cursor: grabbing;
}

.options-preview small {
    line-height: 1.2;
}

.table th {
    border-top: none;
    background-color: #f8f9fa;
    font-weight: 600;
}

.question-info {
    min-height: 60px;
}

.badge {
    font-size: 0.875em;
}

.question-row.table-primary {
    background-color: rgba(0, 123, 255, 0.1);
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
$(document).ready(function() {
    let isEditMode = false;
    let sortable = null;

    // Select All functionality
    $('#selectAll').change(function() {
        $('.question-checkbox:visible').prop('checked', this.checked);
        updateSelectedCount();
        toggleGradeInputs();
    });

    $(document).on('change', '.question-checkbox', function() {
        updateSelectedCount();
        toggleGradeInputs();

        // Update select all checkbox
        const visibleCheckboxes = $('.question-checkbox:visible').length;
        const checkedCheckboxes = $('.question-checkbox:visible:checked').length;
        $('#selectAll').prop('indeterminate', checkedCheckboxes > 0 && checkedCheckboxes < visibleCheckboxes);
        $('#selectAll').prop('checked', checkedCheckboxes === visibleCheckboxes && visibleCheckboxes > 0);
    });

    function updateSelectedCount() {
        const count = $('.question-checkbox:checked').length;
        $('#selectedCount').text(count);
        $('#addSelectedBtn').prop('disabled', count === 0);
    }

    function toggleGradeInputs() {
        $('.question-checkbox').each(function() {
            const row = $(this).closest('tr');
            const gradeInput = row.find('.grade-input');
            gradeInput.prop('disabled', !this.checked);

            if (this.checked) {
                row.addClass('table-primary');
            } else {
                row.removeClass('table-primary');
            }
        });
    }

    // Filter functionality
    function applyFilters() {
        const searchTerm = $('#questionSearch').val().toLowerCase();
        const courseFilter = $('#courseFilter').val();
        const typeFilter = $('#typeFilter').val();
        let visibleCount = 0;

        $('.question-row').each(function() {
            const $row = $(this);
            const searchText = $row.data('search');
            const course = $row.data('course');
            const type = $row.data('type');

            let show = true;

            // Search filter
            if (searchTerm && searchText.indexOf(searchTerm) === -1) {
                show = false;
            }

            // Course filter
            if (courseFilter && course != courseFilter) {
                show = false;
            }

            // Type filter
            if (typeFilter && type !== typeFilter) {
                show = false;
            }

            if (show) {
                $row.show();
                visibleCount++;
            } else {
                $row.hide();
                $row.find('.question-checkbox').prop('checked', false);
            }
        });

        $('#availableCount').text(visibleCount + ' {{ __("messages.available") }}');
        $('#noQuestionsMessage').toggle(visibleCount === 0);
        updateSelectedCount();
    }

    $('#questionSearch, #courseFilter, #typeFilter').on('input change', applyFilters);

    $('#clearFilters').click(function() {
        $('#questionSearch, #courseFilter, #typeFilter').val('');
        applyFilters();
    });

    // Edit Mode Toggle
    $('#editModeBtn').click(function() {
        toggleEditMode(true);
    });

    $('#cancelEditBtn').click(function() {
        toggleEditMode(false);
        location.reload();
    });

    function toggleEditMode(enable) {
        isEditMode = enable;

        if (enable) {
            $('#updateQuestionsForm').show();
            $('#editModeBtn').hide();
            $('.drag-handle-header, .drag-handle').show();
            $('.order-display, .grade-display').hide();
            $('.order-input, .grade-input').show();
            initSortable();
        } else {
            $('#updateQuestionsForm').hide();
            $('#editModeBtn').show();
            $('.drag-handle-header, .drag-handle').hide();
            $('.order-display, .grade-display').show();
            $('.order-input, .grade-input').hide();
            if (sortable) {
                sortable.destroy();
                sortable = null;
            }
        }
    }

    function initSortable() {
        const el = document.getElementById('questionsList');
        if (el && !sortable) {
            sortable = Sortable.create(el, {
                handle: '.drag-handle',
                animation: 150,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                onEnd: function(evt) {
                    updateQuestionOrder();
                }
            });
        }
    }

    function updateQuestionOrder() {
        $('#questionsList tr').each(function(index) {
            $(this).find('.order-input').val(index + 1);
            $(this).find('.order-display').text(index + 1);
            $(this).find('.question-id-input').attr('name', `questions[${index}][id]`);
            $(this).find('.order-input').attr('name', `questions[${index}][order]`);
            $(this).find('.grade-input').attr('name', `questions[${index}][grade]`);
        });
    }

    // Form submissions with confirmation
    $('#addQuestionsForm').submit(function(e) {
        const selectedCount = $('.question-checkbox:checked').length;
        if (selectedCount === 0) {
            e.preventDefault();
            alert('{{ __("messages.please_select_questions") }}');
            return false;
        }

        // Prepare the data in the correct format expected by the trait
        const formData = new FormData();
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        
        const questionsData = {};
        $('.question-checkbox:checked').each(function() {
            const questionId = $(this).val();
            const grade = $(this).closest('tr').find('.grade-input').val();
            questionsData[questionId] = {
                id: questionId,
                grade: parseFloat(grade)
            };
        });
        
        formData.append('questions', JSON.stringify(questionsData));

        if (!confirm(`{{ __("messages.confirm_add_questions") }} ${selectedCount} {{ __("messages.questions") }}?`)) {
            e.preventDefault();
            return false;
        }

        // Submit via AJAX to handle the JSON format properly
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message || 'An error occurred');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                if (response && response.errors) {
                    let errorMsg = 'Validation errors:\n';
                    Object.keys(response.errors).forEach(key => {
                        errorMsg += `- ${response.errors[key].join(', ')}\n`;
                    });
                    alert(errorMsg);
                } else {
                    alert('An error occurred while adding questions');
                }
            }
        });

        e.preventDefault();
    });

    $('#updateQuestionsForm').submit(function(e) {
        if (!confirm('{{ __("messages.confirm_update_questions") }}')) {
            e.preventDefault();
        }
    });

    $('.remove-question-form').submit(function(e) {
        if (!confirm('{{ __("messages.confirm_remove_question") }}')) {
            e.preventDefault();
        }
    });
});

// Show question details in modal
function showQuestionDetails(questionId) {
    $('#questionDetailsContent').html(`
        <div class="text-center">
            <i class="fas fa-spinner fa-spin fa-2x"></i>
            <p class="mt-2">{{ __("messages.loading") }}...</p>
        </div>
    `);
    $('#questionDetailsModal').modal('show');

    $.ajax({
        url: '{{ route("questions.details", ":questionId") }}'.replace(':questionId', questionId),
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const question = response.data;
                let optionsHtml = '';

                if (question.options && question.options.length > 0) {
                    optionsHtml = '<div class="mt-3"><h6>{{ __("messages.options") }}:</h6>';
                    question.options.forEach((option, index) => {
                        const letter = String.fromCharCode(65 + index);
                        const correctIcon = option.is_correct ? '<i class="fas fa-check text-success ml-2"></i>' : '';
                        const locale = '{{ app()->getLocale() }}';
                        const optionText = locale === 'ar' ? option.option_ar : option.option_en;
                        
                        optionsHtml += `
                            <div class="mb-2 p-2 ${option.is_correct ? 'bg-light border-left border-success' : ''}">
                                <strong>${letter}.</strong> ${optionText} ${correctIcon}
                            </div>
                        `;
                    });
                    optionsHtml += '</div>';
                }

                const locale = '{{ app()->getLocale() }}';
                const content = `
                    <div class="question-details">
                        <div class="mb-3">
                            <h5>${locale === 'ar' ? question.title_ar : question.title_en}</h5>
                        </div>
                        
                        <div class="mb-3">
                            <h6>{{ __("messages.question") }}:</h6>
                            <p>${locale === 'ar' ? question.question_ar : question.question_en}</p>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <strong>{{ __("messages.type") }}:</strong>
                                <span class="badge badge-secondary ml-2">${question.type.replace('_', ' ')}</span>
                            </div>
                            <div class="col-md-4">
                                <strong>{{ __("messages.grade") }}:</strong>
                                <span class="badge badge-info ml-2">${question.grade}</span>
                            </div>
                            <div class="col-md-4">
                                ${question.course ? `<strong>{{ __("messages.course") }}:</strong><br><small>${locale === 'ar' ? question.course.name_ar : question.course.name_en}</small>` : ''}
                            </div>
                        </div>

                        ${optionsHtml}

                        ${question.explanation_en || question.explanation_ar ? `
                            <div class="mt-3">
                                <h6>{{ __("messages.explanation") }}:</h6>
                                <p class="text-muted">${locale === 'ar' ? (question.explanation_ar || question.explanation_en) : (question.explanation_en || question.explanation_ar)}</p>
                            </div>
                        ` : ''}
                    </div>
                `;

                $('#questionDetailsContent').html(content);
            } else {
                $('#questionDetailsContent').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ __("messages.error_loading_question_details") }}
                    </div>
                `);
            }
        },
        error: function() {
            $('#questionDetailsContent').html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ __("messages.error_loading_question_details") }}
                </div>
            `);
        }
    });
}
</script>
@endpush