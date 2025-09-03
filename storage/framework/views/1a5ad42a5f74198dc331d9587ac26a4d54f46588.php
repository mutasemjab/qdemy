

<?php $__env->startSection('title', __('messages.Packages')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0"><?php echo e(__('messages.Packages')); ?></h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('messages.Dashboard')); ?></a></li>
                    <li class="breadcrumb-item active"><?php echo e(__('messages.Packages')); ?></li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('package-add')): ?>
                <a href="<?php echo e(route('packages.create')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i><?php echo e(__('messages.Add Package')); ?>

                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('packages.index')); ?>" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label"><?php echo e(__('messages.Search')); ?></label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="<?php echo e(request('search')); ?>" placeholder="<?php echo e(__('messages.Search packages...')); ?>">
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label"><?php echo e(__('messages.Status')); ?></label>
                    <select class="form-control" id="status" name="status">
                        <option value=""><?php echo e(__('messages.All Statuses')); ?></option>
                        <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>
                            <?php echo e(__('messages.Active')); ?>

                        </option>
                        <option value="inactive" <?php echo e(request('status') === 'inactive' ? 'selected' : ''); ?>>
                            <?php echo e(__('messages.Inactive')); ?>

                        </option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="type" class="form-label"><?php echo e(__('messages.Type')); ?></label>
                    <select class="form-control" id="type" name="type">
                        <option value=""><?php echo e(__('messages.All Types')); ?></option>
                        <option value="class" <?php echo e(request('type') === 'class' ? 'selected' : ''); ?>>
                            <?php echo e(__('messages.Class')); ?>

                        </option>
                        <option value="subject" <?php echo e(request('type') === 'subject' ? 'selected' : ''); ?>>
                            <?php echo e(__('messages.Subject')); ?>

                        </option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <div class="d-flex gap-2 w-100">
                        <button type="submit" class="btn btn-outline-primary flex-grow-1">
                            <i class="fas fa-search"></i>
                        </button>
                        <a href="<?php echo e(route('packages.index')); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Packages Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?php echo e(__('messages.Packages List')); ?></h5>
            <?php if($packages->count() > 0): ?>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-success" onclick="bulkAction('activate')">
                        <i class="fas fa-check"></i> <?php echo e(__('messages.Activate Selected')); ?>

                    </button>
                    <button type="button" class="btn btn-sm btn-outline-warning" onclick="bulkAction('deactivate')">
                        <i class="fas fa-pause"></i> <?php echo e(__('messages.Deactivate Selected')); ?>

                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="bulkAction('delete')">
                        <i class="fas fa-trash"></i> <?php echo e(__('messages.Delete Selected')); ?>

                    </button>
                </div>
            <?php endif; ?>
        </div>
        <div class="card-body p-0">
            <?php if($packages->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="30">
                                    <input type="checkbox" id="select-all" class="form-check-input">
                                </th>
                                <th width="80"><?php echo e(__('messages.Image')); ?></th>
                                <th><?php echo e(__('messages.Name')); ?></th>
                                <th><?php echo e(__('messages.Price')); ?></th>
                                <th><?php echo e(__('messages.Type')); ?></th>
                                <th><?php echo e(__('messages.Course Selection')); ?></th>
                                <th><?php echo e(__('messages.Categories')); ?></th>
                                <th><?php echo e(__('messages.Subjects')); ?></th>
                                <th><?php echo e(__('messages.Status')); ?></th>
                                <th width="150"><?php echo e(__('messages.Actions')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="selected_packages[]" 
                                               value="<?php echo e($package->id); ?>" class="form-check-input package-checkbox">
                                    </td>
                                    <td>
                                        <?php if($package->image): ?>
                                            <img src="<?php echo e($package->image_url); ?>" alt="<?php echo e($package->name); ?>" 
                                                 class="img-thumbnail" width="50" height="50" style="object-fit: cover;">
                                        <?php else: ?>
                                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                                 style="width: 50px; height: 50px; border-radius: 0.375rem;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div>
                                            <strong><?php echo e($package->name); ?></strong>
                                            <?php if($package->description): ?>
                                                <br><small class="text-muted"><?php echo e(Str::limit($package->description, 50)); ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold"><?php echo e($package->formatted_price); ?></span>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo e($package->type_badge_class); ?>">
                                            <?php echo e(ucfirst($package->type)); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">
                                            <?php echo e($package->how_much_course_can_select); ?> <?php echo e(__('messages.Courses')); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <?php if($package->categories->count() > 0): ?>
                                            <div class="d-flex flex-wrap gap-1">
                                                <?php $__currentLoopData = $package->categories->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span class="badge badge-secondary small">
                                                        <?php echo e($category->name_ar); ?>

                                                    </span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($package->categories->count() > 2): ?>
                                                    <span class="badge badge-light small">
                                                        +<?php echo e($package->categories->count() - 2); ?>

                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted"><?php echo e(__('messages.No categories')); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($package->subjects->count() > 0): ?>
                                            <div class="d-flex flex-wrap gap-1">
                                                <?php $__currentLoopData = $package->subjects->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span class="badge badge-primary small">
                                                        <?php echo e($subject->name_ar); ?>

                                                    </span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($package->subjects->count() > 2): ?>
                                                    <span class="badge badge-light small">
                                                        +<?php echo e($package->subjects->count() - 2); ?>

                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted"><?php echo e(__('messages.No subjects')); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo e($package->status_badge_class); ?>">
                                            <?php echo e(ucfirst($package->status)); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('package-table')): ?>
                                                <a href="<?php echo e(route('packages.show', $package)); ?>" 
                                                   class="btn btn-sm btn-outline-info" title="<?php echo e(__('messages.View')); ?>">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            <?php endif; ?>
                                            
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('package-edit')): ?>
                                                <a href="<?php echo e(route('packages.edit', $package)); ?>" 
                                                   class="btn btn-sm btn-outline-primary" title="<?php echo e(__('messages.Edit')); ?>">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            <?php endif; ?>
                                            
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('package-edit')): ?>
                                                <form method="POST" action="<?php echo e(route('packages.toggle-status', $package)); ?>" 
                                                      class="d-inline">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PATCH'); ?>
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-<?php echo e($package->status === 'active' ? 'warning' : 'success'); ?>" 
                                                            title="<?php echo e($package->status === 'active' ? __('messages.Deactivate') : __('messages.Activate')); ?>">
                                                        <i class="fas fa-<?php echo e($package->status === 'active' ? 'pause' : 'play'); ?>"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                            
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('package-delete')): ?>
                                                <form method="POST" action="<?php echo e(route('packages.destroy', $package)); ?>" 
                                                      class="d-inline" 
                                                      onsubmit="return confirm('<?php echo e(__('messages.Are you sure you want to delete this package?')); ?>')">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                            title="<?php echo e(__('messages.Delete')); ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted"><?php echo e(__('messages.No packages found')); ?></h5>
                    <p class="text-muted"><?php echo e(__('messages.Try adjusting your search criteria or add a new package')); ?></p>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('package-add')): ?>
                        <a href="<?php echo e(route('packages.create')); ?>" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i><?php echo e(__('messages.Add Package')); ?>

                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if($packages->hasPages()): ?>
            <div class="card-footer">
                <?php echo e($packages->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Bulk Action Form -->
<form id="bulk-action-form" method="POST" action="<?php echo e(route('packages.bulk-action')); ?>" style="display: none;">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="action" id="bulk-action">
    <div id="bulk-packages"></div>
</form>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all checkbox functionality
    const selectAllCheckbox = document.getElementById('select-all');
    const packageCheckboxes = document.querySelectorAll('.package-checkbox');

    selectAllCheckbox.addEventListener('change', function() {
        packageCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    packageCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedBoxes = document.querySelectorAll('.package-checkbox:checked');
            selectAllCheckbox.checked = checkedBoxes.length === packageCheckboxes.length;
        });
    });
});

function bulkAction(action) {
    const checkedBoxes = document.querySelectorAll('.package-checkbox:checked');
    
    if (checkedBoxes.length === 0) {
        alert('<?php echo e(__('messages.Please select at least one package')); ?>');
        return;
    }

    const actionMessages = {
        'activate': '<?php echo e(__('messages.Are you sure you want to activate selected packages?')); ?>',
        'deactivate': '<?php echo e(__('messages.Are you sure you want to deactivate selected packages?')); ?>',
        'delete': '<?php echo e(__('messages.Are you sure you want to delete selected packages? This action cannot be undone.')); ?>'
    };

    if (confirm(actionMessages[action])) {
        const form = document.getElementById('bulk-action-form');
        const actionInput = document.getElementById('bulk-action');
        const packagesContainer = document.getElementById('bulk-packages');
        
        actionInput.value = action;
        packagesContainer.innerHTML = '';
        
        checkedBoxes.forEach(checkbox => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'packages[]';
            input.value = checkbox.value;
            packagesContainer.appendChild(input);
        });
        
        form.submit();
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/admin/packages/index.blade.php ENDPATH**/ ?>