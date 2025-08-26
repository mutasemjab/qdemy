<?php $__env->startSection('title', 'الرئيسية'); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('user.alert-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
    <h2>ما يميز QDEMY</h2>
    <div class="features-wrapper">
        <div class="features-box">
            <div class="feature-item">برنامج التوجيهي والثانوي</div>
            <div class="feature-item">برنامج التوجيهي والثانوي</div>
            <div class="feature-item">برنامج التوجيهي والثانوي</div>
        </div>

    </div>
</section>

<section class="services">
    <h2>خدمات QDEMY</h2>
    <div class="services-box">
        <a href="<?php echo e(route('community')); ?>" class="service-btn dark">مجتمع QDEMY</a>
        <a href="<?php echo e(route('e-exam')); ?>" class="service-btn light">امتحانات الكترونية</a>
        <a href="<?php echo e(route('courses')); ?>" class="service-btn dark">الدورات</a>
        <a href="<?php echo e(route('sale-point')); ?>" class="service-btn light">نقاط البيع</a>
        <a href="<?php echo e(route('ex-questions')); ?>" class="service-btn dark">أسئلة سنوات وزارية</a>
        <a href="<?php echo e(route('bank-questions')); ?>" class="service-btn light">بنك أسئلة <small>(أوراق وملخصات)</small></a>
    </div>
</section>

<section class="social-media">
    <h2>سوشال ميديا</h2>

    <div class="media-row">
        <div class="media-video" data-video="https://www.youtube.com/embed/VIDEO_ID">
            <img data-src="<?php echo e(asset('assets_front/images/videobg.jpg')); ?>" alt="">
            <div class="overlay">
                <i class="fas fa-play"></i>
            </div>
        </div>
        <div class="media-image">
            <img data-src="<?php echo e(asset('assets_front/images/social1.jpg')); ?>" alt="">
        </div>
    </div>

    <div class="media-row">
        <div class="media-image">
            <img data-src="<?php echo e(asset('assets_front/images/social1.jpg')); ?>" alt="">
        </div>
        <div class="media-video" data-video="https://www.youtube.com/embed/VIDEO_ID2">
            <img data-src="<?php echo e(asset('assets_front/images/videobg.jpg')); ?>" alt="">
            <div class="overlay">
                <i class="fas fa-play"></i>
            </div>
        </div>
    </div>

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
    <h2>المعلمون</h2>
    <div class="carousel-container">
        <button class="carousel-btn prev">&#10094;</button>
        <div class="carousel-track">
            <div class="carousel-slide">
                <img data-src="<?php echo e(asset('assets_front/images/teacher1.png')); ?>" alt="">
            </div>
            <div class="carousel-slide">
                <img data-src="<?php echo e(asset('assets_front/images/teacher1.png')); ?>" alt="">
            </div>
            <div class="carousel-slide">
                <img data-src="<?php echo e(asset('assets_front/images/teacher1.png')); ?>" alt="">
            </div>
            <div class="carousel-slide">
                <img data-src="<?php echo e(asset('assets_front/images/teacher1.png')); ?>" alt="">
            </div>
            <div class="carousel-slide">
                <img data-src="<?php echo e(asset('assets_front/images/teacher1.png')); ?>" alt="">
            </div>
        </div>
        <button class="carousel-btn next">&#10095;</button>
    </div>
</section>

<section class="stats-section">
    <div class="stats-overlay">
        <div class="stat-item">
            <span class="stat-number">+20 Thousand</span>
            <p>Course</p>
        </div>
        <div class="divider"></div>
        <div class="stat-item">
            <span class="stat-number">+1 Thousand</span>
            <p>Teacher</p>
        </div>
        <div class="divider"></div>
        <div class="stat-item">
            <span class="stat-number">+2 Million</span>
            <p>Viewing hour</p>
        </div>
        <div class="divider"></div>
        <div class="stat-item">
            <span class="stat-number">+3 Million</span>
            <p>Student</p>
        </div>
    </div>
</section>

<section class="faq-section">
        <h2>الأسئلة الأكثر شيوعاً</h2>
        <div class="faq-section-link" >
        <a href="#">شاهد المزيد ←</a>
        </div>
    <div class="faq-container">
        <div class="faq-card">
            <div class="icon">
                <img data-src="../assets_front/images/ban-icon.png" alt="">
            </div>
            <h3>What is your cancellation policy?</h3>
            <p>You can now cancel an order when it is in packed/shipped status. Any amount paid will be credited into the same payment mode using which the payment was made</p>
        </div>
        <div class="faq-card top-arrow faq-card-custom">
            <div class="icon">
                <img data-src="../assets_front/images/ban-icon.png" alt="">
            </div>
            <h3>What is your cancellation policy?</h3>
            <p>You can now cancel an order when it is in packed/shipped status. Any amount paid will be credited into the same payment mode using which the payment was made</p>
        </div>
        <div class="faq-card">
            <div class="icon">
                <img data-src="../assets_front/images/ban-icon.png" alt="">
            </div>
            <h3>What is your cancellation policy?</h3>
            <p>You can now cancel an order when it is in packed/shipped status. Any amount paid will be credited into the same payment mode using which the payment was made</p>
        </div>
    </div>
</section>
<section class="rvx" dir="rtl">
  <h2 class="rvx-title">آراء بعض الطلاب</h2>

  <div class="rvx-wrap">
    <!-- Blue side panel -->
    <div class="rvx-stage">
      <div class="rvx-panel">
        <h3 class="rvx-panel-title">آراء طلابنا في منصتهم</h3>
        <img class="rvx-panel-logo" data-src="<?php echo e(asset('assets_front/images/logo-white.png')); ?>" alt="Qdemy">
        <p class="rvx-panel-sub"></p>
        <a href="#" class="rvx-panel-link">اقرأ المزيد ←</a>
      </div>

      <!-- Carousel -->
      <div class="rvx-window">
        <div class="rvx-track">
          <!-- Slide 1 -->
          <article class="rvx-card rvx-card--dark">
            <img class="rvx-card-img" data-src="<?php echo e(asset('assets_front/images/social1.jpg')); ?>" alt="">
            <div class="rvx-card-body">
              <h4 class="rvx-card-title">Lorem ipsum dolor sit amet</h4>
              <p class="rvx-card-text">
                Lorem ipsum dolor sit amet consectetur. Aliquet scelerisque urna cum adipiscing sollicitudin nulla.
                Nibh mi viverra fermentum ultrices dolor vitae nascetur vulputate.
              </p>
              <div class="rvx-card-meta">
                <span class="rvx-card-name">Muhamma ahmad</span>
                <span class="rvx-card-stars" aria-label="4 من 5">★★★★☆</span>
              </div>
            </div>
          </article>

          <!-- Slide 2 -->
          <article class="rvx-card rvx-card--blue">
            <img class="rvx-card-img" data-src="<?php echo e(asset('assets_front/images/social1.jpg')); ?>" alt="">
            <div class="rvx-card-body">
              <h4 class="rvx-card-title">Lorem ipsum dolor sit amet</h4>
              <p class="rvx-card-text">
                Lorem ipsum dolor sit amet consectetur. Aliquet scelerisque urna cum adipiscing sollicitudin nulla.
                Nibh mi viverra fermentum ultrices dolor vitae nascetur vulputate.
              </p>
              <div class="rvx-card-meta">
                <span class="rvx-card-name">Muhamma ahmad</span>
                <span class="rvx-card-stars" aria-label="3 من 5">★★★☆☆</span>
              </div>
            </div>
          </article>

          <!-- Slide 3 (مثال إضافي) -->
          <article class="rvx-card rvx-card--dark">
            <img class="rvx-card-img" data-src="<?php echo e(asset('assets_front/images/social1.jpg')); ?>" alt="">
            <div class="rvx-card-body">
              <h4 class="rvx-card-title">Lorem ipsum dolor sit amet</h4>
              <p class="rvx-card-text">
                Lorem ipsum dolor sit amet consectetur. Aliquet scelerisque urna cum adipiscing sollicitudin nulla.
                Nibh mi viverra fermentum ultrices dolor vitae nascetur vulputate.
              </p>
              <div class="rvx-card-meta">
                <span class="rvx-card-name">Muhamma ahmad</span>
                <span class="rvx-card-stars" aria-label="5 من 5">★★★★★</span>
              </div>
            </div>
          </article>
        </div>
      </div>

      <!-- Controls -->
      <div class="rvx-controls">
        <button class="rvx-arrow rvx-prev" aria-label="السابق">◀</button>
        <div class="rvx-dots"></div>
        <button class="rvx-arrow rvx-next" aria-label="التالي">▶</button>
      </div>
    </div>
  </div>
</section>


<section class="blog-slider" dir="rtl">
  <h2 class="blog-slider__title">مدونات</h2>

  <button class="blog-slider__arrow blog-slider__arrow--prev" aria-label="السابق" disabled>
    <span>&rsaquo;</span>
  </button>
  <button class="blog-slider__arrow blog-slider__arrow--next" aria-label="التالي">
    <span>&lsaquo;</span>
  </button>

  <div class="blog-slider__viewport">
    <div class="blog-slider__track">

      <?php
        $items = $posts ?? collect([
          ['img'=>asset('assets_front/images/blog1.png'),'title'=>'Fantom XRP celo gala flow siacoin Livepeer amp looping klaytn ox.','excerpt'=>'TerraUSD stacks chainlink solana decentraland klaytn. Helium ren kava zcash decentraland'],
          ['img'=>asset('assets_front/images/blog2.png'),'title'=>'Fantom XRP celo gala flow siacoin Livepeer amp looping klaytn ox.','excerpt'=>'TerraUSD stacks chainlink solana decentraland klaytn. Helium ren kava zcash decentraland'],
          ['img'=>asset('assets_front/images/blog3.png'),'title'=>'Fantom XRP celo gala flow siacoin Livepeer amp looping klaytn ox.','excerpt'=>'TerraUSD stacks chainlink solana decentraland klaytn. Helium ren kava zcash decentraland'],
          ['img'=>asset('assets_front/images/blog1.png'),'title'=>'Fantom XRP celo gala flow siacoin Livepeer amp looping klaytn ox.','excerpt'=>'TerraUSD stacks chainlink solana decentraland klaytn. Helium ren kava zcash decentraland'],
        ]);
      ?>

      <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <article class="blog-card">
          <a href="#">
            <div class="blog-card__image">
              <img data-src="<?php echo e($p['img']); ?>" alt="">
            </div>
            <h3 class="blog-card__title"><?php echo e($p['title']); ?></h3>
            <p class="blog-card__excerpt"><?php echo e($p['excerpt']); ?></p>
          </a>
        </article>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </div>
  </div>

  <div class="blog-slider__dots" role="tablist" aria-label="مؤشر السلايدر"></div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/user/home.blade.php ENDPATH**/ ?>