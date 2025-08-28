<?php $__env->startSection('title','نقاط البيع'); ?>

<?php $__env->startSection('content'); ?>
<section class="sp2-page">
      <div class="universities-header-wrapper">
        <div class="universities-header">
            <h2>نقاط البيع</h2>
        </div>
    </div>

  <div class="sp2-head">
    <div class="sp2-brand">بطاقات Qdemy</div>
    <div class="sp2-sub">بطاقات متوفرة في المكتبات التالية:</div>
  </div>

  <div class="examx-filters">

    <div class="examx-search">
      <input type="text" placeholder="البحث">
      <i class="fa-solid fa-magnifying-glass"></i>
    </div>
  </div>


  <div class="sp2-box">
    <div class="sp2-group is-open">
      <button class="sp2-group-head">
        <i class="fa-solid fa-minus"></i>
        <span>عمان</span>
      </button>
      <div class="sp2-panel">
        <table class="sp2-table">
          <thead>
          <tr>
            <th>اسم المكتبة</th>
            <th>العنوان</th>
            <th>الرقم</th>
            <th>الموقع</th>
          </tr>
          </thead>
          <tbody>
          <?php for($i=0;$i<4;$i++): ?>
            <tr>
              <td>مكتبة إربد</td>
              <td>تلعة العلي</td>
              <td><a href="tel:0797787987">0797787987</a></td>
              <td><a href="#" class="sp2-loc"><i class="fa-solid fa-location-dot"></i> موقع المكتبة</a></td>
            </tr>
          <?php endfor; ?>
          </tbody>
        </table>
      </div>
    </div>

    <?php $__currentLoopData = ['معان','إربد','الطفيلة','العقبة','الكرك','مأدبا','عجلون','جرش']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="sp2-group">
        <button class="sp2-group-head">
          <i class="fa-solid fa-plus"></i>
          <span><?php echo e($gov); ?></span>
        </button>
        <div class="sp2-panel">
          <table class="sp2-table">
            <thead>
            <tr>
              <th>اسم المكتبة</th><th>العنوان</th><th>الرقم</th><th>الموقع</th>
            </tr>
            </thead>
            <tbody>
            <tr>
              <td>مكتبة <?php echo e($gov); ?></td>
              <td>الحي الرئيسي</td>
              <td><a href="tel:0797787987">0797787987</a></td>
              <td><a href="#" class="sp2-loc"><i class="fa-solid fa-location-dot"></i> موقع المكتبة</a></td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>

</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/user/sale-point.blade.php ENDPATH**/ ?>