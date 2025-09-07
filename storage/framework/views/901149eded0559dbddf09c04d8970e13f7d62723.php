

<div class="ud-panel" id="notifications">
  <div class="ud-title"><?php echo e(__('panel.notifications')); ?></div>
  <div class="ud-list">
    <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <div class="ud-note <?php echo e(is_null($note->read_at) ? 'bg-light' : ''); ?>">
        <div class="ud-note-main">
          <b><?php echo e($note->title); ?></b>
          <small><?php echo e($note->body); ?></small>
        </div>
        <span class="ud-badge">
          <?php echo e(is_null($note->read_at) ? __('panel.new') : ''); ?>

        </span>

        
        <?php if(is_null($note->read_at)): ?>
          <form method="POST" action="<?php echo e(route('student.notifications.read', $note->id)); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-sm btn-link"><?php echo e(__('panel.mark_as_read')); ?></button>
          </form>
        <?php endif; ?>
      </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <p class="text-muted"><?php echo e(__('panel.no_notifications')); ?></p>
    <?php endif; ?>
  </div>
</div>
<?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/panel/common/notifications.blade.php ENDPATH**/ ?>