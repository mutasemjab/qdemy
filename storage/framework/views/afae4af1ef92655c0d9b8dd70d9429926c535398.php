<?php $__env->startSection('title','تواصل معنا'); ?>

<?php $__env->startSection('content'); ?>
<section class="ct-page">
  <div class="ct-wrap">

    <div class="ct-info">
      <h1 class="ct-title">تواصل معنا</h1>
      <p class="ct-desc">اذا لديك أي استفسار لا تتردد بالتواصل معنا متاحين على مدار الـ 24 ساعة لمساعدتك.</p>

      <h3 class="ct-sec">خدمة عملاء</h3>
      <p class="ct-note">فريق الدعم لدينا متاح على مدار الساعة للإجابة على أي استفسارات أو مخاوف لديك.</p>

      <h3 class="ct-sec">المبيعات</h3>
      <p class="ct-note">فريق الدعم لدينا متاح على مدار الساعة للإجابة على أي استفسارات أو مخاوف لديك.</p>

      <h3 class="ct-sec">كن أحد أفراد الشركة</h3>
      <p class="ct-note">للإنضمام لفريقنا قم بتقديم معلوماتك ومؤهلاتك عبر الإيميل التالي:<br>
        <span class="ct-mail">Qdemy@info.com</span>
      </p>
    </div>


    <div class="ct-card">
      <h2 class="ct-head">تواصل معنا</h2>
      <p class="ct-sub">يمكنك التواصل معنا في أي وقت</p>

      <form class="ct-form" action="#" method="post">
        <?php echo csrf_field(); ?>
        <input class="ct-input" type="text" placeholder="الاسم الأول">
        <input class="ct-input" type="text" placeholder="اسم العائلة">
        <input class="ct-input" type="email" placeholder="إيميلك">

        <div class="ct-phone">
          <input class="ct-input ct-number" type="tel" placeholder="رقم الهاتف">
          <select class="ct-code">
            <option value="+962">+962</option>
            <option value="+966">+966</option>
            <option value="+971">+971</option>
            <option value="+20">+20</option>
          </select>
        </div>

        <textarea class="ct-text" rows="5" placeholder="كيف يمكننا مساعدتك؟"></textarea>
        <button type="submit" class="ct-btn">ارسال</button>
      </form>
    </div>

  </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/web/contact.blade.php ENDPATH**/ ?>