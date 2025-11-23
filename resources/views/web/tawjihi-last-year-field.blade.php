@extends('layouts.app')

@section('title',$field->localized_name)

@section('content')
<section class="tawjihi2009">

        <div class="tawjihi2009__inner">
            <div class="tawjihi2009__layout">
                
              @php
        $fieldImages = [
            6 => [
                'image'     => 'alhkl-altby.png',
                'title_key' => 'front.Health field'
            ],
            7 => [
                'image'     => 'alhkl-alhndsy.png',
                'title_key' => 'front.Engineering field'
            ],
            8 => [
                'image'     => 'hkl-tknologya-almaalomat.png',
                'title_key' => 'front.IT field'
            ],
            9 => [
                'image'     => 'hkl-alaaamal.png',
                'title_key' => 'front.Business field'
            ],
            10 => [
                'image'     => 'hkl-allghat-oalaalom-alagtmaaay.png',
                'title_key' => 'front.Languages ​​and Translation Field'
            ],
            11 => [
                'image'     => 'hkl-alkanon-oalshryaa.png',
                'title_key' => 'front.The field of law and Sharia'
            ],
        ];

        $fieldData  = $fieldImages[$field->id] ?? null;
        $imageFile  = $fieldData['image'] ?? 'default-field.png';
        $titleText  = isset($fieldData['title_key']) ? __($fieldData['title_key']) : __('front.Field');
    @endphp


    <div data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200" class="tawjihi2009__right">
        <div class="tawjihi2009__year">
            {{ $titleText }}
        </div>

        <img class="tawjihi2009__image"
            src="{{ asset('assets_front/images/' . $imageFile) }}"
            alt="{{ $titleText }}">
    </div>


            
            {{-- العمود اليسار (العناوين + المواد) --}}
            <div class="tawjihi2009__left">
                <header data-aos="fade-up" data-aos-duration="1000" class="tawjihi2009__head">
                    <h2>{{$field?->localized_name}}</h2>
                    <h3 class="tawjihi2009__section-title">
                        <span>{{__('front.Ministry Subjects')}}</span>
                    </h3>
                </header>

                {{-- المواد الوزارية --}}
                @if($ministrySubjects && $ministrySubjects->count())
                    <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200"
                         class="tawjihi2009__subjects-grid tawjihi2009__subjects-grid--ministry">
                        @foreach($ministrySubjects as $index => $ministrySubject)
                            <div class="tawjihi2009__subject-wrapper">
                                <div class="tawjihi2009__subject"
                                     style="background-image:url('{{ asset('assets_front/images/subject-bg2.png') }}')">
                                    @if($ministrySubject->has_optional_subject)
                                        <a class="tawjihi2009__subject-link text-decoration-none" href="javascript:void(0)">
                                            <span>{{$ministrySubject->localized_name}}</span>
                                        </a>
                                    @else
                                        <a class="tawjihi2009__subject-link text-decoration-none"
                                           href="{{route('subject',['subject'=>$ministrySubject->id,'slug'=>$ministrySubject->slug])}}">
                                            <span>{{$ministrySubject->localized_name}}</span>
                                        </a>
                                    @endif
                                </div>

                                {{-- زر المواد الاختيارية + المنيو --}}
                                @if($ministrySubject->has_optional_subject)
                                    @php $subjects = SubjectRepository()->getOptionalSubjectOptions($field,$ministrySubject); @endphp
                                    @if($subjects && $subjects->count())
                                        <div class="subject-plus-dropdown-examx">
                                            <button class="examx-pill" type="button" tabindex="0">
                                                <span>+</span>
                                            </button>
                                            <ul class="examx-menu">
                                                @foreach($subjects as $optiona_subject)
                                                    <li>
                                                        <a href="{{route('subject',['subject'=>$optiona_subject->id,'slug'=>$optiona_subject->slug])}}">
                                                            {{$optiona_subject->localized_name}}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- المواد المدرسية --}}
                <h3 data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300"
                    class="tawjihi2009__subtitle">
                    <span>{{__('front.School Subjects')}}</span>
                </h3>

                @if($schoolSubjects && $schoolSubjects->count())
                    <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300"
                         class="tawjihi2009__subjects-grid tawjihi2009__subjects-grid--school">
                        @foreach($schoolSubjects as $index => $schoolSubject)
                            <div class="tawjihi2009__subject-wrapper">
                                <div class="tawjihi2009__subject"
                                     style="background-image:url('{{ asset('assets_front/images/subject-bg2.png') }}')">
                                    @if($schoolSubject->has_optional_subject)
                                        <a class="tawjihi2009__subject-link text-decoration-none" href="javascript:void(0)">
                                            <span>{{$schoolSubject->localized_name}}</span>
                                        </a>
                                    @else
                                        <a class="tawjihi2009__subject-link text-decoration-none"
                                           href="{{route('subject',['subject'=>$schoolSubject->id,'slug'=>$schoolSubject->slug])}}">
                                            <span>{{$schoolSubject->localized_name}}</span>
                                        </a>
                                    @endif
                                </div>

                                @if($schoolSubject->has_optional_subject)
                                    @php $subjects = SubjectRepository()->getOptionalSubjectOptions($field,$schoolSubject); @endphp
                                    @if($subjects && $subjects->count())
                                        <div class="subject-plus-dropdown-examx">
                                            <button class="examx-pill" type="button" tabindex="0">
                                                <span>+</span>
                                            </button>
                                            <ul class="examx-menu">
                                                @foreach($subjects as $optiona_subject)
                                                    <li>
                                                        <a href="{{route('subject',['subject'=>$optiona_subject->id,'slug'=>$optiona_subject->slug])}}">
                                                            {{$optiona_subject->localized_name}}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>


        </div>
    </div>
