@extends('layouts.app')
@section('title','المعلمون')

@section('content')
<section class="tch-wrap">

    <div class="universities-header-wrapper">
        <div class="universities-header">
            <h2>المعلمون</h2>
        </div>
    </div>

<div class="examx-filters">
<div class="examx-row">
  <div class="examx-dropdown">
    <button class="examx-pill">
      <i class="fa-solid fa-caret-down"></i>
      <span>اختر المادة</span>
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
      <span>اختر الصف</span>
    </button>
    <ul class="examx-menu">
      <li>الصف الأول</li>
      <li>الصف الثاني</li>
      <li>الصف الثالث</li>
    </ul>
  </div>

  <div class="examx-dropdown">
    <button class="examx-pill">
      <i class="fa-solid fa-caret-down"></i>
      <span>اختر الفصل</span>
    </button>
    <ul class="examx-menu">
      <li>الفصل الأول</li>
      <li>الفصل الثاني</li>
    </ul>
  </div>
</div>


<div class="tch-grid">
  @foreach ($teachers as $teacher)
  <!-- photo -->
    <div class="tch-item">
      <a href="{{ route('teacher',$teacher->id) }}">
        <img data-src="{{ $teacher->photo_url }}">
      </a>
    </div>
  @endforeach
</div>

</section>
@endsection
