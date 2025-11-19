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

    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('assets_front/css/style.css') . '?v=' . (file_exists(public_path('assets_front/css/style.css')) ? filemtime(public_path('assets_front/css/style.css')) : time()) }}">

    @stack('styles')
    @yield('styles')

<style>
    @font-face {
        font-family: 'Somar';
        src: url('{{ asset('assets_front/fonts/Somar-Regular.woff2') }}') format('woff2'),
             url('{{ asset('assets_front/fonts/Somar-Regular.woff') }}') format('woff');
        font-weight: 400;
        font-style: normal;
        font-display: swap;
    }

    @font-face {
        font-family: 'Somar';
        src: url('{{ asset('assets_front/fonts/Somar-Bold.woff2') }}') format('woff2'),
             url('{{ asset('assets_front/fonts/Somar-Bold.woff') }}') format('woff');
        font-weight: 700;
        font-style: normal;
        font-display: swap;
    }

    html, body {
        font-family: 'Somar', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif!important;
    }

    body,
    button,
    input,
    textarea,
    select,
    p,
    span,
    a,
    li,
    label,
    h1, h2, h3, h4, h5, h6 {
        font-family: 'Somar', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif!important;
    }
</style>


</head>

<body>

    @if (!isset($hideHeader))
        @include('layouts.header')
    @endif

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

  const viewport = root.querySelector('.blog-slider__viewport');
  const track = root.querySelector('.blog-slider__track');
  const dotsWrap = root.querySelector('.blog-slider__dots');
  const prevBtn = root.querySelector('.blog-slider__arrow--prev');
  const nextBtn = root.querySelector('.blog-slider__arrow--next');

  let originals = Array.from(track.children);
  if (!originals.length) return;

  let perView = 3, cloneN = 3, index = 0, step = 0, gap = 0, timer, dragging = false, startX = 0, curX = 0, started = false;

  function dirRTL(){ return getComputedStyle(root).direction === 'rtl'; }
  function computePerView(){
    const w = root.clientWidth;
    if (w <= 600) return 1;
    if (w <= 992) return 2;
    return 3;
  }

  function enableArrows(){
    [prevBtn, nextBtn].forEach(b=>{
      if(!b) return;
      b.disabled = false;
      b.removeAttribute('disabled');
      b.style.pointerEvents = 'auto';
    });
  }

  function eagerLoad(){
    const nodes = Array.from(track.children).slice(0, (perView*2)+(cloneN*2));
    nodes.forEach(n=>{
      const img = n.querySelector('img[data-src]');
      if (img){ img.src = img.getAttribute('data-src'); img.removeAttribute('data-src'); }
    });
  }

  function build(){
    perView = computePerView();
    cloneN = perView;
    track.innerHTML = '';
    const head = originals.slice(0, cloneN).map(n=>n.cloneNode(true));
    const tail = originals.slice(-cloneN).map(n=>n.cloneNode(true));
    tail.forEach(n=>track.appendChild(n));
    originals.forEach(n=>track.appendChild(n));
    head.forEach(n=>track.appendChild(n));
    index = cloneN;
    measure(true);
    eagerLoad();
    buildDots();
    updateDots();
    enableArrows();
    ensureStart();
  }

  function measure(jumpToPos=false){
    gap = parseFloat(getComputedStyle(track).gap) || 0;
    const first = track.children[0];
    let cardW = first ? first.getBoundingClientRect().width : 0;
    if (!cardW) cardW = viewport.clientWidth / perView;
    step = cardW + gap;
    if (jumpToPos) applyTransform(false);
  }

  function applyTransform(animated=true){
    const sign = dirRTL() ? 1 : -1;
    if (!animated){
      track.style.transition = 'none';
      track.style.transform = `translateX(${sign * index * step}px)`;
      void track.offsetWidth;
      track.style.transition = 'transform .6s cubic-bezier(.22,.61,.36,1)';
    } else {
      track.style.transform = `translateX(${sign * index * step}px)`;
    }
  }

  function normalize(){
    if (index >= originals.length + cloneN){
      index = cloneN;
      applyTransform(false);
    } else if (index < cloneN){
      index = originals.length + cloneN - 1;
      applyTransform(false);
    }
    enableArrows();
  }

  function buildDots(){
    const pages = Math.max(1, originals.length - perView + 1);
    dotsWrap.innerHTML = '';
    for (let i=0;i<pages;i++){
      const b = document.createElement('button');
      b.type = 'button';
      b.addEventListener('click', () => {
        const target = i + cloneN;
        index = Math.min(cloneN + originals.length - perView, target);
        applyTransform(true);
        updateDots();
      });
      dotsWrap.appendChild(b);
    }
  }

  function updateDots(){
    const pages = Math.max(1, originals.length - perView + 1);
    const curRaw = (index - cloneN + originals.length) % originals.length;
    const cur = Math.min(curRaw, originals.length - perView);
    [...dotsWrap.children].forEach((d,k)=>d.setAttribute('aria-current', k===cur ? 'true':'false'));
    if (dotsWrap.children.length !== pages) buildDots();
  }

  function next(){
    index += 1;
    applyTransform(true);
    updateDots();
    enableArrows();
  }

  function prev(){
    index -= 1;
    applyTransform(true);
    updateDots();
    enableArrows();
  }

  function start(){ stop(); timer = setInterval(next, 5000); started = true; }
  function stop(){ if (timer) clearInterval(timer); }
  function ensureStart(){
    if (started) return;
    if (step > 0){ start(); return; }
    setTimeout(()=>{ measure(true); if (step > 0) start(); }, 60);
  }

  track.addEventListener('transitionend', () => { normalize(); });

  root.addEventListener('click', (e) => {
    const prevHit = e.target.closest('.blog-slider__arrow--prev');
    const nextHit = e.target.closest('.blog-slider__arrow--next');
    if (prevHit) prev();
    else if (nextHit) next();
  });

  viewport.addEventListener('pointerdown', e => {
    dragging = true; startX = curX = e.clientX; viewport.setPointerCapture(e.pointerId);
  });
  viewport.addEventListener('pointermove', e => { if (!dragging) return; curX = e.clientX; });
  viewport.addEventListener('pointerup', () => {
    if (!dragging) return; dragging = false;
    const dx = curX - startX, rtl = dirRTL();
    if (Math.abs(dx) > 40){
      const swipeLeft = dx < 0;
      if ((swipeLeft && !rtl) || (!swipeLeft && rtl)) next(); else prev();
    }
  });

  viewport.addEventListener('mouseenter', stop);
  viewport.addEventListener('mouseleave', start);

  window.addEventListener('resize', () => {
    const oldPer = perView;
    const newPer = computePerView();
    if (newPer !== oldPer){
      originals = Array.from(track.children).slice(cloneN, cloneN + originals.length);
      build();
    } else {
      measure(true);
      updateDots();
      enableArrows();
      ensureStart();
    }
  });

  const ro = new ResizeObserver(()=>{ measure(true); enableArrows(); ensureStart(); });
  ro.observe(track);

  window.addEventListener('load', ()=>{ measure(true); eagerLoad(); enableArrows(); ensureStart(); });

  const lazies = track.querySelectorAll('img[data-src]');
  if (lazies.length){
    const io = new IntersectionObserver(es=>{
      es.forEach(e=>{
        if(e.isIntersecting){
          const img=e.target; img.src=img.getAttribute('data-src'); img.removeAttribute('data-src'); io.unobserve(img);
          setTimeout(()=>{ measure(true); enableArrows(); ensureStart(); }, 50);
        }
      })
    },{root:viewport,rootMargin:'200px'});
    lazies.forEach(img=>io.observe(img));
  }

  build();
})();
</script>


