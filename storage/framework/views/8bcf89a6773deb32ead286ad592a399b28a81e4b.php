<?php $__env->startSection('title','E-Exam'); ?>

<?php $__env->startSection('content'); ?>
<section class="examx-page">

    <div class="universities-header-wrapper">
        <div class="universities-header">
            <h2><?php echo e(__('messages.e-exams')); ?></h2>
        </div>
    </div>
    <div class="examx-filters">
    <?php echo $__env->make('web.alert-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <form action='<?php echo e(route("e-exam")); ?>' methog='get'>
        <?php echo csrf_field(); ?>
        <div class="examx-row">

            <div class="examx-dropdown">
                <select class="examx-pill" name="subject" id="subject_id">
                    <i class="fa-solid fa-caret-down"></i>
                    <option>اختر المادة</option>
                    <?php $__currentLoopData = $subjectUnderProgrammsGrades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option id='subject_<?php echo e($subject->id); ?>' data-grade-id="subject_<?php echo e($subject->id); ?>" value="<?php echo e($subject->id); ?>" <?php if(old("subject") == $subject->id): ?> selected <?php endif; ?>><?php echo e($subject->localized_name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="examx-dropdown">
                <select class="examx-pill" name="grade" id="grad_id">
                    <i class="fa-solid fa-caret-down"></i>
                    <option>اختر الصف</option>
                    <?php $__currentLoopData = $programmsGrades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option id='grade_<?php echo e($grade->id); ?>' data-grade-id="grade_<?php echo e($grade->id); ?>" value="<?php echo e($grade->id); ?>" <?php if(old("grade") == $grade->id): ?> selected <?php endif; ?> ><?php echo e($grade->localized_name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="examx-dropdown">
                <select class="examx-pill" name="semester" id="semester_id">
                    <i class="fa-solid fa-caret-down"></i>
                    <option>اختر الفصل</option>
                    <?php $__currentLoopData = $gradesSemesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $semester): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option id='semester_<?php echo e($semester->id); ?>' data-grade-id="semester_<?php echo e($semester->id); ?>" value="<?php echo e($semester->id); ?>" <?php if(old("semester") == $semester->id): ?> selected <?php endif; ?> ><?php echo e($semester->localized_name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

        </div>

        <div class="examx-search">
            <input type="text" placeholder="البحث" name='search' value="<?php echo e(old('search')); ?>">
            <i class="fa-solid fa-magnifying-glass"></i>
        </div>
    </form>

  <div class="examx-grid">
    <?php $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="examx-card">
      <div class="examx-content">
        <div class="examx-line"><b><?php echo e(__('messages.subject')); ?></b> <?php echo e($exam->course?->category->localized_name); ?></div>
        <a href="#" class="examx-link"><?php echo e($exam->course?->title); ?></a>
        <div class="examx-meta">
          <div><span><?php echo e(__('messages.exam_duration')); ?></span><strong><?php echo e($exam->duration_minutes); ?> <?php echo e(__('messages.minute')); ?></strong></div>
          <div><span><?php echo e(__('messages.question_count')); ?>:</span><strong><?php echo e($exam->questions?->count()); ?> <?php echo e(__('messages.question')); ?></strong></div>
        </div>
        <a href="<?php echo e(route('exam',['exam'=>$exam->id,'slug'=>$exam->slug])); ?>" class="examx-btn"><?php echo e(__('messages.start_exam')); ?></a>
      </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>
    <!-- <div class="pagination-wrapper"> -->
       <?php echo e($exams?->links('pagination::custom-bootstrap-5') ?? ''); ?>

   <!-- </div> -->

</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/user/exam/e-exam.blade.php ENDPATH**/ ?>
