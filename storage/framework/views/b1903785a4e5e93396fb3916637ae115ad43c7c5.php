
<?php
    $uniqueId = 'section-' . $section->id . '-' . $level;
    $childSections = $section->children;
    $sectionContents = $section->contents()->orderBy('order')->get();
    $totalContents = $sectionContents->count() + $childSections->sum(function($child) {
        return $child->contents()->count();
    });
    // Set default values if not provided
    $index = $index ?? 0;
    $parentAccordion = $parentAccordion ?? 'sectionsAccordion';
?>

<div class="accordion-item <?php echo e($level > 0 ? 'ms-' . ($level * 3) : ''); ?>">
    <h2 class="accordion-header" id="heading<?php echo e($uniqueId); ?>">
        <button class="accordion-button <?php echo e($index > 0 ? 'collapsed' : ''); ?>" 
                type="button" 
                data-bs-toggle="collapse" 
                data-bs-target="#collapse<?php echo e($uniqueId); ?>" 
                aria-expanded="<?php echo e($index === 0 ? 'true' : 'false'); ?>" 
                aria-controls="collapse<?php echo e($uniqueId); ?>"
                style="<?php echo e($level > 0 ? 'background-color: ' . ($level % 2 === 1 ? '#f8f9fa' : '#e9ecef') . ';' : ''); ?>">
            
            <div class="d-flex justify-content-between align-items-center w-100 me-3">
                <div class="d-flex align-items-center">
                    
                    <?php if($level > 0): ?>
                        <span class="text-muted me-2">
                            <?php for($i = 0; $i < $level; $i++): ?>
                                <i class="fas fa-level-up-alt fa-rotate-90"></i>
                            <?php endfor; ?>
                        </span>
                    <?php endif; ?>
                    
                    
                    <div>
                        <strong class="<?php echo e($level > 0 ? 'text-primary' : ''); ?>">
                            <?php echo e($section->title_en); ?>

                        </strong>
                        <?php if($section->title_ar): ?>
                            <span class="ms-2 text-muted">- <?php echo e($section->title_ar); ?></span>
                        <?php endif; ?>
                        
                        
                        <?php if($level > 0): ?>
                            <small class="badge bg-info ms-2"><?php echo e(__('messages.subsection')); ?></small>
                        <?php elseif($childSections->count() > 0): ?>
                            <small class="badge bg-primary ms-2"><?php echo e($childSections->count()); ?> <?php echo e(__('messages.subsections')); ?></small>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="d-flex align-items-center">
                    
                    <span class="badge bg-success me-2">
                        <?php echo e($sectionContents->count()); ?> <?php echo e(__('messages.contents')); ?>

                    </span>
                    
                    <?php if($totalContents > $sectionContents->count()): ?>
                        <span class="badge bg-info me-2">
                            <?php echo e($totalContents); ?> <?php echo e(__('messages.total')); ?>

                        </span>
                    <?php endif; ?>
                    
                    
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('course-edit')): ?>
                        <a href="<?php echo e(route('courses.sections.edit', [$course, $section])); ?>" 
                           class="btn btn-sm btn-warning me-1"
                           onclick="event.stopPropagation();"
                           title="<?php echo e(__('messages.edit_section')); ?>">
                            <i class="fas fa-edit"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('course-delete')): ?>
                        <form action="<?php echo e(route('courses.sections.destroy', [$course, $section])); ?>" 
                              method="POST" 
                              class="d-inline"
                              onclick="event.stopPropagation();"
                              onsubmit="return confirm('<?php echo e(__('messages.confirm_delete_section')); ?>')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" 
                                    class="btn btn-sm btn-danger"
                                    title="<?php echo e(__('messages.delete_section')); ?>">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </button>
    </h2>
    
    <div id="collapse<?php echo e($uniqueId); ?>" 
         class="accordion-collapse collapse <?php echo e($index === 0 && $level === 0 ? 'show' : ''); ?>" 
         aria-labelledby="heading<?php echo e($uniqueId); ?>" 
         data-bs-parent="#<?php echo e($parentAccordion); ?>">
        <div class="accordion-body">
            
            
            <?php if($sectionContents->count() > 0): ?>
                <div class="mb-3">
                    <h6 class="text-primary">
                        <i class="fas fa-file-alt"></i> <?php echo e(__('messages.section_contents')); ?>

                    </h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead class="table-light">
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
                                <?php $__currentLoopData = $sectionContents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $content): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                            <?php elseif($content->content_type != 'video'): ?>
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
            <?php else: ?>
                <div class="alert alert-light mb-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> <?php echo e(__('messages.no_contents_in_section')); ?>

                    </small>
                </div>
            <?php endif; ?>

            
            <?php if($childSections->count() > 0): ?>
                <div class="mb-3">
                    <h6 class="text-info">
                        <i class="fas fa-sitemap"></i> <?php echo e(__('messages.subsections')); ?>

                    </h6>
                    <div class="accordion" id="subsectionsAccordion<?php echo e($section->id); ?>">
                        <?php $__currentLoopData = $childSections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $childIndex => $childSection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo $__env->make('admin.courses.partials.section-item', [
                                'section' => $childSection,
                                'course' => $course,
                                'level' => $level + 1,
                                'index' => $childIndex,
                                'parentAccordion' => 'subsectionsAccordion' . $section->id
                            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>

            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('course-add')): ?>
                <div class="border-top pt-3 mt-3">
                    <div class="btn-group btn-group-sm">
                        <a href="<?php echo e(route('courses.contents.create', $course)); ?>?section_id=<?php echo e($section->id); ?>" 
                           class="btn btn-outline-success">
                            <i class="fas fa-plus"></i> <?php echo e(__('messages.add_content_to_section')); ?>

                        </a>
                        <a href="<?php echo e(route('courses.sections.create', $course)); ?>?parent_id=<?php echo e($section->id); ?>" 
                           class="btn btn-outline-primary">
                            <i class="fas fa-plus"></i> <?php echo e(__('messages.add_subsection')); ?>

                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/admin/courses/partials/section-item.blade.php ENDPATH**/ ?>