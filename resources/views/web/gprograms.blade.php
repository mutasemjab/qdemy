@extends('layouts.app')

@section('title', __('messages.International Program'))

@section('content')
<section class="gprograms-page">
    <!-- International -->
    <a data-aos="fade-up" data-aos-duration="1000" href="#" class="gprogram-card gprogram-card-mixed gprogram-card-main">
        <span data-aos="fade" data-aos-duration="1000" data-aos-delay="200" >{{ __('messages.International Program')}}</span>
    </a>

    @if($programms && $programms->count())
    @foreach($programms as $programm)
    <a data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300" href="{{route('international-programm',['programm'=>$programm->id,'slug'=>$programm->slug])}}" class="gprogram-card gprogram-card-american">
        <i data-aos="fade" data-aos-duration="1000" data-aos-delay="400"class='flag {{$programm->icon}}'></i>
        <!-- <img data-src="{{$programm->photo_url}}" alt="American Flag"> -->
        <span data-aos="fade" data-aos-duration="1000" data-aos-delay="400">{{$programm->localized_name}}</span>
    </a>
    @endforeach
    @endif
</section>
@endsection
