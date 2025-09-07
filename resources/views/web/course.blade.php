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
                @if($freeContents?->video_url)
                <div class="lesson-card-image playable" data-video="{{$freeContents?->video_url}}"
                    data-is-completed="{{$freeContents?->is_completed}}" data-content-id="{{$freeContents?->id}}" data-watched-time="{{$freeContents?->watched_time}}" data-duration="{{$freeContents?->video_duration}}">
                    <img data-src="{{ asset('assets_front/images/video-thumb-main.png') }}" alt="Course">
                    <span class="play-overlay"><i class="fas fa-play"></i></span>
                </div>
                @endif
                <div class="lesson-card-info">
                    <h3>{{$course->title}}</h3>
                    <p class="lesson-card-p">{{$freeContents?->title}}</p>
                    <span class="price">{{$course->selling_price}} {{ CURRENCY }}</span>
                    @if($is_enrolled)
                        <a href="javascript:void(0)" class="btn-buy">{{ translate_lang('enrolled') }}</a>
                        <!-- Progress Display -->
                        <div class="progress-info">
                            <div class="progress-bar-container">
                                <div class="progress-bar" style="width: {{$course_progress}}%"></div>
                                <span class="progress-text">{{round($course_progress)}}% {{ translate_lang('completed') }}</span>
                            </div>
                            <div class="progress-stats">
                                <span>{{ translate_lang('completed') }}: {{$completed_videos}}</span>
                                <span>{{ translate_lang('watching') }}: {{$watching_videos}}</span>
                                <span>{{ translate_lang('total_videos') }}: {{$total_videos}}</span>
                            </div>
                        </div>
                    @elseif(is_array($user_courses) && in_array($course->id,$user_courses))
                      <a href="{{route('checkout')}}"class="btn-buy">{{ translate_lang('buy_now') }}</a>
                      <a href="{{route('checkout')}}"class="btn-add">{{ translate_lang('go_to_checkout') }}</a>
                    @else
                        <a id='buy_now' href="javascript:void(0)" data-course-id="{{ $course->id }}" class="enroll-btn btn-buy">{{ translate_lang('buy_now') }}</a>
                        <a href="javascript:void(0)" data-course-id="{{ $course->id }}" class="enroll-btn btn-add">{{ translate_lang('add_to_cart') }}</a>
                    @endif
                    <a href="{{ route('contacts') }}" class="text-decoration-none"><p class="report">{{ translate_lang('report_this_course') }}</p></a>
                </div>
            </div>

            <div class="lesson-teacher">
                <img data-src="{{ $course->teacher?->photo_url }}" alt="Instructor">
                <h4>{{$course->teacher?->name}}</h4>
                <p>{{ translate_lang('session_all_count') }} - {{$mainSections?->count()}}
                    <br> {{ translate_lang('video_all_count') }} - {{$contents?->where('content_type','video')?->count()}}</p>
            </div>

            @if(!$is_enrolled && $user)
            <div class="lesson-card-activation">
                <h3>{{ translate_lang('card_qdemy') }}</h3>
                <p>{{ translate_lang('enter_card_qdemy') }}</p>
                <input class="lesson-card-activation input" type="text" placeholder="{{ translate_lang('enter_card_here') }}">
                <button data-course-id="{{$course->id}}" class="lesson-card-activation button">{{ translate_lang('activate_card') }}</button>
            </div>
            @endif

        </div>

        <!-- Left Content -->
        <div class="lesson-content">
            <h2 class="lesson-title">{{$course->title}}</h2>
            <p class="lesson-desc">{{$course->description}}</p>

            <h3 class="lesson-subtitle">{{ translate_lang('course_content') }}</h3>

            @if($mainSections && $mainSections->count())
            <div class="accordion">
                @foreach($mainSections as $section)
                <div class="accordion-item">
                    <button class="accordion-header"> {{$section->title}}</button>
                        <div class="accordion-body">
                            @php
                                $sectionContents = $section->contents;
                                $subSections    = $section->children; // Assuming you have a `children` relationship for submainSections
                            @endphp

                            @if($sectionContents && $sectionContents->count())
                                @foreach($sectionContents as $content)
                                    @php
                                        $userProgress = $user_progress[$content->id] ?? null;
                                        $progressPercent = $content->video_duration > 0 ? min(100, ($content->watched_time / $content->video_duration) * 100) : 0;
                                    @endphp
                                    <!-- main sections  -->
                                    <div class="lesson-item" data-content-id="{{$content->id}}">
                                        @if($content->video_url)

                                            @if($content->is_free === 1 || $is_enrolled)
                                            <div class="lesson-video" data-video="{{$content->video_url}}"
                                                data-is-completed="{{$content->is_completed}}" data-content-id="{{$content->id}}" data-watched-time="{{$content->watched_time}}" data-duration="{{$content->video_duration}}">
                                                <img data-src="{{ asset('assets_front/images/video-thumb.png') }}" alt="Video">
                                                <span class="play-icon">▶</span>
                                                @if($content->is_completed)
                                                    <span class="completion-badge">✓</span>
                                                @elseif($content->watched_time > 0)
                                                    <span class="progress-badge">{{round($progressPercent)}}%</span>
                                                @endif
                                            </div>
                                            @if(!$is_enrolled) <span class='free'>free</span>@endif
                                            @else
                                            <div class="disabled-lesson-video"><img data-src="{{ asset('assets_front/images/video-thumb.png') }}" alt="Video"></div>
                                            @endif

                                        @elseif($content->file_path)

                                            @if($content->is_free === 1 || $is_enrolled)
                                            <div class="lessonvideo">
                                                <a class='text-decoration-none' target='_blank' href="{{$content->file_path}}">
                                                    <i class="fa fa-file" style='color:rgba(0, 85, 210, 0.7);'>
                                                </i>
                                                </a>
                                                @if(!$is_enrolled) <span class='free'>free</span>@endif
                                            </div>

                                            @else
                                                <a class='text-decoration-none' href="javascrip:void(0)">
                                                    <i class="fa fa-file" style='color:lightgray;'></i>
                                                </a>
                                            @endif

                                        @endif

                                        <div class="lesson-info">
                                            <h4>{{$content->title}}</h4>
                                            @if($content->content_type === 'video' && $is_enrolled)
                                                <div class="video-progress-bar">
                                                    <div class="progress-fill" style="width: {{$progressPercent}}%"></div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    @endforeach

                                    <!-- main sections Quizes -->
                                    @php $sectionsExams = $section->exams @endphp
                                    @if($is_enrolled && $sectionsExams && $sectionsExams->count())
                                        <span>{{translate_lang('section Quiz')}}</span>
                                        @foreach($sectionsExams as $sectionsExam)
                                            <div class="lesson-item exam">
                                                <a class='text-decoration-none d-flex' target='_blank' href="{{route('exam',['exam'=>$sectionsExam->id,'slug'=>$sectionsExam->slug])}}">
                                                <div class="lesson-info">
                                                    <h4> <i class="fa fa-question" style='color:rgba(0, 85, 210, 0.7);'></i> {{$sectionsExam->title}} </h4>
                                                    <div class="">
                                                        <br>
                                                        <i class="fa fa-check"></i> {{translate_lang('عدد المحاولات')}} {{$sectionsExam->user_attempts()->count()}}
                                                        @if($sectionsExam->result_attempt()?->is_passed)
                                                            <i class="fa fa-check"></i>
                                                        @else
                                                            <i class="fa fa-times"></i>
                                                        @endif
                                                        {{$sectionsExam->result_attempt()?->percentage}}
                                                    </div>
                                                </div>
                                                </a>
                                            </div>
                                        @endforeach
                                    @endif


                            @endif

                            {{-- Display SubmainSections --}}
                            @if($subSections && $subSections->count())

                                <div class="accordion">
                                    @foreach($subSections as $subSection)

                                    <div class="accordion-item">
                                        <button class="accordion-header"> {{$subSection->title}}</button>
                                        <div class="accordion-body">
                                            @php $subSectionContents = $subSection->contents @endphp
                                            @if($subSectionContents && $subSectionContents->count())
                                                @foreach($subSectionContents as $subSectioncontent)

                                                @php
                                                    $subContProgressPercent = $subSectioncontent->video_duration > 0 ? min(100, ($subSectioncontent->watched_time / $subSectioncontent->video_duration) * 100) : 0;
                                                @endphp

                                                <div class="lesson-item" data-content-id="{{$subSectioncontent->id}}">
                                                    @if($subSectioncontent->video_url)

                                                        @if($subSectioncontent->is_free === 1 || $is_enrolled)
                                                        <div class="lesson-video" data-video="{{$subSectioncontent->video_url}}"
                                                            data-is-completed="{{$subSectioncontent->is_completed}}" data-content-id="{{$subSectioncontent->id}}" data-watched-time="{{$subSectioncontent->watched_time}}" data-duration="{{$subSectioncontent->video_duration}}">
                                                            <img data-src="{{ asset('assets_front/images/video-thumb.png') }}" alt="Video">
                                                            <span class="play-icon">▶</span>
                                                            @if($subSectioncontent->is_completed)
                                                                <span class="completion-badge">✓</span>
                                                            @elseif($subSectioncontent->watched_time > 0)
                                                                <span class="progress-badge">{{round($subContProgressPercent)}}%</span>
                                                            @endif
                                                        </div>
                                                        @if(!$is_enrolled) <span class='free'>free</span>@endif
                                                        @else
                                                        <div class="disabled-lesson-video"><img data-src="{{ asset('assets_front/images/video-thumb.png') }}" alt="Video"></div>
                                                        @endif

                                                    @elseif($subSectioncontent->file_path)

                                                        @if($subSectioncontent->is_free === 1 || $is_enrolled)
                                                        <div class="lessonvideo">
                                                            <a class='text-decoration-none' target='_blank' href="{{$subSectioncontent->file_path}}">
                                                                <i class="fa fa-file" style='color:rgba(0, 85, 210, 0.7);'>
                                                            </i></a>
                                                            @if(!$is_enrolled) <span class='free'>free</span>@endif
                                                        </div>
                                                        @else
                                                            <a class='text-decoration-none' href="javascrip:void(0)">
                                                                <i class="fa fa-file" style='color:lightgray;'></i>
                                                            </a>
                                                        @endif

                                                    @endif
                                                    <div class="lesson-info">
                                                        <h4>{{$subSectioncontent->title}}</h4>
                                                        @if($subSectioncontent->content_type === 'video' && ($subSectioncontent->is_free === 1 || $is_enrolled))
                                                            <div class="video-progress-bar">
                                                                <div class="progress-fill" style="width: {{$subContProgressPercent}}%"></div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>

                                    <!-- sub sections Quizes -->
                                    @php $subSectionsExams = $subSection->exams @endphp
                                    @if($is_enrolled && $subSectionsExams && $subSectionsExams->count())
                                        <span>{{translate_lang('section Quiz')}}</span>
                                        @foreach($subSectionsExamss as $subSectionsExams)
                                            <div class="lesson-item exam">
                                                <a class='text-decoration-none d-flex' target='_blank' href="{{route('exam',['exam'=>$subSectionsExams->id,'slug'=>$subSectionsExams->slug])}}">
                                                <div class="lesson-info">
                                                    <h4> <i class="fa fa-question" style='color:rgba(0, 85, 210, 0.7);'></i> {{$subSectionsExams->title}} </h4>
                                                    <div class="">
                                                        <br>
                                                        <i class="fa fa-check"></i> {{translate_lang('عدد المحاولات')}} {{$subSectionsExams->user_attempts()->count()}}
                                                        @if($subSectionsExams->result_attempt()?->is_passed)
                                                            <i class="fa fa-check"></i>
                                                        @else
                                                            <i class="fa fa-times"></i>
                                                        @endif
                                                        {{$subSectionsExams->result_attempt()?->percentage}}
                                                    </div>
                                                </div>
                                                </a>
                                            </div>
                                        @endforeach
                                    @endif
                                    @endforeach

                                </div>
                            @endif

                        </div>
                </div>
                @endforeach

                {{-- Display Contents Not Belonging to Any Section --}}
                @php
                    $unassignedContents = $course->contents->where('section_id',null)
                @endphp
                @if($unassignedContents->count())
                <div class="accordion-item">
                    <button class="accordion-header">{{translate_lang('Unassigned Contents')}} </button>
                    <div class="accordion-body">
                        @foreach($unassignedContents as $_content)
                            @php
                                $unasgndCntProgressPercent = $_content->video_duration > 0 ? min(100, ($_content->watched_time / $_content->video_duration) * 100) : 0;
                            @endphp
                            <div class="lesson-item" data-content-id="{{$_content->id}}">
                                @if($_content->video_url)

                                    @if($_content->is_free === 1 || $is_enrolled)
                                    <div class="lesson-video" data-video="{{$_content->video_url}}"
                                        data-is-completed="{{$_content->is_completed}}" data-content-id="{{$_content->id}}" data-watched-time="{{$_content->watched_time}}" data-duration="{{$_content->video_duration}}">
                                        <img data-src="{{ asset('assets_front/images/video-thumb.png') }}" alt="Video">
                                        <span class="play-icon">▶</span>
                                        @if($_content->is_completed)
                                            <span class="completion-badge">✓</span>
                                        @elseif($_content->watched_time > 0)
                                            <span class="progress-badge">{{round($unasgndCntProgressPercent)}}%</span>
                                        @endif
                                    </div>
                                    @if(!$is_enrolled) <span class='free'>free</span>@endif
                                    @else
                                    <div class="disabled-lesson-video"><img data-src="{{ asset('assets_front/images/video-thumb.png') }}" alt="Video"></div>
                                    @endif

                                @elseif($_content->file_path)

                                    @if($_content->is_free === 1 || $is_enrolled)
                                    <div class="lessonvideo">
                                        <a class='text-decoration-none' target='_blank' href="{{$_content->file_path}}">
                                            <i class="fa fa-file" style='color:rgba(0, 85, 210, 0.7);'>
                                        </i></a>
                                        @if(!$is_enrolled) <span class='free'>free</span>@endif
                                    </div>
                                    @else
                                        <a class='text-decoration-none' href="javascrip:void(0)">
                                            <i class="fa fa-file" style='color:lightgray;'></i>
                                        </a>
                                    @endif

                                @endif
                                <div class="lesson-info">
                                    <h4>{{$_content->title}}</h4>
                                    @if($_content->content_type === 'video' && ($_content->is_free === 1 || $is_enrolled))
                                        <div class="video-progress-bar">
                                            <div class="progress-fill" style="width: {{$unasgndCntProgressPercent}}%"></div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- all course quiez -->
                @if($is_enrolled && $exams && $exams->count())
                <div class="accordion-item">
                    <button class="accordion-header"> {{translate_lang('All course Quiez')}}</button>
                    <div class="accordion-body">
                        @foreach($exams as $exam)
                            <div class="lesson-item exam">
                                <a class='text-decoration-none d-flex' target='_blank' href="{{route('exam',['exam'=>$exam->id,'slug'=>$exam->slug])}}">
                                    <div class="lesson-info">
                                    <h4> <i class="fa fa-question" style='color:rgba(0, 85, 210, 0.7);'></i> {{$exam->title}} </h4>
                                    <div class="">
                                        <br>
                                        <i class="fa fa-check"></i> {{translate_lang('trying times')}} {{$exam->user_attempts()->count()}}
                                        @if($exam->result_attempt() && $exam->result_attempt()->is_passed)
                                            <i class="fa fa-check"></i>
                                        @elseif($exam->result_attempt() && !$exam->result_attempt()->is_passed)
                                        <i class="fa fa-times"></i>
                                        @endif

                                        @if($exam->result_attempt())
                                            {{translate_lang('best_results')}}
                                            {{$exam->result_attempt()?->percentage}}%
                                        @endif
                                    </div>
                                </div>
                               </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
            @endif

        </div>
    </div>

