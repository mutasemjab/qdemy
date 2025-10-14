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

.tj2009__subjects .tj2009__item{position:relative!important;z-index:-1!important}
.tj2009__subtitle + .tj2009__subjects .tj2009__item{position:relative!important;z-index:-1!important}

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


.examx-pill{min-width:181px}

@media (max-width:768px){
  .examx-pill{min-width:120px}
  .tj2009__item span{
    font-size:10px;
    max-width:72px;
    transform:translateY(-22px);
  }
  
  .f08-section {
    height: 227px!important;
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
  .examx-pill{margin-bottom: -22px;min-width:100px;width:100%}
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

.tj2009{position:relative;overflow:visible}
.tj2009__decor{position:absolute;z-index:0;pointer-events:none}
.tj2009__inner{position:relative;z-index:1}
.tj2009__subjects{position:relative;z-index:2;overflow:visible}
.subject-plus-dropdown-examx{position:relative;z-index:3}
.examx-menu{position:absolute;top:40px;left:50%;transform:translateX(-50%);display:none;min-width:180px;background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:6px;box-shadow:0 12px 30px rgba(0,0,0,.18);z-index:99999}
.subject-plus-dropdown-examx.is-open .examx-menu{display:block}
@media (max-width:768px){
  .examx-menu{left:8px!important;right:8px!important;width:auto!important;min-width:0!important;max-width:none!important;transform:none!important}
}

.examx-menu{display:none!important}
.examx-portal{list-style: none;position:fixed;z-index:2147483000;min-width:220px;background:#fff;border:1px solid #e5e7eb;border-radius:16px;padding:8px;box-shadow:0 18px 45px rgba(0,0,0,.18)}
.examx-portal.hidden{display:none}
@media (max-width:768px){
  .examx-portal{left:8px!important;right:8px!important;width:auto!important;min-width:0!important;max-width:none!important;transform:none!important}
}

li a {
    text-decoration: none;
    color: #000;
    font-size: 15px;
}
</style>
@endpush

@push('scripts')
<script>
(function(){
  let portal=null, owner=null;
  function closeAll(){
    if(portal){ portal.remove(); portal=null; }
    if(owner){ owner.classList.remove('is-open'); owner=null; }
  }
  function openPortal(btn){
    const wrap=btn.closest('.subject-plus-dropdown-examx');
    const tmpl=wrap.querySelector('.examx-menu');
    closeAll();
    const ul=document.createElement('ul');
    ul.className='examx-portal';
    ul.innerHTML=tmpl.innerHTML;
    document.body.appendChild(ul);
    const r=btn.getBoundingClientRect();
    const px=Math.max(8, Math.min(window.innerWidth-ul.offsetWidth-8, r.left + r.width/2 - ul.offsetWidth/2));
    const py=r.bottom + 8;
    ul.style.left=px+'px';
    ul.style.top=py+'px';
    portal=ul;
    owner=wrap;
    owner.classList.add('is-open');
  }
  document.addEventListener('click',function(e){
    const btn=e.target.closest('.subject-plus-dropdown-examx .examx-pill');
    if(btn){ e.preventDefault(); openPortal(btn); return; }
    if(portal && !portal.contains(e.target)) closeAll();
  });
  window.addEventListener('scroll',function(){ if(portal) closeAll(); },{passive:true});
  window.addEventListener('resize',function(){ if(portal) closeAll(); });
})();
</script>
@endpush

