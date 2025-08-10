<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><?php echo e(__('messages.Parents Management')); ?></h3>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('parent-add')): ?>
                        <a href="<?php echo e(route('parents.create')); ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> <?php echo e(__('messages.Add Parent')); ?>

                        </a>
                    <?php endif; ?>
                </div>

                <!-- Filters -->
                <div class="card-body">
                    <form method="GET" action="<?php echo e(route('parents.index')); ?>" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="<?php echo e(__('messages.Search parents...')); ?>" 
                                       value="<?php echo e(request('search')); ?>">
                            </div>
                            <div class="col-md-2">
                                <select name="has_user" class="form-control">
                                    <option value=""><?php echo e(__('messages.All Parents')); ?></option>
                                    <option value="yes" <?php echo e(request('has_user') == 'yes' ? 'selected' : ''); ?>>
                                        <?php echo e(__('messages.With User Account')); ?>

                                    </option>
                                    <option value="no" <?php echo e(request('has_user') == 'no' ? 'selected' : ''); ?>>
                                        <?php echo e(__('messages.Without User Account')); ?>

                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="students_count" class="form-control">
                                    <option value=""><?php echo e(__('messages.All Parents')); ?></option>
                                    <option value="with_students" <?php echo e(request('students_count') == 'with_students' ? 'selected' : ''); ?>>
                                        <?php echo e(__('messages.With Students')); ?>

                                    </option>
                                    <option value="no_students" <?php echo e(request('students_count') == 'no_students' ? 'selected' : ''); ?>>
                                        <?php echo e(__('messages.Without Students')); ?>

                                    </option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-search"></i> <?php echo e(__('messages.Search')); ?>

                                </button>
                                <a href="<?php echo e(route('parents.index')); ?>" class="btn btn-secondary">
                                    <i class="fas fa-redo"></i> <?php echo e(__('messages.Reset')); ?>

                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Parents Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('messages.ID')); ?></th>
                                    <th><?php echo e(__('messages.Photo')); ?></th>
                                    <th><?php echo e(__('messages.Parent Info')); ?></th>
                                    <th><?php echo e(__('messages.Students')); ?></th>
                                    <th><?php echo e(__('messages.User Account')); ?></th>
                                    <th><?php echo e(__('messages.Created At')); ?></th>
                                    <th><?php echo e(__('messages.Actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $parents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($parent->id); ?></td>
                                        <td>
                                            <?php if($parent->user && $parent->user->photo): ?>
                                                <img src="<?php echo e(asset('assets/admin/uploads/' . $parent->user->photo)); ?>" 
                                                     alt="<?php echo e($parent->name); ?>" 
                                                     class="img-thumbnail" 
                                                     style="width: 60px; height: 60px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-warning text-white d-flex align-items-center justify-content-center" 
                                                     style="width: 60px; height: 60px; border-radius: 4px;">
                                                    <?php echo e(substr($parent->name, 0, 1)); ?>

                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?php echo e($parent->name); ?></strong><br>
                                            <?php if($parent->user): ?>
                                                <small class="text-muted"><?php echo e($parent->user->email); ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">
                                                <?php echo e($parent->students_count); ?> <?php echo e(__('messages.Students')); ?>

                                            </span>
                                            <?php if($parent->students_count > 0): ?>
                                                <br>
                                                <?php $__currentLoopData = $parent->students->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <small class="text-muted d-block">• <?php echo e($student->name); ?></small>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($parent->students_count > 3): ?>
                                                    <small class="text-muted"><?php echo e(__('messages.and :count more', ['count' => $parent->students_count - 3])); ?></small>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($parent->user): ?>
                                                <span class="badge badge-success">
                                                    <i class="fas fa-user-check"></i> <?php echo e(__('messages.Has Account')); ?>

                                                </span>
                                                <br>
                                                <small><?php echo e($parent->user->email); ?></small>
                                                <?php if($parent->user->phone): ?>
                                                    <br><small><?php echo e($parent->user->phone); ?></small>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="badge badge-secondary">
                                                    <i class="fas fa-user-times"></i> <?php echo e(__('messages.No Account')); ?>

                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($parent->created_at->format('M d, Y')); ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('parent-table')): ?>
                                                    <a href="<?php echo e(route('parents.show', $parent)); ?>" 
                                                       class="btn btn-sm btn-info" title="<?php echo e(__('messages.View')); ?>">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('parent-edit')): ?>
                                                    <a href="<?php echo e(route('parents.edit', $parent)); ?>" 
                                                       class="btn btn-sm btn-warning" title="<?php echo e(__('messages.Edit')); ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('parent-delete')): ?>
                                                    <form action="<?php echo e(route('parents.destroy', $parent)); ?>" 
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('<?php echo e(__('messages.Are you sure you want to delete this parent? This will also delete the associated user account and student relationships if they exist.')); ?>')">
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
                                        <td colspan="7" class="text-center"><?php echo e(__('messages.No parents found')); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        <?php echo e($parents->appends(request()->query())->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\qdemy\resources\views/admin/parents/index.blade.php ENDPATH**/ ?>