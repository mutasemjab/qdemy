@extends('layouts.app')

@section('title', 'الرئيسية')

@section('content')
    @include('web.alert-message')
    <section class="hero">

        <div class="hero-container">

            <!-- Right Side: Shapes with links -->
            <div class="hero-buttons-wrapper">

                <!-- العمود اليمين -->
                <div class="hero-column right-column">
                    <a href="{{ route('courses') }}" class="blob-btn blob-btn-1 blob-3d" data-aos="fade-left"
                        data-aos-duration="1000" data-aos-delay="300"
                        style="background-image: url('{{ app()->getLocale() == 'ar'
                            ? asset('assets_front/images/blob2.png')
                            : asset('assets_front/images/en/blob2.png') }}');">

                    </a>

                    @if (auth()->user())
                     <a href="" class="blob-btn blob-btn-2 blob-3d" data-aos="fade-left"
                            data-aos-duration="1200" data-aos-delay="350">
                        </a>
                    @else
                        <a href="{{ route('user.register') }}" class="blob-btn blob-btn-2 blob-3d" data-aos="fade-left"
                            data-aos-duration="1200" data-aos-delay="350"
                            style="background-image: url('{{ app()->getLocale() == 'ar'
                                ? asset('assets_front/images/blob3.png')
                                : asset('assets_front/images/en/blob3.png') }}');">

                        </a>
                    @endif

                </div>

                <!-- العمود الشمال -->
                <div class="hero-column center-column">
                    <a href="{{ route('card-order') }}" class="blob-btn blob-btn-3  anim animate-pulse blob-3d"
                        data-aos="fade" data-aos-duration="1000" data-aos-delay="300"
                        style="background-image: url('{{ app()->getLocale() == 'ar'
                            ? asset('assets_front/images/blob1.png')
                            : asset('assets_front/images/en/blob1.png') }}');">

                    </a>
                </div>
            </div>



            <!-- Left Side: Person Image + Background Shape -->
            <div class="hero-left">
                <div class="hero-person">
                    <img data-src="{{ app()->getLocale() == 'ar'
                        ? asset('assets_front/images/home/person.png')
                        : asset('assets_front/images/en/person.png') }}"
                        alt="Person" class="person-img" data-aos="fade-up">
                </div>
            </div>
        </div>

    </section>

    <!-- Bottom Cards -->
    <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300" class="hero-cards">
        <a href="{{ route('tawjihi-programm') }}" class="hero-card"
            style="background-image: url('{{ app()->getLocale() == 'ar'
                ? asset('assets_front/images/card1.png')
                : asset('assets_front/images/en/card1.png') }}'); background-size: contain; background-repeat: no-repeat; background-position: center;">
        </a>
        <a href="{{ route('grades_basic-programm') }}" class="hero-card"
            style="background-image: url('{{ app()->getLocale() == 'ar'
                ? asset('assets_front/images/card2.png')
                : asset('assets_front/images/en/card2.png') }}'); background-size: contain; background-repeat: no-repeat; background-position: center;">
        </a>
        <a href="{{ route('universities-programm') }}" class="hero-card"
            style="background-image: url('{{ app()->getLocale() == 'ar'
                ? asset('assets_front/images/card3.png')
                : asset('assets_front/images/en/card3.png') }}'); background-size: contain; background-repeat: no-repeat; background-position: center;">
        </a>
        <a href="{{ route('international-programms') }}" class="hero-card"
            style="background-image: url('{{ app()->getLocale() == 'ar'
                ? asset('assets_front/images/card4.png')
                : asset('assets_front/images/en/card4.png') }}'); background-size: contain; background-repeat: no-repeat; background-position: center;">
        </a>
        <a href="{{ route('training-courses') }}" class="hero-card"
            style="background-image: url('{{ app()->getLocale() == 'ar'
                ? asset('assets_front/images/card5.png')
                : asset('assets_front/images/en/card5.png') }}'); background-size: contain; background-repeat: no-repeat; background-position: center;">
        </a>
    </div>

    <section class="features">

    </section>


    <style>
        .services-section {
            text-align: center;
            margin: 60px auto;
            padding: 0 20px;
        }

        .services-section img {
            max-width: 400px;
            width: 100%;
            height: auto;
            margin-bottom: 32px;
        }

        .services-grid {
            background-color: #E7E7E780;
            border-radius: 20px;
            padding: 50px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            max-width: 1300px;
            margin: 0 auto;
        }

        .service-link {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
            color: #fff;
            font-weight: bold;
            font-size: 28px;
            text-decoration: none;
            text-align: center;
            transition: transform 0.3s ease;
            height: 140px;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            border-radius: 20px;
        }

        .service-link.light {
            color: #3c98ff;
        }

        .service-link:hover {
            transform: translateY(-4px);
        }

        .service-link small {
            display: block;
            font-weight: normal;
            font-size: 24px;
            margin-top: 4px;
        }

        /* Tablet - 992px and below */
        @media (max-width: 992px) {
            .services-grid {
                grid-template-columns: repeat(2, 1fr);
                padding: 30px 20px;
            }

            .service-link {
                padding: 40px 20px;
                font-size: 18px;
            }
        }

        /* Mobile - 576px and below */
        @media (max-width: 576px) {
            .services-section {
                padding: 0 10px;
            }

            .services-grid {
                grid-template-columns: 1fr;
                padding: 20px 10px;
                gap: 15px;
            }

            .service-link {
                padding: 30px 15px;
                font-size: 24px;
                height: auto;
                min-height: 140px;
                flex-direction: column;
                line-height: 1.4;
            }

            .service-link small {
                font-size: 20px;
                margin-top: 5px;
            }
        }
    </style>

    <section class="services-section">
        <img src="{{ app()->getLocale() == 'ar'
            ? asset('assets_front/images/our_service.png')
            : asset('assets_front/images/en/our_service.png') }}"
            loading="lazy" alt="Our Services" style="max-width: 600px;">

        <div class="services-grid">
            <a href="{{ route('community') }}" class="service-link anim animate-glow"
                style="background-image: url('{{ asset('assets_front/images/dark.png') }}');">{{ __('front.QDEMY Community') }}</a>
            <a href="{{ route('exam.index') }}" class="service-link light"
                style="background-image: url('{{ asset('assets_front/images/light.png') }}');">{{ __('front.Electronic Exams') }}</a>
            <a href="{{ route('packages-offers') }}" class="service-link anim animate-glow"
                style="background-image: url('{{ asset('assets_front/images/dark.png') }}');">{{ __('front.packages_offers') }}</a>
            <a href="{{ route('doseyat') }}" class="service-link light"
                style="background-image: url('{{ asset('assets_front/images/light.png') }}');">{{ __('front.Doseyat') }}</a>
            <a href="{{ route('ministerialQuestions.index') }}" class="service-link anim animate-glow"
                style="background-image: url('{{ asset('assets_front/images/dark.png') }}');">{{ __('front.Ministry Years Questions') }}</a>
            <a href="{{ route('bankQuestions.index') }}" class="service-link light"
                style="background-image: url('{{ asset('assets_front/images/light.png') }}');">{{ __('front.Question Bank') }}
                <small>({{ __('front.Papers and Summaries') }})</small></a>
        </div>
    </section>





    <section data-aos="zoom-in-up" class="x3c-instructors">
        <h2> <img
                src="{{ app()->getLocale() == 'ar'
                    ? asset('assets_front/images/teacher.png')
                    : asset('assets_front/images/en/teacher.png') }}"
                loading="lazy" width="600px;" height="auto">
        </h2>
        <div class="x3c-viewport">
            <button class="x3c-arrow x3c-left">&#10094;</button>
            <div class="x3c-rail">
                @foreach ($teachers as $teacher)
                    <a href="{{ route('teacher', $teacher->id) }}">
                        <div class="x3c-cell">
                            <div class="x3c-fig">
                                <img data-src="{{ $teacher->photo_url }}"
                                    alt="{{ $teacher->name }}">
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <button class="x3c-arrow x3c-right">&#10095;</button>
        </div>
    </section>


    <section data-aos="zoom-in-up" class="fm3d-videos-block">
        <div class="fm3d-videos-inner">
            <h2 class="fm3d-videos-title" data-aos="zoom-in-up">
                <img src="{{ app()->getLocale() == 'ar'
                    ? asset('assets_front/images/social_media.png')
                    : asset('assets_front/images/en/social_media.png') }}"
                    loading="lazy" width="600px;" height="auto">
            </h2>
            <div class="fm3d-videos-shell">
                <button class="fm3d-nav-arrow fm3d-nav-prev" type="button">
                    <span class="fm3d-nav-chevron"></span>
                </button>

                <div class="fm3d-videos-strip">
                    @foreach ($socialMediaVideos as $item)
                        <div class="fm3d-video-card" data-video="{{ $item->video }}">
                            <div class="fm3d-video-cover"></div>
                            <button class="fm3d-video-play" type="button">
                                <span class="fm3d-play-ring"></span>
                                <span class="fm3d-play-triangle"></span>
                            </button>
                        </div>
                    @endforeach
                </div>

                <button class="fm3d-nav-arrow fm3d-nav-next" type="button">
                    <span class="fm3d-nav-chevron"></span>
                </button>
            </div>

            <div class="fm3d-videos-dots"></div>
        </div>

        <div class="fm3d-video-modal" id="fm3dVideoModal">
            <div class="fm3d-video-modal-layer">
                <button class="fm3d-video-modal-close" type="button">&times;</button>
                <div class="fm3d-video-modal-layout">
                    <button class="fm3d-video-modal-arrow fm3d-video-modal-prev" type="button">
                        <span class="fm3d-modal-chevron"></span>
                    </button>
                    <div class="fm3d-video-modal-frame">
                        <iframe class="fm3d-video-iframe" src="" allow="autoplay; encrypted-media"
                            allowfullscreen></iframe>
                    </div>
                    <button class="fm3d-video-modal-arrow fm3d-video-modal-next" type="button">
                        <span class="fm3d-modal-chevron"></span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section data-aos="zoom-in-up" class="stats-section">
        <div class="stats-overlay">
            <div class="stat-item">
                <span class="stat-number">{{ $settings->number_of_course ?? '+20 Thousand' }}</span>
                <p>{{ __('front.Course') }}</p>
            </div>
            <div class="divider"></div>
            <div class="stat-item">
                <span class="stat-number">{{ $settings->number_of_teacher ?? '+1 Thousand' }}</span>
                <p>{{ __('front.Teacher') }}</p>
            </div>
            <div class="divider"></div>
            <div class="stat-item">
                <span class="stat-number">{{ $settings->number_of_viewing_hour ?? '+2 Million' }}</span>
                <p>{{ __('front.Viewing hour') }}</p>
            </div>
            <div class="divider"></div>
            <div class="stat-item">
                <span class="stat-number">{{ $settings->number_of_students ?? '+3 Million' }}</span>
                <p>{{ __('front.Student') }}</p>
            </div>
        </div>
    </section>

    <section class="faq-section">
        <h2 data-aos="zoom-in-up"> <img
                src="{{ app()->getLocale() == 'ar'
                    ? asset('assets_front/images/fre_question.png')
                    : asset('assets_front/images/en/fre_question.png') }}"
                loading="lazy" width="600px;" height="auto">
        </h2>
        <div class="faq-section-link">
            <a href="{{ route('faq.index') }}">{{ __('front.See More') }} ←</a>
        </div>
        <div data-aos="zoom-in" class="faq-container">
            @foreach ($faqs as $index => $faq)
                <div class="faq-card {{ $index == 1 ? 'top-arrow faq-card-custom' : '' }}">
                    <div class="icon">
                        <img data-src="{{ asset('assets_front/images/ban-icon.png') }}" alt="">
                    </div>
                    <div>
                        <h3>{{ $faq->question }}</h3>
                        <p>{{ $faq->answer }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="rvx" dir="rtl">
        <h2 data-aos="zoom-in-up" class="rvx-title"> <img
                src="{{ app()->getLocale() == 'ar'
                    ? asset('assets_front/images/student_opinion.png')
                    : asset('assets_front/images/en/student_opinion.png') }}"
                loading="lazy" width="600px;" height="auto"></h2>

        <div class="rvx-wrap">
            <!-- Blue side panel -->
            <div data-aos="zoom-in" class="rvx-stage">
                <div class="rvx-panel">
                    <h3 class="rvx-panel-title">{{ __('front.Our Students Reviews on Their Platform') }}</h3>
                    <img class="rvx-panel-logo" data-src="{{ asset('assets_front/images/logo-white.png') }}"
                        alt="Qdemy">
                    <p class="rvx-panel-sub"></p>
                </div>

                <!-- Carousel -->
                <div class="rvx-window">
                    <div class="rvx-track">
                        @foreach ($opinionStudents as $index => $opinion)
                            <img class="rvx-card-img"
                                data-src="{{ $opinion->photo ? asset('assets/admin/uploads/' . $opinion->photo) : asset('assets_front/images/social1.jpg') }}"
                                alt="">
                        @endforeach
                    </div>
                </div>

                <!-- Controls -->
                <div class="rvx-controls">
                    <button class="rvx-arrow rvx-prev" aria-label="{{ __('front.Previous') }}">◀</button>
                    <div class="rvx-dots"></div>
                    <button class="rvx-arrow rvx-next" aria-label="{{ __('front.Next') }}">▶</button>
                </div>
            </div>
        </div>
    </section>

    <section class="blog-slider">
        <h2 data-aos="zoom-in-up" class="blog-slider__title">
            <img src="{{ app()->getLocale() == 'ar'
                ? asset('assets_front/images/blogs.png')
                : asset('assets_front/images/en/blogs.png') }}"
                loading="lazy" width="600px;" height="auto">
        </h2>

        <div class="blog-slider__shell">
            <button class="blog-slider__arrow blog-slider__arrow--prev" aria-label="{{ __('front.Previous') }}">
                <span>&lsaquo;</span>
            </button>

            <div data-aos="zoom-in" class="blog-slider__viewport">
                <div class="blog-slider__track">
                    @foreach ($blogs as $blog)
                        <article class="blog-card">
                            <a href="{{ route('frontBlog.show', $blog->id) }}">
                                <div class="blog-card__image">
                                    <img src="{{ $blog->photo ? asset('assets/admin/uploads/' . $blog->photo) : asset('assets_front/images/blog1.png') }}"
                                        alt="{{ app()->getLocale() == 'ar' ? $blog->title_ar : $blog->title_en }}"
                                        loading="lazy">
                                </div>
                                <h3 class="blog-card__title">
                                    {{ app()->getLocale() == 'ar' ? $blog->title_ar : $blog->title_en }}
                                </h3>
                                <p class="blog-card__excerpt">
                                    {!! app()->getLocale() == 'ar' ? Str::limit($blog->description_ar, 100) : Str::limit($blog->description_en, 100) !!}
                                </p>
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>

            <button class="blog-slider__arrow blog-slider__arrow--next" aria-label="{{ __('front.Next') }}">
                <span>&rsaquo;</span>
            </button>
        </div>

        <div class="blog-slider__dots" role="tablist" aria-label="{{ __('front.Slider Indicator') }}"></div>
    </section>

@endsection