</section>
@endsection


@push('styles')
<style>
/* ====== الهيكل العام للقسم ====== */
.tawjihi2009{
  position:relative;
  padding:60px 0;
  background:#ffffff;
  overflow:hidden;
}

.tawjihi2009__decor{
  position:absolute;
  inset-inline-start:0;
  top:0;
  z-index:0;
  pointer-events:none;
}
.tawjihi2009__decor img{
  max-width:100%;
  height:auto;
  display:block;
}

.tawjihi2009__inner{
  position:relative;
  z-index:1;
  max-width:1200px;
  padding: 0 0px 0 0;
  margin: auto;
}

.tawjihi2009__layout{
  display:flex;
  align-items:flex-start;
  justify-content:space-between;
  gap:40px;
}

.tawjihi2009__left{
  flex:1.3;
  min-width:0;
  text-align: center;
}

.tawjihi2009__right{
  flex:1;
  display:flex;
  flex-direction:column;
  align-items:center;
  justify-content:center;
  margin: 57px -95px 0 90px;
}

/* ====== جزء 2009 والكتب ====== */
.tawjihi2009__year {
    position: relative;
    display: inline-block; 
    font-size: 64px;
    font-weight: 800;
    color: #0055D2;
    padding: 4px 26px 10px;
    line-height: 1;
    margin-bottom: 40px;
}

.tawjihi2009__year::after {
    content: "";
    position: absolute;
    left: 0;
    right: 0;
    bottom: 13px;
    height: 20px;
    background: #ffd602;
    z-index: -1;
}


.tawjihi2009__image{
  max-width:380px;
  width:100%;
  height:auto;
}

/* ====== العناوين ====== */
.tawjihi2009__head{
  margin-bottom:32px;
}
.tawjihi2009__head h2{
  font-size:22px;
  font-weight:600;
  color:#111827;
  margin-bottom:8px;
  display: none;
}

.tawjihi2009__section-title {
    position: relative;
    display: inline-block;
    font-size: 30px;
    margin: 0;
    color: #0055D2;
    font-weight: 800;
    padding: 0 10px 6px;
}

.tawjihi2009__section-title::after {
    content: "";
    position: absolute;
    left: 0;
    right: 0;
    bottom: 15px;
    height: 14px;
    background: #ffd602;
    z-index: -1;
}

.tawjihi2009__section-title span{
  display:inline-block;
  padding:4px 18px 8px;
  border-radius:6px;
}

.tawjihi2009__subtitle{
  font-size:30px;
  font-weight:800;
  color:#0055D2;
  margin:40px 0 16px;
}

.tawjihi2009__subtitle span {
    position: relative;
    display: inline-block;
    padding: 4px 18px 8px;
    border-radius: 6px;
    margin-bottom: 15px;
}

.tawjihi2009__subtitle span::after {
    content: "";
    position: absolute;
    left: 0;
    right: 0;
    bottom: 4px;
    height: 14px;
    background: #ffd602;
    z-index: -1;
}

/* ====== الجريد الخاصة بالمواد ====== */
.tawjihi2009__subjects-grid{
  display:grid;
  grid-template-columns:repeat(4,minmax(0,1fr));
  gap: 28px 173px;
}

.tawjihi2009__subject-wrapper{
  display:flex;
  flex-direction:column;
  align-items:center;
  justify-content:flex-start;
  gap:8px;
}

/* شكل الأيقونة الزرقاء (Q) */
.tawjihi2009__subject{
  width:172px;
  height:195px;
  background-size:100% 100%;
  background-repeat:no-repeat;
  background-position:center;
  display:flex;
  align-items:center;
  justify-content:center;
}


.tawjihi2009__subject-link{
  display:flex;
  width:100%;
  height:100%;
  align-items:center;
  justify-content:center;
  text-align:center;
  text-decoration:none;
  color:#0055D2;
  font-weight:700;
  font-size:16px;
  line-height:1.4;
  padding:0 20px;
}
.tawjihi2009__subject-link span{
  display:block;
}

