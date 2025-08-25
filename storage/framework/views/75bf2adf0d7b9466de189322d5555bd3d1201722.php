

<?php $__env->startSection('title', __('messages.view_exam')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1"><?php echo e(app()->getLocale() == 'ar' ? $exam->title_ar : $exam->title_en); ?></h2>
                    <p class="text-muted mb-0">
                        <?php echo e(__('messages.course')); ?>: <?php echo e(app()->getLocale() == 'ar' ? $exam->course->name_ar : $exam->course->name_en); ?>

                    </p>
                </div>
                <div class="btn-group">
                    <a href="<?php echo e(route('exams.edit', $exam)); ?>" class="btn btn-warning">
                        <i class="fas fa-edit"></i> <?php echo e(__('messages.edit')); ?>

                    </a>
                    <a href="<?php echo e(route('exams.questions.manage', $exam)); ?>" class="btn btn-primary">
                        <i class="fas fa-question"></i> <?php echo e(__('messages.manage_questions')); ?>

                    </a>
                    <a href="<?php echo e(route('exams.results', $exam)); ?>" class="btn btn-info">
                        <i class="fas fa-chart-bar"></i> <?php echo e(__('messages.view_results')); ?>

                    </a>
                    <a href="<?php echo e(route('exams.index')); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.back')); ?>

                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Status and Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-<?php echo e($exam->is_active ? 'success' : 'secondary'); ?>">
                    <i class="fas fa-<?php echo e($exam->is_active ? 'check-circle' : 'pause-circle'); ?>"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text"><?php echo e(__('messages.status')); ?></span>
                    <span class="info-box-number"><?php echo e($exam->is_active ? __('messages.active') : __('messages.inactive')); ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info">
                    <i class="fas fa-question"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text"><?php echo e(__('messages.total_questions')); ?></span>
                    <span class="info-box-number"><?php echo e($exam->questions->count()); ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-warning">
                    <i class="fas fa-star"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text"><?php echo e(__('messages.total_grade')); ?></span>
                    <span class="info-box-number"><?php echo e(number_format($exam->total_grade, 2)); ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-primary">
                    <i class="fas fa-users"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text"><?php echo e(__('messages.total_attempts')); ?></span>
                    <span class="info-box-number"><?php echo e($exam->attempts->count()); ?></span>
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
                        <i class="fas fa-info-circle"></i> <?php echo e(__('messages.exam_information')); ?>

                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th class="text-muted"><?php echo e(__('messages.title_en')); ?>:</th>
                                    <td><?php echo e($exam->title_en); ?></td>
                                </tr>
                                <tr>
                                    <th class="text-muted"><?php echo e(__('messages.title_ar')); ?>:</th>
                                    <td dir="rtl"><?php echo e($exam->title_ar); ?></td>
                                </tr>
                                <tr>
                                    <th class="text-muted"><?php echo e(__('messages.course')); ?>:</th>
                                    <td>
                                        <a href="<?php echo e(route('courses.show', $exam->course)); ?>" class="text-primary">
                                            <?php echo e(app()->getLocale() == 'ar' ? $exam->course->name_ar : $exam->course->name_en); ?>

                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted"><?php echo e(__('messages.duration')); ?>:</th>
                                    <td>
                                        <?php if($exam->duration_minutes): ?>
                                            <?php echo e($exam->duration_minutes); ?> <?php echo e(__('messages.minutes')); ?>

                                        <?php else: ?>
                                            <span class="text-success"><?php echo e(__('messages.unlimited')); ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted"><?php echo e(__('messages.attempts_allowed')); ?>:</th>
                                    <td><?php echo e($exam->attempts_allowed); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th class="text-muted"><?php echo e(__('messages.passing_grade')); ?>:</th>
                                    <td><?php echo e(number_format($exam->passing_grade, 2)); ?>%</td>
                                </tr>
                                <tr>
                                    <th class="text-muted"><?php echo e(__('messages.start_date')); ?>:</th>
                                    <td>
                                        <?php if($exam->start_date): ?>
                                            <?php echo e($exam->start_date->format('Y-m-d H:i')); ?>

                                        <?php else: ?>
                                            <span class="text-muted"><?php echo e(__('messages.no_restriction')); ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted"><?php echo e(__('messages.end_date')); ?>:</th>
                                    <td>
                                        <?php if($exam->end_date): ?>
                                            <?php echo e($exam->end_date->format('Y-m-d H:i')); ?>

                                            <?php if($exam->end_date->isPast()): ?>
                                                <span class="badge badge-danger ml-1"><?php echo e(__('messages.expired')); ?></span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted"><?php echo e(__('messages.no_restriction')); ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted"><?php echo e(__('messages.created_by')); ?>:</th>
                                    <td><?php echo e($exam->creator->name ?? __('messages.unknown')); ?></td>
                                </tr>
                                <tr>
                                    <th class="text-muted"><?php echo e(__('messages.created_at')); ?>:</th>
                                    <td><?php echo e($exam->created_at->format('Y-m-d H:i')); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <?php if($exam->description_en || $exam->description_ar): ?>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="text-muted"><?php echo e(__('messages.description')); ?>:</h6>
                            <?php if(app()->getLocale() == 'ar' && $exam->description_ar): ?>
                                <p dir="rtl"><?php echo e($exam->description_ar); ?></p>
                            <?php elseif($exam->description_en): ?>
                                <p><?php echo e($exam->description_en); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Exam Settings -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cog"></i> <?php echo e(__('messages.exam_settings')); ?>

                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded">
                                <i class="fas fa-random fa-2x text-<?php echo e($exam->shuffle_questions ? 'success' : 'muted'); ?> mb-2"></i>
                                <h6><?php echo e(__('messages.shuffle_questions')); ?></h6>
                                <span class="badge badge-<?php echo e($exam->shuffle_questions ? 'success' : 'secondary'); ?>">
                                    <?php echo e($exam->shuffle_questions ? __('messages.enabled') : __('messages.disabled')); ?>

                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded">
                                <i class="fas fa-exchange-alt fa-2x text-<?php echo e($exam->shuffle_options ? 'success' : 'muted'); ?> mb-2"></i>
                                <h6><?php echo e(__('messages.shuffle_options')); ?></h6>
                                <span class="badge badge-<?php echo e($exam->shuffle_options ? 'success' : 'secondary'); ?>">
                                    <?php echo e($exam->shuffle_options ? __('messages.enabled') : __('messages.disabled')); ?>

                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded">
                                <i class="fas fa-eye fa-2x text-<?php echo e($exam->show_results_immediately ? 'success' : 'muted'); ?> mb-2"></i>
                                <h6><?php echo e(__('messages.show_results_immediately')); ?></h6>
                                <span class="badge badge-<?php echo e($exam->show_results_immediately ? 'success' : 'secondary'); ?>">
                                    <?php echo e($exam->show_results_immediately ? __('messages.enabled') : __('messages.disabled')); ?>

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
                            <i class="fas fa-list"></i> <?php echo e(__('messages.questions')); ?> (<?php echo e($exam->questions->count()); ?>)
                        </h5>
                        <a href="<?php echo e(route('exams.questions.manage', $exam)); ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i> <?php echo e(__('messages.manage')); ?>

                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if($exam->questions->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="60px"><?php echo e(__('messages.order')); ?></th>
                                        <th><?php echo e(__('messages.question')); ?></th>
                                        <th width="100px"><?php echo e(__('messages.type')); ?></th>
                                        <th width="80px"><?php echo e(__('messages.grade')); ?></th>
                                        <th width="100px"><?php echo e(__('messages.options')); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $exam->questions->sortBy('pivot.order'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="text-center">
                                            <span class="badge badge-light"><?php echo e($question->pivot->order); ?></span>
                                        </td>
                                        <td>
                                            <strong class="d-block"><?php echo e(app()->getLocale() == 'ar' ? $question->title_ar : $question->title_en); ?></strong>
                                            <small class="text-muted">
                                                <?php echo e(Str::limit(app()->getLocale() == 'ar' ? $question->question_ar : $question->question_en, 100)); ?>

                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">
                                                <?php echo e(ucfirst(str_replace('_', ' ', $question->type))); ?>

                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-success"><?php echo e(number_format($question->pivot->grade, 2)); ?></span>
                                        </td>
                                        <td class="text-center">
                                            <?php if($question->type == 'multiple_choice'): ?>
                                                <span class="badge badge-secondary"><?php echo e($question->options->count()); ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
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
                            <p class="text-muted"><?php echo e(__('messages.add_questions_to_start')); ?></p>
                            <a href="<?php echo e(route('exams.questions.manage', $exam)); ?>" class="btn btn-primary">
                                <i class="fas fa-plus"></i> <?php echo e(__('messages.add_questions')); ?>

                            </a>
                        </div>
                    <?php endif; ?>
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
                            <i class="fas fa-clock"></i> <?php echo e(__('messages.recent_attempts')); ?>

                        </h6>
                        <?php if($exam->attempts->count() > 0): ?>
                        <a href="<?php echo e(route('exams.results', $exam)); ?>" class="btn btn-sm btn-outline-info">
                            <?php echo e(__('messages.view_all')); ?>

                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <?php if($exam->attempts->take(5)->count() > 0): ?>
                        <?php $__currentLoopData = $exam->attempts->sortByDesc('started_at')->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attempt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex align-items-center mb-3 pb-2 border-bottom">
                            <div class="flex-shrink-0">
                                <i class="fas fa-user-circle fa-2x text-muted"></i>
                            </div>
                            <div class="flex-grow-1 ml-3">
                                <h6 class="mb-1"><?php echo e($attempt->user->name); ?></h6>
                                <small class="text-muted d-block"><?php echo e($attempt->started_at->diffForHumans()); ?></small>
                                <div class="mt-1">
                                    <span class="badge badge-<?php echo e($attempt->status == 'completed' ? 'success' : 
                                        ($attempt->status == 'in_progress' ? 'warning' : 'secondary')); ?>">
                                        <?php echo e(__('messages.' . $attempt->status)); ?>

                                    </span>
                                    <?php if($attempt->status == 'completed'): ?>
                                        <span class="badge badge-light ml-1"><?php echo e(number_format($attempt->percentage, 1)); ?>%</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <div class="text-center py-3">
                            <i class="fas fa-clock fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0"><?php echo e(__('messages.no_attempts_yet')); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Statistics -->
            <?php if($exam->attempts->where('status', 'completed')->count() > 0): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-chart-pie"></i> <?php echo e(__('messages.statistics')); ?>

                    </h6>
                </div>
                <div class="card-body">
                    <?php
                        $completedAttempts = $exam->attempts->where('status', 'completed');
                        $passedAttempts = $completedAttempts->where('is_passed', true);
                        $averageScore = $completedAttempts->avg('percentage');
                        $highestScore = $completedAttempts->max('percentage');
                        $lowestScore = $completedAttempts->min('percentage');
                    ?>
                    
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <h4 class="text-success mb-1"><?php echo e($passedAttempts->count()); ?></h4>
                            <small class="text-muted"><?php echo e(__('messages.passed')); ?></small>
                        </div>
                        <div class="col-6 mb-3">
                            <h4 class="text-danger mb-1"><?php echo e($completedAttempts->count() - $passedAttempts->count()); ?></h4>
                            <small class="text-muted"><?php echo e(__('messages.failed')); ?></small>
                        </div>
                        <div class="col-12 mb-2">
                            <h5 class="text-primary mb-1"><?php echo e(number_format($averageScore, 1)); ?>%</h5>
                            <small class="text-muted"><?php echo e(__('messages.average_score')); ?></small>
                        </div>
                    </div>
                    
                    <div class="progress mb-2" style="height: 8px;">
                        <div class="progress-bar bg-success" 
                             style="width: <?php echo e($completedAttempts->count() > 0 ? ($passedAttempts->count() / $completedAttempts->count()) * 100 : 0); ?>%"
                             title="<?php echo e(__('messages.pass_rate')); ?>: <?php echo e($completedAttempts->count() > 0 ? number_format(($passedAttempts->count() / $completedAttempts->count()) * 100, 1) : 0); ?>%">
                        </div>
                    </div>
                    <small class="text-muted">
                        <?php echo e(__('messages.pass_rate')); ?>: <?php echo e($completedAttempts->count() > 0 ? number_format(($passedAttempts->count() / $completedAttempts->count()) * 100, 1) : 0); ?>%
                    </small>
                    
                    <hr>
                    <div class="d-flex justify-content-between">
                        <small class="text-muted"><?php echo e(__('messages.highest')); ?>: <?php echo e(number_format($highestScore, 1)); ?>%</small>
                        <small class="text-muted"><?php echo e(__('messages.lowest')); ?>: <?php echo e(number_format($lowestScore, 1)); ?>%</small>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-bolt"></i> <?php echo e(__('messages.quick_actions')); ?>

                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('exams.edit', $exam)); ?>" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-edit"></i> <?php echo e(__('messages.edit_exam')); ?>

                        </a>
                        <a href="<?php echo e(route('exams.questions.manage', $exam)); ?>" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-question"></i> <?php echo e(__('messages.manage_questions')); ?>

                        </a>
                        <?php if($exam->attempts->count() > 0): ?>
                        <a href="<?php echo e(route('exams.results', $exam)); ?>" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-chart-bar"></i> <?php echo e(__('messages.view_results')); ?>

                        </a>
                        <?php endif; ?>
                     
                        <hr>
                        <form action="<?php echo e(route('exams.destroy', $exam)); ?>" method="POST" class="delete-exam-form">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                <i class="fas fa-trash"></i> <?php echo e(__('messages.delete_exam')); ?>

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
    
    if (confirm('<?php echo e(__("messages.confirm_delete_exam")); ?>')) {
        this.submit();
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/admin/exams/show.blade.php ENDPATH**/ ?>