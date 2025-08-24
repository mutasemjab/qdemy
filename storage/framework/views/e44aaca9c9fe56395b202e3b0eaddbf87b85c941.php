<?php $__env->startSection('title',$field->localized_name); ?>

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
        <h2><?php echo e($field?->localized_name); ?></h2>
        <h3 class=""><?php echo e(__('messages.Ministry Subjects')); ?></h3>
        </header>

        <?php if($ministrySubjects && $ministrySubjects->count()): ?>
        <div class="tj2009__subjects">
            <?php $__currentLoopData = $ministrySubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $ministrySubject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div href="javascript:void(0)" class="tj2009__item"
                style="background-image:url('<?php echo e(asset('images/subject-')); ?><?php echo e($index % 2 ? 'bg.png' : 'bg2.png'); ?>')">
               <?php if($ministrySubject->is_optional): ?>
                    <a class="text-decoration-none" href="javascript:void(0)">
                        <span> <?php echo e($ministrySubject->localized_name); ?> </span>
                    </a>
                    <?php $subjects = CategoryRepository()->getOtionalSubjectsForField($ministrySubject); ?>

                    <?php if($subjects && $subjects->count()): ?>
                    <div class="examx-dropdown">
                        <button class="examx-pill">
                        <span>+</span>
                        </button>
                        <ul class="examx-menu">
                        <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $optiona_subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                <a class="text-decoration-none" href="<?php echo e(route('subject',['subject'=>$optiona_subject->id,'slug'=>$optiona_subject->slug])); ?>">
                                    <?php echo e($optiona_subject->localized_name); ?>

                                </a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                    <?php endif; ?>

                <?php else: ?>
                    <a class="text-decoration-none" href="<?php echo e(route('subject',['subject'=>$ministrySubject->id,'slug'=>$ministrySubject->slug])); ?>">
                        <span> <?php echo e($ministrySubject->localized_name); ?> </span>
                    </a>
                <?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>

        <h3 class="tj2009__subtitle"><?php echo e(__('messages.School Subjects')); ?></h3>
        <?php if($schoolSubjects && $schoolSubjects->count()): ?>
        <div class="tj2009__subjects">
            <?php $__currentLoopData = $schoolSubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $schoolSubject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div href="javascript:void(0)" class="tj2009__item"
                style="background-image:url('<?php echo e(asset('images/subject-')); ?><?php echo e($index % 2 ? 'bg.png' : 'bg2.png'); ?>')">
               <?php if($schoolSubject->is_optional): ?>
                    <a class="text-decoration-none" href="javascript:void(0)">
                        <span> <?php echo e($schoolSubject->localized_name); ?> </span>
                    </a>
                    <?php $subjects = CategoryRepository()->getOtionalSubjectsForField($schoolSubject); ?>

                    <?php if($subjects && $subjects->count()): ?>
                    <div class="examx-dropdown">
                        <button class="examx-pill">
                        <span>+</span>
                        </button>
                        <ul class="examx-menu">
                        <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $optiona_subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                <a class="text-decoration-none" href="<?php echo e(route('subject',['subject'=>$optiona_subject->id,'slug'=>$optiona_subject->slug])); ?>">
                                    <?php echo e($optiona_subject->localized_name); ?>

                                </a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                    <?php endif; ?>

                <?php else: ?>
                    <a class="text-decoration-none" href="<?php echo e(route('subject',['subject'=>$schoolSubject->id,'slug'=>$schoolSubject->slug])); ?>">
                        <span> <?php echo e($schoolSubject->localized_name); ?> </span>
                    </a>
                <?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/user/tawjihi-field.blade.php ENDPATH**/ ?>