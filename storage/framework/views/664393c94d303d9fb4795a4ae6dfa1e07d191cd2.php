

<?php $__env->startSection('title', __('messages.blogs')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><?php echo e(__('messages.blogs')); ?></h3>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('blog-add')): ?>
                    <a href="<?php echo e(route('blogs.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> <?php echo e(__('messages.add_new_blog')); ?>

                    </a>
                    <?php endif; ?>
                </div>

                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th><?php echo e(__('messages.photo')); ?></th>
                                    <th><?php echo e(__('messages.title')); ?></th>
                                    <th><?php echo e(__('messages.description')); ?></th>
                                    <th><?php echo e(__('messages.created_at')); ?></th>
                                    <th width="200"><?php echo e(__('messages.actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $blogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($loop->iteration); ?></td>
                                    <td>
                                        <?php if($blog->photo): ?>
                                            <img src="<?php echo e(asset('assets/admin/uploads/' . $blog->photo)); ?>" alt="<?php echo e($blog->title); ?>" 
                                                 class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                                 style="width: 60px; height: 60px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?php echo e(Str::limit($blog->title, 40)); ?></strong>
                                    </td>
                                    <td><?php echo e(Str::limit($blog->description, 60)); ?></td>
                                    <td><?php echo e($blog->created_at->format('Y-m-d')); ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('blog-table')): ?>
                                            <a href="<?php echo e(route('blogs.show', $blog)); ?>" 
                                               class="btn btn-info btn-sm" title="<?php echo e(__('messages.view')); ?>">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php endif; ?>
                                            
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('blog-edit')): ?>
                                            <a href="<?php echo e(route('blogs.edit', $blog)); ?>" 
                                               class="btn btn-warning btn-sm" title="<?php echo e(__('messages.edit')); ?>">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php endif; ?>
                                            
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('blog-delete')): ?>
                                            <form action="<?php echo e(route('blogs.destroy', $blog)); ?>" method="POST" 
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
                                            <i class="fas fa-blog fa-3x text-muted mb-3"></i>
                                            <p class="text-muted"><?php echo e(__('messages.no_blogs_found')); ?></p>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('blog-add')): ?>
                                            <a href="<?php echo e(route('blogs.create')); ?>" class="btn btn-primary">
                                                <?php echo e(__('messages.create_first_blog')); ?>

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
                        <?php echo e($blogs->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\qdemy\resources\views/admin/blogs/index.blade.php ENDPATH**/ ?>