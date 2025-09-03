<?php $__env->startSection('title', translate_lang('cart')); ?>

<?php $__env->startSection('content'); ?>
<section class="checkout-wrapper">

    <!-- Cart Section -->
    <div class="checkout-box">
        <h2 class="checkout-heading"><?php echo e(translate_lang('cart')); ?></h2>
        <div class="checkout-row header">
            <span class="checkout-col"><?php echo e(translate_lang('course')); ?></span>
            <span class="checkout-col"><?php echo e(translate_lang('price')); ?></span>
        </div>

        <?php $total = 0; ?>
        <?php if($courses && $courses->count()): ?>
        <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="checkout-row" id='cart_row_<?php echo e($course->id); ?>'>
                <div class="checkout-course">
                    <img data-src="<?php echo e(asset('assets_front/images/course-img2.png')); ?>" alt="<?php echo e($course->title); ?>">
                    <div class="checkout-course-info">
                        <a class='text-decoration-none' href="<?php echo e(route('course',['course'=>$course->id,'slug'=>$course->slug])); ?>"><h3><?php echo e($course->category?->localized_name); ?></h3></a>
                        <p><?php echo e($course->title); ?></p>
                    </div>
                </div>
                <span class="checkout-price" data-course-price='<?php echo e($course->selling_price); ?>'><?php echo e($course->selling_price); ?> <?php echo e(CURRENCY); ?></span>
                <button class="delete-course" data-course-id="<?php echo e($course->id); ?>"><?php echo e(translate_lang('delete')); ?></button>
            </div>
            <?php $total += $course->selling_price; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>

        <?php if($package && $package_courses): ?>
        <?php $__currentLoopData = $package_courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package_course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="checkout-row" id='package_row_<?php echo e($package_course->id); ?>'>
                <div class="checkout-course">
                    <img data-src="<?php echo e(asset('assets_front/images/course-img2.png')); ?>" alt="<?php echo e($package_course->title); ?>">
                    <div class="checkout-course-info">
                        <a class='text-decoration-none' href="<?php echo e(route('course',['course'=>$package_course->id,'slug'=>$package_course->slug])); ?>"><h3><?php echo e($package_course->category?->localized_name); ?></h3></a>
                        <p><?php echo e($package['package_name']); ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <span   class="package-checkout-price"  data-package-price="<?php echo e($package['package_price']); ?>"><?php echo e($package['package_price']); ?> <?php echo e(CURRENCY); ?></span>
            <button class="delete-package" data-package-id="<?php echo e($package['package_id']); ?>"><?php echo e(translate_lang('delete')); ?></button>
        <?php endif; ?>

    </div>

    <?php if($courses && $courses->count()): ?>
    <!-- Total Section -->
    <div class="checkout-total-box">
        <h2 class="checkout-heading"><?php echo e(translate_lang('final_cost')); ?></h2>
        <div class="total-line">
            <span><?php echo e(translate_lang('sub_total')); ?></span>
            <span id='courses_sub_total'><?php echo e($total); ?> <?php echo e(CURRENCY); ?></span>
        </div>
        <div class="total-line">
            <span><?php echo e(translate_lang('total')); ?></span>
            <span id='courses_total'><?php echo e($total); ?> <?php echo e(CURRENCY); ?></span>
        </div>
    </div>
    <?php endif; ?>

    <div class="checkout-total-box">
        <div class="lesson-card-activation">
            <h3><?php echo e(translate_lang('card_qdemy')); ?></h3>
            <p><?php echo e(translate_lang('enter_card_qdemy')); ?></p>
            <input class="lesson-card-activation input" type="text" placeholder="<?php echo e(translate_lang('enter_card_here')); ?>">
            <button class="lesson-card-activation button"><?php echo e(translate_lang('activate_card')); ?></button>
        </div>
    </div>

    <!-- Payment Section -->
    <div class="checkout-payment-box">
        <h2 class="checkout-heading">طرق الدفع</h2>
        <div class="payment-options">
            <button class="payment-option active">VISA</button>
            <button class="payment-option">Cash</button>
        </div>

        <form class="payment-form">
            <div class="payment-field">
                <label>Cardholder's Name</label>
                <input type="text" placeholder="Seen on your card">
            </div>
            <div class="payment-field">
                <label>Card Number</label>
                <input type="text" placeholder="Seen on your card">
            </div>
            <div class="payment-double">
                <div class="payment-field">
                    <label>Expiry</label>
                    <input type="text" placeholder="MM/YY">
                </div>
                <div class="payment-field">
                    <label>CVC</label>
                    <input type="text" placeholder="***">
                </div>
            </div>
            <button type="submit" class="payment-submit">الدفع</button>
        </form>
    </div>

    <!-- نافذة منبثقة -->
    <div id="enrollment-modal" class="messages modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3> <i class="fa fa-check"></i> </h3>
            <h3><?php echo e(translate_lang('تم تفعيل البطاقة بنجاح وإضافتك للكورسات!')); ?></h3>
        </div>
    </div>
    <div id="delete-course-modal" class="messages modal">
        <div class="modal-content">
            <span class="delete-course-modal-close">&times;</span>
            <h3> <i class="fa fa-check"></i> </h3>
            <h3><?php echo e(translate_lang('تم حذف الكورس من عربة التسوق!')); ?></h3>
        </div>
    </div>

