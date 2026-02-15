<section class="services-section">
    <img src="{{ app()->getLocale() == 'ar'
        ? asset('assets_front/images/our_service.png')
        : asset('assets_front/images/en/our_service.png') }}"
        loading="lazy" alt="Our Services" style="max-width: 600px; mix-blend-mode: darken; filter: contrast(1.1) saturate(1.05);">

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
