

<?php $__env->startSection('title', __('messages.wallet_transactions')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><?php echo e(__('messages.wallet_transactions')); ?></h3>
                    <a href="<?php echo e(route('wallet_transactions.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> <?php echo e(__('messages.add_transaction')); ?>

                    </a>
                </div>
                <div class="card-body">
                   

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('messages.id')); ?></th>
                                    <th><?php echo e(__('messages.user')); ?></th>
                                    <th><?php echo e(__('messages.admin')); ?></th>
                                    <th><?php echo e(__('messages.amount')); ?></th>
                                    <th><?php echo e(__('messages.type')); ?></th>
                                    <th><?php echo e(__('messages.note')); ?></th>
                                    <th><?php echo e(__('messages.date')); ?></th>
                                    <th><?php echo e(__('messages.actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($transaction->id); ?></td>
                                        <td>
                                            <strong><?php echo e($transaction->user->name); ?></strong><br>
                                            <small class="text-muted"><?php echo e($transaction->user->email); ?></small><br>
                                            <small class="text-info"><?php echo e(__('messages.balance')); ?>: <?php echo e(number_format($transaction->user->balance, 2)); ?></small>
                                        </td>
                                        <td><?php echo e($transaction->admin->name ?? __('messages.not_available')); ?></td>
                                        <td>
                                            <span class="badge badge-<?php echo e($transaction->type == 1 ? 'success' : 'danger'); ?>">
                                                <?php echo e($transaction->type == 1 ? '+' : '-'); ?><?php echo e($transaction->formatted_amount); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-<?php echo e($transaction->type == 1 ? 'success' : 'warning'); ?>">
                                                <?php echo e($transaction->type_name); ?>

                                            </span>
                                        </td>
                                        <td><?php echo e(Str::limit($transaction->note ?? __('messages.no_note'), 50)); ?></td>
                                        <td><?php echo e($transaction->created_at->format('Y-m-d H:i')); ?></td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo e(route('wallet_transactions.show', $transaction)); ?>" 
                                                   class="btn btn-sm btn-info" title="<?php echo e(__('messages.view')); ?>">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo e(route('wallet_transactions.edit', $transaction)); ?>" 
                                                   class="btn btn-sm btn-warning" title="<?php echo e(__('messages.edit')); ?>">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="<?php echo e(route('wallet_transactions.destroy', $transaction)); ?>" 
                                                      method="POST" class="d-inline" 
                                                      onsubmit="return confirm('<?php echo e(__('messages.confirm_delete')); ?>')">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-sm btn-danger" title="<?php echo e(__('messages.delete')); ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="8" class="text-center"><?php echo e(__('messages.no_transactions_found')); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        <?php echo e($transactions->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\qdemy\resources\views/admin/wallet_transactions/index.blade.php ENDPATH**/ ?>