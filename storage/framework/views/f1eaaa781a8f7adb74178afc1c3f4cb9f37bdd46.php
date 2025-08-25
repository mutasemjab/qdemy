<?php $__env->startSection('title', $grade->localized_name); ?>

<?php $__env->startSection('content'); ?>
<section class="grade-page">

    <!-- Header -->
    <div class="grades-header-wrapper">
        <div class="grades-header">
            <h2><?php echo e($grade->localized_name); ?></h2>
            <span class="grade-number"><?php echo e($grade->sort_order); ?></span>
        </div>
    </div>

    <!-- Semesters -->
     <?php if($semesters && $semesters->count()): ?>
     <div class="semesters-row">
        <?php $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $semester): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <a href="javascript:void(0)" class="semester-box" id='<?php echo e($index); ?>_semester' data-semester="<?php echo e($index); ?>"><?php echo e($semester->localized_name); ?></a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
     </div>
    <!-- Subjects -->
    <?php $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $semester): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="subjects-grid semester-content"  id="<?php echo e($index); ?>_semester_content"
            style="<?php echo e(!$loop->first ? 'display:none;' : ''); ?>">
            <?php $subjects  = CategoryRepository()->getDirectChilds($semester) ?? []; ?>
            <?php if($subjects && $subjects->count()): ?>
                <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('subject',['subject'=>$subject->id,'slug'=>$subject->slug])); ?>" class="subject-card dark">
                    <span><?php echo e($subject->localized_name); ?></span>
                    <i class="<?php echo e($subject->icon); ?>"></i>
                    <!-- <img data-src="<?php echo e(asset('assets_front/images/icon-math.png')); ?>" alt="<?php echo e($subject->localized_name); ?>" class="subject-icon"> -->
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>


</section>
<?php $__env->stopSection(); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // تعريف الدالة
    window.switchSemester = function(semester) {
        // إخفاء جميع المحتويات
        document.querySelectorAll('.semester-content').forEach(content => {
            content.style.display = 'none';
        });

        // إزالة الفئة النشطة من جميع التبويبات
        document.querySelectorAll('.semester-box').forEach(tab => {
            tab.classList.remove('light');
        });

        // إظهار المحتوى المحدد
        document.getElementById(semester + '_semester_content').style.display = 'grid';

        // إضافة الفئة النشطة للتبويب
        document.querySelector(`.semester-box[data-semester="${semester}"]`).classList.add('light');
    };

    // إضافة Event Listeners للتبويبات
    document.querySelectorAll('.semester-box').forEach(tab => {
        tab.addEventListener('click', function() {
            const semester = this.getAttribute('data-semester');
            switchSemester(semester);
        });
    });
});
</script>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/user/grade.blade.php ENDPATH**/ ?>