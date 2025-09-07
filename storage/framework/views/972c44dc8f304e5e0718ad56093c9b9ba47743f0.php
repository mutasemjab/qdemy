

<?php $title = $title ?? 'courses' ?>
<?php $__env->startSection('title', translate_lang($title)); ?>

<?php $__env->startSection('content'); ?>
<section class="universities-page">

    <div class="courses-header-wrapper">
        <div class="courses-header">
            <h2><?php echo e(translate_lang($title)); ?></h2>
            <span class="grade-number"><?php echo e(mb_substr( $title,0,1)); ?></span>
        </div>
    </div>

    <div class="examx-filters">
        <?php echo $__env->make('web.alert-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <form action='<?php echo e(route("courses")); ?>' method='get' id="filterForm">
            <div class="examx-row">

                <div class="examx-dropdown">
                    <select class="examx-pill" name="programm_id" id="programm_id">
                        <option value=""><?php echo e(translate_lang('select_program')); ?></option>
                        <?php $__currentLoopData = $programms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $programm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($programm->id); ?>"
                                    data-ctg-key="<?php echo e($programm->ctg_key); ?>"
                                    <?php echo e(request('programm_id') == $programm->id ? 'selected' : ''); ?>>
                                <?php echo e($programm->localized_name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="examx-dropdown" id="gradeSection" style="">
                    <select class="examx-pill" name="grade_id" id="grade_id">
                        <option value=""><?php echo e(translate_lang('select_grade')); ?></option>
                        <?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($grade->id); ?>"
                                    <?php echo e(request('grade_id') == $grade->id ? 'selected' : ''); ?>>
                                <?php echo e($grade->localized_name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="examx-dropdown" id="subjectSection" style="">
                    <select class="examx-pill" name="subject_id" id="subject_id">
                        <option value=""><?php echo e(translate_lang('select_subject')); ?></option>
                        <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($subject->id); ?>"
                                    <?php echo e(request('subject_id') == $subject->id ? 'selected' : ''); ?>>
                                <?php echo e($subject->localized_name); ?>

                                <?php if($subject->semester): ?> - <?php echo e($subject->semester->localized_name); ?> <?php endif; ?>
                                <?php if($subject->grade && $subject->grade->level == 'international-program-child'): ?> - <?php echo e($subject->grade->localized_name); ?> <?php endif; ?>
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

            </div>

            <div class="examx-search">
                <input type="text" placeholder="<?php echo e(translate_lang('search')); ?>" name='search' value="<?php echo e(request('search')); ?>">
                <button type="submit"><?php echo e(__('messages.search')); ?>

                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </form>
    </div>

    <?php
        $user_courses = session()->get('courses', []);
        $user_enrollment_courses = CourseRepository()->getUserCoursesIds(auth_student()?->id);
    ?>

    <div class="grades-grid">
        <?php $__empty_1 = true; $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="university-card">
            <div class="card-image">
                <span class="rank">#<?php echo e($loop->index + 1); ?></span>
                <img data-src="<?php echo e($course->photo_url); ?>" alt="Course Image">
                <?php if($course->subject?->program): ?>
                    <span class="course-name"><?php echo e($course->subject->program->localized_name); ?></span>
                <?php endif; ?>
            </div>
            <div class="card-info">
                <p class="course-date"><?php echo e($course->created_at->locale(app()->getLocale())->translatedFormat('d F Y')); ?></p>
                <a class='text-decoration-none text-dark' href="<?php echo e(route('course',['course'=>$course->id,'slug'=>$course->slug])); ?>">
                    <span class="course-title"><?php echo e($course->subject?->localized_name); ?></span>
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
                      <a href="javascript:void(0)" class="join-btn joined-btn"><?php echo e(translate_lang('enrolled')); ?></a>
                    <?php elseif(is_array($user_courses) && in_array($course->id,$user_courses)): ?>
                      <a href="<?php echo e(route('checkout')); ?>" class="join-btn"><?php echo e(translate_lang('go_to_checkout')); ?> <i class="fas fa-shopping-cart"></i></a>
                      <span class="price"><?php echo e($course->selling_price); ?> <?php echo e(CURRENCY); ?></span>
                    <?php else: ?>
                        <a href="javascript:void(0)" class="join-btn enroll-btn"
                          data-course-id="<?php echo e($course->id); ?>"><?php echo e(translate_lang('enroll')); ?></a>

                        <a href="<?php echo e(route('checkout')); ?>" id="go_to_checkout_<?php echo e($course->id); ?>" style='display:none;' class="join-btn">
                             <?php echo e(translate_lang('go_to_checkout')); ?>

                            <i class="fas fa-shopping-cart"></i>
                        </a>

                        <span class="price"><?php echo e($course->selling_price); ?> <?php echo e(CURRENCY); ?></span>
                        <span class="price"><?php echo e($course->selling_price); ?> <?php echo e(CURRENCY); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12 text-center">
            <p><?php echo e(translate_lang('no_courses_found')); ?></p>
        </div>
        <?php endif; ?>
    </div>

    <?php echo e($courses?->links('pagination::custom-bootstrap-5') ?? ''); ?>


    <!-- Modal for messages -->
    <div id="enrollment-modal" class="messages modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3 id="modal-icon"> <i class="fa fa-check"></i> </h3>
            <h3 id="modal-title"><?php echo e(translate_lang('course_added')); ?></h3>
            <p id="modal-message"><?php echo e(translate_lang('course_added_successfully')); ?></p>
            <div class="modal-buttons" id="modal-buttons">
                <button id="continue-shopping"><?php echo e(translate_lang('continue_shopping')); ?></button>
                <button id="go-to-checkout"><?php echo e(translate_lang('go_to_checkout')); ?></button>
            </div>
        </div>
    </div>

</section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Course Enrollment Manager
class EnrollmentManager {
    constructor() {
        this.user = "<?php echo e(auth_student()?->id); ?>";
        this.modal = document.getElementById('enrollment-modal');
        this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        this.init();
    }

    init() {
        this.setupEnrollmentButtons();
        this.setupModalButtons();
        this.setupFilterForm();
    }

    // Show modal with custom content
    showModal(title, message, showButtons = true, icon = 'fa-check') {
        const modalTitle = document.getElementById('modal-title');
        const modalMessage = document.getElementById('modal-message');
        const modalIcon = document.getElementById('modal-icon');
        const modalButtons = document.getElementById('modal-buttons');

        modalTitle.textContent = title;
        modalMessage.textContent = message;
        modalIcon.innerHTML = `<i class="fa ${icon}"></i>`;
        modalButtons.style.display = showButtons ? 'flex' : 'none';

        this.modal.style.display = 'flex';
    }

    hideModal() {
        this.modal.style.display = 'none';
    }

    setupEnrollmentButtons() {
        const enrollButtons = document.querySelectorAll('.enroll-btn');

        enrollButtons.forEach(button => {
            button.addEventListener('click', async (e) => {
                e.preventDefault();

                if (!this.user) {
                    this.showModal(
                        "<?php echo e(translate_lang('login_required')); ?>",
                        "<?php echo e(translate_lang('please_login_first')); ?>",
                        false,
                        'fa-exclamation-circle'
                    );
                    setTimeout(() => this.hideModal(), 3000);
                    return;
                }

                const courseId = button.getAttribute('data-course-id');
                const originalText = button.innerHTML;

                // Show loading state
                button.innerHTML = '<?php echo e(translate_lang("loading")); ?>...';
                button.disabled = true;

                try {
                    const response = await fetch('<?php echo e(route("add.to.session")); ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ course_id: courseId })
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Show success modal
                        this.showModal(
                            "<?php echo e(translate_lang('course_added')); ?>",
                            "<?php echo e(translate_lang('course_added_successfully')); ?>",
                            true,
                            'fa-check'
                        );


                        button.remove();
                        document.getElementById('go_to_checkout_'+courseId).style.display = 'block';
                    } else {
                        // Show error modal
                        this.showModal(
                            "<?php echo e(translate_lang('error')); ?>",
                            data.message || "<?php echo e(translate_lang('something_went_wrong')); ?>",
                            false,
                            'fa-exclamation-triangle'
                        );
                        button.innerHTML = originalText;
                        button.disabled = false;
                    }
                } catch (error) {
                    console.error('Error:', error);
                    this.showModal(
                        "<?php echo e(translate_lang('error')); ?>",
                        "<?php echo e(translate_lang('connection_error')); ?>",
                        false,
                        'fa-exclamation-triangle'
                    );
                    button.innerHTML = originalText;
                    button.disabled = false;
                }
            });
        });
    }

    setupModalButtons() {
        // Close button
        const closeBtn = document.querySelector('.close');
        closeBtn?.addEventListener('click', () => this.hideModal());

        // Continue shopping button
        const continueBtn = document.getElementById('continue-shopping');
        continueBtn?.addEventListener('click', () => this.hideModal());

        // Go to checkout button
        const checkoutBtn = document.getElementById('go-to-checkout');
        checkoutBtn?.addEventListener('click', () => {
            window.location.href = '<?php echo e(route("checkout")); ?>';
        });

        // Close on outside click
        window.addEventListener('click', (event) => {
            if (event.target === this.modal) {
                this.hideModal();
            }
        });
    }

    setupFilterForm() {
        const form = document.getElementById('filterForm');
        const programmSelect = document.getElementById('programm_id');
        const gradeSelect = document.getElementById('grade_id');
        const subjectSelect = document.getElementById('subject_id');

        // Auto submit on filter change
        [programmSelect, gradeSelect, subjectSelect].forEach(element => {
            element?.addEventListener('change', () => {
                // Clear dependent filters
                if (element === programmSelect) {
                    gradeSelect.value = '';
                    subjectSelect.value = '';
                } else if (element === gradeSelect) {
                    subjectSelect.value = '';
                }
                form.submit();
            });
        });

        // Show/hide sections based on data
        if (gradeSelect && document.querySelectorAll('#grade_id option').length > 1) {
            document.getElementById('gradeSection').style.display = 'block';
        }

        if (subjectSelect && document.querySelectorAll('#subject_id option').length > 1) {
            document.getElementById('subjectSection').style.display = 'block';
        }
    }
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    new EnrollmentManager();
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/web/courses.blade.php ENDPATH**/ ?>