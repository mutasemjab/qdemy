    <div class="ud-panel" id="results">
      <div class="ud-title"><?php echo e(translate_lang('my exam results')); ?></div>
      <div class="ud-results">
        <?php $__currentLoopData = $userExamsResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <a href="<?php echo e(route('exam',['exam'=>$result->exam->id,'slug'=>$result->exam->slug])); ?>" class="ud-res">
            <b><?php echo e($result->exam->title); ?></b>
            <span class="ud-res-score"><?php echo e($result->score); ?>/<?php echo e($result->exam->total_grade); ?></span>
          </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    </div><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/panel/student/results.blade.php ENDPATH**/ ?>