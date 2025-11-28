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
                        style="background-image: url('{{ asset('assets_front/images/blob2.png') }}');">

                    </a>

                    <a href="{{ route('user.register') }}" class="blob-btn blob-btn-2 blob-3d" data-aos="fade-left"
                        data-aos-duration="1200" data-aos-delay="350"
                        style="background-image: url('{{ asset('assets_front/images/blob3.png') }}');">

                    </a>
                </div>

                <!-- العمود الشمال -->
                <div class="hero-column center-column">
                    <a href="{{ route('card-order') }}" class="blob-btn blob-btn-3  anim animate-pulse blob-3d"
                        data-aos="fade" data-aos-duration="1000" data-aos-delay="300"
                        style="background-image: url('{{ asset('assets_front/images/blob1.png') }}');">

                    </a>
                </div>
            </div>



            <!-- Left Side: Person Image + Background Shape -->
            <div class="hero-left">
                <div class="hero-person">
                    <img data-src="{{ asset('assets_front/images/home/person.png') }}" alt="Person" class="person-img"
                        data-aos="fade-up">
                </div>
            </div>
        </div>

        <!-- Bottom Cards -->
        <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300" class="hero-cards">
            <a href="{{ route('tawjihi-programm') }}" class="hero-card"
                style="background-image: url('{{ asset('assets_front/images/card1.png') }}');">
            </a>
            <a href="{{ route('grades_basic-programm') }}" class="hero-card"
                style="background-image: url('{{ asset('assets_front/images/card2.png') }}');">
            </a>
            <a href="{{ route('universities-programm') }}" class="hero-card"
                style="background-image: url('{{ asset('assets_front/images/card3.png') }}');">
            </a>
            <a href="{{ route('international-programms') }}" class="hero-card"
                style="background-image: url('{{ asset('assets_front/images/card4.png') }}');">
            </a>
        </div>

    </section>

    <section class="features">
        <h2 data-aos="zoom-in-up">{{ __('front.What Makes QDEMY Special') }}</h2>
        <div class="features__media" data-aos="zoom-in" data-aos-duration="1500" data-aos-delay="300">
            <img src="{{ asset('assets_front/images/specialist.png') }}" alt="" class="features__img"
                loading="lazy">
        </div>
    </section>


    <section class="services">
        <h2 data-aos="zoom-in-up">{{ __('front.QDEMY Services') }}</h2>
        <div class="services-box">
            <a href="{{ route('community') }}" data-aos="zoom-in" data-aos-delay="200"
                class="service-btn dark  anim animate-glow"
                style="background-image: url('{{ asset('assets_front/images/dark.png') }}');">{{ __('front.QDEMY Community') }}</a>
            <a href="{{ route('exam.index') }}" data-aos="zoom-in" data-aos-delay="400" class="service-btn light"
                style="background-image: url('{{ asset('assets_front/images/light.png') }}');">{{ __('front.Electronic Exams') }}</a>
            <a href="{{ route('packages-offers') }}" data-aos="zoom-in" data-aos-delay="600"
                class="service-btn dark anim animate-glow"
                style="background-image: url('{{ asset('assets_front/images/dark.png') }}');">{{ __('front.packages_offers') }}</a>
            <a href="{{ route('doseyat') }}" data-aos="zoom-in" data-aos-delay="600" class="service-btn light "
                style="background-image: url('{{ asset('assets_front/images/light.png') }}');">{{ __('front.Doseyat') }}</a>
            <a href="{{ route('ministerialQuestions.index') }}" data-aos="zoom-in" data-aos-delay="400"
                class="service-btn dark anim animate-glow"
                style="background-image: url('{{ asset('assets_front/images/dark.png') }}');">{{ __('front.Ministry Years Questions') }}</a>
            <a href="{{ route('bankQuestions.index') }}" data-aos="zoom-in" data-aos-delay="200" class="service-btn light "
                style="background-image: url('{{ asset('assets_front/images/light.png') }}');">{{ __('front.Question Bank') }}
                <small>({{ __('front.Papers and Summaries') }})</small></a>
        </div>
    </section>





    <section data-aos="zoom-in-up" class="x3c-instructors">
        <h2>{{ __('front.Teachers') }}</h2>
        <div class="x3c-viewport">
            <button class="x3c-arrow x3c-left">&#10094;</button>
            <div class="x3c-rail">
                @foreach ($teachers as $teacher)
                <a href="{{route('teacher',$teacher->id)}}">
                    <div class="x3c-cell">
                        <div class="x3c-fig">
                            <img data-src="{{ $teacher->photo ? asset('assets/admin/uploads/' . $teacher->photo) : asset('assets_front/images/teacher1.png') }}"
                                alt="{{ $teacher->name }}">
                        </div>
                    </div>
                    </a>
                @endforeach
            </div>
            <button class="x3c-arrow x3c-right">&#10095;</button>
        </div>
    </section>


    <section data-aos="zoom-in-up" class="social-media">
        <h2 data-aos="zoom-in-up">{{ __('front.Social Media') }}</h2>

        @php $videoIndex = 0; @endphp
        @for ($i = 0; $i < min(2, ceil($socialMediaVideos->count() / 2)); $i++)
            <div class="media-row">
                @if ($videoIndex < $socialMediaVideos->count())
                    <div class="media-video"
                        data-video="{{ asset('assets/admin/uploads/' . $socialMediaVideos[$videoIndex]->video) }}">
                        <video muted loop playsinline preload="metadata">
                            <source src="{{ asset('assets/admin/uploads/' . $socialMediaVideos[$videoIndex]->video) }}"
                                type="video/mp4">
                        </video>
                        <div class="overlay">
                            <i class="fas fa-play"></i>
                        </div>
                    </div>
                    @php $videoIndex++; @endphp
                @endif

                @if ($videoIndex < $socialMediaVideos->count())
                    <div class="media-video"
                        data-video="{{ asset('assets/admin/uploads/' . $socialMediaVideos[$videoIndex]->video) }}">
                        <video muted loop playsinline preload="metadata">
                            <source src="{{ asset('assets/admin/uploads/' . $socialMediaVideos[$videoIndex]->video) }}"
                                type="video/mp4">
                        </video>
                        <div class="overlay">
                            <i class="fas fa-play"></i>
                        </div>
                    </div>
                    @php $videoIndex++; @endphp
                @endif
            </div>
        @endfor

        <div class="video-popup">
            <div class="popup-content">
                <span class="close-btn">&times;</span>
                <video controls controlsList="nodownload">
                    <source src="" type="video/mp4">
                    {{ __('front.browser_not_support_video') }}
                </video>
            </div>
        </div>

        <div class="image-popup">
            <div class="popup-content">
                <span class="close-btn">&times;</span>
                <img data-src="" alt="">
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
        <h2 data-aos="zoom-in-up">{{ __('front.Most Frequently Asked Questions') }}</h2>
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
        <h2 data-aos="zoom-in-up" class="rvx-title">{{ __('front.Some Students Reviews') }}</h2>

        <div class="rvx-wrap">
            <!-- Blue side panel -->
            <div data-aos="zoom-in" class="rvx-stage">
                <div class="rvx-panel">
                    <h3 class="rvx-panel-title">{{ __('front.Our Students Reviews on Their Platform') }}</h3>
                    <img class="rvx-panel-logo" data-src="{{ asset('assets_front/images/logo-white.png') }}"
                        alt="Qdemy">
                    <p class="rvx-panel-sub"></p>
                    <a href="#" class="rvx-panel-link">{{ __('front.Read More') }} ←</a>
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

    <section class="blog-slider" dir="rtl">
        <h2 data-aos="zoom-in-up" class="blog-slider__title">{{ __('front.Blogs') }}</h2>

        <button class="blog-slider__arrow blog-slider__arrow--prev" aria-label="{{ __('front.Previous') }}" disabled>
            <span>&rsaquo;</span>
        </button>
        <button class="blog-slider__arrow blog-slider__arrow--next" aria-label="{{ __('front.Next') }}">
            <span>&lsaquo;</span>
        </button>

        <div data-aos="zoom-in" class="blog-slider__viewport">
            <div class="blog-slider__track">
                @foreach ($blogs as $blog)
                    <article class="blog-card">
                        <a href="#">
                            <div class="blog-card__image">
                                <img data-src="{{ $blog->photo ? asset('assets/admin/uploads/' . $blog->photo) : asset('assets_front/images/blog1.png') }}"
                                    alt="">
                            </div>
                            <h3 class="blog-card__title">
                                {{ app()->getLocale() == 'ar' ? $blog->title_ar : $blog->title_en }}
                            </h3>
                            <p class="blog-card__excerpt">
                                {{ app()->getLocale() == 'ar' ? Str::limit($blog->description_ar, 100) : Str::limit($blog->description_en, 100) }}
                            </p>
                        </a>
                    </article>
                @endforeach
            </div>
        </div>

        <div class="blog-slider__dots" role="tablist" aria-label="{{ __('front.Slider Indicator') }}"></div>
    </section>
@endsection
