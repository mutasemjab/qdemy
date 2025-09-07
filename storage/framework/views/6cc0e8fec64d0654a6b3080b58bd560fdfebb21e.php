

<?php $__env->startSection('title', 'الرئيسية'); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('web.alert-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <section class="hero">

    <div class="hero-container">

        <!-- Right Side: Shapes with links -->
        <div class="hero-buttons-wrapper">

            <!-- العمود اليمين -->
            <div class="hero-column right-column">
                <a href="<?php echo e(route('courses')); ?>" class="blob-btn blob-btn-1" style="background-image: url('<?php echo e(asset('images/blob2.png')); ?>');">
                    <i class="fas fa-graduation-cap"></i>
                    <span>دورات</span>
                </a>

                <a href="<?php echo e(route('user.register')); ?>" class="blob-btn blob-btn-2" style="background-image: url('<?php echo e(asset('images/blob3.png')); ?>');">
                    <i class="fas fa-user-plus"></i>
                    <span>تسجيل حساب</span>
                </a>
            </div>

            <!-- العمود الشمال -->
            <div class="hero-column center-column">
                <a href="<?php echo e(route('card-order')); ?>" class="blob-btn blob-btn-3" style="background-image: url('<?php echo e(asset('images/blob1.png')); ?>');">
                    <i class="fas fa-id-card"></i>
                    <span>لطلب بطاقة<br>من هنا</span>
                </a>
            </div>

        </div>



        <!-- Left Side: Person Image + Background Shape -->
        <div class="hero-left">
            <div class="hero-person">
                <img data-src="<?php echo e(asset('assets_front/images/home/person.png')); ?>" alt="Person" class="person-img">
                <img data-src="<?php echo e(asset('assets_front/images/home/blue-wave.png')); ?>" alt="Background Shape" class="bg-wave">
            </div>
        </div>
    </div>

    <!-- Bottom Cards -->
    <div class="hero-cards">
        <a href="<?php echo e(route('tawjihi-programm')); ?>" class="hero-card" style="background-image: url('<?php echo e(asset('images/card1.png')); ?>');">
            <p class="card-t" >برنامج التوجيهي والثانوي</p>
        </a>
        <a href="<?php echo e(route('grades_basic-programm')); ?>" class="hero-card" style="background-image: url('<?php echo e(asset('images/card2.png')); ?>');">
            <p class="card-t" >برنامج الصفوف الأساسية</p>
        </a>
        <a href="<?php echo e(route('universities-programm')); ?>" class="hero-card" style="background-image: url('<?php echo e(asset('images/card3.png')); ?>');">
            <p class="card-t" >برنامج الجامعات والكليات</p>
        </a>
        <a href="<?php echo e(route('international-programms')); ?>" class="hero-card" style="background-image: url('<?php echo e(asset('images/card4.png')); ?>');">
            <p class="card-t" >برنامج دولي</p>
        </a>
    </div>

</section>

<section class="features">
    <h2><?php echo e(__('front.What Makes QDEMY Special')); ?></h2>
    
    <div class="features-wrapper">
        <div class="features-box">
            <?php $__currentLoopData = $specialQdemies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $special): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="feature-item">
                    <?php echo e(app()->getLocale() == 'ar' ? $special->title_ar : $special->title_en); ?>

                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>

<section class="services">
    <h2><?php echo e(__('front.QDEMY Services')); ?></h2>
    <div class="services-box">
        <a href="<?php echo e(route('community')); ?>" class="service-btn dark"><?php echo e(__('front.QDEMY Community')); ?></a>
        <a href="<?php echo e(route('exam.index')); ?>" class="service-btn light"><?php echo e(__('front.Electronic Exams')); ?></a>
        <a href="<?php echo e(route('courses')); ?>" class="service-btn dark"><?php echo e(__('front.Courses')); ?></a>
        <a href="<?php echo e(route('sale-point')); ?>" class="service-btn light"><?php echo e(__('front.Sale Points')); ?></a>
        <a href="<?php echo e(route('ministerialQuestions.index')); ?>" class="service-btn dark"><?php echo e(__('front.Ministry Years Questions')); ?></a>
        <a href="<?php echo e(route('bankQuestions.index')); ?>" class="service-btn light"><?php echo e(__('front.Question Bank')); ?> <small>(<?php echo e(__('front.Papers and Summaries')); ?>)</small></a>
    </div>
