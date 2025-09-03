<?php if(Session::has('success')): ?>
<div class="alert alert-success" role="alert">
    <?php echo e(Session::get('success')); ?>

  </div>
  <?php endif; ?>

  <?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/admin/includes/alerts/success.blade.php ENDPATH**/ ?>