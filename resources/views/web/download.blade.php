@extends('layouts.app')

@section('title', __('front.Download App'))

@section('content')
<section class="dl-app">
  <div class="dl-wrap">

    <div class="dl-info">
      <h2 data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200" class="dl-title">{{ __('front.Download the App Now') }}</h2>
      <p data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300" class="dl-sub">{{ __('front.Download the app for easier and more flexible usage on your phone. Learn, follow, communicate with complete flexibility.') }}</p>

      <div class="dl-stores">
        <a data-aos="zoom-in" data-aos-duration="1000" data-aos-delay="400" href="#" class="dl-store"><img data-src="{{ asset('assets_front/images/store-huawei.png') }}" alt="AppGallery"></a>
        <a data-aos="zoom-in" data-aos-duration="1000" data-aos-delay="500"  href="#" class="dl-store"><img data-src="{{ asset('assets_front/images/store-apple.png') }}" alt="App Store"></a>
        <a data-aos="zoom-in" data-aos-duration="1000" data-aos-delay="500"  href="#" class="dl-store"><img data-src="{{ asset('assets_front/images/store-google.png') }}" alt="Google Play"></a>
      </div>
    <div class="dl0">
        <a data-aos="zoom-in" data-aos-duration="1000" data-aos-delay="500" href="#" class="dl-store0"><img data-src="{{ asset('assets_front/images/store-d.png') }}" alt="Download"></a>
      </div>
    </div>

    <div data-aos="zoom-in" data-aos-duration="1000" data-aos-delay="300"  class="anim animate-wiggle dl-visual">
      <img data-src="{{ asset('assets_front/images/app-phone.png') }}" alt="Qdemy App">
    </div>

  </div>
</section>
@endsection