</section>

<section class="social-media">
    <h2><?php echo e(__('front.Social Media')); ?></h2>

    <?php $videoIndex = 0; ?>
    <?php for($i = 0; $i < min(2, ceil($socialMediaVideos->count() / 2)); $i++): ?>
        <div class="media-row">
            <?php if($videoIndex < $socialMediaVideos->count()): ?>
                <div class="media-video" data-video="<?php echo e($socialMediaVideos[$videoIndex]->video); ?>">
                    <img data-src="<?php echo e(asset('assets_front/images/videobg.jpg')); ?>" alt="">
                    <div class="overlay">
                        <i class="fas fa-play"></i>
                    </div>
                </div>
                <?php $videoIndex++; ?>
            <?php endif; ?>
            
            <div class="media-image">
                <img data-src="<?php echo e(asset('assets_front/images/social1.jpg')); ?>" alt="">
            </div>
            
            <?php if($videoIndex < $socialMediaVideos->count()): ?>
                <div class="media-video" data-video="<?php echo e($socialMediaVideos[$videoIndex]->video); ?>">
                    <img data-src="<?php echo e(asset('assets_front/images/videobg.jpg')); ?>" alt="">
                    <div class="overlay">
                        <i class="fas fa-play"></i>
                    </div>
                </div>
                <?php $videoIndex++; ?>
            <?php endif; ?>
        </div>
    <?php endfor; ?>

    <div class="video-popup">
        <div class="popup-content">
            <span class="close-btn">&times;</span>
            <iframe data-src="" frameborder="0" allowfullscreen></iframe>
        </div>
    </div>

    <div class="image-popup">
        <div class="popup-content">
            <span class="close-btn">&times;</span>
            <img data-src="" alt="">
        </div>
    </div>
</section>

<section class="teachers-carousel">
    <h2><?php echo e(__('front.Teachers')); ?></h2>
    <div class="carousel-container">
        <button class="carousel-btn prev">&#10094;</button>
        <div class="carousel-track">
            <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="carousel-slide">
                    <img data-src="<?php echo e($teacher->photo ? asset('assets/admin/uploads/' . $teacher->photo) : asset('assets_front/images/teacher1.png')); ?>" alt="<?php echo e($teacher->name); ?>">
                
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <button class="carousel-btn next">&#10095;</button>
    </div>
</section>

<section class="stats-section">
    <div class="stats-overlay">
        <div class="stat-item">
            <span class="stat-number"><?php echo e($settings->number_of_course ?? '+20 Thousand'); ?></span>
            <p><?php echo e(__('front.Course')); ?></p>
        </div>
        <div class="divider"></div>
        <div class="stat-item">
            <span class="stat-number"><?php echo e($settings->number_of_teacher ?? '+1 Thousand'); ?></span>
            <p><?php echo e(__('front.Teacher')); ?></p>
        </div>
        <div class="divider"></div>
        <div class="stat-item">
            <span class="stat-number"><?php echo e($settings->number_of_viewing_hour ?? '+2 Million'); ?></span>
            <p><?php echo e(__('front.Viewing hour')); ?></p>
        </div>
        <div class="divider"></div>
        <div class="stat-item">
            <span class="stat-number"><?php echo e($settings->number_of_students ?? '+3 Million'); ?></span>
            <p><?php echo e(__('front.Student')); ?></p>
        </div>
    </div>
</section>

