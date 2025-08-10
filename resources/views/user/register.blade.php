@extends('layouts.user')

@section('content')
<div class="container">
        <div class="left-section-login">
            <img src="{{ asset('assets_front/assets/images/register.png') }}" 
                 alt="Battel Olive Oil Products" 
                 class="product-image">
        </div>
        
        <div class="right-section">
            <div class="signin-form">
                <p class="welcome-text">Welcome to <strong>Battel</strong></p>
                <h1 class="signin-title">Sign up</h1>
                
                <form method="POST" action="{{ route('user.register.submit') }}">
                @csrf
                    <div class="form-group">
                        <label class="form-label">Enter your name</label>
                        <input type="text" name="name" class="form-input" placeholder="Enter Your Name">
                    </div>
                  
                    <div class="form-group">
                        <label class="form-label">Enter your phone number</label>
                        <input type="text" name="phone" class="form-input" placeholder="Enter Your Phone Number">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Enter your Password</label>
                        <input type="password" name="password" class="form-input" placeholder="Password">
                        <div class="forgot-password">
                            <a href="javascript:void(0);" id="forgotPasswordLink">{{ __('messages.forgot_password') }}</a>

                        </div>
                    </div>
                    
                    <button type="submit" class="signin-button">Sign Un</button>
                    
                    <div class="signup-link">
                        Hava an account? <a href="{{ route('user.login') }}">Sign in</a>
                    </div>
                </form>
            </div>
        </div>
</div>
@endsection