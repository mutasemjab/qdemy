<?php $__env->startSection('title', $category->name_ar); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 d-flex align-items-center">
                <?php if($category->icon): ?>
                    <span class="me-3" style="color: <?php echo e($category->color); ?>">
                        <i class="<?php echo e($category->icon); ?> fa-lg"></i>
                    </span>
                <?php endif; ?>
                <?php echo e($category->name_ar); ?>

                <?php if(!$category->is_active): ?>
                    <span class="badge bg-danger ms-2"><?php echo e(__('messages.Inactive')); ?></span>
                <?php endif; ?>
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('categories.index')); ?>"><?php echo e(__('messages.Categories')); ?></a></li>
                    <?php if($category->ancestors()->count() > 0): ?>
                        <?php $__currentLoopData = $category->ancestors(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ancestor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="breadcrumb-item">
                                <a href="<?php echo e(route('categories.show', $ancestor)); ?>"><?php echo e($ancestor->name_ar); ?></a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <li class="breadcrumb-item active"><?php echo e($category->name_ar); ?></li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('category-edit')): ?>
                <a href="<?php echo e(route('categories.edit', $category)); ?>" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i><?php echo e(__('messages.Edit')); ?>

                </a>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('category-add')): ?>
                <a href="<?php echo e(route('categories.create', ['parent_id' => $category->id])); ?>" class="btn btn-success">
                    <i class="fas fa-plus me-2"></i><?php echo e(__('messages.Add Subcategory')); ?>

                </a>
            <?php endif; ?>

            <a href="<?php echo e(route('categories.index')); ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i><?php echo e(__('messages.Back')); ?>

            </a>
        </div>
    </div>

    <div class="row">
        <!-- Category Details -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><?php echo e(__('messages.Category Details')); ?></h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold"><?php echo e(__('messages.Arabic Name')); ?></label>
                            <p class="form-control-plaintext"><?php echo e($category->name_ar); ?></p>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold"><?php echo e(__('messages.English Name')); ?></label>
                            <p class="form-control-plaintext"><?php echo e($category->name_en ?: '-'); ?></p>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold"><?php echo e(__('messages.Parent Category')); ?></label>
                            <p class="form-control-plaintext">
                                <?php if($category->parent): ?>
                                    <a href="<?php echo e(route('categories.show', $category->parent)); ?>" class="text-decoration-none">
                                        <?php if($category->parent->icon): ?>
                                            <i class="<?php echo e($category->parent->icon); ?>" style="color: <?php echo e($category->parent->color); ?>"></i>
                                        <?php endif; ?>
                                        <?php echo e($category->parent->name_ar); ?>

                                    </a>
                                <?php else: ?>
                                    <span class="text-muted"><?php echo e(__('messages.Root Category')); ?></span>
                                <?php endif; ?>
                            </p>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold"><?php echo e(__('messages.Level')); ?></label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-info"><?php echo e(__('messages.Level')); ?> <?php echo e($category->depth); ?></span>
                            </p>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold"><?php echo e(__('messages.Sort Order')); ?></label>
                            <p class="form-control-plaintext"><?php echo e($category->sort_order); ?></p>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold"><?php echo e(__('messages.Status')); ?></label>
                            <p class="form-control-plaintext">
                                <?php if($category->is_active): ?>
                                    <span class="badge bg-success"><?php echo e(__('messages.Active')); ?></span>
                                <?php else: ?>
                                    <span class="badge bg-danger"><?php echo e(__('messages.Inactive')); ?></span>
                                <?php endif; ?>
                            </p>
                        </div>

                        <?php if($category->type == 'lesson'): ?>
                        <div class="col-md-6">
                            <label class="form-label fw-bold"><?php echo e(__('messages.is_optional')); ?></label>
                            <p class="form-control-plaintext">
                               <?php echo $category->isOptional(); ?>

                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold"><?php echo e(__('messages.is_ministry')); ?></label>
                            <p class="form-control-plaintext">
                               <?php echo $category->isMinistry(); ?>

                            </p>
                        </div>
                        <?php endif; ?>

                        <?php if($category->description_ar): ?>
                            <div class="col-12">
                                <label class="form-label fw-bold"><?php echo e(__('messages.Arabic Description')); ?></label>
                                <p class="form-control-plaintext"><?php echo e($category->description_ar); ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if($category->description_en): ?>
                            <div class="col-12">
                                <label class="form-label fw-bold"><?php echo e(__('messages.English Description')); ?></label>
                                <p class="form-control-plaintext"><?php echo e($category->description_en); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Subcategories -->
            <?php if($category->children->count() > 0): ?>
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><?php echo e(__('messages.Subcategories')); ?> (<?php echo e($category->children->count()); ?>)</h5>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('category-add')): ?>
                            <a href="<?php echo e(route('categories.create', ['parent_id' => $category->id])); ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus me-1"></i><?php echo e(__('messages.Add')); ?>

                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th><?php echo e(__('messages.Category')); ?></th>
                                        <th><?php echo e(__('messages.Children')); ?></th>
                                        <th><?php echo e(__('messages.Status')); ?></th>
                                         <?php if($category->children->where('type','lesson')->count()): ?>
                                         <th><?php echo e(__('messages.is_ministry')); ?></th>
                                         <th><?php echo e(__('messages.is_optional')); ?></th>
                                         <?php endif; ?>
                                        <th><?php echo e(__('messages.Sort Order')); ?></th>
                                        <th><?php echo e(__('messages.Actions')); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $category->children->sortBy('sort_order'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if($child->icon): ?>
                                                        <span class="me-2" style="color: <?php echo e($child->color); ?>">
                                                            <i class="<?php echo e($child->icon); ?>"></i>
                                                        </span>
                                                    <?php endif; ?>
                                                    <div>
                                                        <div class="fw-bold">
                                                            <a href="<?php echo e(route('categories.show', $child)); ?>" class="text-decoration-none">
                                                                <?php echo e($child->name_ar); ?>

                                                            </a>
                                                        </div>
                                                        <?php if($child->name_en): ?>
                                                            <small class="text-muted"><?php echo e($child->name_en); ?></small>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if($child->children_count > 0): ?>
                                                    <span class="badge bg-secondary"><?php echo e($child->children_count); ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($child->is_active): ?>
                                                    <span class="badge bg-success"><?php echo e(__('messages.Active')); ?></span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger"><?php echo e(__('messages.Inactive')); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <?php if($child->type == 'lesson'): ?>
                                             <td><?php echo $child->isMinistry(); ?></td>
                                             <td><?php echo $child->isOptional(); ?></td>
                                             <?php endif; ?>
                                            <td><?php echo e($child->sort_order); ?></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('category-table')): ?>
                                                        <a href="<?php echo e(route('categories.show', $child)); ?>"
                                                           class="btn btn-sm btn-outline-info" title="<?php echo e(__('messages.View')); ?>">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    <?php endif; ?>

                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('category-edit')): ?>
                                                        <a href="<?php echo e(route('categories.edit', $child)); ?>"
                                                           class="btn btn-sm btn-outline-primary" title="<?php echo e(__('messages.Edit')); ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    <?php endif; ?>

                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('category-add')): ?>
                                                        <a href="<?php echo e(route('categories.create', ['parent_id' => $child->id])); ?>"
                                                           class="btn btn-sm btn-outline-success" title="<?php echo e(__('messages.Add Child')); ?>">
                                                            <i class="fas fa-plus"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                        <h5><?php echo e(__('messages.No subcategories found')); ?></h5>
                        <p class="text-muted"><?php echo e(__('messages.This category has no subcategories yet')); ?></p>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('category-add')): ?>
                            <a href="<?php echo e(route('categories.create', ['parent_id' => $category->id])); ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i><?php echo e(__('messages.Add First Subcategory')); ?>

                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><?php echo e(__('messages.Quick Actions')); ?></h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('category-edit')): ?>
                            <a href="<?php echo e(route('categories.edit', $category)); ?>" class="btn btn-outline-primary">
                                <i class="fas fa-edit me-2"></i><?php echo e(__('messages.Edit Category')); ?>

                            </a>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('category-add')): ?>
                            <a href="<?php echo e(route('categories.create', ['parent_id' => $category->id])); ?>" class="btn btn-outline-success">
                                <i class="fas fa-plus me-2"></i><?php echo e(__('messages.Add Subcategory')); ?>

                            </a>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('category-edit')): ?>
                            <form method="POST" action="<?php echo e(route('categories.toggle-status', $category)); ?>" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <button type="submit" class="btn btn-outline-<?php echo e($category->is_active ? 'warning' : 'success'); ?> w-100">
                                    <i class="fas fa-<?php echo e($category->is_active ? 'pause' : 'play'); ?> me-2"></i>
                                    <?php echo e($category->is_active ? __('messages.Deactivate') : __('messages.Activate')); ?>

                                </button>
                            </form>
                        <?php endif; ?>

                        <?php if($category->parent): ?>
                            <a href="<?php echo e(route('categories.show', $category->parent)); ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-level-up-alt me-2"></i><?php echo e(__('messages.Go to Parent')); ?>

                            </a>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('category-delete')): ?>
                            <?php if($category->children->count() === 0): ?>
                                <form method="POST" action="<?php echo e(route('categories.destroy', $category)); ?>"
                                      onsubmit="return confirm('<?php echo e(__('messages.Are you sure you want to delete this category?')); ?>')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-outline-danger w-100">
                                        <i class="fas fa-trash me-2"></i><?php echo e(__('messages.Delete Category')); ?>

                                    </button>
                                </form>
                            <?php else: ?>
                                <button class="btn btn-outline-danger w-100" disabled title="<?php echo e(__('messages.Cannot delete category with subcategories')); ?>">
                                    <i class="fas fa-trash me-2"></i><?php echo e(__('messages.Delete Category')); ?>

                                </button>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Category Statistics -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><?php echo e(__('messages.Statistics')); ?></h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary mb-0"><?php echo e($category->children->count()); ?></h4>
                                <small class="text-muted"><?php echo e(__('messages.Direct Children')); ?></small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-info mb-0"><?php echo e($category->descendants()->count()); ?></h4>
                            <small class="text-muted"><?php echo e(__('messages.All Descendants')); ?></small>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-success mb-0"><?php echo e($category->depth); ?></h4>
                                <small class="text-muted"><?php echo e(__('messages.Depth Level')); ?></small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-warning mb-0"><?php echo e($category->sort_order); ?></h4>
                            <small class="text-muted"><?php echo e(__('messages.Sort Order')); ?></small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category Path -->
            <?php if($category->ancestors()->count() > 0): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><?php echo e(__('messages.Category Path')); ?></h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column gap-2">
                            <?php $__currentLoopData = $category->ancestors(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ancestor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="d-flex align-items-center">
                                    <div class="me-2" style="margin-left: <?php echo e($loop->index * 20); ?>px;">
                                        <?php if($ancestor->icon): ?>
                                            <i class="<?php echo e($ancestor->icon); ?>" style="color: <?php echo e($ancestor->color); ?>"></i>
                                        <?php else: ?>
                                            <i class="fas fa-folder" style="color: <?php echo e($ancestor->color); ?>"></i>
                                        <?php endif; ?>
                                    </div>
                                    <a href="<?php echo e(route('categories.show', $ancestor)); ?>" class="text-decoration-none">
                                        <?php echo e($ancestor->name_ar); ?>

                                    </a>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <div class="d-flex align-items-center">
                                <div class="me-2" style="margin-left: <?php echo e($category->ancestors()->count() * 20); ?>px;">
                                    <?php if($category->icon): ?>
                                        <i class="<?php echo e($category->icon); ?>" style="color: <?php echo e($category->color); ?>"></i>
                                    <?php else: ?>
                                        <i class="fas fa-folder" style="color: <?php echo e($category->color); ?>"></i>
                                    <?php endif; ?>
                                </div>
                                <strong><?php echo e($category->name_ar); ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Category Info -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><?php echo e(__('messages.Category Information')); ?></h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong><?php echo e(__('messages.ID')); ?>:</strong></td>
                            <td><?php echo e($category->id); ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php echo e(__('messages.Color')); ?>:</strong></td>
                            <td>
                                <span class="d-inline-block rounded"
                                      style="width: 20px; height: 20px; background-color: <?php echo e($category->color); ?>"></span>
                                <?php echo e($category->color); ?>

                            </td>
                        </tr>
                        <tr>
                            <td><strong><?php echo e(__('messages.Created')); ?>:</strong></td>
                            <td><?php echo e($category->created_at->format('Y-m-d H:i')); ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php echo e(__('messages.Updated')); ?>:</strong></td>
                            <td><?php echo e($category->updated_at->format('Y-m-d H:i')); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 d-flex align-items-center">
                <?php if($category->icon): ?>
                    <span class="me-3" style="color: <?php echo e($category->color); ?>">
                        <i class="<?php echo e($category->icon); ?> fa-lg"></i>
                    </span>
                <?php endif; ?>
                <?php echo e($category->name_ar); ?>

                <?php if(!$category->is_active): ?>
                    <span class="badge bg-danger ms-2"><?php echo e(__('Inactive')); ?></span>
                <?php endif; ?>
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('categories.index')); ?>"><?php echo e(__('Categories')); ?></a></li>
                    <?php if($category->ancestors()->count() > 0): ?>
                        <?php $__currentLoopData = $category->ancestors(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ancestor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="breadcrumb-item">
                                <a href="<?php echo e(route('categories.show', $ancestor)); ?>"><?php echo e($ancestor->name_ar); ?></a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    <li class="breadcrumb-item active"><?php echo e($category->name_ar); ?></li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('category-edit')): ?>
                <a href="<?php echo e(route('categories.edit', $category)); ?>" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i><?php echo e(__('Edit')); ?>

                </a>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('category-add')): ?>
                <a href="<?php echo e(route('categories.create', ['parent_id' => $category->id])); ?>" class="btn btn-success">
                    <i class="fas fa-plus me-2"></i><?php echo e(__('Add Subcategory')); ?>

                </a>
            <?php endif; ?>

            <a href="<?php echo e(route('categories.index')); ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i><?php echo e(__('Back')); ?>

            </a>
        </div>
    </div>

    <div class="row">
        <!-- Category Details -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><?php echo e(__('Category Details')); ?></h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold"><?php echo e(__('Arabic Name')); ?></label>
                            <p class="form-control-plaintext"><?php echo e($category->name_ar); ?></p>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold"><?php echo e(__('English Name')); ?></label>
                            <p class="form-control-plaintext"><?php echo e($category->name_en ?: '-'); ?></p>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold"><?php echo e(__('Parent Category')); ?></label>
                            <p class="form-control-plaintext">
                                <?php if($category->parent): ?>
                                    <a href="<?php echo e(route('categories.show', $category->parent)); ?>" class="text-decoration-none">
                                        <?php if($category->parent->icon): ?>
                                            <i class="<?php echo e($category->parent->icon); ?>" style="color: <?php echo e($category->parent->color); ?>"></i>
                                        <?php endif; ?>
                                        <?php echo e($category->parent->name_ar); ?>

                                    </a>
                                <?php else: ?>
                                    <span class="text-muted"><?php echo e(__('Root Category')); ?></span>
                                <?php endif; ?>
                            </p>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold"><?php echo e(__('Level')); ?></label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-info"><?php echo e(__('Level')); ?> <?php echo e($category->depth); ?></span>
                            </p>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold"><?php echo e(__('Sort Order')); ?></label>
                            <p class="form-control-plaintext"><?php echo e($category->sort_order); ?></p>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold"><?php echo e(__('Status')); ?></label>
                            <p class="form-control-plaintext">
                                <?php if($category->is_active): ?>
                                    <span class="badge bg-success"><?php echo e(__('Active')); ?></span>
                                <?php else: ?>
                                    <span class="badge bg-danger"><?php echo e(__('Inactive')); ?></span>
                                <?php endif; ?>
                            </p>
                        </div>

                        <?php if($category->description_ar): ?>
                            <div class="col-12">
                                <label class="form-label fw-bold"><?php echo e(__('Arabic Description')); ?></label>
                                <p class="form-control-plaintext"><?php echo e($category->description_ar); ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if($category->description_en): ?>
                            <div class="col-12">
                                <label class="form-label fw-bold"><?php echo e(__('English Description')); ?></label>
                                <p class="form-control-plaintext"><?php echo e($category->description_en); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Subcategories -->
            <?php if($category->children->count() > 0): ?>
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><?php echo e(__('Subcategories')); ?> (<?php echo e($category->children->count()); ?>)</h5>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('category-add')): ?>
                            <a href="<?php echo e(route('categories.create', ['parent_id' => $category->id])); ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus me-1"></i><?php echo e(__('Add')); ?>

                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th><?php echo e(__('Category')); ?></th>
                                        <th><?php echo e(__('Children')); ?></th>
                                        <th><?php echo e(__('Status')); ?></th>
                                        <th><?php echo e(__('Sort Order')); ?></th>
                                        <th><?php echo e(__('Actions')); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $category->children->sortBy('sort_order'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if($child->icon): ?>
                                                        <span class="me-2" style="color: <?php echo e($child->color); ?>">
                                                            <i class="<?php echo e($child->icon); ?>"></i>
                                                        </span>
                                                    <?php endif; ?>
                                                    <div>
                                                        <div class="fw-bold">
                                                            <a href="<?php echo e(route('categories.show', $child)); ?>" class="text-decoration-none">
                                                                <?php echo e($child->name_ar); ?>

                                                            </a>
                                                        </div>
                                                        <?php if($child->name_en): ?>
                                                            <small class="text-muted"><?php echo e($child->name_en); ?></small>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if($child->children_count > 0): ?>
                                                    <span class="badge bg-secondary"><?php echo e($child->children_count); ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($child->is_active): ?>
                                                    <span class="badge bg-success"><?php echo e(__('Active')); ?></span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger"><?php echo e(__('Inactive')); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($child->sort_order); ?></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('category-table')): ?>
                                                        <a href="<?php echo e(route('categories.show', $child)); ?>"
                                                           class="btn btn-sm btn-outline-info" title="<?php echo e(__('View')); ?>">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    <?php endif; ?>

                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('category-edit')): ?>
                                                        <a href="<?php echo e(route('categories.edit', $child)); ?>"
                                                           class="btn btn-sm btn-outline-primary" title="<?php echo e(__('Edit')); ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    <?php endif; ?>

                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('category-add')): ?>
                                                        <a href="<?php echo e(route('categories.create', ['parent_id' => $child->id])); ?>"
                                                           class="btn btn-sm btn-outline-success" title="<?php echo e(__('Add Child')); ?>">
                                                            <i class="fas fa-plus"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                        <h5><?php echo e(__('No subcategories found')); ?></h5>
                        <p class="text-muted"><?php echo e(__('This category has no subcategories yet')); ?></p>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('category-add')): ?>
                            <a href="<?php echo e(route('categories.create', ['parent_id' => $category->id])); ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i><?php echo e(__('Add First Subcategory')); ?>

                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><?php echo e(__('Quick Actions')); ?></h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('category-edit')): ?>
                            <a href="<?php echo e(route('categories.edit', $category)); ?>" class="btn btn-outline-primary">
                                <i class="fas fa-edit me-2"></i><?php echo e(__('Edit Category')); ?>

                            </a>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('category-add')): ?>
                            <a href="<?php echo e(route('categories.create', ['parent_id' => $category->id])); ?>" class="btn btn-outline-success">
                                <i class="fas fa-plus me-2"></i><?php echo e(__('Add Subcategory')); ?>

                            </a>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('category-edit')): ?>
                            <form method="POST" action="<?php echo e(route('categories.toggle-status', $category)); ?>" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <button type="submit" class="btn btn-outline-<?php echo e($category->is_active ? 'warning' : 'success'); ?> w-100">
                                    <i class="fas fa-<?php echo e($category->is_active ? 'pause' : 'play'); ?> me-2"></i>
                                    <?php echo e($category->is_active ? __('Deactivate') : __('Activate')); ?>

                                </button>
                            </form>
                        <?php endif; ?>

                        <?php if($category->parent): ?>
                            <a href="<?php echo e(route('categories.show', $category->parent)); ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-level-up-alt me-2"></i><?php echo e(__('Go to Parent')); ?>

                            </a>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('category-delete')): ?>
                            <?php if($category->children->count() === 0): ?>
                                <form method="POST" action="<?php echo e(route('categories.destroy', $category)); ?>"
                                      onsubmit="return confirm('<?php echo e(__('Are you sure you want to delete this category?')); ?>')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-outline-danger w-100">
                                        <i class="fas fa-trash me-2"></i><?php echo e(__('Delete Category')); ?>

                                    </button>
                                </form>
                            <?php else: ?>
                                <button class="btn btn-outline-danger w-100" disabled title="<?php echo e(__('Cannot delete category with subcategories')); ?>">
                                    <i class="fas fa-trash me-2"></i><?php echo e(__('Delete Category')); ?>

                                </button>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Category Statistics -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><?php echo e(__('Statistics')); ?></h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary mb-0"><?php echo e($category->children->count()); ?></h4>
                                <small class="text-muted"><?php echo e(__('Direct Children')); ?></small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-info mb-0"><?php echo e($category->descendants()->count()); ?></h4>
                            <small class="text-muted"><?php echo e(__('All Descendants')); ?></small>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-success mb-0"><?php echo e($category->depth); ?></h4>
                                <small class="text-muted"><?php echo e(__('Depth Level')); ?></small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-warning mb-0"><?php echo e($category->sort_order); ?></h4>
                            <small class="text-muted"><?php echo e(__('Sort Order')); ?></small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category Path -->
            <?php if($category->ancestors()->count() > 0): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><?php echo e(__('Category Path')); ?></h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-column gap-2">
                            <?php $__currentLoopData = $category->ancestors(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ancestor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="d-flex align-items-center">
                                    <div class="me-2" style="margin-left: <?php echo e($loop->index * 20); ?>px;">
                                        <?php if($ancestor->icon): ?>
                                            <i class="<?php echo e($ancestor->icon); ?>" style="color: <?php echo e($ancestor->color); ?>"></i>
                                        <?php else: ?>
                                            <i class="fas fa-folder" style="color: <?php echo e($ancestor->color); ?>"></i>
                                        <?php endif; ?>
                                    </div>
                                    <a href="<?php echo e(route('categories.show', $ancestor)); ?>" class="text-decoration-none">
                                        <?php echo e($ancestor->name_ar); ?>

                                    </a>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <div class="d-flex align-items-center">
                                <div class="me-2" style="margin-left: <?php echo e($category->ancestors()->count() * 20); ?>px;">
                                    <?php if($category->icon): ?>
                                        <i class="<?php echo e($category->icon); ?>" style="color: <?php echo e($category->color); ?>"></i>
                                    <?php else: ?>
                                        <i class="fas fa-folder" style="color: <?php echo e($category->color); ?>"></i>
                                    <?php endif; ?>
                                </div>
                                <strong><?php echo e($category->name_ar); ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Category Info -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><?php echo e(__('Category Information')); ?></h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong><?php echo e(__('ID')); ?>:</strong></td>
                            <td><?php echo e($category->id); ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php echo e(__('Color')); ?>:</strong></td>
                            <td>
                                <span class="d-inline-block rounded"
                                      style="width: 20px; height: 20px; background-color: <?php echo e($category->color); ?>"></span>
                                <?php echo e($category->color); ?>

                            </td>
                        </tr>
                        <tr>
                            <td><strong><?php echo e(__('Created')); ?>:</strong></td>
                            <td><?php echo e($category->created_at->format('Y-m-d H:i')); ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php echo e(__('Updated')); ?>:</strong></td>
                            <td><?php echo e($category->updated_at->format('Y-m-d H:i')); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/admin/categories/show.blade.php ENDPATH**/ ?>