@extends('layouts.app')

@section('title','E-Exam')

@section('content')
<section class="examx-page">

    <div class="universities-header-wrapper">
        <div class="universities-header">
            <h2>{{ translate_lang('e-exams')}}</h2>
        </div>
    </div>
    <div class="examx-filters">
    @include('web.alert-message')
    <form action='{{route("e-exam")}}' methog='get'>
        @csrf
        <div class="examx-row">

            <div class="examx-dropdown">
                <select class="examx-pill" name="grade" id="programm_id">
                    <i class="fa-solid fa-caret-down"></i>
                    <option>اختر البرنامج</option>
                    @foreach($programms as $programm)
                        <option id='programm_{{$programm->id}}' data-grade-id="programm{{$programm->id}}" value="{{$programm->id}}" @if(old("programm") == $programm->id) selected @endif >{{$programm->localized_name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="examx-dropdown">
                <select class="examx-pill" name="grade" id="grad_id">
                    <i class="fa-solid fa-caret-down"></i>
                    <option>اختر الصف</option>
                    @foreach($programmsGrades as $grade)
                        <option id='grade_{{$grade->id}}' data-grade-id="grade_{{$grade->id}}" value="{{$grade->id}}" @if(old("grade") == $grade->id) selected @endif >{{$grade->localized_name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="examx-dropdown">
                <select class="examx-pill" name="subject" id="subject_id">
                    <i class="fa-solid fa-caret-down"></i>
                    <option>اختر المادة</option>
                    @foreach($subjects as $subject)
                        <option id='subject_{{$subject->id}}' data-grade-id="subject{{$subject->id}}" value="{{$subject->id}}" @if(old("subject") == $subject->id) selected @endif >
                            {{$subject->localized_name}}
                            @if($subject->semester) > {{$subject->semester?->localized_name}} @endif
                            @if($subject->grade) > {{$subject->grade?->localized_name}} @endif
                        </option>
                    @endforeach
                </select>
            </div>

        </div>

        <div class="examx-search">
            <input type="text" placeholder="البحث" name='search' value="{{old('search')}}">
            <i class="fa-solid fa-magnifying-glass"></i>
        </div>
    </form>

  <div class="examx-grid">
    @foreach($exams as $exam)
    <div class="examx-card">
      <div class="examx-content">
        <div class="examx-line"><b>{{ translate_lang('subject')}}</b> {{$exam->course?->category->localized_name}}</div>
        <a href="#" class="examx-link">{{$exam->course?->title}}</a>
        <div class="examx-meta">
          <div><span>{{ translate_lang('exam_duration')}}</span><strong>{{$exam->duration_minutes}} {{ translate_lang('minute')}}</strong></div>
          <div><span>{{ translate_lang('question_count')}}:</span><strong>{{$exam->questions?->count()}} {{ translate_lang('question')}}</strong></div>
        </div>
        @if($exam->can_add_attempt())
        <a href="{{route('exam',['exam'=>$exam->id,'slug'=>$exam->slug])}}" class="examx-btn">
            {{ translate_lang('start_exam')}}
        </a>
        @elseif($exam->current_user_attempt())
        <a href="{{route('exam',['exam'=>$exam->id,'slug'=>$exam->slug])}}" class="examx-btn">
            {{ translate_lang('continue')}}
        </a>
        @elseif($exam->result_attempt())
        <a href="{{route('exam.results',$exam->id)}}" class="examx-btn">
            {{ translate_lang('result')}}
        </a>
        @endif
      </div>
    </div>
    @endforeach
  </div>
    <!-- <div class="pagination-wrapper"> -->
       {{ $exams?->links('pagination::custom-bootstrap-5') ?? '' }}
   <!-- </div> -->

</section>
@endsection
