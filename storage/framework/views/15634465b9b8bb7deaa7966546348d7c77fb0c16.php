
<?php $__env->startSection('title', __('front.Contact Us')); ?>

<?php $__env->startSection('content'); ?>
<section class="ct-page">
  <div class="ct-wrap">

    <div class="ct-info">
      <h1 class="ct-title"><?php echo e(__('front.Contact Us')); ?></h1>
      <p class="ct-desc"><?php echo e(__('front.If you have any inquiry, do not hesitate to contact us. We are available 24/7 to help you.')); ?></p>

      <h3 class="ct-sec"><?php echo e(__('front.Customer Service')); ?></h3>
      <p class="ct-note"><?php echo e(__('front.Our support team is available around the clock to answer any questions or concerns you have.')); ?></p>

      <h3 class="ct-sec"><?php echo e(__('front.Sales')); ?></h3>
      <p class="ct-note"><?php echo e(__('front.Our support team is available around the clock to answer any questions or concerns you have.')); ?></p>

      <h3 class="ct-sec"><?php echo e(__('front.Join Our Team')); ?></h3>
      <p class="ct-note"><?php echo e(__('front.To join our team, submit your information and qualifications via the following email:')); ?><br>
        <span class="ct-mail">Qdemy@info.com</span>
      </p>
    </div>

    <div class="ct-card">
      <h2 class="ct-head"><?php echo e(__('front.Contact Us')); ?></h2>
      <p class="ct-sub"><?php echo e(__('front.You can contact us anytime')); ?></p>

      
      <?php if(session('success')): ?>
        <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
          <?php echo e(session('success')); ?>

        </div>
      <?php endif; ?>

      
      <?php if(session('error')): ?>
        <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
          <?php echo e(session('error')); ?>

        </div>
      <?php endif; ?>

      
      <?php if($errors->any()): ?>
        <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
          <ul style="margin: 0; padding-left: 20px;">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </ul>
        </div>
      <?php endif; ?>

      <form class="ct-form" action="<?php echo e(route('contacts.store')); ?>" method="post">
        <?php echo csrf_field(); ?>
        <input class="ct-input" 
               type="text" 
               name="name" 
               placeholder="<?php echo e(__('front.First Name')); ?>" 
               value="<?php echo e(old('name')); ?>" 
               required>

        <input class="ct-input" 
               type="email" 
               name="email" 
               placeholder="<?php echo e(__('front.Your Email')); ?>" 
               value="<?php echo e(old('email')); ?>">

        <div class="ct-phone">
          <input class="ct-input ct-number" 
                 type="tel" 
                 name="phone" 
                 placeholder="<?php echo e(__('front.Phone Number')); ?>" 
                 value="<?php echo e(old('phone')); ?>">
          
          <select class="ct-code" name="country_code">
            <option value="+962" <?php echo e(old('country_code') == '+962' ? 'selected' : ''); ?>>+962</option>
            <option value="+966" <?php echo e(old('country_code') == '+966' ? 'selected' : ''); ?>>+966</option>
            <option value="+971" <?php echo e(old('country_code') == '+971' ? 'selected' : ''); ?>>+971</option>
            <option value="+20" <?php echo e(old('country_code') == '+20' ? 'selected' : ''); ?>>+20</option>
          </select>
        </div>

        <textarea class="ct-text" 
                  rows="5" 
                  name="message" 
                  placeholder="<?php echo e(__('front.How can we help you?')); ?>" 
                  required><?php echo e(old('message')); ?></textarea>

        <button type="submit" class="ct-btn"><?php echo e(__('front.Send')); ?></button>
      </form>
    </div>

  </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\qdemy\resources\views/web/contact.blade.php ENDPATH**/ ?>