<?php $__env->startSection('title',translate_lang('packages_offers')); ?>

<?php $__env->startSection('content'); ?>
<section class="pkgo-wrap">


    <div class="universities-header-wrapper">
        <div class="universities-header">
            <h2><?php echo e(translate_lang('packages_offers')); ?></h2>
        </div>
    </div>

  <div class="co-chooser">
    <button class="co-chooser-btn" id="coChooserBtn">
      <span> <?php echo e($programm?->localized_name ?? translate_lang('choose the programm')); ?> </span>
      <i class="fa-solid fa-caret-down"></i>
    </button>
    <?php if($programms && $programms->count()): ?>
    <ul class="co-chooser-list" id="coChooserList">
      <li><a href="<?php echo e(route('packages-offers')); ?>" class='text-decoration-none'>
        <?php echo e(translate_lang('all programms')); ?></a>
      </li>
      <?php $__currentLoopData = $programms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <li><a href="<?php echo e(route('packages-offers',$prog)); ?>" class='text-decoration-none'>
        <?php echo e($prog->localized_name); ?></a>
      </li>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
    <?php endif; ?>
  </div>

  <div class="pkgo-head">
    <?php echo e(translate_lang('cards')); ?> <?php echo e($programm?->localized_name); ?>

   </div>

    <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="pkgo-row">
        <div class="pkgo-side pkgo-side-1">
        <span><?php echo e($package->name); ?></span>
        </div>
        <div class="pkgo-mid">
        <div class="pkgo-title">
            <h3><?php echo e($package->name); ?></h3>
            <!-- <span class="pkgo-year"><?php echo e($package->description); ?></span> -->
        </div>
        <p class="pkgo-desc"><?php echo e($package->description); ?></p>
        <?php if($package->categories && $package->categories->count()): ?>
        <ul class="pkgo-tags">
            <?php $__currentLoopData = $package->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li>
                <?php if($category->parent): ?> <?php echo e($category->parent->localized_name); ?> -> <?php endif; ?> <?php echo e($category->localized_name); ?>

                <!-- <?php echo e($category->localized_name); ?> -->
            </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
        <?php endif; ?>
        </div>
        <div class="pkgo-cta-col">
        <a href="<?php echo e(route('package',$package)); ?>" class="pkgo-cta"><?php echo e(translate_lang('buy_or_activate')); ?></a>
        </div>
        <div class="pkgo-price"><?php echo e(sprintf('%g', $package->price)); ?> <span><?php echo e(CURRENCY); ?></span></div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/web/packages-offers.blade.php ENDPATH**/ ?>