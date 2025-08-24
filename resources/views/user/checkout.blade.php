@extends('layouts.app')

@section('title', __('messages.cart'))

@section('content')
<section class="checkout-wrapper">

    <!-- Cart Section -->
    <div class="checkout-box">
        <h2 class="checkout-heading">{{ __('messages.cart') }}</h2>
        <div class="checkout-row header">
            <span class="checkout-col">{{ __('messages.course') }}</span>
            <span class="checkout-col">{{ __('messages.price') }}</span>
        </div>

        @php $total = 0; @endphp
        @foreach ($courses as $course)
            <div class="checkout-row" id='cart_row_{{$course->id}}'>
                <div class="checkout-course">
                    <img data-src="{{ asset('assets_front/images/course-img2.png') }}" alt="{{ $course->title }}">
                    <div class="checkout-course-info">
                        <a class='text-decoration-none' href="{{route('course',['course'=>$course->id,'slug'=>$course->slug])}}"><h3>{{ $course->category?->localized_name }}</h3></a>
                        <p>{{ $course->title }}</p>
                    </div>
                </div>
                <span class="checkout-price" data-course-price='{{ $course->selling_price }}'>{{ $course->selling_price }} {{ CURRENCY }}</span>
                <button class="delete-course" data-course-id="{{ $course->id }}">{{ __('messages.delete') }}</button>
            </div>
            @php $total += $course->selling_price; @endphp
        @endforeach
    </div>

    <!-- Total Section -->
    <div class="checkout-total-box">
        <h2 class="checkout-heading">{{ __('messages.final_cost') }}</h2>
        <div class="total-line">
            <span>{{ __('messages.sub_total') }}</span>
            <span id='courses_sub_total'>{{ $total }} {{ CURRENCY }}</span>
        </div>
        <div class="total-line">
            <span>{{ __('messages.total') }}</span>
            <span id='courses_total'>{{ $total }} {{ CURRENCY }}</span>
        </div>
    </div>

    <div class="checkout-total-box">
        <div class="lesson-card-activation">
            <h3>{{ __('messages.card_qdemy') }}</h3>
            <p>{{ __('messages.enter_card_qdemy') }}</p>
            <input class="lesson-card-activation input" type="text" placeholder="{{ __('messages.enter_card_here') }}">
            <button class="lesson-card-activation button">{{ __('messages.activate_card') }}</button>
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
            <h3>{{ __('messages.تم تفعيل البطاقة بنجاح وإضافتك للكورسات!') }}</h3>
        </div>
    </div>

</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const paymentForm = document.querySelector('.payment-form');
    const paymentSubmitButton = document.querySelector('.payment-submit');

    // الحصول على CSRF Token من meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    paymentForm.addEventListener('submit', function (e) {
        e.preventDefault();

        // تعطيل الزر أثناء المعالجة
        paymentSubmitButton.disabled = true;
        paymentSubmitButton.textContent = 'جاري المعالجة...';

        // جمع البيانات من النموذج
        const formData = {
            cardholder_name: paymentForm.querySelector('input[placeholder="Seen on your card"]').value,
            card_number: paymentForm.querySelector('input[placeholder="Seen on your card"]').value,
            expiry: paymentForm.querySelector('input[placeholder="MM/YY"]').value,
            cvc: paymentForm.querySelector('input[placeholder="***"]').value,
        };

        // إرسال الطلب عبر Ajax
        fetch('{{ route("process.payment") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(formData)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('تمت عملية الدفع بنجاح!');
                window.location.href = '{{ route("payment.success") }}';
            } else {
                alert('حدث خطأ أثناء عملية الدفع: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء الاتصال بالخادم');
        })
        .finally(() => {
            // إعادة تفعيل الزر
            paymentSubmitButton.disabled = false;
            paymentSubmitButton.textContent = 'الدفع';
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {

    const modal    = document.getElementById('enrollment-modal');
    const closeBtn = document.querySelector('.close');
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

        fetch('{{ route("payment.card") }}', {
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
                alert('تم تفعيل البطاقة بنجاح وإضافتك للكورسات!');
                location.reload();
            } else {
                 modal.style.display = 'flex';
                alert('خطأ: ' + data.message);
            }
        })
        .catch(error => {
             modal.style.display = 'flex';
            alert('حدث خطأ أثناء الاتصال بالخادم');
        })
        .finally(() => {
            activateButton.disabled = false;
            activateButton.textContent = 'تفعيل البطاقة';
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

            fetch('{{ route("remove.course") }}', {
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
                    modal.style.display = 'flex';
                    // إزالة العنصر من الصفحة مباشرة
                    parentRow.remove();

                    // اعادة احتساب السعر الكلي
                    const checkoutPrices = document.querySelectorAll('.checkout-price');
                    let totalPrice = 0;
                    checkoutPrices.forEach(button => {
                        totalPrice += parseInt(button.getAttribute('data-course-price'));
                    });
                    document.getElementById('courses_sub_total').innerHTML = totalPrice + ' ' +  "{{CURRENCY}}";
                    document.getElementById('courses_total').innerHTML = totalPrice + ' ' + "{{CURRENCY}}";
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
                this.textContent = '{{ __('messages.delete') }}';
            });
        });
    });

        // إغلاق النافذة عند النقر على ×
    closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    // إغلاق النافذة عند النقر خارجها
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

});
</script>
@endpush
