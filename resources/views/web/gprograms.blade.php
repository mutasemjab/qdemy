@extends('layouts.app')

@section('title', translate_lang('International Program'))

@section('content')
<section class="gprograms-page">
    <!-- International -->
    <a href="#" class="gprogram-card gprogram-card-mixed gprogram-card-main">
        <span>{{ translate_lang('International Program')}}</span>
    </a>

    @if($programms && $programms->count())
    @foreach($programms as $programm)
    <a href="{{route('international-programm',['programm'=>$programm->id,'slug'=>$programm->slug])}}" class="gprogram-card gprogram-card-american">
        <i class='flag {{$programm->icon}}'></i>
        <!-- <img data-src="{{$programm->photo_url}}" alt="American Flag"> -->
        <span>{{$programm->localized_name}}</span>
    </a>
    @endforeach
    @endif
</section>
@endsection
