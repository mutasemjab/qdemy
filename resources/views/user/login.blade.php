@php $hideFooter = true; @endphp
@extends('layouts.app')

@section('title', 'تسجيل دخول')

@section('content')
<section class="auth-page">
    <div class="auth-overlay"></div>

    <div class="auth-content-wrapper">
        <div class="auth-info-box">
            <img data-src="{{ asset('assets_front/images/logo-white.png') }}" alt="Qdemy Logo">
            <h2>ابدأ في بناء مستقبلك</h2>
            <p>منصة تعليمية تساعدك على التعلم والتطور من أي مكان وفي أي وقت</p>
        </div>

        <div class="auth-form-box">
            <p class="welcome-text">مرحباً بك في <strong>Qdemy</strong></p>
            <h3>تسجيل دخول</h3>

            <form method='post' action="{{ route('user.login.submit') }}">
                @csrf
                <input  value="{{old('phone') }}" name="phone"    type="phone"    placeholder="رقم الهاتف">
                @error('phone')<span class="text-danger">{{ $message }}</span>@enderror
                <input  value="{{old('password') }}" name="password" type="password" placeholder="كلمة المرور">
                @error('password')<span class="text-danger">{{ $message }}</span>@enderror
                <button class="submit-btn" type="submit">تسجيل دخول</button>
            </form>

            <p class="login-link">ليس لديك حساب؟ <a href="#">انشاء حساب</a></p>
        </div>
    </div>
</section>
@endsection
