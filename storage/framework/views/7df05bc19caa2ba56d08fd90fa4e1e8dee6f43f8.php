

<?php $__env->startSection('title', __('messages.questions')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><?php echo e(__('messages.questions')); ?></h3>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('question-add')): ?>
                        <a href="<?php echo e(route('questions.create')); ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> <?php echo e(__('messages.add_question')); ?>

                        </a>
                    <?php endif; ?>
                </div>

                <div class="card-body">
                

                    <!-- Filters -->
                    <form method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <select name="course_id" class="form-control">
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
                                <select name="type" class="form-control">
                                    <option value=""><?php echo e(__('messages.all_types')); ?></option>
                                    <option value="multiple_choice" <?php echo e(request('type') == 'multiple_choice' ? 'selected' : ''); ?>>
                                        <?php echo e(__('messages.multiple_choice')); ?>

                                    </option>
                                    <option value="true_false" <?php echo e(request('type') == 'true_false' ? 'selected' : ''); ?>>
                                        <?php echo e(__('messages.true_false')); ?>

                                    </option>
                                    <option value="essay" <?php echo e(request('type') == 'essay' ? 'selected' : ''); ?>>
                                        <?php echo e(__('messages.essay')); ?>

                                    </option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="<?php echo e(__('messages.search_questions')); ?>" 
                                       value="<?php echo e(request('search')); ?>">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-search"></i> <?php echo e(__('messages.filter')); ?>

                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Questions Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo e(__('messages.title')); ?></th>
                                    <th><?php echo e(__('messages.course')); ?></th>
                                    <th><?php echo e(__('messages.type')); ?></th>
                                    <th><?php echo e(__('messages.grade')); ?></th>
                                    <th><?php echo e(__('messages.created_by')); ?></th>
                                    <th><?php echo e(__('messages.created_at')); ?></th>
                                    <th><?php echo e(__('messages.actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration); ?></td>
                                        <td>
                                            <strong><?php echo e($question->title_en); ?></strong><br>
                                            <small class="text-muted"><?php echo e($question->title_ar); ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-info"><?php echo e($question->course->title_en); ?></span>
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
                                            <?php if($question->creator): ?>
                                                <?php echo e($question->creator->name); ?>

                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($question->created_at->format('Y-m-d')); ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('question-table')): ?>
                                                    <a href="<?php echo e(route('questions.show', $question)); ?>" 
                                                       class="btn btn-sm btn-info"
                                                       title="<?php echo e(__('messages.view')); ?>">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                <?php endif; ?>
                                                
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('question-edit')): ?>
                                                    <a href="<?php echo e(route('questions.edit', $question)); ?>" 
                                                       class="btn btn-sm btn-warning"
                                                       title="<?php echo e(__('messages.edit')); ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                <?php endif; ?>
                                                
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('question-delete')): ?>
                                                    <form action="<?php echo e(route('questions.destroy', $question)); ?>" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('<?php echo e(__('messages.confirm_delete_question')); ?>')">
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
                                        <td colspan="8" class="text-center">
                                            <?php echo e(__('messages.no_questions_found')); ?>

                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        <?php echo e($questions->appends(request()->query())->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/admin/questions/index.blade.php ENDPATH**/ ?>