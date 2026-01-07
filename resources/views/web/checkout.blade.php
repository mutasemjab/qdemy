@extends('layouts.app')
@section('title', __('front.page_title'))

@section('content')
    <section class="checkout-layout">

        <div data-aos="fade-up" data-aos-duration="1000" class="cart-section">
            <h2 class="block-title">{{ __('front.cart') }}</h2>

            <div class="cart-card">

                @php
                    $total = 0;
                    $validCoursesCount = 0;
                @endphp
                @if ($courses && $courses->count())
                    @foreach ($courses as $course)
                        @php
                            $canPurchase = $course->can_purchase;
                            if ($canPurchase) {
                                $validCoursesCount++;
                                if (!$is_package) {
                                    $total += $course->selling_price;
                                }
                            }
                        @endphp

                        <div data-aos="fade" data-aos-duration="1000" data-aos-delay="200"
                            class="cart-item @if (!$canPurchase) disabled-row @endif"
                            id="cart_row_{{ $course->id }}">

                            <a class="course-col"
                                href="{{ route('course', ['course' => $course->id, 'slug' => $course->slug]) }}">
                                <div class="thumb"><img src="{{ asset('assets/admin/uploads/' . $course->photo) }}"
                                        alt=""></div>
                                <div class="info">
                                    <h3>{{ $course->subject?->localized_name }}</h3>
                                    <p>{{ $course->title }}</p>
                                </div>
                            </a>

                            <div class="price-col">{{ $course->selling_price }} {{ CURRENCY }}</div>

                            <div class="act-col">
                                <button class="trash-btn delete-course" data-course-id="{{ $course->id }}"
                                    data-is-package="{{ $is_package ? '1' : '0' }}"
                                    data-package-id="{{ $package_info['package_id'] ?? null }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-cart">
                        <p>{{ __('front.cart_is_empty') }}</p>
                        <a href="{{ route('courses') }}" class="btn btn-primary">{{ __('front.browse_courses') }}</a>
                    </div>
                @endif
            </div>
        </div>

        <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200" class="totals-row">
            <div class="totals-box">
                <h2 class="block-title">{{ __('front.final_cost') }}</h2>
                @if ($is_package)
                    <div class="t-row"><span>{{ __('front.package_price') }}</span><span
                            id="package_total">{{ $package_info['package_price'] }} {{ CURRENCY }}</span></div>
                @else
                    <div class="t-row"><span>{{ __('front.sub_total') }}</span><span
                            id="courses_sub_total">{{ $total }} {{ CURRENCY }}</span></div>
                    <div class="t-row"><span>{{ __('front.total') }}</span><span id="courses_total">{{ $total }}
                            {{ CURRENCY }}</span></div>
                @endif
            </div>
        </div>

        <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="400" class="left-col">
            <div class="card-activate">
                <h3 class="sub-title">Qdemy {{ __('front.card_qdemy') }}</h3>
                <p class="muted">{{ __('front.enter_card_qdemy') }}</p>
                <input type="text" id="card-number-input" class="input-like"
                    placeholder="{{ __('front.enter_card_here') }}">
                <button class="primary-btn" id="activate-card-btn"
                    data-payment-type="{{ $is_package ? 'package' : 'courses' }}">{{ __('front.activate_card') }}</button>
            </div>
        </div>

        <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="500" class="right-col">
            <div class="payment-box">
                <h2 class="block-title">{{ __('front.payment_methods') }}</h2>
                <div class="pay-tabs">
                    <button type="button" class="tab active" data-type="visa">VISA</button>
                    <button type="button" class="tab" data-type="cash">Cash</button>
                </div>

                <form class="pay-form" id="payment-form">

                    <div id="visa-fields">
                        <div class="f-group">
                            <label>{{ __('front.cardholder_name') }}</label>
                            <input type="text" placeholder="{{ __('front.seen_on_card') }}">
                        </div>

                        <div class="f-group">
                            <label>{{ __('front.card_number') }}</label>
                            <input type="text" placeholder="{{ __('front.seen_on_card') }}">
                        </div>

                        <div class="f-row">
                            <div class="f-group">
                                <label>{{ __('front.expiry') }}</label>
                                <input type="text" placeholder="MM/YY">
                            </div>
                            <div class="f-group">
                                <label>CVC</label>
                                <input type="text" placeholder="***">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="primary-btn wide">{{ __('front.pay') }}</button>
                </form>

            </div>
        </div>

    </section>
@endsection



