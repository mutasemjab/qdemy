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
            <a href="{{route('courses')}}" class="blob-btn blob-btn-1" style="background-image: url('{{ asset('images/blob2.png') }}');">
                <i class="fas fa-graduation-cap"></i>
                <span>{{ __('front.courses') }}</span>
            </a>

            <a href="{{route('user.register')}}" class="blob-btn blob-btn-2" style="background-image: url('{{ asset('images/blob3.png') }}');">
                <i class="fas fa-user-plus"></i>
                <span>{{ __('front.register_account') }}</span>
            </a>
        </div>

        <!-- العمود الشمال -->
        <div class="hero-column center-column">
            <a href="{{route('card-order')}}" class="blob-btn blob-btn-3" style="background-image: url('{{ asset('images/blob1.png') }}');">
                <i class="fas fa-id-card"></i>
                <span>{!! __('front.order_card') !!}</span>
            </a>
        </div>
        </div>



        <!-- Left Side: Person Image + Background Shape -->
        <div class="hero-left">
            <div class="hero-person">
                <img data-src="{{ asset('assets_front/images/home/person.png') }}" alt="Person" class="person-img">
                <img data-src="{{ asset('assets_front/images/home/blue-wave.png') }}" alt="Background Shape" class="bg-wave">
            </div>
        </div>
    </div>

    <!-- Bottom Cards -->
  <div class="hero-cards">
    <a href="{{ route('tawjihi-programm') }}" class="hero-card" style="background-image: url('{{ asset('images/card1.png') }}');">
        <p class="card-t">{{ __('front.tawjihi_secondary_program') }}</p>
    </a>
    <a href="{{ route('grades_basic-programm') }}" class="hero-card" style="background-image: url('{{ asset('images/card2.png') }}');">
        <p class="card-t">{{ __('front.basic_grades_program') }}</p>
    </a>
    <a href="{{ route('universities-programm') }}" class="hero-card" style="background-image: url('{{ asset('images/card3.png') }}');">
        <p class="card-t">{{ __('front.universities_colleges_program') }}</p>
    </a>
    <a href="{{ route('international-programms') }}" class="hero-card" style="background-image: url('{{ asset('images/card4.png') }}');">
        <p class="card-t">{{ __('front.international_program') }}</p>
    </a>
</div>

</section>

<section class="features">
    <h2>{{ __('front.What Makes QDEMY Special') }}</h2>

    <div class="features-wrapper">
        <div class="features-box">
            @foreach($specialQdemies as $special)
                <div class="feature-item">
                    {{ app()->getLocale() == 'ar' ? $special->title_ar : $special->title_en }}
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="services">
    <h2>{{ __('front.QDEMY Services') }}</h2>
    <div class="services-box">
        <a href="{{ route('community') }}" class="service-btn dark">{{ __('front.QDEMY Community') }}</a>
        <a href="{{ route('exam.index') }}" class="service-btn light">{{ __('front.Electronic Exams') }}</a>
        <a href="{{ route('packages-offers') }}" class="service-btn dark">{{ __('front.packages_offers') }}</a>
        <a href="{{ route('sale-point') }}" class="service-btn light">{{ __('front.Sale Points') }}</a>
        <a href="{{ route('ministerialQuestions.index') }}" class="service-btn dark">{{ __('front.Ministry Years Questions') }}</a>
        <a href="{{ route('bankQuestions.index') }}" class="service-btn light">{{ __('front.Question Bank') }} <small>({{ __('front.Papers and Summaries') }})</small></a>
    </div>
</section>

<section class="social-media">
    <h2>{{ __('front.Social Media') }}</h2>

    @php $videoIndex = 0; @endphp
    @for($i = 0; $i < min(2, ceil($socialMediaVideos->count() / 2)); $i++)
        <div class="media-row">
            @if($videoIndex < $socialMediaVideos->count())
                <div class="media-video" data-video="{{ $socialMediaVideos[$videoIndex]->video }}">
                    <img data-src="{{ asset('assets_front/images/videobg.jpg') }}" alt="">
                    <div class="overlay">
                        <i class="fas fa-play"></i>
                    </div>
                </div>
                @php $videoIndex++; @endphp
            @endif

            <div class="media-image">
                <img data-src="{{ asset('assets_front/images/social1.jpg') }}" alt="">
            </div>

            @if($videoIndex < $socialMediaVideos->count())
                <div class="media-video" data-video="{{ $socialMediaVideos[$videoIndex]->video }}">
                    <img data-src="{{ asset('assets_front/images/videobg.jpg') }}" alt="">
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
            <iframe data-src="" frameborder="0" allowfullscreen></iframe>
        </div>
    </div>

    <div class="image-popup">
        <div class="popup-content">
            <span class="close-btn">&times;</span>
            <img data-src="" alt="">
        </div>
    </div>
