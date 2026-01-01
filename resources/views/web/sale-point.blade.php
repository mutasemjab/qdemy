@extends('layouts.app')
@section('title', __('front.Sale Points'))

@section('content')
<section class="sp2-page">
    <div data-aos="flip-up" data-aos-duration="1000" class="anim animate-glow universities-header-wrapper">
        <div class="universities-header">
            <h2>{{ __('front.Sale Points') }}</h2>
        </div>
    </div>

    <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200" class="sp2-head">
        <div class="sp2-brand">{{ __('front.Qdemy Cards') }}</div>
        <div class="sp2-sub">{{ __('front.Cards available in the following libraries:') }}</div>
    </div>

  <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200" class="examx-filters">
    <form method="GET" action="{{ route('sale-point') }}" id="salePointForm">
        <div class="examx-row">
            <div class="examx-dropdown">
                <select class="examx-pill" name="country" id="country_filter" onchange="document.getElementById('salePointForm').submit()">
                    <option value="">{{ __('front.All Countries') }}</option>
                    @foreach($posGrouped->keys() as $country)
                        <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>
                            {{ $country }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="examx-search">
            <i class="fas fa-magnifying-glass"></i>
            <input type="text" 
                   name="search" 
                   placeholder="{{ __('front.Search') }}" 
                   value="{{ request('search') }}">
        </div>
    </form>
</div>

    <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300" class="sp2-box">
        @if($posGrouped->count() > 0)
            @foreach($posGrouped as $countryName => $locations)
                <div class="sp2-group {{ $loop->first ? 'is-open' : '' }}">
                    <button class="sp2-group-head">
                        <i class="fas {{ $loop->first ? 'fa-minus' : 'fa-plus' }}"></i>
                        <span>{{ $countryName }}</span>
                    </button>
                    <div class="sp2-panel">
                        <table class="sp2-table">
                            <thead>
                                <tr>
                                    <th>{{ __('front.Library Name') }}</th>
                                    <th>{{ __('front.Address') }}</th>
                                    <th>{{ __('front.Phone Number') }}</th>
                                    <th>{{ __('front.Location') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($locations as $pos)
                                    <tr>
                                        <td>{{ $pos->name }}</td>
                                        <td>{{ $pos->address }}</td>
                                        <td>
                                            <a href="tel:{{ $pos->phone }}">{{ $pos->phone }}</a>
                                        </td>
                                        <td>
                                            @if($pos->google_map_link)
                                                <a href="{{ $pos->google_map_link }}" 
                                                   target="_blank" 
                                                   class="sp2-loc">
                                                    <i class="fas fa-location-dot"></i> 
                                                    {{ __('front.Library Location') }}
                                                </a>
                                            @else
                                                <span class="sp2-loc" style="color: #ccc;">
                                                    <i class="fas fa-location-dot"></i> 
                                                    {{ __('front.Location Not Available') }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        @else
            <div class="no-results" style="text-align: center; padding: 40px; color: #666;">
                <i class="fas fa-search" style="font-size: 48px; margin-bottom: 20px; opacity: 0.3;"></i>
                <h3>{{ __('front.No Results Found') }}</h3>
                <p>{{ __('front.No sale points found matching your search criteria') }}</p>
                @if(request('search'))
                    <a href="{{ route('sale-point') }}" class="btn btn-primary" style="margin-top: 20px; display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;">
                        {{ __('front.Show All Sale Points') }}
                    </a>
                @endif
            </div>
        @endif
    </div>

</section>

@endsection