<!-- Welcome Section -->
<div class="welcome-section">
    <h1 class="welcome-title">مرحباً بك في خليليو</h1>
    <p class="welcome-subtitle">حقيبتك التعليمية الشاملة للامتحانات والدروس والملفات</p>
    <div class="user-info">
        <i class="fas fa-user"></i>
        <span>{{ $userName ?? 'المستخدم' }}</span>
        <a href="{{ route('user.exam-history') }}" style="text-decoration: none; color:white;">
            <i class="fas fa-chart-line"></i>
            <span>نتائج امتحاناتي </span>
        </a>
    </div>
    
    <!-- ADD THIS BUTTON -->
    <div class="dosyat-button-container" style="margin-top: 2rem;">
        <a href="{{ route('dosyat.index') }}" class="btn-dosyat">
            <i class="fas fa-book"></i>
            <span>الدوسيات</span>
        </a>
    </div>
</div>