</section>

<section class="teachers-carousel">
    <h2>{{ __('front.Teachers') }}</h2>
    <div class="carousel-container">
        <button class="carousel-btn prev">&#10094;</button>
        <div class="carousel-track">
            @foreach($teachers as $teacher)
                <div class="carousel-slide">
                    <img data-src="{{ $teacher->photo ? asset('assets/admin/uploads/' . $teacher->photo) : asset('assets_front/images/teacher1.png') }}" alt="{{ $teacher->name }}">

                </div>
            @endforeach
        </div>
        <button class="carousel-btn next">&#10095;</button>
    </div>
</section>

<section class="stats-section">
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
    <h2>{{ __('front.Most Frequently Asked Questions') }}</h2>
    <div class="faq-section-link">
        <a href="#">{{ __('front.See More') }} ←</a>
    </div>
    <div class="faq-container">
        @foreach($faqs as $index => $faq)
            <div class="faq-card {{ $index == 1 ? 'top-arrow faq-card-custom' : '' }}">
                <div class="icon">
                    <img data-src="../assets_front/images/ban-icon.png" alt="">
                </div>
                <h3>{{ $faq->question }}</h3>
                <p>{{ $faq->answer }}</p>
            </div>
        @endforeach
    </div>
</section>

<section class="rvx" dir="rtl">
    <h2 class="rvx-title">{{ __('front.Some Students Reviews') }}</h2>

    <div class="rvx-wrap">
        <!-- Blue side panel -->
        <div class="rvx-stage">
            <div class="rvx-panel">
                <h3 class="rvx-panel-title">{{ __('front.Our Students Reviews on Their Platform') }}</h3>
                <img class="rvx-panel-logo" data-src="{{ asset('assets_front/images/logo-white.png') }}" alt="Qdemy">
                <p class="rvx-panel-sub"></p>
                <a href="#" class="rvx-panel-link">{{ __('front.Read More') }} ←</a>
            </div>

            <!-- Carousel -->
            <div class="rvx-window">
                <div class="rvx-track">
                    @foreach($opinionStudents as $index => $opinion)
                        <article class="rvx-card {{ $index % 2 == 0 ? 'rvx-card--dark' : 'rvx-card--blue' }}">
                            <img class="rvx-card-img" data-src="{{ $opinion->photo ? asset('assets/admin/uploads/' . $opinion->photo) : asset('assets_front/images/social1.jpg') }}" alt="">
                            <div class="rvx-card-body">
                                <h4 class="rvx-card-title">{{ $opinion->title }}</h4>
                                <p class="rvx-card-text">{{ $opinion->description }}</p>
                                <div class="rvx-card-meta">
                                    <span class="rvx-card-name">{{ $opinion->name }}</span>
                                    <span class="rvx-card-stars" aria-label="{{ $opinion->number_of_star }} من 5">
                                        @for($i = 1; $i <= 5; $i++)
                                            {{ $i <= $opinion->number_of_star ? '★' : '☆' }}
                                        @endfor
                                    </span>
                                </div>
                            </div>
                        </article>
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
    <h2 class="blog-slider__title">{{ __('front.Blogs') }}</h2>

    <button class="blog-slider__arrow blog-slider__arrow--prev" aria-label="{{ __('front.Previous') }}" disabled>
        <span>&rsaquo;</span>
    </button>
    <button class="blog-slider__arrow blog-slider__arrow--next" aria-label="{{ __('front.Next') }}">
        <span>&lsaquo;</span>
    </button>

    <div class="blog-slider__viewport">
        <div class="blog-slider__track">
            @foreach($blogs as $blog)
                <article class="blog-card">
                    <a href="#">
                        <div class="blog-card__image">
                            <img data-src="{{ $blog->photo ? asset('assets/admin/uploads/' . $blog->photo) : asset('assets_front/images/blog1.png') }}" alt="">
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
