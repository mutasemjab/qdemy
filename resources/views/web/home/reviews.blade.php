<section class="rvx" dir="rtl">
    <h2 data-aos="zoom-in-up" class="rvx-title"> <img
            src="{{ app()->getLocale() == 'ar'
                ? asset('assets_front/images/student_opinion.png')
                : asset('assets_front/images/en/student_opinion.png') }}"
            loading="lazy" width="600px;" height="auto" style="mix-blend-mode: darken; filter: contrast(1.1) saturate(1.05);"></h2>

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
                <button class="rvx-arrow rvx-prev" aria-label="{{ __('front.Previous') }}">&#9664;</button>
                <div class="rvx-dots"></div>
                <button class="rvx-arrow rvx-next" aria-label="{{ __('front.Next') }}">&#9654;</button>
            </div>
        </div>
    </div>
</section>
