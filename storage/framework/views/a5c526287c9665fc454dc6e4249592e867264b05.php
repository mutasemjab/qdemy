<?php $__env->startSection('title', __('messages.manage_exam_questions')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title mb-0"><?php echo e(__('messages.manage_exam_questions')); ?></h3>
                            <p class="text-muted mb-0">
                                <?php echo e(__('messages.exam')); ?>: <?php echo e(app()->getLocale() == 'ar' ? $exam->title_ar : $exam->title_en); ?>

                                | <?php echo e(__('messages.course')); ?>: <?php echo e(app()->getLocale() == 'ar' ? $exam->course->name_ar : $exam->course->name_en); ?>

                            </p>
                        </div>
                        <div class="btn-group">
                            <a href="<?php echo e(route('exams.show', $exam)); ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-eye"></i> <?php echo e(__('messages.view_exam')); ?>

                            </a>
                            <a href="<?php echo e(route('exams.index')); ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.back_to_exams')); ?>

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
                                    <span class="info-box-text"><?php echo e(__('messages.total_questions')); ?></span>
                                    <span class="info-box-number"><?php echo e($exam->questions->count()); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-star"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><?php echo e(__('messages.total_grade')); ?></span>
                                    <span class="info-box-number"><?php echo e(number_format($exam->total_grade, 2)); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-percentage"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><?php echo e(__('messages.passing_grade')); ?></span>
                                    <span class="info-box-number"><?php echo e(number_format($exam->passing_grade, 2)); ?>%</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary"><i class="fas fa-clock"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><?php echo e(__('messages.duration')); ?></span>
                                    <span class="info-box-number">
                                        <?php echo e($exam->duration_minutes ? $exam->duration_minutes . ' ' . __('messages.minutes') : __('messages.unlimited')); ?>

                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add Questions Section -->
                    <?php if($availableQuestions->count() > 0): ?>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#addQuestionsCollapse">
                                    <i class="fas fa-plus"></i> <?php echo e(__('messages.add_questions')); ?>

                                    <span class="badge badge-info ml-2"><?php echo e($availableQuestions->count()); ?> <?php echo e(__('messages.available')); ?></span>
                                </button>
                            </h5>
                        </div>
                        <div id="addQuestionsCollapse" class="collapse">
                            <div class="card-body">
                                <form action="<?php echo e(route('exams.questions.add', $exam)); ?>" method="POST" id="addQuestionsForm">
                                    <?php echo csrf_field(); ?>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th width="40px">
                                                        <input type="checkbox" id="selectAll" class="form-check-input">
                                                    </th>
                                                    <th><?php echo e(__('messages.question')); ?></th>
                                                    <th width="100px"><?php echo e(__('messages.type')); ?></th>
                                                    <th width="120px"><?php echo e(__('messages.default_grade')); ?></th>
                                                    <th width="120px"><?php echo e(__('messages.exam_grade')); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $availableQuestions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="selected_questions[]" value="<?php echo e($question->id); ?>"
                                                               class="form-check-input question-checkbox">
                                                    </td>
                                                    <td>
                                                        <div class="question-preview">
                                                            <strong><?php echo e(app()->getLocale() == 'ar' ? $question->title_ar : $question->title_en); ?></strong>
                                                            <p class="text-muted small mb-0">
                                                                <?php echo e(Str::limit(app()->getLocale() == 'ar' ? $question->question_ar : $question->question_en, 100)); ?>

                                                            </p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-secondary">
                                                            <?php echo e(ucfirst(str_replace('_', ' ', $question->type))); ?>

                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-info"><?php echo e(number_format($question->grade, 2)); ?></span>
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="questions[<?php echo e($question->id); ?>][id]" value="<?php echo e($question->id); ?>">
                                                        <input type="number" name="questions[<?php echo e($question->id); ?>][grade]"
                                                               value="<?php echo e($question->grade); ?>" step="0.01" min="0.1" max="999.99"
                                                               class="form-control form-control-sm" disabled>
                                                    </td>
                                                </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <div>
                                            <span id="selectedCount">0</span> <?php echo e(__('messages.questions_selected')); ?>

                                        </div>
                                        <button type="submit" class="btn btn-primary" id="addSelectedBtn" disabled>
                                            <i class="fas fa-plus"></i> <?php echo e(__('messages.add_selected_questions')); ?>

                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Current Exam Questions -->
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-list"></i> <?php echo e(__('messages.current_questions')); ?>

                                    <span class="badge badge-primary ml-2"><?php echo e($exam->questions->count()); ?></span>
                                </h5>
                                <?php if($exam->questions->count() > 0): ?>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="editModeBtn">
                                    <i class="fas fa-edit"></i> <?php echo e(__('messages.edit_mode')); ?>

                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if($exam->questions->count() > 0): ?>
                            <form action="<?php echo e(route('exams.questions.update', $exam)); ?>" method="POST" id="updateQuestionsForm" style="display: none;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-success mr-2">
                                        <i class="fas fa-save"></i> <?php echo e(__('messages.save_changes')); ?>

                                    </button>
                                    <button type="button" class="btn btn-secondary" id="cancelEditBtn">
                                        <i class="fas fa-times"></i> <?php echo e(__('messages.cancel')); ?>

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
                                            <th width="60px"><?php echo e(__('messages.order')); ?></th>
                                            <th><?php echo e(__('messages.question')); ?></th>
                                            <th width="100px"><?php echo e(__('messages.type')); ?></th>
                                            <th width="120px"><?php echo e(__('messages.grade')); ?></th>
                                            <th width="120px"><?php echo e(__('messages.actions')); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody id="questionsList" class="sortable">
                                        <?php $__currentLoopData = $exam->questions->sortBy('pivot.order'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr data-question-id="<?php echo e($question->id); ?>">
                                            <td class="drag-handle" style="display: none;">
                                                <i class="fas fa-grip-vertical text-muted"></i>
                                            </td>
                                            <td>
                                                <span class="order-display"><?php echo e($question->pivot->order); ?></span>
                                                <input type="hidden" name="questions[<?php echo e($loop->index); ?>][id]" value="<?php echo e($question->id); ?>" class="question-id-input">
                                                <input type="number" name="questions[<?php echo e($loop->index); ?>][order]" value="<?php echo e($question->pivot->order); ?>"
                                                       class="form-control form-control-sm order-input" style="display: none;" min="1">
                                            </td>
                                            <td>
                                                <div class="question-info">
                                                    <strong><?php echo e(app()->getLocale() == 'ar' ? $question->title_ar : $question->title_en); ?></strong>
                                                    <p class="text-muted small mb-1">
                                                        <?php echo e(Str::limit(app()->getLocale() == 'ar' ? $question->question_ar : $question->question_en, 150)); ?>

                                                    </p>
                                                    <?php if($question->type == 'multiple_choice'): ?>
                                                        <div class="options-preview">
                                                            <?php $__currentLoopData = $question->options->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <small class="text-muted d-block">
                                                                    <?php echo e(chr(65 + $loop->index)); ?>. <?php echo e(Str::limit(app()->getLocale() == 'ar' ? $option->option_ar : $option->option_en, 50)); ?>

                                                                    <?php if($option->is_correct): ?> <i class="fas fa-check text-success"></i> <?php endif; ?>
                                                                </small>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            <?php if($question->options->count() > 2): ?>
                                                                <small class="text-muted">... <?php echo e($question->options->count() - 2); ?> <?php echo e(__('messages.more_options')); ?></small>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-secondary">
                                                    <?php echo e(ucfirst(str_replace('_', ' ', $question->type))); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <span class="grade-display"><?php echo e(number_format($question->pivot->grade, 2)); ?></span>
                                                <input type="number" name="questions[<?php echo e($loop->index); ?>][grade]" value="<?php echo e($question->pivot->grade); ?>"
                                                       step="0.01" min="0.1" max="999.99" class="form-control form-control-sm grade-input" style="display: none;">
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-info"
                                                            onclick="showQuestionDetails(<?php echo e($question->id); ?>)"
                                                            title="<?php echo e(__('messages.view_details')); ?>">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <form action="<?php echo e(route('exams.questions.remove', [$exam, $question])); ?>"
                                                          method="POST" class="d-inline remove-question-form">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="btn btn-outline-danger"
                                                                title="<?php echo e(__('messages.remove_question')); ?>">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted"><?php echo e(__('messages.no_questions_added')); ?></h5>
                                <p class="text-muted"><?php echo e(__('messages.add_questions_to_exam_message')); ?></p>
                                <?php if($availableQuestions->count() > 0): ?>
                                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#addQuestionsCollapse">
                                    <i class="fas fa-plus"></i> <?php echo e(__('messages.add_questions')); ?>

                                </button>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
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
                <h5 class="modal-title"><?php echo e(__('messages.question_details')); ?></h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="questionDetailsContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <?php echo e(__('messages.close')); ?>

                </button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
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
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
$(document).ready(function() {
    let isEditMode = false;
    let sortable = null;

    // Select All functionality
    $('#selectAll').change(function() {
        $('.question-checkbox').prop('checked', this.checked);
        updateSelectedCount();
        toggleGradeInputs();
    });

    $('.question-checkbox').change(function() {
        updateSelectedCount();
        toggleGradeInputs();

        // Update select all checkbox
        const totalCheckboxes = $('.question-checkbox').length;
        const checkedCheckboxes = $('.question-checkbox:checked').length;
        $('#selectAll').prop('indeterminate', checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes);
        $('#selectAll').prop('checked', checkedCheckboxes === totalCheckboxes);
    });

    function updateSelectedCount() {
        const count = $('.question-checkbox:checked').length;
        $('#selectedCount').text(count);
        $('#addSelectedBtn').prop('disabled', count === 0);
    }

    function toggleGradeInputs() {
        $('.question-checkbox').each(function() {
            const questionId = $(this).val();
            const gradeInput = $(`input[name="questions[${questionId}][grade]"]`);
            gradeInput.prop('disabled', !this.checked);

            if (this.checked) {
                $(this).closest('tr').addClass('table-primary');
            } else {
                $(this).closest('tr').removeClass('table-primary');
            }
        });
    }

    // Edit Mode Toggle
    $('#editModeBtn').click(function() {
        toggleEditMode(true);
    });

    $('#cancelEditBtn').click(function() {
        toggleEditMode(false);
        // Reset form values
        location.reload();
    });

    function toggleEditMode(enable) {
        isEditMode = enable;

        if (enable) {
            // Show edit form and hide edit button
            $('#updateQuestionsForm').show();
            $('#editModeBtn').hide();

            // Show drag handles and input fields
            $('.drag-handle-header, .drag-handle').show();
            $('.order-display, .grade-display').hide();
            $('.order-input, .grade-input').show();

            // Initialize sortable
            initSortable();

        } else {
            // Hide edit form and show edit button
            $('#updateQuestionsForm').hide();
            $('#editModeBtn').show();

            // Hide drag handles and input fields
            $('.drag-handle-header, .drag-handle').hide();
            $('.order-display, .grade-display').show();
            $('.order-input, .grade-input').hide();

            // Destroy sortable
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

            // Update input names to maintain correct order
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
            alert('<?php echo e(__("messages.please_select_questions")); ?>');
            return false;
        }

        if (!confirm(`<?php echo e(__("messages.confirm_add_questions")); ?> ${selectedCount} <?php echo e(__("messages.questions")); ?>?`)) {
            e.preventDefault();
        }
    });

    $('#updateQuestionsForm').submit(function(e) {
        if (!confirm('<?php echo e(__("messages.confirm_update_questions")); ?>')) {
            e.preventDefault();
        }
    });

    $('.remove-question-form').submit(function(e) {
        if (!confirm('<?php echo e(__("messages.confirm_remove_question")); ?>')) {
            e.preventDefault();
        }
    });
});

// Show question details in modal
function showQuestionDetails(questionId) {
    // You can implement AJAX call to fetch question details
    // For now, we'll show a placeholder
    $('#questionDetailsContent').html(`
        <div class="text-center">
            <i class="fas fa-spinner fa-spin fa-2x"></i>
            <p class="mt-2"><?php echo e(__("messages.loading")); ?>...</p>
        </div>
    `);
    $('#questionDetailsModal').modal('show');

    // Simulate loading (replace with actual AJAX call)
    setTimeout(() => {
        $('#questionDetailsContent').html(`
            <p><?php echo e(__("messages.question_details_placeholder")); ?></p>
            <p><strong>ID:</strong> ${questionId}</p>
        `);
    }, 1000);
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/admin/exams/manage-questions.blade.php ENDPATH**/ ?>