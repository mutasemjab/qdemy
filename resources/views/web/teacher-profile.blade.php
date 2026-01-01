@extends('layouts.app')
@section('title', $teacher->name . ' - ' . __('front.Teacher Details'))

@section('content')
<section class="t-show">
  <div class="t-hero">
    <div class="t-hero__media">
      <img src="{{ $teacher->photo_url }}" alt="{{ $teacher->name }}">
    </div>
    <div class="t-hero__info">
      <h1 class="t-name">{{ __('front.teacher_prefix') }} {{ $teacher->name }}</h1>
      <p class="t-role">{{ $teacher->name_of_lesson }}</p>
      <div class="t-meta">
        <div class="t-badge">
          <i class="far fa-circle-play"></i>
          <span>{{ $coursesCount }} {{ __('front.course') }}</span>
        </div>
        <div class="t-dot"></div>
        <div class="t-badge">
          <i class="far fa-user"></i>
          <span>{{ number_format($teacher->followersCount()) }} {{ __('front.follower') }}</span>
        </div>
      </div>
      <div class="t-cta">
        @auth
        <button type="button" 
                class="btn t-btn--ghost follow-btn" 
                data-teacher-id="{{ $teacher->id }}"
                data-following="{{ $isFollowing ? 'true' : 'false' }}">
          <i class="far {{ $isFollowing ? 'fa-heart-circle-check' : 'fa-heart' }}"></i>
          <span>{{ $isFollowing ? __('front.following') : __('front.follow') }}</span>
        </button>
        @endauth
      </div>
      <div class="t-socials">
        @if($teacher->facebook)
        <a href="{{ $teacher->facebook }}" target="_blank" aria-label="Facebook">
          <i class="fab fa-facebook"></i>
        </a>
        @endif
        @if($teacher->youtube)
        <a href="{{ $teacher->youtube }}" target="_blank" aria-label="YouTube">
          <i class="fab fa-youtube"></i>
        </a>
        @endif
        @if($teacher->instagram)
        <a href="{{ $teacher->instagram }}" target="_blank" aria-label="Instagram">
          <i class="fab fa-instagram"></i>
        </a>
        @endif
        @if($teacher->whataspp)
        <a href="{{ $teacher->whataspp }}" target="_blank" aria-label="WhatsApp">
          <i class="fab fa-whatsapp"></i>
        </a>
        @endif
      </div>
    </div>
  </div>

  <div class="t-panels">
    <div class="t-tabs">
      <button class="t-tab is-active" data-tab="overview">{{ __('front.overview') }}</button>
      <button class="t-tab" data-tab="courses">{{ __('front.courses') }}</button>
    </div>

    <div class="t-panel is-active" id="overview">
      <div class="t-grid">
        <div class="t-card">
          <h3 class="t-h3">{{ __('front.about_teacher') }}</h3>
          <div class="t-text">
            {{ app()->getLocale() == 'ar' ? $teacher->description_ar : $teacher->description_en }}
          </div>
        </div>
        <div class="t-card">
          <h3 class="t-h3">{{ __('front.specializations') }}</h3>
          <ul class="t-tags">
            @foreach($teacher->courses->pluck('subject.name')->unique() as $subject)
            <li>{{ $subject }}</li>
            @endforeach
          </ul>
          <div class="t-stats">
            <div class="t-stat">
              <span class="t-stat__num">{{ $teacher->user->created_at->diffInYears(now()) }}+</span>
              <span class="t-stat__lbl">{{ __('front.years_experience') }}</span>
            </div>
           
         
          </div>
        </div>
      </div>
    </div>

    <div class="t-panel" id="courses">
      @if($teacher->courses->count() > 0)
      <div class="t-list">
        @foreach($teacher->courses as $course)
        <article class="t-course">
          <div class="t-course__media">
            <img src="{{ $course->image_url }}" alt="{{ $course->title }}">
            <span class="t-price">{{ $course->price }} {{ __('front.currency') }}</span>
          </div>
          <div class="t-course__body">
            <a href="{{ route('course', $course->id) }}" class="t-course__title">
              {{ $course->title }}
            </a>
            <div class="t-course__meta">
              <span>
                {{ $course->description }}
              </span>
             
              <span>
                <i class="far fa-calendar"></i>
                {{ $course->created_at->format('d M Y') }}
              </span>
            </div>
            <div class="t-course__cta">
              <a href="{{ route('course', $course->id) }}" class="btn t-btn--solid">
                {{ __('front.view_details') }}
              </a>
            </div>
          </div>
        </article>
        @endforeach
      </div>
      @else
      <p class="t-empty">{{ __('front.no_courses_yet') }}</p>
      @endif
    </div>

   
  </div>
