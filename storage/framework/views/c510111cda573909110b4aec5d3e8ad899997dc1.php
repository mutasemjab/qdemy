<?php $hideFooter = true; ?>


<?php $__env->startSection('title', __('front.login')); ?>

<?php $__env->startSection('content'); ?>
<section class="auth-page">
    <div class="auth-overlay"></div>

    <div class="auth-content-wrapper">
        <div class="auth-info-box">
            <img data-src="<?php echo e(asset('assets_front/images/logo-white.png')); ?>" alt="Qdemy Logo">
            <h2><?php echo e(__('front.start_building_future')); ?></h2>
            <p><?php echo e(__('front.learning_platform_desc')); ?></p>
        </div>

        <div class="auth-form-box">
            <p class="welcome-text"><?php echo e(__('front.welcome_to')); ?> <strong>Qdemy</strong></p>
            <h3><?php echo e(__('front.login')); ?></h3>

            <form method='post' action="<?php echo e(route('user.login.submit')); ?>">
                <?php echo csrf_field(); ?>
                <input  value="<?php echo e(old('phone')); ?>" name="phone" type="phone" placeholder="<?php echo e(__('front.phone_number')); ?>">
                <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-danger"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                <input  value="<?php echo e(old('password')); ?>" name="password" type="password" placeholder="<?php echo e(__('front.password')); ?>">
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-danger"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                <button class="submit-btn" type="submit"><?php echo e(__('front.login')); ?></button>
            </form>

            <p class="login-link"><?php echo e(__('front.no_account')); ?> <a href="<?php echo e(route('user.register')); ?>"><?php echo e(__('front.create_account')); ?></a></p>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\qdemy\resources\views/web/login.blade.php ENDPATH**/ ?>