</section>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>

document.addEventListener('DOMContentLoaded', function () {

    const modal    = document.getElementById('enrollment-modal');
    const deleteCourseModal    = document.getElementById('delete-course-modal');
    const closeBtn = document.querySelector('.close');
    const DeleteCloseDeleteBtn = document.querySelector('.delete-course-modal-close');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    // تفعيل البطاقة
    const activateButton = document.querySelector('.lesson-card-activation button');
    const cardInput = document.querySelector('.lesson-card-activation input');

    activateButton.addEventListener('click', function (e) {
        e.preventDefault();

        const cardNumber = cardInput.value.trim();
        if (!cardNumber) {
            alert('من فضلك أدخل رقم البطاقة');
            return;
        }

        // تعطيل الزر أثناء المعالجة
        activateButton.disabled = true;
        activateButton.textContent = 'جارِ التفعيل...';

        fetch('<?php echo e(route("payment.card")); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ card_number: cardNumber })
        })
        .then(response => {
            if (!response.ok) throw new Error('Network error');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                modal.style.display = 'flex';
                // alert('تم تفعيل البطاقة بنجاح وإضافتك للكورسات!');
                location.reload();
            } else {
                //  modal.style.display = 'flex';
                alert('خطأ: ' + data.message);
            }
        })
        .catch(error => {
            //  modal.style.display = 'flex';
            alert('حدث خطأ أثناء الاتصال بالخادم');
        })
        .finally(() => {
            activateButton.disabled = false;
            activateButton.textContent = 'تفعيل البطاقة';
        });
    });

    // حذف الباقة من السلة
    const deletePackageButtons = document.querySelectorAll('.delete-package');
    deletePackageButtons.forEach(button => {
        button.addEventListener('click', function () {
            const packageId = this.getAttribute('data-package-id');
            const parentRow = document.getElementById('package_row_'+packageId);

            // تعطيل الزر أثناء المعالجة
            this.disabled = true;
            this.textContent = 'جاري الحذف...';

            fetch('<?php echo e(route("remove.package")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ package_id: packageId })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    deleteCourseModal.style.display = 'flex';
                    // إزالة العنصر من الصفحة مباشرة
                    parentRow.remove();
                } else {
                    alert('خطأ: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ أثناء الاتصال بالخادم');
            })
            .finally(() => {
                // إعادة تفعيل الزر فقط إذا لم يتم الحذف
                this.disabled = false;
                this.textContent = '<?php echo e(translate_lang('delete')); ?>';
            });
        });
    });

    // حذف الكورس من السلة
    const deleteButtons = document.querySelectorAll('.delete-course');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const courseId = this.getAttribute('data-course-id');
            const parentRow = document.getElementById('cart_row_'+courseId);

            // تعطيل الزر أثناء المعالجة
            this.disabled = true;
            this.textContent = 'جاري الحذف...';

            fetch('<?php echo e(route("remove.course")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ course_id: courseId })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    deleteCourseModal.style.display = 'flex';
                    // إزالة العنصر من الصفحة مباشرة
                    parentRow.remove();

                    // اعادة احتساب السعر الكلي
                    const checkoutPrices = document.querySelectorAll('.checkout-price');
                    let totalPrice = 0;
                    checkoutPrices.forEach(button => {
                        totalPrice += parseInt(button.getAttribute('data-course-price'));
                    });
                    document.getElementById('courses_sub_total').innerHTML = totalPrice + ' ' +  "<?php echo e(CURRENCY); ?>";
                    document.getElementById('courses_total').innerHTML = totalPrice + ' ' + "<?php echo e(CURRENCY); ?>";
                } else {
                    alert('خطأ: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ أثناء الاتصال بالخادم');
            })
            .finally(() => {
                // إعادة تفعيل الزر فقط إذا لم يتم الحذف
                this.disabled = false;
                this.textContent = '<?php echo e(translate_lang('delete')); ?>';
            });
        });
    });

        // إغلاق النافذة عند النقر على ×
    closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
    });
    DeleteCloseDeleteBtn.addEventListener('click', function() {
        deleteCourseModal.style.display = 'none';
    });

    // إغلاق النافذة عند النقر خارجها
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }else if(event.target === deleteCourseModal) {
            deleteCourseModal.style.display = 'none';
        }
    });

});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/web/checkout.blade.php ENDPATH**/ ?>