

<?php $__env->startSection('title', __('messages.question_details')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php echo e(__('messages.question_details')); ?></h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('questions.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.back')); ?>

                        </a>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('question-edit')): ?>
                        <a href="<?php echo e(route('questions.edit', $question)); ?>" class="btn btn-warning">
                            <i class="fas fa-edit"></i> <?php echo e(__('messages.edit')); ?>

                        </a>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('question-delete')): ?>
                        <form action="<?php echo e(route('questions.destroy', $question)); ?>" method="POST" class="d-inline" 
                              onsubmit="return confirm('<?php echo e(__('messages.confirm_delete_question')); ?>')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> <?php echo e(__('messages.delete')); ?>

                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Question Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-left-primary h-100">
                                <div class="card-body">
                                    <h5 class="text-primary"><?php echo e(__('messages.question_title_en')); ?></h5>
                                    <p class="h6"><?php echo e($question->title_en); ?></p>
                                    
                                    <h5 class="text-primary mt-3"><?php echo e(__('messages.question_text_en')); ?></h5>
                                    <div class="border p-3 bg-light rounded">
                                        <?php echo e($question->question_en); ?>

                                    </div>
                                    
                                    <?php if($question->explanation_en): ?>
                                    <h5 class="text-primary mt-3"><?php echo e(__('messages.explanation_en')); ?></h5>
                                    <div class="border p-3 bg-info text-white rounded">
                                        <i class="fas fa-info-circle"></i> <?php echo e($question->explanation_en); ?>

                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card border-left-success h-100">
                                <div class="card-body">
                                    <h5 class="text-success"><?php echo e(__('messages.question_title_ar')); ?></h5>
                                    <p class="h6" dir="rtl"><?php echo e($question->title_ar); ?></p>
                                    
                                    <h5 class="text-success mt-3"><?php echo e(__('messages.question_text_ar')); ?></h5>
                                    <div class="border p-3 bg-light rounded" dir="rtl">
                                        <?php echo e($question->question_ar); ?>

                                    </div>
                                    
                                    <?php if($question->explanation_ar): ?>
                                    <h5 class="text-success mt-3"><?php echo e(__('messages.explanation_ar')); ?></h5>
                                    <div class="border p-3 bg-info text-white rounded" dir="rtl">
                                        <i class="fas fa-info-circle"></i> <?php echo e($question->explanation_ar); ?>

                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Question Metadata -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong><?php echo e(__('messages.course')); ?>:</strong><br>
                                            <span class="badge bg-primary"><?php echo e($question->course->title_en); ?></span>
                                        </div>
                                        <div class="col-md-3">
                                            <strong><?php echo e(__('messages.question_type')); ?>:</strong><br>
                                            <?php if($question->type === 'multiple_choice'): ?>
                                                <span class="badge bg-info"><?php echo e(__('messages.multiple_choice')); ?></span>
                                            <?php elseif($question->type === 'true_false'): ?>
                                                <span class="badge bg-warning"><?php echo e(__('messages.true_false')); ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary"><?php echo e(__('messages.essay')); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-3">
                                            <strong><?php echo e(__('messages.grade')); ?>:</strong><br>
                                            <span class="badge bg-success"><?php echo e($question->grade); ?> <?php echo e(__('messages.points')); ?></span>
                                        </div>
                                        <div class="col-md-3">
                                            <strong><?php echo e(__('messages.created_by')); ?>:</strong><br>
                                            <span class="badge bg-dark"><?php echo e($question->creator->name ?? __('messages.system')); ?></span>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <strong><?php echo e(__('messages.created_at')); ?>:</strong><br>
                                            <small class="text-muted"><?php echo e($question->created_at->format('Y-m-d H:i:s')); ?></small>
                                        </div>
                                        <div class="col-md-6">
                                            <strong><?php echo e(__('messages.updated_at')); ?>:</strong><br>
                                            <small class="text-muted"><?php echo e($question->updated_at->format('Y-m-d H:i:s')); ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Question Options -->
                    <?php if($question->type === 'multiple_choice' || $question->type === 'true_false'): ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-list"></i> <?php echo e(__('messages.answer_options')); ?>

                                    </h5>
                                </div>
                                <div class="card-body">
                                    <?php if($question->options->isNotEmpty()): ?>
                                        <div class="row">
                                            <?php $__currentLoopData = $question->options->sortBy('order'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="col-md-6 mb-3">
                                                <div class="card <?php echo e($option->is_correct ? 'border-success' : 'border-secondary'); ?>">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <h6 class="mb-0">
                                                                <span class="badge <?php echo e($option->is_correct ? 'bg-success' : 'bg-secondary'); ?>">
                                                                    <?php echo e(__('messages.option')); ?> <?php echo e(chr(65 + $index)); ?>

                                                                </span>
                                                            </h6>
                                                            <?php if($option->is_correct): ?>
                                                                <span class="badge bg-success">
                                                                    <i class="fas fa-check"></i> <?php echo e(__('messages.correct')); ?>

                                                                </span>
                                                            <?php endif; ?>
                                                        </div>
                                                        
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <strong><?php echo e(__('messages.english')); ?>:</strong>
                                                                <p class="mb-1"><?php echo e($option->option_en); ?></p>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <strong><?php echo e(__('messages.arabic')); ?>:</strong>
                                                                <p class="mb-1" dir="rtl"><?php echo e($option->option_ar); ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <?php echo e(__('messages.no_options_found')); ?>

                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($question->type === 'essay'): ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-secondary text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-pen"></i> <?php echo e(__('messages.essay_question')); ?>

                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        <?php echo e(__('messages.essay_question_grading_note')); ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Usage in Exams -->
                    <?php if($question->examQuestions->isNotEmpty()): ?>
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0">
                                        <i class="fas fa-clipboard-list"></i> <?php echo e(__('messages.used_in_exams')); ?>

                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th><?php echo e(__('messages.exam_title')); ?></th>
                                                    <th><?php echo e(__('messages.order')); ?></th>
                                                    <th><?php echo e(__('messages.grade_in_exam')); ?></th>
                                                    <th><?php echo e(__('messages.exam_status')); ?></th>
                                                    <th><?php echo e(__('messages.actions')); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $question->examQuestions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $examQuestion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td>
                                                        <strong><?php echo e($examQuestion->exam->title_en); ?></strong><br>
                                                        <small class="text-muted"><?php echo e($examQuestion->exam->title_ar); ?></small>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info"><?php echo e($examQuestion->order); ?></span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-success"><?php echo e($examQuestion->grade); ?> <?php echo e(__('messages.points')); ?></span>
                                                    </td>
                                                    <td>
                                                        <?php if($examQuestion->exam->is_active): ?>
                                                            <span class="badge bg-success"><?php echo e(__('messages.active')); ?></span>
                                                        <?php else: ?>
                                                            <span class="badge bg-danger"><?php echo e(__('messages.inactive')); ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('exam-view')): ?>
                                                        <a href="<?php echo e(route('exams.show', $examQuestion->exam)); ?>" class="btn btn-sm btn-info">
                                                            <i class="fas fa-eye"></i> <?php echo e(__('messages.view_exam')); ?>

                                                        </a>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="<?php echo e(route('questions.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.back_to_questions')); ?>

                        </a>
                        <div>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('question-edit')): ?>
                            <a href="<?php echo e(route('questions.edit', $question)); ?>" class="btn btn-warning">
                                <i class="fas fa-edit"></i> <?php echo e(__('messages.edit_question')); ?>

                            </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('question-add')): ?>
                            <a href="<?php echo e(route('questions.create')); ?>" class="btn btn-success">
                                <i class="fas fa-plus"></i> <?php echo e(__('messages.add_new_question')); ?>

                            </a>
                            <?php endif; ?>
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
                <h5 class="modal-title"><?php echo e(__('messages.question_preview')); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><?php echo e($question->title_en); ?></h6>
                        <small><?php echo e(__('messages.grade')); ?>: <?php echo e($question->grade); ?> <?php echo e(__('messages.points')); ?></small>
                    </div>
                    <div class="card-body">
                        <div class="question-text mb-3">
                            <p><strong><?php echo e($question->question_en); ?></strong></p>
                        </div>
                        
                        <?php if($question->type === 'multiple_choice'): ?>
                            <div class="options">
                                <?php $__currentLoopData = $question->options->sortBy('order'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="preview_answer" disabled>
                                    <label class="form-check-label">
                                        <strong><?php echo e(chr(65 + $index)); ?>.</strong> <?php echo e($option->option_en); ?>

                                        <?php if($option->is_correct): ?>
                                            <span class="badge bg-success ms-2"><?php echo e(__('messages.correct')); ?></span>
                                        <?php endif; ?>
                                    </label>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php elseif($question->type === 'true_false'): ?>
                            <div class="options">
                                <?php $__currentLoopData = $question->options->sortBy('order'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="preview_answer" disabled>
                                    <label class="form-check-label">
                                        <?php echo e($option->option_en); ?>

                                        <?php if($option->is_correct): ?>
                                            <span class="badge bg-success ms-2"><?php echo e(__('messages.correct')); ?></span>
                                        <?php endif; ?>
                                    </label>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <i class="fas fa-pen"></i> <?php echo e(__('messages.essay_answer_area')); ?>

                                <textarea class="form-control mt-2" rows="4" disabled placeholder="<?php echo e(__('messages.student_will_type_answer_here')); ?>"></textarea>
                            </div>
                        <?php endif; ?>
                        
                        <?php if($question->explanation_en): ?>
                        <div class="mt-3">
                            <div class="alert alert-info">
                                <strong><?php echo e(__('messages.explanation')); ?>:</strong><br>
                                <?php echo e($question->explanation_en); ?>

                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(__('messages.close')); ?></button>
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/admin/questions/show.blade.php ENDPATH**/ ?>