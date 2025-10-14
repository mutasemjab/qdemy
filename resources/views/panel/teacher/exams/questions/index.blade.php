@extends('layouts.app')

@section('title', __('panel.questions'))
@section('page_title', __('panel.questions_management'))

@push('styles')
<style>
:root{
  --ql-bg:#f6f8fc;
  --ql-surface:#ffffff;
  --ql-ink:#0f172a;
  --ql-muted:#667085;
  --ql-line:#e5e7eb;
  --ql-primary:#0055D2;
  --ql-success:#10b981;
  --ql-info:#3b82f6;
  --ql-warn:#f59e0b;
  --ql-danger:#ef4444;
  --ql-max:1300px;
}
body{background:var(--ql-bg)}
.ql-shell{max-width:var(--ql-max);margin:0 auto;padding:16px}
.ql-hero{border:1px solid var(--ql-line);border-radius:18px;padding:18px;background:radial-gradient(1200px 300px at 20% -10%, rgba(0,85,210,.06), transparent 60%),linear-gradient(180deg,#fff,#f8fbff)}
.ql-hero h2{margin:0;font-weight:900;color:var(--ql-ink);letter-spacing:.2px}
.ql-hero p{margin:6px 0 0;color:var(--ql-muted)}
.ql-btn{display:inline-flex;align-items:center;gap:8px;border-radius:12px;height:44px;padding:0 14px;border:1px solid var(--ql-line);background:#fff;color:var(--ql-ink);font-weight:900;text-decoration:none;cursor:pointer;transition:box-shadow .16s,transform .16s}
.ql-btn:hover{transform:translateY(-1px);box-shadow:0 12px 22px rgba(15,23,42,.06)}
.ql-btn-primary{background:var(--ql-primary);border-color:var(--ql-primary);color:#fff}
.ql-btn-outline{color:var(--ql-primary);border-color:var(--ql-primary)}
.ql-btn-outline:hover{background:var(--ql-primary);color:#fff}
.ql-statgrid{display:grid;grid-template-columns:repeat(12,1fr);gap:12px;margin-top:16px}
.ql-statgrid .ql-col{grid-column:span 3}
@media(max-width:1200px){.ql-statgrid .ql-col{grid-column:span 4}}
@media(max-width:768px){.ql-statgrid .ql-col{grid-column:span 6}}
.ql-stat{border:1px solid var(--ql-line);border-radius:16px;background:var(--ql-surface);padding:16px;text-align:center;transition:transform .16s,box-shadow .16s}
.ql-stat:hover{transform:translateY(-2px);box-shadow:0 16px 26px rgba(15,23,42,.06)}
.ql-stat .ql-ico{width:46px;height:46px;border-radius:12px;margin:0 auto 8px;color:#fff;display:flex;align-items:center;justify-content:center}
.ql-stat .ql-val{font-size:22px;font-weight:900;color:var(--ql-ink)}
.ql-stat .ql-lab{font-size:12px;color:var(--ql-muted)}
.ql-stat.primary .ql-ico{background:var(--ql-primary)}
.ql-stat.success .ql-ico{background:var(--ql-success)}
.ql-stat.info .ql-ico{background:var(--ql-info)}
.ql-stat.warn .ql-ico{background:var(--ql-warn)}
.ql-filter{border:1px solid var(--ql-line);border-radius:16px;background:var(--ql-surface);padding:16px;margin-top:16px}
.ql-filter-head{display:flex;justify-content:space-between;align-items:center;gap:10px;margin-bottom:12px}
.ql-filter-title{font-weight:900;color:var(--ql-ink);margin:0}
.ql-fgrid{display:grid;grid-template-columns:repeat(12,1fr);gap:12px}
.ql-f3{grid-column:span 3}
.ql-f2{grid-column:span 2}
.ql-f4{grid-column:span 4}
.ql-f1{grid-column:span 1}
@media(max-width:1200px){.ql-f3{grid-column:span 4}.ql-f2{grid-column:span 3}.ql-f4{grid-column:span 5}.ql-f1{grid-column:span 0}}
@media(max-width:768px){.ql-f3,.ql-f2,.ql-f4{grid-column:span 12}}
.ql-field{display:flex;flex-direction:column;gap:6px}
.ql-label{font-weight:800;color:var(--ql-ink);font-size:13px;margin:0}
.ql-select,.ql-input{border-radius:12px;border:1px solid var(--ql-line);height:44px}
.ql-inputgroup .ql-btn{height:44px}
.ql-chips{display:flex;gap:8px;flex-wrap:wrap;margin-top:10px}
.ql-chip{padding:7px 12px;border-radius:999px;border:1px solid var(--ql-line);background:#fff;font-size:12px;font-weight:800;color:var(--ql-ink);cursor:pointer;transition:transform .16s,box-shadow .16s,border-color .16s}
.ql-chip.qx-active{border-color:var(--ql-primary);color:var(--ql-primary);background:rgba(0,85,210,.08)}
.ql-list{display:grid;grid-template-columns:repeat(12,1fr);gap:14px;margin-top:10px}
.ql-list .ql-col{grid-column:span 4}
@media(max-width:1200px){.ql-list .ql-col{grid-column:span 6}}
@media(max-width:768px){.ql-list .ql-col{grid-column:span 12}}
.ql-card{display:flex;flex-direction:column;height:100%;background:var(--ql-surface);border:1px solid var(--ql-line);border-radius:16px;overflow:hidden;transition:transform .18s,box-shadow .18s}
.ql-card:hover{transform:translateY(-3px);box-shadow:0 18px 34px rgba(15,23,42,.08)}
.ql-card-h{display:flex;justify-content:space-between;align-items:start;padding:14px 16px;border-bottom:1px solid var(--ql-line)}
.ql-badges{display:flex;gap:6px;flex-wrap:wrap}
.ql-badge{border-radius:999px;font-weight:800}
.ql-badge-type{padding:6px 10px;font-size:12px}
.ql-type-mc{background:rgba(16,185,129,.12);color:#047857}
.ql-type-tf{background:rgba(59,130,246,.12);color:#1d4ed8}
.ql-type-es{background:rgba(245,158,11,.16);color:#b45309}
.ql-badge-sec{background:#eef2f7;color:#111827;padding:6px 10px;font-size:12px;border-radius:999px}
.ql-kebab{position:relative}
.ql-iconbtn{width:40px;height:40px;border-radius:12px;display:flex;align-items:center;justify-content:center;border:1px solid var(--ql-line);background:#fff;color:var(--ql-ink);cursor:pointer}
.ql-menu{position:absolute;top:44px;inset-inline-end:0;background:#fff;border:1px solid var(--ql-line);border-radius:14px;min-width:190px;padding:8px;box-shadow:0 18px 40px rgba(15,23,42,.12);display:none;z-index:10}
.ql-kebab.qx-open .ql-menu{display:block}
.ql-menu a,.ql-menu button{text-decoration:none;cursor:pointer;display:flex;gap:8px;align-items:center;width:100%;text-align:start;background:none;border:0;padding:9px 10px;border-radius:10px;font-weight:700;font-size:14px;color:var(--ql-ink)}
.ql-menu a:hover,.ql-menu button:hover{background:rgba(0,85,210,.08);color:var(--ql-primary)}
.ql-card-b{padding:16px;display:flex;flex-direction:column;gap:10px}
.ql-title{font-size:16px;font-weight:900;color:var(--ql-ink);margin:0}
.ql-prev{color:var(--ql-muted);font-size:13px;line-height:1.55;max-height:3.2em;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical}
.ql-optline{display:flex;align-items:center;gap:8px;color:var(--ql-muted);font-size:12px}
.ql-card-f{margin-top:auto;padding:12px 16px;border-top:1px solid var(--ql-line);display:flex;justify-content:space-between;align-items:center}
.ql-pill{display:inline-flex;align-items:center;gap:6px;border:1px solid var(--ql-line);border-radius:999px;padding:6px 10px;font-weight:800;font-size:12px}
.ql-time{display:inline-flex;align-items:center;gap:6px;color:var(--ql-muted);font-size:12px}
.modal-content{border:1px solid var(--ql-line);border-radius:16px}
.modal:not(.show){display:none}
.spinner-border{color:var(--ql-primary)!important}
.ql-empty{color:#cbd5e1}
@media(max-width:768px){
  .ql-hero{padding:14px}
  .ql-btn{height:46px}
  .ql-filter{position:sticky;top:8px;z-index:2}
}
</style>
@endpush

@section('content')
<div class="container-fluid">
  <div class="ql-shell">
    <div class="ql-hero">
      <div class="d-flex justify-content-between align-items-end gap-2 flex-wrap">
        <div>
          <h2>{{ __('panel.questions_management') }}</h2>
          <p>{{ __('panel.manage_your_questions_desc') }}</p>
        </div>
        <br>
        <div class="d-flex gap-2">
          <a href="{{ route('teacher.exams.exam_questions.create', $exam) }}" class="ql-btn ql-btn-primary"><i class="fas fa-plus"></i>{{ __('panel.create_question') }}</a>
        </div>
      </div>
    </div>

    <div class="ql-statgrid">
      <div class="ql-col">
        <div class="ql-stat primary">
          <div class="ql-ico"><i class="fas fa-question-circle"></i></div>
          <div class="ql-val">{{ $totalQuestions ?? 0 }}</div>
          <div class="ql-lab">{{ __('panel.total_questions') }}</div>
        </div>
      </div>
      <div class="ql-col">
        <div class="ql-stat success">
          <div class="ql-ico"><i class="fas fa-check-circle"></i></div>
          <div class="ql-val">{{ $multipleChoiceCount ?? 0 }}</div>
          <div class="ql-lab">{{ __('panel.multiple_choice') }}</div>
        </div>
      </div>
      <div class="ql-col">
        <div class="ql-stat info">
          <div class="ql-ico"><i class="fas fa-balance-scale"></i></div>
          <div class="ql-val">{{ $trueFalseCount ?? 0 }}</div>
          <div class="ql-lab">{{ __('panel.true_false') }}</div>
        </div>
      </div>
      <div class="ql-col">
        <div class="ql-stat warn">
          <div class="ql-ico"><i class="fas fa-pen"></i></div>
          <div class="ql-val">{{ $essayCount ?? 0 }}</div>
          <div class="ql-lab">{{ __('panel.essay') }}</div>
        </div>
      </div>
    </div>

    <div class="ql-filter">
      <div class="ql-filter-head">
        <h5 class="ql-filter-title">{{ __('panel.filters') }}</h5>
        <a href="{{ route('teacher.exams.exam_questions.index',$exam) }}" class="ql-btn"><i class="fas fa-rotate"></i></a>
      </div>
      <form method="GET" action="{{ route('teacher.exams.exam_questions.index', $exam) }}" id="qlFilterForm">
        <div class="ql-fgrid">
          <div class="ql-f3 ql-field">
            <label class="ql-label">{{ __('panel.course') }}</label>
            <select name="course_id" class="ql-select">
              <option value="">{{ __('panel.all_courses') }}</option>
              @if(isset($courses))
                @foreach($courses as $course)
                  <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                    {{ app()->getLocale() === 'ar' ? $course->title_ar : $course->title_en }}
                  </option>
                @endforeach
              @endif
            </select>
          </div>
          <div class="ql-f2 ql-field">
            <label class="ql-label">{{ __('panel.type') }}</label>
            <select name="type" class="ql-select">
              <option value="">{{ __('panel.all_types') }}</option>
              <option value="multiple_choice" {{ request('type') === 'multiple_choice' ? 'selected' : '' }}>{{ __('panel.multiple_choice') }}</option>
              <option value="true_false" {{ request('type') === 'true_false' ? 'selected' : '' }}>{{ __('panel.true_false') }}</option>
              <option value="essay" {{ request('type') === 'essay' ? 'selected' : '' }}>{{ __('panel.essay') }}</option>
            </select>
          </div>
          <div class="ql-f2 ql-field">
            <label class="ql-label">{{ __('panel.grade_range') }}</label>
            <select name="grade_range" class="ql-select">
              <option value="">{{ __('panel.all_grades') }}</option>
              <option value="0-1" {{ request('grade_range') === '0-1' ? 'selected' : '' }}>0-1</option>
              <option value="1-5" {{ request('grade_range') === '1-5' ? 'selected' : '' }}>1-5</option>
              <option value="5-10" {{ request('grade_range') === '5-10' ? 'selected' : '' }}>5-10</option>
              <option value="10+" {{ request('grade_range') === '10+' ? 'selected' : '' }}>10+</option>
            </select>
          </div>
          <div class="ql-f4 ql-field">
            <label class="ql-label">{{ __('panel.search') }}</label>
            <div class="input-group ql-inputgroup">
              <input type="text" name="search" class="form-control ql-input" placeholder="{{ __('panel.search_questions_placeholder') }}" value="{{ request('search') }}" autocomplete="off">
              <button type="submit" class="ql-btn ql-btn-outline"><i class="fas fa-search"></i></button>
            </div>
            <div class="ql-chips">
              <button type="button" class="ql-chip ql-js-chip" data-type="multiple_choice">{{ __('panel.multiple_choice') }}</button>
              <button type="button" class="ql-chip ql-js-chip" data-type="true_false">{{ __('panel.true_false') }}</button>
              <button type="button" class="ql-chip ql-js-chip" data-type="essay">{{ __('panel.essay') }}</button>
            </div>
          </div>
        </div>
      </form>
    </div>

    @if(isset($questions) && $questions->count() > 0)
      <div class="ql-list">
        @foreach($questions as $question)
          <div class="ql-col">
            <div class="ql-card">
              <div class="ql-card-h">
                <div class="ql-badges">
                  <span class="ql-badge ql-badge-type {{ $question->type === 'multiple_choice' ? 'ql-type-mc' : ($question->type === 'true_false' ? 'ql-type-tf' : 'ql-type-es') }}">{{ __('panel.' . $question->type) }}</span>
                  @if($question->course)
                    <span class="ql-badge-sec">{{ app()->getLocale() === 'ar' ? $question->course->title_ar : $question->course->title_en }}</span>
                  @endif
                </div>
                <div class="ql-kebab ql-js-kebab" tabindex="0">
                  <button type="button" class="ql-iconbtn" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-vertical"></i></button>
                  <div class="ql-menu" role="menu">
                    <a href="#" class="ql-js-view" data-question-id="{{ $question->id }}"><i class="fas fa-eye"></i><span>{{ __('panel.view') }}</span></a>
                    <a href="{{ route('teacher.exams.exam_questions.edit', [$exam, $question]) }}"><i class="fas fa-pen-to-square"></i><span>{{ __('panel.edit') }}</span></a>
                    <form action="{{ route('teacher.exams.exam_questions.destroy', [$exam, $question]) }}" method="POST">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="text-danger"><i class="fas fa-trash"></i><span>{{ __('panel.delete') }}</span></button>
                    </form>
                  </div>
                </div>
              </div>

              <div class="ql-card-b">
                <h5 class="ql-title">{{ app()->getLocale() === 'ar' ? $question->title_ar : $question->title_en }}</h5>
                <div class="ql-prev">{{ strip_tags(app()->getLocale() === 'ar' ? $question->question_ar : $question->question_en) }}</div>
                @if($question->type !== 'essay' && $question->options->count() > 0)
                  @foreach($question->options->take(2) as $option)
                    <div class="ql-optline">
                      <i class="fas fa-{{ $option->is_correct ? 'check-circle text-success' : 'circle' }}"></i>
                      <span>{{ Str::limit(app()->getLocale() === 'ar' ? $option->option_ar : $option->option_en, 40) }}</span>
                    </div>
                  @endforeach
                  @if($question->options->count() > 2)
                    <div class="ql-optline"><i class="fas fa-ellipsis"></i><span>{{ __('panel.and_x_more', ['count' => $question->options->count() - 2]) }}</span></div>
                  @endif
                @endif
                @if($question->exams_count > 0)
                  <div class="ql-optline"><i class="fas fa-clipboard-list text-info"></i><span>{{ __('panel.used_in_x_exams', ['count' => $question->exams_count]) }}</span></div>
                @endif
              </div>

              <div class="ql-card-f">
                <div class="ql-pill"><i class="fas fa-star" style="color:#fbbf24"></i><span>{{ $question->grade }}</span></div>
                <div class="ql-time"><i class="fas fa-clock"></i><span>{{ $question->created_at->diffForHumans() }}</span></div>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <div class="d-flex justify-content-center mt-4">
        {{ $questions->appends(request()->query())->links() }}
      </div>
    @else
      <div class="text-center py-5">
        <div class="mb-4"><i class="fas fa-question-circle fa-5x ql-empty"></i></div>
        <h3 class="mb-2" style="color:var(--ql-ink)">{{ __('panel.no_questions_found') }}</h3>
        <p class="text-muted mb-4">{{ __('panel.no_questions_desc') }}</p>
        <a href="{{ route('teacher.exams.exam_questions.create',$exam) }}" class="ql-btn ql-btn-primary"><i class="fas fa-plus"></i>{{ __('panel.create_first_question') }}</a>
      </div>
    @endif
  </div>
</div>

<div class="modal fade" id="questionModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ __('panel.question_preview') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="qlPreviewArea">
        <div class="text-center py-4"><div class="spinner-border" role="status"><span class="visually-hidden">{{ __('panel.loading') }}</span></div></div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded',function(){
  var form=document.getElementById('qlFilterForm');
  if(form){
    form.querySelectorAll('select').forEach(function(s){s.addEventListener('change',function(){form.submit()})});
    var si=form.querySelector('input[name="search"]');var to;
    if(si){si.addEventListener('input',function(){clearTimeout(to);to=setTimeout(function(){form.submit()},500)})}
    var chips=document.querySelectorAll('.ql-js-chip');var typeSel=form.querySelector('select[name="type"]');
    chips.forEach(function(ch){
      if(typeSel && typeSel.value===ch.dataset.type){ch.classList.add('qx-active')}
      ch.addEventListener('click',function(){if(typeSel){typeSel.value=ch.dataset.type;form.submit()}})
    });
  }

  document.querySelectorAll('.ql-js-kebab').forEach(function(kb){
    var btn=kb.querySelector('.ql-iconbtn');
    btn.addEventListener('click',function(e){
      e.stopPropagation();
      document.querySelectorAll('.ql-js-kebab.qx-open').forEach(function(o){if(o!==kb)o.classList.remove('qx-open')});
      kb.classList.toggle('qx-open');
      btn.setAttribute('aria-expanded',kb.classList.contains('qx-open')?'true':'false');
    });
    kb.addEventListener('keydown',function(e){
      if(e.key==='Enter' || e.key===' '){e.preventDefault();btn.click()}
      if(e.key==='Escape'){kb.classList.remove('qx-open');btn.setAttribute('aria-expanded','false')}
    });
  });
  document.addEventListener('click',function(){document.querySelectorAll('.ql-js-kebab.qx-open').forEach(function(o){o.classList.remove('qx-open')})});
  document.addEventListener('keydown',function(e){if(e.key==='Escape'){document.querySelectorAll('.ql-js-kebab.qx-open').forEach(function(o){o.classList.remove('qx-open')})}});

  document.querySelectorAll('.ql-js-view').forEach(function(a){
    a.addEventListener('click',function(e){
      e.preventDefault();
      var id=this.dataset.questionId;
      var modal=new bootstrap.Modal(document.getElementById('questionModal'));
      var area=document.getElementById('qlPreviewArea');
      area.innerHTML='<div class="text-center py-4"><div class="spinner-border" role="status"><span class="visually-hidden">{{ __('panel.loading') }}</span></div></div>';
      modal.show();
      var url='{{ route("teacher.exams.exam_questions.show", [$exam, ":id"]) }}'.replace(':id',id);
      fetch(url,{headers:{'X-Requested-With':'XMLHttpRequest'}})
        .then(function(r){return r.ok?r.json():Promise.reject()})
        .then(function(res){
          if(!res||!res.success){throw 0}
          var q=res.data||{};
          var t=('{{ app()->getLocale() }}'==='ar'?q.title_ar:q.title_en)||'';
          var qt=('{{ app()->getLocale() }}'==='ar'?q.question_ar:q.question_en)||'';
          var ex=('{{ app()->getLocale() }}'==='ar'?q.explanation_ar:q.explanation_en)||'';
          var c=q.course?('{{ app()->getLocale() }}'==='ar'?q.course.title_ar:q.course.title_en):'';
          var opts='';
          if(q.type==='multiple_choice'||q.type==='true_false'){
            (q.options||[]).forEach(function(o){
              var ic=o.is_correct?'<i class="fas fa-check-circle text-success me-2"></i>':'<i class="far fa-circle text-muted me-2"></i>';
              var tx=('{{ app()->getLocale() }}'==='ar'?o.option_ar:o.option_en)||'';
              opts+='<div class="d-flex align-items-center mb-2 p-2 border rounded">'+ic+'<span>'+tx+'</span></div>';
            });
          }
          var typeClass=q.type==='multiple_choice'?'ql-type-mc':(q.type==='true_false'?'ql-type-tf':'ql-type-es');
          var html=''
            +'<div class="mb-3 d-flex justify-content-between align-items-center">'
            +'<h4 class="m-0">'+t+'</h4>'
            +'<div class="d-flex flex-wrap gap-1">'
            +'<span class="ql-badge ql-badge-type '+typeClass+'">'+String(q.type||'').replace('_',' ')+'</span>'
            +'<span class="ql-badge-sec">{{ __("panel.grade") }}: '+(q.grade??0)+'</span>'
            +(c?'<span class="ql-badge-sec">'+c+'</span>':'')
            +'</div></div>'
            +'<div class="mb-3"><div class="p-3 bg-light rounded">'+qt+'</div></div>'
            +(opts?'<div class="mb-3"><h6 class="text-muted mb-2">{{ __("panel.options") }}</h6>'+opts+'</div>':'')
            +(ex?'<div class="mb-2"><h6 class="text-muted mb-2">{{ __("panel.explanation") }}</h6><div class="p-3 bg-light rounded text-muted">'+ex+'</div></div>':'');
          area.innerHTML=html;
        })
        .catch(function(){
          area.innerHTML='<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>{{ __("panel.failed_to_load_question") }}</div>';
        });
    });
  });

  document.querySelectorAll('.delete-question').forEach(function(btn){
    btn.addEventListener('click',function(e){
      e.preventDefault();
      var f=this.closest('form');
      if(window.Swal){
        Swal.fire({
          title:'{{ __("panel.confirm_delete") }}',
          text:'{{ __("panel.delete_question_warning") }}',
          icon:'warning',
          showCancelButton:true,
          confirmButtonColor:'#d33',
          cancelButtonColor:'#3085d6',
          confirmButtonText:'{{ __("panel.yes_delete") }}',
          cancelButtonText:'{{ __("panel.cancel") }}'
        }).then(function(r){if(r.isConfirmed){f.submit()}});
      }else{
        if(confirm('{{ __("panel.delete_question_warning") }}')) f.submit();
      }
    });
  });
});
</script>
@endpush
