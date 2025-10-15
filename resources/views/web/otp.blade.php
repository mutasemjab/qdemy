@php $hideFooter = true; @endphp
@extends('layouts.app')

@section('title', __('front.login_confirm'))

@section('content')
<section class="auth-page">
    <div class="auth-overlay"></div>

    <div class="otp-section">
        <div class="otp-content">
            <img data-src="{{ asset('assets_front/images/logo-white.png') }}" alt="Qdemy Logo" class="otp-logo">

            @if(session('success'))
                <div class="alert alert-success text-center mb-3">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger text-center mb-3">
                    @foreach($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif

            <p class="otp-sent">{{ __('front.otp_sent_to') }}</p>
            <p class="otp-phone">{{ $phone ?? 'N/A' }}</p>

            <form action="{{ route('otp.verify.submit') }}" method="POST" id="otpForm">
                @csrf
                <div class="otp-inputs">
                    <input type="text" maxlength="1" class="otp-digit" name="otp[]" required>
                    <input type="text" maxlength="1" class="otp-digit" name="otp[]" required>
                    <input type="text" maxlength="1" class="otp-digit" name="otp[]" required>
                    <input type="text" maxlength="1" class="otp-digit" name="otp[]" required>
                </div>
                <input type="hidden" name="otp" id="otpHidden">
            </form>

            <p class="resend-text">
                {{ __('front.resend_otp') }}
                <span id="countdown">(05:00)</span>
                <a href="{{ route('otp.resend') }}" id="resendLink" style="display:none; color: #fff; text-decoration: underline; margin-left: 10px;">
                    {{ __('front.resend') }}
                </a>
            </p>
        </div>
    </div>

</section>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.otp-digit');
    const form = document.getElementById('otpForm');
    const hiddenInput = document.getElementById('otpHidden');
    
    // Auto-focus first input
    if (inputs.length > 0) {
        inputs[0].focus();
    }
    
    // Handle input events
    inputs.forEach((input, index) => {
        // Input event - auto advance
        input.addEventListener('input', (e) => {
            // Only allow numbers
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
            
            if (e.target.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
            
            // Combine all inputs
            const otp = Array.from(inputs).map(inp => inp.value).join('');
            hiddenInput.value = otp;
            
            // Auto-submit when all filled
            if (otp.length === 4 && /^\d{4}$/.test(otp)) {
                form.submit();
            }
        });
        
        // Keydown event - handle backspace
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && e.target.value === '' && index > 0) {
                inputs[index - 1].focus();
            }
        });
        
        // Focus event - select all
        input.addEventListener('focus', (e) => {
            e.target.select();
        });
    });
    
    // Paste support
    inputs[0].addEventListener('paste', (e) => {
        e.preventDefault();
        const paste = e.clipboardData.getData('text');
        
        if (paste.length === 4 && /^\d{4}$/.test(paste)) {
            inputs.forEach((input, index) => {
                input.value = paste[index];
            });
            hiddenInput.value = paste;
            form.submit();
        }
    });
    
    // Countdown timer (5 minutes)
    let timeLeft = 300; // 5 minutes in seconds
    const countdownEl = document.getElementById('countdown');
    const resendLink = document.getElementById('resendLink');
    
    function updateCountdown() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        countdownEl.textContent = `(${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')})`;
        
        if (timeLeft === 0) {
            countdownEl.style.display = 'none';
            resendLink.style.display = 'inline';
        } else {
            timeLeft--;
            setTimeout(updateCountdown, 1000);
        }
    }
    
    updateCountdown();
});
</script>
@endsection