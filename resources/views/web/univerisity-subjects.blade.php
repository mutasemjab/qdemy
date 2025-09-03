@extends('layouts.app')

@section('title', $programm->localized_name)

@section('content')
<section class="grade-page">
    <!-- International -->
    <a href="#" class="gprogram-card gprogram-card-mixed gprogram-card-main ">
        <span>{{$programm->localized_name}}</span>
    </a>

    <!-- Subjects -->
        <div class="subjects-grid semester-content"  id="_semester_content">
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
</section>
@endsection
