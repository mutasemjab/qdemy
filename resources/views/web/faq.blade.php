@extends('layouts.app')
@section('title', __('front.Frequently Asked Questions'))

@section('content')
<section class="faq-page" dir="rtl">
  <div class="faq-hero">
    <h1>{{ __('front.Frequently Asked Questions') }} – {{ __('front.Platform') }} <span>{{ __('front.qdemy') }}</span></h1>
    <p>{{ __('front.Everything you need to know about registration, payment, cards, courses, teachers, your account and usage policy') }}</p>
  </div>

  <div class="faq-controls">
    <ul class="faq-cats" id="faqCats">
      @foreach($categories as $i=>$cat)
        <li class="faq-chip {{ $i===0?'is-active':'' }}" data-key="{{ $cat['key'] }}">{{ $cat['label'] }}</li>
      @endforeach
    </ul>
    <div class="faq-search">
      <input type="text" id="faqSearch" placeholder="{{ __('front.Search, for example: activation code, teachers, cancel') }}" inputmode="search">
      <i class="fas fa-magnifying-glass"></i>
    </div>
  </div>

  <div class="faq-list" id="faqList">
    @foreach($faqs as $f)
      <div class="faq-item" data-cat="{{ $f->type }}">
        <button class="faq-q" aria-expanded="false">
          <span class="faq-tag">{{ collect($categories)->firstWhere('key', $f->type)['label'] ?? __('front.Other') }}</span>
          <span class="faq-title">{{ $f->question }}</span>
          <span class="faq-icon"><i class="fas fa-chevron-down"></i></span>
        </button>
        <div class="faq-a">
          <div class="faq-a-inner"><p>{{ $f->answer }}</p></div>
        </div>
      </div>
    @endforeach
  </div>
</section>
@endsection

@push('styles')
<style>
:root{--faq-bg:#f8f7f4;--card:#ffffff;--text:#0f172a;--muted:#6b7280;--line:#ece8df;--accent:#0055D2;--chip:#fbe9dd}
.faq-page{padding:32px 0}
.faq-hero{
    max-width: 1300px;
    margin: 0 auto 18px;
    padding: 18px 0px;
}
.faq-hero h1{font-weight:900;font-size:32px;color:var(--text);margin:0 0 8px}
.faq-hero h1 span{color:var(--accent)}
.faq-hero p{margin:0;color:var(--muted)}
.faq-controls{max-width:1300px;margin:0 auto 18px;display:flex;gap:16px;align-items:center;flex-wrap:wrap}
.faq-cats{display:flex;gap:10px;list-style:none;margin:0;padding:0;flex-wrap:wrap}
.faq-chip{border:1px solid #000000;padding:8px 14px;border-radius:999px;font-weight:700;cursor:pointer;font-size:20px}
.faq-chip.is-active{background:var(--accent);color:#fff;border-color:var(--accent)}
.faq-search{margin-inline-start:auto;position:relative}
.faq-search input{height:44px;width:320px;max-width:100%;background:#fff;border:1px solid var(--line);border-radius:999px;padding:0 44px 0 16px;font-size:20px}
.faq-search i{position:absolute;inset-inline-start:14px;top:50%;transform:translateY(-50%);color:#9ca3af}
.faq-list{    
    max-width: 1300px;
    margin: 0 auto;
    background: var(--card);
    border: 1px solid var(--line);
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 8px 24px rgb(15 23 42 / 19%), 0 2px 6px rgba(15, 23, 42, .05);}
.faq-item{border-bottom:1px solid var(--line)}
.faq-item:last-child{border-bottom:0}
.faq-q{width:100%;display:grid;grid-template-columns:auto 1fr auto;align-items:center;gap:10px;padding:16px 18px;background:#fff;border:0;cursor:pointer}
.faq-tag{width: 120px;background:#d9d7d7;color:var(--accent);border-radius:999px;padding:4px 10px;font-size:19px;font-weight:700}
.faq-title{color:var(--text);font-weight:700;text-align:right;font-size: x-large}
.faq-icon{color:#9aa1a9;transition:transform .2s}
.faq-a{max-height:0;overflow:hidden;transition:max-height .25s ease}
.faq-a-inner{padding:0 18px 18px;color:var(--muted);line-height:1.9}
.faq-q[aria-expanded="true"] + .faq-a{max-height:240px}
.faq-q[aria-expanded="true"] .faq-icon{transform:rotate(180deg)}
@media (max-width:768px){
  .faq-hero h1{font-size:26px}
  .faq-q{gap:12px}
  .faq-tag{order:2}
  .faq-title{order:1}
}

@media (max-width:768px){
  .faq-hero{padding:12px 10px}
  .faq-controls{gap:10px}
  .faq-cats{overflow:auto;white-space:nowrap;padding-bottom:6px;margin-bottom:6px}
  .faq-chip{font-size:20px;padding:6px 12px}
  .faq-search{width:100%;margin-inline-start:0}
  .faq-search input{width:100%;height:40px}
  .faq-list{margin:0 10px;border-radius:12px}
  .faq-q{gap:8px;padding:14px 14px}
  .faq-tag{order:2;min-width:auto;width:auto;padding:3px 8px;font-size:20px}
  .faq-title{order:1;font-size:20px}
  .faq-a-inner{padding:0 14px 14px;font-size:20px}
  .faq-page {
    padding: 32px 10px;
}
}

@media (max-width:480px){
  .faq-hero h1{font-size:22px}
  .faq-hero p{font-size:20px}
  .faq-chip{font-size:20px;padding:6px 10px}
  .faq-title{font-size:20px}
  .faq-q{padding:12px 12px}
  .faq-list{margin:0 8px}
  .faq-search input{height:38px}
}
</style>
@endpush

@push('scripts')
<script>
const cats=[...document.querySelectorAll('.faq-chip')];
const list=document.getElementById('faqList');
const items=[...list.querySelectorAll('.faq-item')];
const qs=[...list.querySelectorAll('.faq-q')];
const search=document.getElementById('faqSearch');

cats.forEach(c=>c.addEventListener('click',()=>{
    cats.forEach(x=>x.classList.remove('is-active'));
    c.classList.add('is-active');
    filter(c.dataset.key,search.value.trim());
}));

qs.forEach(b=>b.addEventListener('click',()=>{
    const e=b.getAttribute('aria-expanded')==='true';
    qs.forEach(x=>x.setAttribute('aria-expanded','false'));
    b.setAttribute('aria-expanded',e?'false':'true');
}));

search.addEventListener('input',()=>filter(document.querySelector('.faq-chip.is-active')?.dataset.key||'all',search.value.trim()));

function filter(key,term){
    items.forEach(i=>{
        const ok1=(key==='all')||(i.dataset.cat===key);
        const title = i.querySelector('.faq-title').textContent.toLowerCase();
        const answer = i.querySelector('.faq-a-inner p').textContent.toLowerCase();
        const searchTerm = term.toLowerCase();
        const ok2=(term===''||title.includes(searchTerm)||answer.includes(searchTerm));
        i.style.display=(ok1&&ok2)?'block':'none';
    });
}
</script>
@endpush