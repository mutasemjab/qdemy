@extends('layouts.app')
@section('title',translate_lang('packages_offers'))

@section('content')
<section class="pkgo-wrap">


    <div class="universities-header-wrapper">
        <div class="universities-header">
            <h2>{{translate_lang('packages_offers')}}</h2>
        </div>
    </div>

  <div class="co-chooser">
    <button class="co-chooser-btn" id="coChooserBtn">
      <span> {{$programm?->localized_name ?? translate_lang('choose the programm') }} </span>
      <i class="fas fa-caret-down"></i>
    </button>
    @if($programms && $programms->count())
    <ul class="co-chooser-list" id="coChooserList">
      <li><a href="{{ route('packages-offers') }}" class='text-decoration-none'>
        {{translate_lang('all programms')}}</a>
      </li>
      @foreach($programms as $prog)
      <li><a href="{{ route('packages-offers',$prog) }}" class='text-decoration-none'>
        {{$prog->localized_name}}</a>
      </li>
      @endforeach
    </ul>
    @endif
  </div>

  <div class="pkgo-head">
    {{translate_lang('cards')}} {{$programm?->localized_name}}
   </div>

    @foreach($packages as $package)
    <div class="pkgo-row">
        <div class="pkgo-side pkgo-side-1">
        <span>{{ $package->name }}</span>
        </div>
        <div class="pkgo-mid">
        <div class="pkgo-title">
            <h3>{{ $package->name }}</h3>
            <!-- <span class="pkgo-year">{{$package->description}}</span> -->
        </div>
        <p class="pkgo-desc">{{$package->description}}</p>
        @if($package->categories && $package->categories->count())
        <ul class="pkgo-tags">
            @foreach($package->categories as $category)
            <li>
                @if($category->parent) {{$category->parent->localized_name}} -> @endif {{$category->localized_name}}
                <!-- {{$category->localized_name}} -->
            </li>
            @endforeach
        </ul>
        @endif
        </div>
        <div class="pkgo-cta-col">
        <a href="{{route('package',$package)}}" class="pkgo-cta">{{translate_lang('buy_or_activate')}}</a>
        </div>
        <div class="pkgo-price">{{ sprintf('%g', $package->price) }} <span>{{CURRENCY}}</span></div>
    </div>
    @endforeach

</section>
@endsection
