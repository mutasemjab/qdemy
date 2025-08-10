@extends('layouts.user')
@section('css')
<style>
  /* Basic modal styles without Bootstrap */
  .forgot-password-modal {
    border-radius: 10px;
    border: none;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
  }
  
  .forgot-password-modal .modal-title {
    color: #2C3F3D;
    font-size: 28px;
    font-weight: 700;
  }
  
  .forgot-password-modal .form-control {
    border-radius: 8px;
    border: 1px solid #ddd;
    padding: 12px 15px;
    font-size: 16px;
    display: block;
    width: 100%;
    margin-bottom: 15px;
  }
  
  .forgot-password-modal .form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    outline: none;
  }
  
  .forgot-password-modal .form-label {
    font-weight: 500;
    color: #333;
    display: block;
    margin-bottom: 8px;
  }
  
  .forgot-password-btn {
    background-color: #2C3F3D;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 500;
    font-size: 17px;
    transition: all 0.3s ease;
    width: 100%;
    padding: 12px 0;
    cursor: pointer;
  }
  
  .forgot-password-btn:hover {
    background-color: #0b5ed7;
    transform: translateY(-2px);
  }
  
  /* Custom modal styling without Bootstrap */
  .modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0,0,0,0.5);
    z-index: 1000;
  }
  
  .modal-container {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1001;
    width: 90%;
    max-width: 500px;
    background-color: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
  }
  
  .modal-header {
    position: relative;
    margin-bottom: 20px;
  }
  
  .modal-close {
    position: absolute;
    top: 0;
    right: 0;
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    padding: 0;
    color: #666;
  }
  
  .modal-body {
    margin-bottom: 20px;
  }
  
  .d-none {
    display: none;
  }
  
  .form-group {
    margin-bottom: 16px;
  }
</style>
@endsection

@section('content')
<div class="container">
        <div class="left-section-login">
            <img src="{{ asset('assets_front/assets/images/login.png') }}" alt="Battel Olive Oil Products" class="login-image">
        </div>
        
        <div class="right-section">
            <div class="signin-form">
                <p class="welcome-text">Welcome to <strong>Battel</strong></p>
                <h1 class="signin-title">Sign in</h1>
                
            
            <form method="POST" action="{{ route('user.login.submit') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Enter your phone number</label>
                    <input type="text" 
                           name="phone" 
                           class="form-input @error('login') is-invalid @enderror" 
                           placeholder="Phone Number"
                           value="{{ old('login') }}"
                           required>
                    @error('login')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Enter your Password</label>
                    <input type="password" 
                           name="password" 
                           class="form-input @error('password') is-invalid @enderror" 
                           placeholder="Password"
                           required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="forgot-password">
                        <a href="javascript:void(0);" id="forgotPasswordLink">{{ __('messages.forgot_password') }}</a>
                    </div>
                </div>
                

<div class="form-group" style="display: flex; align-items: center;">
  <label class="remember-me" style="display: inline-flex; align-items: center; cursor: pointer; white-space: nowrap;">
    <input type="checkbox" name="remember" style="margin: 0; vertical-align: middle;">
    <span style="margin-left: 6px; font-size: 14px;">Remember me</span>
  </label>
</div>

                
                <button type="submit" class="signin-button">Sign In</button>
                
                <div class="signup-link">
                    No Account? <a href="{{ route('user.register') }}">Sign up</a>
                </div>
            </form>
            </div>
        </div>
</div>
 
<!-- Custom Modal without Bootstrap - Updated to show all fields -->
<div id="modalOverlay" class="modal-overlay"></div>
<div id="forgotPasswordModal" class="modal-container forgot-password-modal">
  <div class="modal-header">
    <h2 class="f-pass-t">{{ __('messages.forgot_password') }}</h2>
    <button type="button" class="modal-close" id="modalClose">&times;</button>
  </div>
  <div class="modal-body">
    <form id="forgotPasswordForm" action="{{ route('password.reset') }}" method="POST">
      @csrf
      
      <div class="form-group">
        <label for="phone" class="form-label">{{ __('messages.enter_phone_number') }}</label>
        <input type="text" 
              class="form-control" 
              id="phone" 
              name="phone" 
              placeholder="{{ __('messages.phone_placeholder') }}" 
              required>
      </div>
      
      <div class="form-group">
        <label for="password" class="form-label">{{ __('messages.new_password') }}</label>
        <input type="password" 
              class="form-control" 
              id="password" 
              name="password" 
              placeholder="{{ __('messages.password_placeholder') }}" 
              required>
      </div>
      
      <div class="form-group">
        <label for="password_confirmation" class="form-label">{{ __('messages.confirm_new_password') }}</label>
        <input type="password" 
              class="form-control" 
              id="password_confirmation" 
              name="password_confirmation" 
              placeholder="{{ __('messages.password_placeholder') }}" 
              required>
      </div>
      
      <button type="submit" class="forgot-password-btn">
        {{ __('messages.reset_password') }}
      </button>
    </form>

  </div>
</div>

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        {{ $errors->first() }}
    </div>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Get elements
  const forgotPasswordLink = document.getElementById('forgotPasswordLink');
  const modalOverlay = document.getElementById('modalOverlay');
  const modal = document.getElementById('forgotPasswordModal');
  const modalClose = document.getElementById('modalClose');
  const forgotPasswordForm = document.getElementById('forgotPasswordForm');

  // Check if elements exist
  if (!forgotPasswordLink || !modalOverlay || !modal || !modalClose) {
    console.error('Required elements not found');
    return;
  }

  // Open modal
  forgotPasswordLink.addEventListener('click', function(e) {
    e.preventDefault();
    modalOverlay.style.display = 'block';
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden'; // Prevent scrolling when modal is open
  });

  // Close modal function
  function closeModal() {
    modalOverlay.style.display = 'none';
    modal.style.display = 'none';
    document.body.style.overflow = ''; // Restore scrolling
    
    // Reset form when closing
    if (forgotPasswordForm) {
      forgotPasswordForm.reset();
    }
  }

  // Close when clicking X
  modalClose.addEventListener('click', closeModal);

  // Close when clicking outside
  modalOverlay.addEventListener('click', function(e) {
    if (e.target === modalOverlay) {
      closeModal();
    }
  });

  // Handle escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && modalOverlay.style.display === 'block') {
      closeModal();
    }
  });

  // Form submission client-side validation
  if (forgotPasswordForm) {
    forgotPasswordForm.addEventListener('submit', function(e) {
      const password = document.getElementById('password').value;
      const confirmPassword = document.getElementById('password_confirmation').value;
      
      if (password !== confirmPassword) {
        e.preventDefault();
        alert('{{ __("messages.passwords_dont_match") }}');
        return false;
      }
      
      // If passwords match, form will submit normally to your controller
    });
  }

  document.addEventListener("DOMContentLoaded", function () {
  const forgotLink = document.getElementById('forgotPasswordLink');
  const modal = document.getElementById('forgotPasswordModal');

  if (forgotLink && modal) {
    forgotLink.addEventListener('click', function () {
      modal.style.display = 'flex';
    });

    window.addEventListener('click', function (e) {
      if (e.target === modal) {
        modal.style.display = 'none';
      }
    });
  }
});

function closeForgotModal() {
  document.getElementById("forgotPasswordModal").style.display = "none";
}

});
</script>
@endpush
@endsection