@extends('layouts.app')
@section('title',$tawjihiLastYear->localized_name)

@section('content')
<section class="f08-page">

    @if($tawjihiLastYearFields && $tawjihiLastYearFields->count())
    @foreach($tawjihiLastYearFields as $index => $tawjihiLastYearField)
    <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200" class="f08-section" style="background-image: url('{{ asset('assets_front/images') }}{{$index % 2 ? '/literature-box.png' : '/science-box.png'}}');">

        <h2 class="f08-title">{{$tawjihiLastYearField->localized_name}}</h2>

            @php $tawjihiLastYearFieldSubjects = CategoryRepository()->getDirectChilds($tawjihiLastYearField); @endphp
            @if($tawjihiLastYearFieldSubjects && $tawjihiLastYearFieldSubjects->count())
            <div class="f08-items">
            @foreach($tawjihiLastYearFieldSubjects as $tawjihiLastYearFieldSubject)
                <a href="{{route('tawjihi-grade-field',['field'=>$tawjihiLastYearFieldSubject->id,'slug'=>$tawjihiLastYearFieldSubject->slug])}}" class="tj2009__item"
                    style="background-image:url('{{ asset('assets_front/images/it-icon-light.png')}}">
                    <span class='text-light'> {{$tawjihiLastYearFieldSubject->localized_name}} </span>
                </a>
            @endforeach
           </div>
           @endif

    </div>
    @endforeach
    @endif
</section>


@endsection