@push('styles')
    <style>
        .checkout-layout {
            --card-bg: #e9eaec;
            --shadow: 0 14px 30px rgba(0, 0, 0, .08);
            max-width: 1300px;
            margin-inline: auto;
            padding: 20px;
            display: grid;
            gap: 36px 40px;
            grid-template-areas:
                "cart cart"
                "totals totals"
                "left right";
            grid-template-columns: 1fr 1fr;
        }

        .block-title {
            margin: 0 0 12px;
            font-weight: 800;
        }

        .cart-section {
            grid-area: cart
        }

        .cart-card {
            background: #DDDDDD;
            border-radius: 24px;
            box-shadow: var(--shadow);
            overflow: hidden
        }

        .cart-head {
            display: grid;
            grid-template-columns: .18fr 1fr .12fr;
            gap: 16px;
            padding: 18px 22px;
            color: #333;
            font-weight: 700
        }

        .cart-item {
            display: grid;
            grid-template-columns: 5.18fr 1fr .12fr;
            gap: 16px;
            align-items: center;
            padding: 24px 22px
        }

        .price-col {
            font-weight: 800;
            font-size: 20px
        }

        .course-col {
            display: flex;
            align-items: center;
            gap: 16px;
            text-decoration: none;
            color: inherit
        }

        .thumb img {
            width: 120px;
            height: 84px;
            object-fit: cover;
            border-radius: 12px
        }

        .info h3 {
            margin: 0 0 4px;
            font-weight: 800
        }

        .info p {
            margin: 0;
            color: #666
        }

        .act-col {
            display: flex;
            justify-content: flex-end
        }

        .trash-btn {
            cursor: pointer;
            background: #fff;
            border: 1px solid #e3e7ef;
            color: #d32727;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: grid;
            place-items: center
        }

        .trash-btn i {
            font-size: 14px
        }

        .disabled-row {
            opacity: .6
        }

        .totals-row {
            grid-area: totals
        }

        .totals-box {
            background: #fff;
            padding: 18px
        }

        .t-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0
        }

        .left-col {
            grid-area: right;
            padding-inline-start: 28px;
            border-inline-start: 1px solid #dfe3ea
        }

        .right-col {
            grid-area: left;
            position: relative
        }

        .card-activate .sub-title {
            margin: 0 0 8px;
            font-weight: 800;
            font-size: 28px
        }

        .card-activate .muted {
            color: #666;
            margin: 0 0 12px
        }

        .input-like {
            width: 100%;
            background: #e6e7e9;
            border: 0;
            border-radius: 8px;
            padding: 12px 14px;
            margin-bottom: 10px
        }

        .primary-btn {
            cursor: pointer;
            background: #0055D2;
            color: #fff;
            border: 0;
            border-radius: 10px;
            padding: 12px 16px;
            font-weight: 800
        }

        .primary-btn.wide {
            width: 100%
        }

        .payment-box .pay-tabs {
            display: flex;
            gap: 8px;
            margin: 8px 0 16px
        }

        .payment-box .tab {
            cursor: pointer;
            background: #eef0f3;
            border: 0;
            border-radius: 8px;
            padding: 8px 12px;
            font-weight: 700
        }

        .payment-box .tab.active {
            background: #0055D2;
            color: #fff
        }

        .pay-form {
            background: #fff;
            padding: 16px;
        }

        .f-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 12px
        }

        .f-group input {
            border: 1px solid #e6e7e9;
            border-radius: 10px;
            padding: 12px 14px;
            background: #fff
        }

        .f-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px
        }

        @media (max-width:992px) {
            .checkout-layout {
                grid-template-areas: "cart" "totals" "right" "left";
                grid-template-columns: 1fr;
                gap: 28px
            }

            .right-col {
                border-inline-start: 0;
                padding-inline-start: 0
            }

            .cart-head,
            .cart-item {
                grid-template-columns: .25fr 1fr .12fr
            }
        }

        @media (max-width:560px) {
            .thumb img {
                width: 100px;
                height: 70px
            }

            .cart-head {
                grid-template-columns: 1fr .2fr;
                gap: 10px
            }

            .cart-head .h-col:first-child {
                order: 2;
                text-align: start
            }

            .cart-item {
                grid-template-columns: 1fr .2fr;
                gap: 10px
            }

            .price-col {
                order: 2
            }

            .act-col {
                grid-column: 2
            }

            .left-col {
                grid-area: right;
                padding-inline-start: 0px;
                border-inline-start: none;
            }
        }
    </style>
