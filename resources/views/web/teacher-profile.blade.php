@extends('layouts.app')
@section('title', $teacher->name . ' - ' . __('front.Teacher Profile'))

@section('content')
<section class="teacher-profile-wrapper">

    <div class="teacher-header-wrapper">
        <div class="teacher-header-image">
            <img data-src="{{ $teacher->photo ? asset('assets/admin/uploads/' . $teacher->photo) : asset('assets_front/images/teacher1.png') }}" alt="{{ $teacher->name }}">        
        </div>
        <div class="teacher-follow-btn">
            @auth
                <button id="followBtn" class="follow-button {{ $isFollowing ? 'following' : '' }}" 
                        data-teacher-id="{{ $teacher->id }}">
                    <span class="follow-text">{{ $isFollowing ? __('front.Unfollow') : __('front.Follow') }}</span>
                    <span class="loading-text" style="display: none;">{{ __('front.Loading') }}...</span>
                </button>
            @else
                <a href="{{ route('login') }}" class="follow-button">
                    {{ __('front.Follow') }}
                </a>
            @endauth
        </div>
    </div>

    <div class="teacher-details">
        <div class="teacher-stats">
            <div class="stat-item">
                <span>{{ __('front.Courses') }}</span>
                <strong>{{ $teacher->courses->count() }}</strong>
            </div>
            <div class="stat-item">
                <span>{{ __('front.Followers') }}</span>
                <strong id="followersCount">{{ $teacher->followersCount() }}</strong>
            </div>
        </div>

        <div class="teacher-bio">
            <h3>{{ __('front.About Teacher') }}</h3>
            <p>
                @if(app()->getLocale() == 'ar')
                    {{ $teacher->description_ar ?? __('front.No description available') }}
                @else
                    {{ $teacher->description_en ?? __('front.No description available') }}
                @endif
            </p>
        </div>

        <div class="teacher-tabs">
            <button class="tab-btn active">{{ __('front.Courses') }}</button>
        </div>

        <div class="teacher-content">
            <div class="tab-content active">
                @if($teacher->courses->count() > 0)
                    <p>
                        @foreach($teacher->courses as $course)
                            <div class="university-card">
                                <div class="card-image">
                                    <span class="rank">#{{ $loop->index + 1}}</span>
                                    <img data-src="{{ $course->photo_url }}" alt="Course Image">
                                    @if($course->subject?->porgramm)<span class="course-name">{{$course->subject->porgramm->localized_name}}</span>@endif
                                </div>
                                <div class="card-info">
                                    <p class="course-date">{{ $course->created_at->locale(app()->getLocale())->translatedFormat('d F Y') }}</p>
                                    <a class='text-decoration-none text-dark' href="{{route('course',['course'=>$course->id,'slug'=>$course->slug])}}">
                                        <span class="course-title">{{$course->title}}</span>
                                    </a>
                                    <div class="instructor">
                                        <img data-src="{{$teacher?->photo_url}}" alt="Instructor">
                                        <a class='text-decoration-none text-dark' href="{{route('teacher',$teacher?->id ?? '-')}}">
                                            <span>{{$teacher?->name}}</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </p>
                @else
                    <p>{{ __('front.No courses available') }}</p>
                @endif
            </div>
        </div>
    </div>

</section>



<script>
document.addEventListener('DOMContentLoaded', function() {
    const followBtn = document.getElementById('followBtn');
    
    if (followBtn) {
        followBtn.addEventListener('click', function() {
            const teacherId = this.dataset.teacherId;
            const followText = this.querySelector('.follow-text');
            const loadingText = this.querySelector('.loading-text');
            
            // Show loading state
            followText.style.display = 'none';
            loadingText.style.display = 'inline';
            this.disabled = true;
            
            fetch(`{{ route('teacher.toggle-follow', ':teacherId') }}`.replace(':teacherId', teacherId), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update button text and class
                    if (data.is_following) {
                        followText.textContent = '{{ __("front.Unfollow") }}';
                        this.classList.add('following');
                    } else {
                        followText.textContent = '{{ __("front.Follow") }}';
                        this.classList.remove('following');
                    }
                    
                    // Update followers count
                    const followersCount = document.getElementById('followersCount');
                    if (followersCount) {
                        followersCount.textContent = data.followers_count;
                    }
                    
                    // Show success message (optional)
                    // You can add a toast notification here if you want
                } else {
                    alert(data.message || '{{ __("front.An error occurred") }}');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('{{ __("front.An error occurred") }}');
            })
            .finally(() => {
                // Hide loading state
                followText.style.display = 'inline';
                loadingText.style.display = 'none';
                this.disabled = false;
            });
        });
    }
});
</script>
@endsection