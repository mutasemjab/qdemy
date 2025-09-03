<?php $__env->startSection('title', __('messages.subject_details')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Main Details Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <?php echo e(__('messages.subject_details')); ?>: <?php echo e($subject->name_ar); ?>

                    </h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('subjects.index')); ?>" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.back')); ?>

                        </a>
                        <a href="<?php echo e(route('subjects.edit', $subject->id)); ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> <?php echo e(__('messages.edit')); ?>

                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-6">
                            <h5 class="border-bottom pb-2 mb-3"><?php echo e(__('messages.basic_information')); ?></h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%"><?php echo e(__('messages.name_ar')); ?>:</th>
                                    <td dir="rtl"><?php echo e($subject->name_ar); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.name_en')); ?>:</th>
                                    <td><?php echo e($subject->name_en ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.description_ar')); ?>:</th>
                                    <td dir="rtl"><?php echo e($subject->description_ar ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.description_en')); ?>:</th>
                                    <td><?php echo e($subject->description_en ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.icon')); ?>:</th>
                                    <td>
                                        <?php if($subject->icon): ?>
                                            <i class="<?php echo e($subject->icon); ?>"></i> <?php echo e($subject->icon); ?>

                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.color')); ?>:</th>
                                    <td>
                                        <?php if($subject->color): ?>
                                            <span class="badge" style="background-color: <?php echo e($subject->color); ?>; color: white;">
                                                <?php echo e($subject->color); ?>

                                            </span>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.sort_order')); ?>:</th>
                                    <td><?php echo e($subject->sort_order); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.status')); ?>:</th>
                                    <td>
                                        <?php if($subject->is_active): ?>
                                            <span class="badge badge-success"><?php echo e(__('messages.active')); ?></span>
                                        <?php else: ?>
                                            <span class="badge badge-danger"><?php echo e(__('messages.inactive')); ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- Category Relations -->
                        <div class="col-md-6">
                            <h5 class="border-bottom pb-2 mb-3"><?php echo e(__('messages.category_relations')); ?></h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%"><?php echo e(__('messages.field_type')); ?>:</th>
                                    <td>
                                        <?php if($subject->fieldType): ?>
                                            <span class="badge badge-dark">
                                                <?php echo e(app()->getLocale() == 'ar' ? $subject->fieldType->name_ar : ($subject->fieldType->name_en ?? $subject->fieldType->name_ar)); ?>

                                            </span>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.program')); ?>:</th>
                                    <td>
                                        <?php if($subject->program): ?>
                                            <span class="badge badge-info">
                                                <?php echo e(app()->getLocale() == 'ar' ? $subject->program->name_ar : ($subject->program->name_en ?? $subject->program->name_ar)); ?>

                                            </span>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.grade')); ?>:</th>
                                    <td>
                                        <?php if($subject->grade): ?>
                                            <span class="badge badge-secondary">
                                                <?php echo e(app()->getLocale() == 'ar' ? $subject->grade->name_ar : ($subject->grade->name_en ?? $subject->grade->name_ar)); ?>

                                            </span>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.semester')); ?>:</th>
                                    <td>
                                        <?php if($subject->semester): ?>
                                            <span class="badge badge-warning">
                                                <?php echo e(app()->getLocale() == 'ar' ? $subject->semester->name_ar : ($subject->semester->name_en ?? $subject->semester->name_ar)); ?>

                                            </span>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>

                            <!-- Timestamps -->
                            <h5 class="border-bottom pb-2 mb-3 mt-4"><?php echo e(__('messages.timestamps')); ?></h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%"><?php echo e(__('messages.created_at')); ?>:</th>
                                    <td><?php echo e($subject->created_at->format('Y-m-d H:i:s')); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.updated_at')); ?>:</th>
                                    <td><?php echo e($subject->updated_at->format('Y-m-d H:i:s')); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Fields (for Tawjihi last year) -->
            <?php if($relatedFields->count() > 0): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title"><?php echo e(__('messages.related_fields')); ?></h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('messages.field')); ?></th>
                                    <th><?php echo e(__('messages.is_optional')); ?></th>
                                    <th><?php echo e(__('messages.is_ministry')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $relatedFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <?php echo e(app()->getLocale() == 'ar' ? $field->name_ar : ($field->name_en ?? $field->name_ar)); ?>

                                    </td>
                                    <td>
                                        <?php if($field->pivot->is_optional): ?>
                                            <span class="badge badge-warning"><?php echo e(__('messages.yes')); ?></span>
                                        <?php else: ?>
                                            <span class="badge badge-success"><?php echo e(__('messages.no')); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($field->pivot->is_ministry): ?>
                                            <span class="badge badge-success"><?php echo e(__('messages.yes')); ?></span>
                                        <?php else: ?>
                                            <span class="badge badge-danger"><?php echo e(__('messages.no')); ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Related Courses -->
            <?php if($subject->courses->count() > 0): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <?php echo e(__('messages.related_courses')); ?>

                        <span class="badge badge-primary"><?php echo e($subject->courses->count()); ?></span>
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo e(__('messages.course_name')); ?></th>
                                    <th><?php echo e(__('messages.teacher')); ?></th>
                                    <th><?php echo e(__('messages.price')); ?></th>
                                    <th><?php echo e(__('messages.students_count')); ?></th>
                                    <th><?php echo e(__('messages.actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $subject->courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($loop->iteration); ?></td>
                                    <td>
                                        <?php echo e(app()->getLocale() == 'ar' ? $course->title_ar : ($course->title_en ?? $course->title_ar)); ?>

                                    </td>
                                    <td><?php echo e($course->teacher->name ?? '-'); ?></td>
                                    <td><?php echo e($course->selling_price); ?> <?php echo e(__('messages.currency')); ?></td>
                                    <td>
                                        <span class="badge badge-info"><?php echo e($course->students_count); ?></span>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('courses.show', $course->id)); ?>"
                                           class="btn btn-sm btn-info"
                                           title="<?php echo e(__('messages.view')); ?>">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="card mt-3">
                <div class="card-body text-center">
                    <p class="mb-0"><?php echo e(__('messages.no_courses_for_this_subject')); ?></p>
                </div>
            </div>
            <?php endif; ?>

            <!-- Action Buttons -->
            <div class="card mt-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <a href="<?php echo e(route('subjects.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.back_to_list')); ?>

                        </a>
                        <div>
                            <a href="<?php echo e(route('subjects.edit', $subject->id)); ?>" class="btn btn-primary">
                                <i class="fas fa-edit"></i> <?php echo e(__('messages.edit')); ?>

                            </a>
                            <form action="<?php echo e(route('subjects.destroy', $subject->id)); ?>"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('<?php echo e(__('messages.confirm_delete')); ?>');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> <?php echo e(__('messages.delete')); ?>

                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/admin/subjects/show.blade.php ENDPATH**/ ?>