@php
use App\Models\Setting;
use App\Models\Page;

// Get settings data
$footerSettings = Setting::getSettings();

// Get privacy policy and terms pages
$privacyPolicy = Page::where('type', 2)->first(); // TYPE_PRIVACY_POLICY = 2
$termsConditions = Page::where('type', 1)->first(); // TYPE_TERMS_CONDITIONS = 1
@endphp
<!--
<footer class="footer">
    <div class="footer-container">

        <div class="footer-logo-section">
            @if($footerSettings)
                <img src="{{ $footerSettings->logo_url }}" alt="{{ config('app.name') }} Logo" class="footer-logo">
            @else
                <img src="{{ asset('assets_front/images/logo-white.png') }}" alt="{{ config('app.name') }} Logo" class="footer-logo">
            @endif
            
            <p class="footer-desc">
                @if($footerSettings && $footerSettings->text_under_logo_in_footer)
                    {{ $footerSettings->text_under_logo_in_footer }}
                @else
                    Lorem ipsum dolor sit amet consectetur. Porttitor molestie sapien dictum quam semper a sed auctor turpis.
                    Quam iaculis fringilla eros erat. Purus dui aliquet eget blandit enim nunc accumsan quis.
                @endif
            </p>

        </div>

        <div class="footer-column">
            <div>
                <h4>{{ __('front.technical_support') }}</h4>
                <ul>
                    @if($privacyPolicy)
                        <li><a href="{{ route('page.privacy-policy') }}">{{ __('front.privacy_policy') }}</a></li>
                    @endif
                    
                    @if($termsConditions)
                        <li><a href="{{ route('page.terms-conditions') }}">{{ __('front.terms_conditions') }}</a></li>
                    @endif
                </ul>
            </div>

            <div>
                <h4>{{ __('front.quick_links') }}</h4>
                <ul>
                    <li><a href="{{ route('international-programms') }}">{{ __('front.international_program') }}</a></li>
                    <li><a href="{{ route('grades_basic-programm') }}">{{ __('front.basic_grades_program') }}</a></li>
                    <li><a href="{{ route('universities-programm') }}">{{ __('front.universities_program') }}</a></li>
                    <li><a href="{{ route('tawjihi-programm') }}">{{ __('front.tawjihi_program') }}</a></li>
                    <li><a href="{{ route('packages-offers') }}">{{ __('front.packages_offers') }}</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-column">
            <div class="footer-column-Contact">
                <h4>{{ __('front.contact_us') }}</h4>
                <ul>
                    @if($footerSettings && $footerSettings->address)
                        <li>{{ $footerSettings->address }}</li>
                    @else
                        <li>Jordan – Amman</li>
                    @endif
                    
                    @if($footerSettings && $footerSettings->email)
                        <li>{{ __('front.technical_support_email') }}: {{ $footerSettings->email }}</li>
                    @else
                        <li>{{ __('front.technical_support_email') }}: support@qdemy.com</li>
                    @endif
                    
                    <li>{{ __('front.careers_email') }}: jobs@qdemy.com</li>
                    
                    @if($footerSettings && $footerSettings->phone)
                        <li>{{ $footerSettings->phone }}</li>
                    @endif
                </ul>
            </div>

            <div>
                <h4>{{ __('front.follow_us_social_media') }}</h4>
                <ul>
                    <li><a href="#" target="_blank">{{ __('front.facebook') }}</a></li>
                    <li><a href="#" target="_blank">{{ __('front.instagram') }}</a></li>
                    <li><a href="#" target="_blank">{{ __('front.twitter') }}</a></li>
                    <li><a href="#" target="_blank">{{ __('front.youtube') }}</a></li>
                </ul>
            </div>
        </div>

      

    </div>

   
