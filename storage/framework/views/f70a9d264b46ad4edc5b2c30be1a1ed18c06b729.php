<div class="category-item border-bottom" data-category-id="<?php echo e($category->id); ?>">
    <div class="d-flex align-items-center p-3 category-header"
         style="padding-left: <?php echo e(($level * 20) + 15); ?>px !important;">

        <!-- Toggle Button -->
        <?php if($category->children()->where('is_active', true)->count() > 0): ?>
            <button type="button" class="btn btn-sm btn-link p-0 mr-2 toggle-btn"
                    data-toggle="collapse"
                    data-target="#children-<?php echo e($category->id); ?>"
                    aria-expanded="false">
                <i class="fas fa-chevron-right transition-icon"></i>
            </button>
        <?php else: ?>
            <span class="mr-4"></span>
        <?php endif; ?>

        <!-- Category Icon -->
        <?php if($category->icon): ?>
            <span class="mr-2" style="color: <?php echo e($category->color); ?>">
                <i class="<?php echo e($category->icon); ?>"></i>
            </span>
        <?php endif; ?>

        <!-- Category Info -->
        <div class="flex-grow-1">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="font-weight-bold"><?php echo e($category->name_ar); ?></span>
                    <?php if($category->name_en): ?>
                        <small class="text-muted ml-2">(<?php echo e($category->name_en); ?>)</small>
                    <?php endif; ?>

                     <?php if($category->type == 'lesson'): ?>
                            <span class="badge badge-success mr-1" title="<?php echo e(__('messages.Lesson - Teachable subject')); ?>">
                                <i class="fas fa-book-open"></i> <?php echo e(__('messages.Lesson')); ?>

                            </span>
                        <?php elseif($category->type == 'major'): ?>
                            <span class="badge badge-primary mr-1" title="<?php echo e(__('messages.Major - Main program')); ?>">
                                <i class="fas fa-star"></i> <?php echo e(__('messages.Major')); ?>

                            </span>
                        <?php else: ?>
                            <span class="badge badge-secondary mr-1" title="<?php echo e(__('messages.Class - Organizational category')); ?>">
                                <i class="fas fa-folder"></i> <?php echo e(__('messages.Class')); ?>

                            </span>
                        <?php endif; ?>

                    <!-- Badges -->
                    <div class="mt-1">
                        <?php if($category->is_active): ?>
                            <span class="badge badge-success mr-1"><?php echo e(__('messages.Active')); ?></span>
                        <?php else: ?>
                            <span class="badge badge-danger mr-1"><?php echo e(__('messages.Inactive')); ?></span>
                        <?php endif; ?>

                        <?php if($category->children()->count() > 0): ?>
                            <span class="badge badge-info mr-1">
                                <?php echo e($category->children()->count()); ?> <?php echo e(__('messages.Children')); ?>

                            </span>
                        <?php endif; ?>

                        <span class="badge badge-secondary"><?php echo e(__('messages.Level')); ?> <?php echo e($level + 1); ?></span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="btn-group" role="group">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('category-table')): ?>
                        <a href="<?php echo e(route('categories.show', $category)); ?>"
                           class="btn btn-sm btn-outline-info"
                           title="<?php echo e(__('messages.View')); ?>">
                            <i class="fas fa-eye"></i>
                        </a>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('category-edit')): ?>
                        <a href="<?php echo e(route('categories.edit', $category)); ?>"
                           class="btn btn-sm btn-outline-primary"
                           title="<?php echo e(__('messages.Edit')); ?>">
                            <i class="fas fa-edit"></i>
                        </a>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('category-edit')): ?>
                        <form method="POST" action="<?php echo e(route('categories.toggle-status', $category)); ?>"
                              class="d-inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PATCH'); ?>
                            <button type="submit"
                                    class="btn btn-sm btn-outline-<?php echo e($category->is_active ? 'warning' : 'success'); ?>"
                                    title="<?php echo e($category->is_active ? __('messages.Deactivate') : __('messages.Activate')); ?>">
                                <i class="fas fa-<?php echo e($category->is_active ? 'pause' : 'play'); ?>"></i>
                            </button>
                        </form>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

    <!-- Children -->
    <?php if($category->children()->where('is_active', true)->count() > 0): ?>
        <div class="collapse" id="children-<?php echo e($category->id); ?>">
            <?php $__currentLoopData = $category->children()->where('is_active', true)->orderBy('sort_order')->orderBy('name_ar')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('admin.categories.partials.tree-item', ['category' => $child, 'level' => $level + 1], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>
<?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/admin/categories/partials/tree-item.blade.php ENDPATH**/ ?>