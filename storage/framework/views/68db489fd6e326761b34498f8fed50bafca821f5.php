<?php $title = $title ?? 'courses' ?>
<?php $__env->startSection('title', __('messages.' . $title)); ?>

<?php $__env->startSection('content'); ?>
<section class="universities-page">

    <div class="courses-header-wrapper">
        <div class="courses-header">
            <h2><?php echo e(__('messages.' . $title)); ?></h2>
            <span class="grade-number"><?php echo e(mb_substr( $title,0,1)); ?></span>
        </div>
    </div>
    <?php $user_courses = session()->get('courses', []); ?>
    <?php $user_enrollment_courses = CourseRepository()->getUserCoursesIds(auth('user')->user()?->id); ?>
    <div class="grades-grid">
        <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="university-card">
            <div class="card-image">
                <span class="rank">#<?php echo e($loop->index + 1); ?></span>
                <img data-src="<?php echo e($course->photo_url); ?>" alt="Course Image">
                <?php if($course->category && $course->category?->parent_id): ?><span class="course-name"><?php echo e($course->category->localized_name); ?></span><?php endif; ?>
            </div>
            <div class="card-info">
                <p class="course-date"><?php echo e($course->created_at->locale(app()->getLocale())->translatedFormat('d F Y')); ?></p>
                <a class='text-decoration-none text-dark' href="<?php echo e(route('course',['course'=>$course->id,'slug'=>$course->slug])); ?>">
                    <span class="course-title"><?php echo e($course->title); ?></span>
                </a>
                <div class="instructor">
                    <img data-src="<?php echo e($course->teacher?->photo_url); ?>" alt="Instructor">
                    <a class='text-decoration-none text-dark' href="<?php echo e(route('teacher',$course->teacher?->id ?? '-')); ?>">
                        <span><?php echo e($course->teacher?->name); ?></span>
                    </a>
                </div>
                <div class="card-footer">
                    <?php if(is_array($user_enrollment_courses) && in_array($course->id,$user_enrollment_courses)): ?>
                      <a href="javascript:void(0)" class="join-btn joined-btn"><?php echo e(__('messages.enrolled')); ?></a>
                    <?php elseif(is_array($user_courses) && in_array($course->id,$user_courses)): ?>
                      <a href="<?php echo e(route('checkout')); ?>" class="join-btn"><?php echo e(__('messages.go_to_checkout')); ?> <i class="fas fa-shopping-cart"></i></a>
                      <span class="price"><?php echo e($course->selling_price); ?> <?php echo e(CURRENCY); ?></span>
                    <?php else: ?>
                        <a href="javascript:void(0)" class="join-btn enroll-btn"
                          data-course-id="<?php echo e($course->id); ?>"><?php echo e(__('messages.enroll')); ?></a>
                        <span class="price"><?php echo e($course->selling_price); ?> <?php echo e(CURRENCY); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php echo e($courses?->links() ?? ''); ?>


    <!-- نافذة منبثقة -->
    <div id="enrollment-modal" class="messages modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3> <i class="fa fa-check"></i> </h3>
            <h3><?php echo e(__('messages.course_added')); ?></h3>
            <p><?php echo e(__('messages.course_added_successfully')); ?></p>
            <div class="modal-buttons">
                <button id="continue-shopping"><?php echo e(__('messages.continue_shopping')); ?></button>
                <button id="go-to-checkout"><?php echo e(__('messages.go_to_checkout')); ?></button>
            </div>
        </div>
    </div>

</section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // الحصول على العناصر
        const modal = document.getElementById('enrollment-modal');
        const closeBtn = document.querySelector('.close');
        const continueBtn = document.getElementById('continue-shopping');
        const checkoutBtn = document.getElementById('go-to-checkout');
        const enrollButtons = document.querySelectorAll('.enroll-btn');

        // الحصول على CSRF Token من meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // إضافة حدث النقر لأزرار التسجيل
        enrollButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const courseId = this.getAttribute('data-course-id');
                // إظهار مؤشر تحميل (اختياري)
                this.innerHTML = '<?php echo e(__("messages.loading")); ?>...';
                this.disabled = true;

                // إرسال طلب Ajax
                fetch('<?php echo e(route("add.to.session")); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ course_id: courseId })
                })
                .then(response => {
                    // إعادة زر التسجيل إلى حالته الأصلية
                    this.innerHTML = "<?php echo e(__('messages.go_to_checkout')); ?> <i class='fas fa-shopping-cart'>";
                    this.setAttribute('href',"<?php echo e(route('checkout')); ?>");
                    this.disabled = false;
                    this.classList.remove('enroll-btn');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // عرض النافذة المنبثقة
                        modal.style.display = 'flex';
                    } else {
                        alert('حدث خطأ أثناء إضافة الكورس: ' + (data.message || 'Unknown error'));
                    }
                    console.log(data);
                })
                .catch(error => {
                    console.log('Error:', error);
                    alert('حدث خطأ في الاتصال بالخادم');
                });
            });
        });

        // إغلاق النافذة عند النقر على ×
        closeBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        // الاستمرار في التسوق
        continueBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        // الانتقال إلى صفحة الدفع
        checkoutBtn.addEventListener('click', function() {
            window.location.href = '<?php echo e(route("checkout")); ?>'; // تأكد من وجود هذا المسار
        });

        // إغلاق النافذة عند النقر خارجها
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/user/courses.blade.php ENDPATH**/ ?>