<?php $hideFooter = true; ?>


<?php $__env->startSection('title', 'تسجيل حساب'); ?>

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
            <h3>تسجيل حساب جديد</h3>

            <div class="account-type">
                <button id='student_account' class="active account_type">حساب طالب</button>
                <button id='parent_account'  class="account_type">حساب ولي أمر</button>
            </div>

            <form method='post' action="<?php echo e(route('user.register.submit')); ?>">
                <?php echo csrf_field(); ?>
                <input  type="hidden"   value="<?php echo e(old('student') ?? 'student'); ?>" id="role_name" name="role_name">
                <?php $__errorArgs = ['role_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-danger"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <input  value="<?php echo e(old('name')); ?>" name="name"    type="text"     placeholder="الاسم الكامل">
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-danger"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <input  value="<?php echo e(old('phone')); ?>" name="phone"    type="phone"    placeholder="رقم الهاتف">
                <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-danger"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <input  value="<?php echo e(old('email')); ?>" name="email"    type="email"    placeholder="إيميل">
                <?php $__errorArgs = ['email'];
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
                <select name="grade"    class="grade-select">
                    <option disabled selected>اختر الصف</option>
                    <option <?php echo e(old('grade') == 1 ? 'selected' : ''); ?> value="1">الصف الأول</option>
                    <option <?php echo e(old('grade') == 2 ? 'selected' : ''); ?> value="2">الصف الثاني</option>
                    <option <?php echo e(old('grade') == 3 ? 'selected' : ''); ?> value="3">الصف الثالث</option>
                    <option <?php echo e(old('grade') == 4 ? 'selected' : ''); ?> value="4">الصف الرابع</option>
                    <option <?php echo e(old('grade') == 5 ? 'selected' : ''); ?> value="5">الصف الخامس</option>
                    <option <?php echo e(old('grade') == 6 ? 'selected' : ''); ?> value="6">الصف السادس</option>
                    <option <?php echo e(old('grade') == 7 ? 'selected' : ''); ?> value="7">الصف السابع</option>
                    <option <?php echo e(old('grade') == 8 ? 'selected' : ''); ?> value="8">الصف الثامن</option>
                    <option <?php echo e(old('grade') == 9 ? 'selected' : ''); ?> value="9">الصف التاسع</option>
                </select>
                <?php $__errorArgs = ['grade'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-danger"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                <div class="student-info">
                    <img data-src="<?php echo e(asset('assets_front/images/register-icon.png')); ?>">
                    <div>
                        <h4>خالد أحمد</h4>
                        <span>0098978787</span>
                    </div>
                    <span class="remove">×</span>
                </div>

                <button class="submit-btn" type="submit">إنشاء حساب</button>
            </form>

            <p class="login-link">لديك حساب؟ <a href="#">سجل دخول</a></p>
        </div>
    </div>
</section>
<script>
    const roleName       = document.getElementById('role_name');
    const studentAccount = document.getElementById('student_account');
    const parentAccount  = document.getElementById('parent_account');
    // إضافة مستمعي الأحداث لكل زر
    studentAccount.addEventListener('click', () => toggleActiveClass(studentAccount, parentAccount,'student'));
    parentAccount.addEventListener('click', () => toggleActiveClass(parentAccount, studentAccount,'parent'));
    // دالة التبديل
    function toggleActiveClass(clickedButton, otherButton ,role_name = 'student') {
        roleName.value = role_name;
        clickedButton.classList.add('active');
        otherButton.classList.remove('active');
    }

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/user/register.blade.php ENDPATH**/ ?>