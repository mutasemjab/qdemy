<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    <!-- Preload ALL OTF fonts correctly -->
    <link rel="preload" href="{{ asset('assets_front/fonts/Somar-Medium.otf') }}" as="font" type="font/otf"
        crossorigin>
    <link rel="preload" href="{{ asset('assets_front/fonts/Somar-Bold.otf') }}" as="font" type="font/otf"
        crossorigin>
    <link rel="preload" href="{{ asset('assets_front/fonts/Somar-ExtraBold.otf') }}" as="font" type="font/otf"
        crossorigin>

    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Main CSS -->
    <link rel="stylesheet"
        href="{{ asset('assets_front/css/style.css') . '?v=' . filemtime(base_path('assets_front/css/style.css')) }}">

    @if (app()->getLocale() === 'en')
        <link rel="stylesheet"
            href="{{ asset('assets_front/css/en.css') . '?v=' . filemtime(base_path('assets_front/css/en.css')) }}">
    @endif

    @stack('styles')
    @yield('styles')

    <style>
        /* CRITICAL: Disable font synthesis */
        * {
            font-synthesis: none;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Force exact font matching */
        @font-face {
            font-family: 'Somar';
            src: url('/assets_front/fonts/Somar-Medium.otf') format('opentype');
            font-weight: 400;
            font-style: normal;
            font-display: block;
            font-synthesis: none;
            /* Don't fake bold */
        }

        @font-face {
            font-family: 'Somar';
            src: url('/assets_front/fonts/Somar-Bold.otf') format('opentype');
            font-weight: 700;
            font-style: normal;
            font-display: block;
            font-synthesis: none;
            /* Use actual bold file */
        }

        @font-face {
            font-family: 'Somar';
            src: url('/assets_front/fonts/Somar-ExtraBold.otf') format('opentype');
            font-weight: 900;
            font-style: normal;
            font-display: block;
            font-synthesis: none;
            /* Use actual black file */
        }

        html {
            font-size: 16px !important;
        }

        body {
            font-size: 16px !important;
            min-height: 100vh;
            margin: 0;
            direction: rtl;
            line-height: 1.6;
        }

        /* Force font application immediately */
        html,
        body {
            font-family: 'Somar', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            font-weight: 400;
            /* Default to regular weight */
        }

        /* Apply to all elements with proper weight inheritance */
        body,
        button:not(.fa):not(.fas):not(.far):not(.fab):not(.fal),
        input,
        textarea,
        select,
        option,
        p,
        span:not(.fa):not(.fas):not(.far):not(.fab):not(.fal),
        a,
        li,
        label,
        div,
        .form-control,
        .form-select,
        .examx-pill,
        .examx-dropdown select,
        .examx-dropdown option {
            font-family: 'Somar', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
        }

        /* Ensure headings use bold weights */
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Somar', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important;
            font-weight: 700;
            /* Use Bold variant */
        }

        /* Explicitly set bold elements */
        strong,
        b,
        .bold {
            font-weight: 700 !important;
        }

        /* Protect Font Awesome */
        i,
        .fa,
        .fas,
        .far,
        .fab,
        .fal,
        [class^="fa-"],
        [class*=" fa-"] {
            font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands" !important;
            font-weight: 900 !important;
        }

        .far {
            font-weight: 400 !important;
        }

        /* Prevent layout shift during font load */
        body {
            visibility: visible;
            opacity: 1;
        }

        /* Responsive font sizes */
        @media (min-width: 1600px) {
            html {
                font-size: 18px !important;
            }

            body {
                font-size: 18px !important;
            }
        }
    </style>

    <!-- Font Loading Detection Script -->
    <script>
        (function() {
            if ('fonts' in document) {
                Promise.all([
                    document.fonts.load('400 16px Somar'),
                    document.fonts.load('700 16px Somar'),
                    document.fonts.load('900 16px Somar')
                ]).then(function() {
                    document.documentElement.classList.add('fonts-loaded');
                }).catch(function() {
                    document.documentElement.classList.add('fonts-loaded');
                });

                setTimeout(function() {
                    document.documentElement.classList.add('fonts-loaded');
                }, 3000);
            } else {
                document.documentElement.classList.add('fonts-loaded');
            }
        })();
    </script>
