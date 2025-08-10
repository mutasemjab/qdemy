<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><?php echo e(__('messages.Settings Management')); ?></h3>
                  
                </div>

                <!-- Search -->
                <div class="card-body">
                    <form method="GET" action="<?php echo e(route('settings.index')); ?>" class="mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="<?php echo e(__('messages.Search by email, phone, address...')); ?>" 
                                       value="<?php echo e(request('search')); ?>">
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-search"></i> <?php echo e(__('messages.Search')); ?>

                                </button>
                                <a href="<?php echo e(route('settings.index')); ?>" class="btn btn-secondary">
                                    <i class="fas fa-redo"></i> <?php echo e(__('messages.Reset')); ?>

                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Settings Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('messages.ID')); ?></th>
                                    <th><?php echo e(__('messages.Logo')); ?></th>
                                    <th><?php echo e(__('messages.Contact Info')); ?></th>
                                    <th><?php echo e(__('messages.App Links')); ?></th>
                                    <th><?php echo e(__('messages.Statistics')); ?></th>
                                    <th><?php echo e(__('messages.Actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $settings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($setting->id); ?></td>
                                        <td>
                                            <?php if($setting->logo): ?>
                                                <img src="<?php echo e(asset('assets/admin/uploads/' . $setting->logo)); ?>" 
                                                     alt="Logo" 
                                                     class="img-thumbnail" 
                                                     style="width: 60px; height: 60px; object-fit: contain;">
                                            <?php else: ?>
                                                <span class="text-muted"><?php echo e(__('messages.No Logo')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?php echo e(__('messages.Email')); ?>:</strong> <?php echo e($setting->email); ?><br>
                                            <strong><?php echo e(__('messages.Phone')); ?>:</strong> <?php echo e($setting->phone); ?><br>
                                            <?php if($setting->address): ?>
                                                <strong><?php echo e(__('messages.Address')); ?>:</strong> <?php echo e(Str::limit($setting->address, 30)); ?>

                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($setting->google_play_link): ?>
                                                <span class="badge badge-success"><?php echo e(__('messages.Google Play')); ?></span><br>
                                            <?php endif; ?>
                                            <?php if($setting->app_store_link): ?>
                                                <span class="badge badge-info"><?php echo e(__('messages.App Store')); ?></span><br>
                                            <?php endif; ?>
                                            <?php if($setting->hawawi_link): ?>
                                                <span class="badge badge-warning"><?php echo e(__('messages.Huawei')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($setting->number_of_students): ?>
                                                <small><strong><?php echo e(__('messages.Students')); ?>:</strong> <?php echo e($setting->number_of_students); ?></small><br>
                                            <?php endif; ?>
                                            <?php if($setting->number_of_teacher): ?>
                                                <small><strong><?php echo e(__('messages.Teachers')); ?>:</strong> <?php echo e($setting->number_of_teacher); ?></small><br>
                                            <?php endif; ?>
                                            <?php if($setting->number_of_course): ?>
                                                <small><strong><?php echo e(__('messages.Courses')); ?>:</strong> <?php echo e($setting->number_of_course); ?></small><br>
                                            <?php endif; ?>
                                            <?php if($setting->number_of_viewing_hour): ?>
                                                <small><strong><?php echo e(__('messages.Hours')); ?>:</strong> <?php echo e($setting->number_of_viewing_hour); ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                            
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('setting-edit')): ?>
                                                    <a href="<?php echo e(route('settings.edit', $setting)); ?>" 
                                                       class="btn btn-sm btn-warning" title="<?php echo e(__('messages.Edit')); ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                <?php endif; ?>
                                               
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="text-center"><?php echo e(__('messages.No settings found')); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        <?php echo e($settings->appends(request()->query())->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\qdemy\resources\views/admin/settings/index.blade.php ENDPATH**/ ?>