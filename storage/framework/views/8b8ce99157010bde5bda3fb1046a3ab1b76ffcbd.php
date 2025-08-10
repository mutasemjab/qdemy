
<?php
    $indent = str_repeat('&nbsp;&nbsp;&nbsp;', $level);
    $prefix = $level > 0 ? '└─&nbsp;' : '';
    $icon = $level > 0 ? '📁' : '📂';
    $childSections = $allSections->where('parent_id', $section->id);
?>

<option value="<?php echo e($section->id); ?>" 
        <?php echo e($selectedId == $section->id ? 'selected' : ''); ?>

        data-level="<?php echo e($level); ?>"
        style="<?php echo e($level > 0 ? 'color: #6c757d;' : ''); ?>">
    <?php echo $indent; ?><?php echo $prefix; ?><?php echo e($icon); ?> <?php echo e($section->title_en); ?> - <?php echo e($section->title_ar); ?>

    <?php if($childSections->count() > 0): ?>
        (<?php echo e($childSections->count()); ?> <?php echo e(__('messages.subsections')); ?>)
    <?php endif; ?>
</option>


<?php $__currentLoopData = $childSections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $childSection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php echo $__env->make('admin.courses.partials.section-option', [
        'section' => $childSection,
        'allSections' => $allSections,
        'level' => $level + 1,
        'selectedId' => $selectedId
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php /**PATH C:\xampp\htdocs\qdemy\resources\views/admin/courses/partials/section-option.blade.php ENDPATH**/ ?>