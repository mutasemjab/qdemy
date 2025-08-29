@extends('layouts.app')

@section('title', __('front.Download App'))

@section('content')
<section class="dl-app">
  <div class="dl-wrap">

    <div class="dl-info">
      <h2 class="dl-title">{{ __('front.Download the App Now') }}</h2>
      <p class="dl-sub">{{ __('front.Download the app for easier and more flexible usage on your phone. Learn, follow, communicate with complete flexibility.') }}</p>

      <div class="dl-stores">
        <a href="#" class="dl-store"><img data-src="{{ asset('assets_front/images/store-huawei.png') }}" alt="AppGallery"></a>
        <a href="#" class="dl-store"><img data-src="{{ asset('assets_front/images/store-apple.png') }}" alt="App Store"></a>
        <a href="#" class="dl-store"><img data-src="{{ asset('assets_front/images/store-google.png') }}" alt="Google Play"></a>
      </div>
    </div>

    <div class="dl-visual">
      <img data-src="{{ asset('assets_front/images/app-phone.png') }}" alt="Qdemy App">
    </div>

  </div>
</section>
@endsection