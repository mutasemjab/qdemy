@extends('layouts.app')

@section('title', $programm->localized_name)

@section('content')
<section class="grade-page">
    <!-- International -->
    
    <div data-aos="fade-up" data-aos-duration="1000" class="gprogram-card gprogram-card-mixed0 gprogram-card-main0">
        <div class="universities-header">
            <h2>{{__('front.universities_colleges')}}</h2> <br>
        </div>
    </div>
    </a>

    <!-- Subjects -->
        <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200" class="subjects-grid semester-content"  id="_semester_content">
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
