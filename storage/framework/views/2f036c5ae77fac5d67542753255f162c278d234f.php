<?php $__env->startSection('title','توجيهي 2009'); ?>

<?php $__env->startSection('content'); ?>
<section class="tj2009">
  <div class="tj2009__decor tj2009__decor--left">
    <img data-src="<?php echo e(asset('assets_front/images/tawjihi-left-bg.png')); ?>" alt="">
  </div>
  <div class="tj2009__decor tj2009__decor--right">
    <img data-src="<?php echo e(asset('assets_front/images/tj-right.png')); ?>" alt="">
  </div>

  <div class="tj2009__inner">
    <header class="tj2009__head">
      <h2><?php echo e($tawjihiFirstYear?->localized_name); ?></h2>
      <h3 class=""><?php echo e(translate_lang('Mandatory Ministry Subjects')); ?></h3>
    </header>

    <?php if($ministrySubjects && $ministrySubjects->count()): ?>
    <div class="tj2009__subjects">
        <?php $__currentLoopData = $ministrySubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $ministrySubject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('subject',['subject'=>$ministrySubject->id,'slug'=>$ministrySubject->slug])); ?>" class="tj2009__item"
            style="background-image:url('<?php echo e(asset('images/subject-')); ?><?php echo e($index % 2 ? 'bg.png' : 'bg2.png'); ?>')">
            <span> <?php echo e($ministrySubject->localized_name); ?> </span>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>

    <h3 class="tj2009__subtitle"><?php echo e(translate_lang('School Subjects')); ?></h3>
    <?php if($schoolSubjects && $schoolSubjects->count()): ?>
    <div class="tj2009__subjects">
        <?php $__currentLoopData = $schoolSubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $schoolSubject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('subject',['subject'=>$schoolSubject->id,'slug'=>$schoolSubject->slug])); ?>" class="tj2009__item"
            style="background-image:url('<?php echo e(asset('images/subject-')); ?><?php echo e($index % 2 ? 'bg.png' : 'bg2.png'); ?>')">
            <span> <?php echo e($schoolSubject->localized_name); ?> </span>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>

  </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/web/tawjihi-first-year.blade.php ENDPATH**/ ?>