<?php $__env->startSection('title',__('getTawjihiProgrammGrades')); ?>
<?php $__env->startSection('content'); ?>
<section class="tawjihi-page">
    <a href="<?php echo e(route('tawjihi-first-year',['slug'=>$tawjihiFirstYear?->slug])); ?>" class="tawjihi-card" style="background-image: url('<?php echo e(asset('images/tawjihi-2009.png')); ?>');">
        <div class="tawjihi-text">
            <h3><?php echo e(translate_lang('tawjihi')); ?></h3>
            <p><?php echo e($tawjihiFirstYear?->localized_name); ?></p>
        </div>
    </a>
    <a href="<?php echo e(route('tawjihi-grade-year-fields',['slug'=>$tawjihiLastYear?->slug])); ?>" class="tawjihi-card" style="background-image: url('<?php echo e(asset('images/tawjihi-2008.png')); ?>');">
        <div class="tawjihi-text">
            <h3><?php echo e(translate_lang('tawjihi')); ?></h3>
            <p><?php echo e($tawjihiLastYear?->localized_name); ?></p>
        </div>
    </a>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/web/tawjihi.blade.php ENDPATH**/ ?>