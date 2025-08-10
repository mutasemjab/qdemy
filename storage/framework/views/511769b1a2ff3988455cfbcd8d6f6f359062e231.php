

<?php $__env->startSection('title', __('messages.edit_section')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php echo e(__('messages.edit_section')); ?></h3>
                    <p class="text-muted mb-0"><?php echo e(__('messages.course')); ?>: <?php echo e($course->title_en); ?></p>
                    <div class="card-tools">
                        <a href="<?php echo e(route('courses.sections.index', $course)); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.back')); ?>

                        </a>
                    </div>
                </div>

                <form action="<?php echo e(route('courses.sections.update', [$course, $section])); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="card-body">
                        <div class="row">
                            <!-- English Title -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="title_en" class="form-label">
                                        <?php echo e(__('messages.section_title_en')); ?> <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control <?php $__errorArgs = ['title_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="title_en" 
                                           name="title_en" 
                                           value="<?php echo e(old('title_en', $section->title_en)); ?>" 
                                           placeholder="<?php echo e(__('messages.enter_section_title_en')); ?>">
                                    <?php $__errorArgs = ['title_en'];
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
                            </div>

                            <!-- Arabic Title -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="title_ar" class="form-label">
                                        <?php echo e(__('messages.section_title_ar')); ?> <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control <?php $__errorArgs = ['title_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="title_ar" 
                                           name="title_ar" 
                                           value="<?php echo e(old('title_ar', $section->title_ar)); ?>" 
                                           placeholder="<?php echo e(__('messages.enter_section_title_ar')); ?>"
                                           dir="rtl">
                                    <?php $__errorArgs = ['title_ar'];
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
                            </div>

                            <!-- Parent Section -->
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="parent_id" class="form-label"><?php echo e(__('messages.parent_section')); ?></label>
                                    <select class="form-control <?php $__errorArgs = ['parent_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="parent_id" 
                                            name="parent_id">
                                        <option value=""><?php echo e(__('messages.select_parent_section')); ?></option>
                                        <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parentSection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($parentSection->id); ?>" 
                                                    <?php echo e(old('parent_id', $section->parent_id) == $parentSection->id ? 'selected' : ''); ?>>
                                                <?php echo e($parentSection->title_en); ?> - <?php echo e($parentSection->title_ar); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <small class="form-text text-muted">
                                        <?php echo e(__('messages.parent_section_help')); ?>

                                    </small>
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
                            </div>

                            <!-- Section Statistics -->
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-info-circle"></i> <?php echo e(__('messages.section_statistics')); ?></h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong><?php echo e(__('messages.total_contents')); ?>:</strong> 
                                            <?php echo e($section->contents->count()); ?>

                                        </div>
                                        <div class="col-md-4">
                                            <strong><?php echo e(__('messages.free_contents')); ?>:</strong> 
                                            <?php echo e($section->contents->where('is_free', 1)->count()); ?>

                                        </div>
                                        <div class="col-md-4">
                                            <strong><?php echo e(__('messages.paid_contents')); ?>:</strong> 
                                            <?php echo e($section->contents->where('is_free', 2)->count()); ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo e(route('courses.sections.index', $course)); ?>" class="btn btn-secondary">
                                <?php echo e(__('messages.cancel')); ?>

                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> <?php echo e(__('messages.update')); ?>

                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\qdemy\resources\views/admin/courses/sections/edit.blade.php ENDPATH**/ ?>