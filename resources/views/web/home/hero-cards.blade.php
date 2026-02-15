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
