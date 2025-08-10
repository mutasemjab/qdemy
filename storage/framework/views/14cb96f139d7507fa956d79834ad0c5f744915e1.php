

<?php $__env->startSection('title', __('messages.view_blog')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php echo e(__('messages.blog_details')); ?></h3>
                    <div class="card-tools">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('blog-edit')): ?>
                        <a href="<?php echo e(route('blogs.edit', $blog)); ?>" class="btn btn-warning">
                            <i class="fas fa-edit"></i> <?php echo e(__('messages.edit')); ?>

                        </a>
                        <?php endif; ?>
                        <a href="<?php echo e(route('blogs.index')); ?>" class="btn btn-secondary ml-2">
                            <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.back')); ?>

                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Cover Photo -->
                        <?php if($blog->photo_cover): ?>
                        <div class="col-12 mb-4">
                            <div class="text-center">
                                <img src="<?php echo e(asset('assets/admin/uploads/' . $blog->photo_cover)); ?>" alt="<?php echo e($blog->title); ?>" 
                                     class="img-fluid rounded shadow" style="max-height: 400px; width: 100%; object-fit: cover;">
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="col-md-8">
                            <!-- Titles -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <div class="info-box-content">
                                            <span class="info-box-text"><?php echo e(__('messages.title_ar')); ?></span>
                                            <span class="info-box-number" dir="rtl"><?php echo e($blog->title_ar); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <div class="info-box-content">
                                            <span class="info-box-text"><?php echo e(__('messages.title_en')); ?></span>
                                            <span class="info-box-number"><?php echo e($blog->title_en); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Descriptions -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card card-outline card-info">
                                        <div class="card-header">
                                            <h3 class="card-title"><?php echo e(__('messages.description_ar')); ?></h3>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-justify" dir="rtl"><?php echo e($blog->description_ar); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card card-outline card-info">
                                        <div class="card-header">
                                            <h3 class="card-title"><?php echo e(__('messages.description_en')); ?></h3>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-justify"><?php echo e($blog->description_en); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Timestamps -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-box bg-success">
                                        <span class="info-box-icon"><i class="fas fa-calendar-plus"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text"><?php echo e(__('messages.created_at')); ?></span>
                                            <span class="info-box-number"><?php echo e($blog->created_at->format('Y-m-d H:i')); ?></span>
                                            <span class="progress-description"><?php echo e($blog->created_at->diffForHumans()); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box bg-warning">
                                        <span class="info-box-icon"><i class="fas fa-calendar-edit"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text"><?php echo e(__('messages.updated_at')); ?></span>
                                            <span class="info-box-number"><?php echo e($blog->updated_at->format('Y-m-d H:i')); ?></span>
                                            <span class="progress-description"><?php echo e($blog->updated_at->diffForHumans()); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Main Photo -->
                        <div class="col-md-4">
                            <?php if($blog->photo): ?>
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title"><?php echo e(__('messages.main_photo')); ?></h3>
                                </div>
                                <div class="card-body text-center">
                                    <img src="<?php echo e(asset('assets/admin/uploads/' . $blog->photo)); ?>" alt="<?php echo e($blog->title); ?>" 
                                         class="img-fluid rounded shadow-sm" style="max-height: 300px;">
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title"><?php echo e(__('messages.main_photo')); ?></h3>
                                </div>
                                <div class="card-body text-center">
                                    <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                         style="height: 200px;">
                                        <div class="text-center">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                            <p class="text-muted mt-2"><?php echo e(__('messages.no_image')); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Actions -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h3 class="card-title"><?php echo e(__('messages.actions')); ?></h3>
                                </div>
                                <div class="card-body">
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('blog-edit')): ?>
                                    <a href="<?php echo e(route('blogs.edit', $blog)); ?>" class="btn btn-warning btn-block">
                                        <i class="fas fa-edit"></i> <?php echo e(__('messages.edit_blog')); ?>

                                    </a>
                                    <?php endif; ?>
                                    
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('blog-delete')): ?>
                                    <form action="<?php echo e(route('blogs.destroy', $blog)); ?>" method="POST" class="mt-2">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-danger btn-block" 
                                                onclick="return confirm('<?php echo e(__('messages.confirm_delete')); ?>')">
                                            <i class="fas fa-trash"></i> <?php echo e(__('messages.delete_blog')); ?>

                                        </button>
                                    </form>
                                    <?php endif; ?>
                                    
                                    <a href="<?php echo e(route('blogs.index')); ?>" class="btn btn-secondary btn-block mt-2">
                                        <i class="fas fa-list"></i> <?php echo e(__('messages.all_blogs')); ?>

                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\qdemy\resources\views/admin/blogs/show.blade.php ENDPATH**/ ?>