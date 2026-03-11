<section class="rvx" dir="rtl">
    <h2 data-aos="zoom-in-up" class="rvx-title"> <img
            src="{{ app()->getLocale() == 'ar'
                ? asset('assets_front/images/student_opinion.png')
                : asset('assets_front/images/en/student_opinion.png') }}"
            loading="lazy" width="600px;" height="auto" style="mix-blend-mode: darken; filter: contrast(1.1) saturate(1.05);"></h2>

    <div class="rvx-wrap">
        <!-- Carousel Window -->
        <div class="rvx-window" data-aos="zoom-in">
            <div class="rvx-track">
                @foreach ($opinionStudents as $index => $opinion)
                    <div class="rvx-card">
                        <img class="rvx-card-img"
                            data-src="{{ $opinion->photo ? asset('assets/admin/uploads/' . $opinion->photo) : asset('assets_front/images/social1.jpg') }}"
                            alt="{{ $opinion->name }}">
                        <h3 class="rvx-card-name" style="font-size: clamp(18px, 6vw, 32px);">{{ $opinion->name }}</h3>
                        <p class="rvx-card-title" style="font-size: clamp(16px, 5vw, 30px);">{{ $opinion->title }}</p>
                        <div class="rvx-card-stars">
                            @foreach ($opinion->getStarRatingAttribute() as $star)
                                @if ($star === 'full')
                                    <span class="rvx-card-star">★</span>
                                @elseif ($star === 'half')
                                    <span class="rvx-card-star">⭐</span>
                                @else
                                    <span class="rvx-card-star empty">☆</span>
                                @endif
                            @endforeach
                        </div>
                        <p class="rvx-card-description" style="font-size: clamp(16px, 5vw, 30px);">{{ $opinion->description }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Right side panel -->
        <div class="rvx-panel">
            <h3 class="rvx-panel-title" style="font-size: clamp(36px, 10vw, 72px);">{{ __('front.Our Students Reviews on Their Platform') }}</h3>
            <img class="rvx-panel-logo" data-src="{{ asset('assets_front/images/logo-white.png') }}"
                alt="Qdemy">
        </div>
    </div>

    <!-- Controls -->
    <div class="rvx-controls">
        <button class="rvx-arrow rvx-prev" aria-label="{{ __('front.Previous') }}">&#9654;</button>
        <div class="rvx-dots"></div>
        <button class="rvx-arrow rvx-next" aria-label="{{ __('front.Next') }}">&#9664;</button>
    </div>
</section>
