<?php $__env->startSection('title','E-Exam'); ?>

<?php $__env->startSection('content'); ?>
<section class="examx-page">

    <div class="universities-header-wrapper">
        <div class="universities-header">
            <h2><?php echo e(translate_lang('e-exams')); ?></h2>
        </div>
    </div>

    <div class="examx-filters">
        <?php echo $__env->make('web.alert-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <form action='<?php echo e(route("e-exam")); ?>' method='get' id="filterForm">
            <div class="examx-row">

                <div class="examx-dropdown">
                    <select class="examx-pill" name="programm_id" id="programm_id">
                        <option value=""><?php echo e(translate_lang('select_program')); ?></option>
                        <?php $__currentLoopData = $programms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $programm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($programm->id); ?>"
                                    data-ctg-key="<?php echo e($programm->ctg_key); ?>"
                                    <?php echo e(request('programm_id') == $programm->id ? 'selected' : ''); ?>>
                                <?php echo e($programm->localized_name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="examx-dropdown" id="gradeSection" style="">
                    <select class="examx-pill" name="grade_id" id="grade_id">
                        <option value=""><?php echo e(translate_lang('select_grade')); ?></option>
                        <?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($grade->id); ?>"
                                    <?php echo e(request('grade_id') == $grade->id ? 'selected' : ''); ?>>
                                <?php echo e($grade->localized_name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="examx-dropdown" id="subjectSection" style="">
                    <select class="examx-pill" name="subject_id" id="subject_id">
                        <option value=""><?php echo e(translate_lang('select_subject')); ?></option>
                        <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($subject->id); ?>"
                                    <?php echo e(request('subject_id') == $subject->id ? 'selected' : ''); ?>>
                                <?php echo e($subject->localized_name); ?>

                                <?php if($subject->semester): ?> - <?php echo e($subject->semester->localized_name); ?> <?php endif; ?>
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

            </div>

            <div class="examx-search">
                <input type="text" placeholder="<?php echo e(translate_lang('search')); ?>" name='search' value="<?php echo e(request('search')); ?>">
                <button type="submit"><?php echo e(__('messages.search')); ?>

                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </form>
    </div>

    <div class="examx-grid">
        <?php $__empty_1 = true; $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="examx-card">
            <div class="examx-content">
                <div class="examx-line">
                    <b><?php echo e(translate_lang('subject')); ?></b>
                    <?php echo e($exam->subject ? $exam->subject->localized_name : '-'); ?>

                </div>
                <a href="#" class="examx-link"><?php echo e($exam->title); ?></a>
                <div class="examx-meta">
                    <div>
                        <span><?php echo e(translate_lang('exam_duration')); ?></span>
                        <strong><?php echo e($exam->duration_minutes); ?> <?php echo e(translate_lang('minute')); ?></strong>
                    </div>
                    <div>
                        <span><?php echo e(translate_lang('question_count')); ?>:</span>
                        <strong><?php echo e($exam->questions?->count()); ?> <?php echo e(translate_lang('question')); ?></strong>
                    </div>
                </div>
                <?php if($exam->can_add_attempt()): ?>
                <a href="<?php echo e(route('exam',['exam'=>$exam->id,'slug'=>$exam->slug])); ?>" class="examx-btn">
                    <?php echo e(translate_lang('start_exam')); ?>

                </a>
                <?php elseif($exam->current_user_attempt()): ?>
                <a href="<?php echo e(route('exam',['exam'=>$exam->id,'slug'=>$exam->slug])); ?>" class="examx-btn">
                    <?php echo e(translate_lang('continue')); ?>

                </a>
                <?php elseif($exam->result_attempt()): ?>
                <a href="<?php echo e(route('exam.results',$exam->id)); ?>" class="examx-btn">
                    <?php echo e(translate_lang('result')); ?>

                </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12 text-center">
            <p><?php echo e(translate_lang('no_exams_found')); ?></p>
        </div>
        <?php endif; ?>
    </div>

    <?php echo e($exams?->links('pagination::custom-bootstrap-5') ?? ''); ?>


</section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const programmSelect = document.getElementById('programm_id');
    const gradeSection = document.getElementById('gradeSection');
    const gradeSelect = document.getElementById('grade_id');
    const subjectSection = document.getElementById('subjectSection');
    const subjectSelect = document.getElementById('subject_id');
    const form = document.getElementById('filterForm');

    // Auto submit form on filter change
    [programmSelect, gradeSelect, subjectSelect].forEach(element => {
        if (element) {
            element.addEventListener('change', function() {
                // Clear dependent filters
                if (element === programmSelect) {
                    gradeSelect.value = '';
                    subjectSelect.value = '';
                } else if (element === gradeSelect) {
                    subjectSelect.value = '';
                }
                form.submit();
            });
        }
    });

    // Handle program change for dynamic grade loading
    programmSelect.addEventListener('change', async function() {
        const programId = this.value;
        const selectedOption = this.selectedOptions[0];

        if (!programId) {
            gradeSection.style.display = 'none';
            subjectSection.style.display = 'none';
            return;
        }

        const ctgKey = selectedOption.dataset.ctgKey;

        // Check if program needs grades
        if (['tawjihi-and-secondary-program', 'elementary-grades-program'].includes(ctgKey)) {
            // Program needs grades - grades will be loaded via form submit
            gradeSection.style.display = 'block';
        } else {
            // Program doesn't need grades
            gradeSection.style.display = 'none';
            gradeSelect.innerHTML = '<option value=""><?php echo e(translate_lang("select_grade")); ?></option>';
        }
    });

    // Show/hide sections based on initial state
    if (programmSelect.value) {
        const selectedOption = programmSelect.selectedOptions[0];
        const ctgKey = selectedOption.dataset.ctgKey;

        if (!['tawjihi-and-secondary-program', 'elementary-grades-program'].includes(ctgKey)) {
            if (document.querySelectorAll('#subject_id option').length > 1) {
                subjectSection.style.display = 'block';
            }
        }
    }

    if (gradeSelect.value || document.querySelectorAll('#grade_id option').length > 1) {
        gradeSection.style.display = 'block';
    }

    if (document.querySelectorAll('#subject_id option').length > 1) {
        subjectSection.style.display = 'block';
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/web/exam/e-exam.blade.php ENDPATH**/ ?>