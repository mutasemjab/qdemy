<section class="faq-section">
    <h2 data-aos="zoom-in-up"> <img
            src="{{ app()->getLocale() == 'ar'
                ? asset('assets_front/images/fre_question.png')
                : asset('assets_front/images/en/fre_question.png') }}"
            loading="lazy" width="600px;" height="auto" style="mix-blend-mode: darken; filter: contrast(1.1) saturate(1.05);">
    </h2>
    <div data-aos="zoom-in" class="faq-container">
        @foreach ($faqs as $index => $faq)
            <div class="faq-item {{ $index == 0 ? 'active' : '' }}">
                <button class="faq-question" onclick="toggleFaq(this)">
                    <span>{{ $faq->question }}</span>
                    <svg class="faq-icon" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M5 8L10 13L15 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="faq-answer">
                    <p>{{ $faq->answer }}</p>
                </div>
            </div>
        @endforeach
    </div>
    <div class="faq-section-link">
        <a href="{{ route('faq.index') }}">{{ __('front.See More') }} ←</a>
    </div>
</section>

<script>
function toggleFaq(button) {
    const item = button.parentElement;
    const isActive = item.classList.contains('active');

    // Close all other items
    document.querySelectorAll('.faq-item').forEach(i => {
        i.classList.remove('active');
    });

    // Toggle current item
    if (!isActive) {
        item.classList.add('active');
    }
}
</script>
