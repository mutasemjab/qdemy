<?php $__env->startSection('title', __('messages.edit_exam')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php echo e(__('messages.edit_exam')); ?></h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('exams.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.back')); ?>

                        </a>
                    </div>
                </div>

                <form action="<?php echo e(route('exams.update', $exam)); ?>" method="POST" id="examForm">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
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
                                           value="<?php echo e(old('title_en', $exam->title_en)); ?>"
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
                                           value="<?php echo e(old('title_ar', $exam->title_ar)); ?>"
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
                                              placeholder="<?php echo e(__('messages.enter_description_en')); ?>"><?php echo e(old('description_en', $exam->description_en)); ?></textarea>
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
                                              dir="rtl"><?php echo e(old('description_ar', $exam->description_ar)); ?></textarea>
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
                                        <?php echo e(__('messages.course')); ?> <span class="text-danger"></span>
                                    </label>
                                    <select class="form-select form-control <?php $__errorArgs = ['course_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="course_id"
                                            name="course_id"
                                            onchange="loadCourseSections(this.value)">
                                        <option value=""><?php echo e(__('messages.select_course')); ?></option>
                                        <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($course->id); ?>"
                                                    <?php echo e(old('course_id', $exam->course_id) == $course->id ? 'selected' : ''); ?>>
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

                            <!-- Section (New Field) -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="section_id" class="form-label">
                                        <?php echo e(__('messages.section')); ?>

                                    </label>
                                    <select class="form-select  form-control <?php $__errorArgs = ['section_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="section_id"
                                            name="section_id"
                                            <?php echo e($exam->course_id ? '' : 'disabled'); ?>>
                                        <option value=""><?php echo e(__('messages.select_section_optional')); ?></option>
                                        <?php if($exam->course_id && isset($sections)): ?>
                                            <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($section->id); ?>"
                                                        <?php echo e(old('section_id', $exam->section_id) == $section->id ? 'selected' : ''); ?>>
                                                    <?php echo e(app()->getLocale() === 'ar' ? $section->title_ar : $section->title_en); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </select>
                                    <small class="form-text text-muted"><?php echo e(__('messages.select_course_first')); ?></small>
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
                                               value="<?php echo e(old('duration_minutes', $exam->duration_minutes)); ?>"
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
                            <div class="col-md-6">
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
                                           value="<?php echo e(old('attempts_allowed', $exam->attempts_allowed)); ?>"
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
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="passing_grade" class="form-label">
                                        <?php echo e(__('messages.passing_grade')); ?> (%) <span class="text-danger">*</span>
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
                                               value="<?php echo e(old('passing_grade', $exam->passing_grade)); ?>"
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
                                           value="<?php echo e(old('start_date', $exam->start_date ? \Carbon\Carbon::parse($exam->start_date)->format('Y-m-d\TH:i') : '')); ?>">
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
                                           value="<?php echo e(old('end_date', $exam->end_date ? \Carbon\Carbon::parse($exam->end_date)->format('Y-m-d\TH:i') : '')); ?>">
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
                                                   <?php echo e(old('shuffle_questions', $exam->shuffle_questions) ? 'checked' : ''); ?>>
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
                                                   <?php echo e(old('shuffle_options', $exam->shuffle_options) ? 'checked' : ''); ?>>
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
                                                   <?php echo e(old('show_results_immediately', $exam->show_results_immediately) ? 'checked' : ''); ?>>
                                            <label class="form-check-label" for="show_results_immediately">
                                                <strong><?php echo e(__('messages.show_results_immediately')); ?></strong><br>
                                                <small class="text-muted"><?php echo e(__('messages.show_results_after_submission')); ?></small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input"
                                                   type="checkbox"
                                                   id="is_active"
                                                   name="is_active"
                                                   value="1"
                                                   <?php echo e(old('is_active', $exam->is_active) ? 'checked' : ''); ?>>
                                            <label class="form-check-label" for="is_active">
                                                <strong><?php echo e(__('messages.is_active')); ?></strong><br>
                                                <small class="text-muted"><?php echo e(__('messages.exam_available_for_students')); ?></small>
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
                                    <strong><?php echo e(__('messages.note')); ?>:</strong> <?php echo e(__('messages.exam_update_note')); ?>

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
                                <i class="fas fa-save"></i> <?php echo e(__('messages.update_exam')); ?>

                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
// Function to load sections when course is selected
function loadCourseSections(courseId, selectedSectionId = null) {
    const sectionSelect = document.getElementById('section_id');

    // Clear current options
    sectionSelect.innerHTML = '<option value=""><?php echo e(__('messages.select_section_optional')); ?></option>';

    if (!courseId) {
        sectionSelect.disabled = true;
        return;
    }

    // Enable the section select
    sectionSelect.disabled = false;

    // Fetch sections via AJAX
    fetch(`<?php echo e(route('sections.ajax')); ?>/${courseId}`, {
            method: 'POST', // استخدم POST أو أي Method محتاج حماية
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
        })
        .then(response => response.json())
        .then(sections => {
            sections.forEach(section => {
                const option = new Option(section.title, section.id);
                if (selectedSectionId && section.id == selectedSectionId) {
                    option.selected = true;
                }
                sectionSelect.add(option);
            });
        })
        .catch(error => {
            console.error('Error loading sections:', error);
            sectionSelect.disabled = true;
        });
}

// Load sections on page load if course is selected
document.addEventListener('DOMContentLoaded', function() {
    const courseId = document.getElementById('course_id').value;
    const selectedSectionId = '<?php echo e(old('section_id', $exam->section_id)); ?>';

    if (courseId) {
        // If sections are already loaded from server (on edit), don't reload
        const sectionSelect = document.getElementById('section_id');
        if (sectionSelect.options.length <= 1) {
            loadCourseSections(courseId, selectedSectionId);
        }
    }
});

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
        alert('<?php echo e(__("messages.passing_grade_must_be_between_0_100")); ?>');
        return;
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/admin/exams/edit.blade.php ENDPATH**/ ?>