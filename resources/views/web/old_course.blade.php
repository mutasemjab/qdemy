@extends('layouts.app')

@section('title',$course->title)

@section('content')
<section class="lesson-page">

    <!-- Header -->
    <div class="grades-header-wrapper">
        <div class="grades-header">
            <h2>{{$course->title}}</h2>
            <span class="grade-number">{{mb_substr($course->title,0,1)}}</span>
        </div>
    </div>

    <div class="lesson-container">

        <!-- Right Sidebar -->
        <div class="lesson-sidebar">
            <div class="lesson-card">
                <!-- https://www.youtube.com/embed/SFPRqxNMUkM -->
                <div class="lesson-card-image playable" data-video="{{$freeContents?->video_url}}">
                    <img data-src="{{ asset('assets_front/images/video-thumb-main.png') }}" alt="Course">
                    <span class="play-overlay"><i class="fas fa-play"></i></span>
                </div>
                <div class="lesson-card-info">
                    <h3>{{$course->title}}</h3>
                    <p class="lesson-card-p">{{$freeContents?->title}}</p>
                    <span class="price">{{$course->selling_price}} {{ CURRENCY }}</span>
                    @if($is_enrolled)
                        <a  href="javascript:void(0)" class="btn-buy">{{ __('messages.enrolled') }}</a>
                    @elseif(is_array($user_courses) && in_array($course->id,$user_courses))
                      <a href="{{route('checkout')}}"class="btn-buy">{{ __('messages.buy_now') }}</a>
                      <a href="{{route('checkout')}}"class="btn-add">{{ __('messages.go_to_checkout') }}</a>
                    @else
                        <a id='buy_now' href="javascript:void(0)" data-course-id="{{ $course->id }}" class="enroll-btn btn-buy">{{ __('messages.buy_now') }}</a>
                        <a href="javascript:void(0)" data-course-id="{{ $course->id }}" class="enroll-btn btn-add">{{ __('messages.add_to_cart') }}</a>
                    @endif
                    <a href="{{ route('contacts') }}" class="text-decoration-none"><p class="report">{{ __('messages.report_this_course') }}</p></a>
                </div>
            </div>

            <div class="lesson-teacher">
                <img data-src="{{ $course->teacher?->photo_url }}" alt="Instructor">
                <h4>{{$course->teacher?->name}}</h4>
                <p>{{ __('messages.session_all_count') }} - {{$sections?->count()}}
                    <br> {{ __('messages.video_all_count') }} - {{$contents?->where('content_type','video')?->count()}}</p>
            </div>

            @if(!$is_enrolled)
            <div class="lesson-card-activation">
                <h3>{{ __('messages.card_qdemy') }}</h3>
                <p>{{ __('messages.enter_card_qdemy') }}</p>
                <input class="lesson-card-activation input" type="text" placeholder="{{ __('messages.enter_card_here') }}">
                <button data-course-id="{{$course->id}}" class="lesson-card-activation button">{{ __('messages.activate_card') }}</button>
            </div>
            @endif

        </div>

        <!-- Left Content -->
        <div class="lesson-content">
            <h2 class="lesson-title">{{$course->title}}</h2>
            <p class="lesson-desc">{{$course->description}}</p>

            <h3 class="lesson-subtitle">{{ __('messages.course_content') }}</h3>

            @if($sections && $sections->count())
            <div class="accordion">
                @foreach($sections as $section)
                <div class="accordion-item">
                    <button class="accordion-header"> {{$section->title}}</button>
                        <div class="accordion-body">
                            @php $sectionContents = $section->contents @endphp
                            @if($sectionContents && $sectionContents->count())
                                @foreach($sectionContents as $content)
                                <div class="lesson-item">
                                    @if($content->video_url)

                                        @if($content->is_free === 1 || $is_enrolled)
                                        <div class="lesson-video" data-video="{{$content->video_url}}">
                                            <img data-src="{{ asset('assets_front/images/video-thumb.png') }}" alt="Video">
                                            <span class="play-icon">▶</span>
                                        </div>
                                        @else
                                        <div class="lesson-video"><img data-src="{{ asset('assets_front/images/video-thumb.png') }}" alt="Video"></div>
                                        @endif

                                    @elseif($content->file_path)

                                        @if($content->is_free === 1 || $is_enrolled)
                                        <div class="lessonvideo">
                                            <a class='text-decoration-none' href="{{$content->file_path}}">
                                                <i class="fa fa-file" style='color:rgba(0, 85, 210, 0.7);'>
                                            </i></a>
                                        </div>
                                        @else
                                            <a class='text-decoration-none' href="javascrip:void(0)">
                                                <i class="fa fa-file" style='color:lightgray;'></i>
                                            </a>
                                        @endif

                                    @endif
                                    <div class="lesson-info">
                                        <h4>{{$content->title}}</h4>
                                        <!-- <p>{{$content->title}}</p> -->
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>
                </div>
                @endforeach
            </div>
            @endif

        </div>
    </div>

