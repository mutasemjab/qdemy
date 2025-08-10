

<?php $__env->startSection('title', __('messages.transaction_details')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-lg-6 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><?php echo e(__('messages.transaction_details')); ?></h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('wallet_transactions.edit', $walletTransaction)); ?>" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> <?php echo e(__('messages.edit')); ?>

                        </a>
                        <a href="<?php echo e(route('wallet_transactions.index')); ?>" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.back')); ?>

                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <strong><?php echo e(__('messages.transaction_id')); ?>:</strong>
                        </div>
                        <div class="col-md-8">
                            #<?php echo e($walletTransaction->id); ?>

                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <strong><?php echo e(__('messages.user')); ?>:</strong>
                        </div>
                        <div class="col-md-8">
                            <div>
                                <strong><?php echo e($walletTransaction->user->name); ?></strong>
                                <span class="badge badge-info"><?php echo e(__(ucfirst($walletTransaction->user->role_name))); ?></span>
                            </div>
                            <small class="text-muted"><?php echo e($walletTransaction->user->email); ?></small><br>
                            <small class="text-muted"><?php echo e(__('messages.phone')); ?>: <?php echo e($walletTransaction->user->phone ?? __('messages.not_available')); ?></small><br>
                            <small class="text-success"><?php echo e(__('messages.current_balance')); ?>: <?php echo e(number_format($walletTransaction->user->balance, 2)); ?></small>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <strong><?php echo e(__('messages.admin')); ?>:</strong>
                        </div>
                        <div class="col-md-8">
                            <?php echo e($walletTransaction->admin->name ?? __('messages.not_available')); ?>

                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <strong><?php echo e(__('messages.transaction_type')); ?>:</strong>
                        </div>
                        <div class="col-md-8">
                            <span class="badge badge-<?php echo e($walletTransaction->type == 1 ? 'success' : 'warning'); ?> badge-lg">
                                <?php echo e($walletTransaction->type_name); ?>

                            </span>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <strong><?php echo e(__('messages.amount')); ?>:</strong>
                        </div>
                        <div class="col-md-8">
                            <span class="h4 badge badge-<?php echo e($walletTransaction->type == 1 ? 'success' : 'danger'); ?>">
                                <?php echo e($walletTransaction->type == 1 ? '+' : '-'); ?><?php echo e($walletTransaction->formatted_amount); ?>

                            </span>
                        </div>
                    </div>
                    <hr>

                    <?php if($walletTransaction->note): ?>
                    <div class="row">
                        <div class="col-md-4">
                            <strong><?php echo e(__('messages.note')); ?>:</strong>
                        </div>
                        <div class="col-md-8">
                            <div class="alert alert-light">
                                <?php echo e($walletTransaction->note); ?>

                            </div>
                        </div>
                    </div>
                    <hr>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-4">
                            <strong><?php echo e(__('messages.created_at')); ?>:</strong>
                        </div>
                        <div class="col-md-8">
                            <?php echo e($walletTransaction->created_at->format('Y-m-d H:i:s')); ?>

                            <small class="text-muted">(<?php echo e($walletTransaction->created_at->diffForHumans()); ?>)</small>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <strong><?php echo e(__('messages.updated_at')); ?>:</strong>
                        </div>
                        <div class="col-md-8">
                            <?php echo e($walletTransaction->updated_at->format('Y-m-d H:i:s')); ?>

                            <small class="text-muted">(<?php echo e($walletTransaction->updated_at->diffForHumans()); ?>)</small>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-12">
                            <div class="btn-group d-flex" role="group">
                                <a href="<?php echo e(route('wallet_transactions.edit', $walletTransaction)); ?>" class="btn btn-warning flex-fill">
                                    <i class="fas fa-edit"></i> <?php echo e(__('messages.edit')); ?>

                                </a>
                                <form action="<?php echo e(route('wallet_transactions.destroy', $walletTransaction)); ?>" 
                                      method="POST" class="flex-fill" 
                                      onsubmit="return confirm('<?php echo e(__('messages.confirm_delete')); ?>')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-trash"></i> <?php echo e(__('messages.delete')); ?>

                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\qdemy\resources\views/admin/wallet_transactions/show.blade.php ENDPATH**/ ?>