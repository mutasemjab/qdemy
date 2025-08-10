<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><?php echo e(__('messages.Teachers Management')); ?></h3>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('teacher-add')): ?>
                        <a href="<?php echo e(route('teachers.create')); ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> <?php echo e(__('messages.Add Teacher')); ?>

                        </a>
                    <?php endif; ?>
                </div>

                <!-- Filters -->
                <div class="card-body">
                    <form method="GET" action="<?php echo e(route('teachers.index')); ?>" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="<?php echo e(__('messages.Search teachers...')); ?>" 
                                       value="<?php echo e(request('search')); ?>">
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="lesson" class="form-control" 
                                       placeholder="<?php echo e(__('messages.Lesson')); ?>" 
                                       value="<?php echo e(request('lesson')); ?>">
                            </div>
                            <div class="col-md-2">
                                <select name="has_user" class="form-control">
                                    <option value=""><?php echo e(__('messages.All Teachers')); ?></option>
                                    <option value="yes" <?php echo e(request('has_user') == 'yes' ? 'selected' : ''); ?>>
                                        <?php echo e(__('messages.With User Account')); ?>

                                    </option>
                                    <option value="no" <?php echo e(request('has_user') == 'no' ? 'selected' : ''); ?>>
                                        <?php echo e(__('messages.Without User Account')); ?>

                                    </option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-search"></i> <?php echo e(__('messages.Search')); ?>

                                </button>
                                <a href="<?php echo e(route('teachers.index')); ?>" class="btn btn-secondary">
                                    <i class="fas fa-redo"></i> <?php echo e(__('messages.Reset')); ?>

                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Teachers Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('messages.ID')); ?></th>
                                    <th><?php echo e(__('messages.Photo')); ?></th>
                                    <th><?php echo e(__('messages.Teacher Info')); ?></th>
                                    <th><?php echo e(__('messages.Lesson')); ?></th>
                                    <th><?php echo e(__('messages.Social Media')); ?></th>
                                    <th><?php echo e(__('messages.User Account')); ?></th>
                                    <th><?php echo e(__('messages.Created At')); ?></th>
                                    <th><?php echo e(__('messages.Actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($teacher->id); ?></td>
                                        <td>
                                            <?php if($teacher->photo): ?>
                                                <img src="<?php echo e(asset('assets/admin/uploads/' . $teacher->photo)); ?>" 
                                                     alt="<?php echo e($teacher->name); ?>" 
                                                     class="img-thumbnail" 
                                                     style="width: 60px; height: 60px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" 
                                                     style="width: 60px; height: 60px; border-radius: 4px;">
                                                    <?php echo e(substr($teacher->name, 0, 1)); ?>

                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?php echo e($teacher->name); ?></strong><br>
                                            <?php if($teacher->description_en): ?>
                                                <small class="text-muted"><?php echo e(Str::limit($teacher->description_en, 50)); ?></small>
                                            <?php elseif($teacher->description_ar): ?>
                                                <small class="text-muted"><?php echo e(Str::limit($teacher->description_ar, 50)); ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge badge-primary"><?php echo e($teacher->name_of_lesson); ?></span>
                                        </td>
                                        <td>
                                            <div class="social-links">
                                                <?php if($teacher->facebook): ?>
                                                    <a href="<?php echo e($teacher->facebook); ?>" target="_blank" class="text-primary me-2" title="Facebook">
                                                        <i class="fab fa-facebook"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if($teacher->instagram): ?>
                                                    <a href="<?php echo e($teacher->instagram); ?>" target="_blank" class="text-danger me-2" title="Instagram">
                                                        <i class="fab fa-instagram"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if($teacher->youtube): ?>
                                                    <a href="<?php echo e($teacher->youtube); ?>" target="_blank" class="text-danger me-2" title="YouTube">
                                                        <i class="fab fa-youtube"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if(!$teacher->facebook && !$teacher->instagram && !$teacher->youtube): ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if($teacher->user): ?>
                                                <span class="badge badge-success">
                                                    <i class="fas fa-user-check"></i> <?php echo e(__('messages.Has Account')); ?>

                                                </span>
                                                <br><small><?php echo e($teacher->user->email); ?></small>
                                            <?php else: ?>
                                                <span class="badge badge-secondary">
                                                    <i class="fas fa-user-times"></i> <?php echo e(__('messages.No Account')); ?>

                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($teacher->created_at->format('M d, Y')); ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('teacher-table')): ?>
                                                    <a href="<?php echo e(route('teachers.show', $teacher)); ?>" 
                                                       class="btn btn-sm btn-info" title="<?php echo e(__('messages.View')); ?>">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('teacher-edit')): ?>
                                                    <a href="<?php echo e(route('teachers.edit', $teacher)); ?>" 
                                                       class="btn btn-sm btn-warning" title="<?php echo e(__('messages.Edit')); ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('teacher-delete')): ?>
                                                    <form action="<?php echo e(route('teachers.destroy', $teacher)); ?>" 
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('<?php echo e(__('messages.Are you sure you want to delete this teacher? This will also delete the associated user account if exists.')); ?>')">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                                title="<?php echo e(__('messages.Delete')); ?>">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="8" class="text-center"><?php echo e(__('messages.No teachers found')); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        <?php echo e($teachers->appends(request()->query())->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.social-links a {
    font-size: 1.2rem;
    margin-right: 0.5rem;
}

.social-links a:hover {
    opacity: 0.7;
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\qdemy\resources\views/admin/teachers/index.blade.php ENDPATH**/ ?>