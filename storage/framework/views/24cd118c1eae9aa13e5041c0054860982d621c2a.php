

<?php $__env->startSection('title', __('messages.subjects')); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php echo e(__('messages.subjects_list')); ?></h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('subjects.create')); ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> <?php echo e(__('messages.add_subject')); ?>

                        </a>
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="card-body pb-0">
                    <form method="GET" action="<?php echo e(route('subjects.index')); ?>" class="mb-3">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="text"
                                           name="search"
                                           class="form-control"
                                           placeholder="<?php echo e(__('messages.search_by_name')); ?>"
                                           value="<?php echo e(request('search')); ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select name="program_id" id="filter_program_id" class="form-control">
                                        <option value=""><?php echo e(__('messages.all_programs')); ?></option>
                                        <?php $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($program->id); ?>"
                                                    data-ctg-key="<?php echo e($program->ctg_key); ?>"
                                                    <?php echo e(request('program_id') == $program->id ? 'selected' : ''); ?>>
                                                <?php echo e(app()->getLocale() == 'ar' ? $program->name_ar : ($program->name_en ?? $program->name_ar)); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2" id="gradeFilterSection" style="<?php echo e(count($grades) > 0 ? '' : 'display: none;'); ?>">
                                <div class="form-group">
                                    <select name="grade_id" id="filter_grade_id" class="form-control">
                                        <option value=""><?php echo e(__('messages.all_grades')); ?></option>
                                        <?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($grade->id); ?>"
                                                    <?php echo e(request('grade_id') == $grade->id ? 'selected' : ''); ?>>
                                                <?php echo e(app()->getLocale() == 'ar' ? $grade->name_ar : ($grade->name_en ?? $grade->name_ar)); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select name="status" class="form-control">
                                        <option value=""><?php echo e(__('messages.all_status')); ?></option>
                                        <option value="1" <?php echo e(request('status') == '1' ? 'selected' : ''); ?>><?php echo e(__('messages.active')); ?></option>
                                        <option value="0" <?php echo e(request('status') == '0' ? 'selected' : ''); ?>><?php echo e(__('messages.inactive')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search"></i> <?php echo e(__('messages.search')); ?>

                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo e(session('success')); ?>

                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo e(session('error')); ?>

                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="50">#</th>
                                    <th><?php echo e(__('messages.name')); ?></th>
                                    <th><?php echo e(__('messages.semester')); ?></th>
                                    <th width="100"><?php echo e(__('messages.courses_count')); ?></th>
                                    <th width="100"><?php echo e(__('messages.status')); ?></th>
                                    <th width="100"><?php echo e(__('messages.order')); ?></th>
                                    <th width="200"><?php echo e(__('messages.actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration + (($subjects->currentPage() - 1) * $subjects->perPage())); ?></td>
                                        <td dir="rtl"><?php echo e($subject->localized_name); ?> <br> <?php echo e($subject->grade?->breadcrumb); ?></td>
                                        <td>
                                            <?php if($subject->semester): ?>
                                                <span class="badge badge-warning">
                                                    <?php echo e($subject->semester->localized_name); ?>

                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-info"><?php echo e($subject->courses->count()); ?></span>
                                        </td>
                                        <td>
                                            <form action="<?php echo e(route('subjects.toggleStatus', $subject->id)); ?>" method="POST" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-sm <?php echo e($subject->is_active ? 'btn-success' : 'btn-danger'); ?>">
                                                    <?php if($subject->is_active): ?>
                                                        <i class="fas fa-check"></i> <?php echo e(__('messages.active')); ?>

                                                    <?php else: ?>
                                                        <i class="fas fa-times"></i> <?php echo e(__('messages.inactive')); ?>

                                                    <?php endif; ?>
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <form action="<?php echo e(route('subjects.moveUp', $subject->id)); ?>" method="POST" class="d-inline">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="btn btn-sm btn-secondary" title="<?php echo e(__('messages.move_up')); ?>">
                                                        <i class="fas fa-arrow-up"></i>
                                                    </button>
                                                </form>
                                                <form action="<?php echo e(route('subjects.moveDown', $subject->id)); ?>" method="POST" class="d-inline">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="btn btn-sm btn-secondary" title="<?php echo e(__('messages.move_down')); ?>">
                                                        <i class="fas fa-arrow-down"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo e(route('subjects.edit', $subject->id)); ?>"
                                                   class="btn btn-sm btn-primary"
                                                   title="<?php echo e(__('messages.edit')); ?>">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="<?php echo e(route('subjects.show', $subject->id)); ?>"
                                                class="btn btn-sm btn-info"
                                                title="<?php echo e(__('messages.view')); ?>">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <form action="<?php echo e(route('subjects.destroy', $subject->id)); ?>"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('<?php echo e(__('messages.confirm_delete')); ?>');">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit"
                                                            class="btn btn-sm btn-danger"
                                                            title="<?php echo e(__('messages.delete')); ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <?php echo e(__('messages.no_subjects_found')); ?>

                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        <?php echo e($subjects->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const programSelect = document.getElementById('filter_program_id');
    const gradeSection = document.getElementById('gradeFilterSection');
    const gradeSelect = document.getElementById('filter_grade_id');

    programSelect.addEventListener('change', async function() {
        const programId = this.value;
        const selectedOption = this.selectedOptions[0];

        if (!programId) {
            gradeSection.style.display = 'none';
            gradeSelect.innerHTML = '<option value=""><?php echo e(__("messages.all_grades")); ?></option>';
            return;
        }

        const ctgKey = selectedOption.dataset.ctgKey;

        if (['tawjihi-and-secondary-program', 'elementary-grades-program'].includes(ctgKey)) {
            // Load grades via AJAX
            try {
                const formData = new FormData();
                formData.append('program_id', programId);
                formData.append('_token', '<?php echo e(csrf_token()); ?>');

                const response = await fetch('<?php echo e(route("admin.subjects.getGrades")); ?>', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const grades = await response.json();

                if (!grades.no_grades && grades.length > 0) {
                    gradeSection.style.display = 'block';
                    gradeSelect.innerHTML = '<option value=""><?php echo e(__("messages.all_grades")); ?></option>';

                    grades.forEach(grade => {
                        const option = document.createElement('option');
                        option.value = grade.id;
                        option.textContent = '<?php echo e(app()->getLocale()); ?>' === 'ar' ?
                            grade.name_ar : (grade.name_en || grade.name_ar);
                        gradeSelect.appendChild(option);
                    });
                } else {
                    gradeSection.style.display = 'none';
                }
            } catch (error) {
                console.error('Error loading grades:', error);
                gradeSection.style.display = 'none';
            }
        } else {
            gradeSection.style.display = 'none';
        }
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\qdemy\resources\views/admin/subjects/index.blade.php ENDPATH**/ ?>