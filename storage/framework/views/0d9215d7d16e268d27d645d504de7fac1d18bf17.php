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
                <li><a href="<?php echo e(route('home')); ?>" class="active"><?php echo e(__('front.home')); ?></a></li>
                <li><a href="<?php echo e(route('contacts')); ?>"><?php echo e(__('front.about_us')); ?></a></li>
                <li><a href="<?php echo e(route('courses')); ?>"><?php echo e(__('front.courses')); ?></a></li>
                <li><a href="<?php echo e(route('teachers')); ?>"><?php echo e(__('front.teachers')); ?></a></li>
                <li><a href="<?php echo e(route('sale-point')); ?>"><?php echo e(__('front.sale_points')); ?></a></li>
                <li>
                    <a href="<?php echo e(route('community')); ?>" class="community-link">
                        <span><?php echo e(__('front.community')); ?></span>
                        <img src="<?php echo e(asset('assets_front/images/icons/community.png')); ?>" alt="Community Icon" class="icon">
                    </a>
                </li>
                <li><a href="<?php echo e(route('exam.index')); ?>">امتحانات</a></li>
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
              <a href="<?php echo e(route('user.register')); ?>" class="btn btn-primary"><?php echo e(__('front.create_account')); ?></a>
              <a href="<?php echo e(route('user.login')); ?>" class="btn btn-outline"><?php echo e(__('front.login')); ?></a>
            <?php else: ?>
              <a href="<?php echo e(route('student.account')); ?>" class="btn btn-outline"><?php echo e(__('front.my_account')); ?></a>
              <form action="<?php echo e(route('user.logout')); ?>" method='post'><?php echo csrf_field(); ?>
                <button style='border: 1px solid #0055D2;padding: 8px;' class="btn btn-primary"><?php echo e(__('front.logout')); ?></button>
              </form>
            <?php endif; ?>

            <!-- Language Dropdown -->
            <div class="lang-dropdown">
                <button class="lang-btn"><i class="fas fa-globe"></i></button>
                <div class="lang-menu">
                    <a href="<?php echo e(LaravelLocalization::getLocalizedURL('ar')); ?>">العربية</a>
                    <a href="<?php echo e(LaravelLocalization::getLocalizedURL('en')); ?>">English</a>
                </div>
            </div>

            <!-- Hamburger Button -->
            <button class="menu-toggle" id="menuToggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
</header>
<?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/layouts/header.blade.php ENDPATH**/ ?>