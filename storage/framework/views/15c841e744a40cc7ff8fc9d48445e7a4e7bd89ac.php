

<?php $__env->startSection('title', __('messages.add_new_blog')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php echo e(__('messages.add_new_blog')); ?></h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('blogs.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.back')); ?>

                        </a>
                    </div>
                </div>

                <form action="<?php echo e(route('blogs.store')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="card-body">
                        <div class="row">
                            <!-- Arabic Title -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title_ar"><?php echo e(__('messages.title_ar')); ?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['title_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="title_ar" name="title_ar" value="<?php echo e(old('title_ar')); ?>" 
                                           placeholder="<?php echo e(__('messages.enter_title_ar')); ?>" dir="rtl">
                                    <?php $__errorArgs = ['title_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <!-- English Title -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title_en"><?php echo e(__('messages.title_en')); ?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['title_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="title_en" name="title_en" value="<?php echo e(old('title_en')); ?>" 
                                           placeholder="<?php echo e(__('messages.enter_title_en')); ?>">
                                    <?php $__errorArgs = ['title_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Arabic Description -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description_ar"><?php echo e(__('messages.description_ar')); ?> <span class="text-danger">*</span></label>
                                    <textarea class="form-control tinymce <?php $__errorArgs = ['description_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                              id="description_ar" name="description_ar" rows="6" 
                                              placeholder="<?php echo e(__('messages.enter_description_ar')); ?>" dir="rtl"><?php echo e(old('description_ar')); ?></textarea>
                                    <?php $__errorArgs = ['description_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <!-- English Description -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description_en"><?php echo e(__('messages.description_en')); ?> <span class="text-danger">*</span></label>
                                    <textarea class="form-control tinymce <?php $__errorArgs = ['description_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                              id="description_en" name="description_en" rows="6" 
                                              placeholder="<?php echo e(__('messages.enter_description_en')); ?>"><?php echo e(old('description_en')); ?></textarea>
                                    <?php $__errorArgs = ['description_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Main Photo -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="photo"><?php echo e(__('messages.main_photo')); ?> <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control-file <?php $__errorArgs = ['photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="photo" name="photo" accept="image/*">
                                    <small class="form-text text-muted">
                                        <?php echo e(__('messages.allowed_formats')); ?>: JPG, JPEG, PNG, GIF. <?php echo e(__('messages.max_size')); ?>: 2MB
                                    </small>
                                    <?php $__errorArgs = ['photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <div class="mt-2">
                                        <img id="photo-preview" src="#" alt="" class="img-thumbnail d-none" 
                                             style="width: 150px; height: 150px; object-fit: cover;">
                                    </div>
                                </div>
                            </div>

                            <!-- Cover Photo -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="photo_cover"><?php echo e(__('messages.cover_photo')); ?></label>
                                    <input type="file" class="form-control-file <?php $__errorArgs = ['photo_cover'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="photo_cover" name="photo_cover" accept="image/*">
                                    <small class="form-text text-muted">
                                        <?php echo e(__('messages.allowed_formats')); ?>: JPG, JPEG, PNG, GIF. <?php echo e(__('messages.max_size')); ?>: 2MB
                                    </small>
                                    <?php $__errorArgs = ['photo_cover'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <div class="mt-2">
                                        <img id="cover-preview" src="#" alt="" class="img-thumbnail d-none" 
                                             style="width: 150px; height: 150px; object-fit: cover;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> <?php echo e(__('messages.save')); ?>

                        </button>
                        <a href="<?php echo e(route('blogs.index')); ?>" class="btn btn-secondary ml-2">
                            <i class="fas fa-times"></i> <?php echo e(__('messages.cancel')); ?>

                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<!-- TinyMCE -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js"></script>
<script>
    // Initialize TinyMCE
    tinymce.init({
        selector: '.tinymce',
        height: 300,
        menubar: false,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table paste code help wordcount'
        ],
        toolbar: 'undo redo | formatselect | ' +
        'bold italic backcolor | alignleft aligncenter ' +
        'alignright alignjustify | bullist numlist outdent indent | ' +
        'removeformat | help',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
        },
        // RTL support for Arabic editor
        directionality: function(inst) {
            return inst.id === 'description_ar' ? 'rtl' : 'ltr';
        }
    });

    // Preview main photo
    $('#photo').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#photo-preview').attr('src', e.target.result).removeClass('d-none');
            }
            reader.readAsDataURL(file);
        } else {
            $('#photo-preview').addClass('d-none');
        }
    });

    // Preview cover photo
    $('#photo_cover').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#cover-preview').attr('src', e.target.result).removeClass('d-none');
            }
            reader.readAsDataURL(file);
        } else {
            $('#cover-preview').addClass('d-none');
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\qdemy\resources\views/admin/blogs/create.blade.php ENDPATH**/ ?>