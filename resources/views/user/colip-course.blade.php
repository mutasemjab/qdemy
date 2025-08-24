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
                <div class="lesson-card-image playable" data-video="{{$freeContents?->video_url}}" data-content-id="{{$freeContents?->id}}" data-watched-time="{{$freeContents?->watched_time}}" data-duration="{{$freeContents?->video_duration}}">
                    <img data-src="{{ asset('assets_front/images/video-thumb-main.png') }}" alt="Course">
                    <span class="play-overlay"><i class="fas fa-play"></i></span>
                </div>
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
                                <span>{{ __('messages.total') }}: {{$total_videos}}</span>
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
                                @php
                                    $userProgress = $user_progress[$content->id] ?? null;
                                    $progressPercent = $content->video_duration > 0 ? min(100, ($content->watched_time / $content->video_duration) * 100) : 0;
                                @endphp
                                <div class="lesson-item" data-content-id="{{$content->id}}">
                                    @if($content->video_url)

                                        @if($content->is_free === 1 || $is_enrolled)
                                        <div class="lesson-video" data-video="{{$content->video_url}}" data-content-id="{{$content->id}}" data-watched-time="{{$content->watched_time}}" data-duration="{{$content->video_duration}}">
                                            <img data-src="{{ asset('assets_front/images/video-thumb.png') }}" alt="Video">
                                            <span class="play-icon">▶</span>
                                            @if($content->is_completed)
                                                <span class="completion-badge">✓</span>
                                            @elseif($content->watched_time > 0)
                                                <span class="progress-badge">{{round($progressPercent)}}%</span>
                                            @endif
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
                                        @if($content->content_type === 'video' && ($content->is_free === 1 || $is_enrolled))
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
        <div class="video-controls">
            <button id="mark-complete-btn" class="mark-complete-btn">تسجيل كمكتمل</button>
        </div>
        <div class="video-container">
            <iframe id="video-iframe" src="" frameborder="0" allowfullscreen></iframe>
            <video id="video-player" style="display:none;" controls></video>
        </div>
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

