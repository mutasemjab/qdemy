<?php $__env->startSection('title', __('messages.edit_subject')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php echo e(__('messages.edit_subject')); ?>: <?php echo e($subject->name_ar); ?></h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('subjects.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.back')); ?>

                        </a>
                    </div>
                </div>

                <form action="<?php echo e(route('subjects.update', $subject->id)); ?>" method="POST" id="subjectForm">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="card-body">
                        <div class="row">
                            <!-- Arabic Name -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name_ar" class="form-label">
                                        <?php echo e(__('messages.name_ar')); ?> <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control <?php $__errorArgs = ['name_ar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="name_ar"
                                           name="name_ar"
                                           value="<?php echo e(old('name_ar', $subject->name_ar)); ?>"
                                           placeholder="<?php echo e(__('messages.enter_name_ar')); ?>"
                                           dir="rtl"
                                           maxlength="100"
                                           required>
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
                            </div>

                            <!-- English Name -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name_en" class="form-label">
                                        <?php echo e(__('messages.name_en')); ?> <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           class="form-control <?php $__errorArgs = ['name_en'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="name_en"
                                           name="name_en"
                                           value="<?php echo e(old('name_en', $subject->name_en)); ?>"
                                           placeholder="<?php echo e(__('messages.enter_name_en')); ?>"
                                           maxlength="100"
                                           required>
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
                            </div>

                            <!-- Arabic Description -->
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
                                              dir="rtl"
                                              maxlength="500"><?php echo e(old('description_ar', $subject->description_ar)); ?></textarea>
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

                            <!-- English Description -->
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
                                              placeholder="<?php echo e(__('messages.enter_description_en')); ?>"
                                              maxlength="500"><?php echo e(old('description_en', $subject->description_en)); ?></textarea>
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

                            <!-- Icon -->
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="icon" class="form-label">
                                        <?php echo e(__('messages.icon')); ?>

                                    </label>
                                    <input type="text"
                                           class="form-control <?php $__errorArgs = ['icon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="icon"
                                           name="icon"
                                           value="<?php echo e(old('icon', $subject->icon)); ?>"
                                           placeholder="<?php echo e(__('messages.enter_icon_class')); ?>"
                                           maxlength="100">
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
                            </div>

                            <!-- Color -->
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="color" class="form-label">
                                        <?php echo e(__('messages.color')); ?>

                                    </label>
                                    <input type="color"
                                           class="form-control <?php $__errorArgs = ['color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="color"
                                           name="color"
                                           value="<?php echo e(old('color', $subject->color)); ?>">
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
                            </div>

                            <!-- Status -->
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="is_active" class="form-label">
                                        <?php echo e(__('messages.status')); ?>

                                    </label>
                                    <select class="form-control <?php $__errorArgs = ['is_active'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="is_active"
                                            name="is_active">
                                        <option value="1" <?php echo e(old('is_active', $subject->is_active) == 1 ? 'selected' : ''); ?>>
                                            <?php echo e(__('messages.active')); ?>

                                        </option>
                                        <option value="0" <?php echo e(old('is_active', $subject->is_active) == 0 ? 'selected' : ''); ?>>
                                            <?php echo e(__('messages.inactive')); ?>

                                        </option>
                                    </select>
                                    <?php $__errorArgs = ['is_active'];
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

                            <!-- Program -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="programm_id" class="form-label">
                                        <?php echo e(__('messages.program')); ?> <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control <?php $__errorArgs = ['programm_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="programm_id"
                                            name="programm_id"
                                            required>
                                        <option value=""><?php echo e(__('messages.select_program')); ?></option>
                                        <?php $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($program->id); ?>"
                                                    data-ctg-key="<?php echo e($program->ctg_key); ?>"
                                                    <?php echo e(old('programm_id', $subject->programm_id) == $program->id ? 'selected' : ''); ?>>
                                                <?php echo e(app()->getLocale() == 'ar' ? $program->name_ar : ($program->name_en ?? $program->name_ar)); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['programm_id'];
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

                            <!-- Grade -->
                            <?php
                                $program = $programs->firstWhere('id', $subject->programm_id);
                                $showGrade = $program && in_array($program->ctg_key, ['tawjihi-and-secondary-program', 'elementary-grades-program']);
                            ?>
                            <div class="col-md-6" id="gradeSection" style="<?php echo e($showGrade ? '' : 'display: none;'); ?>">
                                <div class="form-group mb-3">
                                    <label for="grade_id" class="form-label">
                                        <?php echo e(__('messages.grade')); ?> <span class="text-danger grade-required" style="<?php echo e($showGrade ? '' : 'display: none;'); ?>">*</span>
                                    </label>
                                    <select class="form-control <?php $__errorArgs = ['grade_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="grade_id"
                                            name="grade_id"
                                            <?php echo e($showGrade ? 'required' : ''); ?>>
                                        <option value=""><?php echo e(__('messages.select_grade')); ?></option>
                                        <?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($grade->id); ?>"
                                                    data-ctg-key="<?php echo e($grade->ctg_key); ?>"
                                                    data-level="<?php echo e($grade->level); ?>"
                                                    <?php echo e(old('grade_id', $subject->grade_id) == $grade->id ? 'selected' : ''); ?>>
                                                <?php echo e(app()->getLocale() == 'ar' ? $grade->name_ar : ($grade->name_en ?? $grade->name_ar)); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['grade_id'];
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

                            <!-- Semester (for single semester selection) -->
                            <?php
                                $showSemester = $subject->semester_id && $subject->grade && $subject->grade->level == 'elementray_grade';
                            ?>
                            <div class="col-md-6" id="semesterSection" style="<?php echo e($showSemester ? '' : 'display: none;'); ?>">
                                <div class="form-group mb-3">
                                    <label for="semester_id" class="form-label">
                                        <?php echo e(__('messages.semester')); ?> <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control <?php $__errorArgs = ['semester_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="semester_id"
                                            name="semester_id">
                                        <option value=""><?php echo e(__('messages.select_semester')); ?></option>
                                        <?php if($showSemester && count($semesters) > 0): ?>
                                            <?php $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $semester): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($semester->id); ?>"
                                                        <?php echo e(old('semester_id', $subject->semester_id) == $semester->id ? 'selected' : ''); ?>>
                                                    <?php echo e(app()->getLocale() == 'ar' ? $semester->name_ar : ($semester->name_en ?? $semester->name_ar)); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </select>
                                    <?php $__errorArgs = ['semester_id'];
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

                            <!-- Tawjihi First Year Options -->
                            <?php
                                $showTawjihiFirstYear = $subject->program && $subject->program->ctg_key == 'tawjihi-and-secondary-program'
                                    && $subject->grade && $subject->grade->ctg_key == 'first_year';
                                $existingGradeRelation = null;
                                if ($showTawjihiFirstYear) {
                                    $existingGradeRelation = $subject->categories()
                                        ->wherePivot('pivot_level', 'grade')
                                        ->first();
                                }
                            ?>
                            <div class="col-12" id="tawjihiFirstYearSection" style="<?php echo e($showTawjihiFirstYear ? '' : 'display: none;'); ?>">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="is_optional_single" class="form-label">
                                                <?php echo e(__('messages.is_optional')); ?> <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-control <?php $__errorArgs = ['is_optional_single'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                    id="is_optional_single"
                                                    name="is_optional_single"
                                                    <?php echo e($showTawjihiFirstYear ? 'required' : ''); ?>>
                                                <option value="0" <?php echo e(old('is_optional_single', $existingGradeRelation ? $existingGradeRelation->pivot->is_optional : 0) == 0 ? 'selected' : ''); ?>>
                                                    <?php echo e(__('messages.no')); ?>

                                                </option>
                                                <option value="1" <?php echo e(old('is_optional_single', $existingGradeRelation ? $existingGradeRelation->pivot->is_optional : 0) == 1 ? 'selected' : ''); ?>>
                                                    <?php echo e(__('messages.yes')); ?>

                                                </option>
                                            </select>
                                            <?php $__errorArgs = ['is_optional_single'];
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
                                            <label for="is_ministry_single" class="form-label">
                                                <?php echo e(__('messages.is_ministry')); ?> <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-control <?php $__errorArgs = ['is_ministry_single'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                    id="is_ministry_single"
                                                    name="is_ministry_single"
                                                    <?php echo e($showTawjihiFirstYear ? 'required' : ''); ?>>
                                                <option value="1" <?php echo e(old('is_ministry_single', $existingGradeRelation ? $existingGradeRelation->pivot->is_ministry : 1) == 1 ? 'selected' : ''); ?>>
                                                    <?php echo e(__('messages.yes')); ?>

                                                </option>
                                                <option value="0" <?php echo e(old('is_ministry_single', $existingGradeRelation ? $existingGradeRelation->pivot->is_ministry : 1) == 0 ? 'selected' : ''); ?>>
                                                    <?php echo e(__('messages.no')); ?>

                                                </option>
                                            </select>
                                            <?php $__errorArgs = ['is_ministry_single'];
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

                            <!-- Fields Selection (for Tawjihi last year) -->
                            <?php
                                $showFieldsSelection = $subject->grade && $subject->grade->ctg_key == 'last_year' && count($fields) > 0;
                            ?>
                            <div class="col-12" id="fieldsSelectionSection" style="<?php echo e($showFieldsSelection ? '' : 'display: none;'); ?>">

                                <!-- field_type_select -->
                                <div class="col-md-6 p-0">
                                    <div class="form-group mb-3">
                                        <label for="field_type_id" class="form-label">
                                            <?php echo e(__('messages.field_type')); ?> <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control <?php $__errorArgs = ['field_type_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                id="field_type_id"
                                                name="field_type_id">
                                            <option value=""><?php echo e(__('messages.select_field_type')); ?></option>
                                            <?php $__currentLoopData = $fieldTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fieldType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($fieldType->id); ?>"
                                                        <?php echo e(old('field_type_id', $subject->field_type_id) == $fieldType->id ? 'selected' : ''); ?>>
                                                    <?php echo e(app()->getLocale() == 'ar' ? $fieldType->name_ar : ($fieldType->name_en ?? $fieldType->name_ar)); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <?php $__errorArgs = ['field_type_id'];
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


                                <div class="form-group mb-3">
                                    <label class="form-label">
                                        <?php echo e(__('messages.select_fields')); ?> <span class="text-danger">*</span>
                                    </label>
                                    <div id="fieldsTable" class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th><?php echo e(__('messages.field')); ?></th>
                                                    <th><?php echo e(__('messages.add_to_field')); ?></th>
                                                    <th><?php echo e(__('messages.is_optional')); ?></th>
                                                    <th><?php echo e(__('messages.is_ministry')); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody id="fieldsTableBody">
                                                <?php if($showFieldsSelection): ?>
                                                    <?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php
                                                            $existingField = $existingFields->get($field->id);
                                                            $isChecked  = $existingField !== null;
                                                            $isOptional = $existingField ? $existingField->pivot->is_optional : false;
                                                            $isMinistry = $existingField ? $existingField->pivot->is_ministry : true;
                                                        ?>
                                                        <?php dd($existingField); ?>
                                                        <tr>
                                                            <td>
                                                                <?php echo e(app()->getLocale() == 'ar' ? $field->name_ar : ($field->name_en ?? $field->name_ar)); ?>

                                                            </td>
                                                            <td>
                                                                <div class="form-check">
                                                                    <input type="checkbox"
                                                                           class="form-check-input field-checkbox"
                                                                           name="field_categories[]"
                                                                           value="<?php echo e($field->id); ?>"
                                                                           id="field_<?php echo e($field->id); ?>"
                                                                           <?php echo e($isChecked ? 'checked' : ''); ?>

                                                                           onchange="toggleFieldSelects(<?php echo e($field->id); ?>)">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <select class="form-control form-control-sm"
                                                                        name="is_optional[<?php echo e($field->id); ?>]"
                                                                        id="is_optional_<?php echo e($field->id); ?>"
                                                                        <?php echo e(!$isChecked ? 'disabled' : ''); ?>>
                                                                    <option value="0" <?php echo e(!$isOptional ? 'selected' : ''); ?>><?php echo e(__('messages.no')); ?></option>
                                                                    <option value="1" <?php echo e($isOptional ? 'selected' : ''); ?>><?php echo e(__('messages.yes')); ?></option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select class="form-control form-control-sm"
                                                                        name="is_ministry[<?php echo e($field->id); ?>]"
                                                                        id="is_ministry_<?php echo e($field->id); ?>"
                                                                        <?php echo e(!$isChecked ? 'disabled' : ''); ?>>
                                                                    <option value="0" <?php echo e(!$isMinistry ? 'selected' : ''); ?>><?php echo e(__('messages.no')); ?></option>
                                                                    <option value="1" <?php echo e($isMinistry ? 'selected' : ''); ?>><?php echo e(__('messages.yes')); ?></option>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <?php $__errorArgs = ['field_categories'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
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
                            <a href="<?php echo e(route('subjects.index')); ?>" class="btn btn-secondary">
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

<?php $__env->startPush('scripts'); ?>
 <script>
     <?php echo file_get_contents(resource_path('views/admin/subjects/subjects-js.js')); ?>

    // Helper function for edit page
    function toggleFieldSelects(fieldId) {
        const checkbox = document.getElementById('field_' + fieldId);
        const optionalSelect = document.getElementById('is_optional_' + fieldId);
        const ministrySelect = document.getElementById('is_ministry_' + fieldId);

        if (checkbox && optionalSelect && ministrySelect) {
            optionalSelect.disabled = !checkbox.checked;
            ministrySelect.disabled = !checkbox.checked;
        }
    }

    // Initialize the SubjectFormManager for edit
    document.addEventListener('DOMContentLoaded', function() {
        const formManager = new SubjectFormManager({
            formId: 'subjectForm',
            routes: {
                getGrades: '<?php echo e(route("admin.subjects.getGrades")); ?>',
                getSemesters: '<?php echo e(route("admin.subjects.getSemesters")); ?>',
                getFields: '<?php echo e(route("admin.subjects.getFields")); ?>'
            },
            translations: {
                selectGrade: '<?php echo e(__("messages.select_grade")); ?>',
                selectSemester: '<?php echo e(__("messages.select_semester")); ?>',
                selectField: '<?php echo e(__("messages.select_field")); ?>',
                yes: '<?php echo e(__("messages.yes")); ?>',
                no: '<?php echo e(__("messages.no")); ?>'
            },
            isEditMode: true,
            existingData: {
                subjectId: <?php echo e($subject->id ?? 'null'); ?>,
                programId: <?php echo e($subject->programm_id ?? 'null'); ?>,
                gradeId: <?php echo e($subject->grade_id ?? 'null'); ?>,
                semesterId: <?php echo e($subject->semester_id ?? 'null'); ?>

            }
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/admin/subjects/edit.blade.php ENDPATH**/ ?>