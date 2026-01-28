@extends('layouts.app')

@section('title', __('front.training_courses'))

@section('content')
<section class="grade-page">
    <!-- Header -->
    <div data-aos="fade-up" data-aos-duration="1000" class="gprogram-card gprogram-card-mixed0 gprogram-card-main0">
        <div class="universities-header">
            <h2>{{__('front.training_courses')}}</h2> <br>
        </div>
    </div>

    <!-- Subjects -->
    <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200" class="subjects-grid semester-content" id="_semester_content">
        @if($subjects && $subjects->count())
            @foreach($subjects as $index => $subject)
            <a href="{{route('subject',['subject'=>$subject->id,'slug'=>$subject->slug])}}" class="subject-card dark">
                <span>
                    {{$subject->localized_name}}
                    @if($subject->grade)
                        <br><small>{{ app()->getLocale() === 'ar' ? $subject->grade->name_ar : $subject->grade->name_en }}</small>
                    @endif
                    @if($subject->semester)
                        <br><small>{{ app()->getLocale() === 'ar' ? $subject->semester->name_ar : $subject->semester->name_en }}</small>
                    @endif
                </span>
                <i class="{{$subject->icon}}"></i>
            </a>
            @endforeach
        @else
            <div class="alert alert-info text-center" style="width: 100%; grid-column: 1 / -1;">
                <i class="fas fa-info-circle"></i>
                @if(app()->getLocale() === 'ar')
                    <p>لا توجد مواد دراسية في الدورات التدريبية حالياً</p>
                @else
                    <p>No training courses available at the moment</p>
                @endif
            </div>
        @endif
    </div>
</section>
@endsection
