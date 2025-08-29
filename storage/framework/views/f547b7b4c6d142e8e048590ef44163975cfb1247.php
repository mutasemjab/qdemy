<?php $__env->startSection('title',$package?->name); ?>

<?php $__env->startSection('content'); ?>
<section class="pkgo-wrap">


    <div class="universities-header-wrapper">
        <div class="universities-header">
            <h2><?php echo e($package?->name); ?></h2> <br>
        </div>
    </div>

    <?php if($is_type_class && $categoriesTree && $categoriesTree->count()): ?>
    <div class="co-chooser">
        <button class="co-chooser-btn" id="coChooserBtn">
            <span><?php echo e($clas?->localized_name ?? __('messages.choose class')); ?></span>
            <i class="fa-solid fa-caret-down"></i>
        </button>

        <ul class="co-chooser-list" id="coChooserList">
            <li><a href="javascript:void(0)" class='text-decoration-none'>
                <?php echo e(__('messages.all classes')); ?></a>
            </li>
            <?php $__currentLoopData = $categoriesTree; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categories): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($categories && count($categories)): ?>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li>
                        <a class='text-decoration-none'
                            href="<?php echo e(route('package',['package'=>$package->id,'clas'=>$class['id']])); ?>">
                            <!-- <?php if($class['category']?->parent): ?> <?php echo e($class['category']?->parent->localized_name); ?> <?php endif; ?> >  -->
                            <?php echo e($class['name']); ?>

                        </a>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>


    </div>
    <?php endif; ?>

    <div type="submit" class="pkgo-head">
        <?php echo e(sprintf('%g', $package->price)); ?> <span><?php echo e(CURRENCY); ?></span>
    </div>


    <?php if($lessons && $lessons->count()): ?>
    <div class="sp2-box">
      <?php $__currentLoopData = $lessons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lesson): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="sp2-group">
          <button class="sp2-group-head">
            <i class="fa-solid fa-plus"></i>
            <span><?php echo e($lesson->localized_name); ?></span>
          </button>
          <div class="sp2-panel">
            <table class="sp2-table">
              <thead>
              <tr>
                <th><?php echo e($lesson->localized_name); ?></th>
              </tr>
              </thead>
              <tbody>
              <tr>
                <td>
                    <?php if($lesson->is_optional == 0): ?>

                        <?php echo e($lesson->localized_name); ?>


                        <button><?php echo e(__('messages.add to cart')); ?></button>

                    <?php else: ?>
                        <?php $optionals = CategoryRepository()->getOtionalSubjectsForField($lesson); ?>
                        <?php $__currentLoopData = $optionals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $optional): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="sp2-group">
                            <button class="sp2-group-head">
                                <i class="fa-solid fa-plus"></i>
                                <span><?php echo e($optional->localized_name); ?></span>
                            </button>
                            <div class="sp2-panel">
                                <table class="sp2-table">
                                <thead>
                                <tr>
                                    <!-- <th><?php echo e($optional->localized_name); ?></th> -->
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <?php echo e($optional->localized_name); ?>

                                        <button><?php echo e(__('messages.add to cart')); ?></button>
                                    </td>
                                </tr>
                                </tbody>
                                </table>
                            </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <?php endif; ?>
                </td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>


</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/web/package.blade.php ENDPATH**/ ?>