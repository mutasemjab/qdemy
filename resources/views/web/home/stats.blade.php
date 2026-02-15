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
