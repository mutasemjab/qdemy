@extends('layouts.app')
@section('title','عروض البكجات')

@section('content')
<section class="pkgo-wrap">


      <div class="universities-header-wrapper">
        <div class="universities-header">
            <h2>البكجات والعروض</h2>
        </div>
    </div>

  <div class="co-chooser">
    <button class="co-chooser-btn" id="coChooserBtn">
      <span>اختر البطاقة الخاصة بك</span>
      <i class="fa-solid fa-caret-down"></i>
    </button>
    <ul class="co-chooser-list" id="coChooserList">
      <li data-label="الصفوف الأساسية">الصفوف الأساسية</li>
      <li data-label="التوجيهي">التوجيهي</li>
      <li data-label="الجامعات والكليات">الجامعات والكليات</li>
      <li data-label="البرنامج الدولي">البرنامج الدولي</li>
    </ul>
  </div>

  <div class="pkgo-head">بطاقات الصفوف الأساسية</div>

  <div class="pkgo-row">
    <div class="pkgo-side pkgo-side-1">
      <span>البكج الأول</span>
    </div>
    <div class="pkgo-mid">
      <div class="pkgo-title">
        <h3>بكج المادة الواحدة</h3>
        <span class="pkgo-year">2009</span>
      </div>
      <p class="pkgo-desc">في هذا البكج يستطيع الطالب شراء مادة واحدة من هذه المواد</p>
      <ul class="pkgo-tags">
        <li>اللغة العربية</li>
        <li>اللغة الإنجليزية</li>
        <li>التربية الإسلامية</li>
        <li>تاريخ الأردن</li>
      </ul>
    </div>

    <div class="pkgo-cta-col">
      <a href="#" class="pkgo-cta">شراء/ تفعيل البطاقة</a>
    </div>
    <div class="pkgo-price">30 <span>JOD</span></div>

  </div>

  <div class="pkgo-row">

    <div class="pkgo-side pkgo-side-2">
      <span>البكج الثاني</span>
    </div>

    <div class="pkgo-mid">
      <div class="pkgo-title">
        <h3>بكج المادة الواحدة</h3>
        <span class="pkgo-year">2009</span>
      </div>
      <p class="pkgo-desc">في هذا البكج يستطيع الطالب شراء مادة واحدة من هذه المواد</p>
      <ul class="pkgo-tags">
        <li>اللغة العربية</li>
        <li>اللغة الإنجليزية</li>
        <li>التربية الإسلامية</li>
        <li>تاريخ الأردن</li>
      </ul>
    </div>

    <div class="pkgo-cta-col">
      <a href="#" class="pkgo-cta">شراء/ تفعيل البطاقة</a>
    </div>
    <div class="pkgo-price">30 <span>JOD</span></div>
  </div>

  <div class="pkgo-row">
    <div class="pkgo-side pkgo-side-3">
      <span>البكج الثالث</span>
    </div>

    <div class="pkgo-mid">
      <div class="pkgo-title">
        <h3>بكج المادة الواحدة</h3>
        <span class="pkgo-year">2009</span>
      </div>
      <p class="pkgo-desc">في هذا البكج يستطيع الطالب شراء مادة واحدة من هذه المواد</p>
      <ul class="pkgo-tags">
        <li>اللغة العربية</li>
        <li>اللغة الإنجليزية</li>
        <li>التربية الإسلامية</li>
        <li>تاريخ الأردن</li>
      </ul>
    </div>

    <div class="pkgo-cta-col">
      <a href="#" class="pkgo-cta">شراء/ تفعيل البطاقة</a>
    </div>
    <div class="pkgo-price">30 <span>JOD</span></div>
  </div>

</section>
@endsection
