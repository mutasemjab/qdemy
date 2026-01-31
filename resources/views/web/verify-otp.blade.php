@php $hideFooter = true; @endphp
@extends('layouts.app')

@section('title', __('front.verify_otp'))

@section('content')
<style>
    input , button {
        font-size: 20px
    }
</style>
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
            <h3>{{ __('front.verify_otp') }}</h3>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <p class="info-text">{{ __('front.otp_sent_to') }} {{ session('reset_phone') }}</p>

            <form method='post' action="{{ route('user.forgot-password.verify-otp.submit') }}">
                @csrf
                <input value="{{ old('otp') }}" name="otp" type="text" placeholder="{{ __('front.enter_otp') }}" maxlength="6">
                @error('otp')<span class="text-danger">{{ $message }}</span>@enderror

                <button class="submit-btn" type="submit">{{ __('front.verify_otp') }}</button>
            </form>

            <p class="login-link">
                {{ __('front.did_not_receive_otp') }} 
                <a href="{{ route('user.forgot-password.resend-otp') }}" onclick="event.preventDefault(); document.getElementById('resend-form').submit();">
                    {{ __('front.resend_otp') }}
                </a>
            </p>

            <form id="resend-form" action="{{ route('user.forgot-password.resend-otp') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
</section>
@endsection