@extends('layouts.app')
@section('title', __('panel.manage_course_content'))

@section('content')
<section class="ud-wrap">
  <aside class="ud-menu">
    <div class="ud-user">
      <img data-src="{{ auth()->user()->photo_url }}" alt="">
      <div>
        <h3>{{ auth()->user()->name }}</h3>
        <span>{{ auth()->user()->email }}</span>
      </div>
    </div>
    <a href="{{ route('teacher.courses.index') }}" class="ud-item">
      <i class="fas fa-arrow-left"></i>
      <span>{{ __('panel.back_to_courses') }}</span>
    </a>
  </aside>

  <div class="ud-content">
    <div class="ud-panel show">
      <div class="course-header">
        <div class="course-info">
          <h1 class="course-title">{{ $course->title_ar }}</h1>
          <p class="course-subject">{{ $course->subject->name_ar ?? __('panel.no_subject') }}</p>
        </div>
        <div class="course-actions">
          <a href="{{ route('teacher.courses.sections.create', $course) }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> {{ __('panel.add_section') }}
          </a>
          <a href="{{ route('teacher.courses.contents.create', $course) }}" class="btn btn-secondary">
            <i class="fas fa-file-circle-plus"></i> {{ __('panel.add_content') }}
          </a>
        </div>
      </div>

      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif

      <div class="course-structure">
        @if($directContents->count() > 0)
          <div class="content-group direct-contents">
            <div class="group-header">
              <h3><i class="fas fa-file"></i> {{ __('panel.course_materials') }}</h3>
            </div>
            <div class="contents-list">
              @foreach($directContents as $content)
                @include('panel.teacher.courses.partials.content-item', ['content' => $content])
              @endforeach
            </div>
          </div>
        @endif

        @forelse($course->sections as $parentSection)
          <div class="section-group" id="section-{{ $parentSection->id }}">
            <div class="section-header">
              <div class="section-info">
                <h3><i class="fas fa-folder"></i>{{ $parentSection->title_ar }}</h3>
                <span class="content-count">
                  {{ $parentSection->contents->count() + $parentSection->children->sum(function($child){ return $child->contents->count(); }) }} {{ __('panel.items') }}
                </span>
              </div>
              <div class="section-actions">
                <a href="{{ route('teacher.courses.contents.create', [$course, 'section_id' => $parentSection->id]) }}" class="btn-action" title="{{ __('panel.add_content') }}">
                  <i class="fas fa-plus"></i>
                </a>
                <a href="{{ route('teacher.courses.sections.edit', [$course, $parentSection]) }}" class="btn-action" title="{{ __('panel.edit_section') }}">
                  <i class="fas fa-pen"></i>
                </a>
                <button onclick="deleteSection({{ $parentSection->id }})" class="btn-action btn-danger" title="{{ __('panel.delete_section') }}">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            </div>

            @if($parentSection->contents->count() > 0)
              <div class="contents-list">
                @foreach($parentSection->contents->sortBy('order') as $content)
                  @include('panel.teacher.courses.partials.content-item', ['content' => $content])
                @endforeach
              </div>
            @endif

            @foreach($parentSection->children->sortBy('created_at') as $childSection)
              <div class="child-section-group" id="section-{{ $childSection->id }}">
                <div class="child-section-header">
                  <div class="section-info">
                    <h4><i class="fas fa-folder-open"></i>{{ $childSection->title_ar }}</h4>
                    <span class="content-count">{{ $childSection->contents->count() }} {{ __('panel.items') }}</span>
                  </div>
                  <div class="section-actions">
                    <a href="{{ route('teacher.courses.contents.create', [$course, 'section_id' => $childSection->id]) }}" class="btn-action" title="{{ __('panel.add_content') }}">
                      <i class="fas fa-plus"></i>
                    </a>
                    <a href="{{ route('teacher.courses.sections.edit', [$course, $childSection]) }}" class="btn-action" title="{{ __('panel.edit_section') }}">
                      <i class="fas fa-pen"></i>
                    </a>
                    <button onclick="deleteSection({{ $childSection->id }})" class="btn-action btn-danger" title="{{ __('panel.delete_section') }}">
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                </div>

                @if($childSection->contents->count() > 0)
                  <div class="contents-list">
                    @foreach($childSection->contents->sortBy('order') as $content)
                      @include('panel.teacher.courses.partials.content-item', ['content' => $content])
                    @endforeach
                  </div>
                @else
                  <div class="empty-section">
                    <i class="far fa-folder-open"></i>
                    <p>{{ __('panel.no_content_in_section') }}</p>
                    <a href="{{ route('teacher.courses.contents.create', [$course, 'section_id' => $childSection->id]) }}" class="btn btn-sm btn-primary">
                      {{ __('panel.add_first_content') }}
                    </a>
                  </div>
                @endif
              </div>
            @endforeach

            @if($parentSection->contents->count() == 0 && $parentSection->children->count() == 0)
              <div class="empty-section">
                <i class="far fa-folder-open"></i>
                <p>{{ __('panel.no_content_in_section') }}</p>
                <div class="empty-actions">
                  <a href="{{ route('teacher.courses.sections.create', $course) }}" class="btn btn-sm btn-secondary">{{ __('panel.add_subsection') }}</a>
                  <a href="{{ route('teacher.courses.contents.create', [$course, 'section_id' => $parentSection->id]) }}" class="btn btn-sm btn-primary">{{ __('panel.add_content') }}</a>
                </div>
              </div>
            @endif
          </div>
        @empty
          @if($directContents->count() == 0)
            <div class="empty-course">
              <div class="empty-icon"><i class="fas fa-graduation-cap"></i></div>
              <h3>{{ __('panel.empty_course') }}</h3>
              <p>{{ __('panel.start_adding_content') }}</p>
              <div class="empty-actions">
                <a href="{{ route('teacher.courses.sections.create', $course) }}" class="btn btn-primary">
                  <i class="fas fa-folder-plus"></i> {{ __('panel.create_section') }}
                </a>
                <a href="{{ route('teacher.courses.contents.create', $course) }}" class="btn btn-secondary">
                  <i class="fas fa-file-circle-plus"></i> {{ __('panel.add_content_directly') }}
                </a>
              </div>
            </div>
          @endif
        @endforelse
      </div>
    </div>
  </div>