</section>

<!-- Video Popup -->
<div class="video-popup">
    <div class="video-popup-content">
        <span class="close-popup">&times;</span>
        <div class="video-controls">
            <button style="@if(!$user?->id)display:none;@endif" id="mark-complete-btn" class="mark-complete-btn">تسجيل كمكتمل</button>
        </div>
        <iframe src="" frameborder="0" allowfullscreen></iframe>
    </div>
</div>

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
@endsection

@push('scripts')
<script>
    let user = "{{$user?->id}}";
    let isEnrolled = {{$is_enrolled}};
</script>
<script>
    // Card activation
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

            if(!user){
                alert("{{translate_lang('please login first .')}}");
                return 0;
            }else if (!cardNumber) {
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
    // enrollment
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
                    alert("{{__('front.login_required')}}");
                    return 0;
                }

                const courseId = this.getAttribute('data-course-id');
                const buttonId = this.getAttribute('id');
                const buttonInnerHTML = this.innerHTML;

                // إظهار مؤشر تحميل (اختياري)
                this.innerHTML = '{{translate_lang("loading")}}';
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
                    return response.json();
                })
                .then(data => {
                    if (buttonId && buttonId == 'buy_now') {
                        window.location.href = '{{ route("checkout") }}';
                    }
                    if (data.success) {
                        // عرض النافذة المنبثقة
                        modal.style.display = 'flex';
                        this.innerHTML = "{{translate_lang('go_to_checkout')}}";
                        this.disabled = false;
                    }else {
                         alert('حدث خطأ أثناء إضافة الكورس: ' + (data.message || 'Unknown error'));
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


        // Modal functionality
        document.querySelector('.close').addEventListener('click', function() {
            document.getElementById('enrollment-modal').classList.remove('show');
        });

        document.getElementById('continue-shopping').addEventListener('click', function() {
            document.getElementById('enrollment-modal').classList.remove('show');
        });

        document.getElementById('go-to-checkout').addEventListener('click', function() {
            window.location.href = '{{ route("checkout") }}';
        });
        // Accordion functionality
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('accordion-header')) {
                e.target.classList.toggle('active');
                const body = e.target.nextElementSibling;
                body.classList.toggle('active');
            }
        });
    });