</head>

<body>

    @if (!isset($hideHeader) || !$hideHeader)
        @include('layouts.header')
    @endif

    <main>
        <!-- @include('admin.includes.alerts.success')
        @include('admin.includes.alerts.error') -->
        @yield('content')
    </main>

    @if (!isset($hideFooter) || !$hideFooter)
        @include('layouts.footer')
    @endif

    <!-- <script src="{{ asset('assets_front/js/app.js') }}"></script> -->
    <script>
        var menuToggle = document.getElementById('menuToggle');
        var navMenu = document.getElementById('navMenu');
        if (menuToggle && navMenu) {
            menuToggle.addEventListener('click', function() {
                navMenu.classList.toggle('active');
            });
        }
    </script>

    <script>
        // lazy loading images in all page
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('img').forEach(tab => {
                const srcAttr = tab.getAttribute('data-src');
                if (srcAttr) tab.setAttribute('src', srcAttr);
            });
        });

        // فتح الفيديو
        document.querySelectorAll('.media-video').forEach(video => {
            video.addEventListener('click', () => {
                const popup = document.querySelector('.video-popup');
                if (popup) {
                    const iframe = popup.querySelector('iframe');
                    if (iframe) {
                        iframe.src = video.getAttribute('data-video') + "?autoplay=1";
                        popup.classList.add('active');
                    }
                }
            });
        });

        // غلق الفيديو
        var videoPopupCloseBtn = document.querySelector('.video-popup .close-btn');
        if (videoPopupCloseBtn) {
            videoPopupCloseBtn.addEventListener('click', () => {
                const popup = document.querySelector('.video-popup');
                if (popup) {
                    const iframe = popup.querySelector('iframe');
                    if (iframe) iframe.src = '';
                    popup.classList.remove('active');
                }
            });
        }

        var videoPopup = document.querySelector('.video-popup');
        if (videoPopup) {
            videoPopup.addEventListener('click', (e) => {
                if (e.target.classList.contains('video-popup')) {
                    const iframe = videoPopup.querySelector('iframe');
                    if (iframe) iframe.src = '';
                    videoPopup.classList.remove('active');
                }
            });
        }

        // فتح الصورة
        document.querySelectorAll('.media-image img').forEach(img => {
            img.addEventListener('click', () => {
                const popup = document.querySelector('.image-popup');
                if (popup) {
                    const popupImg = popup.querySelector('img');
                    if (popupImg) {
                        popupImg.src = img.src;
                        popup.classList.add('active');
                    }
                }
            });
        });

        // غلق الصورة
        var imagePopupCloseBtn = document.querySelector('.image-popup .close-btn');
        if (imagePopupCloseBtn) {
            imagePopupCloseBtn.addEventListener('click', () => {
                const popup = document.querySelector('.image-popup');
                if (popup) popup.classList.remove('active');
            });
        }

        var imagePopup = document.querySelector('.image-popup');
        if (imagePopup) {
            imagePopup.addEventListener('click', (e) => {
                if (e.target.classList.contains('image-popup')) {
                    imagePopup.classList.remove('active');
                }
            });
        }
    </script>

    <script>
        const track = document.querySelector('.carousel-track');
        if (track) {
            let slides = Array.from(document.querySelectorAll('.carousel-slide'));
            const prevBtn = document.querySelector('.carousel-btn.prev');
            const nextBtn = document.querySelector('.carousel-btn.next');
            let autoSlide;

            function setActive() {
                slides.forEach(slide => slide.classList.remove('active'));
                if (slides[1]) slides[1].classList.add('active');
            }

            function moveNext() {
                track.style.transition = 'transform 0.5s ease';
                track.style.transform = 'translateX(-33.33%)';

                track.addEventListener('transitionend', () => {
                    track.style.transition = 'none';
                    track.appendChild(slides[0]);
                    slides = Array.from(document.querySelectorAll('.carousel-slide'));
                    track.style.transform = 'translateX(0)';
                    requestAnimationFrame(() => {
                        setActive();
                    });
                }, {
                    once: true
                });
            }

            function movePrev() {
                track.style.transition = 'none';
                track.insertBefore(slides[slides.length - 1], slides[0]);
                slides = Array.from(document.querySelectorAll('.carousel-slide'));
                track.style.transform = 'translateX(-33.33%)';

                requestAnimationFrame(() => {
                    track.style.transition = 'transform 0.5s ease';
                    track.style.transform = 'translateX(0)';
                    setActive();
                });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    moveNext();
                    resetAutoSlide();
                });
            }

            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    movePrev();
                    resetAutoSlide();
                });
            }

            function startAutoSlide() {
                autoSlide = setInterval(moveNext, 5000);
            }

            function resetAutoSlide() {
                clearInterval(autoSlide);
                startAutoSlide();
            }

            setActive();
            startAutoSlide();
        }
    </script>

    <script>
        // Accordion
        document.querySelectorAll('.accordion-header').forEach(header => {
            header.addEventListener('click', () => {
                const item = header.parentElement;
                item.classList.toggle('active');
            });
        });

        // Video Popup
        const popup = document.querySelector('.video-popup');
        if (popup) {
            const iframe = popup.querySelector('iframe');
            const closeBtn = popup.querySelector('.close-popup');

            document.querySelectorAll('.lesson-video').forEach(video => {
                video.addEventListener('click', () => {
                    if (iframe) iframe.src = video.dataset.video + "?autoplay=1";
                    popup.classList.add('active');
                });
            });

            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    if (iframe) iframe.src = '';
                    popup.classList.remove('active');
                });
            }

            popup.addEventListener('click', (e) => {
                if (e.target === popup) {
                    if (iframe) iframe.src = '';
                    popup.classList.remove('active');
                }
            });
        }

        const accordionItems = document.querySelectorAll('.accordion-item');
        if (accordionItems.length > 0) {
            accordionItems[accordionItems.length - 1].classList.add('active');
        }

        document.querySelectorAll('.playable').forEach(video => {
            video.addEventListener('click', () => {
                const popup = document.querySelector('.video-popup');
                if (popup) {
                    const iframe = popup.querySelector('iframe');
                    if (iframe) {
                        iframe.src = video.getAttribute('data-video') + "?autoplay=1";
                        popup.classList.add('active');
                    }
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const wrap = document.querySelector('.tch-wrap');
            const grid = document.querySelector('.tch-grid');

            function closeAll() {
                document.querySelectorAll('.examx-dropdown.is-open').forEach(d => d.classList.remove('is-open'));
                if (grid) grid.style.marginTop = '';
            }

            function adjustPush(dd) {
                const menu = dd?.querySelector('.examx-menu');
                if (!grid || !menu) return;
                requestAnimationFrame(() => {
                    const h = menu.offsetHeight || 0;
                    grid.style.marginTop = dd.classList.contains('is-open') ? (h + 16) + 'px' : '';
                });
            }

            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.examx-dropdown .examx-pill');
                if (btn) {
                    const dd = btn.closest('.examx-dropdown');
                    document.querySelectorAll('.examx-dropdown').forEach(x => {
                        if (x !== dd) x.classList.remove('is-open');
                    });
                    dd.classList.toggle('is-open');
                    adjustPush(dd);
                    return;
                }
                if (!e.target.closest('.examx-dropdown')) {
                    closeAll();
                }
            });

            window.addEventListener('resize', function() {
                const open = document.querySelector('.examx-dropdown.is-open');
                if (open) adjustPush(open);
            });

            if (wrap) {
                new MutationObserver(() => {
                    const open = document.querySelector('.examx-dropdown.is-open');
                    if (open) adjustPush(open);
                }).observe(wrap, {
                    subtree: true,
                    childList: true
                });
            }
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var block = document.querySelector(".fm3d-videos-block");
            if (!block) return;

            var cards = Array.prototype.slice.call(block.querySelectorAll(".fm3d-video-card"));
            var dotsContainer = block.querySelector(".fm3d-videos-dots");
            var prevBtn = block.querySelector(".fm3d-nav-prev");
            var nextBtn = block.querySelector(".fm3d-nav-next");
            var modal = document.getElementById("fm3dVideoModal");

            if (!modal) return;

            var modalIframe = modal.querySelector(".fm3d-video-iframe");
            var modalPrev = modal.querySelector(".fm3d-video-modal-prev");
            var modalNext = modal.querySelector(".fm3d-video-modal-next");
            var modalClose = modal.querySelector(".fm3d-video-modal-close");

            var currentIndex = 0;
            var autoplayDelay = 6000;
            var autoplayTimer = null;
            var dots = [];

            if (cards.length === 0) return;

            function extractYoutubeId(url) {
                if (!url) return "";
                url = url.trim();
                var id = "";
                var match;

                if (url.indexOf("youtu.be/") !== -1) {
                    match = url.match(/youtu\.be\/([^?&\/]+)/);
                    if (match && match[1]) id = match[1];
                } else if (url.indexOf("youtube.com") !== -1) {
                    match = url.match(/[?&]v=([^?&]+)/);
                    if (match && match[1]) id = match[1];

                    if (!id) {
                        match = url.match(/embed\/([^?&\/]+)/);
                        if (match && match[1]) id = match[1];
                    }

                    if (!id) {
                        match = url.match(/shorts\/([^?&\/]+)/);
                        if (match && match[1]) id = match[1];
                    }
                }

                return id;
            }

            function getYoutubeEmbed(url) {
                var videoId = extractYoutubeId(url);
                if (!videoId) return url || "";
                return "https://www.youtube.com/embed/" + videoId + "?autoplay=1&rel=0";
            }

            cards.forEach(function(card, index) {
                var dot = document.createElement("div");
                dot.className = "fm3d-dot";
                dot.dataset.index = String(index);
                dotsContainer.appendChild(dot);
                dots.push(dot);

                var videoUrl = card.dataset.video || "";
                var videoId = extractYoutubeId(videoUrl);
                var cover = card.querySelector(".fm3d-video-cover");
                if (cover && videoId) {
                    cover.style.backgroundImage =
                        "url('https://img.youtube.com/vi/" + videoId + "/hqdefault.jpg')";
                }
            });

            function normalizeIndex(index) {
                var total = cards.length;
                return (index + total) % total;
            }

            function applyPositions() {
                var total = cards.length;
                cards.forEach(function(card, index) {
                    card.classList.remove("fm3d-pos-center", "fm3d-pos-left", "fm3d-pos-right",
                        "fm3d-pos-out");
                    var diff = normalizeIndex(index - currentIndex);
                    if (diff === 0) {
                        card.classList.add("fm3d-pos-center");
                    } else if (diff === 1) {
                        card.classList.add("fm3d-pos-right");
                    } else if (diff === total - 1) {
                        card.classList.add("fm3d-pos-left");
                    } else {
                        card.classList.add("fm3d-pos-out");
                    }
                });
                dots.forEach(function(dot, index) {
                    dot.classList.toggle("fm3d-dot-active", index === currentIndex);
                });
            }

            function gotoIndex(index) {
                currentIndex = normalizeIndex(index);
                applyPositions();
            }

            function goNext() {
                gotoIndex(currentIndex + 1);
            }

            function goPrev() {
                gotoIndex(currentIndex - 1);
            }

            function startAutoplay() {
                clearAutoplay();
                autoplayTimer = setInterval(goNext, autoplayDelay);
            }

            function clearAutoplay() {
                if (autoplayTimer) {
                    clearInterval(autoplayTimer);
                    autoplayTimer = null;
                }
            }

            function openModalForIndex(index) {
                currentIndex = normalizeIndex(index);
                applyPositions();
                var videoUrl = cards[currentIndex].dataset.video || "";
                var embedUrl = getYoutubeEmbed(videoUrl);
                modalIframe.src = embedUrl;
                modal.classList.add("fm3d-modal-open");
                clearAutoplay();
            }

            function closeModal() {
                modal.classList.remove("fm3d-modal-open");
                modalIframe.src = "";
                startAutoplay();
            }

            cards.forEach(function(card, index) {
                var trigger = card.querySelector(".fm3d-video-play");
                if (trigger) {
                    trigger.addEventListener("click", function() {
                        openModalForIndex(index);
                    });
                }
            });

            prevBtn.addEventListener("click", function() {
                goPrev();
                startAutoplay();
            });

            nextBtn.addEventListener("click", function() {
                goNext();
                startAutoplay();
            });

            dots.forEach(function(dot) {
                dot.addEventListener("click", function() {
                    var index = parseInt(dot.dataset.index, 10);
                    gotoIndex(index);
                    startAutoplay();
                });
            });

            modalPrev.addEventListener("click", function() {
                goPrev();
                openModalForIndex(currentIndex);
            });

            modalNext.addEventListener("click", function() {
                goNext();
                openModalForIndex(currentIndex);
            });

            modalClose.addEventListener("click", function() {
                closeModal();
            });

            modal.addEventListener("click", function(e) {
                if (e.target === modal) {
                    closeModal();
                }
            });

            document.addEventListener("keydown", function(e) {
                if (!modal.classList.contains("fm3d-modal-open")) return;
                if (e.key === "Escape") {
                    closeModal();
                } else if (e.key === "ArrowLeft") {
                    goPrev();
                    openModalForIndex(currentIndex);
                } else if (e.key === "ArrowRight") {
                    goNext();
                    openModalForIndex(currentIndex);
                }
            });

            applyPositions();
            startAutoplay();
        });
    </script>


    <script>
        document.querySelectorAll('.tab-btn').forEach((btn, index) => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                btn.classList.add('active');
                document.querySelectorAll('.tab-content')[index].classList.add('active');
            });
        });
    </script>

    <script>
        document.querySelectorAll('.examx-dropdown').forEach(drop => {
            drop.querySelector('.examx-pill').addEventListener('click', () => {
                document.querySelectorAll('.examx-dropdown').forEach(d => {
                    if (d !== drop) d.classList.remove('active');
                });
                drop.classList.toggle('active');
            });
        });

        document.addEventListener('click', (e) => {
            if (!e.target.closest('.examx-dropdown')) {
                document.querySelectorAll('.examx-dropdown').forEach(d => d.classList.remove('active'));
            }
        });
    </script>

    <script>
        document.querySelectorAll('.cmty-more').forEach(btn => {
            btn.addEventListener('click', () => {
                const box = btn.previousElementSibling;
                box.style.display = box.style.display === 'none' ? 'flex' : (box.style.display ? 'none' :
                    'none');
                if (!btn.dataset.state) {
                    btn.dataset.state = 'open'
                }
                btn.dataset.state = btn.dataset.state === 'open' ? 'closed' : 'open';
                btn.textContent = btn.dataset.state === 'open' ? 'شاهد جميع التعليقات ←' :
                    'إخفاء التعليقات ←';
            });
        });
    </script>

    <script>
        // resources/js/sale-point-2.js (inline allowed under the view)
        document.querySelectorAll('.sp2-group-head').forEach(btn => {
            btn.addEventListener('click', () => {
                const gp = btn.parentElement
                const icon = btn.querySelector('i')
                const open = gp.classList.toggle('is-open')
                icon.classList.toggle('fa-plus', !open)
                icon.classList.toggle('fa-minus', open)
            })
        })

        const sp2Input = document.getElementById('sp2Search')
        if (sp2Input) {
            sp2Input.addEventListener('input', e => {
                const q = e.target.value.trim()
                document.querySelectorAll('.sp2-panel tbody').forEach(tb => {
                    tb.querySelectorAll('tr').forEach(tr => {
                        tr.style.display = tr.innerText.includes(q) ? '' : 'none'
                    })
                })
            })
        }
    </script>

    <script>
        // cards-order minimal interactions
        const coBtn = document.getElementById('coChooserBtn');
        const coList = document.getElementById('coChooserList');
        if (coBtn && coList) {
            coBtn.addEventListener('click', () => {
                coBtn.parentElement.classList.toggle('open');
            });
            coList.querySelectorAll('li').forEach(li => {
                li.addEventListener('click', () => {
                    coBtn.querySelector('span').textContent = li.dataset.label || li.textContent.trim();
                    coBtn.parentElement.classList.remove('open');
                });
            });
            document.addEventListener('click', e => {
                if (!coBtn.parentElement.contains(e.target)) {
                    coBtn.parentElement.classList.remove('open');
                }
            });
        }
    </script>

    <script>
        document.querySelectorAll('.ud-item').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.ud-item').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                const id = btn.getAttribute('data-target');
                document.querySelectorAll('.ud-panel').forEach(p => p.classList.remove('show'));
                document.getElementById(id).classList.add('show');
            });
        });

        const chat = document.getElementById('udChat');
        document.querySelectorAll('.ud-send').forEach(b => {
            b.addEventListener('click', () => {
                const box = b.parentElement.querySelector('input');
                const txt = box.value.trim();
                if (!txt) return;
                const m = document.createElement('div');
                m.className = 'msg to';
                m.innerHTML = `<span>${txt}</span>`;
                chat.appendChild(m);
                box.value = '';
                chat.scrollTop = chat.scrollHeight;
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const root = document.querySelector('section.blog-slider');
            if (!root) return;

            const track = root.querySelector('.blog-slider__track');
            const dotsWrap = root.querySelector('.blog-slider__dots');

            if (!track) return;

            const cards = Array.from(track.querySelectorAll('.blog-card'));
            const total = cards.length;
            if (!total) return;

            function getPerView() {
                const w = window.innerWidth;
                if (w <= 600) return 1;
                if (w <= 992) return 2;
                return 3;
            }

            let perView = getPerView();
            const prevBtn = root.querySelector('.blog-slider__arrow--prev');
            const nextBtn = root.querySelector('.blog-slider__arrow--next');

            if (total <= perView) {
                if (prevBtn) prevBtn.style.display = 'none';
                if (nextBtn) nextBtn.style.display = 'none';
                if (dotsWrap) dotsWrap.style.display = 'none';
                return;
            }

            let index = 0;
            let timer = null;
            let pages = Math.ceil(total / perView);

            function buildDots() {
                if (!dotsWrap) return;
                dotsWrap.innerHTML = '';
                for (let i = 0; i < pages; i++) {
                    const b = document.createElement('button');
                    b.type = 'button';
                    b.addEventListener('click', function() {
                        index = i * perView;
                        updateVisible();
                        restartAuto();
                    });
                    dotsWrap.appendChild(b);
                }
            }

            function updateDots() {
                if (!dotsWrap) return;
                const currentPage = Math.floor(index / perView) % pages;
                Array.from(dotsWrap.children).forEach(function(btn, i) {
                    btn.setAttribute('aria-current', i === currentPage ? 'true' : 'false');
                });
            }

            function updateVisible() {
                perView = getPerView();
                pages = Math.ceil(total / perView);

                cards.forEach(function(card) {
                    card.classList.add('blog-card--hidden');
                });

                for (let i = 0; i < perView; i++) {
                    const current = (index + i) % total;
                    cards[current].classList.remove('blog-card--hidden');
                }

                updateDots();
            }

            function nextSlide() {
                perView = getPerView();
                index = (index + perView) % total;
                updateVisible();
            }

            function prevSlide() {
                perView = getPerView();
                index = (index - perView) % total;
                if (index < 0) index = ((pages - 1) * perView) % total;
                updateVisible();
            }

            function startAuto() {
                if (timer) clearInterval(timer);
                timer = setInterval(nextSlide, 10000);
            }

            function stopAuto() {
                if (timer) {
                    clearInterval(timer);
                    timer = null;
                }
            }

            function restartAuto() {
                stopAuto();
                startAuto();
            }

            buildDots();
            updateVisible();
            startAuto();

            root.addEventListener('click', function(e) {
                const nextHit = e.target.closest('.blog-slider__arrow--next');
                const prevHit = e.target.closest('.blog-slider__arrow--prev');

                if (nextHit) {
                    nextSlide();
                    restartAuto();
                } else if (prevHit) {
                    prevSlide();
                    restartAuto();
                }
            });

            window.addEventListener('resize', function() {
                const newPerView = getPerView();
                if (newPerView !== perView) {
                    perView = newPerView;
                    pages = Math.ceil(total / perView);
                    index = 0;
                    buildDots();
                    updateVisible();
                    restartAuto();
                }
            });

            root.addEventListener('mouseenter', stopAuto);
            root.addEventListener('mouseleave', startAuto);
        });
    </script>



    <script>
        (function() {
            const win = document.querySelector('.rvx-window');
            const track = document.querySelector('.rvx-track');
            const prev = document.querySelector('.rvx-prev');
            const next = document.querySelector('.rvx-next');
            const dotsBox = document.querySelector('.rvx-dots');
            if (!win || !track || !dotsBox) return;

            let originals = Array.from(track.children);
            if (!originals.length) return;

            const GAP = 28;
            let visible = 2;
            let cloneN = visible;
            let items = [];
            let index = 0;
            let step = 0;
            let timer, dragging = false,
                startX = 0,
                curX = 0;

            function perView() {
                const w = win.clientWidth;
                if (w <= 600) return 1;
                if (w <= 992) return 2;
                return 2;
            }

            function setCardWidths() {
                const vw = win.clientWidth;
                const cardW = (vw - GAP * (visible - 1)) / visible;
                items.forEach(el => {
                    el.style.minWidth = cardW + 'px';
                });
                step = cardW + GAP;
            }

            function buildClones() {
                track.innerHTML = '';
                cloneN = visible;
                const head = originals.slice(0, cloneN).map(n => n.cloneNode(true));
                const tail = originals.slice(-cloneN).map(n => n.cloneNode(true));
                tail.forEach(n => track.appendChild(n));
                originals.forEach(n => track.appendChild(n));
                head.forEach(n => track.appendChild(n));
                items = Array.from(track.children);
            }

            function jump(i) {
                track.style.transition = 'none';
                track.style.transform = `translateX(${-i*step}px)`;
                track.offsetHeight;
                track.style.transition = 'transform .6s cubic-bezier(.22,.61,.36,1)';
            }

            function apply() {
                track.style.transform = `translateX(${-index*step}px)`;
                updateDots();
            }

            function normalize() {
                if (index >= originals.length + cloneN) {
                    index = cloneN;
                    jump(index);
                }
                if (index < cloneN) {
                    index = cloneN + originals.length - 1;
                    jump(index);
                }
            }

            function currentDot() {
                const raw = (index - cloneN + originals.length) % originals.length;
                return raw;
            }

            function buildDots() {
                dotsBox.innerHTML = '';
                for (let i = 0; i < originals.length; i++) {
                    const b = document.createElement('button');
                    b.type = 'button';
                    b.addEventListener('click', () => {
                        index = cloneN + i;
                        apply();
                    });
                    dotsBox.appendChild(b);
                }
                updateDots();
            }

            function updateDots() {
                const cur = currentDot();
                [...dotsBox.children].forEach((d, k) => d.setAttribute('aria-current', k === cur ? 'true' : 'false'));
            }

            function measure(rebuild = false) {
                const newVis = perView();
                if (rebuild || newVis !== visible) {
                    visible = newVis;
                    buildClones();
                    setCardWidths();
                    index = cloneN;
                    jump(index);
                    buildDots();
                    eagerLoad();
                    ensureAuto();
                } else {
                    setCardWidths();
                    jump(index);
                    updateDots();
                }
            }

            function nextSlide() {
                index += 1;
                apply();
            }

            function prevSlide() {
                index -= 1;
                apply();
            }

            track.addEventListener('transitionend', normalize);

            win.addEventListener('pointerdown', e => {
                dragging = true;
                startX = curX = e.clientX;
                win.setPointerCapture(e.pointerId);
            });
            win.addEventListener('pointermove', e => {
                if (!dragging) return;
                curX = e.clientX;
            });
            win.addEventListener('pointerup', () => {
                if (!dragging) return;
                dragging = false;
                const dx = curX - startX;
                if (Math.abs(dx) > 40) {
                    dx < 0 ? nextSlide() : prevSlide();
                }
            });

            if (prev) prev.addEventListener('click', prevSlide);
            if (next) next.addEventListener('click', nextSlide);

            function start() {
                stop();
                timer = setInterval(nextSlide, 5000);
            }

            function stop() {
                if (timer) clearInterval(timer);
            }

            function ensureAuto() {
                if (step > 0) {
                    start();
                }
            }

            win.addEventListener('mouseenter', stop);
            win.addEventListener('mouseleave', start);

            window.addEventListener('resize', () => measure(true));
            window.addEventListener('load', () => measure(true));

            function eagerLoad() {
                const imgs = track.querySelectorAll('img[data-src]');
                const io = new IntersectionObserver(es => {
                    es.forEach(e => {
                        if (e.isIntersecting) {
                            const img = e.target;
                            img.src = img.getAttribute('data-src');
                            img.removeAttribute('data-src');
                            io.unobserve(img);
                        }
                    });
                }, {
                    root: win,
                    rootMargin: '200px'
                });
                imgs.forEach(img => io.observe(img));
            }

            measure(true);
        })();
    </script>


    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 600,
            easing: 'ease-out-cubic',
            offset: 80,
            once: true
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const viewport = document.querySelector('.x3c-viewport');
            if (!viewport) return;

            const rail = viewport.querySelector('.x3c-rail');
            const left = viewport.querySelector('.x3c-left');
            const right = viewport.querySelector('.x3c-right');

            if (!rail) return;

            let baseCells = Array.from(rail.children);
            if (!baseCells.length) return;

            let vis = window.matchMedia('(max-width:768px)').matches ? 1 : 3;
            let cloneCount = vis;
            let index = cloneCount;
            let itemWidth = 0;
            let items, realCount = baseCells.length;
            let animating = false,
                timer;

            function buildClones() {
                rail.innerHTML = '';
                const head = baseCells.slice(0, cloneCount).map(n => n.cloneNode(true));
                const tail = baseCells.slice(-cloneCount).map(n => n.cloneNode(true));
                tail.forEach(n => rail.appendChild(n));
                baseCells.forEach(n => rail.appendChild(n));
                head.forEach(n => rail.appendChild(n));
                items = Array.from(rail.children);
            }

            function jump(i) {
                rail.style.transition = 'none';
                rail.style.transform = `translate3d(${-i*itemWidth}px,0,0)`;
                rail.offsetHeight;
                rail.style.transition = 'transform .7s cubic-bezier(.22,.61,.36,1)';
            }

            function focus() {
                items.forEach(i => i.classList.remove('x3c-focus'));
                const centerOffset = (vis === 3 ? 1 : 0);
                const mid = index + centerOffset;
                if (items[mid]) items[mid].classList.add('x3c-focus');
            }

            function measure() {
                const nowVis = window.matchMedia('(max-width:768px)').matches ? 1 : 3;
                if (nowVis !== vis) {
                    vis = nowVis;
                    cloneCount = vis;
                    index = cloneCount;
                    baseCells = items.slice(cloneCount, cloneCount + realCount).map(n => n.cloneNode(true));
                    buildClones();
                }
                itemWidth = viewport.clientWidth / vis;
                items.forEach(i => i.style.minWidth = itemWidth + 'px');
                jump(index);
                focus();
            }

            function go(to) {
                if (animating) return;
                animating = true;
                index = to;
                rail.style.transform = `translate3d(${-index*itemWidth}px,0,0)`;
                focus();
            }

            function next() {
                go(index + 1)
            }

            function prev() {
                go(index - 1)
            }

            function normalize() {
                if (index >= realCount + cloneCount) {
                    index = cloneCount;
                    jump(index)
                } else if (index < cloneCount) {
                    index = cloneCount + realCount - 1;
                    jump(index)
                }
            }

            rail.addEventListener('transitionend', () => {
                normalize();
                animating = false;
                focus();
            });

            left.addEventListener('click', prev);
            right.addEventListener('click', next);

            function startAuto() {
                stopAuto();
                timer = setInterval(next, 5000)
            }

            function stopAuto() {
                if (timer) clearInterval(timer)
            }
            viewport.addEventListener('mouseenter', stopAuto);
            viewport.addEventListener('mouseleave', startAuto);

            buildClones();
            requestAnimationFrame(() => {
                measure();
                startAuto();
            });
            window.addEventListener('resize', measure);
        });
    </script>


    @stack('scripts')
    @yield('scripts')
</body>

</html>
