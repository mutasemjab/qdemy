


<?php $__env->startSection('title', __('messages.course_details')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php echo e(__('messages.course_details')); ?></h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('courses.index')); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.back')); ?>

                        </a>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('course-edit')): ?>
                            <a href="<?php echo e(route('courses.edit', $course)); ?>" class="btn btn-warning">
                                <i class="fas fa-edit"></i> <?php echo e(__('messages.edit')); ?>

                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Course Image -->
                        <div class="col-md-4">
                            <div class="text-center mb-3">
                                <img src="<?php echo e($course->photo_url); ?>" 
                                     alt="<?php echo e($course->title); ?>" 
                                     class="img-fluid rounded"
                                     style="max-height: 300px; object-fit: cover;">
                            </div>
                        </div>

                        <!-- Course Information -->
                        <div class="col-md-8">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%"><?php echo e(__('messages.title_en')); ?>:</th>
                                    <td><?php echo e($course->title_en); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.title_ar')); ?>:</th>
                                    <td dir="rtl"><?php echo e($course->title_ar); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.description_en')); ?>:</th>
                                    <td><?php echo e($course->description_en); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.description_ar')); ?>:</th>
                                    <td dir="rtl"><?php echo e($course->description_ar); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.selling_price')); ?>:</th>
                                    <td>
                                        <span class="badge bg-success fs-6">JD <?php echo e(number_format($course->selling_price, 2)); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.teacher')); ?>:</th>
                                    <td>
                                        <?php if($course->teacher): ?>
                                            <span class="badge bg-info"><?php echo e($course->teacher->name); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted"><?php echo e(__('messages.no_teacher')); ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.Subject')); ?>:</th>
                                    <td>
                                        <?php if($course->subject): ?>
                                            <span class="badge bg-primary"><?php echo e($course->subject->localized_name); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted"><?php echo e(__('messages.No Subject')); ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.created_at')); ?>:</th>
                                    <td><?php echo e($course->created_at->format('Y-m-d H:i:s')); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('messages.updated_at')); ?>:</th>
                                    <td><?php echo e($course->updated_at->format('Y-m-d H:i:s')); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Course Sections -->
                    <?php if($course->sections->count() > 0): ?>
                        <div class="row mt-4">
                            <div class="col-12">
                                <h4><?php echo e(__('messages.course_sections')); ?></h4>
                                <div class="accordion" id="sectionsAccordion">
                                    <?php $__currentLoopData = $course->sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading<?php echo e($section->id); ?>">
                                                <button class="accordion-button collapsed" 
                                                        type="button" 
                                                        data-bs-toggle="collapse" 
                                                        data-bs-target="#collapse<?php echo e($section->id); ?>" 
                                                        aria-expanded="false" 
                                                        aria-controls="collapse<?php echo e($section->id); ?>">
                                                    <strong><?php echo e($section->title_en); ?></strong>
                                                    <?php if($section->title_ar): ?>
                                                        <span class="ms-2 text-muted">- <?php echo e($section->title_ar); ?></span>
                                                    <?php endif; ?>
                                                    <span class="badge bg-primary ms-auto me-2">
                                                        <?php echo e($section->contents->count()); ?> <?php echo e(__('messages.contents')); ?>

                                                    </span>
                                                </button>
                                            </h2>
                                            <div id="collapse<?php echo e($section->id); ?>" 
                                                 class="accordion-collapse collapse" 
                                                 aria-labelledby="heading<?php echo e($section->id); ?>" 
                                                 data-bs-parent="#sectionsAccordion">
                                                <div class="accordion-body">
                                                    <?php if($section->contents->count() > 0): ?>
                                                        <div class="table-responsive">
                                                            <table class="table table-sm">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th><?php echo e(__('messages.title')); ?></th>
                                                                        <th><?php echo e(__('messages.content_type')); ?></th>
                                                                        <th><?php echo e(__('messages.is_free')); ?></th>
                                                                        <th><?php echo e(__('messages.order')); ?></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php $__currentLoopData = $section->contents->sortBy('order'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $content): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <tr>
                                                                            <td><?php echo e($loop->iteration); ?></td>
                                                                            <td>
                                                                                <strong><?php echo e($content->title_en); ?></strong><br>
                                                                                <small class="text-muted"><?php echo e($content->title_ar); ?></small>
                                                                            </td>
                                                                            <td>
                                                                                <span class="badge bg-secondary">
                                                                                    <?php echo e(ucfirst($content->content_type)); ?>

                                                                                </span>
                                                                            </td>
                                                                            <td>
                                                                                <?php if($content->is_free == 1): ?>
                                                                                    <span class="badge bg-success"><?php echo e(__('messages.free')); ?></span>
                                                                                <?php else: ?>
                                                                                    <span class="badge bg-warning"><?php echo e(__('messages.paid')); ?></span>
                                                                                <?php endif; ?>
                                                                            </td>
                                                                            <td><?php echo e($content->order); ?></td>
                                                                        </tr>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    <?php else: ?>
                                                        <p class="text-muted mb-0"><?php echo e(__('messages.no_contents_in_section')); ?></p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Course Contents (without sections) -->
                    <?php
                        $directContents = $course->contents()->whereNull('section_id')->orderBy('order')->get();
                    ?>
                    <?php if($directContents->count() > 0): ?>
                        <div class="row mt-4">
                            <div class="col-12">
                                <h4><?php echo e(__('messages.direct_contents')); ?></h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th><?php echo e(__('messages.title')); ?></th>
                                                <th><?php echo e(__('messages.content_type')); ?></th>
                                                <th><?php echo e(__('messages.is_free')); ?></th>
                                                <th><?php echo e(__('messages.order')); ?></th>
                                                <th><?php echo e(__('messages.details')); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $directContents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $content): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($loop->iteration); ?></td>
                                                    <td>
                                                        <strong><?php echo e($content->title_en); ?></strong><br>
                                                        <small class="text-muted"><?php echo e($content->title_ar); ?></small>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-secondary">
                                                            <?php echo e(ucfirst($content->content_type)); ?>

                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php if($content->is_free == 1): ?>
                                                            <span class="badge bg-success"><?php echo e(__('messages.free')); ?></span>
                                                        <?php else: ?>
                                                            <span class="badge bg-warning"><?php echo e(__('messages.paid')); ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo e($content->order); ?></td>
                                                    <td>
                                                        <?php if($content->content_type === 'video'): ?>
                                                            <small>
                                                                <?php echo e(__('messages.type')); ?>: <?php echo e(ucfirst($content->video_type ?? 'N/A')); ?><br>
                                                                <?php echo e(__('messages.duration')); ?>: <?php echo e($content->video_duration ? gmdate('H:i:s', $content->video_duration) : 'N/A'); ?>

                                                            </small>
                                                        <?php elseif($content->content_type === 'pdf'): ?>
                                                            <small>
                                                                <?php echo e(__('messages.pdf_type')); ?>: <?php echo e(ucfirst($content->pdf_type ?? 'N/A')); ?>

                                                            </small>
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

                    <?php if($course->sections->count() == 0 && $directContents->count() == 0): ?>
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-info text-center">
                                    <i class="fas fa-info-circle fa-2x mb-2"></i>
                                    <h5><?php echo e(__('messages.no_content_yet')); ?></h5>
                                    <p class="mb-0"><?php echo e(__('messages.add_sections_and_contents')); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/admin/courses/show.blade.php ENDPATH**/ ?>