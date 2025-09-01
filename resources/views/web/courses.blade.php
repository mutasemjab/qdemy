@extends('layouts.app')

@php $title = $title ?? 'courses' @endphp
@section('title', translate_lang($title))

@section('content')
<section class="universities-page">

    <div class="courses-header-wrapper">
        <div class="courses-header">
            <h2>{{ translate_lang($title) }}</h2>
            <span class="grade-number">{{mb_substr( $title,0,1)}}</span>
        </div>
    </div>
    @php $user_courses = session()->get('courses', []); @endphp
    @php $user_enrollment_courses = CourseRepository()->getUserCoursesIds(auth_student()?->id); @endphp
    <div class="grades-grid">
        @foreach ($courses as $course)
        <div class="university-card">
            <div class="card-image">
                <span class="rank">#{{ $loop->index + 1}}</span>
                <img data-src="{{ $course->photo_url }}" alt="Course Image">
                @if($course->category && $course->category?->parent_id)<span class="course-name">{{$course->category->localized_name}}</span>@endif
            </div>
            <div class="card-info">
                <p class="course-date">{{ $course->created_at->locale(app()->getLocale())->translatedFormat('d F Y') }}</p>
                <a class='text-decoration-none text-dark' href="{{route('course',['course'=>$course->id,'slug'=>$course->slug])}}">
                    <span class="course-title">{{$course->title}}</span>
                </a>
                <div class="instructor">
                    <img data-src="{{$course->teacher?->photo_url}}" alt="Instructor">
                    <a class='text-decoration-none text-dark' href="{{route('teacher',$course->teacher?->id ?? '-')}}">
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
                        <span class="price">{{$course->selling_price}} {{ CURRENCY }}</span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
   <!-- <div class="pagination-wrapper"> -->
       {{ $courses?->links('pagination::custom-bootstrap-5') ?? '' }}
   <!-- </div> -->


    <!-- نافذة منبثقة -->
    <div id="enrollment-modal" class="messages modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3> <i class="fa fa-check"></i> </h3>
            <h3>{{ translate_lang('course_added') }}</h3>
            <p>{{ translate_lang('course_added_successfully') }}</p>
            <div class="modal-buttons">
                <button id="continue-shopping">{{ translate_lang('continue_shopping') }}</button>
                <button id="go-to-checkout">{{ translate_lang('go_to_checkout') }}</button>
            </div>
        </div>
    </div>

</section>
@endsection

@push('scripts')
<script>
    let user = "{{auth_student()?->id}}";

    document.addEventListener('DOMContentLoaded', function() {
        // الحصول على العناصر
        const modal = document.getElementById('enrollment-modal');
        const closeBtn = document.querySelector('.close');
        const continueBtn = document.getElementById('continue-shopping');
        const checkoutBtn = document.getElementById('go-to-checkout');
        const enrollButtons = document.querySelectorAll('.enroll-btn');

        // الحصول على CSRF Token من meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // إضافة حدث النقر لأزرار التسجيل
        enrollButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                if(!user){
                    alert("{{translate_lang('please login first .')}}");
                    return 0;
                }

                const courseId = this.getAttribute('data-course-id');
                // إظهار مؤشر تحميل (اختياري)
                this.innerHTML = '{{translate_lang("loading")}}...';
                this.disabled = true;

                // إرسال طلب Ajax
                fetch('{{ route("add.to.session") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ course_id: courseId })
                })
                .then(response => {
                    this.innerHTML = '{{translate_lang("enroll")}}';
                    this.disabled = false;
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // عرض النافذة المنبثقة
                        modal.style.display = 'flex';
                        // تحويل زر التسجيل إلى لينك لصفحة الدفع
                        this.innerHTML = "{{translate_lang('go_to_checkout')}} <i class='fas fa-shopping-cart'>";
                        this.setAttribute('href',"{{route('checkout')}}");
                        this.disabled = false;
                        this.classList.remove('enroll-btn');
                    } else {
                        alert('حدث خطأ أثناء إضافة الكورس: ' + (data.message || 'Unknown error'));
                    }
                    console.log(data);
                })
                .catch(error => {
                    console.log('Error:', error);
                    alert('حدث خطأ في الاتصال بالخادم');
                });
            });
        });

        // إغلاق النافذة عند النقر على ×
        closeBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        // الاستمرار في التسوق
        continueBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        // الانتقال إلى صفحة الدفع
        checkoutBtn.addEventListener('click', function() {
            window.location.href = '{{ route("checkout") }}'; // تأكد من وجود هذا المسار
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
