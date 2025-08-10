

<?php $__env->startSection('title', __('messages.add_new_bank_question')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php echo e(__('messages.add_new_bank_question')); ?></h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('bank-questions.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.back')); ?>

                        </a>
                    </div>
                </div>

                <form action="<?php echo e(route('bank-questions.store')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="card-body">
                        <div class="row">
                            <!-- Parent Category -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="parent_category"><?php echo e(__('messages.parent_category')); ?> <span class="text-danger">*</span></label>
                                    <select class="form-control <?php $__errorArgs = ['parent_category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="parent_category" name="parent_category">
                                        <option value=""><?php echo e(__('messages.select_parent_category')); ?></option>
                                        <?php $__currentLoopData = $parentCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($category->id); ?>" <?php echo e(old('parent_category') == $category->id ? 'selected' : ''); ?>>
                                                <?php if($category->icon): ?>
                                                    <i class="<?php echo e($category->icon); ?>"></i>
                                                <?php endif; ?>
                                                <?php echo e($category->localized_name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['parent_category'];
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

                            <!-- Child Category -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category_id"><?php echo e(__('messages.child_category')); ?> <span class="text-danger">*</span></label>
                                    <select class="form-control <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="category_id" name="category_id" disabled>
                                        <option value=""><?php echo e(__('messages.select_child_category')); ?></option>
                                    </select>
                                    <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <small class="form-text text-muted">
                                        <?php echo e(__('messages.select_parent_first')); ?>

                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- PDF File -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="pdf"><?php echo e(__('messages.pdf_file')); ?> <span class="text-danger">*</span></label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input <?php $__errorArgs = ['pdf'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               id="pdf" name="pdf" accept=".pdf">
                                        <label class="custom-file-label" for="pdf"><?php echo e(__('messages.choose_pdf_file')); ?></label>
                                    </div>
                                    <small class="form-text text-muted">
                                        <?php echo e(__('messages.allowed_formats')); ?>: PDF. <?php echo e(__('messages.max_size')); ?>: 10MB
                                    </small>
                                    <?php $__errorArgs = ['pdf'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    
                                    <!-- PDF Preview -->
                                    <div class="mt-3 d-none" id="pdf-preview">
                                        <div class="alert alert-info">
                                            <i class="fas fa-file-pdf text-danger mr-2"></i>
                                            <span id="pdf-name"></span>
                                            <span class="float-right" id="pdf-size"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> <?php echo e(__('messages.save')); ?>

                        </button>
                        <a href="<?php echo e(route('bank-questions.index')); ?>" class="btn btn-secondary ml-2">
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
<script>
    $(document).ready(function() {
        // Handle parent category change
        $('#parent_category').change(function() {
            var parentId = $(this).val();
            var childSelect = $('#category_id');
            
            // Reset child category
            childSelect.html('<option value=""><?php echo e(__('messages.select_child_category')); ?></option>');
            childSelect.prop('disabled', true);
            
            if (parentId) {
                // Enable the parent category as selectable
                childSelect.append('<option value="' + parentId + '"><?php echo e(__('messages.use_parent_category')); ?></option>');
                
                // Fetch child categories
                $.ajax({
                    url: '<?php echo e(route('bank-questions.get-children', ':id')); ?>'.replace(':id', parentId),
                    type: 'GET',
                    success: function(data) {
                        if (data.length > 0) {
                            $.each(data, function(key, category) {
                                childSelect.append('<option value="' + category.id + '">' + 
                                    (category.name_ar || category.name_en) + '</option>');
                            });
                        }
                        childSelect.prop('disabled', false);
                    },
                    error: function() {
                        alert('<?php echo e(__('messages.error_loading_categories')); ?>');
                    }
                });
            }
        });

        // Handle file input change
        $('#pdf').change(function() {
            var file = this.files[0];
            var preview = $('#pdf-preview');
            
            if (file) {
                if (file.type !== 'application/pdf') {
                    alert('<?php echo e(__('messages.please_select_pdf_file')); ?>');
                    $(this).val('');
                    preview.addClass('d-none');
                    return;
                }
                
                var fileName = file.name;
                var fileSize = (file.size / 1024 / 1024).toFixed(2) + ' MB';
                
                $('.custom-file-label').text(fileName);
                $('#pdf-name').text(fileName);
                $('#pdf-size').text(fileSize);
                preview.removeClass('d-none');
            } else {
                $('.custom-file-label').text('<?php echo e(__('messages.choose_pdf_file')); ?>');
                preview.addClass('d-none');
            }
        });

        // Restore old values if validation fails
        <?php if(old('parent_category')): ?>
            $('#parent_category').trigger('change');
            setTimeout(function() {
                $('#category_id').val('<?php echo e(old('category_id')); ?>');
            }, 500);
        <?php endif; ?>
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\qdemy\resources\views/admin/bank_questions/create.blade.php ENDPATH**/ ?>