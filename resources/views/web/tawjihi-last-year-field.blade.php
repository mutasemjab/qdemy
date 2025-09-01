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
        <h3 class="">{{translate_lang('Ministry Subjects')}}</h3>
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
                    <div class="examx-dropdown subject-plus-dropdown-examx">
                        <button class="examx-pill" type="button" tabindex="0">
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

        <h3 class="tj2009__subtitle">{{translate_lang('School Subjects')}}</h3>
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
                    <div class="examx-dropdown subject-plus-dropdown-examx">
                        <button class="examx-pill" type="button" tabindex="0">
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
@push('styles')
<style>
.tj2009__item {
    display: grid;
    place-items: center !important;
    align-content: center!important;
}
.subject-plus-dropdown-examx {
    /* display: inline-flex; */
    align-items: center;
    /* gap: 8px; */
    background: #fff;
    border-radius: 18px;
    /* padding: 5px 14px 5px 10px; */
    box-shadow: 0 1px 10px rgba(0,85,210,0.07);
    margin: 0;
    /* min-width: 120px; */
    transition: box-shadow 0.17s;
    position: relative;
}

.subject-plus-dropdown-examx .examx-pill {
    background: #fff;
    color: #0055D2;
    border: none;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    font-size: 22px;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 1px 6px rgba(0,85,210,0.15);
    transition: box-shadow 0.18s, background 0.18s;
    outline: none;
    margin: 0 0 0 5px;
    z-index: 2;
}
.subject-plus-dropdown-examx .examx-pill:focus,
.subject-plus-dropdown-examx .examx-pill:hover {
    background: #f5faff;
    box-shadow: 0 2px 12px rgba(0,85,210,0.25);
}

/* لا تغير قائمة الدروب داون نفسها */
.subject-plus-dropdown-examx .examx-menu {
    z-index: 3;
}
</style>
@endpush
