<div class="teachers-title">
    <img src="{{ app()->getLocale() == 'ar'
        ? asset('assets_front/images/teacher.png')
        : asset('assets_front/images/en/teacher.png') }}"
        loading="lazy" alt="Teachers">
</div>

<section data-aos="zoom-in-up" class="x3c-instructors">
    <div class="x3c-viewport">
        <button class="x3c-arrow x3c-left">&#10094;</button>
        <div class="x3c-rail">
            @foreach ($teachers as $teacher)
                <a href="{{ route('teacher', $teacher->id) }}">
                    <div class="x3c-cell">
                        <div class="x3c-fig">
                            <img data-src="{{ $teacher->photo_url }}"
                                alt="{{ $teacher->name }}">
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        <button class="x3c-arrow x3c-right">&#10095;</button>
    </div>
</section>
