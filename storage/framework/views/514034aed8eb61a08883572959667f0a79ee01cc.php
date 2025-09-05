<?php $__env->startSection('title', $programm->localized_name); ?>

<?php $__env->startSection('content'); ?>
<section class="grade-page">
    <!-- International -->
    <a href="#" class="gprogram-card gprogram-card-mixed gprogram-card-main ">
        <span><?php echo e($programm->localized_name); ?></span>
    </a>

    <!-- Subjects -->
        <div class="subjects-grid semester-content"  id="_semester_content">
            <?php if($subjects && $subjects->count()): ?>
                <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('subject',['subject'=>$subject->id,'slug'=>$subject->slug])); ?>" class="subject-card dark">
                    <span><?php echo e($subject->localized_name); ?></span>
                    <i class="<?php echo e($subject->icon); ?>"></i>
                    <!-- <img data-src="<?php echo e(asset('assets_front/images/icon-math.png')); ?>" alt="<?php echo e($subject->localized_name); ?>" class="subject-icon"> -->
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/web/univerisity-subjects.blade.php ENDPATH**/ ?>