<section class="faq-section">
    <h2><?php echo e(__('front.Most Frequently Asked Questions')); ?></h2>
    <div class="faq-section-link">
        <a href="#"><?php echo e(__('front.See More')); ?> ←</a>
    </div>
    <div class="faq-container">
        <?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="faq-card <?php echo e($index == 1 ? 'top-arrow faq-card-custom' : ''); ?>">
                <div class="icon">
                    <img data-src="../assets_front/images/ban-icon.png" alt="">
                </div>
                <h3><?php echo e($faq->question); ?></h3>
                <p><?php echo e($faq->answer); ?></p>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</section>

<section class="rvx" dir="rtl">
    <h2 class="rvx-title"><?php echo e(__('front.Some Students Reviews')); ?></h2>

    <div class="rvx-wrap">
        <!-- Blue side panel -->
        <div class="rvx-stage">
            <div class="rvx-panel">
                <h3 class="rvx-panel-title"><?php echo e(__('front.Our Students Reviews on Their Platform')); ?></h3>
                <img class="rvx-panel-logo" data-src="<?php echo e(asset('assets_front/images/logo-white.png')); ?>" alt="Qdemy">
                <p class="rvx-panel-sub"></p>
                <a href="#" class="rvx-panel-link"><?php echo e(__('front.Read More')); ?> ←</a>
            </div>

            <!-- Carousel -->
            <div class="rvx-window">
                <div class="rvx-track">
                    <?php $__currentLoopData = $opinionStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $opinion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <article class="rvx-card <?php echo e($index % 2 == 0 ? 'rvx-card--dark' : 'rvx-card--blue'); ?>">
                            <img class="rvx-card-img" data-src="<?php echo e($opinion->photo ? asset('assets/admin/uploads/' . $opinion->photo) : asset('assets_front/images/social1.jpg')); ?>" alt="">
                            <div class="rvx-card-body">
                                <h4 class="rvx-card-title"><?php echo e($opinion->title); ?></h4>
                                <p class="rvx-card-text"><?php echo e($opinion->description); ?></p>
                                <div class="rvx-card-meta">
                                    <span class="rvx-card-name"><?php echo e($opinion->name); ?></span>
                                    <span class="rvx-card-stars" aria-label="<?php echo e($opinion->number_of_star); ?> من 5">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <?php echo e($i <= $opinion->number_of_star ? '★' : '☆'); ?>

                                        <?php endfor; ?>
                                    </span>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Controls -->
            <div class="rvx-controls">
                <button class="rvx-arrow rvx-prev" aria-label="<?php echo e(__('front.Previous')); ?>">◀</button>
                <div class="rvx-dots"></div>
                <button class="rvx-arrow rvx-next" aria-label="<?php echo e(__('front.Next')); ?>">▶</button>
            </div>
        </div>
    </div>
</section>

<section class="blog-slider" dir="rtl">
    <h2 class="blog-slider__title"><?php echo e(__('front.Blogs')); ?></h2>

    <button class="blog-slider__arrow blog-slider__arrow--prev" aria-label="<?php echo e(__('front.Previous')); ?>" disabled>
        <span>&rsaquo;</span>
    </button>
    <button class="blog-slider__arrow blog-slider__arrow--next" aria-label="<?php echo e(__('front.Next')); ?>">
        <span>&lsaquo;</span>
    </button>

    <div class="blog-slider__viewport">
        <div class="blog-slider__track">
            <?php $__currentLoopData = $blogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <article class="blog-card">
                    <a href="#">
                        <div class="blog-card__image">
                            <img data-src="<?php echo e($blog->photo ? asset('assets/admin/uploads/' . $blog->photo) : asset('assets_front/images/blog1.png')); ?>" alt="">
                        </div>
                        <h3 class="blog-card__title">
                            <?php echo e(app()->getLocale() == 'ar' ? $blog->title_ar : $blog->title_en); ?>

                        </h3>
                        <p class="blog-card__excerpt">
                            <?php echo e(app()->getLocale() == 'ar' ? Str::limit($blog->description_ar, 100) : Str::limit($blog->description_en, 100)); ?>

                        </p>
                    </a>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <div class="blog-slider__dots" role="tablist" aria-label="<?php echo e(__('front.Slider Indicator')); ?>"></div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/web/home.blade.php ENDPATH**/ ?>