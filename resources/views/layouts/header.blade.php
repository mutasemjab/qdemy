<header class="header">
    <div class="container">
        <a href="{{ route('home') }}">
            <div class="logo" data-aos="fade">
                <img src="{{ asset('assets_front/images/qdemylogo.gif') }}" alt="Logo">
            </div>
        </a>

        <nav class="nav" id="navMenu">
            <ul>
                <li data-aos="fade">
                    <a class="{{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        {{ __('front.home') }}
                    </a>
                </li>

                <li data-aos="fade">
                    <a class="{{ request()->routeIs('about*') ? 'active' : '' }}" href="{{ route('about') }}">
                        {{ __('front.about_us') }}
                    </a>
                </li>

                <li data-aos="fade">
                    <a class="{{ request()->routeIs('contacts*') ? 'active' : '' }}" href="{{ route('contacts') }}">
                        {{ __('front.contacts') }}
                    </a>
                </li>

                <li data-aos="fade">
                    <a class="{{ request()->routeIs('courses*') ? 'active' : '' }}" href="{{ route('courses') }}">
                        {{ __('front.courses') }}
                    </a>
                </li>

                <li data-aos="fade">
                    <a class="{{ request()->routeIs('teachers*') ? 'active' : '' }}" href="{{ route('teachers') }}">
                        {{ __('front.teachers') }}
                    </a>
                </li>

                <li data-aos="fade">
                    <a class="{{ request()->routeIs('sale-point*') ? 'active' : '' }}"
                        href="{{ route('sale-point') }}">
                        {{ __('front.sale_points') }}
                    </a>
                </li>

                <li data-aos="fade">
                    <a class="community-link {{ request()->routeIs('community*') ? 'active' : '' }}"
                        href="{{ route('community') }}">
                        <span>{{ __('front.community') }}</span>
                        <img src="{{ asset('assets_front/images/icons/community.png') }}" alt="Community Icon"
                            class="icon">
                    </a>
                </li>

                <li data-aos="fade">
                    <a class="{{ request()->routeIs('exam.*') ? 'active' : '' }}" href="{{ route('exam.index') }}">
                        {{ __('front.Exams') }}
                    </a>
                </li>

                <li data-aos="fade">
                    <a class="{{ request()->routeIs('download*') ? 'active' : '' }}" href="{{ route('download') }}">
                        {{ __('front.Our Apps') }}
                    </a>
                </li>

                <li class="mobile-auth" data-aos="fade">
                    @if (!auth('user')?->user())
                        <a href="{{ route('user.register') }}" class="btn btn-primary mobile-btn">
                            {{ __('front.create_account') }}
                        </a>
                        <a href="{{ route('user.login') }}" class="btn btn-outline mobile-btn">
                            {{ __('front.login') }}
                        </a>
                    @else
                        <a href="{{ route('student.account') }}" class="btn btn-outline mobile-btn">
                            {{ __('front.my_account') }}
                        </a>
                        <form action="{{ route('panel.user.logout') }}" method="post" class="mobile-logout-form">
                            @csrf
                            <button type="submit" class="btn btn-primary mobile-btn"
                                style="border: 1px solid #0055D2;">
                                {{ __('front.logout') }}
                            </button>
                        </form>
                    @endif
                </li>
            </ul>
        </nav>

        <div data-aos="fade" class="actions">
            <a href="{{ route('checkout') }}" class="cart-icon">
                <i class="fas fa-shopping-cart"></i>
            </a>

            <div class="auth-cta">
                @if (!auth('user')?->user())
                    <a href="{{ route('user.register') }}" class="btn btn-primary">
                        {{ __('front.create_account') }}
                    </a>
                    <a href="{{ route('user.login') }}" class="btn btn-outline">
                        {{ __('front.login') }}
                    </a>
                @else
                    <a href="{{ route('student.account') }}" class="btn btn-outline">
                        {{ __('front.my_account') }}
                    </a>
                    <form action="{{ route('panel.user.logout') }}" method="post" class="mobile-logout-form">
                        @csrf
                        <button type="submit" class="btn btn-primary mobile-btn" style="border: 1px solid #0055D2;">
                            {{ __('front.logout') }}
                        </button>
                    </form>
                @endif
            </div>

            <div class="language-switch" id="languageSwitch">
                <button class="ls-btn" type="button" id="lsBtn">
                    <i class="fas fa-globe"></i>
                </button>
            </div>

            <button class="menu-toggle" id="menuToggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