<!-- Enhanced video progress tracking -->
@section('scripts')
<script>
    let currentVideoId = null;
    let currentVideoDuration = 0;
    let lastWatchedTime = 0;
    let progressUpdateInterval = null;
    let isYouTubeVideo = false;
    let ytPlayer = null;
    let videoPlayer = null;
    let currentVideoElement = null;

    const COMPLETED_MINUTE = {{ env('COMPLETED_WATCHING_COURSES', 5) }};

    // Load YouTube API
    function loadYouTubeAPI() {
        if (window.YT && window.YT.Player) return Promise.resolve();

        return new Promise((resolve) => {
            const tag = document.createElement('script');
            tag.src = "https://www.youtube.com/iframe_api";
            const firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

            window.onYouTubeIframeAPIReady = resolve;
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        loadYouTubeAPI();

        // Handle video click
        document.addEventListener('click', function(e) {
            if (e.target.closest('.lesson-video, .playable')) {
                const element = e.target.closest('.lesson-video, .playable');
                let videoUrl = element.getAttribute('data-video');
                let contentId = element.getAttribute('data-content-id');
                let duration = parseInt(element.getAttribute('data-duration')) || 0;

                lastWatchedTime = parseInt(element.getAttribute('data-watched-time')) || 0;
                currentVideoElement = element;

                if (videoUrl && contentId) {
                    currentVideoId = contentId;
                    currentVideoDuration = duration;

                    openVideoModal(videoUrl, lastWatchedTime);
                }
            }
        });

        // Close video popup
        document.querySelector('.close-popup').addEventListener('click', function() {
            closeVideoModal();
        });

        // Mark as complete button
        document.getElementById('mark-complete-btn').addEventListener('click', function() {
            if (currentVideoId) {
                markVideoComplete(currentVideoId);
            }
        });
    });

    function openVideoModal(videoUrl, startTime = 0) {
        const iframe = document.getElementById('video-iframe');
        const videoElement = document.getElementById('video-player');

        if (isYouTubeURL(videoUrl)) {
            // YouTube video handling
            isYouTubeVideo = true;
            iframe.style.display = 'block';
            videoElement.style.display = 'none';

            const videoId = extractYouTubeID(videoUrl);
            iframe.src = `https://www.youtube.com/embed/${videoId}?enablejsapi=1&start=${startTime}&rel=0&modestbranding=1`;

            // Initialize YouTube player when ready
            setTimeout(() => {
                if (window.YT && window.YT.Player) {
                    ytPlayer = new YT.Player('video-iframe', {
                        events: {
                            'onReady': onYouTubePlayerReady,
                            'onStateChange': onYouTubePlayerStateChange
                        }
                    });
                }
            }, 5000);
        } else {
            // Direct video file handling
            isYouTubeVideo = false;
            iframe.style.display = 'none';
            videoElement.style.display = 'block';

            videoElement.src = videoUrl;
            videoElement.currentTime = startTime;
            videoPlayer = videoElement;

            // Add event listeners for direct video
            videoElement.addEventListener('loadedmetadata', function() {
                startProgressTracking();
            });

            videoElement.addEventListener('play', function() {
                startProgressTracking();
            });

            videoElement.addEventListener('pause', function() {
                updateVideoProgress();
            });

            videoElement.addEventListener('ended', function() {
                markVideoComplete(currentVideoId);
            });
        }

        document.querySelector('.video-popup').classList.add('active');
    }

    function closeVideoModal() {
        document.querySelector('.video-popup').classList.remove('active');

        // Stop progress tracking
        stopProgressTracking();

        // Clean up players
        if (ytPlayer) {
            ytPlayer.destroy();
            ytPlayer = null;
        }

        if (videoPlayer) {
            videoPlayer.pause();
            videoPlayer.src = '';
            videoPlayer = null;
        }

        document.getElementById('video-iframe').src = '';
        currentVideoId = null;
        isYouTubeVideo = false;
    }

    function onYouTubePlayerReady(event) {
        // Seek to last watched time
        if (lastWatchedTime > 0) {
            event.target.seekTo(lastWatchedTime, true);
        }
        startProgressTracking();
    }

    function onYouTubePlayerStateChange(event) {
        if (event.data == YT.PlayerState.PLAYING) {
            startProgressTracking();
        } else if (event.data == YT.PlayerState.PAUSED || event.data == YT.PlayerState.ENDED) {
            updateVideoProgress();
        }

        if (event.data == YT.PlayerState.ENDED) {
            markVideoComplete(currentVideoId);
        }
    }

    function startProgressTracking() {
        if (progressUpdateInterval) {
            clearInterval(progressUpdateInterval);
        }

        // Update progress every 5 seconds
        progressUpdateInterval = setInterval(function() {
            updateVideoProgress();
        }, 5000);
    }

    function stopProgressTracking() {
        if (progressUpdateInterval) {
            clearInterval(progressUpdateInterval);
            progressUpdateInterval = null;

            // Final update when stopping
            updateVideoProgress(true);
        }
    }

    function getCurrentVideoTime() {
        if (isYouTubeVideo && ytPlayer && ytPlayer.getCurrentTime) {
            return Math.floor(ytPlayer.getCurrentTime());
        } else if (videoPlayer && videoPlayer.currentTime) {
            return Math.floor(videoPlayer.currentTime);
        }
        return 0;
    }

    function updateVideoProgress(isFinalUpdate = false) {
        if (!currentVideoId) return;

        const currentTime = getCurrentVideoTime();
        if (currentTime <= 0) return;

        // Check if video should be marked as completed
        let shouldComplete = false;
        if (currentVideoDuration > 0) {
            let remainingMinutes = (currentVideoDuration - currentTime) / 60;
            shouldComplete = remainingMinutes <= COMPLETED_MINUTE || currentTime >= currentVideoDuration - 30;
        }

        fetch('/update-video-progress', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                content_id: currentVideoId,
                watch_time: currentTime,
                completed: shouldComplete ? 1 : 0,
                is_final_update: isFinalUpdate
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update watched time attribute
                if (currentVideoElement) {
                    currentVideoElement.setAttribute('data-watched-time', currentTime);
                }

                updateProgressDisplay(data.progress);
                updateVideoVisualProgress(currentVideoId, currentTime, data.completed);

                if (data.completed) {
                    updateVideoCompletionStatus(currentVideoId, true);
                }
            }
        })
        .catch(error => console.error('Error updating progress:', error));
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
                alert('تم تسجيل الفيديو كمكتمل المشاهدة');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function updateVideoCompletionStatus(contentId, completed) {
        const videoElement = document.querySelector(`.lesson-item[data-content-id="${contentId}"] .lesson-video`);

        if (completed && videoElement) {
            // Remove progress badge
            const progressBadge = videoElement.querySelector('.progress-badge');
            if (progressBadge) {
                progressBadge.remove();
            }

            // Add completion badge
            if (!videoElement.querySelector('.completion-badge')) {
                const completionBadge = document.createElement('span');
                completionBadge.className = 'completion-badge';
                completionBadge.textContent = '✓';
                videoElement.appendChild(completionBadge);
            }

            // Update progress bar
            const progressFill = videoElement.closest('.lesson-item').querySelector('.progress-fill');
            if (progressFill) {
                progressFill.style.width = '100%';
            }
        }
    }

    function updateVideoVisualProgress(contentId, currentTime, isCompleted) {
        const videoElement = document.querySelector(`.lesson-item[data-content-id="${contentId}"] .lesson-video`);

        if (!videoElement || isCompleted) return;

        // Calculate progress percentage
        const duration = parseInt(videoElement.getAttribute('data-duration')) || 0;
        const progressPercent = duration > 0 ? Math.min(100, (currentTime / duration) * 100) : 0;

        // Update or create progress badge
        let progressBadge = videoElement.querySelector('.progress-badge');
        if (!progressBadge && progressPercent > 0) {
            progressBadge = document.createElement('span');
            progressBadge.className = 'progress-badge';
            videoElement.appendChild(progressBadge);
        }

        if (progressBadge) {
            progressBadge.textContent = Math.round(progressPercent) + '%';
        }

        // Update progress bar
        const progressFill = videoElement.closest('.lesson-item').querySelector('.progress-fill');
        if (progressFill) {
            progressFill.style.width = progressPercent + '%';
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
                    <span>{{__('messages.total')}}: ${progressData.total_videos}</span>
                `;
            }
        }
    }

    // Helper functions
    function isYouTubeURL(url) {
        return url.includes('youtube.com') || url.includes('youtu.be');
    }

    function extractYouTubeID(url) {
        const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
        const match = url.match(regExp);
        return (match && match[2].length === 11) ? match[2] : null;
    }
</script>
@endsection

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
@endpush
