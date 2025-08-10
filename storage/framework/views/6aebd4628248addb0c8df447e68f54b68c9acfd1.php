<?php $__env->startSection('title', __('messages.add_exam')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php echo e(__('messages.add_exam')); ?></h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('exams.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.back')); ?>

                        </a>
                    </div>
                </div>

                <form action="<?php echo e(route('exams.store')); ?>" method="POST" id="examForm">
                    <?php echo csrf_field(); ?>
                    <div class="card-body">
                        <div class="row">
                            <!-- Exam Title English -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="title_en" class="form-label">
                                        <?php echo e(__('messages.title_en')); ?> <span class="text-danger">*</span>
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
                                           placeholder="<?php echo e(__('messages.enter_exam_title_en')); ?>">
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

                            <!-- Exam Title Arabic -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="title_ar" class="form-label">
                                        <?php echo e(__('messages.title_ar')); ?> <span class="text-danger">*</span>
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
                                           placeholder="<?php echo e(__('messages.enter_exam_title_ar')); ?>"
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

                            <!-- Description English -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="description_en" class="form-label">
                                        <?php echo e(__('messages.description_en')); ?>

                                    </label>
                                    <textarea class="form-control <?php $__errorArgs = ['description_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                              id="description_en" 
                                              name="description_en" 
                                              rows="4" 
                                              placeholder="<?php echo e(__('messages.enter_description_en')); ?>"><?php echo e(old('description_en')); ?></textarea>
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
                            </div>

                            <!-- Description Arabic -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="description_ar" class="form-label">
                                        <?php echo e(__('messages.description_ar')); ?>

                                    </label>
                                    <textarea class="form-control <?php $__errorArgs = ['description_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                              id="description_ar" 
                                              name="description_ar" 
                                              rows="4" 
                                              placeholder="<?php echo e(__('messages.enter_description_ar')); ?>"
                                              dir="rtl"><?php echo e(old('description_ar')); ?></textarea>
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
                            </div>

                            <!-- Course -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="course_id" class="form-label">
                                        <?php echo e(__('messages.course')); ?> <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select <?php $__errorArgs = ['course_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="course_id" 
                                            name="course_id">
                                        <option value=""><?php echo e(__('messages.select_course')); ?></option>
                                        <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($course->id); ?>" 
                                                    <?php echo e(old('course_id') == $course->id ? 'selected' : ''); ?>>
                                                <?php echo e($course->title_en); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['course_id'];
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

                            <!-- Duration -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="duration_minutes" class="form-label">
                                        <?php echo e(__('messages.duration_minutes')); ?>

                                    </label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control <?php $__errorArgs = ['duration_minutes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               id="duration_minutes" 
                                               name="duration_minutes" 
                                               value="<?php echo e(old('duration_minutes')); ?>" 
                                               min="1"
                                               placeholder="<?php echo e(__('messages.leave_blank_for_unlimited')); ?>">
                                        <span class="input-group-text"><?php echo e(__('messages.minutes')); ?></span>
                                    </div>
                                    <small class="form-text text-muted"><?php echo e(__('messages.leave_blank_for_unlimited_time')); ?></small>
                                    <?php $__errorArgs = ['duration_minutes'];
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

                            <!-- Attempts Allowed -->
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="attempts_allowed" class="form-label">
                                        <?php echo e(__('messages.attempts_allowed')); ?> <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" 
                                           class="form-control <?php $__errorArgs = ['attempts_allowed'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="attempts_allowed" 
                                           name="attempts_allowed" 
                                           value="<?php echo e(old('attempts_allowed', 1)); ?>" 
                                           min="1" 
                                           max="10">
                                    <?php $__errorArgs = ['attempts_allowed'];
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

                            <!-- Passing Grade -->
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="passing_grade" class="form-label">
                                        <?php echo e(__('messages.passing_grade')); ?> <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control <?php $__errorArgs = ['passing_grade'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               id="passing_grade" 
                                               name="passing_grade" 
                                               value="<?php echo e(old('passing_grade', 50)); ?>" 
                                               min="0" 
                                               max="100" 
                                               step="0.01">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <?php $__errorArgs = ['passing_grade'];
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

                            <!-- Status -->
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label"><?php echo e(__('messages.status')); ?></label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="is_active" 
                                               name="is_active" 
                                               value="1"
                                               <?php echo e(old('is_active', true) ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="is_active">
                                            <?php echo e(__('messages.active')); ?>

                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Start Date -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="start_date" class="form-label">
                                        <?php echo e(__('messages.start_date')); ?>

                                    </label>
                                    <input type="datetime-local" 
                                           class="form-control <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="start_date" 
                                           name="start_date" 
                                           value="<?php echo e(old('start_date')); ?>">
                                    <small class="form-text text-muted"><?php echo e(__('messages.leave_blank_for_no_restriction')); ?></small>
                                    <?php $__errorArgs = ['start_date'];
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

                            <!-- End Date -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="end_date" class="form-label">
                                        <?php echo e(__('messages.end_date')); ?>

                                    </label>
                                    <input type="datetime-local" 
                                           class="form-control <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="end_date" 
                                           name="end_date" 
                                           value="<?php echo e(old('end_date')); ?>">
                                    <small class="form-text text-muted"><?php echo e(__('messages.leave_blank_for_no_restriction')); ?></small>
                                    <?php $__errorArgs = ['end_date'];
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

                        <!-- Exam Settings -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3"><?php echo e(__('messages.exam_settings')); ?></h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   id="shuffle_questions" 
                                                   name="shuffle_questions" 
                                                   value="1"
                                                   <?php echo e(old('shuffle_questions') ? 'checked' : ''); ?>>
                                            <label class="form-check-label" for="shuffle_questions">
                                                <strong><?php echo e(__('messages.shuffle_questions')); ?></strong><br>
                                                <small class="text-muted"><?php echo e(__('messages.randomize_question_order')); ?></small>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   id="shuffle_options" 
                                                   name="shuffle_options" 
                                                   value="1"
                                                   <?php echo e(old('shuffle_options') ? 'checked' : ''); ?>>
                                            <label class="form-check-label" for="shuffle_options">
                                                <strong><?php echo e(__('messages.shuffle_options')); ?></strong><br>
                                                <small class="text-muted"><?php echo e(__('messages.randomize_answer_options')); ?></small>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   id="show_results_immediately" 
                                                   name="show_results_immediately" 
                                                   value="1"
                                                   <?php echo e(old('show_results_immediately', true) ? 'checked' : ''); ?>>
                                            <label class="form-check-label" for="show_results_immediately">
                                                <strong><?php echo e(__('messages.show_results_immediately')); ?></strong><br>
                                                <small class="text-muted"><?php echo e(__('messages.show_results_after_submission')); ?></small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Information Note -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    <strong><?php echo e(__('messages.note')); ?>:</strong> <?php echo e(__('messages.exam_creation_note')); ?>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo e(route('exams.index')); ?>" class="btn btn-secondary">
                                <?php echo e(__('messages.cancel')); ?>

                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> <?php echo e(__('messages.create_exam')); ?>

                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Form validation
document.getElementById('examForm').addEventListener('submit', function(e) {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    
    // Validate date range
    if (startDate && endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        
        if (start >= end) {
            e.preventDefault();
            alert('<?php echo e(__("messages.end_date_must_be_after_start_date")); ?>');
            return;
        }
    }
    
    // Validate passing grade
    const passingGrade = parseFloat(document.getElementById('passing_grade').value);
    if (passingGrade < 0 || passingGrade > 100) {
        e.preventDefault();
        alert('<?php echo e(__("messages.passing_grade_must_be_between_0_and_100")); ?>');
        return;
    }
    
    // Validate attempts
    const attempts = parseInt(document.getElementById('attempts_allowed').value);
    if (attempts < 1 || attempts > 10) {
        e.preventDefault();
        alert('<?php echo e(__("messages.attempts_must_be_between_1_and_10")); ?>');
        return;
    }
});

// Set minimum date for start_date to today
document.addEventListener('DOMContentLoaded', function() {
    const now = new Date();
    const isoString = now.toISOString().slice(0, 16);
    document.getElementById('start_date').setAttribute('min', isoString);
    
    // Update end_date minimum when start_date changes
    document.getElementById('start_date').addEventListener('change', function() {
        const startDate = this.value;
        if (startDate) {
            document.getElementById('end_date').setAttribute('min', startDate);
        }
    });
});

// Auto-fill Arabic title when English title is entered (optional helper)
document.getElementById('title_en').addEventListener('blur', function() {
    const titleAr = document.getElementById('title_ar');
    if (!titleAr.value && this.value) {
        // You can add auto-translation logic here if needed
        // For now, just focus on the Arabic field
        titleAr.focus();
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\qdemy\resources\views/admin/exams/create.blade.php ENDPATH**/ ?>