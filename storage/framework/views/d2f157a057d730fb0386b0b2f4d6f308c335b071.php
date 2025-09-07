    <div class="ud-panel" id="courses">
        <div class="ud-title"><?php echo e(__('front.courses')); ?></div>
        <div class="ud-courses">
            <?php $__currentLoopData = $userCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('course',['course'=>$course->id,'slug'=>$course->slug])); ?>" class="ud-course">
                <div class="ud-course-meta">
                <h3><?php echo e($course->title); ?> <br>
                <span class="ud-course-meta-sub">
                    <?php if($course->subject): ?> <?php echo e($course->subject->localized_name); ?> <?php endif; ?>
                    <?php if($course->subject?->semester): ?> - <?php echo e($course->subject?->semester->localized_name); ?> <?php endif; ?>
                </span></h3>
                <div class="ud-course-teacher">
                    <img data-src="<?php echo e($course->teacher?->photo_url); ?>"><span><?php echo e($course->teacher?->name); ?></span>
                </div>
                </div>
                <div class="ud-course-date">
                <small><?php echo e($course->course_user?->created_at); ?></small>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/panel/student/courses.blade.php ENDPATH**/ ?>