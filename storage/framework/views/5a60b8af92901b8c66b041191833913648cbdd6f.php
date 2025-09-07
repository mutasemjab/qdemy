
<?php $__env->startSection('title','حسابي'); ?>
<?php $__env->startSection('content'); ?>
<section class="ud-wrap">

  <aside class="ud-menu">
    <div class="ud-user">
      <img data-src="<?php echo e(asset('assets_front/images/avatar-big.png')); ?>" alt="">
      <div>
        <h3>خالد أحمد</h3>
        <span>khaledahmed@gmail.com</span>
      </div>
    </div>

    <button class="ud-item active" data-target="profile"><i class="fa-regular fa-user"></i><span>حسابي الشخصي</span><i class="fa-solid fa-angle-left"></i></button>
    <button class="ud-item" data-target="settings"><i class="fa-solid fa-gear"></i><span>إعدادات الحساب</span><i class="fa-solid fa-angle-left"></i></button>
    <button class="ud-item" data-target="notifications"><i class="fa-regular fa-bell"></i><span>الإشعارات</span><i class="fa-solid fa-angle-left"></i></button>
    <button class="ud-item" data-target="inbox"><i class="fa-regular fa-comments"></i><span>الرسائل</span><i class="fa-solid fa-angle-left"></i></button>
    <button class="ud-item" data-target="courses"><i class="fa-solid fa-graduation-cap"></i><span>دوراتي</span><i class="fa-solid fa-angle-left"></i></button>
    <button class="ud-item" data-target="schedule"><i class="fa-regular fa-calendar-days"></i><span>الجدول الزمني</span><i class="fa-solid fa-angle-left"></i></button>
    <button class="ud-item" data-target="kids"><i class="fa-solid fa-children"></i><span>الأبناء وإنجازاتهم</span><i class="fa-solid fa-angle-left"></i></button>
    <button class="ud-item" data-target="offers"><i class="fa-solid fa-tags"></i><span>العروض</span><i class="fa-solid fa-angle-left"></i></button>
    <button class="ud-item" data-target="students"><i class="fa-solid fa-user-group"></i><span>الطلاب</span><i class="fa-solid fa-angle-left"></i></button>
    <button class="ud-item" data-target="wallet"><i class="fa-regular fa-wallet"></i><span>المحفظة</span><i class="fa-solid fa-angle-left"></i></button>
    <button class="ud-item" data-target="results"><i class="fa-solid fa-square-poll-vertical"></i><span>نتائج اختباراتي</span><i class="fa-solid fa-angle-left"></i></button>
    <button class="ud-item" data-target="community"><i class="fa-solid fa-magnifying-glass"></i><span>مجتمع Q</span><i class="fa-solid fa-angle-left"></i></button>
    <button class="ud-item" data-target="support"><i class="fa-brands fa-whatsapp"></i><span>الدعم الفني</span><i class="fa-solid fa-angle-left"></i></button>

    <a href="#" class="ud-logout"><i class="fa-solid fa-arrow-left-long"></i><span>تسجيل خروج</span></a>
  </aside>

  <div class="ud-content">

    <div class="ud-panel show" id="profile">
      <div class="ud-title">حسابي الشخصي</div>
      <div class="ud-profile-head">
        <img data-src="<?php echo e(asset('assets_front/images/avatar-round.png')); ?>" class="ud-ava" alt="">
        <div class="ud-name">
          <h2>خالد أحمد<br><span class="g-sub1" >khaledahmed@gmail.com</span></h2>
        </div>
      </div>
      <div class="ud-formlist">
        <div class="ud-row"><div class="ud-key">الاسم</div><div class="ud-val">خالد أحمد</div></div>
        <div class="ud-row"><div class="ud-key">الإيميل</div><div class="ud-val">khaledahmed@gmail.com</div></div>
        <div class="ud-row"><div class="ud-key">الهاتف</div><div class="ud-val">0799879876</div></div>
      </div>
    </div>

    <div class="ud-panel" id="settings">
      <div class="ud-title">إعدادات الحساب</div>
            <div class="ud-profile-head">
                <div class="ud-ava-wrap">
                    <img data-src="<?php echo e(asset('assets_front/images/avatar-round.png')); ?>" class="ud-ava" alt="">
                    <label class="ud-ava-edit">
                        <i class="fa-solid fa-pen"></i>
                        <input type="file" name="avatar" accept="image/*" style="display:none">
                    </label>
                </div>
                <div class="ud-name">
                    <h2>خالد أحمد<br><span class="g-sub1">khaledahmed@gmail.com</span></h2>
                </div>
            </div>

      <div class="ud-edit">
        <label>الاسم<input type="text" value="خالد أحمد"></label>
        <label>الإيميل<input type="email" value="khaledahmed@gmail.com"></label>
        <label>الهاتف<input type="text" value="0799879876"></label>
        <button class="ud-primary">حفظ</button>
      </div>
    </div>

    <div class="ud-panel" id="notifications">
      <div class="ud-title">الإشعارات</div>
      <div class="ud-list">
        <?php for($i=0;$i<5;$i++): ?>
          <div class="ud-note">
            <div class="ud-note-main">
              <b>تم رفع مادة الفيزياء الكونية</b>
              <small>يمكنك شراء البطاقة الآن لمشاهدة المادة</small>
            </div>
            <span class="ud-badge">جديد</span>
          </div>
        <?php endfor; ?>
      </div>
    </div>

    <div class="ud-panel" id="inbox">
      <div class="ud-title">الرسائل</div>
      <div class="ud-inbox">
        <div class="ud-threads">
          <?php $__currentLoopData = [['سالم أحمد',3],['محمد',0],['أحمد',0]]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$n,$c]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <button class="ud-thread<?php echo e($loop->first?' active':''); ?>">
              <div class="ud-thread-user">
                <img data-src="<?php echo e(asset('assets_front/images/uc'.($loop->index+1).'.png')); ?>">
                <div><b><?php echo e($n); ?></b><small>قبل 12 دقيقة</small></div>
              </div>
              <?php if($c): ?><span class="ud-pill"><?php echo e($c); ?></span><?php endif; ?>
            </button>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="ud-chat">
          <div class="ud-chat-flow" id="udChat">
            <div class="msg from"><span>كيفك؟</span></div>
            <div class="msg to"><span>تمام، كيف تحضيراتك للامتحان؟</span></div>
            <div class="msg from"><span>أي عارف على أتم الاستعداد</span></div>
          </div>
          <div class="ud-chat-box">
            <input type="text" placeholder="اكتب رسالة">
            <button class="ud-primary ud-send"><i class="fa-solid fa-paper-plane"></i></button>
          </div>
        </div>
      </div>
    </div>

        <div class="ud-panel" id="courses">
        <div class="ud-title"><?php echo e(__('front.courses')); ?></div>
        <div class="ud-courses">
            <?php $__currentLoopData = $userCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('course',['course'=>$course->id,'slug'=>$course->slug])); ?>" class="ud-course">
                <div class="ud-course-meta">
                <h3><?php echo e($course->title); ?> <br>
                <span class="ud-course-meta-sub">
                    <?php if($course->subject): ?> <?php echo e($course->subject->localized_name); ?> <?php endif; ?>
                    <?php if($course->subject?->semester): ?> - <?php echo e($course->subject?->semester->localized_name); ?> <?php endif; ?>
                </span></h3>
                <div class="ud-course-teacher">
                    <img data-src="<?php echo e($course->teacher?->photo_url); ?>"><span><?php echo e($course->teacher?->name); ?></span>
                </div>
                </div>
                <div class="ud-course-date">
                <small><?php echo e($course->course_user?->created_at); ?></small>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        </div>


    <div class="ud-panel" id="schedule">
      <div class="ud-title"><?php echo e(translate_lang('courses progress')); ?></div>
      <div class="ud-bars">
        <?php $__currentLoopData = $userCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="ud-bar">
            <div class="ud-bar-head"><b><?php echo e($course->title); ?></b>
            <?php if($course->subject): ?> <small> <?php echo e($course->subject->localized_name); ?> </small> <?php endif; ?>
            <?php if($course->subject?->semester): ?> <small> - <?php echo e($course->subject?->semester->localized_name); ?> </small> <?php endif; ?>
           </div>
           <?php if($course->calculateCourseProgress()): ?>
           <div class="ud-bar-track"><span style="width:<?php echo e($course->calculateCourseProgress()); ?>%"></span></div>
           <div class="ud-bar-foot">100%<b><?php echo e(number_format($course->calculateCourseProgress(), 1, '.', '')); ?>% 
               <!-- <?php echo e($course->calculateCourseProgress()); ?>% -->
           </b></div>
           <?php else: ?>
           <div class="ud-bar-track"><span style="width:0%"></span></div>
           <div class="ud-bar-foot">100%<b>0% 
           </b></div>
           <?php endif; ?>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    </div>

    <div class="ud-panel" id="kids">
      <div class="ud-title">الأبناء وإنجازاتهم</div>
      <div class="ud-kid-head">
        <img data-src="<?php echo e(asset('assets_front/images/kid.png')); ?>">
        <div>
          <h2>خالد أحمد<br><span class="g-sub1" >khaledahmed@gmail.com</span></h2>
        </div>
      </div>
      <div class="ud-bars">
        <?php $__currentLoopData = [['اللغة العربية',25],['اللغة الإنجليزية',35],['اللغة العربية',25],['اللغة العربية',25]]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$t,$p]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="ud-bar">
            <div class="ud-bar-head"><b><?php echo e($t); ?></b><small>(الفصل الأول)</small></div>
            <div class="ud-bar-track"><span style="width:<?php echo e($p); ?>%"></span></div>
            <div class="ud-bar-foot">100%<b><?php echo e($p); ?>%</b></div>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    </div>

    <div class="ud-panel" id="offers">
      <div class="ud-title">العروض</div>
      <div class="ud-offers">
        <?php $__currentLoopData = ['البكج الأول','#3488FC','البكج الثاني','#0055D2','البكج الثالث','#0055D2']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php if($loop->odd): ?>
            <?php $title=$v; $color=$loop->iteration==1?'#0055D2':'#3488FC'; ?>
          <?php else: ?>
            <?php $title=$i; $color=$v; ?>
          <?php endif; ?>
          <div class="ud-offer">
            <a class="ud-offer-pill" style="background:<?php echo e($color); ?>"><?php echo e($title); ?></a>
            <div class="ud-offer-body">
              <h3>بكج المادة الواحدة <small>2009</small></h3>
              <div class="ud-tags">
                <span>تاريخ الأردن</span><span>التربية الإسلامية</span><span>اللغة الإنجليزية</span><span>اللغة العربية</span>
              </div>
            </div>
            <div class="ud-offer-price">30 JOD</div>
            <a href="#" class="ud-ghost">شراء/تفعيل البطاقة</a>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    </div>

    <div class="ud-panel" id="students">
      <div class="ud-title">الطلاب <small>50 طالب</small></div>
      <div class="ud-grid">
        <?php for($i=0;$i<16;$i++): ?>
          <a href="#" class="ud-stu">
            <img data-src="<?php echo e(asset('assets_front/images/us'.(($i%4)+1).'.png')); ?>">
            <span>خالد أحمد</span>
          </a>
        <?php endfor; ?>
      </div>
    </div>

    <div class="ud-panel" id="wallet">
      <div class="ud-title">المحفظة</div>
      <div class="ud-wallet">
        <div class="ud-card">
          <small>Balance</small>
          <h2>2,354 JOD</h2>
        </div>
        <div class="ud-trans">
          <?php for($i=0;$i<2;$i++): ?>
            <div class="ud-tr">
              <div class="ud-amt <?php echo e($i? 'pos':'neg'); ?>"><?php echo e($i?'+':'-'); ?>30 JOD</div>
            <div style="display: flex; flex-direction: column;text-align: left;">
            <b>Pay out</b>
            <small>Dec 07, 2024 - 10:45 AM</small>
            <small>RF: 35567867443</small>
            </div>

            </div>
          <?php endfor; ?>
        </div>
      </div>
    </div>

    <div class="ud-panel" id="results">
      <div class="ud-title"><?php echo e(translate_lang('my exam results')); ?></div>
      <div class="ud-results">
        <?php $__currentLoopData = $userExamsResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <a href="<?php echo e(route('course',['course'=>$course->id,'slug'=>$course->slug])); ?>" class="ud-res">
            <b><?php echo e($result->exam->title); ?></b>
            <span class="ud-res-score"><?php echo e($result->score); ?>/<?php echo e($result->exam->total_grade); ?></span>
          </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    </div>

    <div class="ud-panel" id="community">
      <div class="ud-title">مجتمع Q</div>
      <div class="ud-community">
        <div class="ud-postbox">
          <div class="ud-post-head">
            <img data-src="<?php echo e(asset('assets_front/images/avatar-round.png')); ?>"><b>خالد أحمد</b>
          </div>
          <textarea placeholder="اكتب سؤالك أو منشورك هنا"></textarea>
          <div class="ud-post-actions">
            <button class="ud-primary">Post</button>
          </div>
        </div>

        <div class="ud-feed">
          <div class="ud-post">
            <div class="ud-post-top">
              <div class="ud-post-user">
                <img data-src="<?php echo e(asset('assets_front/images/avatar-round.png')); ?>">
                <div><b>خالد أحمد</b><br><small>8:45 AM · Sep 1, 2022</small></div>
              </div>
              <img data-src="<?php echo e(asset('assets_front/images/qmark.png')); ?>" class="ud-q">
            </div>
            <p>رفعت مواد التربية الثقافية على المنصة لمن يرغب يطلب البطاقة الخاصة بها.</p>
          </div>
        </div>
      </div>
    </div>

    <div class="ud-panel" id="support">
      <div class="ud-title">الدعم الفني</div>
      <div class="ud-support">
        <p>لأي مشاكل تقنية تواجهك يرجى مراسلتنا عبر الواتساب على الرقم التالي</p>
        <a href="https://wa.me/09898756567876" class="ud-wh"><i class="fa-brands fa-whatsapp"></i> 09898756567876</a>
      </div>
    </div>

  </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/web/account.blade.php ENDPATH**/ ?>