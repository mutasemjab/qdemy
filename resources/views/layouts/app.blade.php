<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    <!-- Google Font: Montserrat Arabic -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat+Arabic:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('assets_front/css/style.css') }}">

    @stack('styles')
    @yield('styles')

</head>

<body>

    @include('layouts.header')

    <main>
        <!-- @include('admin.includes.alerts.success')
        @include('admin.includes.alerts.error') -->
        @yield('content')
    </main>

    @if (!isset($hideFooter))
        @include('layouts.footer')
    @endif

    <!-- <script src="{{ asset('assets_front/js/app.js') }}"></script> -->
    <script>
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.getElementById('navMenu').classList.toggle('active');
        });
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
                const iframe = popup.querySelector('iframe');
                iframe.src = video.getAttribute('data-video') + "?autoplay=1";
                popup.classList.add('active');
            });
        });

        // غلق الفيديو
        document.querySelector('.video-popup .close-btn').addEventListener('click', () => {
            const popup = document.querySelector('.video-popup');
            const iframe = popup.querySelector('iframe');
            iframe.src = '';
            popup.classList.remove('active');
        });

        document.querySelector('.video-popup').addEventListener('click', (e) => {
            if (e.target.classList.contains('video-popup')) {
                const popup = document.querySelector('.video-popup');
                const iframe = popup.querySelector('iframe');
                iframe.src = '';
                popup.classList.remove('active');
            }
        });

        // فتح الصورة
        document.querySelectorAll('.media-image img').forEach(img => {
            img.addEventListener('click', () => {
                const popup = document.querySelector('.image-popup');
                const popupImg = popup.querySelector('img');
                popupImg.src = img.src;
                popup.classList.add('active');
            });
        });

        // غلق الصورة
        document.querySelector('.image-popup .close-btn').addEventListener('click', () => {
            document.querySelector('.image-popup').classList.remove('active');
        });

        document.querySelector('.image-popup').addEventListener('click', (e) => {
            if (e.target.classList.contains('image-popup')) {
                document.querySelector('.image-popup').classList.remove('active');
            }
        });
    </script>

    <script>
        const track = document.querySelector('.carousel-track');
        let slides = Array.from(document.querySelectorAll('.carousel-slide'));
        const prevBtn = document.querySelector('.carousel-btn.prev');
        const nextBtn = document.querySelector('.carousel-btn.next');
        let autoSlide;

        function setActive() {
            slides.forEach(slide => slide.classList.remove('active'));
            slides[1].classList.add('active'); // العنصر الأوسط دايمًا
        }

        function moveNext() {
            track.style.transition = 'transform 0.5s ease';
            track.style.transform = 'translateX(-33.33%)';

            track.addEventListener('transitionend', () => {
                track.style.transition = 'none'; // نوقف الأنيميشن
                track.appendChild(slides[0]); // أول عنصر يروح آخر التراك
                slides = Array.from(document.querySelectorAll('.carousel-slide'));
                track.style.transform = 'translateX(0)'; // نرجع للوضع الطبيعي
                requestAnimationFrame(() => { // نضمن تحديث الـ DOM قبل إضافة الأنيميشن من جديد
                    setActive();
                });
            }, {
                once: true
            });
        }

        function movePrev() {
            track.style.transition = 'none';
            track.insertBefore(slides[slides.length - 1], slides[0]); // آخر عنصر ييجي الأول
            slides = Array.from(document.querySelectorAll('.carousel-slide'));
            track.style.transform = 'translateX(-33.33%)';

            requestAnimationFrame(() => {
                track.style.transition = 'transform 0.5s ease';
                track.style.transform = 'translateX(0)';
                setActive();
            });
        }


        nextBtn.addEventListener('click', () => {
            moveNext();
            resetAutoSlide();
        });

        prevBtn.addEventListener('click', () => {
            movePrev();
            resetAutoSlide();
        });

        function startAutoSlide() {
            autoSlide = setInterval(moveNext, 5000);
        }

        function resetAutoSlide() {
            clearInterval(autoSlide);
            startAutoSlide();
        }

        setActive();
        startAutoSlide();
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
        const iframe = popup.querySelector('iframe');
        const closeBtn = popup.querySelector('.close-popup');

        document.querySelectorAll('.lesson-video').forEach(video => {
            video.addEventListener('click', () => {
                iframe.src = video.dataset.video + "?autoplay=1";
                popup.classList.add('active');
            });
        });

        closeBtn.addEventListener('click', () => {
            iframe.src = '';
            popup.classList.remove('active');
        });

        popup.addEventListener('click', (e) => {
            if (e.target === popup) {
                iframe.src = '';
                popup.classList.remove('active');
            }
        });

        const accordionItems = document.querySelectorAll('.accordion-item');
        accordionItems[accordionItems.length - 1].classList.add('active');

        document.querySelectorAll('.playable').forEach(video => {
            video.addEventListener('click', () => {
                const popup = document.querySelector('.video-popup');
                const iframe = popup.querySelector('iframe');
                iframe.src = video.getAttribute('data-video') + "?autoplay=1";
                popup.classList.add('active');
            });
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
        (() => {
            const root = document.querySelector('.blog-slider');
            if (!root) return;

            const track = root.querySelector('.blog-slider__track');
            const cards = [...root.querySelectorAll('.blog-card')];
            const prevBtn = root.querySelector('.blog-slider__arrow--prev');
            const nextBtn = root.querySelector('.blog-slider__arrow--next');
            const dotsWrap = root.querySelector('.blog-slider__dots');

            let index = 0,
                perView = 3,
                pages = 1,
                startX = 0,
                curX = 0,
                dragging = false;

            function computePerView() {
                const w = root.clientWidth;
                if (w <= 600) return 1;
                if (w <= 992) return 2;
                return 3;
            }

            function layout() {
                perView = computePerView();
                pages = Math.max(1, Math.ceil(cards.length / perView));
                index = Math.min(index, pages - 1);
                buildDots();
                update();
            }

            function update() {
                const gap = parseFloat(getComputedStyle(track).gap) || 0;
                const cardW = cards[0].getBoundingClientRect().width;
                const offset = index * (cardW * perView + gap * (perView - 1));
                track.style.transform = `translateX(${(root.dir==='rtl' ? offset : -offset)}px)`;
                prevBtn.disabled = index === 0;
                nextBtn.disabled = index >= pages - 1;
                [...dotsWrap.children].forEach((d, i) => d.setAttribute('aria-current', i === index ? 'true' :
                'false'));
            }

            function buildDots() {
                dotsWrap.innerHTML = '';
                for (let i = 0; i < pages; i++) {
                    const b = document.createElement('button');
                    b.type = 'button';
                    b.addEventListener('click', () => {
                        index = i;
                        update();
                    });
                    dotsWrap.appendChild(b);
                }
            }

            prevBtn.addEventListener('click', () => {
                if (index > 0) {
                    index--;
                    update();
                }
            });
            nextBtn.addEventListener('click', () => {
                if (index < pages - 1) {
                    index++;
                    update();
                }
            });

            // drag / swipe
            const viewport = root.querySelector('.blog-slider__viewport');
            viewport.addEventListener('pointerdown', e => {
                dragging = true;
                startX = curX = e.clientX;
                viewport.setPointerCapture(e.pointerId);
            });
            viewport.addEventListener('pointermove', e => {
                if (!dragging) return;
                curX = e.clientX;
            });
            viewport.addEventListener('pointerup', e => {
                if (!dragging) return;
                dragging = false;
                const dx = curX - startX;
                const thresh = 40;
                if (Math.abs(dx) > thresh) {
                    const isRTL = getComputedStyle(root).direction === 'rtl';
                    const swipeLeft = dx < 0;
                    if ((swipeLeft && !isRTL) || (!swipeLeft && isRTL)) { // next
                        if (index < pages - 1) index++;
                    } else {
                        if (index > 0) index--;
                    }
                }
                update();
            });

            window.addEventListener('resize', () => {
                layout();
            });
            layout();
            setInterval(() => {
                if (pages <= 1) return;
                if (index < pages - 1) {
                    index++;
                } else {
                    index = 0;
                }
                update();
            }, 5000);

        })();
    </script>

    <script>
        (function() {
            const win = document.querySelector('.rvx-window');
            const track = document.querySelector('.rvx-track');
            const cards = Array.from(document.querySelectorAll('.rvx-card'));
            const prev = document.querySelector('.rvx-prev');
            const next = document.querySelector('.rvx-next');
            const dotsBox = document.querySelector('.rvx-dots');

            const gap = 28;
            let visible = 2; // بطاقتان معاً على الشاشات الكبيرة
            const cardW = cards[0].offsetWidth;
            const step = cardW + gap;

            function pagesCount() {
                const vw = win.clientWidth;
                visible = Math.max(1, Math.floor((vw + gap) / (cardW + gap)));
                return Math.max(1, Math.ceil(cards.length / visible));
            }

            let pages = pagesCount();
            let index = 0;

            function layout() {
                pages = pagesCount();
                dotsBox.innerHTML = '';
                for (let i = 0; i < pages; i++) {
                    const b = document.createElement('button');
                    if (i === index) b.setAttribute('aria-current', 'true');
                    b.addEventListener('click', () => {
                        index = i;
                        update();
                    });
                    dotsBox.appendChild(b);
                }
                update();
            }

            function update() {
                [...dotsBox.children].forEach((b, i) => b.toggleAttribute('aria-current', i === index));
                const x = -index * step * visible;
                track.style.transform = `translateX(${x}px)`;
            }

            prev.addEventListener('click', () => {
                index = (index - 1 + pages) % pages;
                update();
            });

            next.addEventListener('click', () => {
                index = (index + 1) % pages;
                update();
            });

            // سحب باللمس/الماوس
            let startX = null,
                moved = 0;
            win.addEventListener('pointerdown', e => {
                startX = e.clientX;
                moved = 0;
                win.setPointerCapture(e.pointerId);
            });
            win.addEventListener('pointermove', e => {
                if (startX !== null) moved = e.clientX - startX;
            });
            win.addEventListener('pointerup', () => {
                if (Math.abs(moved) > 40) {
                    (moved > 0 ? prev : next).click();
                }
                startX = null;
                moved = 0;
            });

            // Auto play
            setInterval(() => {
                next.click();
            }, 5000);

            window.addEventListener('resize', layout);
            layout();
        })();
    </script>

    @stack('scripts')
    @yield('scripts')
</body>

</html>
