@extends('layouts.app')
@section('title','طلب البطاقات')

@section('content')
<section class="co-page">

      <div class="universities-header-wrapper">
        <div class="universities-header">
            <h2>طلب بطاقة</h2>
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

  <div class="co-actions">
    <div class="co-action">
      <div class="co-action-title">تواصل معنا عبر الواتساب</div>
      <a href="#" class="co-btn">Contact</a>
    </div>

    <div class="co-action co-action-primary">
      <div class="co-action-title">الدفع كاش</div>
      <a href="#" class="co-btn co-btn-inv">submit</a>
    </div>

        <div class="co-action">
      <div class="co-action-title">الدفع من خلال الفيزا</div>
      <a href="#" class="co-btn co-btn-c">Pay</a>
    </div>
  </div>

  <div class="co-grid">
    @php
      $cards = [
        ['img'=>'images/card-order.png','link'=>'#'],
        ['img'=>'images/card-order.png','link'=>'#'],
        ['img'=>'images/card-order.png','link'=>'#'],
        ['img'=>'images/card-order.png','link'=>'#'],
        ['img'=>'images/card-order.png','link'=>'#'],
        ['img'=>'images/card-order.png','link'=>'#'],
      ];
    @endphp

    @foreach($cards as $c)
      <a class="co-card" href="{{ $c['link'] }}">
        <img data-src="{{ asset($c['img']) }}" alt="card">
      </a>
    @endforeach
  </div>

</section>
@endsection
