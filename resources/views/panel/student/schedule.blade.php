   <div class="ud-panel" id="schedule">
  <div class="ud-title">{{ translate_lang('courses progress') }}</div>
  <div class="ud-bars">
    @foreach($userCourses as $index => $course)
      @php
        $progress = $course->calculateCourseProgress();
        $totalProgress = $progress['total_progress'] ?? 0;
      @endphp
      
      <div class="ud-bar">
        <div class="ud-bar-head">
          <b>{{ $course->title }}</b>
          @if($course->subject) <small> {{ $course->subject->localized_name }} </small> @endif
          @if($course->subject?->semester) <small> - {{ $course->subject?->semester->localized_name }} </small> @endif
        </div>
        
        <div class="ud-bar-track"><span style="width:{{ $totalProgress }}%"></span></div>
        <div class="ud-bar-foot">100%<b>{{ number_format($totalProgress, 1, '.', '') }}%</b></div>
      </div>
    @endforeach
  </div>
</div>