<!-- Header Section -->
<header class="header">
    <div class="container">
        <a href="<?php echo e(route('home')); ?>">
        <div class="logo">
            <img src="<?php echo e(asset('assets_front/images/logo.png')); ?>" alt="Logo">
        </div>
        </a>
        <nav class="nav" id="navMenu">
            <ul>
                <li><a href="<?php echo e(route('home')); ?>" class="active">الرئيسية</a></li>
                <li><a href="<?php echo e(route('contacts')); ?>">من نحن؟</a></li>
                <li><a href="<?php echo e(route('courses')); ?>">دورات</a></li>
                <li><a href="<?php echo e(route('teachers')); ?>">المعلمون</a></li>
                <li><a href="<?php echo e(route('sale-point')); ?>">نقاط البيع</a></li>
                <li>
                    <a href="<?php echo e(route('community')); ?>" class="community-link">
                        <span>مجتمع</span>
                        <img src="<?php echo e(asset('assets_front/images/icons/community.png')); ?>" alt="Community Icon" class="icon">
                    </a>
                </li>
                <li><a href="<?php echo e(route('e-exam')); ?>">امتحانات</a></li>
                <li><a href="<?php echo e(route('download')); ?>">تطبيقاتنا</a></li>
            </ul>
        </nav>

        <div class="actions">

            <!-- Cart -->
            <a href="<?php echo e(route('checkout')); ?>" class="cart-icon">
                <i class="fas fa-shopping-cart"></i>
            </a>

            <!-- Auth Buttons -->
            <?php if(!auth('user')?->user()): ?>
              <a href="<?php echo e(route('user.register')); ?>" class="btn btn-primary">إنشاء حساب</a>
              <a href="<?php echo e(route('user.login')); ?>" class="btn btn-outline">تسجيل دخول</a>
            <?php else: ?>
              <a href="<?php echo e(route('student.account')); ?>" class="btn btn-outline">حسابي</a>
              <form action="<?php echo e(route('logout')); ?>" method='post'><?php echo csrf_field(); ?>
                <button style='border: 1px solid #0055D2;padding: 8px;' class="btn btn-primary">تسجيل خروج</button>
              </form>
              <!-- <a href="<?php echo e(route('logout')); ?>" class="btn btn-primary">تسجيل خروج</a> -->
            <?php endif; ?>

            <!-- Language Dropdown -->
            <div class="lang-dropdown">
                <button class="lang-btn"><i class="fas fa-globe"></i></button>
                <div class="lang-menu">
                    <a href="#">العربية</a>
                    <a href="#">English</a>
                </div>
            </div>

            <!-- Hamburger Button -->
            <button class="menu-toggle" id="menuToggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
</header>
<?php /**PATH C:\xampp\htdocs\qdemy\resources\views/layouts/header.blade.php ENDPATH**/ ?>