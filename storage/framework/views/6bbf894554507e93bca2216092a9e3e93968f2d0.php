<?php $__env->startSection('title', __('messages.Add Category')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0"><?php echo e(__('messages.Add Category')); ?></h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('categories.index')); ?>"><?php echo e(__('messages.Categories')); ?></a></li>
                            <li class="breadcrumb-item active"><?php echo e(__('messages.Add Category')); ?></li>
                        </ol>
                    </nav>
                </div>
                <a href="<?php echo e(route('categories.index')); ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i><?php echo e(__('messages.Back')); ?>

                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><?php echo e(__('messages.Category Information')); ?></h5>
                </div>
                
                <form method="POST" action="<?php echo e(route('categories.store')); ?>">
                    <?php echo csrf_field(); ?>
                    
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Arabic Name -->
                            <div class="col-md-6">
                                <label for="name_ar" class="form-label required"><?php echo e(__('messages.Arabic Name')); ?></label>
                                <input type="text" class="form-control <?php $__errorArgs = ['name_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="name_ar" name="name_ar" value="<?php echo e(old('name_ar')); ?>" required>
                                <?php $__errorArgs = ['name_ar'];
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

                            <!-- English Name -->
                            <div class="col-md-6">
                                <label for="name_en" class="form-label"><?php echo e(__('messages.English Name')); ?></label>
                                <input type="text" class="form-control <?php $__errorArgs = ['name_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="name_en" name="name_en" value="<?php echo e(old('name_en')); ?>">
                                <?php $__errorArgs = ['name_en'];
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

                            <!-- Parent Category -->
                            <div class="col-md-6">
                                <label for="parent_id" class="form-label"><?php echo e(__('messages.Parent Category')); ?></label>
                                <select class="form-control <?php $__errorArgs = ['parent_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="parent_id" name="parent_id">
                                    <option value=""><?php echo e(__('messages.Root Category')); ?></option>
                                    <?php $__currentLoopData = $parentCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($parent['category']->id); ?>" 
                                                <?php echo e((old('parent_id', $parentId) == $parent['category']->id) ? 'selected' : ''); ?>>
                                            <?php echo e($parent['name']); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['parent_id'];
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

                              <div class="col-md-6">
                                <label for="type" class="form-label"><?php echo e(__('messages.Category Type')); ?></label>
                                <select class="form-control <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="type" name="type">
                                    <option value="class" <?php echo e(old('type', 'class') == 'class' ? 'selected' : ''); ?>>
                                        <?php echo e(__('messages.Class')); ?> 
                                    </option>
                                    <option value="lesson" <?php echo e(old('type') == 'lesson' ? 'selected' : ''); ?>>
                                        <?php echo e(__('messages.Lesson')); ?> 
                                    </option>
                                    <option value="major" <?php echo e(old('type') == 'major' ? 'selected' : ''); ?>>
                                        <?php echo e(__('messages.Major')); ?>

                                    </option>
                                </select>
                                <div class="form-text">
                                    <?php echo e(__('messages.Type determines how this category can be used in the system')); ?>

                                </div>
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

                            <!-- Sort Order -->
                            <div class="col-md-6">
                                <label for="sort_order" class="form-label"><?php echo e(__('messages.Sort Order')); ?></label>
                                <input type="number" class="form-control <?php $__errorArgs = ['sort_order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="sort_order" name="sort_order" value="<?php echo e(old('sort_order', 0)); ?>" min="0">
                                <?php $__errorArgs = ['sort_order'];
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

                            <!-- Icon -->
                            <div class="col-md-6">
                                <label for="icon" class="form-label"><?php echo e(__('messages.Icon')); ?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control <?php $__errorArgs = ['icon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="icon" name="icon" value="<?php echo e(old('icon')); ?>" 
                                           placeholder="fas fa-folder">
                                    <button type="button" class="btn btn-outline-secondary" 
                                            data-bs-toggle="modal" data-bs-target="#iconModal">
                                        <i class="fas fa-icons"></i>
                                    </button>
                                </div>
                                <div class="form-text"><?php echo e(__('messages.Font Awesome icon class (e.g., fas fa-folder)')); ?></div>
                                <?php $__errorArgs = ['icon'];
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

                            <!-- Color -->
                            <div class="col-md-6">
                                <label for="color" class="form-label"><?php echo e(__('messages.Color')); ?></label>
                                <input type="color" class="form-control form-control-color <?php $__errorArgs = ['color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="color" name="color" value="<?php echo e(old('color', '#007bff')); ?>">
                                <?php $__errorArgs = ['color'];
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

                            <!-- Arabic Description -->
                            <div class="col-12">
                                <label for="description_ar" class="form-label"><?php echo e(__('messages.Arabic Description')); ?></label>
                                <textarea class="form-control <?php $__errorArgs = ['description_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                          id="description_ar" name="description_ar" rows="3"><?php echo e(old('description_ar')); ?></textarea>
                                <?php $__errorArgs = ['description_ar'];
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

                            <!-- English Description -->
                            <div class="col-12">
                                <label for="description_en" class="form-label"><?php echo e(__('messages.English Description')); ?></label>
                                <textarea class="form-control <?php $__errorArgs = ['description_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                          id="description_en" name="description_en" rows="3"><?php echo e(old('description_en')); ?></textarea>
                                <?php $__errorArgs = ['description_en'];
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

                            <!-- Status -->
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" 
                                           id="is_active" name="is_active" <?php echo e(old('is_active', true) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="is_active">
                                        <?php echo e(__('messages.Category is active')); ?>

                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?php echo e(route('categories.index')); ?>" class="btn btn-secondary">
                                <?php echo e(__('messages.Cancel')); ?>

                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i><?php echo e(__('messages.Save Category')); ?>

                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Icon Selection Modal -->
<div class="modal fade" id="iconModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(__('messages.Select Icon')); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2" id="iconGrid">
                    <!-- Common icons -->
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-folder">
                            <i class="fas fa-folder fa-2x"></i><br>
                            <small>folder</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-book">
                            <i class="fas fa-book fa-2x"></i><br>
                            <small>book</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-graduation-cap">
                            <i class="fas fa-graduation-cap fa-2x"></i><br>
                            <small>graduation-cap</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-school">
                            <i class="fas fa-school fa-2x"></i><br>
                            <small>school</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-university">
                            <i class="fas fa-university fa-2x"></i><br>
                            <small>university</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-globe">
                            <i class="fas fa-globe fa-2x"></i><br>
                            <small>globe</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-flask">
                            <i class="fas fa-flask fa-2x"></i><br>
                            <small>flask</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-calculator">
                            <i class="fas fa-calculator fa-2x"></i><br>
                            <small>calculator</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-microscope">
                            <i class="fas fa-microscope fa-2x"></i><br>
                            <small>microscope</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-language">
                            <i class="fas fa-language fa-2x"></i><br>
                            <small>language</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-stethoscope">
                            <i class="fas fa-stethoscope fa-2x"></i><br>
                            <small>stethoscope</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-briefcase">
                            <i class="fas fa-briefcase fa-2x"></i><br>
                            <small>briefcase</small>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Icon selection
    document.querySelectorAll('.icon-btn').forEach(button => {
        button.addEventListener('click', function() {
            const icon = this.dataset.icon;
            document.getElementById('icon').value = icon;
            const modal = bootstrap.Modal.getInstance(document.getElementById('iconModal'));
            modal.hide();
        });
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0"><?php echo e(__('Add Category')); ?></h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('categories.index')); ?>"><?php echo e(__('Categories')); ?></a></li>
                            <li class="breadcrumb-item active"><?php echo e(__('Add Category')); ?></li>
                        </ol>
                    </nav>
                </div>
                <a href="<?php echo e(route('categories.index')); ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i><?php echo e(__('Back')); ?>

                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><?php echo e(__('Category Information')); ?></h5>
                </div>
                
                <form method="POST" action="<?php echo e(route('categories.store')); ?>">
                    <?php echo csrf_field(); ?>
                    
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Arabic Name -->
                            <div class="col-md-6">
                                <label for="name_ar" class="form-label required"><?php echo e(__('Arabic Name')); ?></label>
                                <input type="text" class="form-control <?php $__errorArgs = ['name_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="name_ar" name="name_ar" value="<?php echo e(old('name_ar')); ?>" required>
                                <?php $__errorArgs = ['name_ar'];
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

                            <!-- English Name -->
                            <div class="col-md-6">
                                <label for="name_en" class="form-label"><?php echo e(__('English Name')); ?></label>
                                <input type="text" class="form-control <?php $__errorArgs = ['name_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="name_en" name="name_en" value="<?php echo e(old('name_en')); ?>">
                                <?php $__errorArgs = ['name_en'];
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

                            <!-- Parent Category -->
                            <div class="col-md-6">
                                <label for="parent_id" class="form-label"><?php echo e(__('Parent Category')); ?></label>
                                <select class="form-control <?php $__errorArgs = ['parent_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="parent_id" name="parent_id">
                                    <option value=""><?php echo e(__('Root Category')); ?></option>
                                    <?php $__currentLoopData = $parentCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($parent['category']->id); ?>" 
                                                <?php echo e((old('parent_id', $parentId) == $parent['category']->id) ? 'selected' : ''); ?>>
                                            <?php echo e($parent['name']); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['parent_id'];
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

                            <!-- Sort Order -->
                            <div class="col-md-6">
                                <label for="sort_order" class="form-label"><?php echo e(__('Sort Order')); ?></label>
                                <input type="number" class="form-control <?php $__errorArgs = ['sort_order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="sort_order" name="sort_order" value="<?php echo e(old('sort_order', 0)); ?>" min="0">
                                <?php $__errorArgs = ['sort_order'];
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

                            <!-- Icon -->
                            <div class="col-md-6">
                                <label for="icon" class="form-label"><?php echo e(__('Icon')); ?></label>
                                <div class="input-group">
                                    <input type="text" class="form-control <?php $__errorArgs = ['icon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="icon" name="icon" value="<?php echo e(old('icon')); ?>" 
                                           placeholder="fas fa-folder">
                                    <button type="button" class="btn btn-outline-secondary" 
                                            data-bs-toggle="modal" data-bs-target="#iconModal">
                                        <i class="fas fa-icons"></i>
                                    </button>
                                </div>
                                <div class="form-text"><?php echo e(__('Font Awesome icon class (e.g., fas fa-folder)')); ?></div>
                                <?php $__errorArgs = ['icon'];
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

                            <!-- Color -->
                            <div class="col-md-6">
                                <label for="color" class="form-label"><?php echo e(__('Color')); ?></label>
                                <input type="color" class="form-control form-control-color <?php $__errorArgs = ['color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="color" name="color" value="<?php echo e(old('color', '#007bff')); ?>">
                                <?php $__errorArgs = ['color'];
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

                            <!-- Arabic Description -->
                            <div class="col-12">
                                <label for="description_ar" class="form-label"><?php echo e(__('Arabic Description')); ?></label>
                                <textarea class="form-control <?php $__errorArgs = ['description_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                          id="description_ar" name="description_ar" rows="3"><?php echo e(old('description_ar')); ?></textarea>
                                <?php $__errorArgs = ['description_ar'];
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

                            <!-- English Description -->
                            <div class="col-12">
                                <label for="description_en" class="form-label"><?php echo e(__('English Description')); ?></label>
                                <textarea class="form-control <?php $__errorArgs = ['description_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                          id="description_en" name="description_en" rows="3"><?php echo e(old('description_en')); ?></textarea>
                                <?php $__errorArgs = ['description_en'];
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

                            <!-- Status -->
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" 
                                           id="is_active" name="is_active" <?php echo e(old('is_active', true) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="is_active">
                                        <?php echo e(__('Category is active')); ?>

                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?php echo e(route('categories.index')); ?>" class="btn btn-secondary">
                                <?php echo e(__('Cancel')); ?>

                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i><?php echo e(__('Save Category')); ?>

                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Icon Selection Modal -->
<div class="modal fade" id="iconModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(__('Select Icon')); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2" id="iconGrid">
                    <!-- Common icons -->
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-folder">
                            <i class="fas fa-folder fa-2x"></i><br>
                            <small>folder</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-book">
                            <i class="fas fa-book fa-2x"></i><br>
                            <small>book</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-graduation-cap">
                            <i class="fas fa-graduation-cap fa-2x"></i><br>
                            <small>graduation-cap</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-school">
                            <i class="fas fa-school fa-2x"></i><br>
                            <small>school</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-university">
                            <i class="fas fa-university fa-2x"></i><br>
                            <small>university</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-globe">
                            <i class="fas fa-globe fa-2x"></i><br>
                            <small>globe</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-flask">
                            <i class="fas fa-flask fa-2x"></i><br>
                            <small>flask</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-calculator">
                            <i class="fas fa-calculator fa-2x"></i><br>
                            <small>calculator</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-microscope">
                            <i class="fas fa-microscope fa-2x"></i><br>
                            <small>microscope</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-language">
                            <i class="fas fa-language fa-2x"></i><br>
                            <small>language</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-stethoscope">
                            <i class="fas fa-stethoscope fa-2x"></i><br>
                            <small>stethoscope</small>
                        </button>
                    </div>
                    <div class="col-2 text-center">
                        <button type="button" class="btn btn-outline-secondary icon-btn" data-icon="fas fa-briefcase">
                            <i class="fas fa-briefcase fa-2x"></i><br>
                            <small>briefcase</small>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Icon selection
    document.querySelectorAll('.icon-btn').forEach(button => {
        button.addEventListener('click', function() {
            const icon = this.dataset.icon;
            document.getElementById('icon').value = icon;
            const modal = bootstrap.Modal.getInstance(document.getElementById('iconModal'));
            modal.hide();
        });
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\qdemy\resources\views/admin/categories/create.blade.php ENDPATH**/ ?>