/* ====== Dropdown المواد الاختيارية ====== */
.subject-plus-dropdown-examx{
  position:relative;
}

.subject-plus-dropdown-examx .examx-pill{
  display:inline-flex;
  align-items:center;
  justify-content:center;
  gap:6px;
  min-width:120px;
  height:36px;
  padding:0 16px;
  border-radius:999px;
  border:1px solid #e5e7eb;
  background:#ffffff;
  box-shadow:0 10px 25px rgba(15,23,42,0.12);
  color:#0055D2;
  font-size:13px;
  font-weight:600;
  cursor:pointer;
  outline:none;
}

.subject-plus-dropdown-examx .examx-pill span{
  font-size:20px;
  line-height:1;
}

/* القائمة الأصلية (مستخدَمة كـ template فقط) */
.subject-plus-dropdown-examx .examx-menu{
  display:none;
}

/* المنيو الطايرة اللي بيعملها الـ JS */
.examx-portal{
  list-style:none;
  position:fixed;
  z-index:2147483000;
  min-width:220px;
  background:#fff;
  border:1px solid #e5e7eb;
  border-radius:16px;
  padding:8px;
  box-shadow:0 18px 45px rgba(15,23,42,0.2);
}
.examx-portal.hidden{display:none;}

.examx-portal li{
  margin:0;
  padding:0;
}
.examx-portal a{
  display:block;
  width:100%;
  box-sizing:border-box;
  padding:9px 12px;
  border-radius:10px;
  text-decoration:none;
  color:#111827;
  font-size:13px;
  transition:background .15s ease;
}
.examx-portal a:hover{
  background:#EFF6FF;
}

/* ====== Responsive ====== */
@media (max-width:1100px){
  .tawjihi2009__subjects-grid{
    grid-template-columns:repeat(3,minmax(0,1fr));
  }
}

@media (max-width:900px){
  .tawjihi2009__layout{
    flex-direction:column-reverse;
    align-items:center;
  }
  .tawjihi2009__right{
    margin: auto;
  }
}

@media (max-width:768px){
  .tawjihi2009{
    padding:40px 0 60px;
  }
  .tawjihi2009__subjects-grid{
    grid-template-columns:repeat(3,minmax(0,1fr));
    gap:20px;
  }
  .tawjihi2009__subject{
        width: 140px;
        height: 160px;
  }
      a.text-decoration-none {
        font-size: 12px!important;
        max-width: 48px;
        transform: translateY(-12px)!important;
    }
        .tawjihi2009__right {
        margin: auto;
        display: none;
    }
  .tawjihi2009__subject-link{
    font-size:12px;
    padding:0 10px;
  }
  .examx-portal{
    left:8px!important;
    right:8px!important;
    width:auto!important;
    min-width:0!important;
    max-width:none!important;
  }
  
  .tawjihi2009__inner {
    padding: 0 0px 0 0;
}

}

@media (max-width:640px){
  .tawjihi2009__subjects-grid{
    grid-template-columns:repeat(2,minmax(0,1fr));
  }
  .tawjihi2009__year{
    font-size:42px;
    padding-inline:18px;
  }
}

@media (max-width:480px){
  .tawjihi2009__head h2{
    font-size:18px;
  }
  .tawjihi2009__section-title{
    font-size:24px;
  }
  .tawjihi2009__subtitle{
    font-size:20px;
  }
}
</style>
@endpush

@push('scripts')
<script>
(function(){
  let portal = null, owner = null;

  function closeAll(){
    if (portal){
      portal.remove();
      portal = null;
    }
    if (owner){
      owner.classList.remove('is-open');
      owner = null;
    }
  }

  function openPortal(btn){
    const wrap = btn.closest('.subject-plus-dropdown-examx');
    const tmpl = wrap.querySelector('.examx-menu');
    if (!tmpl) return;

    closeAll();

    const ul = document.createElement('ul');
    ul.className = 'examx-portal';
    ul.innerHTML = tmpl.innerHTML;
    document.body.appendChild(ul);

    const r = btn.getBoundingClientRect();
    const px = Math.max(8, Math.min(window.innerWidth - ul.offsetWidth - 8, r.left + r.width/2 - ul.offsetWidth/2));
    const py = r.bottom + 8;

    ul.style.left = px + 'px';
    ul.style.top  = py + 'px';

    portal = ul;
    owner  = wrap;
    owner.classList.add('is-open');
  }

  document.addEventListener('click', function(e){
    const btn = e.target.closest('.subject-plus-dropdown-examx .examx-pill');
    if (btn){
      e.preventDefault();
      openPortal(btn);
      return;
    }
    if (portal && !portal.contains(e.target)){
      closeAll();
    }
  });

  window.addEventListener('scroll', function(){ if (portal) closeAll(); }, {passive:true});
  window.addEventListener('resize', function(){ if (portal) closeAll(); });
})();
</script>
@endpush
