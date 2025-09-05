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
        <h3 class=""><?php echo e(translate_lang('Ministry Subjects')); ?></h3>
        </header>

        <?php if($ministrySubjects && $ministrySubjects->count()): ?>
        <div class="tj2009__subjects">
            <?php $__currentLoopData = $ministrySubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $ministrySubject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div href="javascript:void(0)" class="tj2009__item"
                style="background-image:url('<?php echo e(asset('images/subject-')); ?><?php echo e($index % 2 ? 'bg.png' : 'bg2.png'); ?>')">
               <?php if($ministrySubject->has_optional_subject): ?>
                    <a class="text-decoration-none" href="javascript:void(0)">
                        <span> <?php echo e($ministrySubject->localized_name); ?> </span>
                    </a>
                    <?php $subjects = SubjectRepository()->getOptionalSubjectOptions($field,$ministrySubject); ?>

                    <div class="examx-dropdown subject-plus-dropdown-examx">
                        <button class="examx-pill" type="button" tabindex="0">
                            <span>+</span>
                        </button>
                        <ul class="examx-menu">
                        <?php if($subjects && $subjects->count()): ?>
                        <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $optiona_subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                <a class="text-decoration-none" href="<?php echo e(route('subject',['subject'=>$optiona_subject->id,'slug'=>$optiona_subject->slug])); ?>">
                                    <?php echo e($optiona_subject->localized_name); ?>

                                </a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                        </ul>
                    </div>

                <?php else: ?>
                    <a class="text-decoration-none" href="<?php echo e(route('subject',['subject'=>$ministrySubject->id,'slug'=>$ministrySubject->slug])); ?>">
                        <span> <?php echo e($ministrySubject->localized_name); ?> </span>
                    </a>
                <?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>

        <h3 class="tj2009__subtitle"><?php echo e(translate_lang('School Subjects')); ?></h3>
        <?php if($schoolSubjects && $schoolSubjects->count()): ?>
        <div class="tj2009__subjects">
            <?php $__currentLoopData = $schoolSubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $schoolSubject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div href="javascript:void(0)" class="tj2009__item"
                style="background-image:url('<?php echo e(asset('images/subject-')); ?><?php echo e($index % 2 ? 'bg.png' : 'bg2.png'); ?>')">
               <?php if($schoolSubject->has_optional_subject): ?>
                    <a class="text-decoration-none" href="javascript:void(0)">
                        <span> <?php echo e($schoolSubject->localized_name); ?> </span>
                    </a>
                    <?php $subjects = SubjectRepository()->getOptionalSubjectOptions($field,$schoolSubject); ?>

                    <div class="examx-dropdown subject-plus-dropdown-examx">
                        <button class="examx-pill" type="button" tabindex="0">
                            <span>+</span>
                        </button>
                        <ul class="examx-menu">
                        <?php if($subjects && $subjects->count()): ?>
                        <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $optiona_subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                <a class="text-decoration-none" href="<?php echo e(route('subject',['subject'=>$optiona_subject->id,'slug'=>$optiona_subject->slug])); ?>">
                                    <?php echo e($optiona_subject->localized_name); ?>

                                </a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                        </ul>
                    </div>

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
<?php $__env->startPush('styles'); ?>
<style>
.tj2009__item {
    display: grid;
    place-items: center !important;
    align-content: center!important;
}
.subject-plus-dropdown-examx {
    /* display: inline-flex; */
    align-items: center;
    /* gap: 8px; */
    background: #fff;
    border-radius: 18px;
    /* padding: 5px 14px 5px 10px; */
    box-shadow: 0 1px 10px rgba(0,85,210,0.07);
    margin: 0;
    /* min-width: 120px; */
    transition: box-shadow 0.17s;
    position: relative;
}

.subject-plus-dropdown-examx .examx-pill {
    background: #fff;
    color: #0055D2;
    border: none;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    font-size: 22px;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 1px 6px rgba(0,85,210,0.15);
    transition: box-shadow 0.18s, background 0.18s;
    outline: none;
    margin: 0 0 0 5px;
    z-index: 2;
}
.subject-plus-dropdown-examx .examx-pill:focus,
.subject-plus-dropdown-examx .examx-pill:hover {
    background: #f5faff;
    box-shadow: 0 2px 12px rgba(0,85,210,0.25);
}

/* لا تغير قائمة الدروب داون نفسها */
.subject-plus-dropdown-examx .examx-menu {
    z-index: 3;
}
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/web/tawjihi-last-year-field.blade.php ENDPATH**/ ?>