</section>

<!-- Video Popup -->
<div class="video-popup">
    <div class="video-popup-content">
        <span class="close-popup">&times;</span>
        <!-- <iframe width="315" height="560"
            src="https://www.youtube.com/embed/SFPRqxNMUkM"
            title="قَالَ إِنَّمَا یَتَقَبَّلُ ٱللَّهُ مِنَ ٱلۡمُتَّقِینَ♥️ #الشيخ_سيد_سعيد #القران_الكريم"
            frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen>
        </iframe> -->
        <iframe src="" frameborder="0" allowfullscreen></iframe>
    </div>
</div>

<!-- نافذة منبثقة -->
<div id="enrollment-modal" class="messages modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3> <i class="fa fa-check"></i> </h3>
        <h3>{{ __('messages.course_added') }}</h3>
        <p>{{ __('messages.course_added_successfully') }}</p>
        <div class="modal-buttons">
            <button id="continue-shopping">{{ __('messages.continue_shopping') }}</button>
            <button id="go-to-checkout">{{ __('messages.go_to_checkout') }}</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // الحصول على العناصر
        const activateButton = document.querySelector('.lesson-card-activation button');
        const cardInput      = document.querySelector('.lesson-card-activation input');

        // الحصول على CSRF Token من meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // إضافة حدث النقر لزر التفعيل
        activateButton.addEventListener('click', function(e) {
            e.preventDefault();
            const cardNumber = cardInput.value.trim();
            const courseId   = this.getAttribute('data-course-id');

            if (!cardNumber) {
                alert('من فضلك أدخل رقم البطاقة');
                return;
            }

            // إظهار مؤشر تحميل (اختياري)
            activateButton.innerHTML = 'جارِ التفعيل...';
            activateButton.disabled = true;

            // إرسال طلب Ajax
            fetch('{{ route("activate.card") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ card_number: cardNumber ,course_id:courseId})
            })
            .then(response => {
                // إعادة زر التفعيل إلى حالته الأصلية
                activateButton.innerHTML = 'تفعيل البطاقة';
                activateButton.disabled = false;

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('تم تفعيل البطاقة بنجاح وإضافتك للكورس!');
                    // يمكنك إعادة توجيه المستخدم أو تحديث الصفحة
                    location.reload();
                } else {
                    alert('حدث خطأ أثناء التفعيل: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.log('Error:', error);
                alert('حدث خطأ في الاتصال بالخادم');
            });
        });
    });
</script>
<script>
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
                const courseId = this.getAttribute('data-course-id');
                const buttonId = this.getAttribute('id');
                const buttonInnerHTML = this.innerHTML;

                // إظهار مؤشر تحميل (اختياري)
                this.innerHTML = '{{__("messages.loading")}}';
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
                    // إعادة زر التسجيل إلى حالته الأصلية
                    this.innerHTML = "{{__('messages.go_to_checkout')}}";
                    this.disabled = false;

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    return response.json();
                })
                .then(data => {
                    if (buttonId && buttonId == 'buy_now') {
                        window.location.href = '{{ route("checkout") }}';
                    }else if (data.success) {
                        // عرض النافذة المنبثقة
                        modal.style.display = 'flex';
                    }
                    // console.log(data);
                })
                .catch(error => {
                    // console.log('Error:', error);
                    this.innerHTML = buttonInnerHTML;
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
