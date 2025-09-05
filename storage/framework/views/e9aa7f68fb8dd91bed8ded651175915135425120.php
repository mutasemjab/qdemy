<style>
   .alert {
    padding: 12px 16px;
    border-radius: 6px;
    margin: 10px 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-family: sans-serif;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .alert-danger {
    background-color: #ffebee;
    color: #c62828;
    border-right: 4px solid #c62828;
    }

    .alert-success {
    background-color: #e8f5e9;
    color: #2e7d32;
    border-right: 4px solid #2e7d32;
    }

    .close-btn {
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    color: inherit;
    margin-left: 15px;
    }
</style>
  <?php $__errorArgs = ['*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?php echo e($message); ?>

      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
<?php if(session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php echo e(session('error')); ?>

    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/web/alert-message.blade.php ENDPATH**/ ?>