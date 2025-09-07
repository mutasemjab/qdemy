    <div class="ud-panel" id="schedule">
      <div class="ud-title">{{ translate_lang('courses progress') }}</div>
      <div class="ud-bars">
        @foreach($userCourses as $index => $course)
          <div class="ud-bar">
            <div class="ud-bar-head"><b>{{ $course->title }}</b>
            @if($course->subject) <small> {{ $course->subject->localized_name }} </small> @endif
            @if($course->subject?->semester) <small> - {{ $course->subject?->semester->localized_name }} </small> @endif
           </div>
           @if($course->calculateCourseProgress())
           <div class="ud-bar-track"><span style="width:{{ $course->calculateCourseProgress() }}%"></span></div>
           <div class="ud-bar-foot">100%<b>{{ number_format($course->calculateCourseProgress(), 1, '.', '') }}% 
               <!-- {{ $course->calculateCourseProgress() }}% -->
           </b></div>
           @else
           <div class="ud-bar-track"><span style="width:0%"></span></div>
           <div class="ud-bar-foot">100%<b>0% 
           </b></div>
           @endif
          </div>
        @endforeach
      </div>
    </div>