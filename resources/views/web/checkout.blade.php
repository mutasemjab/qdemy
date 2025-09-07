@extends('layouts.app')
@section('title', __('front.page_title'))

@section('content')
<section class="checkout-wrapper">
    <!-- Cart Section -->
    <div class="checkout-box">
        <h2 class="checkout-heading">{{ translate_lang('cart') }}</h2>

        @if($is_package)
            <div class="package-info-box">
                <h3>{{ $package_info['package_name'] }}</h3>
                <p>عدد الكورسات المطلوبة: {{ $package_info['max_courses'] }}</p>
                <p>عدد الكورسات المختارة: <span id="selected-courses-count">{{ count($package_info['courses']) }}</span></p>
                <p>السعر الإجمالي للباكدج: {{ $package_info['package_price'] }} {{ CURRENCY }}</p>
            </div>
        @endif

        <div class="checkout-row header">
            <span class="checkout-col">{{ translate_lang('course') }}</span>
            @if(!$is_package)
                <span class="checkout-col">{{ translate_lang('price') }}</span>
            @endif
            <span class="checkout-col">{{ translate_lang('status') }}</span>
            <span class="checkout-col">{{ translate_lang('actions') }}</span>
        </div>
    </div>

        @php
            $total = 0;
            $validCoursesCount = 0;
        @endphp

        @if($courses && $courses->count())
            @foreach ($courses as $course)
                @php
                    $canPurchase = $course->can_purchase;
                    if($canPurchase) {
                        $validCoursesCount++;
                        if(!$is_package) {
                            $total += $course->selling_price;
                        }
                    }
                @endphp

                <div class="checkout-row @if(!$canPurchase) disabled-row @endif"
                     id='cart_row_{{$course->id}}'
                     data-course-id="{{ $course->id }}"
                     data-package-id="{{ $package_info['package_id'] ?? null}}"
                     data-can-purchase="{{ $canPurchase ? '1' : '0' }}">

                    <div class="checkout-course">
                        <img src="{{ asset('assets_front/images/course-img2.png') }}"
                             alt="{{ $course->title }}">
                        <div class="checkout-course-info">
                            <a class='text-decoration-none'
                               href="{{route('course',['course'=>$course->id,'slug'=>$course->slug])}}">
                                <h3>{{ $course->subject?->localized_name }}</h3>
                            </a>
                            <p>{{ $course->title }}</p>
                        </div>
                    </div>

                    @if(!$is_package)
                        <span class="checkout-price" data-course-price='{{ $course->selling_price }}'>
                            {{ $course->selling_price }} {{ CURRENCY }}
                        </span>
                    @endif

                    <span class="course-status">
                        @if($course->is_enrolled)
                            <span class="badge badge-warning">مشترك مسبقاً</span>
                        @elseif(!$course->is_active)
                            <span class="badge badge-danger">غير متاح</span>
                        @else
                            <span class="badge badge-success">متاح للشراء</span>
                        @endif
                    </span>

                    <div class="course-actions">
                        <button class="delete-course btn btn-sm btn-danger"
                                data-course-id="{{ $course->id }}"
                                data-package-id="{{ $package_info['package_id'] ?? null }}"
                                data-is-package="{{ $is_package ? '1' : '0' }}">
                            {{ translate_lang('delete') }}
                        </button>

                        @if(!$canPurchase)
                            <p class="text-danger small mt-1">
                                @if($course->is_enrolled)
                                    يجب حذف هذا الكورس (مشترك مسبقاً)
                                @else
                                    يجب حذف هذا الكورس (غير متاح)
                                @endif
                            </p>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <div class="empty-cart">
                <p>{{ translate_lang('cart_is_empty') }}</p>
                <a href="{{ route('courses') }}" class="btn btn-primary">تصفح الكورسات</a>
            </div>
        @endif

        @if($is_package && $courses->count() > 0)
            <div class="package-actions mt-3">
                <button class="btn btn-danger delete-package"
                        data-package-id="{{ $package_info['package_id'] ?? null}}">
                    {{ translate_lang('delete_package') }}
                </button>

                @if($validCoursesCount != $package_info['max_courses'])
                    <div class="alert alert-warning mt-2">
                        <i class="fa fa-exclamation-triangle"></i>
                        تحذير: يجب أن يكون لديك {{ $package_info['max_courses'] }} كورسات صالحة للشراء.
                        حالياً لديك {{ $validCoursesCount }} كورسات صالحة.
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Total Section -->
    @if($courses && $courses->count() && $validCoursesCount > 0)
        <div class="checkout-total-box">
            <h2 class="checkout-heading">{{ translate_lang('final_cost') }}</h2>

            @if($is_package)
                <div class="total-line">
                    <span>سعر الباكدج</span>
                    <span id='package_total'>{{ $package_info['package_price'] }} {{ CURRENCY }}</span>
                </div>
            @else
                <div class="total-line">
                    <span>{{ translate_lang('sub_total') }}</span>
                    <span id='courses_sub_total'>{{ $total }} {{ CURRENCY }}</span>
                </div>
                <div class="total-line">
                    <span>{{ translate_lang('total') }}</span>
                    <span id='courses_total'>{{ $total }} {{ CURRENCY }}</span>
                </div>
            @endif
        </div>

        <!-- Card Activation Section -->
        <div class="checkout-total-box">
            <div class="lesson-card-activation">
                <h3>{{ translate_lang('card_qdemy') }}</h3>
                <p>{{ translate_lang('enter_card_qdemy') }}</p>
                <input class="lesson-card-activation input"
                       type="text"
                       id="card-number-input"
                       placeholder="{{ translate_lang('enter_card_here') }}">
                <button class="lesson-card-activation button"
                        id="activate-card-btn"
                        data-payment-type="{{ $is_package ? 'package' : 'courses' }}"
                        @if($is_package && $validCoursesCount != $package_info['max_courses']) disabled @endif>
                    {{ translate_lang('activate_card') }}
                </button>

                @if($is_package && $validCoursesCount != $package_info['max_courses'])
                    <p class="text-danger small mt-2">
                        يجب أن يكون لديك {{ $package_info['max_courses'] }} كورسات صالحة للشراء لتتمكن من الدفع
                    </p>
                @endif
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
    @endif

    <!-- Success Modal -->
    <div id="enrollment-modal" class="messages modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3><i class="fa fa-check"></i></h3>
            <h3 id="success-message">{{ translate_lang('تم تفعيل البطاقة بنجاح وإضافتك للكورسات!') }}</h3>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-course-modal" class="messages modal">
        <div class="modal-content">
            <span class="delete-course-modal-close">&times;</span>
            <h3><i class="fa fa-check"></i></h3>
            <h3>{{ translate_lang('تم حذف الكورس من عربة التسوق!') }}</h3>
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
@endsection

@push('styles')
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
@endpush

@push('scripts')
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
                    ? '{{ route("payment.package.card") }}'
                    : '{{ route("payment.card") }}';

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
                    handleCourseDeleteRoute = '{{ route("remove.course.from.package") }}';
                }else{
                    handleCourseDeleteData = { course_id: courseId};
                    handleCourseDeleteRoute = '{{ route("remove.course") }}';
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
                const response = await fetch('{{ route("remove.package") }}', {
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

            if (subTotalElement) subTotalElement.textContent = total + ' {{ CURRENCY }}';
            if (totalElement) totalElement.textContent = total + ' {{ CURRENCY }}';
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
@endpush
