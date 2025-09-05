<?php $__env->startSection('title', translate_lang('cart')); ?>

<?php $__env->startSection('content'); ?>
<section class="checkout-wrapper">
    <!-- Cart Section -->
    <div class="checkout-box">
        <h2 class="checkout-heading"><?php echo e(translate_lang('cart')); ?></h2>

        <?php if($is_package): ?>
            <div class="package-info-box">
                <h3><?php echo e($package_info['package_name']); ?></h3>
                <p>عدد الكورسات المطلوبة: <?php echo e($package_info['max_courses']); ?></p>
                <p>عدد الكورسات المختارة: <span id="selected-courses-count"><?php echo e(count($package_info['courses'])); ?></span></p>
                <p>السعر الإجمالي للباكدج: <?php echo e($package_info['package_price']); ?> <?php echo e(CURRENCY); ?></p>
            </div>
        <?php endif; ?>

        <div class="checkout-row header">
            <span class="checkout-col"><?php echo e(translate_lang('course')); ?></span>
            <?php if(!$is_package): ?>
                <span class="checkout-col"><?php echo e(translate_lang('price')); ?></span>
            <?php endif; ?>
            <span class="checkout-col"><?php echo e(translate_lang('status')); ?></span>
            <span class="checkout-col"><?php echo e(translate_lang('actions')); ?></span>
        </div>

        <?php
            $total = 0;
            $validCoursesCount = 0;
        ?>

        <?php if($courses && $courses->count()): ?>
            <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $canPurchase = $course->can_purchase;
                    if($canPurchase) {
                        $validCoursesCount++;
                        if(!$is_package) {
                            $total += $course->selling_price;
                        }
                    }
                ?>

                <div class="checkout-row <?php if(!$canPurchase): ?> disabled-row <?php endif; ?>"
                     id='cart_row_<?php echo e($course->id); ?>'
                     data-course-id="<?php echo e($course->id); ?>"
                     data-package-id="<?php echo e($package_info['package_id'] ?? null); ?>"
                     data-can-purchase="<?php echo e($canPurchase ? '1' : '0'); ?>">

                    <div class="checkout-course">
                        <img src="<?php echo e(asset('assets_front/images/course-img2.png')); ?>"
                             alt="<?php echo e($course->title); ?>">
                        <div class="checkout-course-info">
                            <a class='text-decoration-none'
                               href="<?php echo e(route('course',['course'=>$course->id,'slug'=>$course->slug])); ?>">
                                <h3><?php echo e($course->subject?->localized_name); ?></h3>
                            </a>
                            <p><?php echo e($course->title); ?></p>
                        </div>
                    </div>

                    <?php if(!$is_package): ?>
                        <span class="checkout-price" data-course-price='<?php echo e($course->selling_price); ?>'>
                            <?php echo e($course->selling_price); ?> <?php echo e(CURRENCY); ?>

                        </span>
                    <?php endif; ?>

                    <span class="course-status">
                        <?php if($course->is_enrolled): ?>
                            <span class="badge badge-warning">مشترك مسبقاً</span>
                        <?php elseif(!$course->is_active): ?>
                            <span class="badge badge-danger">غير متاح</span>
                        <?php else: ?>
                            <span class="badge badge-success">متاح للشراء</span>
                        <?php endif; ?>
                    </span>

                    <div class="course-actions">
                        <button class="delete-course btn btn-sm btn-danger"
                                data-course-id="<?php echo e($course->id); ?>"
                                data-package-id="<?php echo e($package_info['package_id'] ?? null); ?>"
                                data-is-package="<?php echo e($is_package ? '1' : '0'); ?>">
                            <?php echo e(translate_lang('delete')); ?>

                        </button>

                        <?php if(!$canPurchase): ?>
                            <p class="text-danger small mt-1">
                                <?php if($course->is_enrolled): ?>
                                    يجب حذف هذا الكورس (مشترك مسبقاً)
                                <?php else: ?>
                                    يجب حذف هذا الكورس (غير متاح)
                                <?php endif; ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <div class="empty-cart">
                <p><?php echo e(translate_lang('cart_is_empty')); ?></p>
                <a href="<?php echo e(route('courses')); ?>" class="btn btn-primary">تصفح الكورسات</a>
            </div>
        <?php endif; ?>

        <?php if($is_package && $courses->count() > 0): ?>
            <div class="package-actions mt-3">
                <button class="btn btn-danger delete-package"
                        data-package-id="<?php echo e($package_info['package_id'] ?? null); ?>">
                    <?php echo e(translate_lang('delete_package')); ?>

                </button>

                <?php if($validCoursesCount != $package_info['max_courses']): ?>
                    <div class="alert alert-warning mt-2">
                        <i class="fa fa-exclamation-triangle"></i>
                        تحذير: يجب أن يكون لديك <?php echo e($package_info['max_courses']); ?> كورسات صالحة للشراء.
                        حالياً لديك <?php echo e($validCoursesCount); ?> كورسات صالحة.
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Total Section -->
    <?php if($courses && $courses->count() && $validCoursesCount > 0): ?>
        <div class="checkout-total-box">
            <h2 class="checkout-heading"><?php echo e(translate_lang('final_cost')); ?></h2>

            <?php if($is_package): ?>
                <div class="total-line">
                    <span>سعر الباكدج</span>
                    <span id='package_total'><?php echo e($package_info['package_price']); ?> <?php echo e(CURRENCY); ?></span>
                </div>
            <?php else: ?>
                <div class="total-line">
                    <span><?php echo e(translate_lang('sub_total')); ?></span>
                    <span id='courses_sub_total'><?php echo e($total); ?> <?php echo e(CURRENCY); ?></span>
                </div>
                <div class="total-line">
                    <span><?php echo e(translate_lang('total')); ?></span>
                    <span id='courses_total'><?php echo e($total); ?> <?php echo e(CURRENCY); ?></span>
                </div>
            <?php endif; ?>
        </div>

        <!-- Card Activation Section -->
        <div class="checkout-total-box">
            <div class="lesson-card-activation">
                <h3><?php echo e(translate_lang('card_qdemy')); ?></h3>
                <p><?php echo e(translate_lang('enter_card_qdemy')); ?></p>
                <input class="lesson-card-activation input"
                       type="text"
                       id="card-number-input"
                       placeholder="<?php echo e(translate_lang('enter_card_here')); ?>">
                <button class="lesson-card-activation button"
                        id="activate-card-btn"
                        data-payment-type="<?php echo e($is_package ? 'package' : 'courses'); ?>"
                        <?php if($is_package && $validCoursesCount != $package_info['max_courses']): ?> disabled <?php endif; ?>>
                    <?php echo e(translate_lang('activate_card')); ?>

                </button>

                <?php if($is_package && $validCoursesCount != $package_info['max_courses']): ?>
                    <p class="text-danger small mt-2">
                        يجب أن يكون لديك <?php echo e($package_info['max_courses']); ?> كورسات صالحة للشراء لتتمكن من الدفع
                    </p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Payment Section -->
        <div class="checkout-payment-box">
            <h2 class="checkout-heading">طرق الدفع</h2>
            <div class="payment-options">
                <button class="payment-option active">VISA</button>
                <button class="payment-option">Cash</button>
            </div>

            <form class="payment-form" id="payment-form">
                <div class="payment-field">
                    <label>Cardholder's Name</label>
                    <input type="text" name="cardholder_name" placeholder="Seen on your card">
                </div>
                <div class="payment-field">
                    <label>Card Number</label>
                    <input type="text" name="card_number" placeholder="Seen on your card">
                </div>
                <div class="payment-double">
                    <div class="payment-field">
                        <label>Expiry</label>
                        <input type="text" name="expiry" placeholder="MM/YY">
                    </div>
                    <div class="payment-field">
                        <label>CVC</label>
                        <input type="text" name="cvc" placeholder="***">
                    </div>
                </div>
                <button type="submit" class="payment-submit">الدفع</button>
            </form>
        </div>
    <?php endif; ?>

    <!-- Success Modal -->
    <div id="enrollment-modal" class="messages modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3><i class="fa fa-check"></i></h3>
            <h3 id="success-message"><?php echo e(translate_lang('تم تفعيل البطاقة بنجاح وإضافتك للكورسات!')); ?></h3>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-course-modal" class="messages modal">
        <div class="modal-content">
            <span class="delete-course-modal-close">&times;</span>
            <h3><i class="fa fa-check"></i></h3>
            <h3><?php echo e(translate_lang('تم حذف الكورس من عربة التسوق!')); ?></h3>
        </div>
    </div>

    <!-- Error Modal -->
    <div id="error-modal" class="messages modal">
        <div class="modal-content">
            <span class="error-modal-close">&times;</span>
            <h3><i class="fa fa-times"></i></h3>
            <h3 id="error-message"></h3>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .disabled-row {
        opacity: 0.6;
        background-color: #f5f5f5;
    }
    .badge {
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 12px;
    }
    .badge-success {
        background-color: #28a745;
        color: white;
    }
    .badge-warning {
        background-color: #ffc107;
        color: #333;
    }
    .badge-danger {
        background-color: #dc3545;
        color: white;
    }
    .package-info-box {
        background: #f8f9fa;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }
    .alert {
        padding: 12px;
        border-radius: 4px;
        margin-top: 10px;
    }
    .alert-warning {
        background-color: #fff3cd;
        border: 1px solid #ffeaa7;
        color: #856404;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Class for managing cart operations
    class CartManager {
        constructor() {
            this.initializeEventListeners();
            this.updateUI();
        }

        initializeEventListeners() {
            // تفعيل البطاقة
            const activateBtn = document.getElementById('activate-card-btn');
            if (activateBtn) {
                activateBtn.addEventListener('click', (e) => this.handleCardActivation(e));
            }

            // حذف الكورسات
            document.querySelectorAll('.delete-course').forEach(button => {
                button.addEventListener('click', (e) => this.handleCourseDelete(e));
            });

            // حذف الباكدج
            const deletePackageBtn = document.querySelector('.delete-package');
            if (deletePackageBtn) {
                deletePackageBtn.addEventListener('click', (e) => this.handlePackageDelete(e));
            }

            // نموذج الدفع
            const paymentForm = document.getElementById('payment-form');
            if (paymentForm) {
                paymentForm.addEventListener('submit', (e) => this.handlePaymentSubmit(e));
            }

            // إغلاق النوافذ المنبثقة
            this.setupModalClosers();
        }

        setupModalClosers() {
            document.querySelectorAll('.close, .delete-course-modal-close, .error-modal-close').forEach(closer => {
                closer.addEventListener('click', function() {
                    this.closest('.modal').style.display = 'none';
                });
            });

            window.addEventListener('click', function(event) {
                if (event.target.classList.contains('modal')) {
                    event.target.style.display = 'none';
                }
            });
        }

        async handleCardActivation(e) {
            e.preventDefault();

            const button = e.target;
            const cardInput = document.getElementById('card-number-input');
            const cardNumber = cardInput.value.trim();
            const paymentType = button.getAttribute('data-payment-type');

            if (!cardNumber) {
                this.showError('من فضلك أدخل رقم البطاقة');
                return;
            }

            button.disabled = true;
            button.textContent = 'جاري التفعيل...';

            try {
                const endpoint = paymentType === 'package'
                    ? '<?php echo e(route("payment.package.card")); ?>'
                    : '<?php echo e(route("payment.card")); ?>';

                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ card_number: cardNumber })
                });

                const data = await response.json();

                if (data.success) {
                    this.showSuccess(data.message || 'تمت العملية بنجاح');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    this.showError(data.message || 'حدث خطأ');
                }
            } catch (error) {
                this.showError('حدث خطأ أثناء الاتصال بالخادم');
            } finally {
                button.disabled = false;
                button.textContent = 'تفعيل البطاقة';
            }
        }

        async handleCourseDelete(e) {
            const button    = e.target;
            const courseId  = button.getAttribute('data-course-id');
            const packageId = button.getAttribute('data-package-id');
            const isPackage = button.getAttribute('data-is-package') === '1';
            const parentRow = document.getElementById('cart_row_' + courseId);

            button.disabled = true;
            button.textContent = 'جاري الحذف...';

            let handleCourseDeleteData;
            let handleCourseDeleteRoute;
            try {
                if(isPackage){
                    handleCourseDeleteData = { course_id: courseId,package_id: packageId };
                    handleCourseDeleteRoute = '<?php echo e(route("remove.course.from.package")); ?>';
                }else{
                    handleCourseDeleteData = { course_id: courseId};
                    handleCourseDeleteRoute = '<?php echo e(route("remove.course")); ?>';
                }
                const response = await fetch(handleCourseDeleteRoute, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(handleCourseDeleteData)
                });

                const data = await response.json();

                if (data && data.success) {
                    parentRow.remove();
                    this.showDeleteSuccess();

                    // تحديث المعلومات
                    if (isPackage && data.remaining_count !== undefined) {
                        this.updatePackageInfo(data.remaining_count, data.required_count);
                    } else {
                        this.recalculateTotal();
                    }
                } else {
                    this.showError(data.message || 'حدث خطأ');
                }
                console.log(data);
            } catch (error) {
                this.showError('حدث خطأ أثناء الاتصال بالخادم');
            } finally {
                button.disabled = false;
                button.textContent = 'حذف';
            }
        }

        async handlePackageDelete(e) {
            const button = e.target;
            const packageId = button.getAttribute('data-package-id');

            if (!confirm('هل أنت متأكد من حذف الباكدج بالكامل؟')) {
                return;
            }

            button.disabled = true;
            button.textContent = 'جاري الحذف...';

            try {
                const response = await fetch('<?php echo e(route("remove.package")); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ package_id: packageId })
                });

                const data = await response.json();

                if (data.success) {
                    this.showSuccess('تم حذف الباكدج بنجاح');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    this.showError(data.message || 'حدث خطأ');
                }
            } catch (error) {
                this.showError('حدث خطأ أثناء الاتصال بالخادم');
            } finally {
                button.disabled = false;
                button.textContent = 'حذف الباكدج';
            }
        }

        async handlePaymentSubmit(e) {
            e.preventDefault();
            // يمكن إضافة منطق معالجة الدفع بالفيزا هنا
            // this.showError('خدمة الدفع بالفيزا قيد التطوير');
        }

        recalculateTotal() {
            const priceElements = document.querySelectorAll('.checkout-row:not(.disabled-row) .checkout-price');
            let total = 0;

            priceElements.forEach(element => {
                const price = parseFloat(element.getAttribute('data-course-price'));
                if (!isNaN(price)) {
                    total += price;
                }
            });

            const subTotalElement = document.getElementById('courses_sub_total');
            const totalElement = document.getElementById('courses_total');

            if (subTotalElement) subTotalElement.textContent = total + ' <?php echo e(CURRENCY); ?>';
            if (totalElement) totalElement.textContent = total + ' <?php echo e(CURRENCY); ?>';
        }

        updatePackageInfo(currentCount, requiredCount) {
            const countElement = document.getElementById('selected-courses-count');
            if (countElement) {
                countElement.textContent = currentCount;
            }

            // تحديث حالة زر الدفع
            const activateBtn = document.getElementById('activate-card-btn');
            if (activateBtn) {
                if (currentCount !== requiredCount) {
                    activateBtn.disabled = true;
                    this.showError(`يجب أن يكون لديك ${requiredCount} كورسات صالحة للدفع`);
                } else {
                    activateBtn.disabled = false;
                }
            }
        }

        updateUI() {
            // تحديث واجهة المستخدم بناءً على الحالة الحالية
            this.recalculateTotal();
        }

        showSuccess(message) {
            const modal = document.getElementById('enrollment-modal');
            const messageElement = document.getElementById('success-message');
            if (messageElement) messageElement.textContent = message;
            if (modal) modal.style.display = 'flex';
        }

        showDeleteSuccess() {
            const modal = document.getElementById('delete-course-modal');
            if (modal) modal.style.display = 'flex';
        }

        showError(message) {
            const modal = document.getElementById('error-modal');
            const messageElement = document.getElementById('error-message');
            if (messageElement) messageElement.textContent = message;
            if (modal) modal.style.display = 'flex';
        }
    }

    // Initialize cart manager
    new CartManager();
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/web/checkout.blade.php ENDPATH**/ ?>