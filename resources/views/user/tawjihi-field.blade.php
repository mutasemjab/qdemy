@extends('layouts.app')

@section('title',$field->localized_name)

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
        <h2>{{$field?->localized_name}}</h2>
        <h3 class="">{{__('messages.Ministry Subjects')}}</h3>
        </header>

        @if($ministrySubjects && $ministrySubjects->count())
        <div class="tj2009__subjects">
            @foreach($ministrySubjects as $index => $ministrySubject)
            <div href="javascript:void(0)" class="tj2009__item"
                style="background-image:url('{{ asset('images/subject-') }}{{$index % 2 ? 'bg.png' : 'bg2.png'}}')">
               @if($ministrySubject->is_optional)
                    <a class="text-decoration-none" href="javascript:void(0)">
                        <span> {{$ministrySubject->localized_name}} </span>
                    </a>
                    @php $subjects = CategoryRepository()->getOtionalSubjectsForField($ministrySubject); @endphp

                    @if($subjects && $subjects->count())
                    <div class="examx-dropdown">
                        <button class="examx-pill">
                        <span>+</span>
                        </button>
                        <ul class="examx-menu">
                        @foreach($subjects as $optiona_subject)
                            <li>
                                <a class="text-decoration-none" href="{{route('subject',['subject'=>$optiona_subject->id,'slug'=>$optiona_subject->slug])}}">
                                    {{$optiona_subject->localized_name}}
                                </a>
                            </li>
                        @endforeach
                        </ul>
                    </div>
                    @endif

                @else
                    <a class="text-decoration-none" href="{{route('subject',['subject'=>$ministrySubject->id,'slug'=>$ministrySubject->slug])}}">
                        <span> {{$ministrySubject->localized_name}} </span>
                    </a>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        <h3 class="tj2009__subtitle">{{__('messages.School Subjects')}}</h3>
        @if($schoolSubjects && $schoolSubjects->count())
        <div class="tj2009__subjects">
            @foreach($schoolSubjects as $index => $schoolSubject)
            <div href="javascript:void(0)" class="tj2009__item"
                style="background-image:url('{{ asset('images/subject-') }}{{$index % 2 ? 'bg.png' : 'bg2.png'}}')">
               @if($schoolSubject->is_optional)
                    <a class="text-decoration-none" href="javascript:void(0)">
                        <span> {{$schoolSubject->localized_name}} </span>
                    </a>
                    @php $subjects = CategoryRepository()->getOtionalSubjectsForField($schoolSubject); @endphp

                    @if($subjects && $subjects->count())
                    <div class="examx-dropdown">
                        <button class="examx-pill">
                        <span>+</span>
                        </button>
                        <ul class="examx-menu">
                        @foreach($subjects as $optiona_subject)
                            <li>
                                <a class="text-decoration-none" href="{{route('subject',['subject'=>$optiona_subject->id,'slug'=>$optiona_subject->slug])}}">
                                    {{$optiona_subject->localized_name}}
                                </a>
                            </li>
                        @endforeach
                        </ul>
                    </div>
                    @endif

                @else
                    <a class="text-decoration-none" href="{{route('subject',['subject'=>$schoolSubject->id,'slug'=>$schoolSubject->slug])}}">
                        <span> {{$schoolSubject->localized_name}} </span>
                    </a>
                @endif
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>
@endsection
