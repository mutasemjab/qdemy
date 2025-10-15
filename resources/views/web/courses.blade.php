@extends('layouts.app')

@php $title = $title ?? 'courses' @endphp
@section('title', translate_lang($title))

@section('content')
<section class="universities-page">

    <div data-aos="flip-up" data-aos-duration="1000" class="anim animate-glow courses-header-wrapper">
        <div class="courses-header">
            <h2>{{ translate_lang($title) }}</h2>
            <span class="grade-number">{{mb_substr( $title,0,1)}}</span>
        </div>
    </div>

    <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200" class="examx-filters">
        @include('web.alert-message')
        <form action='{{ route("courses") }}' method='get' id="filterForm">
            <div class="examx-row">

                <div class="examx-dropdown">
                    <select class="examx-pill" name="programm_id" id="programm_id">
                        <option value="">{{ translate_lang('select_program') }}</option>
                        @foreach($programms as $programm)
                            <option value="{{ $programm->id }}"
                                    data-ctg-key="{{ $programm->ctg_key }}"
                                    {{ request('programm_id') == $programm->id ? 'selected' : '' }}>
                                {{ $programm->localized_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="examx-dropdown" id="gradeSection" style="">
                    <select class="examx-pill" name="grade_id" id="grade_id">
                        <option value="">{{ translate_lang('select_grade') }}</option>
                        @foreach($grades as $grade)
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
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}"
                                    {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->localized_name }}
                                @if($subject->semester) - {{ $subject->semester->localized_name }} @endif
                                @if($subject->grade && $subject->grade->level == 'international-program-child') - {{ $subject->grade->localized_name }} @endif
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="examx-search">
                <input type="text" placeholder="{{ translate_lang('search') }}" name='search' value="{{ request('search') }}">
                <!--
                <button type="submit">{{__('messages.search')}}
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                -->
            </div>
        </form>
    </div>

    @php
        $user_courses = session()->get('courses', []);
        $user_enrollment_courses = CourseRepository()->getUserCoursesIds(auth_student()?->id);
    @endphp

    <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300" class="grades-grid">
        @forelse ($courses as $course)
        <div class="university-card">
            <div class="card-image">
                <span class="rank">#{{ $loop->index + 1}}</span>
                <img data-src="{{ $course->photo_url }}" alt="Course Image">
                @if($course->subject?->program)
                    <span class="course-name">{{$course->subject->program->localized_name}}</span>
                @endif
            </div>
            <div class="card-info">
                <p class="course-date">{{ $course->created_at->locale(app()->getLocale())->translatedFormat('d F Y') }}</p>
                <a class='text-dark' href="{{route('course',['course'=>$course->id,'slug'=>$course->slug])}}">
                    <span class="course-title">{{$course->subject?->localized_name}}</span>
                    <span class="course-title">{{$course->title}}</span>
                </a>
                <div class="instructor">
                    <img data-src="{{$course->teacher?->photo_url}}" alt="Instructor">
                    <a class='text-dark' href="{{route('teacher',$course->teacher?->id ?? '-')}}">
                        <span>{{$course->teacher?->name}}</span>
                    </a>
                </div>
                <div class="card-footer">
                    @if(is_array($user_enrollment_courses) && in_array($course->id,$user_enrollment_courses))
                      <a href="javascript:void(0)" class="join-btn joined-btn">{{translate_lang('enrolled')}}</a>
                    @elseif(is_array($user_courses) && in_array($course->id,$user_courses))
                      <a href="{{ route('checkout') }}" class="join-btn">{{translate_lang('go_to_checkout')}} <i class="fas fa-shopping-cart"></i></a>
                      <span class="price">{{$course->selling_price}} {{ CURRENCY }}</span>
                    @else
                        <a href="javascript:void(0)" class="join-btn enroll-btn"
                          data-course-id="{{ $course->id }}">{{translate_lang('enroll')}}</a>

                        <a href="{{ route('checkout') }}" id="go_to_checkout_{{$course->id}}" style='display:none;' class="join-btn">
                             {{translate_lang('go_to_checkout')}}
                            <i class="fas fa-shopping-cart"></i>
                        </a>

                        <span class="price">{{$course->selling_price}} {{ CURRENCY }}</span>
                        <!-- <span class="price">{{$course->selling_price}} {{ CURRENCY }}</span> -->
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center">
            <p>{{ translate_lang('no_courses_found') }}</p>
        </div>
        @endforelse
    </div>

    {{ $courses?->links('pagination::custom-bootstrap-5') ?? '' }}

    <!-- Modal for messages -->
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

