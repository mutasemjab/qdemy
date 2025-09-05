<?php $__env->startSection('title', __('messages.add_content')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php echo e(__('messages.add_content')); ?></h3>
                    <p class="text-muted mb-0"><?php echo e(__('messages.course')); ?>: <?php echo e($course->title_en); ?></p>
                    <div class="card-tools">
                        <a href="<?php echo e(route('courses.sections.index', $course)); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.back')); ?>

                        </a>
                    </div>
                </div>

                <form action="<?php echo e(route('courses.contents.store', $course)); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="card-body">
                        <div class="row">
                            <!-- English Title -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="title_en" class="form-label">
                                        <?php echo e(__('messages.content_title_en')); ?> <span class="text-danger">*</span>
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
                                           value="<?php echo e(old('title_en')); ?>" 
                                           placeholder="<?php echo e(__('messages.enter_content_title_en')); ?>">
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
                                        <?php echo e(__('messages.content_title_ar')); ?> <span class="text-danger">*</span>
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
                                           value="<?php echo e(old('title_ar')); ?>" 
                                           placeholder="<?php echo e(__('messages.enter_content_title_ar')); ?>"
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

                            <!-- Content Type -->
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="content_type" class="form-label">
                                        <?php echo e(__('messages.content_type')); ?> <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control <?php $__errorArgs = ['content_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="content_type" 
                                            name="content_type"
                                            onchange="toggleContentFields()">
                                        <option value=""><?php echo e(__('messages.select_content_type')); ?></option>
                                        <option value="video" <?php echo e(old('content_type') == 'video' ? 'selected' : ''); ?>>
                                            <?php echo e(__('messages.video')); ?>

                                        </option>
                                        <option value="pdf" <?php echo e(old('content_type') == 'pdf' ? 'selected' : ''); ?>>
                                            <?php echo e(__('messages.pdf')); ?>

                                        </option>
                                        <option value="quiz" <?php echo e(old('content_type') == 'quiz' ? 'selected' : ''); ?>>
                                            <?php echo e(__('messages.quiz')); ?>

                                        </option>
                                        <option value="assignment" <?php echo e(old('content_type') == 'assignment' ? 'selected' : ''); ?>>
                                            <?php echo e(__('messages.assignment')); ?>

                                        </option>
                                    </select>
                                    <?php $__errorArgs = ['content_type'];
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

                            <!-- Access Type -->
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="is_free" class="form-label">
                                        <?php echo e(__('messages.access_type')); ?> <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control <?php $__errorArgs = ['is_free'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="is_free" 
                                            name="is_free">
                                        <option value=""><?php echo e(__('messages.select_access_type')); ?></option>
                                        <option value="1" <?php echo e(old('is_free') == '1' ? 'selected' : ''); ?>>
                                            <?php echo e(__('messages.free')); ?>

                                        </option>
                                        <option value="2" <?php echo e(old('is_free') == '2' ? 'selected' : ''); ?>>
                                            <?php echo e(__('messages.paid')); ?>

                                        </option>
                                    </select>
                                    <?php $__errorArgs = ['is_free'];
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

                            <!-- Order -->
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="order" class="form-label">
                                        <?php echo e(__('messages.order')); ?> <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" 
                                           class="form-control <?php $__errorArgs = ['order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="order" 
                                           name="order" 
                                           value="<?php echo e(old('order', 1)); ?>" 
                                           min="0"
                                           placeholder="1">
                                    <?php $__errorArgs = ['order'];
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

                            <!-- Section -->
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="section_id" class="form-label"><?php echo e(__('messages.section')); ?></label>
                                    <select class="form-control <?php $__errorArgs = ['section_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="section_id" 
                                            name="section_id">
                                        <option value=""><?php echo e(__('messages.select_section_or_direct')); ?></option>
                                        <?php
                                            $parentSections = $sections->whereNull('parent_id');
                                        ?>
                                        <?php $__currentLoopData = $parentSections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php echo $__env->make('admin.courses.partials.section-option', [
                                                'section' => $section, 
                                                'allSections' => $sections, 
                                                'level' => 0,
                                                'selectedId' => old('section_id', request('section_id'))
                                            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <small class="form-text text-muted">
                                        <?php echo e(__('messages.section_help')); ?>

                                    </small>
                                    <?php $__errorArgs = ['section_id'];
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
                        </div>

                        <!-- Video Fields -->
                        <div id="video-fields" class="row" style="display: none;">
                            <div class="col-12">
                                <h5 class="text-primary"><?php echo e(__('messages.video_details')); ?></h5>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="video_type" class="form-label">
                                        <?php echo e(__('messages.video_type')); ?> <span class="text-danger">*</span>
                                    </label>
                                    <select   onchange="toggleContentFields()" class="form-control <?php $__errorArgs = ['video_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="video_type" 
                                            name="video_type">
                                        <option value=""><?php echo e(__('messages.select_video_type')); ?></option>
                                        <option value="youtube" <?php echo e(old('video_type') == 'youtube' ? 'selected' : ''); ?>>
                                            YouTube
                                        </option>
                                        <option value="bunny" <?php echo e(old('video_type') == 'bunny' ? 'selected' : ''); ?>>
                                            Bunny CDN
                                        </option>
                                    </select>
                                    <?php $__errorArgs = ['video_type'];
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

                            <div class="col-md-4" id="video_url_field">
                                <div class="form-group mb-3">
                                    <label for="video_url" class="form-label">
                                        <?php echo e(__('messages.video_url')); ?> <span class="text-danger">*</span>
                                    </label>
                                    <input type="url" 
                                           class="form-control <?php $__errorArgs = ['video_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="video_url" 
                                           name="video_url" 
                                           value="<?php echo e(old('video_url')); ?>" 
                                           placeholder="https://example.com/video">
                                    <?php $__errorArgs = ['video_url'];
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

                            <div class="col-md-6" id="upload_video_field">
                                <div class="form-group mb-3">
                                    <label for="upload_video" class="form-label">
                                        <?php echo e(__('messages.upload_video')); ?>

                                    </label>
                                    <input type="file" 
                                           class="form-control <?php $__errorArgs = ['upload_video'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="upload_video" 
                                           name="upload_video" 
                                           accept="video/*">
                                    <small class="form-text text-muted"><?php echo e(__('messages.video_requirements_optional')); ?></small>
                                    <?php $__errorArgs = ['upload_video'];
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

                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="video_duration" class="form-label"><?php echo e(__('messages.video_duration')); ?></label>
                                    <input type="number" 
                                           class="form-control <?php $__errorArgs = ['video_duration'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="video_duration" 
                                           name="video_duration" 
                                           value="<?php echo e(old('video_duration')); ?>" 
                                           min="0"
                                           placeholder="<?php echo e(__('messages.duration_seconds')); ?>">
                                    <small class="form-text text-muted"><?php echo e(__('messages.duration_seconds_help')); ?></small>
                                    <?php $__errorArgs = ['video_duration'];
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
                        </div>

                        <!-- PDF Fields -->
                        <div id="pdf-fields" class="row" style="display: none;">
                            <div class="col-12">
                                <h5 class="text-primary"><?php echo e(__('messages.pdf_details')); ?></h5>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="pdf_type" class="form-label">
                                        <?php echo e(__('messages.pdf_type')); ?> <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control <?php $__errorArgs = ['pdf_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="pdf_type" 
                                            name="pdf_type">
                                        <option value=""><?php echo e(__('messages.select_pdf_type')); ?></option>
                                        <option value="homework" <?php echo e(old('pdf_type') == 'homework' ? 'selected' : ''); ?>>
                                            <?php echo e(__('messages.homework')); ?>

                                        </option>
                                        <option value="worksheet" <?php echo e(old('pdf_type') == 'worksheet' ? 'selected' : ''); ?>>
                                            <?php echo e(__('messages.worksheet')); ?>

                                        </option>
                                        <option value="notes" <?php echo e(old('pdf_type') == 'notes' ? 'selected' : ''); ?>>
                                            <?php echo e(__('messages.notes')); ?>

                                        </option>
                                        <option value="other" <?php echo e(old('pdf_type') == 'other' ? 'selected' : ''); ?>>
                                            <?php echo e(__('messages.other')); ?>

                                        </option>
                                    </select>
                                    <?php $__errorArgs = ['pdf_type'];
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

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="file_path" class="form-label">
                                        <?php echo e(__('messages.pdf_file')); ?> <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" 
                                           class="form-control <?php $__errorArgs = ['file_path'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="file_path" 
                                           name="file_path" 
                                           accept=".pdf">
                                    <small class="form-text text-muted"><?php echo e(__('messages.pdf_requirements')); ?></small>
                                    <?php $__errorArgs = ['file_path'];
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
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo e(route('courses.sections.index', $course)); ?>" class="btn btn-secondary">
                                <?php echo e(__('messages.cancel')); ?>

                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> <?php echo e(__('messages.save')); ?>

                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggleContentFields() {
    const contentType = document.getElementById('content_type').value;
    const videoType   = document.getElementById('video_type').value;
    const videoFields = document.getElementById('video-fields');
    const videoUpload = document.getElementById('upload_video_field');
    const videoUrl    = document.getElementById('video_url_field');
    const pdfFields   = document.getElementById('pdf-fields');
    

    // Hide all fields
    videoFields.style.display = 'none';
    videoUpload.style.display = 'none';
    videoUrl.style.display    = 'none';
    pdfFields.style.display   = 'none';
    
    // Show relevant fields
    if (contentType === 'video') {
        videoFields.style.display = 'block';
    }
    if (contentType === 'video' && videoType == 'youtube') {
        videoUrl.style.display    = 'block';
    } else if (contentType === 'video' && videoType == 'bunny') {
        videoUpload.style.display = 'block';
    } else if (contentType === 'pdf' || contentType === 'quiz' || contentType === 'assignment') {
        pdfFields.style.display = 'block';
    }

}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleContentFields();
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/admin/courses/contents/create.blade.php ENDPATH**/ ?>