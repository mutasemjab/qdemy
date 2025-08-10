

<?php $__env->startSection('title', __('messages.add_transaction')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-lg-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php echo e(__('messages.add_transaction')); ?></h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('wallet_transactions.index')); ?>" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.back')); ?>

                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if(session('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo e(session('error')); ?>

                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('wallet_transactions.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        
                        <div class="form-group">
                            <label for="user_id"><?php echo e(__('messages.user')); ?> *</label>
                            <select name="user_id" id="user_id" class="form-control <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value=""><?php echo e(__('messages.select_user')); ?></option>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>" <?php echo e(old('user_id') == $user->id ? 'selected' : ''); ?>

                                            data-balance="<?php echo e($user->balance); ?>">
                                        <?php echo e($user->name); ?> (<?php echo e($user->email); ?>) - <?php echo e(__('messages.balance')); ?>: <?php echo e(number_format($user->balance, 2)); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group">
                            <label for="admin_id"><?php echo e(__('messages.admin')); ?> *</label>
                            <select name="admin_id" id="admin_id" class="form-control <?php $__errorArgs = ['admin_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value=""><?php echo e(__('messages.select_admin')); ?></option>
                                <?php $__currentLoopData = $admins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $admin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($admin->id); ?>" <?php echo e(old('admin_id') == $admin->id ? 'selected' : ''); ?>>
                                        <?php echo e($admin->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['admin_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group">
                            <label for="type"><?php echo e(__('messages.transaction_type')); ?> *</label>
                            <select name="type" id="type" class="form-control <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value=""><?php echo e(__('messages.select_type')); ?></option>
                                <option value="1" <?php echo e(old('type') == '1' ? 'selected' : ''); ?>><?php echo e(__('messages.add_money')); ?></option>
                                <option value="2" <?php echo e(old('type') == '2' ? 'selected' : ''); ?>><?php echo e(__('messages.withdrawal')); ?></option>
                            </select>
                            <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group">
                            <label for="amount"><?php echo e(__('messages.amount')); ?> *</label>
                            <input type="number" step="0.01" name="amount" id="amount" 
                                   class="form-control <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   value="<?php echo e(old('amount')); ?>" required min="0.01">
                            <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small id="balance-warning" class="text-danger" style="display: none;">
                                <?php echo e(__('messages.withdrawal_exceeds_balance')); ?>

                            </small>
                        </div>

                        <div class="form-group">
                            <label for="note"><?php echo e(__('messages.note')); ?></label>
                            <textarea name="note" id="note" class="form-control <?php $__errorArgs = ['note'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      rows="3" placeholder="<?php echo e(__('messages.transaction_note_placeholder')); ?>"><?php echo e(old('note')); ?></textarea>
                            <?php $__errorArgs = ['note'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> <?php echo e(__('messages.save_transaction')); ?>

                            </button>
                            <a href="<?php echo e(route('wallet_transactions.index')); ?>" class="btn btn-secondary">
                                <?php echo e(__('messages.cancel')); ?>

                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const userSelect = document.getElementById('user_id');
    const typeSelect = document.getElementById('type');
    const amountInput = document.getElementById('amount');
    const balanceWarning = document.getElementById('balance-warning');

    function checkBalance() {
        const selectedOption = userSelect.options[userSelect.selectedIndex];
        const userBalance = selectedOption ? parseFloat(selectedOption.dataset.balance) : 0;
        const transactionType = typeSelect.value;
        const amount = parseFloat(amountInput.value) || 0;

        if (transactionType == '2' && amount > userBalance) {
            balanceWarning.style.display = 'block';
            amountInput.classList.add('is-invalid');
        } else {
            balanceWarning.style.display = 'none';
            amountInput.classList.remove('is-invalid');
        }
    }

    userSelect.addEventListener('change', checkBalance);
    typeSelect.addEventListener('change', checkBalance);
    amountInput.addEventListener('input', checkBalance);
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\qdemy\resources\views/admin/wallet_transactions/create.blade.php ENDPATH**/ ?>