<?php $__env->startSection('title', __('messages.courses')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><?php echo e(__('messages.courses')); ?></h3>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('course-add')): ?>
                        <a href="<?php echo e(route('courses.create')); ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> <?php echo e(__('messages.add_course')); ?>

                        </a>
                    <?php endif; ?>
                </div>

                <div class="card-body">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo e(__('messages.photo')); ?></th>
                                    <th><?php echo e(__('messages.title')); ?></th>
                                    <th><?php echo e(__('messages.teacher')); ?></th>
                                    <th><?php echo e(__('messages.category')); ?></th>
                                    <th><?php echo e(__('messages.price')); ?></th>
                                    <th><?php echo e(__('messages.created_at')); ?></th>
                                    <th><?php echo e(__('messages.actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration); ?></td>
                                        <td>
                                            <img src="<?php echo e($course->photo_url); ?>" 
                                                 alt="<?php echo e($course->title); ?>" 
                                                 class="img-thumbnail" 
                                                 style="width: 60px; height: 60px; object-fit: cover;">
                                        </td>
                                        <td>
                                            <strong><?php echo e($course->title_en); ?></strong><br>
                                            <small class="text-muted"><?php echo e($course->title_ar); ?></small>
                                        </td>
                                        <td>
                                            <?php if($course->teacher): ?>
                                                <?php echo e($course->teacher->name); ?>

                                            <?php else: ?>
                                                <span class="text-muted"><?php echo e(__('messages.no_teacher')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($course->category): ?>
                                                <?php echo e($course->category->name); ?>

                                            <?php else: ?>
                                                <span class="text-muted"><?php echo e(__('messages.no_category')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">JD <?php echo e(number_format($course->selling_price, 2)); ?></span>
                                        </td>
                                        <td><?php echo e($course->created_at->format('Y-m-d')); ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('course-table')): ?>
                                                    <a href="<?php echo e(route('courses.show', $course)); ?>" 
                                                       class="btn btn-sm btn-info"
                                                       title="<?php echo e(__('messages.view')); ?>">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                <?php endif; ?>

                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('course-edit')): ?>
                                                    <a href="<?php echo e(route('courses.sections.index', $course)); ?>" 
                                                       class="btn btn-sm btn-success"
                                                       title="<?php echo e(__('messages.manage_sections_contents')); ?>">
                                                        <i class="fas fa-list"></i>
                                                    </a>
                                                <?php endif; ?>
                                                
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('course-edit')): ?>
                                                    <a href="<?php echo e(route('courses.edit', $course)); ?>" 
                                                       class="btn btn-sm btn-warning"
                                                       title="<?php echo e(__('messages.edit')); ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                <?php endif; ?>
                                                
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('course-delete')): ?>
                                                    <form action="<?php echo e(route('courses.destroy', $course)); ?>" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('<?php echo e(__('messages.confirm_delete')); ?>')">
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
                                            <?php echo e(__('messages.no_courses_found')); ?>

                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        <?php echo e($courses->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/admin/courses/index.blade.php ENDPATH**/ ?>