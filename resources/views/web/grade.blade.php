@extends('layouts.app')

@section('title', $grade->localized_name)

@section('content')
<section class="grade-page">

    <!-- Header -->
    <div data-aos="fade-up" data-aos-duration="1000" class="grades-header-wrapper">
        <div class="grades-header">
            <h2>{{$grade->localized_name}}</h2>
            <span class="grade-number">{{$grade->sort_order}}</span>
        </div>
    </div>

    <!-- Semesters -->
     @if($semesters && $semesters->count())
     <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200" class="semesters-row">
        @foreach($semesters as $index => $semester)
          <a href="javascript:void(0)" class="semester-box" id='{{$index}}_semester' data-semester="{{$index}}">{{$semester->localized_name}}</a>
        @endforeach
     </div>
    <!-- Subjects -->
    @foreach($semesters as $index => $semester)
        <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300" class="subjects-grid semester-content"  id="{{$index}}_semester_content"
            style="{{ !$loop->first ? 'display:none;' : '' }}">
            @php $subjects  = SubjectRepository()->getSubjectsForSemester($semester) ?? []; @endphp
            @if($subjects && $subjects->count())
                @foreach($subjects as $index => $subject)
                <a href="{{route('subject',['subject'=>$subject->id,'slug'=>$subject->slug])}}" class="subject-card dark">
                    <span>{{$subject->localized_name}}</span>
                    <i class="{{$subject->icon}}"></i>
                    <!-- <img data-src="{{ asset('assets_front/images/icon-math.png') }}" alt="{{$subject->localized_name}}" class="subject-icon"> -->
                </a>
                @endforeach
            @endif
        </div>
    @endforeach
    @endif


</section>
@endsection
<script>
document.addEventListener('DOMContentLoaded',function(){
  function switchSemester(semester){
    var contents=document.querySelectorAll('.semester-content');
    contents.forEach(function(c){c.style.display='none'});
    var tabs=document.querySelectorAll('.semester-box');
    tabs.forEach(function(t){t.classList.remove('light')});
    var target=document.getElementById(semester+'_semester_content');
    if(target){target.style.display='grid'}
    var tab=document.querySelector('.semester-box[data-semester="'+semester+'"]');
    if(tab){tab.classList.add('light')}
  }

  var row=document.querySelector('.semesters-row');
  if(row){
    row.addEventListener('click',function(e){
      var t=e.target.closest('.semester-box');
      if(!t)return;
      var s=t.getAttribute('data-semester');
      if(s!==null){switchSemester(s)}
    });
  }

  var active=document.querySelector('.semester-box.light')||document.querySelector('.semester-box');
  if(active){
    var s=active.getAttribute('data-semester');
    switchSemester(s);
  }

  window.switchSemester=switchSemester;
});
</script>

