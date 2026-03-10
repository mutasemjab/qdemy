<section class="hero" style="background: linear-gradient(to bottom, #f5f5f5 0%, #fafafa 100%);">

    <div class="hero-container">

        <!-- Right Side: Shapes with links -->
        <div class="hero-buttons-wrapper">

            <!-- Right column -->
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

            <!-- Left column -->
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

            <a href="{{ $settings->gpa_calculator_link ?? '#' }}" class="blob-gpa blob-3d" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="500">
                <span>{{ __('front.gpa_calculator') }}</span>
            </a>
        </div>
    </div>

</section>
