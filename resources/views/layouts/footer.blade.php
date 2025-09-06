@php
use App\Models\Setting;
use App\Models\Page;

// Get settings data
$footerSettings = Setting::getSettings();

// Get privacy policy and terms pages
$privacyPolicy = Page::where('type', 2)->first(); // TYPE_PRIVACY_POLICY = 2
$termsConditions = Page::where('type', 1)->first(); // TYPE_TERMS_CONDITIONS = 1
@endphp

<footer class="footer">
    <div class="footer-container">

        <!-- Logo + Description -->
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

        <!-- Column 1: Technical Support + Quick Links -->
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

        <!-- Column 2: Contact Us + Follow Us -->
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