<script>
(function(){
  const win=document.querySelector('.rvx-window');
  const track=document.querySelector('.rvx-track');
  const prev=document.querySelector('.rvx-prev');
  const next=document.querySelector('.rvx-next');
  const dotsBox=document.querySelector('.rvx-dots');
  if(!win||!track||!dotsBox) return;

  let originals=Array.from(track.children);
  if(!originals.length) return;

  const GAP=28;
  let visible=2;
  let cloneN=visible;
  let items=[];
  let index=0;
  let step=0;
  let timer, dragging=false, startX=0, curX=0;

  function perView(){
    const w=win.clientWidth;
    if(w<=600) return 1;
    if(w<=992) return 2;
    return 2;
  }

  function setCardWidths(){
    const vw=win.clientWidth;
    const cardW=(vw - GAP*(visible-1))/visible;
    items.forEach(el=>{ el.style.minWidth=cardW+'px'; });
    step=cardW+GAP;
  }

  function buildClones(){
    track.innerHTML='';
    cloneN=visible;
    const head=originals.slice(0,cloneN).map(n=>n.cloneNode(true));
    const tail=originals.slice(-cloneN).map(n=>n.cloneNode(true));
    tail.forEach(n=>track.appendChild(n));
    originals.forEach(n=>track.appendChild(n));
    head.forEach(n=>track.appendChild(n));
    items=Array.from(track.children);
  }

  function jump(i){
    track.style.transition='none';
    track.style.transform=`translateX(${-i*step}px)`;
    track.offsetHeight;
    track.style.transition='transform .6s cubic-bezier(.22,.61,.36,1)';
  }

  function apply(){
    track.style.transform=`translateX(${-index*step}px)`;
    updateDots();
  }

  function normalize(){
    if(index>=originals.length+cloneN){ index=cloneN; jump(index); }
    if(index<cloneN){ index=cloneN+originals.length-1; jump(index); }
  }

  function currentDot(){
    const raw=(index-cloneN+originals.length)%originals.length;
    return raw;
  }

  function buildDots(){
    dotsBox.innerHTML='';
    for(let i=0;i<originals.length;i++){
      const b=document.createElement('button');
      b.type='button';
      b.addEventListener('click',()=>{ index=cloneN+i; apply(); });
      dotsBox.appendChild(b);
    }
    updateDots();
  }

  function updateDots(){
    const cur=currentDot();
    [...dotsBox.children].forEach((d,k)=>d.setAttribute('aria-current',k===cur?'true':'false'));
  }

  function measure(rebuild=false){
    const newVis=perView();
    if(rebuild || newVis!==visible){
      visible=newVis;
      buildClones();
      setCardWidths();
      index=cloneN;
      jump(index);
      buildDots();
      eagerLoad();
      ensureAuto();
    }else{
      setCardWidths();
      jump(index);
      updateDots();
    }
  }

  function nextSlide(){ index+=1; apply(); }
  function prevSlide(){ index-=1; apply(); }

  track.addEventListener('transitionend',normalize);

  win.addEventListener('pointerdown',e=>{ dragging=true; startX=curX=e.clientX; win.setPointerCapture(e.pointerId); });
  win.addEventListener('pointermove',e=>{ if(!dragging) return; curX=e.clientX; });
  win.addEventListener('pointerup',()=>{ if(!dragging) return; dragging=false; const dx=curX-startX; if(Math.abs(dx)>40){ dx<0?nextSlide():prevSlide(); }});

  if(prev) prev.addEventListener('click',prevSlide);
  if(next) next.addEventListener('click',nextSlide);

  function start(){ stop(); timer=setInterval(nextSlide,5000); }
  function stop(){ if(timer) clearInterval(timer); }
  function ensureAuto(){ if(step>0){ start(); } }

  win.addEventListener('mouseenter',stop);
  win.addEventListener('mouseleave',start);

  window.addEventListener('resize',()=>measure(true));
  window.addEventListener('load',()=>measure(true));

  function eagerLoad(){
    const imgs=track.querySelectorAll('img[data-src]');
    const io=new IntersectionObserver(es=>{
      es.forEach(e=>{ if(e.isIntersecting){ const img=e.target; img.src=img.getAttribute('data-src'); img.removeAttribute('data-src'); io.unobserve(img); }});
    },{root:win,rootMargin:'200px'});
    imgs.forEach(img=>io.observe(img));
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
document.addEventListener('DOMContentLoaded',function(){
  const viewport=document.querySelector('.x3c-viewport');
  const rail=viewport.querySelector('.x3c-rail');
  const left=viewport.querySelector('.x3c-left');
  const right=viewport.querySelector('.x3c-right');

  let baseCells=Array.from(rail.children);
  if(!baseCells.length)return;

  let vis=window.matchMedia('(max-width:768px)').matches?1:3;
  let cloneCount=vis;
  let index=cloneCount;
  let itemWidth=0;
  let items,realCount=baseCells.length;
  let animating=false,timer;

  function buildClones(){
    rail.innerHTML='';
    const head=baseCells.slice(0,cloneCount).map(n=>n.cloneNode(true));
    const tail=baseCells.slice(-cloneCount).map(n=>n.cloneNode(true));
    tail.forEach(n=>rail.appendChild(n));
    baseCells.forEach(n=>rail.appendChild(n));
    head.forEach(n=>rail.appendChild(n));
    items=Array.from(rail.children);
  }

  function jump(i){
    rail.style.transition='none';
    rail.style.transform=`translate3d(${-i*itemWidth}px,0,0)`;
    rail.offsetHeight;
    rail.style.transition='transform .7s cubic-bezier(.22,.61,.36,1)';
  }

  function focus(){
    items.forEach(i=>i.classList.remove('x3c-focus'));
    const centerOffset=(vis===3?1:0);
    const mid=index+centerOffset;
    if(items[mid])items[mid].classList.add('x3c-focus');
  }

  function measure(){
    const nowVis=window.matchMedia('(max-width:768px)').matches?1:3;
    if(nowVis!==vis){
      vis=nowVis;cloneCount=vis;index=cloneCount;
      baseCells=items.slice(cloneCount,cloneCount+realCount).map(n=>n.cloneNode(true));
      buildClones();
    }
    itemWidth=viewport.clientWidth/vis;
    items.forEach(i=>i.style.minWidth=itemWidth+'px');
    jump(index);
    focus();
  }

  function go(to){
    if(animating)return;
    animating=true;
    index=to;
    rail.style.transform=`translate3d(${-index*itemWidth}px,0,0)`;
    focus();
  }

  function next(){go(index+1)}
  function prev(){go(index-1)}

  function normalize(){
    if(index>=realCount+cloneCount){index=cloneCount;jump(index)}
    else if(index<cloneCount){index=cloneCount+realCount-1;jump(index)}
  }

  rail.addEventListener('transitionend',()=>{
    normalize();
    animating=false;
    focus();
  });

  left.addEventListener('click',prev);
  right.addEventListener('click',next);

  function startAuto(){ stopAuto(); timer=setInterval(next,5000) }
  function stopAuto(){ if(timer) clearInterval(timer) }
  viewport.addEventListener('mouseenter',stopAuto);
  viewport.addEventListener('mouseleave',startAuto);

  buildClones();
  requestAnimationFrame(()=>{ measure(); startAuto(); });
  window.addEventListener('resize',measure);
});
</script>


    @stack('scripts')
    @yield('scripts')
</body>

</html>
