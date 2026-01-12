@php $hideFooter = true; @endphp
@extends('layouts.app')

@section('title', __('front.reset_password'))

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
            <h3>{{ __('front.reset_password') }}</h3>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method='post' action="{{ route('user.forgot-password.reset.submit') }}">
                @csrf
                <input value="{{ old('password') }}" name="password" type="password" placeholder="{{ __('front.new_password') }}">
                @error('password')<span class="text-danger">{{ $message }}</span>@enderror

                <input value="{{ old('password_confirmation') }}" name="password_confirmation" type="password" placeholder="{{ __('front.confirm_password') }}">
                @error('password_confirmation')<span class="text-danger">{{ $message }}</span>@enderror

                <button class="submit-btn" type="submit">{{ __('front.reset_password') }}</button>
            </form>

            <p class="login-link">{{ __('front.remember_password') }} <a href="{{ route('user.login') }}">{{ __('front.login') }}</a></p>
        </div>
    </div>
</section>
@endsection