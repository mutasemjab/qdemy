<?php $hideFooter = true; ?>


<?php $__env->startSection('title', 'تسجيل دخول'); ?>

<?php $__env->startSection('content'); ?>
<section class="auth-page">
    <div class="auth-overlay"></div>

    <div class="auth-content-wrapper">
        <div class="auth-info-box">
            <img data-src="<?php echo e(asset('assets_front/images/logo-white.png')); ?>" alt="Qdemy Logo">
            <h2>ابدأ في بناء مستقبلك</h2>
            <p>منصة تعليمية تساعدك على التعلم والتطور من أي مكان وفي أي وقت</p>
        </div>

        <div class="auth-form-box">
            <p class="welcome-text">مرحباً بك في <strong>Qdemy</strong></p>
            <h3>تسجيل دخول</h3>

            <form method='post' action="<?php echo e(route('user.login.submit')); ?>">
                <?php echo csrf_field(); ?>
                <input  value="<?php echo e(old('phone')); ?>" name="phone"    type="phone"    placeholder="رقم الهاتف">
                <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-danger"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <input  value="<?php echo e(old('password')); ?>" name="password" type="password" placeholder="كلمة المرور">
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-danger"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <button class="submit-btn" type="submit">تسجيل دخول</button>
            </form>

            <p class="login-link">ليس لديك حساب؟ <a href="#">انشاء حساب</a></p>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/user/login.blade.php ENDPATH**/ ?>