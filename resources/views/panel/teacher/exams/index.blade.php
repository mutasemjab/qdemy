@extends('layouts.app')

@section('title', __('panel.exams'))
@section('page_title', __('panel.exams_management'))

@section('styles')
<style>
:root{
  --primary:#2b6cb0;
  --ink:#0f172a;
  --muted:#64748b;
  --bg:#f8fafc;
  --card:#ffffff;
  --line:#e5e7eb;
  --success:#0f766e;
  --error:#b91c1c;
}
.container-page{
    display:grid;
    gap:16px;
    max-width: 1300px;
    margin: auto;
    padding: 30px 0;
}
.header{
  background:var(--card);
  border:1px solid var(--line);
  border-radius:14px;
  padding:16px;
  display:flex;
  gap:12px;
  justify-content:space-between;
  align-items:end
}
.dropdown { position: relative; }
.dropdown-menu { display: none; }
.dropdown-menu.show {list-style: none;display: block; }

.header h2{margin:0;color:var(--ink);font-weight:800;font-size:22px}
.header p{margin:4px 0 0 0;color:var(--muted);font-size:13px}
.header .actions{display:flex;gap:8px;flex-wrap:wrap}
.btnx{
  display:inline-flex;align-items:center;gap:8px;
  border-radius:10px;padding:10px 14px;font-weight:700;font-size:14px;
  text-decoration:none;cursor:pointer;border:1px solid var(--line);background:#fff;color:var(--ink)
}
.btnx-primary{background:var(--primary);border-color:var(--primary);color:#fff}
.btnx:focus{outline:2px solid #cbd5e1;outline-offset:2px}

.filters{
  background:var(--card);
  border:1px solid var(--line);
  border-radius:14px;
  padding:14px
}
.f-row{display:grid;grid-template-columns:repeat(12,1fr);gap:10px;align-items:end}
.f-col-3{grid-column:span 3}
.f-col-2{grid-column:span 2}
.f-col-1{grid-column:span 1}
@media(max-width:1200px){.f-col-3{grid-column:span 4}.f-col-2{grid-column:span 3}}
@media(max-width:992px){.f-col-3,.f-col-2{grid-column:span 6}
    .container-page {
    padding: 30px 30px;
}
}
@media(max-width:576px){.f-col-3,.f-col-2,.f-col-1{grid-column:span 12}}
.form-label{font-size:12px;font-weight:700;color:var(--muted);margin-bottom:6px}
.form-select,.form-control{border:1px solid var(--line);border-radius:10px;padding:10px 12px;font-size:14px}

.grid{display:grid;grid-template-columns:repeat(12,1fr);gap:14px}
.col-4{grid-column:span 4}
@media(max-width:1200px){.col-4{grid-column:span 6}}
@media(max-width:768px){.col-4{grid-column:span 12}}

.cardx{
  background:var(--card);
  border:1px solid var(--line);
  border-radius:14px;
  display:flex;flex-direction:column;min-height:100%;
}

.dropdown-inline{position:relative;display:inline-block;vertical-align:middle}
.kebab{display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border:1px solid #e5e7eb;border-radius:10px;background:#fff;padding:0;line-height:1;cursor:pointer;transition:box-shadow .16s,border-color .16s,transform .16s}
.kebab i{font-size:16px;color:#374151}
.kebab:hover{border-color:#cfd4dc;box-shadow:0 6px 16px rgba(0,0,0,.06);transform:translateY(-1px)}
.kebab:focus{outline:none;box-shadow:0 0 0 3px rgba(0,85,210,.15)}
.dropdown-menu{min-width:180px;border:1px solid #e5e7eb;border-radius:12px;padding:8px;background:#fff;box-shadow:0 16px 40px rgba(15,23,42,.12)}
.dropdown-divider{margin:.35rem 0}
.text-danger{color:#dc2626}
.cardx-head{
  display:flex;justify-content:space-between;gap:8px;align-items:start;
  padding:12px 12px 0 12px
}
.status{
  font-size:11px;font-weight:800;border-radius:999px;padding:6px 10px;border:1px solid var(--line);color:var(--muted);background:#f1f5f9
}
.status.active{border-color:#99f6e4;background:#ecfdf5;color:var(--success)}
.status.inactive{border-color:#fecaca;background:#fef2f2;color:var(--error)}
.dropdown.position-static{align-self:end}
.kebab{border:1px solid var(--line);background:#fff;width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;color:var(--muted)}
.kebab:focus{outline:2px solid #cbd5e1;outline-offset:2px}
.dropdown-menu{border-radius:10px;border:1px solid var(--line);min-width:220px}
.dropdown-item{text-decoration: none;font-weight:600}
button.btn.btn-light.border.rounded-3.px-2 {
    background-color: #fff;
    cursor: pointer;
}
.cardx-body{padding:12px;display:flex;flex-direction:column;gap:8px}
.title{margin:2px 0 0 0;color:var(--ink);font-weight:800;font-size:16px}
.desc{margin:0;color:var(--muted);font-size:12px}
.meta{
  display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:4px
}
.meta .m{display:flex;align-items:center;gap:6px;border:1px solid var(--line);border-radius:8px;padding:8px 10px;color:#334155;font-size:12px;background:#fff}
.meta .m strong{color:var(--ink);font-weight:800;margin-inline-end:4px}

.cardx-foot{display:flex;gap:8px;padding:0 12px 12px 12px;margin-top:auto}
.btn-mini{
  text-decoration: none;flex:1 1 0%;border:1px solid var(--primary);color:var(--primary);
  background:#fff;border-radius:10px;padding:9px 12px;font-size:13px;font-weight:700;display:inline-flex;justify-content:center;align-items:center;gap:6px
}
.btn-mini.alt{border-color:#475569;color:#475569}

.empty{
  background:var(--card);border:1px solid var(--line);border-radius:14px;
  text-align:center;padding:48px
}
.empty h4{color:var(--ink);margin:8px 0}
.empty p{color:var(--muted);margin:0 0 14px 0}
.badge-pill{display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:999px;border:1px solid var(--line);background:#fff;color:#334155;font-size:11px;font-weight:700}
</style>
@endsection

@section('content')
<div class="container-fluid container-page">
  <div class="header">
    <div>
      <h2>{{ __('panel.exams_management') }}</h2>
      <p>{{ __('panel.manage_your_exams_desc') }}</p>
    </div>
    <div class="actions">
      <a href="{{ route('teacher.exams.create') }}" class="btnx btnx-primary"><i class="fas fa-plus"></i>{{ __('panel.create_exam') }}</a>
      <a href="{{ route('teacher.exams.index') }}" class="btnx"><i class="fas fa-rotate"></i>{{ __('panel.refresh') }}</a>
    </div>
  </div>

  <div class="filters">
    <form method="GET" action="{{ route('teacher.exams.index') }}" id="filterForm">
      <div class="f-row">
        <div class="f-col-3">
          <label class="form-label">{{ __('panel.course') }}</label>
          <select name="course_id" class="form-select">
            <option value="">{{ __('panel.all_courses') }}</option>
            @foreach($courses as $course)
              <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                {{ app()->getLocale() === 'ar' ? $course->title_ar : $course->title_en }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="f-col-3">
          <label class="form-label">{{ __('panel.subject') }}</label>
          <select name="subject_id" class="form-select">
            <option value="">{{ __('panel.all_subjects') }}</option>
            @foreach($subjects as $subject)
              <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                {{ app()->getLocale() === 'ar' ? $subject->name_ar : $subject->name_en }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="f-col-2">
          <label class="form-label">{{ __('panel.status') }}</label>
          <select name="status" class="form-select">
            <option value="">{{ __('panel.all_status') }}</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('panel.active') }}</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>{{ __('panel.inactive') }}</option>
          </select>
        </div>
        <div class="f-col-3">
          <label class="form-label">{{ __('panel.search') }}</label>
          <input type="text" name="search" class="form-control" placeholder="{{ __('panel.search_exams') }}" value="{{ request('search') }}">
        </div>
        <div class="f-col-1">
          <button type="submit" class="btnx btnx-primary w-100"><i class="fas fa-search"></i></button>
        </div>
      </div>

      @if(request('course_id') || request('subject_id') || request('status') || request('search'))
        <div class="mt-3 d-flex flex-wrap gap-2">
          @if(request('course_id'))
            <span class="badge-pill"><i class="fas fa-graduation-cap"></i>{{ __('panel.course') }}: {{ optional($courses->firstWhere('id',request('course_id')))->title_ar ?? optional($courses->firstWhere('id',request('course_id')))->title_en }}</span>
          @endif
          @if(request('subject_id'))
            <span class="badge-pill"><i class="fas fa-book"></i>{{ __('panel.subject') }}: {{ optional($subjects->firstWhere('id',request('subject_id')))->name_ar ?? optional($subjects->firstWhere('id',request('subject_id')))->name_en }}</span>
          @endif
          @if(request('status'))
            <span class="badge-pill"><i class="fas fa-toggle-on"></i>{{ __('panel.status') }}: {{ __('panel.'.request('status')) }}</span>
          @endif
          @if(request('search'))
            <span class="badge-pill"><i class="fas fa-magnifying-glass"></i>{{ __('panel.search') }}: {{ request('search') }}</span>
          @endif
        </div>
      @endif
    </form>
  </div>

  @if($exams->count() > 0)
    <div class="grid">
      @foreach($exams as $exam)
        <div class="col-4">
          <div class="cardx">
            <div class="cardx-head">
              <span class="status {{ $exam->is_active ? 'active' : 'inactive' }}">
                {{ $exam->is_active ? __('panel.active') : __('panel.inactive') }}
              </span>
<div class="dropdown">
  <button class="btn btn-light border rounded-3 px-2" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="More">
    <i class="fas fa-ellipsis-vertical"></i>
  </button>
  <ul class="dropdown-menu dropdown-menu-end">
    <li><a class="dropdown-item" href="#">عرض</a></li>
    <li><a class="dropdown-item" href="#">تعديل</a></li>
    <li><hr class="dropdown-divider"></li>
    <li><a class="dropdown-item text-danger" href="#">حذف</a></li>
  </ul>
</div>
            </div>

            <div class="cardx-body">
              <h5 class="title">{{ app()->getLocale() === 'ar' ? $exam->title_ar : $exam->title_en }}</h5>
              @if($exam->description_en || $exam->description_ar)
                <p class="desc">{{ Str::limit(app()->getLocale() === 'ar' ? $exam->description_ar : $exam->description_en, 110) }}</p>
              @endif

              <div class="meta">
                @if($exam->subject)
                  <div class="m"><i class="fas fa-book"></i><strong>{{ __('panel.subject') }}:</strong>{{ app()->getLocale() === 'ar' ? $exam->subject->name_ar : $exam->subject->name_en }}</div>
                @endif
                @if($exam->course)
                  <div class="m"><i class="fas fa-graduation-cap"></i><strong>{{ __('panel.course') }}:</strong>{{ app()->getLocale() === 'ar' ? $exam->course->title_ar : $exam->course->title_en }}</div>
                @endif
                <div class="m"><i class="fas fa-clock"></i><strong>{{ __('panel.duration') }}:</strong>{{ $exam->duration_minutes ? $exam->duration_minutes . ' ' . __('panel.minutes') : __('panel.unlimited') }}</div>
                <div class="m"><i class="fas fa-question-circle"></i><strong>{{ __('panel.questions') }}:</strong>{{ $exam->questions_count ?? $exam->questions()->count() }}</div>
                <div class="m"><i class="fas fa-star"></i><strong>{{ __('panel.total_grade') }}:</strong>{{ $exam->total_grade ?? 0 }}</div>
                @if($exam->attempts()->count() > 0)
                  <div class="m"><i class="fas fa-users"></i><strong>{{ __('panel.attempts') }}:</strong>{{ $exam->attempts()->count() }}</div>
                @endif
              </div>
            </div>

            <div class="cardx-foot">
              <a href="{{ route('teacher.exams.exam_questions.index', $exam) }}" class="btn-mini"><i class="fas fa-cogs"></i>{{ __('panel.manage') }}</a>
              <a href="{{ route('teacher.exams.results', $exam) }}" class="btn-mini alt"><i class="fas fa-chart-line"></i>{{ __('panel.results') }}</a>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <div class="d-flex justify-content-center mt-3">
      {{ $exams->appends(request()->query())->links() }}
    </div>
  @else
    <div class="empty">
      <div class="mb-2"><i class="fas fa-clipboard-list fa-3x" style="color:#94a3b8"></i></div>
      <h4>{{ __('panel.no_exams_found') }}</h4>
      <p>{{ __('panel.no_exams_created_yet') }}</p>
      <a href="{{ route('teacher.exams.create') }}" class="btnx btnx-primary"><i class="fas fa-plus"></i>{{ __('panel.create_first_exam') }}</a>
    </div>
  @endif
</div>
@endsection

@push('scripts')
<script>
$(function(){
  $('#filterForm select').on('change',function(){ $('#filterForm').submit(); });
  let t; $('input[name="search"]').on('input',function(){ clearTimeout(t); t=setTimeout(function(){ $('#filterForm').submit(); },500); });
  $('.delete-exam').on('click',function(e){
    e.preventDefault();
    const form=$(this).closest('form');
    Swal.fire({
      title:'{{ __("panel.confirm_delete") }}',
      text:'{{ __("panel.delete_exam_warning") }}',
      icon:'warning',
      showCancelButton:true,
      confirmButtonColor:'#b91c1c',
      cancelButtonColor:'#64748b',
      confirmButtonText:'{{ __("panel.yes_delete") }}',
      cancelButtonText:'{{ __("panel.cancel") }}'
    }).then((r)=>{ if(r.isConfirmed){ form.submit(); } });
  });
});
</script>

<script>
(function(){
  var wrappers=document.querySelectorAll('.dropdown-inline');

  function initWithBootstrap(wrap){
    var btn=wrap.querySelector('[data-bs-toggle="dropdown"]');
    if(!btn||!window.bootstrap||!bootstrap.Dropdown) return false;
    new bootstrap.Dropdown(btn,{autoClose:'outside'});
    return true;
  }

  function initNative(wrap){
    var btn=wrap.querySelector('.kebab');
    var menu=wrap.querySelector('.dropdown-menu');
    if(!btn||!menu) return;
    function hide(){ menu.classList.remove('show'); btn.setAttribute('aria-expanded','false'); }
    function show(){
      document.querySelectorAll('.dropdown-inline .dropdown-menu.show').forEach(function(m){ m.classList.remove('show'); });
      menu.classList.add('show');
      btn.setAttribute('aria-expanded','true');
    }
    btn.addEventListener('click',function(e){
      e.stopPropagation();
      menu.classList.contains('show') ? hide() : show();
    });
  }

  wrappers.forEach(function(wrap){
    if(!initWithBootstrap(wrap)) initNative(wrap);
  });

  document.addEventListener('click',function(){
    document.querySelectorAll('.dropdown-inline .dropdown-menu.show').forEach(function(m){ m.classList.remove('show'); });
    document.querySelectorAll('.dropdown-inline .kebab[aria-expanded="true"]').forEach(function(b){ b.setAttribute('aria-expanded','false'); });
  });
  document.addEventListener('keydown',function(e){
    if(e.key==='Escape'){
      document.querySelectorAll('.dropdown-inline .dropdown-menu.show').forEach(function(m){ m.classList.remove('show'); });
      document.querySelectorAll('.dropdown-inline .kebab[aria-expanded="true"]').forEach(function(b){ b.setAttribute('aria-expanded','false'); });
    }
  });
})();
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@endpush
