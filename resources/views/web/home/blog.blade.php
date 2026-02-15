<section class="blog-slider">
    <h2 data-aos="zoom-in-up" class="blog-slider__title">
        <img src="{{ app()->getLocale() == 'ar'
            ? asset('assets_front/images/blogs.png')
            : asset('assets_front/images/en/blogs.png') }}"
            loading="lazy" width="600px;" height="auto" style="mix-blend-mode: darken; filter: contrast(1.1) saturate(1.05);">
    </h2>

    <div class="blog-slider__shell">
        <button class="blog-slider__arrow blog-slider__arrow--prev" aria-label="{{ __('front.Previous') }}">
            <span>&lsaquo;</span>
        </button>

        <div data-aos="zoom-in" class="blog-slider__viewport">
            <div class="blog-slider__track">
                @foreach ($blogs as $blog)
                    <article class="blog-card">
                        <a href="{{ route('frontBlog.show', $blog->id) }}">
                            <div class="blog-card__image">
                                <img src="{{ $blog->photo ? asset('assets/admin/uploads/' . $blog->photo) : asset('assets_front/images/blog1.png') }}"
                                    alt="{{ app()->getLocale() == 'ar' ? $blog->title_ar : $blog->title_en }}"
                                    loading="lazy">
                            </div>
                            <h3 class="blog-card__title">
                                {{ app()->getLocale() == 'ar' ? $blog->title_ar : $blog->title_en }}
                            </h3>
                            <p class="blog-card__excerpt">
                                {!! app()->getLocale() == 'ar' ? Str::limit($blog->description_ar, 100) : Str::limit($blog->description_en, 100) !!}
                            </p>
                        </a>
                    </article>
                @endforeach
            </div>
        </div>

        <button class="blog-slider__arrow blog-slider__arrow--next" aria-label="{{ __('front.Next') }}">
            <span>&rsaquo;</span>
        </button>
    </div>

    <div class="blog-slider__dots" role="tablist" aria-label="{{ __('front.Slider Indicator') }}"></div>
</section>
