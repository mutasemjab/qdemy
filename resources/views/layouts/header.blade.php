<!-- Header Section -->
<header class="header">
    <div class="container">
        <a href="{{ route('home') }}">
        <div class="logo">
            <img src="{{ asset('assets_front/images/logo.png') }}" alt="Logo">
        </div>
        </a>
        <nav class="nav" id="navMenu">
            <ul>
                <li><a href="{{ route('home') }}" class="active">الرئيسية</a></li>
                <li><a href="{{ route('contacts') }}">من نحن؟</a></li>
                <li><a href="{{ route('courses') }}">دورات</a></li>
                <li><a href="{{ route('teachers') }}">المعلمون</a></li>
                <li><a href="{{ route('sale-point') }}">نقاط البيع</a></li>
                <li>
                    <a href="{{ route('community') }}" class="community-link">
                        <span>مجتمع</span>
                        <img src="{{ asset('assets_front/images/icons/community.png') }}" alt="Community Icon" class="icon">
                    </a>
                </li>
                <li><a href="{{ route('e-exam') }}">امتحانات</a></li>
                <li><a href="{{ route('download') }}">تطبيقاتنا</a></li>
            </ul>
        </nav>

        <div class="actions">

            <!-- Cart -->
            <a href="{{ route('checkout') }}" class="cart-icon">
                <i class="fas fa-shopping-cart"></i>
            </a>

            <!-- Auth Buttons -->
            @if(!auth('user')?->user())
              <a href="{{ route('user.register') }}" class="btn btn-primary">إنشاء حساب</a>
              <a href="{{ route('user.login') }}" class="btn btn-outline">تسجيل دخول</a>
            @else
              <a href="{{ route('student.account') }}" class="btn btn-outline">حسابي</a>
              <form action="{{ route('logout') }}" method='post'>@csrf
                <button style='border: 1px solid #0055D2;padding: 8px;' class="btn btn-primary">تسجيل خروج</button>
              </form>
              <!-- <a href="{{ route('logout') }}" class="btn btn-primary">تسجيل خروج</a> -->
            @endif

            <!-- Language Dropdown -->
            <div class="lang-dropdown">
                <button class="lang-btn"><i class="fas fa-globe"></i></button>
                <div class="lang-menu">
                    <a href="#">العربية</a>
                    <a href="#">English</a>
                </div>
            </div>

            <!-- Hamburger Button -->
            <button class="menu-toggle" id="menuToggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
</header>