</header>

<style>
    .ls-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border: 0;
        background: transparent;
        cursor: pointer;
    }

    .ls-menu {
        display: none;
        position: fixed;
        min-width: 180px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 6px;
        box-shadow: 0 12px 32px rgba(0, 0, 0, .22);
        z-index: 2147483647;
    }

    .ls-menu a {
        display: block;
        padding: 10px 12px;
        color: #111827;
        text-decoration: none;
        border-radius: 8px;
    }

    .ls-menu a:hover {
        background: #f3f4f6;
    }

    .ls-btn i {
        font-size: 18px !important;
        color: #111827;
    }

    .nav .mobile-auth {
        display: none;
    }

    .auth-cta {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    @media (max-width: 991px) {
        .auth-cta {
            display: none;
        }

        a.btn.btn-primary.mobile-btn {
            color: #fff;
        }

        .nav .mobile-auth {
            display: block;
            margin-top: 16px;
            padding-top: 12px;
            border-top: 1px solid rgba(0, 0, 0, 0.06);
        }

        .nav .mobile-auth .mobile-btn {
            display: block;
            width: 100%;
            text-align: center;
            margin-bottom: 8px;
        }

        .nav .mobile-auth .mobile-logout-form {
            margin: 0;
        }
    }

    @media (min-width: 992px) {
        .nav .mobile-auth {
            display: none;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const trigger = document.getElementById('lsBtn');
        if (trigger) {
            const menu = document.createElement('div');
            menu.className = 'ls-menu';
            menu.innerHTML = `
            <a href="{{ LaravelLocalization::getLocalizedURL('ar') }}">العربية</a>
            <a href="{{ LaravelLocalization::getLocalizedURL('en') }}">English</a>
        `;
            document.body.appendChild(menu);

            function place() {
                const r = trigger.getBoundingClientRect(),
                    gap = 8,
                    w = menu.offsetWidth || 200;
                let left = r.right - w;
                if (left < 8) left = 8;
                if (left + w > innerWidth - 8) left = innerWidth - w - 8;
                menu.style.width = w + 'px';
                menu.style.top = Math.round(r.bottom + gap) + 'px';
                menu.style.left = Math.round(left) + 'px';
            }

            function open() {
                menu.style.display = 'block';
                place();
                add();
            }

            function close() {
                menu.style.display = 'none';
                rem();
            }

            function add() {
                addEventListener('resize', place);
                addEventListener('scroll', place, {
                    passive: true
                });
                document.addEventListener('click', doc);
                document.addEventListener('keydown', esc);
            }

            function rem() {
                removeEventListener('resize', place);
                removeEventListener('scroll', place);
                document.removeEventListener('click', doc);
                document.removeEventListener('keydown', esc);
            }

            function doc(e) {
                if (e.target === trigger || trigger.contains(e.target) || menu.contains(e.target)) return;
                close();
            }

            function esc(e) {
                if (e.key === 'Escape') close();
            }
            trigger.addEventListener('click', e => {
                e.stopPropagation();
                menu.style.display === 'block' ? close() : open();
            });
        }

        const menuToggle = document.getElementById('menuToggle');
        const navMenu = document.getElementById('navMenu');

        if (menuToggle && navMenu) {
            menuToggle.addEventListener('click', function() {
                navMenu.classList.toggle('open');
            });
        }
    });
</script>
