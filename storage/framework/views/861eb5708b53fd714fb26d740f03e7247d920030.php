<?php $__env->startSection('title', __('messages.International Program')); ?>

<?php $__env->startSection('content'); ?>
<section class="gprograms-page">
    <!-- International -->
    <a href="#" class="gprogram-card gprogram-card-mixed gprogram-card-main">
        <span><?php echo e(__('messages.International Program')); ?></span>
    </a>

    <?php if($programms && $programms->count()): ?>
    <?php $__currentLoopData = $programms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $programm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <a href="<?php echo e(route('international-programm',['programm'=>$programm->id,'slug'=>$programm->slug])); ?>" class="gprogram-card gprogram-card-american">
        <i class='flag <?php echo e($programm->icon); ?>'></i>
        <!-- <img data-src="<?php echo e($programm->photo_url); ?>" alt="American Flag"> -->
        <span><?php echo e($programm->localized_name); ?></span>
    </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/user/gprograms.blade.php ENDPATH**/ ?>