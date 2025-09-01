@extends('layouts.app')

@section('title','توجيهي 2009')

@section('content')
<section class="tj2009">
  <div class="tj2009__decor tj2009__decor--left">
    <img data-src="{{ asset('assets_front/images/tawjihi-left-bg.png') }}" alt="">
  </div>
  <div class="tj2009__decor tj2009__decor--right">
    <img data-src="{{ asset('assets_front/images/tj-right.png') }}" alt="">
  </div>

  <div class="tj2009__inner">
    <header class="tj2009__head">
      <h2>{{$tawjihiFirstYear?->localized_name}}</h2>
      <h3 class="">{{translate_lang('Mandatory Ministry Subjects')}}</h3>
    </header>

    @if($ministrySubjects && $ministrySubjects->count())
    <div class="tj2009__subjects">
        @foreach($ministrySubjects as $index => $ministrySubject)
        <a href="{{route('subject',['subject'=>$ministrySubject->id,'slug'=>$ministrySubject->slug])}}" class="tj2009__item"
            style="background-image:url('{{ asset('images/subject-') }}{{$index % 2 ? 'bg.png' : 'bg2.png'}}')">
            <span> {{$ministrySubject->localized_name}} </span>
        </a>
        @endforeach
    </div>
    @endif

    <h3 class="tj2009__subtitle">{{translate_lang('School Subjects')}}</h3>
    @if($schoolSubjects && $schoolSubjects->count())
    <div class="tj2009__subjects">
        @foreach($schoolSubjects as $index => $schoolSubject)
        <a href="{{route('subject',['subject'=>$schoolSubject->id,'slug'=>$schoolSubject->slug])}}" class="tj2009__item"
            style="background-image:url('{{ asset('images/subject-') }}{{$index % 2 ? 'bg.png' : 'bg2.png'}}')">
            <span> {{$schoolSubject->localized_name}} </span>
        </a>
        @endforeach
    </div>
    @endif

  </div>
</section>
@endsection
