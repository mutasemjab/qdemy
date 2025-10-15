@if(isset($userName))
<div style="position: fixed; bottom: 2rem; left: 2rem;">
    <a href="{{ route('logout') }}" class="btn" style="background: rgba(220, 53, 69, 0.9); color: white; padding: 0.5rem 1rem; font-size: 0.9rem; text-decoration: none;">
        <i class="fas fa-sign-out-alt"></i>
        تسجيل الخروج
    </a>
</div>
@endif