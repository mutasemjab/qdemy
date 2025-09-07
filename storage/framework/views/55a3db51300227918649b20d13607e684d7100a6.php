<div class="ud-panel" id="inbox">
  <div class="ud-title"><?php echo e(__('panel.messages')); ?></div>
  <div class="ud-inbox">
    <div class="ud-threads">
      <?php $__currentLoopData = [['سالم أحمد',3],['محمد علي',0],['فاطمة أحمد',1]]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$n,$c]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <button class="ud-thread<?php echo e($loop->first?' active':''); ?>">
          <div class="ud-thread-user">
            <img data-src="<?php echo e(asset('assets_front/images/uc'.($loop->index+1).'.png')); ?>">
            <div><b><?php echo e($n); ?></b><small>قبل <?php echo e(rand(5, 60)); ?> دقيقة</small></div>
          </div>
          <?php if($c): ?><span class="ud-pill"><?php echo e($c); ?></span><?php endif; ?>
        </button>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <div class="ud-chat">
      <div class="ud-chat-flow" id="udChat">
        <div class="msg from"><span>مرحباً، كيف حالك؟</span></div>
        <div class="msg to"><span>بخير والحمد لله، كيف الدراسة؟</span></div>
        <div class="msg from"><span>جيدة، أحتاج مساعدة في الرياضيات</span></div>
        <div class="msg to"><span>طبعاً، ما المشكلة تحديداً؟</span></div>
      </div>
      <div class="ud-chat-box">
        <input type="text" placeholder="اكتب رسالة">
        <button class="ud-primary ud-send"><i class="fa-solid fa-paper-plane"></i></button>
      </div>
    </div>
  </div>
</div><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/panel/common/inbox.blade.php ENDPATH**/ ?>