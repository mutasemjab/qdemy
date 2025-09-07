
<?php $__env->startSection('title', __('panel.my_account')); ?>
<?php $__env->startSection('content'); ?>
    <section class="ud-wrap">

        <aside class="ud-menu">
            <div class="ud-user">
                <img data-src="<?php echo e($user->photo ? asset('assets/admin/uploads/' . $user->photo) : asset('assets_front/images/avatar-big.png')); ?>"
                    alt="">
                <div>
                    <h3><?php echo e($user->name); ?></h3>
                    <span><?php echo e($user->email); ?></span>
                </div>
            </div>

            <button class="ud-item active" data-target="profile"><i
                    class="fa-regular fa-user"></i><span><?php echo e(__('panel.personal_profile')); ?></span><i
                    class="fa-solid fa-angle-left"></i></button>
            <button class="ud-item" data-target="settings"><i
                    class="fa-solid fa-gear"></i><span><?php echo e(__('panel.account_settings')); ?></span><i
                    class="fa-solid fa-angle-left"></i></button>
            <button class="ud-item" data-target="notifications"><i
                    class="fa-regular fa-bell"></i><span><?php echo e(__('panel.notifications')); ?></span><i
                    class="fa-solid fa-angle-left"></i></button>
            <button class="ud-item" data-target="inbox"><i
                    class="fa-regular fa-comments"></i><span><?php echo e(__('panel.messages')); ?></span><i
                    class="fa-solid fa-angle-left"></i></button>
            <button class="ud-item" data-target="courses"><i
                    class="fa-solid fa-graduation-cap"></i><span><?php echo e(__('panel.my_courses')); ?></span><i
                    class="fa-solid fa-angle-left"></i></button>
            <button class="ud-item" data-target="schedule"><i class="fa-regular fa-calendar-days"></i><span>الجدول الزمني</span><i class="fa-solid fa-angle-left"></i></button>

            <button class="ud-item" data-target="results"><i
                    class="fa-solid fa-square-poll-vertical"></i><span><?php echo e(__('panel.my_results')); ?></span><i
                    class="fa-solid fa-angle-left"></i></button>
    
            <button class="ud-item" data-target="community"><i
                    class="fa-solid fa-magnifying-glass"></i><span><?php echo e(__('panel.q_community')); ?></span><i
                    class="fa-solid fa-angle-left"></i></button>
            <button class="ud-item" data-target="support"><i
                    class="fa-brands fa-whatsapp"></i><span><?php echo e(__('panel.technical_support')); ?></span><i
                    class="fa-solid fa-angle-left"></i></button>

            <a href="<?php echo e(route('user.logout')); ?>" class="ud-logout"><i
                    class="fa-solid fa-arrow-left-long"></i><span><?php echo e(__('panel.logout')); ?></span></a>
        </aside>

        <div class="ud-content">

            <div class="ud-panel show" id="profile">
                <div class="ud-title"><?php echo e(__('panel.personal_profile')); ?></div>
                <div class="ud-profile-head">
                    <img data-src="<?php echo e($user->photo ? asset('assets/admin/uploads/' . $user->photo) : asset('assets_front/images/avatar-round.png')); ?>"
                        class="ud-ava" alt="">
                    <div class="ud-name">
                        <h2><?php echo e($user->name); ?><br><span class="g-sub1"><?php echo e($user->email); ?></span></h2>
                    </div>
                </div>
                <div class="ud-formlist">
                    <div class="ud-row">
                        <div class="ud-key"><?php echo e(__('panel.name')); ?></div>
                        <div class="ud-val"><?php echo e($user->name); ?></div>
                    </div>
                    <div class="ud-row">
                        <div class="ud-key"><?php echo e(__('panel.email')); ?></div>
                        <div class="ud-val"><?php echo e($user->email); ?></div>
                    </div>
                    <div class="ud-row">
                        <div class="ud-key"><?php echo e(__('panel.phone')); ?></div>
                        <div class="ud-val"><?php echo e($user->phone); ?></div>
                    </div>
                    <div class="ud-row">
                        <div class="ud-key"><?php echo e(__('panel.grade')); ?></div>
                        <div class="ud-val"><?php echo e(__('panel.grade')); ?>

                            <?php echo e($user->clas_id ? 'الـ' . $user->clas_id : 'غير محدد'); ?></div>
                    </div>
                </div>
            </div>

            <div class="ud-panel" id="settings">
                <div class="ud-title"><?php echo e(__('panel.account_settings')); ?></div>
                <form method="POST" action="<?php echo e(route('student.update.account')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="ud-profile-head">
                        <div class="ud-ava-wrap">
                            <!-- صورة البروفايل -->
                            <img id="preview-image"
                                src="<?php echo e($user->photo ? asset('assets/admin/uploads/' . $user->photo) : asset('assets_front/images/avatar-round.png')); ?>"
                                class="ud-ava" alt="">

                            <!-- زر التعديل -->
                            <label class="ud-ava-edit">
                                <i class="fa-solid fa-pen"></i>
                                <input type="file" id="avatarInput" name="photo" accept="image/*" style="display:none">
                            </label>
                        </div>

                        <div class="ud-name">
                            <h2><?php echo e($user->name); ?><br><span class="g-sub1"><?php echo e($user->email); ?></span></h2>
                        </div>
                    </div>

                    <div class="ud-edit">
                        <label><?php echo e(__('panel.name')); ?>

                            <input type="text" name="name" value="<?php echo e(old('name', $user->name)); ?>">
                        </label>

                        <label><?php echo e(__('panel.email')); ?>

                            <input type="email" name="email" value="<?php echo e(old('email', $user->email)); ?>">
                        </label>

                        <label><?php echo e(__('panel.phone')); ?>

                            <input type="text" name="phone" value="<?php echo e(old('phone', $user->phone)); ?>">
                        </label>

                        <button class="ud-primary" type="submit"><?php echo e(__('panel.save')); ?></button>
                    </div>
                </form>
            </div>


            

            <!-- Include other panels (notifications, inbox, courses, schedule, results, offers, wallet, community, support) -->
            <?php echo $__env->make('panel.common.notifications', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->make('panel.common.inbox', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->make('panel.student.courses',['userCourses' => $userCourses], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->make('panel.student.results',['userCourses' => $userCourses], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->make('panel.student.schedule',['userExamsResults' => $userExamsResults], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->make('panel.student.community', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->make('panel.common.support', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
  <!-- JavaScript لمعاينة الصورة -->
 <script>
     document.getElementById('avatarInput').addEventListener('change', function(event) {
         const file = event.target.files[0];
         if (file) {
             const reader = new FileReader();

             reader.onload = function(e) {
                 document.getElementById('preview-image').setAttribute('src', e.target.result);
             };

             reader.readAsDataURL(file);
         }
     });
 </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/panel/student/dashboard.blade.php ENDPATH**/ ?>