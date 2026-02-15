<section data-aos="zoom-in-up" class="fm3d-videos-block">
    <div class="fm3d-videos-inner">
        <h2 class="fm3d-videos-title" data-aos="zoom-in-up">
            <img src="{{ app()->getLocale() == 'ar'
                ? asset('assets_front/images/social_media.png')
                : asset('assets_front/images/en/social_media.png') }}"
                loading="lazy" width="600px;" height="auto" style="mix-blend-mode: darken; filter: contrast(1.1) saturate(1.05);">
        </h2>
        <div class="fm3d-videos-shell">
            <button class="fm3d-nav-arrow fm3d-nav-prev" type="button">
                <span class="fm3d-nav-chevron"></span>
            </button>

            <div class="fm3d-videos-strip">
                @foreach ($socialMediaVideos as $item)
                    <div class="fm3d-video-card" data-video="{{ $item->video }}">
                        <div class="fm3d-video-cover"></div>
                        <button class="fm3d-video-play" type="button">
                            <span class="fm3d-play-ring"></span>
                            <span class="fm3d-play-triangle"></span>
                        </button>
                    </div>
                @endforeach
            </div>

            <button class="fm3d-nav-arrow fm3d-nav-next" type="button">
                <span class="fm3d-nav-chevron"></span>
            </button>
        </div>

        <div class="fm3d-videos-dots"></div>
    </div>

    <div class="fm3d-video-modal" id="fm3dVideoModal">
        <div class="fm3d-video-modal-layer">
            <button class="fm3d-video-modal-close" type="button">&times;</button>
            <div class="fm3d-video-modal-layout">
                <button class="fm3d-video-modal-arrow fm3d-video-modal-prev" type="button">
                    <span class="fm3d-modal-chevron"></span>
                </button>
                <div class="fm3d-video-modal-frame">
                    <iframe class="fm3d-video-iframe" src="" allow="autoplay; encrypted-media"
                        allowfullscreen></iframe>
                </div>
                <button class="fm3d-video-modal-arrow fm3d-video-modal-next" type="button">
                    <span class="fm3d-modal-chevron"></span>
                </button>
            </div>
        </div>
    </div>
</section>
