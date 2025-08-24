@php $hideFooter = true; @endphp
@extends('layouts.app')

@section('title', 'تأكيد دخول')

@section('content')
<section class="auth-page">
    <div class="auth-overlay"></div>

    <div class="otp-section">
        <div class="otp-content">
            <img data-src="{{ asset('assets_front/images/logo-white.png') }}" alt="Qdemy Logo" class="otp-logo">

            <p class="otp-sent">OTP Sent to:</p>
            <p class="otp-phone">079989584938</p>

            <div class="otp-inputs">
                <input type="text" maxlength="1">
                <input type="text" maxlength="1">
                <input type="text" maxlength="1">
                <input type="text" maxlength="1">
            </div>

            <p class="resend-text">Resend OTP <span>(0012)</span></p>
        </div>
    </div>

</section>
@endsection
