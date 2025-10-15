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
document.addEventListener('DOMContentLoaded', function() {
    // تعريف الدالة
    window.switchSemester = function(semester) {
        // إخفاء جميع المحتويات
        document.querySelectorAll('.semester-content').forEach(content => {
            content.style.display = 'none';
        });

        // إزالة الفئة النشطة من جميع التبويبات
        document.querySelectorAll('.semester-box').forEach(tab => {
            tab.classList.remove('light');
        });

        // إظهار المحتوى المحدد
        document.getElementById(semester + '_semester_content').style.display = 'grid';

        // إضافة الفئة النشطة للتبويب
        document.querySelector(`.semester-box[data-semester="${semester}"]`).classList.add('light');
    };

    // إضافة Event Listeners للتبويبات
    document.querySelectorAll('.semester-box').forEach(tab => {
        tab.addEventListener('click', function() {
            const semester = this.getAttribute('data-semester');
            switchSemester(semester);
        });
    });
});
</script>