</footer>
 -->
 
 <footer class="footer" role="contentinfo">
  <div class="footer-top-accent" aria-hidden="true"></div>

  <div class="footer-container">
    {{-- العمود 1: الشعار + وصف + السوشيال --}}
    <section class="footer-brand" aria-label="About {{ config('app.name') }}">
      @if($footerSettings)
        <img src="{{ $footerSettings->logo_url }}" alt="{{ config('app.name') }} Logo" class="footer-logo">
      @else
        <img src="{{ asset('assets_front/images/logo-white.png') }}" alt="{{ config('app.name') }} Logo" class="footer-logo">
      @endif

      <p class="footer-desc">
        @if($footerSettings && $footerSettings->text_under_logo_in_footer)
          {{ $footerSettings->text_under_logo_in_footer }}
        @else
          Lorem ipsum dolor sit amet consectetur. Porttitor molestie sapien dictum quam semper a sed auctor turpis.
        @endif
      </p>

      {{-- أيقونات التواصل الاجتماعي --}}
      <div class="footer-social" aria-label="{{ __('front.follow_us_social_media') }}">
        @php
          $fb = $footerSettings->facebook ?? '#';
          $ig = $footerSettings->instagram ?? '#';
          $tw = $footerSettings->twitter ?? '#';
          $yt = $footerSettings->youtube ?? '#';
        @endphp

        <a href="{{ $fb }}" class="social-btn" aria-label="Facebook" target="_blank" rel="noopener">
          <i class="fab fa-facebook-f" aria-hidden="true"></i>
        </a>
        <a href="{{ $ig }}" class="social-btn" aria-label="Instagram" target="_blank" rel="noopener">
          <i class="fab fa-instagram" aria-hidden="true"></i>
        </a>
        <a href="{{ $tw }}" class="social-btn" aria-label="X (Twitter)" target="_blank" rel="noopener">
          <i class="fab fa-x-twitter" aria-hidden="true"></i>
        </a>
        <a href="{{ $yt }}" class="social-btn" aria-label="YouTube" target="_blank" rel="noopener">
          <i class="fab fa-youtube" aria-hidden="true"></i>
        </a>
      </div>
    </section>

    {{-- العمود 3: تواصل معنا --}}
    <section class="footer-contact" aria-label="{{ __('front.contact_us') }}">
      <h4 class="footer-title">{{ __('front.contact_us') }}</h4>
      <ul class="contact-list">
        <li>
          <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
          <span>
            @if($footerSettings && $footerSettings->address)
              {{ $footerSettings->address }}
            @else
              Jordan – Amman
            @endif
          </span>
        </li>

        <li>
          <i class="fas fa-envelope" aria-hidden="true"></i>
          <span>
            {{ __('front.technical_support_email') }}:
            <a href="mailto:{{ $footerSettings->email ?? 'support@qdemy.com' }}">
              {{ $footerSettings->email ?? 'support@qdemy.com' }}
            </a>
          </span>
        </li>

        <li>
          <i class="fas fa-briefcase" aria-hidden="true"></i>
          <span>
            {{ __('front.careers_email') }}:
            <a href="mailto:jobs@qdemy.com">jobs@qdemy.com</a>
          </span>
        </li>

        @if($footerSettings && $footerSettings->phone)
          <li>
            <i class="fas fa-phone" aria-hidden="true"></i>
            <a href="tel:{{ preg_replace('/\s+/', '', $footerSettings->phone) }}">{{ $footerSettings->phone }}</a>
          </li>
        @endif
      </ul>
      <br><br>
        <div class="footer-block">
        <h4 class="footer-title">{{ __('front.technical_support') }}</h4>
        <ul class="footer-list">
          @if($privacyPolicy)
            <li><a href="{{ route('page.privacy-policy') }}">{{ __('front.privacy_policy') }}</a></li>
          @endif

          @if($termsConditions)
            <li><a href="{{ route('page.terms-conditions') }}">{{ __('front.terms_conditions') }}</a></li>
          @endif
        </ul>
      </div>
      
    </section>
    
        {{-- العمود 2: دعم فني + روابط سريعة --}}
    <nav class="footer-links" aria-label="Footer links">

      <div class="footer-block">
        <h4 class="footer-title">{{ __('front.quick_links') }}</h4>
        <ul class="footer-list">
          <li><a href="{{ route('international-programms') }}">{{ __('front.international_program') }}</a></li>
          <li><a href="{{ route('grades_basic-programm') }}">{{ __('front.basic_grades_program') }}</a></li>
          <li><a href="{{ route('universities-programm') }}">{{ __('front.universities_program') }}</a></li>
          <li><a href="{{ route('tawjihi-programm') }}">{{ __('front.tawjihi_program') }}</a></li>
          <li><a href="{{ route('packages-offers') }}">{{ __('front.packages_offers') }}</a></li>
        </ul>
      </div>
    </nav>
    
  </div>

  {{-- شريط سفلي صغير للحقوق --}}
  <div class="footer-bottom">
    <p class="copy">
      © {{ date('Y') }} {{ config('app.name') }} — {{ __('front.all_rights_reserved') }}
    </p>
    <p class="made-by">
      {{ __('front.made_with_love') }} 
    </p>
  </div>
</footer>

 