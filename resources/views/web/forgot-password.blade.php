@php $hideFooter = true; @endphp
@extends('layouts.app')

@section('title', __('front.forgot_password'))

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
            <h3>{{ __('front.forgot_password') }}</h3>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method='post' action="{{ route('user.forgot-password.check-phone') }}">
                @csrf
                <input value="{{ old('phone') }}" name="phone" type="text" placeholder="{{ __('front.phone_number') }}">
                @error('phone')<span class="text-danger">{{ $message }}</span>@enderror

                <button class="submit-btn" type="submit">{{ __('front.send_otp') }}</button>
            </form>

            <p class="login-link">{{ __('front.remember_password') }} <a href="{{ route('user.login') }}">{{ __('front.login') }}</a></p>
        </div>
    </div>
</section>
@endsection