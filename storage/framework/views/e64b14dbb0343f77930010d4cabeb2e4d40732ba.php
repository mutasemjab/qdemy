

<?php $__env->startSection('title', __('messages.manage_sections_contents')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title"><?php echo e(__('messages.manage_sections_contents')); ?></h3>
                            <p class="text-muted mb-0"><?php echo e(__('messages.course')); ?>: <?php echo e($course->title_en); ?></p>
                        </div>
                        <div>
                            <a href="<?php echo e(route('courses.index')); ?>" class="btn btn-secondary me-2">
                                <i class="fas fa-arrow-left"></i> <?php echo e(__('messages.back_to_courses')); ?>

                            </a>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('course-add')): ?>
                                <div class="btn-group">
                                    <a href="<?php echo e(route('courses.sections.create', $course)); ?>" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> <?php echo e(__('messages.add_section')); ?>

                                    </a>
                                    <a href="<?php echo e(route('courses.contents.create', $course)); ?>" class="btn btn-success">
                                        <i class="fas fa-plus"></i> <?php echo e(__('messages.add_content')); ?>

                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Course Sections with Hierarchical Structure -->
                    <?php
                        $parentSections = $course->sections()->whereNull('parent_id')->get();
                    ?>
                    
                    <?php if($parentSections->count() > 0): ?>
                        <div class="mb-4">
                            <h4><?php echo e(__('messages.course_sections')); ?></h4>
                            <div class="accordion" id="sectionsAccordion">
                                <?php $__currentLoopData = $parentSections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php echo $__env->make('admin.courses.partials.section-item', [
                                        'section' => $section,
                                        'course' => $course,
                                        'level' => 0,
                                        'index' => $index,
                                        'parentAccordion' => 'sectionsAccordion'
                                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Direct Contents (without sections) -->
                    <?php if($directContents->count() > 0): ?>
                        <div class="mb-4">
                            <h4><?php echo e(__('messages.direct_contents')); ?></h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="25%"><?php echo e(__('messages.title')); ?></th>
                                            <th width="10%"><?php echo e(__('messages.type')); ?></th>
                                            <th width="10%"><?php echo e(__('messages.access')); ?></th>
                                            <th width="8%"><?php echo e(__('messages.order')); ?></th>
                                            <th width="20%"><?php echo e(__('messages.details')); ?></th>
                                            <th width="22%"><?php echo e(__('messages.actions')); ?></th>
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
                                                            <strong><?php echo e(__('messages.type')); ?>:</strong> <?php echo e(ucfirst($content->video_type ?? 'N/A')); ?><br>
                                                            <strong><?php echo e(__('messages.duration')); ?>:</strong> <?php echo e($content->video_duration ? gmdate('H:i:s', $content->video_duration) : 'N/A'); ?><br>
                                                            <?php if($content->video_url): ?>
                                                                <a href="<?php echo e($content->video_url); ?>" target="_blank" class="text-primary">
                                                                    <i class="fas fa-external-link-alt"></i> <?php echo e(__('messages.view_video')); ?>

                                                                </a>
                                                            <?php endif; ?>
                                                        </small>
                                                    <?php elseif($content->content_type === 'pdf'): ?>
                                                        <small>
                                                            <strong><?php echo e(__('messages.pdf_type')); ?>:</strong> <?php echo e(ucfirst($content->pdf_type ?? 'N/A')); ?><br>
                                                            <?php if($content->file_path): ?>
                                                                <a href="<?php echo e($content->file_url); ?>" target="_blank" class="text-primary">
                                                                    <i class="fas fa-download"></i> <?php echo e(__('messages.download_pdf')); ?>

                                                                </a>
                                                            <?php endif; ?>
                                                        </small>
                                                    <?php else: ?>
                                                        <span class="text-muted"><?php echo e(__('messages.no_additional_details')); ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('course-edit')): ?>
                                                            <a href="<?php echo e(route('courses.contents.edit', [$course, $content])); ?>" 
                                                               class="btn btn-sm btn-warning"
                                                               title="<?php echo e(__('messages.edit_content')); ?>">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('course-delete')): ?>
                                                            <form action="<?php echo e(route('courses.contents.destroy', [$course, $content])); ?>" 
                                                                  method="POST" 
                                                                  class="d-inline"
                                                                  onsubmit="return confirm('<?php echo e(__('messages.confirm_delete_content')); ?>')">
                                                                <?php echo csrf_field(); ?>
                                                                <?php echo method_field('DELETE'); ?>
                                                                <button type="submit" 
                                                                        class="btn btn-sm btn-danger"
                                                                        title="<?php echo e(__('messages.delete_content')); ?>">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if($parentSections->count() == 0 && $directContents->count() == 0): ?>
                        <div class="text-center py-5">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle fa-2x mb-2"></i>
                                <h5><?php echo e(__('messages.no_content_yet')); ?></h5>
                                <p class="mb-3"><?php echo e(__('messages.add_sections_and_contents')); ?></p>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('course-add')): ?>
                                    <div class="btn-group">
                                        <a href="<?php echo e(route('courses.sections.create', $course)); ?>" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> <?php echo e(__('messages.add_first_section')); ?>

                                        </a>
                                        <a href="<?php echo e(route('courses.contents.create', $course)); ?>" class="btn btn-success">
                                            <i class="fas fa-plus"></i> <?php echo e(__('messages.add_direct_content')); ?>

                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Keep only the first accordion item open by default
document.addEventListener('DOMContentLoaded', function() {
    const accordionItems = document.querySelectorAll('.accordion-collapse');
    accordionItems.forEach((item, index) => {
        if (index > 0) {
            item.classList.remove('show');
        }
    });
    
    const accordionButtons = document.querySelectorAll('.accordion-button');
    accordionButtons.forEach((button, index) => {
        if (index > 0) {
            button.classList.add('collapsed');
            button.setAttribute('aria-expanded', 'false');
        }
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\qdemy\resources\views/admin/courses/sections/index.blade.php ENDPATH**/ ?>