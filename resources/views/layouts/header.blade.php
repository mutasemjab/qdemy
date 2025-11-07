<!-- Header Section -->
<header class="header">
    <div class="container">
        <a href="{{ route('home') }}">
            <div class="logo" data-aos="fade" >
                <img src="{{ asset('images/logo.png') }}" alt="Logo">
            </div>
        </a>
<nav class="nav" id="navMenu">
  <ul>
    <li data-aos="fade"><a class="{{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">{{ __('front.home') }}</a></li>

    <li data-aos="fade"><a class="{{ request()->routeIs('about*') ? 'active' : '' }}" href="{{ route('about') }}">{{ __('front.about_us') }}</a></li>
    <li data-aos="fade"><a class="{{ request()->routeIs('contacts*') ? 'active' : '' }}" href="{{ route('contacts') }}">{{ __('front.contacts') }}</a></li>

    <li data-aos="fade"><a class="{{ request()->routeIs('courses*') ? 'active' : '' }}" href="{{ route('courses') }}">{{ __('front.courses') }}</a></li>

    <li data-aos="fade"><a class="{{ request()->routeIs('teachers*') ? 'active' : '' }}" href="{{ route('teachers') }}">{{ __('front.teachers') }}</a></li>

    <li data-aos="fade"><a class="{{ request()->routeIs('sale-point*') ? 'active' : '' }}" href="{{ route('sale-point') }}">{{ __('front.sale_points') }}</a></li>

    <li data-aos="fade">
      <a class="community-link {{ request()->routeIs('community*') ? 'active' : '' }}" href="{{ route('community') }}">
        <span>{{ __('front.community') }}</span>
        <img src="{{ asset('assets_front/images/icons/community.png') }}" alt="Community Icon" class="icon">
      </a>
    </li>

    <li data-aos="fade"><a class="{{ request()->routeIs('exam.*') ? 'active' : '' }}" href="{{ route('exam.index') }}">{{ __('front.Exams') }}</a></li>

    <li data-aos="fade"><a class="{{ request()->routeIs('download*') ? 'active' : '' }}" href="{{ route('download') }}">{{ __('front.Our Apps') }}</a></li>
                <!-- Auth Buttons -->
            @if(!auth('user')?->user())
              <a href="{{ route('user.register') }}" class="btn btn-primary hidden-disc">{{ __('front.create_account') }}</a>
              <a href="{{ route('user.login') }}" class="btn btn-outline hidden-disc">{{ __('front.login') }}</a>
            @else
              <a href="{{ route('student.account') }}" class="btn btn-outline hidden-disc">{{ __('front.my_account') }}</a>
              <form action="{{ route('user.logout') }}" method='post'>@csrf
                <button style='border: 1px solid #0055D2;padding: 8px;' class="btn btn-primary hidden-disc">{{ __('front.logout') }}</button>
              </form>
            @endif

  </ul>
</nav>


        <div data-aos="fade" class="actions">

            <!-- Cart -->
            <a href="{{ route('checkout') }}" class="cart-icon">
                <i class="fas fa-shopping-cart"></i>
            </a>

<div class="auth-cta">
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
 </div>

            <!-- Language Dropdown -->
<div class="language-switch" id="languageSwitch">
  <button class="ls-btn" type="button" id="lsBtn"><i class="fas fa-globe"></i></button>
</div>

            <!-- Hamburger Button -->
            <button class="menu-toggle" id="menuToggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>

</header>

<style>
.ls-btn{display:inline-flex;align-items:center;justify-content:center;width:40px;height:40px;border:0;background:transparent;cursor:pointer}
.ls-menu{display:none;position:fixed;min-width:180px;background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:6px;box-shadow:0 12px 32px rgba(0,0,0,.22);z-index:2147483647}
.ls-menu a{display:block;padding:10px 12px;color:#111827;text-decoration:none;border-radius:8px}
.ls-menu a:hover{background:#f3f4f6}
.ls-btn i {
  font-size: 18px !important;
  color: #111827;
}

</style>

<script>
document.addEventListener('DOMContentLoaded',function(){
  const trigger=document.getElementById('lsBtn');
  if(!trigger) return;
  const menu=document.createElement('div');
  menu.className='ls-menu';
  menu.innerHTML=`<a href="{{ LaravelLocalization::getLocalizedURL('ar') }}">العربية</a><a href="{{ LaravelLocalization::getLocalizedURL('en') }}">English</a>`;
  document.body.appendChild(menu);
  function place(){
    const r=trigger.getBoundingClientRect(),gap=8,w=menu.offsetWidth||200;
    let left=r.right-w; if(left<8) left=8; if(left+w>innerWidth-8) left=innerWidth-w-8;
    menu.style.width=w+'px'; menu.style.top=Math.round(r.bottom+gap)+'px'; menu.style.left=Math.round(left)+'px';
  }
  function open(){menu.style.display='block';place();add();} function close(){menu.style.display='none';rem();}
  function add(){addEventListener('resize',place);addEventListener('scroll',place,{passive:true});document.addEventListener('click',doc);document.addEventListener('keydown',esc);}
  function rem(){removeEventListener('resize',place);removeEventListener('scroll',place);document.removeEventListener('click',doc);document.removeEventListener('keydown',esc);}
  function doc(e){if(e.target===trigger||trigger.contains(e.target)||menu.contains(e.target))return;close();}
  function esc(e){if(e.key==='Escape')close();}
  trigger.addEventListener('click',e=>{e.stopPropagation();menu.style.display==='block'?close():open();});
});
</script>
