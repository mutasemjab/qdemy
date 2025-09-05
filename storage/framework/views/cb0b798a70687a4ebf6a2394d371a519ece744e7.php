<?php $__env->startSection('title', __('messages.Categories')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0"><?php echo e(__('messages.Categories')); ?></h1>
            <p class="text-muted"><?php echo e(__('messages.Manage category hierarchy')); ?></p>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('categories.index')); ?>" class="row">
                <div class="col-md-8">
                    <input type="text" name="search" class="form-control"
                           value="<?php echo e(request('search')); ?>"
                           placeholder="<?php echo e(__('messages.Search categories...')); ?>">
                </div>
                <div class="col-md-4 d-flex">
                    <button type="submit" class="btn btn-outline-primary mr-2">
                        <i class="fas fa-search mr-1"></i><?php echo e(__('messages.Search')); ?>

                    </button>
                    <?php if(request('search')): ?>
                        <a href="<?php echo e(route('categories.index')); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-times mr-1"></i><?php echo e(__('messages.Clear')); ?>

                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Categories Tree -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?php echo e(__('messages.Categories List')); ?></h5>
            <div class="d-flex">
                <button type="button" class="btn btn-sm btn-outline-primary mr-2" id="expand-all">
                    <i class="fas fa-expand-arrows-alt mr-1"></i><?php echo e(__('messages.Expand All')); ?>

                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="collapse-all">
                    <i class="fas fa-compress-arrows-alt mr-1"></i><?php echo e(__('messages.Collapse All')); ?>

                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <?php if($rootCategories->count() > 0): ?>
                <div class="category-tree">
                    <?php $__currentLoopData = $rootCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make('admin.categories.partials.tree-item', ['category' => $category, 'level' => 0], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                    <h5><?php echo e(__('messages.No categories found')); ?></h5>
                    <p class="text-muted"><?php echo e(__('messages.Create your first category to get started')); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>


<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // Handle collapse toggle icon rotation
    $('[data-toggle="collapse"]').each(function() {
        var $toggleBtn = $(this);
        var $icon = $toggleBtn.find('.transition-icon');
        var targetSelector = $toggleBtn.attr('data-target');
        var $target = $(targetSelector);

        // Bootstrap 4 events
        $target.on('shown.bs.collapse', function() {
            $icon.removeClass('fa-chevron-right').addClass('fa-chevron-down');
            $toggleBtn.attr('aria-expanded', 'true');
        });

        $target.on('hidden.bs.collapse', function() {
            $icon.removeClass('fa-chevron-down').addClass('fa-chevron-right');
            $toggleBtn.attr('aria-expanded', 'false');
        });
    });

    // Expand all button
    $('#expand-all').click(function() {
        $('.collapse:not(.show)').collapse('show');
    });

    // Collapse all button
    $('#collapse-all').click(function() {
        $('.collapse.show').collapse('hide');
    });

    // Manual click handler as fallback
    $('.toggle-btn').click(function(e) {
        e.preventDefault();
        var targetSelector = $(this).attr('data-target');
        var $target = $(targetSelector);
        var $icon = $(this).find('.transition-icon');

        if ($target.length) {
            $target.collapse('toggle');
        }
    });
});
</script>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const categoryCheckboxes = document.querySelectorAll('.category-checkbox');
    const bulkButtons = document.querySelectorAll('#bulk-activate, #bulk-deactivate, #bulk-delete');
    const bulkForm = document.getElementById('bulk-form');
    const bulkActionInput = document.getElementById('bulk-action-input');

    // Select all functionality
    selectAllCheckbox.addEventListener('change', function() {
        categoryCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        toggleBulkButtons();
    });

    // Individual checkbox change
    categoryCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedCount = document.querySelectorAll('.category-checkbox:checked').length;
            selectAllCheckbox.checked = checkedCount === categoryCheckboxes.length;
            selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < categoryCheckboxes.length;
            toggleBulkButtons();
        });
    });

    // Toggle bulk action buttons
    function toggleBulkButtons() {
        const checkedCount = document.querySelectorAll('.category-checkbox:checked').length;
        bulkButtons.forEach(button => {
            button.disabled = checkedCount === 0;
        });
    }

    // Bulk action buttons
    document.getElementById('bulk-activate').addEventListener('click', function() {
        if (confirm('<?php echo e(__("Are you sure you want to activate selected categories?")); ?>')) {
            bulkActionInput.value = 'activate';
            bulkForm.submit();
        }
    });

    document.getElementById('bulk-deactivate').addEventListener('click', function() {
        if (confirm('<?php echo e(__("Are you sure you want to deactivate selected categories?")); ?>')) {
            bulkActionInput.value = 'deactivate';
            bulkForm.submit();
        }
    });

    document.getElementById('bulk-delete').addEventListener('click', function() {
        if (confirm('<?php echo e(__("Are you sure you want to delete selected categories?")); ?>')) {
            bulkActionInput.value = 'delete';
            bulkForm.submit();
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/admin/categories/index.blade.php ENDPATH**/ ?>