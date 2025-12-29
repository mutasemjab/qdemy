@extends('layouts.app')
@section('title', __('panel.my_courses'))

@section('content')
<section class="ud-scope">
  <div class="ud-content">
    <div class="ud-panel show" id="courses">
      <div class="ud-title-actions">
        <h1 class="ud-title">{{ __('panel.my_courses') }}</h1>
        <a href="{{ route('teacher.courses.create') }}" class="ud-btn-primary">
          <i class="fas fa-plus"></i> {{ __('panel.add_course') }}
        </a>
      </div>

      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      @if($courses->count() > 0 || request()->filled('status') || request()->filled('subject_id') || request()->filled('search'))
        @php
          $subjects = $courses->pluck('subject.name_ar','subject_id')->filter()->unique();
        @endphp

        <div class="ud-toolbar">
          <div class="ud-search">
            <form method="GET" class="d-flex gap-2" style="width: 100%;">
              <input type="text" name="search" id="courseSearch" placeholder="{{ __('panel.search') }}…" value="{{ request('search') }}" style="flex: 1;">
              <i class="fas fa-magnifying-glass" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #6b7280;"></i>
            </form>
          </div>
          <div class="ud-filters">
            <select id="statusFilter" name="status" onchange="applyFilters()">
              <option value="all" {{ request('status', 'all') === 'all' ? 'selected' : '' }}>{{ __('panel.all_status') }}</option>
              <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('messages.status_pending') }}</option>
              <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>{{ __('messages.status_accepted') }}</option>
              <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>{{ __('messages.status_rejected') }}</option>
            </select>
            <select id="subjectFilter" name="subject_id" onchange="applyFilters()">
              <option value="all" {{ request('subject_id', 'all') === 'all' ? 'selected' : '' }}>{{ __('panel.all_subjects') }}</option>
              @foreach($subjects as $sid => $sname)
                <option value="{{ $sid }}" {{ request('subject_id') == $sid ? 'selected' : '' }}>{{ $sname }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="courses-grid" id="coursesGrid">
          @foreach($courses as $course)
            <div class="course-card"
                 data-title="{{ Str::lower($course->title_ar) }}"
                 data-subject="{{ $course->subject_id }}"
                 data-date="{{ $course->created_at->timestamp }}"
                 data-price="{{ $course->selling_price }}">
              <div class="course-image">
                <img src="{{ $course->photo ? asset('assets/admin/uploads/' . $course->photo) : asset('assets_front/images/course-default.png') }}" alt="{{ $course->title_ar }}">
                <div class="course-actions">
                  <a href="{{ route('teacher.courses.show', $course) }}" class="btn-action" title="{{ __('panel.view') }}">
                    <i class="fas fa-eye"></i>
                  </a>
                  <a href="{{ route('teacher.courses.edit', $course) }}" class="btn-action" title="{{ __('panel.edit') }}">
                    <i class="fas fa-edit"></i>
                  </a>
                  <button type="button" onclick="deleteCourse({{ $course->id }})" class="btn-action btn-danger" title="{{ __('panel.delete') }}">
                    <i class="fas fa-trash"></i>
                  </button>
                </div>
                <span class="course-badge">{{ $course->subject->name_ar ?? __('panel.no_subject') }}</span>
                <span class="course-status-badge status-{{ $course->status }}">
                  @if($course->status === 'pending')
                    <i class="fas fa-clock"></i> {{ __('messages.status_pending') }}
                  @elseif($course->status === 'accepted')
                    <i class="fas fa-check-circle"></i> {{ __('messages.status_accepted') }}
                  @else
                    <i class="fas fa-times-circle"></i> {{ __('messages.status_rejected') }}
                  @endif
                </span>
              </div>

              <div class="course-content">
                <h3 class="course-title">{{ $course->title_ar }}</h3>
                <p class="course-description">{{ Str::limit($course->description_ar, 110) }}</p>
                <div class="course-meta">
                  <div class="course-price">
                    <span class="price">{{ number_format($course->selling_price, 2) }} JD</span>
                  </div>
                  <div class="course-date">
                    <i class="far fa-calendar"></i>
                    <span>{{ $course->created_at->format('Y-m-d') }}</span>
                  </div>
                </div>
                <div class="course-buttons">
                  <a href="{{ route('teacher.courses.sections.index', $course) }}" class="btn-secondary">{{ __('panel.manage_content') }}</a>
                  <a href="{{ route('teacher.courses.show', $course) }}" class="btn-primary">{{ __('panel.view_details') }}</a>
                </div>
              </div>
            </div>
          @endforeach
        </div>

        <div class="pagination-wrapper">
          {{ $courses->links() }}
        </div>
      @else
        <div class="empty-state">
          <div class="empty-icon"><i class="fas fa-graduation-cap"></i></div>
          <h3>{{ __('panel.no_courses_yet') }}</h3>
          <p>{{ __('panel.start_creating_courses') }}</p>
          <a href="{{ route('teacher.courses.create') }}" class="btn-primary"><i class="fas fa-plus"></i> {{ __('panel.create_first_course') }}</a>
        </div>
      @endif
    </div>
  </div>
</section>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="overflow:hidden;border-radius:14px">
      <div class="modal-body">
        <p>{{ __('panel.delete_course_warning') }}</p>
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

.ud-scope .courses-grid{display:grid!important;grid-template-columns:repeat(3,minmax(0,1fr))!important;gap:18px!important}
.ud-scope .course-card{background:#fff!important;border:1px solid #eef1f6!important;border-radius:16px!important;overflow:hidden!important;transition:transform .18s ease,box-shadow .18s ease!important}
.ud-scope .course-card:hover{transform:translateY(-2px)!important;box-shadow:0 14px 30px rgba(0,0,0,.08)!important}
.ud-scope .course-image{position:relative!important;height:180px!important;overflow:hidden!important}
.ud-scope .course-image img{width:100%!important;height:100%!important;object-fit:cover!important;display:block!important;transform:scale(1)!important;transition:transform .25s ease!important}
.ud-scope .course-card:hover .course-image img{transform:scale(1.04)!important}
.ud-scope .course-actions{position:absolute!important;top:10px!important;right:10px!important;display:flex!important;gap:6px!important;opacity:0!important;transition:opacity .2s ease!important}
.ud-scope .course-card:hover .course-actions{opacity:1!important}
.ud-scope .btn-action{cursor: pointer;text-decoration: none;width:34px!important;height:34px!important;border-radius:50%!important;border:1px solid #e5e7eb!important;background:#fff!important;display:grid!important;place-items:center!important;color:#111827!important}
.ud-scope .btn-action:hover{border-color:#0055D2!important;color:#0055D2!important}
.ud-scope .btn-action.btn-danger:hover{border-color:#dc2626!important;color:#dc2626!important}
.ud-scope .course-badge{position:absolute!important;left:10px!important;top:10px!important;background:#0ea5e9!important;color:#fff!important;border-radius:999px!important;padding:6px 10px!important;font-size:12px!important;font-weight:700!important}
.ud-scope .course-status-badge{position:absolute!important;right:10px!important;bottom:10px!important;border-radius:6px!important;padding:6px 10px!important;font-size:11px!important;font-weight:700!important;display:inline-flex!important;align-items:center!important;gap:4px!important}
.ud-scope .course-status-badge.status-pending{background:#fef3c7!important;color:#92400e!important}
.ud-scope .course-status-badge.status-accepted{background:#d1fae5!important;color:#065f46!important}
.ud-scope .course-status-badge.status-rejected{background:#fee2e2!important;color:#991b1b!important}

.ud-scope .course-content{padding:16px 16px 18px!important}
.ud-scope .course-title{margin:0 0 6px!important;font-size:18px!important;font-weight:800!important;color:#0f172a!important}
.ud-scope .course-description{margin:0 0 14px!important;color:#475569!important;font-size:14px!important;line-height:1.5!important}
.ud-scope .course-meta{display:flex!important;justify-content:space-between!important;align-items:center!important;margin-bottom:14px!important;padding-bottom:12px!important;border-bottom:1px dashed #e5e7eb!important}
.ud-scope .price{font-size:16px!important;font-weight:800!important;color:#059669!important}
.ud-scope .course-date{display:inline-flex!important;align-items:center!important;gap:8px!important;color:#6b7280!important;font-size:13px!important}
.ud-scope .course-buttons{display:flex!important;gap:10px!important}
.ud-scope .btn-primary,.ud-scope .btn-secondary{flex:1!important;text-align:center!important;text-decoration:none!important;border-radius:10px!important;font-weight:800!important;padding:10px 12px!important;transition:transform .15s ease,box-shadow .15s ease!important}
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

/* Card look */
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

/* Ensure hidden when not shown */
.modal:not(.show){ display:none !important; }

@media (max-width:1024px){
  .ud-scope .ud-toolbar{flex-direction:column!important;align-items:stretch!important}
  .ud-scope .ud-filters{width:100%!important}
  .ud-scope .ud-filters select{flex:1!important}
}
@media (max-width:768px){
  .ud-scope .courses-grid{grid-template-columns:1fr!important}
  .ud-scope .ud-title-actions{flex-direction:column!important;align-items:stretch!important}
  .ud-scope .ud-btn-primary{justify-content:center!important}
  .ud-scope .course-image{height:170px!important}
  .ud-scope .course-title{font-size:16px!important}
  .ud-scope .course-description{font-size:13px!important}
}

#deleteModal .modal-body{
  display:flex !important;
  align-items:center !important;
  gap:14px !important;
  padding:22px 18px !important;
  text-align:start !important;
    background-color: #f9fafc;
}
#deleteModal .modal-body p{
  margin:0 !important;
  font-size:15px !important;
  line-height:1.6 !important;
  color:#374151 !important;
}

#deleteModal .confirm-icon{
  width:44px !important;
  height:44px !important;
  border-radius:50% !important;
  display:flex !important;
  align-items:center !important;
  justify-content:center !important;
  background:rgba(220,53,69,.12) !important;
  color:#dc2626 !important;
  font-size:18px !important;
  flex:0 0 44px !important;
}

#deleteModal .modal-footer{
  display:flex !important;
  gap:10px !important;
  justify-content:flex-end !important;
}

#deleteModal .modal-footer .btn{
  border-radius:10px !important;
  padding:10px 14px !important;
  font-weight:700 !important;
  font-size:14px !important;
  transition:transform .16s ease, box-shadow .16s ease, background .16s ease, color .16s ease !important;
  cursor: pointer;
}

#deleteModal .modal-footer .btn.btn-secondary{
  background:#ffffff !important;
  color:#111827 !important;
  border:1px solid #e5e7eb !important;
  box-shadow:0 1px 0 rgba(0,0,0,.02) !important;
}
#deleteModal .modal-footer .btn.btn-secondary:hover{
  background:#f3f4f6 !important;
  transform:translateY(-1px) !important;
  box-shadow:0 6px 16px rgba(0,0,0,.06) !important;
}

#deleteModal .modal-footer .btn.btn-danger{
  background:#ef4444 !important;
  border:0 !important;
  color:#fff !important;
  box-shadow:0 8px 20px rgba(239,68,68,.25) !important;
}
#deleteModal .modal-footer .btn.btn-danger:hover{
  background:#dc2626 !important;
  transform:translateY(-1px) !important;
  box-shadow:0 10px 24px rgba(220,38,38,.3) !important;
}
#deleteModal .modal-footer .btn.btn-danger:disabled{
  opacity:.7 !important;
  transform:none !important;
  box-shadow:none !important;
}

#deleteModal .modal-header .modal-title{
  font-size:16px !important;
  font-weight:800 !important;
  color:#111827 !important;
}
#deleteModal .btn-close{
  opacity:.8 !important;
}
#deleteModal .btn-close:hover{
  opacity:1 !important;
}

@media (max-width:600px){
  #deleteModal .modal-dialog{
    max-width:92% !important;
  }
  #deleteModal .modal-body{
    align-items:flex-start !important;
  }
  #deleteModal .modal-footer{
    flex-direction:column-reverse !important;
  }
  #deleteModal .modal-footer .btn{
    width:100% !important;
  }
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
function deleteCourse(courseId){
  const form=document.getElementById('deleteForm');
  form.action=`/teacher/courses/${courseId}`;
  openDeleteModal();
}

function applyFilters(){
  const status = document.getElementById('statusFilter').value;
  const subject = document.getElementById('subjectFilter').value;
  const search = document.getElementById('courseSearch').value;

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