</section>
@endsection

@push('styles')
<style>
:root{--ink:#000000;--paper:#FFFFFF;--brand:#0054d2;--violet:#665D99;--coal:#464846;--line:#ECECEC;--muted:#6b7280}
.t-show{max-width:1300px;margin:0 auto;padding:20px 16px}
.t-hero{display:grid;grid-template-columns:360px 1fr;gap:28px;background:linear-gradient(135deg,#f7f9fb 0%,#fff 100%);border:1px solid var(--line);border-radius:20px;padding:24px;position:relative;overflow:hidden}
.t-hero::after{content:"";position:absolute;inset:auto 0 0 0;height:9px;background:linear-gradient(90deg,var(--brand),var(--violet))}
.t-hero__media{display:flex;align-items:center;justify-content:center}
.t-hero__media img{width:100%;max-width:320px;border-radius:16px;object-fit:cover;}
.t-hero__info{display:flex;flex-direction:column;justify-content:center}
.t-name{margin:0 0 6px;font-weight:900;color:var(--ink);font-size:34px}
.t-role{margin:0 0 12px;color:var(--violet);font-weight:700}
.t-meta{display:flex;align-items:center;gap:10px;color:var(--coal);flex-wrap:wrap}
.t-badge{display:inline-flex;align-items:center;gap:8px;background:#f3f5f7;border:1px solid var(--line);border-radius:999px;padding:6px 12px;font-weight:700}
.t-dot{width:6px;height:6px;border-radius:999px;background:#d9dde2}
.t-cta{display:flex;gap:10px;margin:16px 0 12px;flex-wrap:wrap}
.btn{display:inline-flex;align-items:center;justify-content:center;height:42px;padding:0 18px;border-radius:12px;font-weight:800;text-decoration:none;cursor:pointer;border:none;gap:8px}
.t-btn--solid{background:var(--brand);color:#fff}
.t-btn--ghost{background:#fff;border:1px solid var(--line);color:var(--coal)}
.t-btn--ghost:hover{background:#f8f9fa}
.t-socials{display:flex;gap:12px}
.t-socials a{text-decoration: none;width:40px;height:40px;border-radius:10px;display:inline-flex;align-items:center;justify-content:center;background:#f5f7f9;color:#fff;border:1px solid transparent;background-image:linear-gradient(#1d72e8, #0054d2);box-shadow:0 6px 20px rgba(1,173,94,.2)}
.t-panels{margin-top:24px}
.t-tabs{display:flex;gap:8px;background:#fff;border:1px solid var(--line);border-radius:14px;padding:6px;flex-wrap:wrap}
.t-tab{appearance:none;background:#fff;border:1px solid var(--line);padding:10px 14px;border-radius:10px;font-weight:800;color:var(--coal);cursor:pointer}
.t-tab.is-active{background:var(--brand);border-color:var(--brand);color:#fff}
.t-panel{display:none;background:#fff;border:1px solid var(--line);border-radius:14px;margin-top:14px;padding:18px}
.t-panel.is-active{display:block}
.t-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px}
.t-card{background:#fff;border:1px solid var(--line);border-radius:14px;padding:16px}
.t-h3{margin:0 0 10px;font-weight:900;color:var(--ink)}
.t-text{color:var(--muted);line-height:1.9}
.t-tags{display:flex;gap:8px;flex-wrap:wrap;list-style:none;margin:0;padding:0}
.t-tags li{background:#eef2f7;border:1px solid var(--line);color:var(--coal);padding:6px 10px;border-radius:999px;font-weight:700;font-size:13px}
.t-stats{display:flex;gap:14px;margin-top:14px;flex-wrap:wrap}
.t-stat{background:#f8fff9;border:1px solid #d7f3e4;border-radius:14px;padding:12px 14px;min-width:120px}
.t-stat__num{display:block;font-weight:900;color:var(--brand);font-size:20px}
.t-stat__lbl{display:block;color:var(--coal);font-weight:700;font-size:12px}
.t-list{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px}
.t-course{background:#fff;border:1px solid var(--line);border-radius:14px;overflow:hidden;display:flex;flex-direction:column}
.t-course__media{position:relative;aspect-ratio:16/9;overflow:hidden}
.t-course__media img{width:100%;height:100%;object-fit:cover;display:block}
.t-price{position:absolute;bottom:10px;inset-inline-end:10px;background:var(--brand);color:#fff;border-radius:10px;padding:6px 10px;font-weight:900}
.t-course__body{padding:14px}
.t-course__title{display:block;font-weight:900;color:var(--ink);text-decoration:none;margin-bottom:8px}
.t-course__meta{display:flex;gap:12px;color:var(--muted);font-size:13px;flex-wrap:wrap}
.t-course__meta i{margin-inline-end:6px}
.t-course__cta{margin-top:12px}
.t-empty{text-align:center;color:var(--muted);font-weight:700;padding:40px 20px}
.t-revs{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}
.t-rev{background:#fff;border:1px solid var(--line);border-radius:14px;padding:14px}
.t-rev__head{display:flex;justify-content:space-between;align-items:center;margin-bottom:8px}
.t-rev__rate{display:inline-flex;align-items:center;gap:6px;background:#fff7ec;border:1px solid #ffe1bd;border-radius:999px;padding:4px 10px;font-weight:800;color:#a16100}
.t-rev__text{color:var(--coal);line-height:1.9;margin:0}
@media (max-width:1200px){
  .t-hero{grid-template-columns:300px 1fr}
}
@media (max-width:992px){
  .t-hero{grid-template-columns:1fr}
  .t-hero__media img{max-width:240px}
  .t-grid{grid-template-columns:1fr}
  .t-list{grid-template-columns:repeat(2,minmax(0,1fr))}
  .t-revs{grid-template-columns:1fr}
}
@media (max-width:640px){
  .t-show{padding:16px 10px}
  .t-name{font-size:26px}
  .t-list{grid-template-columns:1fr}
  .t-cta .btn{width:100%}
}
</style>
@endpush

@push('scripts')
<script>
// Tab switching
document.querySelectorAll('.t-tab').forEach(b=>{
  b.addEventListener('click',()=>{
    document.querySelectorAll('.t-tab').forEach(x=>x.classList.remove('is-active'));
    document.querySelectorAll('.t-panel').forEach(x=>x.classList.remove('is-active'));
    b.classList.add('is-active');
    document.getElementById(b.dataset.tab)?.classList.add('is-active');
  });
});

// Follow/Unfollow functionality
@auth
document.querySelector('.follow-btn')?.addEventListener('click', function() {
    const btn = this;
    const teacherId = btn.dataset.teacherId;
    const isFollowing = btn.dataset.following === 'true';
    
    fetch(`/teacher/${teacherId}/toggle-follow`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            btn.dataset.following = data.is_following;
            const icon = btn.querySelector('i');
            const text = btn.querySelector('span');
            
            if (data.is_following) {
                icon.classList.remove('fa-heart');
                icon.classList.add('fa-heart-circle-check');
                text.textContent = '{{ __("front.following") }}';
            } else {
                icon.classList.remove('fa-heart-circle-check');
                icon.classList.add('fa-heart');
                text.textContent = '{{ __("front.follow") }}';
            }
            
            // Update followers count
            const followersBadge = document.querySelector('.t-badge span');
            if (followersBadge) {
                followersBadge.textContent = `${data.followers_count} {{ __("front.follower") }}`;
            }
        }
    })
    .catch(error => console.error('Error:', error));
});
@endauth
</script>
@endpush