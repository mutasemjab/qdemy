@extends('layouts.app')

@section('title',__('getTawjihiProgrammGrades'))
@section('content')
<section class="tawjihi-page">
    <a href="{{route('tawjihi-first-year',['slug'=>$tawjihiFirstYear?->slug])}}" class="tawjihi-card" style="background-image: url('{{ asset('images/tawjihi-2009.png') }}');">
        <div class="tawjihi-text">
            <h3>{{__('messages.tawjihi')}}</h3>
            <p>{{$tawjihiFirstYear?->localized_name}}</p>
        </div>
    </a>
    <a href="{{route('tawjihi-grade-year-fields',['slug'=>$tawjihiLastYear?->slug])}}" class="tawjihi-card" style="background-image: url('{{ asset('images/tawjihi-2008.png') }}');">
        <div class="tawjihi-text">
            <h3>{{__('messages.tawjihi')}}</h3>
            <p>{{$tawjihiLastYear?->localized_name}}</p>
        </div>
    </a>
    <a href="#" class="tawjihi-card" style="display:none;background-image: url('{{ asset('images/tawjihi-2008.png') }}');">
        <div class="tawjihi-text">
            <h3>{{$tawjihiVocational?->localized_name}}</h3>
        </div>
    </a>
</section>
@endsection