@push('scripts')
<script>
// Course Enrollment Manager
class EnrollmentManager {
    constructor() {
        this.user = "{{auth_student()?->id}}";
        this.modal = document.getElementById('enrollment-modal');
        this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        this.init();
    }

    init() {
        this.setupEnrollmentButtons();
        this.setupModalButtons();
        this.setupFilterForm();
    }

    // Show modal with custom content
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
                    this.showModal(
                        "{{ translate_lang('login_required') }}",
                        "{{ translate_lang('please_login_first') }}",
                        false,
                        'fa-exclamation-circle'
                    );
                    setTimeout(() => this.hideModal(), 3000);
                    return;
                }

                const courseId = button.getAttribute('data-course-id');
                const originalText = button.innerHTML;

                // Show loading state
                button.innerHTML = '{{ translate_lang("loading") }}...';
                button.disabled = true;

                try {
                    const response = await fetch('{{ route("add.to.session") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ course_id: courseId })
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Show success modal
                        this.showModal(
                            "{{ translate_lang('course_added') }}",
                            "{{ translate_lang('course_added_successfully') }}",
                            true,
                            'fa-check'
                        );


                        button.remove();
                        document.getElementById('go_to_checkout_'+courseId).style.display = 'block';
                    } else {
                        // Show error modal
                        this.showModal(
                            "{{ translate_lang('error') }}",
                            data.message || "{{ translate_lang('something_went_wrong') }}",
                            false,
                            'fa-exclamation-triangle'
                        );
                        button.innerHTML = originalText;
                        button.disabled = false;
                    }
                } catch (error) {
                    console.error('Error:', error);
                    this.showModal(
                        "{{ translate_lang('error') }}",
                        "{{ translate_lang('connection_error') }}",
                        false,
                        'fa-exclamation-triangle'
                    );
                    button.innerHTML = originalText;
                    button.disabled = false;
                }
            });
        });
    }

    setupModalButtons() {
        // Close button
        const closeBtn = document.querySelector('.close');
        closeBtn?.addEventListener('click', () => this.hideModal());

        // Continue shopping button
        const continueBtn = document.getElementById('continue-shopping');
        continueBtn?.addEventListener('click', () => this.hideModal());

        // Go to checkout button
        const checkoutBtn = document.getElementById('go-to-checkout');
        checkoutBtn?.addEventListener('click', () => {
            window.location.href = '{{ route("checkout") }}';
        });

        // Close on outside click
        window.addEventListener('click', (event) => {
            if (event.target === this.modal) {
                this.hideModal();
            }
        });
    }

    setupFilterForm() {
        const form = document.getElementById('filterForm');
        const programmSelect = document.getElementById('programm_id');
        const gradeSelect = document.getElementById('grade_id');
        const subjectSelect = document.getElementById('subject_id');

        // Auto submit on filter change
        [programmSelect, gradeSelect, subjectSelect].forEach(element => {
            element?.addEventListener('change', () => {
                // Clear dependent filters
                if (element === programmSelect) {
                    gradeSelect.value = '';
                    subjectSelect.value = '';
                } else if (element === gradeSelect) {
                    subjectSelect.value = '';
                }
                form.submit();
            });
        });

        // Show/hide sections based on data
        if (gradeSelect && document.querySelectorAll('#grade_id option').length > 1) {
            document.getElementById('gradeSection').style.display = 'block';
        }

        if (subjectSelect && document.querySelectorAll('#subject_id option').length > 1) {
            document.getElementById('subjectSection').style.display = 'block';
        }
    }
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    new EnrollmentManager();
});
</script>
@endpush
