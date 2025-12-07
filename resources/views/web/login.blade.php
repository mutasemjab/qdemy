@php $hideFooter = true; @endphp
@extends('layouts.app')

@section('title', __('front.login'))

@section('content')
<section class="auth-page">
    <div class="auth-overlay"></div>

    <div class="auth-content-wrapper">
        <div class="auth-info-box">
           <br>
           <br>
           <br>
            <h2>{{ __('front.start_building_future') }}</h2>
            <p>{{ __('front.learning_platform_desc') }}</p>
        </div>

        <div class="auth-form-box">
                <p class="welcome-text">
                  {{ __('front.welcome_to') }}
                  <img src="{{ asset('images/logo.png') }}" alt="Qdemy" class="welcome-logo">
                </p>
            <h3>{{ __('front.login') }}</h3>

            <form method='post' action="{{ route('user.login.submit') }}">
                @csrf
                <input  value="{{old('phone') }}" name="phone" type="phone" placeholder="{{ __('front.phone_number') }}">
                @error('phone')<span class="text-danger">{{ $message }}</span>@enderror

                <input  value="{{old('password') }}" name="password" type="password" placeholder="{{ __('front.password') }}">
                @error('password')<span class="text-danger">{{ $message }}</span>@enderror

                <button class="submit-btn" type="submit">{{ __('front.login') }}</button>
            </form>

            <p class="login-link">{{ __('front.no_account') }} <a href="{{route('user.register')}}">{{ __('front.create_account') }}</a></p>
        </div>
    </div>
</section>
@endsection
