<?php $__env->startSection('title', __('messages.exams')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><?php echo e(__('messages.exams')); ?></h3>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('exam-add')): ?>
                        <a href="<?php echo e(route('exams.create')); ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> <?php echo e(__('messages.add_exam')); ?>

                        </a>
                    <?php endif; ?>
                </div>

                <div class="card-body">

                    <!-- Filters -->
                    <form method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <select name="course_id" class="form-select">
                                    <option value=""><?php echo e(__('messages.all_courses')); ?></option>
                                    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($course->id); ?>" 
                                                <?php echo e(request('course_id') == $course->id ? 'selected' : ''); ?>>
                                            <?php echo e($course->title_en); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-select">
                                    <option value=""><?php echo e(__('messages.all_statuses')); ?></option>
                                    <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>
                                        <?php echo e(__('messages.active')); ?>

                                    </option>
                                    <option value="inactive" <?php echo e(request('status') == 'inactive' ? 'selected' : ''); ?>>
                                        <?php echo e(__('messages.inactive')); ?>

                                    </option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="<?php echo e(__('messages.search_exams')); ?>" 
                                       value="<?php echo e(request('search')); ?>">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-search"></i> <?php echo e(__('messages.filter')); ?>

                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Exams Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo e(__('messages.title')); ?></th>
                                    <th><?php echo e(__('messages.course')); ?></th>
                                    <th><?php echo e(__('messages.questions')); ?></th>
                                    <th><?php echo e(__('messages.total_grade')); ?></th>
                                    <th><?php echo e(__('messages.duration')); ?></th>
                                    <th><?php echo e(__('messages.status')); ?></th>
                                    <th><?php echo e(__('messages.created_at')); ?></th>
                                    <th><?php echo e(__('messages.actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration); ?></td>
                                        <td>
                                            <strong><?php echo e($exam->title_en); ?></strong><br>
                                            <small class="text-muted"><?php echo e($exam->title_ar); ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info"><?php echo e($exam->course->title_en); ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary"><?php echo e($exam->questions->count()); ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success"><?php echo e($exam->total_grade); ?></span>
                                        </td>
                                        <td>
                                            <?php if($exam->duration_minutes): ?>
                                                <?php echo e($exam->formatted_duration); ?>

                                            <?php else: ?>
                                                <span class="text-muted"><?php echo e(__('messages.unlimited')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($exam->is_active): ?>
                                                <span class="badge bg-success"><?php echo e(__('messages.active')); ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-danger"><?php echo e(__('messages.inactive')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($exam->created_at->format('Y-m-d')); ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('exam-table')): ?>
                                                    <a href="<?php echo e(route('exams.show', $exam)); ?>" 
                                                       class="btn btn-sm btn-info"
                                                       title="<?php echo e(__('messages.view')); ?>">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                <?php endif; ?>

                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('exam-edit')): ?>
                                                    <a href="<?php echo e(route('exams.questions.manage', $exam)); ?>" 
                                                       class="btn btn-sm btn-success"
                                                       title="<?php echo e(__('messages.manage_questions')); ?>">
                                                        <i class="fas fa-list"></i>
                                                    </a>
                                                <?php endif; ?>

                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('exam-table')): ?>
                                                    <a href="<?php echo e(route('exams.results', $exam)); ?>" 
                                                       class="btn btn-sm btn-primary"
                                                       title="<?php echo e(__('messages.results')); ?>">
                                                        <i class="fas fa-chart-bar"></i>
                                                    </a>
                                                <?php endif; ?>
                                                
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('exam-edit')): ?>
                                                    <a href="<?php echo e(route('exams.edit', $exam)); ?>" 
                                                       class="btn btn-sm btn-warning"
                                                       title="<?php echo e(__('messages.edit')); ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                <?php endif; ?>
                                                
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('exam-delete')): ?>
                                                    <form action="<?php echo e(route('exams.destroy', $exam)); ?>" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('<?php echo e(__('messages.confirm_delete_exam')); ?>')">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-danger"
                                                                title="<?php echo e(__('messages.delete')); ?>">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="9" class="text-center">
                                            <?php echo e(__('messages.no_exams_found')); ?>

                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        <?php echo e($exams->appends(request()->query())->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/admin/exams/index.blade.php ENDPATH**/ ?>