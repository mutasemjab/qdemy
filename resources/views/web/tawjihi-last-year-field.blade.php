@extends('layouts.app')

@section('title',$field->localized_name)

@section('content')
<section class="tj2009">
  <div data-aos="fade" data-aos-duration="1000" class="tj2009__decor tj2009__decor--left">
    <img data-src="{{ asset('assets_front/images/tawjihi-left-bg.png') }}" alt="">
  </div>
  <div data-aos="fade" data-aos-duration="1000" class="tj2009__decor tj2009__decor--right">
    <img data-src="{{ asset('assets_front/images/tj-right.png') }}" alt="">
  </div>

    <div class="tj2009__inner">
        <header data-aos="fade-up" data-aos-duration="1000" class="tj2009__head">
        <h2>{{$field?->localized_name}}</h2>
        <h3 class="">{{translate_lang('Ministry Subjects')}}</h3>
        </header>

        @if($ministrySubjects && $ministrySubjects->count())
        <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200" class="tj2009__subjects">
            @foreach($ministrySubjects as $index => $ministrySubject)
            <div href="javascript:void(0)" class="tj2009__item"
                style="background-image:url('{{ asset('images/subject-') }}{{$index % 2 ? 'bg.png' : 'bg2.png'}}')">
               @if($ministrySubject->has_optional_subject)
                    <a class="text-decoration-none" href="javascript:void(0)">
                        <span> {{$ministrySubject->localized_name}} </span>
                    </a>
                    @php $subjects = SubjectRepository()->getOptionalSubjectOptions($field,$ministrySubject); @endphp

                    <div class="examx-dropdown subject-plus-dropdown-examx">
                        <button class="examx-pill" type="button" tabindex="0">
                            <span>+</span>
                        </button>
                        <ul class="examx-menu">
                        @if($subjects && $subjects->count())
                        @foreach($subjects as $optiona_subject)
                            <li>
                                <a class="" href="{{route('subject',['subject'=>$optiona_subject->id,'slug'=>$optiona_subject->slug])}}">
                                    {{$optiona_subject->localized_name}}
                                </a>
                            </li>
                        @endforeach
                        @endif
                        </ul>
                    </div>

                @else
                    <a class="text-decoration-none" href="{{route('subject',['subject'=>$ministrySubject->id,'slug'=>$ministrySubject->slug])}}">
                        <span> {{$ministrySubject->localized_name}} </span>
                    </a>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        <h3 data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300" class="tj2009__subtitle">{{translate_lang('School Subjects')}}</h3>
        @if($schoolSubjects && $schoolSubjects->count())
        <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300" class="tj2009__subjects">
            @foreach($schoolSubjects as $index => $schoolSubject)
            <div href="javascript:void(0)" class="tj2009__item"
                style="background-image:url('{{ asset('images/subject-') }}{{$index % 2 ? 'bg.png' : 'bg2.png'}}')">
               @if($schoolSubject->has_optional_subject)
                    <a class="text-decoration-none" href="javascript:void(0)">
                        <span> {{$schoolSubject->localized_name}} </span>
                    </a>
                    @php $subjects = SubjectRepository()->getOptionalSubjectOptions($field,$schoolSubject); @endphp

                    <div class="examx-dropdown subject-plus-dropdown-examx">
                        <button class="examx-pill" type="button" tabindex="0">
                            <span>+</span>
                        </button>
                        <ul class="examx-menu">
                        @if($subjects && $subjects->count())
                        @foreach($subjects as $optiona_subject)
                            <li>
                                <a class="" href="{{route('subject',['subject'=>$optiona_subject->id,'slug'=>$optiona_subject->slug])}}">
                                    {{$optiona_subject->localized_name}}
                                </a>
                            </li>
                        @endforeach
                        @endif
                        </ul>
                    </div>

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
.tj2009__item{
  position:relative;
  display:flex;
  flex-direction:column;
  align-items:center;
  justify-content:center;
  text-align:center;
}

.subject-plus-dropdown-examx{
  position:relative;
  align-items:center;
  border-radius:18px;
  margin:0;
  transition:box-shadow .17s;
  margin-top:8px;
}

.subject-plus-dropdown-examx .examx-pill{
  color:#0055D2;
  border:none;
  border-radius:50%;
  width:32px;
  height:32px;
  font-size:22px;
  font-weight:bold;
  display:flex;
  align-items:center;
  justify-content:center;
  cursor:pointer;
  outline:none;
  background-color:transparent;
}

.subject-plus-dropdown-examx .examx-pill:focus,
.subject-plus-dropdown-examx .examx-pill:hover{
  border:none!important;
}

.subject-plus-dropdown-examx .examx-menu{z-index:4}
.subject-plus-dropdown-examx .examx-menu{z-index:3}

.examx-pill{min-width:181px}

@media (max-width:768px){
  .examx-pill{min-width:120px}
  .tj2009__item span{
    font-size:10px;
    max-width:72px;
    transform:translateY(-9px);
  }
}

@media (max-width:710px){
  .tj2009__subjects{
    grid-template-columns:repeat(3,1fr)!important;
    gap:18px;
    padding:20px;
  }
}

@media (max-width:640px){
  .examx-pill{min-width:100px;width:100%}
}

@media (max-width:560px){
  .tj2009__item{
    width:108px;
    height:127px;
    background-size:106px 124px;
  }
}

.tj2009{
  position:relative;
  min-height:max-content;
  overflow:hidden;
  padding:20px 0 300px 0;
}

.examx-menu li{padding:0;margin:0}

.examx-menu a{
  display:block;
  width:100%;
  box-sizing:border-box;
  padding:10px 12px;
  text-decoration:none;
  border-radius:10px;
  color:#111827;
  transition:background .15s ease;
  font-size:13px;
}

@media (max-width:768px){
  .examx-dropdown .examx-menu{
    left:8px!important;
    right:8px!important;
    width:auto!important;
    min-width:0!important;
    max-width:none!important;
    transform:none!important;
  }
}

</style>
@endpush
