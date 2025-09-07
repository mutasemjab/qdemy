    <div class="ud-panel" id="schedule">
      <div class="ud-title"><?php echo e(translate_lang('courses progress')); ?></div>
      <div class="ud-bars">
        <?php $__currentLoopData = $userCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="ud-bar">
            <div class="ud-bar-head"><b><?php echo e($course->title); ?></b>
            <?php if($course->subject): ?> <small> <?php echo e($course->subject->localized_name); ?> </small> <?php endif; ?>
            <?php if($course->subject?->semester): ?> <small> - <?php echo e($course->subject?->semester->localized_name); ?> </small> <?php endif; ?>
           </div>
           <?php if($course->calculateCourseProgress()): ?>
           <div class="ud-bar-track"><span style="width:<?php echo e($course->calculateCourseProgress()); ?>%"></span></div>
           <div class="ud-bar-foot">100%<b><?php echo e(number_format($course->calculateCourseProgress(), 1, '.', '')); ?>% 
               <!-- <?php echo e($course->calculateCourseProgress()); ?>% -->
           </b></div>
           <?php else: ?>
           <div class="ud-bar-track"><span style="width:0%"></span></div>
           <div class="ud-bar-foot">100%<b>0% 
           </b></div>
           <?php endif; ?>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    </div><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/panel/student/schedule.blade.php ENDPATH**/ ?>