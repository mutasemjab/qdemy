

<?php $__env->startSection('title', __('messages.ministerial_questions')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><?php echo e(__('messages.ministerial_questions')); ?></h3>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ministerial-question-add')): ?>
                    <a href="<?php echo e(route('ministerial-questions.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> <?php echo e(__('messages.add_new_ministerial_question')); ?>

                    </a>
                    <?php endif; ?>
                </div>

                <div class="card-body">
                   
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th><?php echo e(__('messages.category')); ?></th>
                                    <th><?php echo e(__('messages.pdf_file')); ?></th>
                                    <th><?php echo e(__('messages.file_size')); ?></th>
                                    <th><?php echo e(__('messages.created_at')); ?></th>
                                    <th width="200"><?php echo e(__('messages.actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $ministerialQuestions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ministerialQuestion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($loop->iteration); ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if($ministerialQuestion->category && $ministerialQuestion->category->icon): ?>
                                                <i class="<?php echo e($ministerialQuestion->category->icon); ?> mr-2" 
                                                   style="color: <?php echo e($ministerialQuestion->category->color ?? '#007bff'); ?>"></i>
                                            <?php endif; ?>
                                            <div>
                                                <strong><?php echo e($ministerialQuestion->category_breadcrumb); ?></strong>
                                                <?php if($ministerialQuestion->category && $ministerialQuestion->category->parent): ?>
                                                    <br><small class="text-muted">
                                                        <?php echo e(__('messages.parent')); ?>: <?php echo e($ministerialQuestion->category->parent->localized_name); ?>

                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if($ministerialQuestion->pdf): ?>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-file-pdf text-danger mr-2"></i>
                                                <div>
                                                    <span class="text-truncate" style="max-width: 150px; display: inline-block;">
                                                        <?php echo e($ministerialQuestion->pdf); ?>

                                                    </span>
                                                    <?php if($ministerialQuestion->pdfExists()): ?>
                                                        <span class="badge badge-success"><?php echo e(__('messages.available')); ?></span>
                                                    <?php else: ?>
                                                        <span class="badge badge-danger"><?php echo e(__('messages.missing')); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted"><?php echo e(__('messages.no_file')); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo e($ministerialQuestion->pdf_size ?? '--'); ?>

                                    </td>
                                    <td><?php echo e($ministerialQuestion->created_at->format('Y-m-d H:i')); ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ministerial-question-table')): ?>
                                            <a href="<?php echo e(route('ministerial-questions.show', $ministerialQuestion)); ?>" 
                                               class="btn btn-info btn-sm" title="<?php echo e(__('messages.view')); ?>">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php endif; ?>

                                            <?php if($ministerialQuestion->pdfExists()): ?>
                                                <a href="<?php echo e(route('ministerial-questions.download', $ministerialQuestion)); ?>" 
                                                   class="btn btn-success btn-sm" title="<?php echo e(__('messages.download')); ?>">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            <?php endif; ?>
                                            
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ministerial-question-edit')): ?>
                                            <a href="<?php echo e(route('ministerial-questions.edit', $ministerialQuestion)); ?>" 
                                               class="btn btn-warning btn-sm" title="<?php echo e(__('messages.edit')); ?>">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php endif; ?>
                                            
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ministerial-question-delete')): ?>
                                            <form action="<?php echo e(route('ministerial-questions.destroy', $ministerialQuestion)); ?>" method="POST" 
                                                  class="d-inline delete-form">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-danger btn-sm" 
                                                        title="<?php echo e(__('messages.delete')); ?>"
                                                        onclick="return confirm('<?php echo e(__('messages.confirm_delete')); ?>')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <div class="py-4">
                                            <i class="fas fa-file-pdf fa-3x text-muted mb-3"></i>
                                            <p class="text-muted"><?php echo e(__('messages.no_ministerial_questions_found')); ?></p>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ministerial-question-add')): ?>
                                            <a href="<?php echo e(route('ministerial-questions.create')); ?>" class="btn btn-primary">
                                                <?php echo e(__('messages.create_first_ministerial_question')); ?>

                                            </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        <?php echo e($ministerialQuestions->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\qdemy\resources\views/admin/ministerial_questions/index.blade.php ENDPATH**/ ?>