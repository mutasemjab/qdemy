{{-- resources/views/bank-questions.blade.php --}}
@extends('layouts.app')

@section('title','أسئلة سنوات وزارية')

@section('content')
<section class="bq-page">

  @php
    $items = [
      ['title'=>'extensive paragraphs answers1','subject'=>'اللغة الإنجليزية-مكثف 2008 (شادي المصري)','downloads'=>10,'date'=>'20-7-2025'],
      ['title'=>'extensive paragraphs answers1','subject'=>'اللغة الإنجليزية-مكثف 2008 (شادي المصري)','downloads'=>10,'date'=>'20-7-2025'],
      ['title'=>'extensive paragraphs answers1','subject'=>'اللغة الإنجليزية-مكثف 2008 (شادي المصري)','downloads'=>10,'date'=>'20-7-2025'],
      ['title'=>'extensive paragraphs answers1','subject'=>'اللغة الإنجليزية-مكثف 2008 (شادي المصري)','downloads'=>10,'date'=>'20-7-2025'],
    ];
  @endphp

      <div class="universities-header-wrapper">
        <div class="universities-header">
            <h2>أسئلة سنوات وزارية</h2>
        </div>
    </div>

<div class="examx-filters">
<div class="examx-row-2">
  <div class="examx-dropdown">
    <button class="examx-pill">
      <i class="fa-solid fa-caret-down"></i>
      <span>اختر البرنامج</span>
    </button>
    <ul class="examx-menu">
      <li>اللغة العربية</li>
      <li>الرياضيات</li>
      <li>العلوم</li>
    </ul>
  </div>

  <div class="examx-dropdown">
    <button class="examx-pill">
      <i class="fa-solid fa-caret-down"></i>
      <span>اختر المادة</span>
    </button>
    <ul class="examx-menu">
      <li>الصف الأول</li>
      <li>الصف الثاني</li>
      <li>الصف الثالث</li>
    </ul>
  </div>

</div>


    <div class="examx-search">
      <input type="text" placeholder="البحث">
      <i class="fa-solid fa-magnifying-glass"></i>
    </div>
  </div>

  <div class="bq-list">
    @foreach($items as $i)
<div class="bq-item">
  <div class="bq-thumb">
    <img data-src="{{ asset('assets_front/images/pdf.png') }}" alt="PDF">
  </div>

  <div class="bq-right">
    <h4 class="bq-title">{{ $i['title'] }}</h4>
    <div class="bq-sub">{{ $i['subject'] }}</div>
    <div class="bq-meta">
      <span>{{ $i['date'] }}</span>
      <span class="bq-sep">|</span>
      <span>عدد مرات التحميل ( {{ $i['downloads'] }} ) </span>
    </div>
  </div>

  <div class="bq-left">
    <a href="#" class="bq-btn bq-share"><i class="fa-solid fa-share-nodes"></i><span>المشاركة</span></a>
    <a href="#" class="bq-btn bq-download"><i class="fa-solid fa-download"></i><span>تحميل الملف</span></a>
  </div>
</div>

    @endforeach
  </div>

</section>
@endsection
