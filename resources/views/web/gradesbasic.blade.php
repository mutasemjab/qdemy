@extends('layouts.app')

@section('title', translate_lang('basic_grades'))

@section('content')
<section class="grades-basic-page">
    <div data-aos="fade-up" data-aos-duration="1000" class="grades-header-wrapper-basic">
        <img
            src="{{ app()->getLocale() == 'ar'
                ? asset('assets_front/images/rec-bg-basic.png')
                : asset('assets_front/images/en/rec-bg-basic.png') }}"
            alt=""
            class="grades-header-basic-img"
            loading="lazy"
        >
    </div>

    <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200" class="grades-grid-wrapper">
    <div class="grades-grid">
        @foreach($grades as $grade)
        <a href="{{route('grade',['grade'=>$grade->id,'slug'=>$grade->slug])}}" class="grade-card" style="background-image: url('{{ asset('images/boxbg.png') }}');">
            <span>{{$grade->localized_name}}</span>
        </a>
        @endforeach
    </div>
    </div>
</section>
@endsection
