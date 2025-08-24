<?php $__env->startSection('title',$tawjihiLastYear->localized_name); ?>

<?php $__env->startSection('content'); ?>
<section class="f08-page">
    <div class="f08-head"><?php echo e($tawjihiLastYear->localized_name); ?></div>

    <?php if($tawjihiLastYearFields && $tawjihiLastYearFields->count()): ?>
    <?php $__currentLoopData = $tawjihiLastYearFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $tawjihiLastYearField): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="f08-section" style="background-image: url('<?php echo e(asset('images')); ?><?php echo e($index % 2 ? '/literature-box.png' : '/science-box.png'); ?>');">

        <h2 class="f08-title"><?php echo e($tawjihiLastYearField->localized_name); ?></h2>

            <?php $tawjihiLastYearFieldSubjects = CategoryRepository()->getDirectChilds($tawjihiLastYearField); ?>
            <?php if($tawjihiLastYearFieldSubjects && $tawjihiLastYearFieldSubjects->count()): ?>
            <div class="f08-items">
            <?php $__currentLoopData = $tawjihiLastYearFieldSubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tawjihiLastYearFieldSubject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('tawjihi-grade-field',['field'=>$tawjihiLastYearFieldSubject->id,'slug'=>$tawjihiLastYearFieldSubject->slug])); ?>" class="tj2009__item"
                    style="background-image:url('<?php echo e(asset('assets_front/images/it-icon-light.png')); ?>">
                    <span class='text-light'> <?php echo e($tawjihiLastYearFieldSubject->localized_name); ?> </span>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
           </div>
           <?php endif; ?>

    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
</section>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/user/tawjihi-last-year.blade.php ENDPATH**/ ?>