</script>
<!-- save user progress -->

<script>
    let currentVideoId = null;
    let videoStartTime = 0;
    let progressUpdateInterval = null;
    let currentVideoDuration = 0;
    let lastWatchedTime = 0; // وقت المشاهدة الأخير
    let stopProgress = 0; // لايقاف التحديث التلقائي عندما لا تساوي 0
    const COMPLETED_MINUTE = {{ env('COMPLETED_WATCHING_COURSES', 5) }};

    document.addEventListener('DOMContentLoaded', function() {

        // Handle video click
        document.addEventListener('click', function(e) {
            if (e.target.closest('.lesson-video, .playable')) {
                const element   = e.target.closest('.lesson-video, .playable');
                let videoUrl    = element.getAttribute('data-video');
                let videoProgress = element.getAttribute('data-progress');
                let contentId   = element.getAttribute('data-content-id');
                let isCompleted = element.getAttribute('data-is-completed');
                let duration    = element.getAttribute('data-duration') || 0;

                // التحكم في زر زر تسجيل الفيديو كمكتمل اذا كان مكتمل - اذا تواجد يوزر مسجل دخول - اذا كان الفيديو ف الاشتراك
                if(user && isEnrolled){
                    document.getElementById('mark-complete-btn').style.display='block';
                    if(isCompleted){
                        document.getElementById('mark-complete-btn').style.display='none';
                    }
                }

                // mark-complete-btn
                // الغاء ايقاف التحديث التلقائي ان كان موقوف
                stopProgress = 0;
                if(!isEnrolled){
                    stopProgress = 1;
                }

                // استرداد وقت المشاهدة الأخير
                lastWatchedTime = parseInt(element.getAttribute('data-watched-time')) || 0;

                if (videoUrl && contentId) {
                    currentVideoId = contentId;
                    currentVideoDuration = duration;
                    videoStartTime = Date.now();
                    // اضبط بداية الفيديو إلى وقت المشاهدة الأخير
                    videoUrl = fixYouTubeUrl(videoUrl);

                    document.querySelector('.video-popup iframe').src = videoUrl;
                    document.querySelector('.video-popup').classList.add('active');
                    // Start progress tracking
                    //  dont update progress if user deoesnt sighn in
                    if(!user) return 0;
                    startProgressTracking();
                }

            }
        });

        //  dont update progress if user deoesnt sighn in
        if(!user) return 0;

        // Close video popup
        // تحديث التقدم عند الإغلاق
        const popup = document.querySelector('.video-popup');
        const closeButton = document.querySelector('.close-popup');
        // إضافة مستمع حدث واحد
        popup.addEventListener('click', function(event) {
            // تحقق إذا كان العنصر المستهدف هو زر الإغلاق أو العنصر الخارجي
            if (event.target === closeButton || event.target === popup) {
                document.querySelector('.video-popup').classList.remove('active');
                document.querySelector('.video-popup iframe').src = '';
                stopProgressProgressTracking();
            }
        });

        // Mark as complete button
        document.getElementById('mark-complete-btn').addEventListener('click', function() {
            if (currentVideoId) {
                markVideoComplete(currentVideoId);
            }
        });

    });

    function fixYouTubeUrl(url) {
        // lastWatchedTime = parseInt(element.getAttribute('data-watched-time')) || 0;
        let start = (lastWatchedTime <= currentVideoDuration) ? lastWatchedTime :0;
        if (url.includes('youtube.com/watch?v=')) {
            let videoId = url.split('v=')[1].split('&')[0];
            return `https://www.youtube.com/embed/${videoId}?rel=0&modestbranding=1` + `&start=${start}`;
        } else if (url.includes('youtu.be/')) {
            let videoId = url.split('youtu.be/')[1].split('?')[0];
            return `https://www.youtube.com/embed/${videoId}?rel=0&modestbranding=1` + `&start=${start}`;
        } else if (url.includes('youtube.com/embed/')) {
            return url + (url.includes('?') ? '&' : '?') + 'rel=0&modestbranding=1' + `&start=${start}`;
        }
        return url;
    }

    function startProgressTracking() {
        if (progressUpdateInterval) {
            clearInterval(progressUpdateInterval);
        }

        // Update progress every 10 seconds
        progressUpdateInterval = setInterval(function() {
            updateVideoProgress();
        }, 3000);
    }

    function stopProgressProgressTracking() {
        if (progressUpdateInterval) {
            clearInterval(progressUpdateInterval);
            progressUpdateInterval = null;

            // Final progress update when closing
            if (currentVideoId && !stopProgress) {
                updateVideoProgress(true); // تحديث التقدم عند الإغلاق
            }
        }
    }

    function updateVideoProgress(isFinalUpdate = false) {
        if (!currentVideoId || stopProgress) return;

        let currentTime = Date.now();
        let watchedSeconds = Math.floor((currentTime - videoStartTime) / 1000) + lastWatchedTime;

        // Check if video should be marked as completed
        let shouldComplete = false;
        if (currentVideoDuration > 0) {
            let remainingMinutes = (currentVideoDuration - watchedSeconds) / 60;
            shouldComplete = remainingMinutes <= COMPLETED_MINUTE;
        }
        fetch("{{route('video.progress.update')}}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                content_id: currentVideoId,
                watch_time: watchedSeconds,
                completed: shouldComplete ? 1 : 0,
                is_final_update: isFinalUpdate
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateProgressDisplay(data.progress);

                // Update visual indicators
                if (data.completed) {
                    updateVideoCompletionStatus(currentVideoId, true);
                }
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function markVideoComplete(contentId) {
        fetch("{{route('video.progress.complete')}}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                content_id: contentId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateVideoCompletionStatus(contentId, true);
                updateProgressDisplay(data.progress);
                stopProgress = true;
                document.getElementById('mark-complete-btn').style.display='none';
                console.log('تم تسجيل الفيديو كمكتمل المشاهدة');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function updateVideoCompletionStatus(contentId, completed) {
        const videoElement = document.querySelector(`.lesson-item[data-content-id="${contentId}"] .lesson-video`);

        if (completed && videoElement) {
            const progressBadge = videoElement.querySelector('.progress-badge');
            if (progressBadge) {
                progressBadge.remove();
            }

            if (!videoElement.querySelector('.completion-badge')) {
                const completionBadge = document.createElement('span');
                completionBadge.className = 'completion-badge';
                completionBadge.textContent = '✓';
                videoElement.appendChild(completionBadge);
            }

            const progressFill = videoElement.closest('.lesson-item').querySelector('.progress-fill');
            if (progressFill) {
                progressFill.style.width = '100%';
            }
        }
    }

    function updateProgressDisplay(progressData) {
        if (progressData) {
            const progressBar = document.querySelector('.progress-bar');
            const progressText = document.querySelector('.progress-text');
            const progressStats = document.querySelector('.progress-stats');

            if (progressBar) {
                progressBar.style.width = progressData.course_progress + '%';
            }

            if (progressText) {
                progressText.textContent = Math.round(progressData.course_progress) + '% ' + "{{translate_lang('completed')}}";
            }

            if (progressStats) {
                progressStats.innerHTML = `
                    <span>{{translate_lang('completed')}}: ${progressData.completed_videos}</span>
                    <span>{{translate_lang('watching')}}: ${progressData.watching_videos}</span>
                    <span>{{translate_lang('total_videos')}}: ${progressData.total_videos}</span>
                `;
            }
        }
    }
</script>

@push('styles')
<style>
    .progress-info {
        margin-top: 15px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .progress-bar-container {
        position: relative;
        width: 100%;
        height: 20px;
        background: #e9ecef;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 10px;
    }

    .progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #28a745, #20c997);
        transition: width 0.3s ease;
    }

    .progress-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 12px;
        font-weight: bold;
        color: #333;
    }

    .progress-stats {
        display: flex;
        justify-content: space-between;
        font-size: 13px;
        color: #666;
    }

    .completion-badge {
        position: absolute;
        top: 5px;
        right: 5px;
        background: #28a745;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }

    .progress-badge {
        position: absolute;
        top: 5px;
        right: 5px;
        background: #ffc107;
        color: #333;
        border-radius: 10px;
        padding: 2px 6px;
        font-size: 10px;
        font-weight: bold;
    }

    .video-progress-bar {
        width: 100%;
        height: 3px;
        background: #e9ecef;
        border-radius: 2px;
        overflow: hidden;
        margin-top: 5px;
    }

    .progress-fill {
        height: 100%;
        background: #007bff;
        transition: width 0.3s ease;
    }

    .video-controls {
        position: absolute;
        top: 15px;
        right: 50px;
        z-index: 1000;
    }

    .mark-complete-btn {
        background: #28a745;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
    }

    .mark-complete-btn:hover {
        background: #218838;
    }

    .lesson-video {
        position: relative;
    }
</style>
<style>
    /* Exam Item Styling */
    .lesson-item.exam {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border: 1px solid #e0e6ed;
        border-radius: 8px;
        padding: 12px 16px;
        margin: 10px 0;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .lesson-item.exam::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 4px;
        background: linear-gradient(180deg, #4a90e2 0%, #0055d2 100%);
    }

    .lesson-item.exam:hover {
        box-shadow: 0 3px 12px rgba(0, 85, 210, 0.1);
        transform: translateY(-2px);
        border-color: #d0dae5;
    }

    .lesson-item.exam a {
        display: flex !important;
        align-items: center;
        width: 100%;
        color: inherit;
    }

    .lesson-item.exam .lesson-info {
        flex: 1;
        padding-left: 8px;
    }

    .lesson-item.exam h4 {
        margin: 0 0 8px 0;
        color: #2c3e50;
        font-size: 16px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .lesson-item.exam h4 i {
        font-size: 18ンpx;
        background: rgba(0, 85, 210, 0.1);
        padding: 6px;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Exam Stats Styling */
    .lesson-item.exam .lesson-info > div {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        font-size: 14px;
        color: #6c757d;
        margin-top: 8px;
    }

    .lesson-item.exam .lesson-info > div > * {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .lesson-item.exam .fa-check {
        color: #28a745;
        font-size: 12px;
    }

    .lesson-item.exam .fa-times {
        color: #dc3545;
        font-size: 12px;
    }

    /* File/Resource Item Styling */
    .lesson-item .lessonvideo a,
    .lesson-item a[href*="file_path"] {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        background: linear-gradient(135deg, #f0f4f8 0%, #ffffff 100%);
        border: 1px solid #d0dae5;
        border-radius: 6px;
        transition: all 0.3s ease;
        text-decoration: none !important;
        color: #2c3e50;
    }

    .lesson-item .lessonvideo a:hover,
    .lesson-item a[href*="file_path"]:hover {
        background: linear-gradient(135deg, #e8f0f7 0%, #f5f8fb 100%);
        border-color: rgba(0, 85, 210, 0.3);
        transform: translateX(3px);
    }

    .lesson-item .lessonvideo a i,
    .lesson-item a[href*="file_path"] i {
        font-size: 16px;
        color: rgba(0, 85, 210, 0.7);
    }

    /* Add "Download Resource" text after file icon */
    .lesson-item .lessonvideo a::after {
        content: 'Download Resource';
        font-size: 14px;
        color: #4a5568;
        font-weight: 500;
    }

    /* Disabled file style */
    .lesson-item a[href="javascrip:void(0)"] i,
    .lesson-item a[href="javascript:void(0)"] i {
        color: #b0b9c3 !important;
    }

    .lesson-item a[href="javascrip:void(0)"]::after,
    .lesson-item a[href="javascript:void(0)"]::after {
        content: 'Locked Resource';
        font-size: 14px;
        color: #a0a9b3;
        margin-left: 8px;
    }

    /* Section Quiz Label Styling */
    .accordion-body > span:contains('Quiz') {
        display: inline-block;
        margin: 15px 0 10px 0;
        padding: 4px 12px;
        background: rgba(0, 85, 210, 0.08);
        color: #0055d2;
        border-radius: 4px;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Video progress enhancements */
    .video-progress-bar {
        height: 4px;
        background: #e9ecef;
        border-radius: 2px;
        overflow: hidden;
        margin-top: 8px;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #4a90e2 0%, #0055d2 100%);
        transition: width 0.3s ease;
        border-radius: 2px;
    }

    /* Completion and Progress Badges Enhancement */
    .completion-badge {
        position: absolute;
        top: 5px;
        right: 5px;
        background: #28a745;
        color: white;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
        box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);
    }

    .progress-badge {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(0, 85, 210, 0.9);
        color: white;
        padding: 2px 6px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        box-shadow: 0 2px 4px rgba(0, 85, 210, 0.3);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .lesson-item.exam .lesson-info > div {
            flex-direction: column;
            gap: 8px;
        }

        .lesson-item .lessonvideo a::after {
            content: 'Download';
        }
    }
</style>
@endpush
