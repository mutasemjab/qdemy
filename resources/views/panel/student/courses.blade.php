    <div class="ud-panel" id="courses">
        <div class="ud-title">{{__('front.courses')}}</div>
        <div class="ud-courses">
            @foreach($userCourses as $course)
            <a href="{{route('course',['course'=>$course->id,'slug'=>$course->slug])}}" class="ud-course">
                <div class="ud-course-meta">
                <h3>{{ $course->title }} <br>
                <span class="ud-course-meta-sub">
                    @if($course->subject) {{ $course->subject->localized_name }} @endif
                    @if($course->subject?->semester) - {{ $course->subject?->semester->localized_name }} @endif
                </span></h3>
                <div class="ud-course-teacher">
                    <img data-src="{{$course->teacher?->photo_url}}"><span>{{$course->teacher?->name}}</span>
                </div>
                </div>
                <div class="ud-course-date">
                <small>{{ $course->course_user?->created_at }}</small>
                </div>
            </a>
            @endforeach
        </div>
    </div>