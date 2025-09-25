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
                <li><a href="{{ route('home') }}" class="active">{{ __('front.home') }}</a></li>
                <li><a href="{{ route('contacts') }}">{{ __('front.about_us') }}</a></li>
                <li><a href="{{ route('courses') }}">{{ __('front.courses') }}</a></li>
                <li><a href="{{ route('teachers') }}">{{ __('front.teachers') }}</a></li>
                <li><a href="{{ route('sale-point') }}">{{ __('front.sale_points') }}</a></li>
                <li>
                    <a href="{{ route('community') }}" class="community-link">
                        <span>{{ __('front.community') }}</span>
                        <img src="{{ asset('assets_front/images/icons/community.png') }}" alt="Community Icon" class="icon">
                    </a>
                </li>
                <li><a href="{{ route('exam.index') }}">{{ __('front.Exams') }}</a></li>
                <li><a href="{{ route('download') }}">{{ __('front.Our Apps') }}</a></li>
            </ul>
        </nav>

        <div class="actions">

            <!-- Cart -->
            <a href="{{ route('checkout') }}" class="cart-icon">
                <i class="fas fa-shopping-cart"></i>
            </a>

            <!-- Auth Buttons -->
            @if(!auth('user')?->user())
              <a href="{{ route('user.register') }}" class="btn btn-primary">{{ __('front.create_account') }}</a>
              <a href="{{ route('user.login') }}" class="btn btn-outline">{{ __('front.login') }}</a>
            @else
              <a href="{{ route('student.account') }}" class="btn btn-outline">{{ __('front.my_account') }}</a>
              <form action="{{ route('user.logout') }}" method='post'>@csrf
                <button style='border: 1px solid #0055D2;padding: 8px;' class="btn btn-primary">{{ __('front.logout') }}</button>
              </form>
            @endif

            <!-- Language Dropdown -->
            <div class="lang-dropdown">
                <button class="lang-btn" type="button"><i class="fas fa-globe"></i></button>
                <div class="lang-menu">
                    <a href="{{ LaravelLocalization::getLocalizedURL('ar') }}">العربية</a>
                    <a href="{{ LaravelLocalization::getLocalizedURL('en') }}">English</a>
                </div>
            </div>

            <!-- Hamburger Button -->
            <button class="menu-toggle" id="menuToggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>

</header>

    <script>
document.addEventListener("DOMContentLoaded", function(){
  const langDropdown = document.querySelector(".lang-dropdown");
  const langBtn = document.querySelector(".lang-btn");

  langBtn.addEventListener("click", function(e){
    e.stopPropagation();
    langDropdown.classList.toggle("open");
  });

  document.addEventListener("click", function(e){
    if(!langDropdown.contains(e.target)){
      langDropdown.classList.remove("open");
    }
  });
});
</script>
