<?php $__env->startSection('title','المجتمع'); ?>

<?php $__env->startSection('content'); ?>
<section class="cmty-page">

    <div class="universities-header-wrapper">
        <div class="universities-header">
            <h2>مجتمع Qdemy</h2>
        </div>
    </div>

  <div class="cmty-feed">

    <article class="cmty-post cmty-post--outlined">
      <header class="cmty-head">
        <img class="cmty-mark" data-src="<?php echo e(asset('assets_front/images/community-logo1.png')); ?>" alt="">
        <div class="cmty-user">
          <div>
            <h4>Anna Cantelli</h4>
          </div>
          <img data-src="<?php echo e(asset('assets_front/images/u1.png')); ?>" alt="">
        </div>
      </header>

      <p class="cmty-text">
        رفعت مواد التربية الثقافية على المنصة لمن يرغب بطلب البطاقة الخاصة بها
        <a href="#">#جديد</a>
      </p>
        <time>8:45 AM · Sep 1, 2022</time>

      <div class="cmty-actions">
        <input class="cmty-input" type="text" placeholder="أضف تعليق">
      <button class="cmty-like"><i class="fa-solid fa-thumbs-up"></i> أعجبني</button>

      </div>
    </article>

    <article class="cmty-post">
      <header class="cmty-head">
        <img class="cmty-mark" data-src="<?php echo e(asset('assets_front/images/community-logo1.png')); ?>" alt="">
        <div class="cmty-user">
          <div>
            <h4>Anna Cantelli</h4>
          </div>
          <img data-src="<?php echo e(asset('assets_front/images/u1.png')); ?>" alt="">
        </div>
      </header>

      <p class="cmty-text">
        رفعت مواد التربية الثقافية على المنصة لمن يرغب بطلب البطاقة الخاصة بها
        <a href="#">#جديد</a>
      </p>
        <time>8:45 AM · Sep 1, 2022</time>

      <div class="cmty-actions">
        <input class="cmty-input" type="text" placeholder="أضف تعليق">
      <button class="cmty-like"><i class="fa-solid fa-thumbs-up"></i> أعجبني</button>
      </div>

      <div class="cmty-comments">
        <div class="cmty-comment">
          <img data-src="<?php echo e(asset('assets_front/images/Profile-picture.png')); ?>" alt="">
          <div>
            <b>Anas Cani</b>
            <p>شكراً لكم</p>
          </div>
        </div>
        <div class="cmty-comment">
          <img data-src="<?php echo e(asset('assets_front/images/Profile-picture.png')); ?>" alt="">
          <div>
            <b>Anas Cani</b>
            <p>متى ستتوفر مادة التربية الإسلامية</p>
          </div>
        </div>
      </div>
        <div class="cmty-more-sec">
            <button class="cmty-more" data-toggle>شاهد جميع التعليقات ←</button>
        </div>
    </article>

  </div>

</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/user/community.blade.php ENDPATH**/ ?>