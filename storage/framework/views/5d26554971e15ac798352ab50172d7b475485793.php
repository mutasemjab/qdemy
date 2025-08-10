

<?php $__env->startSection('title', __('messages.community_posts')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><?php echo e(__('messages.community_posts')); ?></h3>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('community-add')): ?>
                        <a href="<?php echo e(route('admin.community.posts.create')); ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> <?php echo e(__('messages.add_post')); ?>

                        </a>
                    <?php endif; ?>
                </div>

                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" class="mb-3">
                        <div class="row">
                            <div class="col-md-3">
                                <select name="approval_status" class="form-control">
                                    <option value=""><?php echo e(__('messages.all_approval_status')); ?></option>
                                    <option value="approved" <?php echo e(request('approval_status') == 'approved' ? 'selected' : ''); ?>>
                                        <?php echo e(__('messages.approved')); ?>

                                    </option>
                                    <option value="pending" <?php echo e(request('approval_status') == 'pending' ? 'selected' : ''); ?>>
                                        <?php echo e(__('messages.pending')); ?>

                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="active_status" class="form-control">
                                    <option value=""><?php echo e(__('messages.all_status')); ?></option>
                                    <option value="1" <?php echo e(request('active_status') == '1' ? 'selected' : ''); ?>>
                                        <?php echo e(__('messages.active')); ?>

                                    </option>
                                    <option value="0" <?php echo e(request('active_status') == '0' ? 'selected' : ''); ?>>
                                        <?php echo e(__('messages.inactive')); ?>

                                    </option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="<?php echo e(__('messages.search_posts')); ?>" 
                                       value="<?php echo e(request('search')); ?>">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-secondary"><?php echo e(__('messages.filter')); ?></button>
                            </div>
                        </div>
                    </form>

                    <?php if($posts->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th><?php echo e(__('messages.title')); ?></th>
                                        <th><?php echo e(__('messages.author')); ?></th>
                                        <th><?php echo e(__('messages.comments_count')); ?></th>
                                        <th><?php echo e(__('messages.status')); ?></th>
                                        <th><?php echo e(__('messages.approval')); ?></th>
                                        <th><?php echo e(__('messages.created_at')); ?></th>
                                        <th><?php echo e(__('messages.actions')); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <a href="<?php echo e(route('admin.community.posts.show', $post)); ?>">
                                                    <?php echo e(Str::limit($post->title, 50)); ?>

                                                </a>
                                            </td>
                                            <td><?php echo e($post->user->name); ?></td>
                                            <td><?php echo e($post->comments->count()); ?></td>
                                            <td>
                                                <span class="badge badge-<?php echo e($post->is_active ? 'success' : 'danger'); ?>">
                                                    <?php echo e($post->is_active ? __('messages.active') : __('messages.inactive')); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-<?php echo e($post->is_approved ? 'success' : 'warning'); ?>">
                                                    <?php echo e($post->is_approved ? __('messages.approved') : __('messages.pending')); ?>

                                                </span>
                                            </td>
                                            <td><?php echo e($post->created_at->format('Y-m-d H:i')); ?></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('community-table')): ?>
                                                        <a href="<?php echo e(route('admin.community.posts.show', $post)); ?>" 
                                                           class="btn btn-sm btn-info">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('community-edit')): ?>
                                                        <a href="<?php echo e(route('admin.community.posts.edit', $post)); ?>" 
                                                           class="btn btn-sm btn-primary">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        
                                                        <?php if(!$post->is_approved): ?>
                                                            <a href="<?php echo e(route('admin.community.posts.approve', $post)); ?>" 
                                                               class="btn btn-sm btn-success">
                                                                <i class="fas fa-check"></i>
                                                            </a>
                                                        <?php else: ?>
                                                            <a href="<?php echo e(route('admin.community.posts.reject', $post)); ?>" 
                                                               class="btn btn-sm btn-warning">
                                                                <i class="fas fa-times"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                        
                                                        <a href="<?php echo e(route('admin.community.posts.toggle-status', $post)); ?>" 
                                                           class="btn btn-sm btn-<?php echo e($post->is_active ? 'danger' : 'success'); ?>">
                                                            <i class="fas fa-<?php echo e($post->is_active ? 'ban' : 'check'); ?>"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('community-delete')): ?>
                                                        <form action="<?php echo e(route('admin.community.comments.destroy', $comment)); ?>" 
                                                              method="POST" class="d-inline">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                                    onclick="return confirm('<?php echo e(__('messages.confirm_delete')); ?>')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <?php echo e($comments->withQueryString()->links()); ?>

                    <?php else: ?>
                        <div class="text-center py-4">
                            <p class="text-muted"><?php echo e(__('messages.no_comments_found')); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\qdemy\resources\views/admin/community/posts/index.blade.php ENDPATH**/ ?>