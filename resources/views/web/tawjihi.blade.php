@extends('layouts.app')

@section('title',__('getTawjihiProgrammGrades'))
@section('content')
<section class="tawjihi-page">
    <a data-aos="fade-up" data-aos-duration="1000" href="{{route('tawjihi-first-year',['slug'=>$tawjihiFirstYear?->slug])}}" class="tawjihi-card" style="background-image: url('{{ app()->getLocale() == "ar" 
                            ? asset("assets_front/images/tawjihi-2009.png") 
                            : asset("assets_front/images/en/tawjihi-2009.png") }}');">
    </a>
    <a data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200" href="{{route('tawjihi-grade-year-fields',['slug'=>$tawjihiLastYear?->slug])}}" class="tawjihi-card" style="background-image: url('{{ app()->getLocale() == "ar" 
                            ? asset("assets_front/images/tawjihi-2008.png") 
                            : asset("assets_front/images/en/tawjihi-2008.png") }}');">
    </a>
</section>
@endsection
