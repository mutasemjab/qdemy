<?php $__env->startSection('title', translate_lang('basic_grades')); ?>

<?php $__env->startSection('content'); ?>
<section class="grades-basic-page">

<div class="grades-header-wrapper">
    <div class="grades-header">
        <img data-src="<?php echo e(asset('assets_front/images/booksframe.png')); ?>" class="header-icon">
        <h2><?php echo e(translate_lang('basic_grades')); ?></h2>
        <img data-src="<?php echo e(asset('assets_front/images/bookssearch.png')); ?>" class="header-icon">
    </div>
    </div>

    <div class="grades-grid-wrapper">
    <div class="grades-grid">
        <?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('grade',['grade'=>$grade->id,'slug'=>$grade->slug])); ?>" class="grade-card" style="background-image: url('<?php echo e(asset('images/boxbg.png')); ?>');">
            <span><?php echo e($grade->localized_name); ?></span>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/web/gradesbasic.blade.php ENDPATH**/ ?>