@endpush


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            const translations = {
                enterCardNumber: @json(__('front.enter_card_number')),
                activating: @json(__('front.activating')),
                operationSuccess: @json(__('front.operation_success')),
                errorOccurred: @json(__('front.error_occurred')),
                serverConnectionError: @json(__('front.server_connection_error')),
                activateCard: @json(__('front.activate_card')),
                confirmDeletePackage: @json(__('front.confirm_delete_package')),
                packageDeletedSuccess: @json(__('front.package_deleted_success')),
                deletePackage: @json(__('front.delete_package')),
                paymentSubmitted: @json(__('front.payment_submitted')),
                cashPaymentSuccess: @json(__('front.cash_payment_success'))
            };

            const tabs = document.querySelectorAll('.pay-tabs .tab');
            const visaFields = document.getElementById('visa-fields');

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {

                    // active tab style
                    tabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    // toggle fields
                    if (this.dataset.type === 'cash') {
                        visaFields.style.display = 'none';
                    } else {
                        visaFields.style.display = 'block';
                    }
                });
            });


            function recalculateTotals() {
                let total = 0;
                document.querySelectorAll('.cart-item:not(.disabled-row) .price-col').forEach(el => {
                    const n = parseFloat((el.textContent || '').replace(/[^\d.]/g, ''));
                    if (!isNaN(n)) total += n;
                });
                const sub = document.getElementById('courses_sub_total');
                const tot = document.getElementById('courses_total');
                if (sub) sub.textContent = total + ' {{ CURRENCY }}';
                if (tot) tot.textContent = total + ' {{ CURRENCY }}';
            }

            document.addEventListener('click', async function(e) {
                const btn = e.target.closest('.delete-course');
                if (!btn) return;
                const row = btn.closest('.cart-item');
                const courseId = btn.getAttribute('data-course-id');
                const packageId = btn.getAttribute('data-package-id');
                const isPackage = btn.getAttribute('data-is-package') === '1';
                const payload = isPackage ? {
                    course_id: courseId,
                    package_id: packageId
                } : {
                    course_id: courseId
                };
                const url = isPackage ? '{{ route('remove.course.from.package') }}' :
                    '{{ route('remove.course') }}';

                const parent = row.parentNode;
                const placeholder = document.createComment('removed-row');
                parent.replaceChild(placeholder, row);
                recalculateTotals();

                try {
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });
                    const data = await res.json();
                    if (!(data && data.success)) throw new Error();
                    placeholder.remove();
                } catch (_) {
                    parent.replaceChild(row, placeholder);
                    recalculateTotals();
                }
            });

            const deletePackageBtn = document.querySelector('.delete-package');
            if (deletePackageBtn) {
                deletePackageBtn.addEventListener('click', async function() {
                    if (!confirm(translations.confirmDeletePackage)) return;
                    try {
                        const res = await fetch('{{ route('remove.package') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                package_id: this.getAttribute('data-package-id')
                            })
                        });
                        const data = await res.json();
                        if (data && data.success) {
                            location.reload();
                        }
                    } catch (_) {}
                });
            }

            const activateBtn = document.getElementById('activate-card-btn');
            if (activateBtn) {
                activateBtn.addEventListener('click', async function(e) {
                    e.preventDefault();
                    const input = document.getElementById('card-number-input');
                    const card = (input?.value || '').trim();
                    if (!card) return alert(translations.enterCardNumber);
                    const type = activateBtn.getAttribute('data-payment-type');
                    const url = type === 'package' ? '{{ route('payment.package.card') }}' :
                        '{{ route('payment.card') }}';
                    activateBtn.disabled = true;
                    activateBtn.textContent = translations.activating;
                    try {
                        const res = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                card_number: card
                            })
                        });
                        const data = await res.json();
                        if (data && data.success) {
                            location.reload();
                        } else {
                            activateBtn.disabled = false;
                            activateBtn.textContent = translations.activateCard;
                        }
                    } catch (_) {
                        activateBtn.disabled = false;
                        activateBtn.textContent = translations.activateCard;
                    }
                });
            }

            const paymentForm = document.getElementById('payment-form');
            if (paymentForm) {
                paymentForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const activeTab = document.querySelector('.pay-tabs .tab.active');
                    const paymentType = activeTab?.dataset.type;

                    if (paymentType === 'cash') {
                        handleCashPayment();
                    } else if (paymentType === 'visa') {
                        alert(translations.paymentSubmitted);
                    }
                });
            }

            async function handleCashPayment() {
                try {
                    const response = await fetch('{{ route("payment.cash") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            payment_type: @json($is_package ? 'package' : 'courses')
                        })
                    });

                    const data = await response.json();
                    if (data && data.success && data.whatsapp_url) {
                        alert(translations.cashPaymentSuccess);
                        window.open(data.whatsapp_url, '_blank');
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        alert(data?.message || translations.errorOccurred);
                    }
                } catch (error) {
                    alert(translations.serverConnectionError);
                }
            }

            recalculateTotals();
        });
    </script>
@endpush
