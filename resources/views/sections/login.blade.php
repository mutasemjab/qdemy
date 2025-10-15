@extends('layouts.front')

@section('content')
<div id="loginSection" class="register-form">
    <div class="card">
        <h2 style="text-align: center; margin-bottom: 2rem; color: var(--text-dark); font-size: 2rem;">
            <i class="fas fa-sign-in-alt" style="color: var(--primary-color); margin-left: 0.5rem;"></i>
            تسجيل الدخول
        </h2>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-right: 1rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-phone"></i> رقم الهاتف
                </label>
                <input type="tel" name="phone" class="form-control" required
                       placeholder="07xxxxxxxx" value="{{ old('phone') }}"
                       pattern="^07[0-9]{8}$"
                       maxlength="10"
                       title="رقم الهاتف يجب أن يكون 10 أرقام ويبدأ بـ 07">
                <small class="form-text text-muted">مثال: 0791234567</small>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-sign-in-alt"></i>
                تسجيل الدخول
            </button>
        </form>

        <!-- Registration link section -->
        <div style="text-align: center; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #e0e0e0;">
            <p style="margin-bottom: 1rem; color: var(--text-muted);">
                ليس لديك حساب؟
            </p>
            <a href="{{ route('register') }}" class="btn btn-outline-primary" style="text-decoration: none;">
                <i class="fas fa-user-plus"></i>
                إنشاء حساب جديد
            </a>
        </div>
    </div>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
    const phone = document.querySelector('input[name="phone"]').value;
    const phoneRegex = /^07[0-9]{8}$/;
    
    if (!phoneRegex.test(phone)) {
        e.preventDefault();
        alert('رقم الهاتف يجب أن يكون 10 أرقام ويبدأ بـ 07');
        return false;
    }
});

// Real-time validation feedback
document.querySelector('input[name="phone"]').addEventListener('input', function() {
    const phone = this.value;
    const phoneRegex = /^07[0-9]{8}$/;
    
    if (phone.length > 0 && !phoneRegex.test(phone)) {
        this.style.borderColor = '#e74c3c';
    } else if (phoneRegex.test(phone)) {
        this.style.borderColor = '#27ae60';
    } else {
        this.style.borderColor = '';
    }
});
</script>
@endsection