</section>

<div class="modal fade custom-modal" id="deleteSectionModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-animate">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ __('panel.confirm_delete') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>{{ __('panel.delete_section_warning') }}</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn m-btn-secondary" data-bs-dismiss="modal">{{ __('panel.cancel') }}</button>
        <form id="deleteSectionForm" method="POST" style="display:inline">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn m-btn-danger">{{ __('panel.delete') }}</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade custom-modal" id="deleteContentModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-animate">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ __('panel.confirm_delete') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>{{ __('panel.delete_content_warning') }}</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn m-btn-secondary" data-bs-dismiss="modal">{{ __('panel.cancel') }}</button>
        <form id="deleteContentForm" method="POST" style="display:inline">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn m-btn-danger">{{ __('panel.delete') }}</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('styles')
<style>
.ud-wrap{display:grid;grid-template-columns:260px 1fr;gap:24px;padding:16px 0}
.ud-menu{background:#fff;border:1px solid #eef0f3;border-radius:14px;padding:16px;position:sticky;top:88px;height:max-content}
.ud-user{display:flex;align-items:center;gap:12px;margin-bottom:12px}
.ud-user img{width:56px;height:56px;border-radius:50%;object-fit:cover;border:2px solid #f1f5f9}
.ud-user h3{font-size:16px;margin:0 0 2px 0}
.ud-user span{font-size:12px;color:#6b7280}
.ud-item{display:flex;align-items:center;gap:10px;padding:12px 14px;border:1px solid #e5e7eb;border-radius:10px;text-decoration:none;color:#0f172a;transition:all .18s}
.ud-item:hover{border-color:#0055D2;box-shadow:0 6px 18px rgba(0,85,210,.12);transform:translateY(-2px)}
.ud-content{min-width:0}
.ud-panel{background:#fff;border:1px solid #eef0f3;border-radius:14px;padding:18px}

.course-header{display:flex;justify-content:space-between;gap:14px;align-items:flex-start;margin-bottom:18px;padding-bottom:14px;border-bottom:2px solid #f1f5f9}
.course-title{font-size:22px;font-weight:900;color:#0f172a;margin:0 0 4px 0}
.course-subject{margin:0;color:#6b7280}
.course-actions{display:flex;gap:10px;flex-wrap:wrap}

.btn{display:inline-flex;align-items:center;gap:8px;border-radius:12px;padding:10px 14px;font-weight:900;font-size:14px;text-decoration:none;cursor:pointer;transition:transform .16s,box-shadow .16s}
.btn:hover{transform:translateY(-1px)}
.btn-primary{background:#0055D2;color:#fff;border:1px solid #0048b3}
.btn-primary:hover{box-shadow:0 10px 22px rgba(0,85,210,.22)}
.btn-secondary{background:#111827;color:#fff;border:1px solid #0b1220}
.btn-secondary:hover{box-shadow:0 10px 22px rgba(17,24,39,.22)}

.alert{padding:12px 14px;border-radius:12px;margin-bottom:14px}
.alert-success{background:#ecfdf5;color:#065f46;border:1px solid #d1fae5}
.alert-danger{background:#fef2f2;color:#991b1b;border:1px solid #fee2e2}

.course-structure{display:flex;flex-direction:column;gap:18px}
.content-group,.section-group{background:#fff;border:1px solid #eef0f3;border-radius:14px;overflow:hidden}
.group-header,.section-header{background:#f8fafc;padding:14px 16px;border-bottom:1px solid #eef0f3;display:flex;justify-content:space-between;align-items:center}
.group-header h3,.section-header h3{margin:0;font-size:16px;color:#0f172a;display:flex;align-items:center;gap:10px}
.section-info{display:flex;align-items:center;gap:14px}
.content-count{background:#0055D2;color:#fff;padding:2px 10px;border-radius:999px;font-size:12px;font-weight:800}
.section-actions{display:flex;gap:8px}
.btn-action{width:34px;height:34px;border-radius:10px;background:#fff;border:1px solid #e5e7eb;display:flex;align-items:center;justify-content:center;color:#0f172a;text-decoration:none;transition:all .16s}
.btn-action:hover{border-color:#0055D2;box-shadow:0 8px 18px rgba(0,85,210,.14);color:#0055D2}
.btn-action.btn-danger:hover{background:#ef4444;border-color:#ef4444;color:#fff}

.contents-list{padding:0}
.contents-list{display:flex!important;flex-direction:column!important;gap:10px!important;padding:12px 14px!important;background:#fff!important}
.contents-list .content-item{display:flex!important;align-items:center!important;gap:12px!important;padding:12px 14px!important;border:1px solid #e5e7eb!important;border-radius:12px!important;background:#ffffff!important;transition:box-shadow .16s ease,transform .16s ease,border-color .16s ease!important}
.contents-list .content-item:hover{border-color:#0055D2!important;box-shadow:0 8px 18px rgba(0,85,210,.12)!important;transform:translateY(-1px)!important}
.contents-list .content-item i{font-size:16px!important;color:#0055D2!important;flex-shrink:0!important}
.contents-list .ci-left{display:flex!important;align-items:center!important;gap:10px!important;flex:1 1 auto!important;min-width:0!important}
.contents-list .ci-title,.contents-list .content-title{font-weight:800!important;font-size:14px!important;color:#0f172a!important;white-space:nowrap!important;overflow:hidden!important;text-overflow:ellipsis!important}
.contents-list .content-badges{display:flex!important;flex-wrap:wrap!important;gap:6px!important}
.contents-list .badge{padding:4px 8px!important;border-radius:999px!important;font-size:11px!important;font-weight:800!important;line-height:1!important}
.contents-list .badge-primary{background:#0055D2!important;color:#fff!important}
.contents-list .badge-secondary{background:#6b7280!important;color:#fff!important}
.contents-list .badge-success{background:#10b981!important;color:#fff!important}
.contents-list .badge-warning{background:#f59e0b!important;color:#111827!important}
.contents-list .ci-actions,.contents-list .content-actions,.section-actions .btn-action{display:flex!important;align-items:center!important;gap:8px!important;margin-inline-start:auto!important}
.contents-list .btn-action{width:34px!important;height:34px!important;border-radius:10px!important;background:#fff!important;border:1px solid #e5e7eb!important;display:flex!important;align-items:center!important;justify-content:center!important;color:#0f172a!important;text-decoration:none!important;transition:all .16s!important}
.contents-list .btn-action:hover{border-color:#0055D2!important;color:#0055D2!important;box-shadow:0 6px 16px rgba(0,85,210,.12)!important}
.contents-list .btn-action.btn-danger:hover{background:#ef4444!important;border-color:#ef4444!important;color:#fff!important}
.contents-list .ci-drag{cursor:grab!important;color:#9ca3af!important}
.contents-list .ci-drag:active{cursor:grabbing!important}
.section-group .contents-list + .contents-list{margin-top:6px!important}

@media (max-width:768px){
  .contents-list .content-item{padding:12px!important;gap:10px!important}
  .contents-list .ci-left{flex-wrap:wrap!important}
  .contents-list .ci-actions{margin-inline-start:0!important}
}

.modal{display:none!important;position:fixed;inset:0;z-index:9999;align-items:center;justify-content:center;background:rgba(17,24,39,.55)!important}
.modal.open{display:flex!important}
.modal-dialog{width:min(520px,92vw)!important;margin:0!important;transform:scale(.96);opacity:.96;transition:transform .18s ease,opacity .18s ease}
.modal.open .modal-dialog{transform:scale(1);opacity:1}
.modal-content{background:#fff!important;border-radius:16px!important;border:0!important;box-shadow:0 20px 60px rgba(0,0,0,.25)!important;overflow:hidden}
.modal-header,.modal-footer{border:0!important}
.modal-header{padding:16px 18px!important}
.modal-body{padding:14px 18px!important}
.btn-close{background:none;border:0;font-size:20px;line-height:1;opacity:.6}
.btn-close:hover{opacity:1}

.child-section-group{margin:14px 12px 12px;border:1px solid #eef0f3;border-radius:12px;overflow:hidden;background:#fbfcfe}
.child-section-header{background:#f3f6fb;padding:12px 14px;border-bottom:1px solid #e8eef9;display:flex;justify-content:space-between;align-items:center}
.child-section-header h4{margin:0;font-size:15px;color:#0f172a;display:flex;align-items:center;gap:8px}

.empty-section,.empty-course{padding:32px 18px;text-align:center;color:#6b7280}
.empty-course{padding:56px 18px}
.empty-course .empty-icon{font-size:44px;color:#e5e7eb;margin-bottom:14px}
.empty-actions{display:flex;gap:10px;justify-content:center;flex-wrap:wrap}

.btn.btn-sm{padding:8px 12px;border-radius:10px;font-size:12px}

.custom-modal .modal-dialog{transform:translateY(16px);transition:transform .2s ease,opacity .2s ease}
.custom-modal.show .modal-dialog{transform:none}
.custom-modal .modal-content{border:1px solid #eef0f3;border-radius:16px;overflow:hidden;box-shadow:0 24px 60px rgba(15,23,42,.22)}
.custom-modal .modal-header{background:#f8fafc;border-bottom:1px solid #eef0f3}
.custom-modal .modal-title{font-weight:900;color:#0f172a}
.custom-modal .modal-body p{margin:0;color:#334155}
.m-btn-secondary{background:#111827;color:#fff;border:1px solid #0b1220;border-radius:12px;padding:10px 14px;font-weight:900}
.m-btn-secondary:hover{box-shadow:0 10px 22px rgba(17,24,39,.22)}
.m-btn-danger{background:#ef4444;color:#fff;border:1px solid #dc2626;border-radius:12px;padding:10px 14px;font-weight:900}
.m-btn-danger:hover{box-shadow:0 10px 22px rgba(239,68,68,.22)}

@media (max-width:992px){
  .ud-wrap{grid-template-columns:1fr}
  .ud-menu{margin: 10px;position:static}
}
@media (max-width:768px){
  .course-header{flex-direction:column;gap:12px}
  .course-actions{width:100%}
  .course-actions .btn{flex:1}
  .child-section-group{margin:10px 8px}
  .empty-actions{flex-direction:column;align-items:stretch}
}
</style>
@endsection

@section('scripts')
<script>
(function(){
  const courseId='{{ $course->id }}';
  let handlersAttached=false;

  function getModal(id){
    const el=document.getElementById(id);
    if(!el||!window.bootstrap) return null;
    return bootstrap.Modal.getOrCreateInstance(el,{backdrop:'static',keyboard:true});
  }

  function handleDelete(form,modal,loadingText,defaultText){
    const btn=form.querySelector('button[type="submit"]');
    const originalHTML=btn.innerHTML;
    btn.disabled=true;
    btn.innerHTML=`<i class="fas fa-spinner fa-spin"></i> ${loadingText}...`;
    fetch(form.action,{
      method:'POST',
      headers:{
        'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Content-Type':'application/json'
      },
      body:JSON.stringify({_method:'DELETE'})
    })
    .then(r=>r.json())
    .then(d=>{
      if(d.success){
        modal&&modal.hide();
        location.reload();
      }else{
        alert(d.message||'{{ __("panel.error_occurred") }}');
        btn.disabled=false;
        btn.innerHTML=defaultText;
      }
    })
    .catch(()=>{
      alert('{{ __("panel.error_occurred") }}');
      btn.disabled=false;
      btn.innerHTML=originalHTML;
    });
  }

  function attachHandlersOnce(){
    if(handlersAttached) return;
    const secForm=document.getElementById('deleteSectionForm');
    const conForm=document.getElementById('deleteContentForm');
    secForm&&secForm.addEventListener('submit',function(e){
      e.preventDefault();
      handleDelete(secForm,getModal('deleteSectionModal'),'{{ __("panel.deleting") }}','{{ __("panel.delete") }}');
    });
    conForm&&conForm.addEventListener('submit',function(e){
      e.preventDefault();
      handleDelete(conForm,getModal('deleteContentModal'),'{{ __("panel.deleting") }}','{{ __("panel.delete") }}');
    });
    handlersAttached=true;
  }

  window.deleteSection=function(sectionId){
    attachHandlersOnce();
    const form=document.getElementById('deleteSectionForm');
    const modal=getModal('deleteSectionModal');
    if(!form||!modal) return;
    form.action=`/teacher/courses/${courseId}/sections/${sectionId}`;
    modal.show();
  };

  window.deleteContent=function(contentId){
    attachHandlersOnce();
    const form=document.getElementById('deleteContentForm');
    const modal=getModal('deleteContentModal');
    if(!form||!modal) return;
    form.action=`/teacher/courses/${courseId}/contents/${contentId}`;
    modal.show();
  };
})();
</script>
@endsection
