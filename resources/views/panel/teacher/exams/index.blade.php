@extends('layouts.app')

@section('title', __('panel.exams'))
@section('page_title', __('panel.exams_management'))

@section('content')
<section class="ud-scope">
<div class="ud-content">
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
              <div class="exam-actions">
                <a href="{{ route('teacher.exams.show', $exam) }}" class="btn-action" title="{{ __('panel.view') }}">
                  <i class="fas fa-eye"></i>
                </a>
                <a href="{{ route('teacher.exams.edit', $exam) }}" class="btn-action" title="{{ __('panel.edit') }}">
                  <i class="fas fa-edit"></i>
                </a>
                <button type="button" onclick="deleteExam({{ $exam->id }})" class="btn-action btn-danger" title="{{ __('panel.delete') }}">
                  <i class="fas fa-trash"></i>
                </button>
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
</div>
</section>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="overflow:hidden;border-radius:14px">
      <div class="modal-body">
        <p>{{ __('panel.delete_exam_warning') }}</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('panel.cancel') }}</button>
        <form id="deleteForm" method="POST" style="display:inline;">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">{{ __('panel.delete') }}</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

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
.header h2{margin:0;color:var(--ink);font-weight:800;font-size:22px}
.header p{margin:4px 0 0 0;color:var(--muted);font-size:19px}
.header .actions{display:flex;gap:8px;flex-wrap:wrap}
.btnx{
  display:inline-flex;align-items:center;gap:8px;
  border-radius:10px;padding:10px 14px;font-weight:700;font-size:20px;
  text-decoration:none;cursor:pointer;border:1px solid var(--line);background:#fff;color:var(--ink)
}
.btnx-primary{background:var(--primary);border-color:var(--primary);color:#fff}
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
@media(max-width:992px){.f-col-3,.f-col-2{grid-column:span 6}.container-page {padding: 30px 30px;}}
@media(max-width:576px){.f-col-3,.f-col-2,.f-col-1{grid-column:span 12}}
.form-label{font-size:19px;font-weight:700;color:var(--muted);margin-bottom:6px}
.form-select,.form-control{border:1px solid var(--line);border-radius:10px;padding:10px 12px;font-size:20px}
.grid{display:grid;grid-template-columns:repeat(12,1fr);gap:14px}
.col-4{grid-column:span 4}
@media(max-width:1200px){.col-4{grid-column:span 6}}
@media(max-width:768px){.col-4{grid-column:span 12}}
.cardx{position:relative;background:var(--card);border:1px solid var(--line);border-radius:14px;display:flex;flex-direction:column;min-height:100%;overflow:visible;transition:transform .18s ease,box-shadow .18s ease}
.cardx:hover{transform:translateY(-2px);box-shadow:0 14px 30px rgba(0,0,0,.08)}
.cardx-head{position:relative;display:flex;justify-content:space-between;gap:8px;align-items:flex-start;padding:12px}
.status{font-size:19px;font-weight:800;border-radius:999px;padding:6px 10px;border:1px solid var(--line);color:var(--muted);background:#f1f5f9}
.status.active{border-color:#99f6e4;background:#ecfdf5;color:var(--success)}
.status.inactive{border-color:#fecaca;background:#fef2f2;color:var(--error)}
.cardx-body{padding:12px;display:flex;flex-direction:column;gap:8px}
.title{margin:2px 0 0 0;color:var(--ink);font-weight:800;font-size:24px}
.desc{margin:0;color:var(--muted);font-size:20px}
.meta{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:4px}
.meta .m{display:flex;align-items:center;gap:6px;border:1px solid var(--line);border-radius:8px;padding:8px 10px;color:#334155;font-size:20px;background:#fff}
.meta .m strong{color:var(--ink);font-weight:800;margin-inline-end:4px}
.cardx-foot{display:flex;gap:8px;padding:0 12px 12px 12px;margin-top:auto}
.btn-mini{text-decoration: none;flex:1 1 0%;border:1px solid var(--primary);color:var(--primary);background:#fff;border-radius:10px;padding:9px 12px;font-size:20px;font-weight:700;display:inline-flex;justify-content:center;align-items:center;gap:6px}
.btn-mini.alt{border-color:#475569;color:#475569}
.empty{background:var(--card);border:1px solid var(--line);border-radius:14px;text-align:center;padding:48px}
.empty h4{color:var(--ink);margin:8px 0}
.empty p{color:var(--muted);margin:0 0 14px 0}

.ud-scope .ud-title-actions{display:flex!important;justify-content:space-between!important;align-items:center!important;margin-bottom:18px!important;gap:12px!important}
.ud-scope .ud-title{margin:0!important;font-weight:800!important;font-size:22px!important}
.ud-scope .ud-btn-primary{display:inline-flex!important;align-items:center!important;gap:8px!important;background:#0055D2!important;color:#fff!important;text-decoration:none!important;border-radius:10px!important;padding:10px 14px!important;font-weight:700!important;transition:transform .15s ease,box-shadow .15s ease!important}
.ud-scope .ud-btn-primary:hover{transform:translateY(-1px)!important;box-shadow:0 8px 22px rgba(0,85,210,.18)!important}

.ud-scope .ud-toolbar{display:flex!important;justify-content:space-between!important;align-items:center!important;gap:12px!important;margin:12px 0 18px!important}
.ud-scope .ud-search{position:relative!important;flex:1!important;min-width:240px!important}
.ud-scope .ud-search input{width:100%!important;border:1px solid #e3e7ef!important;border-radius:10px!important;padding:12px 40px 12px 12px!important;font:inherit!important}
.ud-scope .ud-search i{position:absolute!important;right:12px!important;top:50%!important;transform:translateY(-50%)!important;color:#6b7280!important}
.ud-scope .ud-filters{display:flex!important;gap:10px!important}
.ud-scope .ud-filters select{border:1px solid #e3e7ef!important;border-radius:10px!important;padding:10px 12px!important;min-width:160px!important;background:#fff!important}

.ud-scope .exams-grid{display:grid!important;grid-template-columns:repeat(2,minmax(0,1fr))!important;gap:18px!important}
.ud-scope .exam-card{background:#fff!important;border:1px solid #eef1f6!important;border-radius:16px!important;overflow:hidden!important;transition:transform .18s ease,box-shadow .18s ease!important}
.ud-scope .exam-card:hover{transform:translateY(-2px)!important;box-shadow:0 14px 30px rgba(0,0,0,.08)!important}

.ud-scope .exam-header{position:relative!important;padding:14px!important;display:flex!important;justify-content:space-between!important;align-items:center!important;background:#f9fafb!important;border-bottom:1px solid #e5e7eb!important}
.ud-scope .exam-badge{background:#0ea5e9!important;color:#fff!important;border-radius:999px!important;padding:6px 10px!important;font-size:20px!important;font-weight:700!important}
.ud-scope .exam-status-badge{border-radius:6px!important;padding:6px 10px!important;font-size:19px!important;font-weight:700!important;display:inline-flex!important;align-items:center!important;gap:4px!important}
.ud-scope .exam-status-badge.status-active{background:#d1fae5!important;color:#065f46!important}
.ud-scope .exam-status-badge.status-inactive{background:#fee2e2!important;color:#991b1b!important}

.ud-scope .exam-content{padding:16px!important;display:flex!important;flex-direction:column!important;gap:14px!important}
.ud-scope .exam-title{margin:0!important;font-size:24px!important;font-weight:800!important;color:#0f172a!important}
.ud-scope .exam-description{margin:0!important;color:#475569!important;font-size:19px!important;line-height:1.5!important}

.ud-scope .exam-meta{display:grid!important;grid-template-columns:1fr 1fr!important;gap:10px!important}
.ud-scope .meta-item{display:flex!important;align-items:center!important;gap:6px!important;font-size:19px!important;color:#64748b!important;padding:8px!important;background:#f9fafb!important;border-radius:8px!important}
.ud-scope .meta-item i{color:#0055D2!important}
.ud-scope .meta-item strong{color:#0f172a!important}

.exam-actions{display:flex!important;gap:6px!important;opacity:0!important;transition:opacity .2s ease!important}
.cardx:hover .exam-actions{opacity:1!important}
.btn-action{cursor:pointer!important;text-decoration:none!important;width:36px!important;height:36px!important;border-radius:50%!important;border:1px solid #e5e7eb!important;background:#fff!important;display:flex!important;align-items:center!important;justify-content:center!important;color:#374151!important;transition:all .2s!important;font-size:24px!important}
.btn-action:hover{border-color:#0055D2!important;color:#0055D2!important;box-shadow:0 6px 16px rgba(0,0,0,.06);transform:translateY(-1px)}
.btn-action.btn-danger:hover{border-color:#dc2626!important;color:#dc2626!important}

.ud-scope .exam-buttons{display:flex!important;gap:10px!important}
.ud-scope .btn-primary,.ud-scope .btn-secondary{flex:1!important;text-align:center!important;text-decoration:none!important;border-radius:10px!important;font-weight:800!important;padding:10px 12px!important;font-size:19px!important;transition:transform .15s ease,box-shadow .15s ease!important;display:inline-flex!important;align-items:center!important;justify-content:center!important;gap:6px!important}
.ud-scope .btn-primary{background:#0055D2!important;color:#fff!important}
.ud-scope .btn-primary:hover{box-shadow:0 10px 24px rgba(0,85,210,.18)!important;transform:translateY(-1px)!important}
.ud-scope .btn-secondary{background:#111827!important;color:#fff!important}
.ud-scope .btn-secondary:hover{box-shadow:0 10px 24px rgba(17,24,39,.18)!important;transform:translateY(-1px)!important}

.ud-scope .empty-state{text-align:center!important;padding:60px 20px!important;background:#fff!important;border:1px dashed #e5e7eb!important;border-radius:16px!important}
.ud-scope .empty-icon{font-size:44px!important;color:#cbd5e1!important;margin-bottom:10px!important}
.ud-scope .empty-state h3{margin:6px 0 6px!important;color:#0f172a!important}
.ud-scope .empty-state p{margin:0 0 18px!important;color:#6b7280!important}
.ud-scope .pagination-wrapper{display:flex!important;justify-content:center!important;margin-top:18px!important}

.ud-scope .alert{padding:12px 14px!important;border-radius:10px!important;margin-bottom:14px!important}
.ud-scope .alert-success{background:#ecfdf5!important;color:#065f46!important;border:1px solid #a7f3d0!important}
.ud-scope .alert-danger{background:#fef2f2!important;color:#991b1b!important;border:1px solid #fecaca!important}

.modal:not(.show){display:none!important}
.modal{
  position:fixed !important;
  inset:0 !important;
  display:none !important;
  align-items:center !important;
  justify-content:center !important;
  background:rgba(17,24,39,.5) !important;
  backdrop-filter:blur(2px) !important;
  z-index:9999 !important;
  opacity:0 !important;
  transition:opacity .18s ease !important;
}
.modal.show{ display:flex !important; opacity:1 !important; }

.ud-content {
    background: #fff;
    border-radius: 20px;
    padding: 18px;
    max-width: 1300px;
    width: 100%;
    margin: auto;
}

.modal-dialog{
  width:100% !important;
  max-width:520px !important;
  margin:0 !important;
  transform:scale(.96) translateY(10px) !important;
  opacity:0 !important;
  transition:transform .22s ease, opacity .22s ease !important;
}
.modal.show .modal-dialog{
  transform:scale(1) translateY(0) !important;
  opacity:1 !important;
}

.modal-content{
  border:0 !important;
  border-radius:16px !important;
  overflow:hidden !important;
  box-shadow:0 24px 64px rgba(0,0,0,.28) !important;
}
.modal-header{
  padding:14px 16px !important;
  background:#f8fafc !important;
  border-bottom:1px solid #e5e7eb !important;
}
.modal-title{ font-weight:700 !important; }
.btn-close{ filter:grayscale(1) !important; opacity:.7 !important; }
.modal-body{ padding:18px 16px !important; }
.modal-footer{
  padding:12px 16px !important;
  background:#f9fafb !important;
  border-top:1px solid #e5e7eb !important;
}

.modal:not(.show){ display:none !important; }

@media (max-width:1024px){
  .ud-scope .ud-toolbar{flex-direction:column!important;align-items:stretch!important}
  .ud-scope .ud-filters{width:100%!important}
  .ud-scope .ud-filters select{flex:1!important}
  .ud-scope .exams-grid{grid-template-columns:1fr!important}
}
@media (max-width:768px){
  .ud-scope .exams-grid{grid-template-columns:1fr!important}
  .ud-scope .ud-title-actions{flex-direction:column!important;align-items:stretch!important}
  .ud-scope .ud-btn-primary{justify-content:center!important}
  .ud-scope .exam-meta{grid-template-columns:1fr!important}
  .ud-scope .exam-title{font-size:24px!important}
  .ud-scope .exam-description{font-size:20px!important}
}
</style>
@endsection

@section('scripts')
<script>
function openDeleteModal(){
  const modal=document.getElementById('deleteModal');
  modal.style.display='flex';
  document.body.style.overflow='hidden';
  requestAnimationFrame(()=>{ modal.classList.add('show'); });
}
function closeDeleteModal(){
  const modal=document.getElementById('deleteModal');
  modal.classList.remove('show');
  modal.addEventListener('transitionend',function onEnd(e){
    if(e.propertyName==='opacity'){
      modal.style.display='none';
      document.body.style.overflow='';
      modal.removeEventListener('transitionend',onEnd);
    }
  });
}
function deleteExam(examId){
  const form=document.getElementById('deleteForm');
  form.action=`{{ route('teacher.exams.destroy', ':id') }}`.replace(':id', examId);
  openDeleteModal();
}

function applyFilters(){
  const status = document.getElementById('statusFilter').value;
  const subject = document.getElementById('subjectFilter').value;
  const search = document.getElementById('examSearch').value;

  let url = new URL(window.location.href);

  if (status && status !== 'all') {
    url.searchParams.set('status', status);
  } else {
    url.searchParams.delete('status');
  }

  if (subject && subject !== 'all') {
    url.searchParams.set('subject_id', subject);
  } else {
    url.searchParams.delete('subject_id');
  }

  if (search) {
    url.searchParams.set('search', search);
  } else {
    url.searchParams.delete('search');
  }

  window.location.href = url.toString();
}

document.addEventListener('DOMContentLoaded',function(){
  document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(btn=>{
    btn.addEventListener('click',closeDeleteModal);
  });
  const modal=document.getElementById('deleteModal');
  if(modal){
    modal.addEventListener('click',function(e){
      if(e.target===modal) closeDeleteModal();
    });
    document.addEventListener('keydown',function(e){
      if(e.key==='Escape') closeDeleteModal();
    });
  }

  const delForm=document.getElementById('deleteForm');
  if(delForm){
    delForm.addEventListener('submit',function(e){
      e.preventDefault();
      const form=this;
      const submitBtn=form.querySelector('button[type="submit"]');
      submitBtn.disabled=true;
      submitBtn.innerHTML='<i class="fas fa-spinner fa-spin"></i> {{ __("panel.deleting") }}...';
      fetch(form.action,{
        method:'POST',
        headers:{
          'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Content-Type':'application/json'
        },
        body:JSON.stringify({_method:'DELETE'})
      })
      .then(r=>r.json())
      .then(data=>{
        if(data.success){ location.reload(); }
        else{
          alert(data.message || '{{ __("panel.error_occurred") }}');
          submitBtn.disabled=false;
          submitBtn.textContent='{{ __("panel.delete") }}';
        }
      })
      .catch(()=>{
        alert('{{ __("panel.error_occurred") }}');
        submitBtn.disabled=false;
        submitBtn.textContent='{{ __("panel.delete") }}';
      });
    });
  }
});
</script>
@endsection
