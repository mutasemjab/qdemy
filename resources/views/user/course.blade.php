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
                        <a href="javascript:void(0)" class="btn-buy">{{ __('messages.enrolled') }}</a>
                        <!-- Progress Display -->
                        <div class="progress-info">
                            <div class="progress-bar-container">
                                <div class="progress-bar" style="width: {{$course_progress}}%"></div>
                                <span class="progress-text">{{round($course_progress)}}% مكتمل</span>
                            </div>
                            <div class="progress-stats">
                                <span>{{ __('messages.completed') }}: {{$completed_videos}}</span>
                                <span>{{ __('messages.watching') }}: {{$watching_videos}}</span>
                                <span>{{ __('messages.total_videos') }}: {{$total_videos}}</span>
                            </div>
                        </div>
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
                <p>{{ __('messages.session_all_count') }} - {{$mainSections?->count()}}
                    <br> {{ __('messages.video_all_count') }} - {{$contents?->where('content_type','video')?->count()}}</p>
            </div>

            @if(!$is_enrolled && $user)
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
                                            @else
                                            <div class="disabled-lesson-video"><img data-src="{{ asset('assets_front/images/video-thumb.png') }}" alt="Video"></div>
                                            @endif

                                        @elseif($content->file_path)

                                            @if($content->is_free === 1 || $is_enrolled)
                                            <div class="lessonvideo">
                                                <a class='text-decoration-none' target='_blank' href="{{$content->file_path}}">
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
                                            @if($content->content_type === 'video' && ($content->is_free === 1 || $is_enrolled))
                                                <div class="video-progress-bar">
                                                    <div class="progress-fill" style="width: {{$progressPercent}}%"></div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- main sections Quizes -->
                                    @php $sectionsExams = $section->exams @endphp
                                    @if($is_enrolled && $sectionsExams && $sectionsExams->count())
                                        <span>{{__('messages.section Quiz')}}</span>
                                        @foreach($sectionsExams as $sectionsExam)
                                            <div class="lesson-item exam">
                                                <a class='text-decoration-none d-flex' target='_blank' href="{{route('exam',['exam'=>$course->id,'slug'=>$sectionsExam->slug])}}">
                                                <div class="lesson-info">
                                                    <h4> <i class="fa fa-question" style='color:rgba(0, 85, 210, 0.7);'></i> {{$sectionsExam->title}} </h4>
                                                    <div class="">
                                                        <br>
                                                        <i class="fa fa-check"></i> {{__('messages.عدد المحاولات')}} {{$sectionsExam->user_attempts()->count()}}
                                                        @if($sectionsExam->result_attempt()?->is_passed)
                                                            <i class="fa fa-check"></i>
                                                        @else
                                                            <i class="fa fa-times"></i>
                                                        @endif
                                                        {{$sectionsExam->result_attempt()?->percentage}}
                                                        {{__('messages.أفضل نتيجة')}} {{$sectionsExam->result_attempt()?->score}}
                                                    </div>
                                                </div>
                                                </a>
                                            </div>
                                        @endforeach
                                    @endif
                                    @endforeach


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
                                                                <span class="progress-badge">{{round($progressPercent)}}%</span>
                                                            @endif
                                                        </div>
                                                        @else
                                                        <div class="disabled-lesson-video"><img data-src="{{ asset('assets_front/images/video-thumb.png') }}" alt="Video"></div>
                                                        @endif

                                                    @elseif($subSectioncontent->file_path)

                                                        @if($subSectioncontent->is_free === 1 || $is_enrolled)
                                                        <div class="lessonvideo">
                                                            <a class='text-decoration-none' target='_blank' href="{{$subSectioncontent->file_path}}">
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
                                                        <h4>{{$subSectioncontent->title}}</h4>
                                                        @if($subSectioncontent->content_type === 'video' && ($subSectioncontent->is_free === 1 || $is_enrolled))
                                                            <div class="video-progress-bar">
                                                                <div class="progress-fill" style="width: {{$progressPercent}}%"></div>
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
                                        <span>{{__('messages.section Quiz')}}</span>
                                        @foreach($subSectionsExamss as $subSectionsExams)
                                            <div class="lesson-item exam">
                                                <a class='text-decoration-none d-flex' target='_blank' href="{{route('exam',['exam'=>$course->id,'slug'=>$subSectionsExams->slug])}}">
                                                <div class="lesson-info">
                                                    <h4> <i class="fa fa-question" style='color:rgba(0, 85, 210, 0.7);'></i> {{$subSectionsExams->title}} </h4>
                                                    <div class="">
                                                        <br>
                                                        <i class="fa fa-check"></i> {{__('messages.عدد المحاولات')}} {{$subSectionsExams->user_attempts()->count()}}
                                                        @if($subSectionsExams->result_attempt()?->is_passed)
                                                            <i class="fa fa-check"></i>
                                                        @else
                                                            <i class="fa fa-times"></i>
                                                        @endif
                                                        {{$subSectionsExams->result_attempt()?->percentage}}
                                                        {{__('messages.أفضل نتيجة')}} {{$subSectionsExams->result_attempt()?->score}}
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
                    <button class="accordion-header"> Unassigned Contents</button>
                    <div class="accordion-body">
                        @foreach($unassignedContents as $_content)
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
                                            <span class="progress-badge">{{round($progressPercent)}}%</span>
                                        @endif
                                    </div>
                                    @else
                                    <div class="disabled-lesson-video"><img data-src="{{ asset('assets_front/images/video-thumb.png') }}" alt="Video"></div>
                                    @endif

                                @elseif($_content->file_path)

                                    @if($_content->is_free === 1 || $is_enrolled)
                                    <div class="lessonvideo">
                                        <a class='text-decoration-none' target='_blank' href="{{$_content->file_path}}">
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
                                    <h4>{{$_content->title}}</h4>
                                    @if($_content->content_type === 'video' && ($_content->is_free === 1 || $is_enrolled))
                                        <div class="video-progress-bar">
                                            <div class="progress-fill" style="width: {{$progressPercent}}%"></div>
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
                    <button class="accordion-header"> {{__('messages.All course Quiez')}}</button>
                    <div class="accordion-body">
                        @foreach($exams as $exam)
                            <div class="lesson-item exam">
                                <a class='text-decoration-none d-flex' target='_blank' href="{{route('exam',['exam'=>$course->id,'slug'=>$exam->slug])}}">
                                    <div class="lesson-info">
                                    <h4> <i class="fa fa-question" style='color:rgba(0, 85, 210, 0.7);'></i> {{$exam->title}} </h4>
                                    <div class="">
                                        <br>
                                        <i class="fa fa-check"></i> {{__('messages.عدد المحاولات')}} {{$exam->user_attempts()->count()}}
                                        @if($exam->result_attempt()?->is_passed)
                                            <i class="fa fa-check"></i>
                                        @else
                                            <i class="fa fa-times"></i>
                                        @endif
                                        {{$exam->result_attempt()?->percentage}}
                                        {{__('messages.أفضل نتيجة')}} {{$exam->result_attempt()?->score}}
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


        // Card activation
        document.querySelector('.lesson-card-activation.button').addEventListener('click', function() {
            let courseId = this.getAttribute('data-course-id');
            let cardNumber = document.querySelector('.lesson-card-activation.input').value;

            if (!cardNumber) {
                alert('يرجى إدخال رقم البطاقة');
                return;
            }

            fetch('/activate-card', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    course_id: courseId,
                    card_number: cardNumber
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('تم تفعيل البطاقة بنجاح');
                    location.reload();
                } else {
                    alert(data.message || 'خطأ في تفعيل البطاقة');
                }
            })
            .catch(error => {
                alert('حدث خطأ، يرجى المحاولة مرة أخرى');
                console.error('Error:', error);
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

        // Handle enrollment buttons
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('enroll-btn')) {
                let courseId = e.target.getAttribute('data-course-id');
                let action = e.target.id === 'buy_now' ? 'buy' : 'add';

                fetch('/enroll-course', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        course_id: courseId,
                        action: action
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('enrollment-modal').classList.add('show');
                    }
                })
                .catch(error => console.error('Error:', error));
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
    let user = "{{$user?->id}}";
    let stop = 0; // لايقاف التحديث التلقائي عندما لا تساوي 0
    const COMPLETED_MINUTE = {{ env('COMPLETED_WATCHING_COURSES', 5) }};

    document.addEventListener('DOMContentLoaded', function() {

        // Handle video click
        document.addEventListener('click', function(e) {
            if (e.target.closest('.lesson-video, .playable')) {
                const element   = e.target.closest('.lesson-video, .playable');
                let videoUrl    = element.getAttribute('data-video');
                let contentId   = element.getAttribute('data-content-id');
                let isCompleted = element.getAttribute('data-is-completed');
                let duration    = element.getAttribute('data-duration') || 0;

                // التحكم في زر زر تسجيل الفيديو كمكتمل اذا كان مكتمل - اذا تواجد يوزر مسجل دخول
                if(user){
                    document.getElementById('mark-complete-btn').style.display='block';
                    if(isCompleted){
                        document.getElementById('mark-complete-btn').style.display='none';
                    }
                }
                // mark-complete-btn
                // الغاء ايقاف التحديث التلقائي ان كان موقوف
                stop = 0;

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
                stopProgressTracking();
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

    function stopProgressTracking() {
        if (progressUpdateInterval) {
            clearInterval(progressUpdateInterval);
            progressUpdateInterval = null;

            // Final progress update when closing
            if (currentVideoId && !stop) {
                updateVideoProgress(true); // تحديث التقدم عند الإغلاق
            }
        }
    }

    function updateVideoProgress(isFinalUpdate = false) {
        if (!currentVideoId || stop) return;

        let currentTime = Date.now();
        let watchedSeconds = Math.floor((currentTime - videoStartTime) / 1000) + lastWatchedTime;

        // Check if video should be marked as completed
        let shouldComplete = false;
        if (currentVideoDuration > 0) {
            let remainingMinutes = (currentVideoDuration - watchedSeconds) / 60;
            shouldComplete = remainingMinutes <= COMPLETED_MINUTE;
        }
        fetch('/update-video-progress', {
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
        fetch('/mark-video-complete', {
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
                stop = true;
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
                progressText.textContent = Math.round(progressData.course_progress) + '% ' + "{{__('messages.completed')}}";
            }

            if (progressStats) {
                progressStats.innerHTML = `
                    <span>{{__('messages.completed')}}: ${progressData.completed_videos}</span>
                    <span>{{__('messages.watching')}}: ${progressData.watching_videos}</span>
                    <span>{{__('messages.total_videos')}}: ${progressData.total_videos}</span>
                `;
            }
        }
    }
</script>

@push('styles')
<!-- <style>
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
</style> -->
<style>
    /* Reset & Base */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .course-view {
        background: #f8f9fa;
        min-height: 100vh;
    }

    /* Course Header */
    .course-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 40px 0;
        margin-bottom: 30px;
    }

    .course-header-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .course-breadcrumb {
        font-size: 14px;
        margin-bottom: 20px;
        opacity: 0.9;
    }

    .course-breadcrumb a {
        color: white;
        text-decoration: none;
    }

    .course-breadcrumb span {
        margin: 0 8px;
    }

    .course-main-title {
        font-size: 36px;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .course-description {
        font-size: 18px;
        line-height: 1.6;
        opacity: 0.95;
        margin-bottom: 25px;
    }

    .course-meta {
        display: flex;
        gap: 30px;
        align-items: center;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .teacher-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: 2px solid white;
    }

    /* Course Body Layout */
    .course-body {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 30px;
    }

    /* Curriculum Section */
    .curriculum-section {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .section-title {
        font-size: 24px;
        margin-bottom: 25px;
        color: #333;
    }

    /* Curriculum Modules */
    .curriculum-module {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        margin-bottom: 15px;
        overflow: hidden;
    }

    .module-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        background: #f9fafb;
        cursor: pointer;
        transition: background 0.3s;
    }

    .module-header:hover {
        background: #f3f4f6;
    }

    .module-title {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .module-number {
        width: 35px;
        height: 35px;
        background: #667eea;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    .module-title h3 {
        font-size: 18px;
        color: #333;
    }

    .module-info {
        display: flex;
        align-items: center;
        gap: 15px;
        color: #666;
    }

    .module-arrow {
        transition: transform 0.3s;
    }

    .module-header.active .module-arrow {
        transform: rotate(180deg);
    }

    .module-content {
        display: none;
        padding: 20px;
        background: white;
    }

    .module-content.active {
        display: block;
    }

    /* Content Items */
    .content-item {
        display: flex;
        align-items: center;
        padding: 15px;
        border-bottom: 1px solid #f0f0f0;
        transition: background 0.3s;
    }

    .content-item:hover {
        background: #f9fafb;
    }

    .content-icon {
        width: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 20px;
        color: #666;
    }

    .content-icon .completed {
        color: #10b981;
    }

    .progress-circle {
        width: 24px;
        height: 24px;
    }

    .progress-circle svg {
        transform: rotate(-90deg);
    }

    .progress-circle path {
        fill: none;
        stroke: #667eea;
        stroke-width: 4;
    }

    .content-details {
        flex: 1;
        margin-left: 15px;
    }

    .content-details h4 {
        font-size: 16px;
        color: #333;
        margin-bottom: 5px;
    }

    .content-type {
        font-size: 13px;
        color: #666;
    }

    .content-action {
        margin-left: auto;
    }

    .btn-play, .btn-download, .btn-quiz {
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s;
    }

    .btn-play {
        background: #667eea;
        color: white;
    }

    .btn-download {
        background: #10b981;
        color: white;
    }

    .btn-quiz {
        background: #f59e0b;
        color: white;
    }

    .locked-content {
        color: #9ca3af;
    }

    /* Quiz Items */
    .quiz-divider {
        padding: 10px 15px;
        background: #f3f4f6;
        color: #666;
        font-size: 14px;
        font-weight: 500;
    }

    .quiz-item {
        background: #fef3c7;
    }

    .quiz-stats {
        font-size: 13px;
        color: #92400e;
    }

    /* Sidebar */
    .course-sidebar {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .sidebar-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .preview-video {
        position: relative;
        cursor: pointer;
    }

    .preview-video img {
        width: 100%;
        display: block;
    }

    .play-button {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 60px;
        height: 60px;
        background: rgba(0,0,0,0.7);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
    }

    .preview-label {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(0,0,0,0.7);
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 12px;
    }

    .card-body {
        padding: 20px;
    }

    .price-section {
        margin-bottom: 20px;
        text-align: center;
    }

    .price {
        font-size: 32px;
        font-weight: bold;
        color: #333;
    }

    .btn {
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        margin-bottom: 10px;
        transition: all 0.3s;
    }

    .btn-primary {
        background: #667eea;
        color: white;
    }

    .btn-secondary {
        background: #e5e7eb;
        color: #333;
    }

    .btn-enrolled {
        background: #10b981;
        color: white;
    }

    /* Progress Section */
    .progress-section {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #e5e7eb;
    }

    .progress-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .progress-percentage {
        font-weight: bold;
        color: #667eea;
    }

    .progress-bar-wrapper {
        height: 8px;
        background: #e5e7eb;
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 15px;
    }

    .progress-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #667eea, #764ba2);
        transition: width 0.3s;
    }

    .progress-details {
        display: flex;
        justify-content: space-between;
    }

    .progress-stat {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 14px;
        color: #666;
    }

    /* Instructor Card */
    .instructor-card h3 {
        padding: 15px 20px;
        border-bottom: 1px solid #e5e7eb;
        margin: 0;
    }

    .instructor-info {
        padding: 20px;
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .instructor-info img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
    }

    .instructor-info h4 {
        margin-bottom: 5px;
        color: #333;
    }

    .instructor-info p {
        font-size: 14px;
        color: #666;
    }

    /* Activation Card */
    .activation-card {
        padding: 20px;
    }

    .activation-card h3 {
        margin-bottom: 10px;
        color: #333;
    }

    .activation-card p {
        font-size: 14px;
        color: #666;
        margin-bottom: 15px;
    }

    .activation-form {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .activation-input {
        padding: 10px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        font-size: 14px;
    }

    .btn-activate {
        background: #10b981;
        color: white;
    }

    /* Video Modal */
    .video-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.9);
        z-index: 1000;
    }

    .video-modal.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .video-modal-content {
        width: 90%;
        max-width: 1000px;
        position: relative;
    }

    .video-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .btn-mark-complete {
        background: #10b981;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        cursor: pointer;
    }

    .close-video {
        background: white;
        color: #333;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 20px;
    }

    .video-modal iframe {
        width: 100%;
        height: 70vh;
        border-radius: 8px;
    }

    /* Success Modal */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .modal.show {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 12px;
        width: 400px;
        overflow: hidden;
    }

    .modal-header {
        padding: 20px;
        text-align: center;
        background: #f9fafb;
    }

    .modal-header i {
        font-size: 48px;
        color: #10b981;
        margin-bottom: 10px;
    }

    .modal-body {
        padding: 20px;
        text-align: center;
    }

    .modal-footer {
        padding: 20px;
        display: flex;
        gap: 10px;
    }

    .modal-footer button {
        flex: 1;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .course-body {
            grid-template-columns: 1fr;
        }

        .course-sidebar {
            order: -1;
        }
    }

    @media (max-width: 768px) {
        .course-main-title {
            font-size: 24px;
        }

        .course-meta {
            flex-wrap: wrap;
        }

        .module-header {
            padding: 15px;
        }

        .content-item {
            padding: 10px;
        }
    }
</style>
@endpush
