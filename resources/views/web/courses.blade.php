@extends('layouts.app')

@php $title = $title ?? __('front.courses') @endphp
@section('title', translate_lang($title))

@section('content')
    <section class="universities-page">

        <div data-aos="flip-up" data-aos-duration="1000" class="anim animate-glow courses-header-wrapper">
            <div class="courses-header-1">
                <img src="{{ app()->getLocale() == 'ar'
                    ? asset('assets_front/images/courses-header-line-new.png')
                    : asset('assets_front/images/en/courses-header-line-new.png') }}"
                    alt="" class="courses-header-img" loading="lazy">
                <span class="grade-number">{{ mb_substr($title, 0, 1) }}</span>
            </div>
        </div>

        <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200" class="examx-filters">
            @include('web.alert-message')
            <form action='{{ route('courses') }}' method='get' id="filterForm">
                <div class="examx-row">
                    <div class="examx-dropdown">
                        <select class="examx-pill" name="programm_id" id="programm_id">
                            <option value="">{{ translate_lang('select_program') }}</option>
                            @foreach ($programms as $programm)
                                <option value="{{ $programm->id }}" data-ctg-key="{{ $programm->ctg_key }}"
                                    {{ request('programm_id') == $programm->id ? 'selected' : '' }}>
                                    {{ $programm->localized_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="examx-dropdown" id="gradeSection" style="">
                        <select class="examx-pill" name="grade_id" id="grade_id">
                            <option value="">{{ translate_lang('select_grade') }}</option>
                            @foreach ($grades as $grade)
                                <option value="{{ $grade->id }}"
                                    {{ request('grade_id') == $grade->id ? 'selected' : '' }}>
                                    {{ $grade->localized_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="examx-dropdown" id="subjectSection" style="">
                        <select class="examx-pill" name="subject_id" id="subject_id">
                            <option value="">{{ translate_lang('select_subject') }}</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}"
                                    {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->localized_name }}
                                    @if ($subject->semester)
                                        - {{ $subject->semester->localized_name }}
                                    @endif
                                    @if ($subject->grade && $subject->grade->level == 'international-program-child')
                                        - {{ $subject->grade->localized_name }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="examx-search">
                    <input type="text" placeholder="{{ translate_lang('search') }}" name='search'
                        value="{{ request('search') }}">
                </div>
            </form>
        </div>

        @php
            $user_courses = session()->get('courses', []);
            $user_enrollment_courses = CourseRepository()->getUserCoursesIds(auth_student()?->id);
        @endphp

        <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300" class="grades-grid courses-grid">
            @forelse ($courses as $course)
                <article class="course-card">
                    <div class="course-card__media">
                        <img src="{{ $course->photo_url }}" alt="Course Image">
                        @if ($course->is_pinned ?? false)
                            <span class="course-card__ribbon">{{ translate_lang('pinned_course') }}</span>
                        @endif

                    </div>

                    <div class="course-card__body">
                        <div class="course-card__crumb">
                            <span>
                                {{ $course->subject?->localized_name }}
                                @if ($course->teacher)
                                    - {{ $course->teacher->name }}
                                @endif
                                @if ($course->grade)
                                    - {{ $course->grade->localized_name }}
                                @endif
                            </span>
                        </div>

                        <a class="course-card__title"
                            href="{{ route('course', ['course' => $course->id, 'slug' => $course->slug]) }}">
                            {{ $course->title ?: $course->subject?->localized_name . ' - ' . $course->teacher?->name }}
                        </a>

                        <div class="course-card__divider"></div>

                        <div class="course-card__text">
                            @if ($course->description)
                                {!! $course->description !!}
                            @else
                                <p>{{ translate_lang('weekly_lecture_structure') }}</p>
                                <ul>
                                    <li>{{ translate_lang('watch_how_to_submit_homework') }}</li>
                                    <li>{{ translate_lang('homework_attempt_once_then_contact_support') }}</li>
                                    <li>{{ translate_lang('three_exam_attempts_open_after_finish') }}</li>
                                </ul>
                            @endif
                        </div>

                        <div class="course-card__cta">
                            @if (is_array($user_enrollment_courses) && in_array($course->id, $user_enrollment_courses))
                                <a href="{{ route('course', ['course' => $course->id, 'slug' => $course->slug]) }}"
                                    class="btn btn--outline">{{ translate_lang('enter_course') }}</a>
                                <span class="btn btn--solid is-muted">{{ translate_lang('enrolled') }}</span>
                            @elseif(is_array($user_courses) && in_array($course->id, $user_courses))
                                <a href="{{ route('checkout') }}"
                                    class="btn btn--outline">{{ translate_lang('go_to_checkout') }}</a>
                                <a href="{{ route('course', ['course' => $course->id, 'slug' => $course->slug]) }}"
                                    class="btn btn--solid">{{ translate_lang('enter_course') }}</a>
                            @else
                                <a href="{{ route('course', ['course' => $course->id, 'slug' => $course->slug]) }}"
                                    class="btn btn--outline">{{ translate_lang('enter_course') }}</a>

                                <a href="javascript:void(0)" class="btn btn--solid enroll-btn"
                                    data-course-id="{{ $course->id }}">{{ translate_lang('subscribe_now') }}</a>

                                <a href="{{ route('checkout') }}" id="go_to_checkout_{{ $course->id }}"
                                    style="display:none;" class="btn btn--solid">
                                    {{ translate_lang('go_to_checkout') }}
                                </a>
                            @endif
                        </div>

                        <div class="course-card__meta">
                            <div class="course-card__meta-item">
                                <i class="fas fa-calendar-alt"></i>
                                <span>{{ $course->start_at?->locale(app()->getLocale())->translatedFormat('dddd, d F Y') ?? $course->created_at->locale(app()->getLocale())->translatedFormat('dddd, d F Y') }}</span>
                            </div>
                            <div class="course-card__meta-item">
                                <i class="fas fa-clock"></i>
                                <span>{{ $course->start_at?->format('g:i A') ?? $course->created_at->format('g:i A') }}</span>
                            </div>
                        </div>

                        <div class="course-card__footer">
                            <span class="course-card__footer-price">
                                {{ number_format((float) $course->selling_price, 0) }}
                                <small>{{ CURRENCY }}</small>
                            </span>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-12 text-center">
                    <p>{{ translate_lang('no_courses_found') }}</p>
                </div>
            @endforelse
        </div>

        {{ $courses?->links('pagination::custom-bootstrap-5') ?? '' }}

        <div id="enrollment-modal" class="messages modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h3 id="modal-icon"> <i class="fa fa-check"></i> </h3>
                <h3 id="modal-title">{{ translate_lang('course_added') }}</h3>
                <p id="modal-message">{{ translate_lang('course_added_successfully') }}</p>
                <div class="modal-buttons" id="modal-buttons">
                    <button id="continue-shopping">{{ translate_lang('continue_shopping') }}</button>
                    <button id="go-to-checkout">{{ translate_lang('go_to_checkout') }}</button>
                </div>
            </div>
        </div>

    </section>
@endsection
@push('styles')
    <style>
        /* Apply Somar font */
        .universities-page,
        .universities-page * {
            font-family: 'Somar', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .courses-grid {
            grid-template-columns: 1fr;
            gap: 28px;
        }

        .course-card {
            background: #f7f9fb;
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, .08);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .course-card:hover {
            transform: scale(1.03);
            box-shadow: 0 20px 50px rgba(15, 23, 42, .15);
        }

        .course-card__media {
            position: relative;
            aspect-ratio: 16/9;
            overflow: hidden;
        }

        .course-card__media img {
            border-radius: 10px;
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.3s ease;
        }

        .course-card:hover .course-card__media img {
            transform: scale(1.05);
        }

        .course-card__ribbon {
            position: absolute;
            top: 18px;
            inset-inline-end: -36px;
            background: #ff5a7a;
            color: #fff;
            padding: 8px 44px;
            transform: rotate(45deg);
            font-size: 13px;
            font-weight: 700;
            letter-spacing: .3px;
        }

        .course-card__teacher {
            position: absolute;
            top: 10px;
            border: solid #0055d3;
            transform: translateX(-50%);
            background: #fff;
            border-radius: 10px;
            padding: 6px 14px;
            font-weight: 700;
            color: #0f172a;
            box-shadow: 0 6px 18px rgba(15, 23, 42, .15);
        }

        .course-card__price-badge {
            position: absolute;
            bottom: 16px;
            inset-inline-end: 16px;
            background: #fff;
            border-radius: 12px;
            padding: 10px 12px;
            display: flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 8px 22px rgba(15, 23, 42, .15);
        }

        .course-card__price-number {
            font-weight: 800;
            color: #0f172a;
        }

        .course-card__price-currency {
            background: #16a34a;
            color: #fff;
            border-radius: 999px;
            padding: 2px 8px;
            font-size: 12px;
        }

        .course-card__body {
            z-index: 9999;
            position: relative;
            background: #fff;
            margin: -26px 20px 16px;
            border-radius: 14px;
            padding: 18px 18px 14px;
            box-shadow: -1px 5px 30px rgb(15 23 42 / 9%);
        }

        .course-card__crumb {
            display: inline-block;
            background: #fde68a;
            color: #6b4f00;
            border-radius: 10px;
            padding: 6px 12px;
            font-size: 13px;
            font-weight: 700;
            margin-top: -10px;
        }

        .course-card__title {
            display: block;
            color: #0f172a;
            font-weight: 800;
            font-size: 17px;
            margin: 14px 0 10px;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .course-card:hover .course-card__title {
            color: #3c98ff;
        }

        .course-card__divider {
            height: 3px;
            width: 120px;
            background: #3c98ff;
            border-radius: 3px;
        }

        .course-card__text {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.9;
            margin-top: 12px;
        }

        .course-card__text ul {
            padding: 0 10px;
            margin: 8px 0 0 1rem;
        }

        .course-card__cta {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin: 14px 0 8px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 40px;
            padding: 0 18px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 14px;
            text-decoration: none;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn--outline {
            border: 1px solid #bcd0ea;
            background: #f0f7ff;
            color: #0b63ce;
        }

        .btn--outline:hover {
            background: #e0f0ff;
            box-shadow: 0 4px 12px rgba(11, 99, 206, 0.2);
        }

        .btn--solid {
            background: #3c98ff;
            color: #fff;
            border: 0;
        }

        .btn--solid:hover {
            background: #2980ff;
            box-shadow: 0 4px 12px rgba(60, 152, 255, 0.3);
        }

        .btn.is-muted {
            background: #e5e7eb;
            color: #6b7280;
        }

        .course-card__meta {
            display: flex;
            gap: 18px;
            color: #6b7280;
            font-size: 13px;
            margin-top: 6px;
        }

        .course-card__meta i {
            margin-inline-end: 6px;
        }

        .course-card__footer {
            display: flex;
            margin-top: 10px;
        }

        .course-card__footer-price {
            background: #fde58a;
            color: #6b4f00;
            border-radius: 999px;
            padding: 6px 12px;
            font-weight: 800;
        }

        .course-card__footer-price small {
            font-weight: 700;
            margin-inline-start: 4px;
            color: #6b4f00;
        }

        @media (min-width:992px) {
            .courses-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width:720px) {
            .course-card__body {
                padding: 18px 13px 14px !important;
                margin: -26px 7px 16px !important;
            }
        }

        @media (min-width:1400px) {
            .courses-grid {
                grid-template-columns: 1fr 1fr 1fr;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        class EnrollmentManager {
            constructor() {
                this.user = "{{ auth_student()?->id }}";
                this.modal = document.getElementById('enrollment-modal');
                this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                this.init();
            }
            init() {
                this.setupEnrollmentButtons();
                this.setupModalButtons();
                this.setupFilterForm();
            }
            showModal(title, message, showButtons = true, icon = 'fa-check') {
                const modalTitle = document.getElementById('modal-title');
                const modalMessage = document.getElementById('modal-message');
                const modalIcon = document.getElementById('modal-icon');
                const modalButtons = document.getElementById('modal-buttons');
                modalTitle.textContent = title;
                modalMessage.textContent = message;
                modalIcon.innerHTML = `<i class="fa ${icon}"></i>`;
                modalButtons.style.display = showButtons ? 'flex' : 'none';
                this.modal.style.display = 'flex';
            }
            hideModal() {
                this.modal.style.display = 'none';
            }
            setupEnrollmentButtons() {
                const enrollButtons = document.querySelectorAll('.enroll-btn');
                enrollButtons.forEach(button => {
                    button.addEventListener('click', async (e) => {
                        e.preventDefault();
                        if (!this.user) {
                            this.showModal("{{ translate_lang('login_required') }}",
                                "{{ translate_lang('please_login_first') }}", false,
                                'fa-exclamation-circle');
                            setTimeout(() => this.hideModal(), 3000);
                            return;
                        }
                        const courseId = button.getAttribute('data-course-id');
                        const originalText = button.innerHTML;
                        button.innerHTML = '{{ translate_lang('loading') }}...';
                        button.disabled = true;
                        try {
                            const response = await fetch('{{ route('add.to.session') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': this.csrfToken,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    course_id: courseId
                                })
                            });
                            const data = await response.json();
                            if (data.success) {
                                this.showModal("{{ translate_lang('course_added') }}",
                                    "{{ translate_lang('course_added_successfully') }}", true,
                                    'fa-check');
                                button.remove();
                                document.getElementById('go_to_checkout_' + courseId).style
                                    .display = 'block';
                            } else {
                                this.showModal("{{ translate_lang('error') }}", data.message ||
                                    "{{ translate_lang('something_went_wrong') }}", false,
                                    'fa-exclamation-triangle');
                                button.innerHTML = originalText;
                                button.disabled = false;
                            }
                        } catch (error) {
                            this.showModal("{{ translate_lang('error') }}",
                                "{{ translate_lang('connection_error') }}", false,
                                'fa-exclamation-triangle');
                            button.innerHTML = originalText;
                            button.disabled = false;
                        }
                    });
                });
            }
            setupModalButtons() {
                const closeBtn = document.querySelector('.close');
                closeBtn?.addEventListener('click', () => this.hideModal());
                const continueBtn = document.getElementById('continue-shopping');
                continueBtn?.addEventListener('click', () => this.hideModal());
                const checkoutBtn = document.getElementById('go-to-checkout');
                checkoutBtn?.addEventListener('click', () => {
                    window.location.href = '{{ route('checkout') }}';
                });
                window.addEventListener('click', (event) => {
                    if (event.target === this.modal) this.hideModal();
                });
            }
            setupFilterForm() {
                const form = document.getElementById('filterForm');
                const programmSelect = document.getElementById('programm_id');
                const gradeSelect = document.getElementById('grade_id');
                const subjectSelect = document.getElementById('subject_id');
                [programmSelect, gradeSelect, subjectSelect].forEach(element => {
                    element?.addEventListener('change', () => {
                        if (element === programmSelect) {
                            gradeSelect.value = '';
                            subjectSelect.value = '';
                        } else if (element === gradeSelect) {
                            subjectSelect.value = '';
                        }
                        form.submit();
                    });
                });
                if (gradeSelect && document.querySelectorAll('#grade_id option').length > 1) {
                    document.getElementById('gradeSection').style.display = 'block';
                }
                if (subjectSelect && document.querySelectorAll('#subject_id option').length > 1) {
                    document.getElementById('subjectSection').style.display = 'block';
                }
            }
        }
        document.addEventListener('DOMContentLoaded', () => {
            new EnrollmentManager();
        });
    </script>
@endpush
