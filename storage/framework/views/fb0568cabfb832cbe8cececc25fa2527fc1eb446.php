

<?php $__env->startSection('title', __('messages.manage_exam_questions')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title"><?php echo e(__('messages.manage_exam_questions')); ?></h3>
                            <p class="text-muted mb-0">
                                <?php echo e(__('messages.exam')); ?>: <?php echo e($exam->title_en); ?> | 
                                <?php echo e(__('messages.total_grade')); ?>: <?php echo e($exam->total_grade); ?>

                            </p>
                        </div>
                        <div>
                            <a href="<?php echo e(route('exams.index')); ?>" class="btn btn-secondary me-2">
                                <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.back_to_exams')); ?>

                            </a>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addQuestionsModal">
                                <i class="fas fa-plus"></i> <?php echo e(__('messages.add_questions')); ?>

                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    

                    <!-- Current Questions -->
                    <?php if($exam->questions->count() > 0): ?>
                        <div class="mb-4">
                            <h5><?php echo e(__('messages.current_questions')); ?> (<?php echo e($exam->questions->count()); ?>)</h5>
                            
                            <form action="<?php echo e(route('exams.questions.update', $exam)); ?>" method="POST" id="updateQuestionsForm">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                                
                                <div class="table-responsive">
                                    <table class="table table-striped" id="questionsTable">
                                        <thead>
                                            <tr>
                                                <th width="5%"><?php echo e(__('messages.order')); ?></th>
                                                <th width="30%"><?php echo e(__('messages.question')); ?></th>
                                                <th width="15%"><?php echo e(__('messages.type')); ?></th>
                                                <th width="10%"><?php echo e(__('messages.default_grade')); ?></th>
                                                <th width="10%"><?php echo e(__('messages.exam_grade')); ?></th>
                                                <th width="20%"><?php echo e(__('messages.options_preview')); ?></th>
                                                <th width="10%"><?php echo e(__('messages.actions')); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody id="questionsTableBody">
                                            <?php $__currentLoopData = $exam->questions->sortBy('pivot.order'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr data-question-id="<?php echo e($question->id); ?>">
                                                    <td>
                                                        <input type="hidden" name="questions[<?php echo e($loop->index); ?>][id]" value="<?php echo e($question->id); ?>">
                                                        <input type="number" 
                                                               class="form-control form-control-sm" 
                                                               name="questions[<?php echo e($loop->index); ?>][order]" 
                                                               value="<?php echo e($question->pivot->order); ?>" 
                                                               min="1"
                                                               style="width: 60px;">
                                                    </td>
                                                    <td>
                                                        <strong><?php echo e($question->title_en); ?></strong><br>
                                                        <small class="text-muted"><?php echo e($question->title_ar); ?></small>
                                                    </td>
                                                    <td>
                                                        <?php if($question->type === 'multiple_choice'): ?>
                                                            <span class="badge bg-primary">
                                                                <i class="fas fa-list"></i> <?php echo e(__('messages.multiple_choice')); ?>

                                                            </span>
                                                        <?php elseif($question->type === 'true_false'): ?>
                                                            <span class="badge bg-success">
                                                                <i class="fas fa-check-circle"></i> <?php echo e(__('messages.true_false')); ?>

                                                            </span>
                                                        <?php else: ?>
                                                            <span class="badge bg-warning">
                                                                <i class="fas fa-edit"></i> <?php echo e(__('messages.essay')); ?>

                                                            </span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-secondary"><?php echo e($question->grade); ?></span>
                                                    </td>
                                                    <td>
                                                        <input type="number" 
                                                               class="form-control form-control-sm" 
                                                               name="questions[<?php echo e($loop->index); ?>][grade]" 
                                                               value="<?php echo e($question->pivot->grade); ?>" 
                                                               step="0.25" 
                                                               min="0.25"
                                                               style="width: 80px;">
                                                    </td>
                                                    <td>
                                                        <?php if($question->options->count() > 0): ?>
                                                            <div class="small">
                                                                <?php $__currentLoopData = $question->options->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <div class="mb-1">
                                                                        <span class="badge <?php echo e($option->is_correct ? 'bg-success' : 'bg-light text-dark'); ?> badge-sm">
                                                                            <?php echo e($option->letter); ?>

                                                                        </span>
                                                                        <?php echo e(Str::limit($option->option_en, 20)); ?>

                                                                    </div>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                <?php if($question->options->count() > 2): ?>
                                                                    <small class="text-muted">+<?php echo e($question->options->count() - 2); ?> more</small>
                                                                <?php endif; ?>
                                                            </div>
                                                        <?php else: ?>
                                                            <span class="text-muted"><?php echo e(__('messages.no_options')); ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-info me-1" 
                                                                onclick="previewQuestion(<?php echo e($question->id); ?>)"
                                                                title="<?php echo e(__('messages.preview')); ?>">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <a href="<?php echo e(route('exams.questions.remove', [$exam, $question])); ?>" 
                                                           class="btn btn-sm btn-outline-danger"
                                                           onclick="return confirm('<?php echo e(__('messages.confirm_remove_question')); ?>')"
                                                           title="<?php echo e(__('messages.remove')); ?>">
                                                            <i class="fas fa-times"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save"></i> <?php echo e(__('messages.update_questions')); ?>

                                    </button>
                                </div>
                            </form>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle fa-2x mb-2"></i>
                                <h5><?php echo e(__('messages.no_questions_added')); ?></h5>
                                <p><?php echo e(__('messages.add_questions_to_exam')); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
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
                <h5 class="modal-title"><?php echo e(__('messages.question_preview')); ?></h5>
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
                    '<div class="alert alert-warning"><?php echo e(__("messages.preview_not_available")); ?></div>';
            }
            
            const modal = new bootstrap.Modal(document.getElementById('questionPreviewModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('questionPreviewContent').innerHTML = 
                '<div class="alert alert-danger"><?php echo e(__("messages.error_loading_preview")); ?></div>';
        });
}

// Form validation for add questions
document.getElementById('addQuestionsForm').addEventListener('submit', function(e) {
    const checkedBoxes = document.querySelectorAll('.question-checkbox:checked');
    
    if (checkedBoxes.length === 0) {
        e.preventDefault();
        alert('<?php echo e(__("messages.please_select_at_least_one_question")); ?>');
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
        alert('<?php echo e(__("messages.please_enter_valid_grades")); ?>');
        return;
    }
});

// Sortable functionality for questions table
document.addEventListener('DOMContentLoaded', function() {
    // You can add drag-and-drop sorting here if needed
    // using libraries like Sortable.js
});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\qdemy\resources\views/admin/exams/manage-questions.blade.php ENDPATH**/ ?>