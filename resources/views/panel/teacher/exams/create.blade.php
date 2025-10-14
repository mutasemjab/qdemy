@extends('layouts.app')

@section('title', __('panel.create_exam'))
@section('page_title', __('panel.create_exam'))

@section('styles')
<style>
:root{
  --ex-max:1300px;
  --ex-bg:#f6f8fc;
  --ex-surface:#ffffff;
  --ex-ink:#0f172a;
  --ex-muted:#667085;
  --ex-line:#e5e7eb;
  --ex-primary:#0055D2;
  --ex-success:#10b981;
  --ex-warn:#f59e0b;
  --ex-danger:#ef4444;
}
body{background:var(--ex-bg)}
.ex-shell{max-width:var(--ex-max);margin:0 auto;padding:16px}
.ex-hero{border:1px solid var(--ex-line);border-radius:18px;padding:18px;background:radial-gradient(1200px 300px at 20% -10%, rgba(0,85,210,.06), transparent 60%),linear-gradient(180deg,#fff,#f8fbff);display:flex;justify-content:space-between;align-items:end;gap:10px;flex-wrap:wrap}
.ex-hero h2{margin:0;font-weight:900;color:var(--ex-ink)}
.ex-hero p{margin:6px 0 0;color:var(--ex-muted)}
.ex-btn{display:inline-flex;align-items:center;gap:8px;border-radius:12px;height:44px;padding:0 14px;border:1px solid var(--ex-line);background:#fff;color:var(--ex-ink);font-weight:900;text-decoration:none;cursor:pointer;transition:box-shadow .16s,transform .16s}
.ex-btn:hover{transform:translateY(-1px);box-shadow:0 12px 22px rgba(15,23,42,.06)}
.ex-btn-primary{background:var(--ex-primary);border-color:var(--ex-primary);color:#fff}
.ex-btn-outline{color:var(--ex-primary);border-color:var(--ex-primary)}
.ex-btn-outline:hover{background:var(--ex-primary);color:#fff}
.ex-card{background:var(--ex-surface);border:1px solid var(--ex-line);border-radius:18px;overflow:hidden;margin-top:16px}
.ex-card-h{padding:14px 16px;border-bottom:1px solid var(--ex-line);display:flex;justify-content:space-between;align-items:center}
.ex-card-t{margin:0;color:var(--ex-ink);font-weight:900}
.ex-card-b{padding:16px}
.ex-grid{display:grid;grid-template-columns:repeat(12,1fr);gap:14px}
.ex-col-12{grid-column:span 12}
.ex-col-6{grid-column:span 6}
.ex-col-4{grid-column:span 4}
.ex-col-3{grid-column:span 3}
@media(max-width:992px){.ex-col-6,.ex-col-4,.ex-col-3{grid-column:span 12}}
.ex-field{display:flex;flex-direction:column;gap:6px}
.ex-label{font-weight:900;color:var(--ex-ink);font-size:13px}
.ex-req::after{content:" *";color:var(--ex-danger)}
.ex-input,.ex-select,.ex-text{border-radius:12px;border:1px solid var(--ex-line);padding:10px 12px;min-height:44px;width:100%;font-size:14px;background:#fff}
.ex-text{min-height:120px;resize:vertical}
.ex-note{color:var(--ex-muted);font-size:12px}
.ex-switch{display:flex;gap:12px;align-items:flex-start;border:1px solid var(--ex-line);border-radius:14px;padding:12px;background:#fff}
.ex-switch .form-check-input{width:46px;height:24px;cursor:pointer}
.ex-switch strong{display:block;color:var(--ex-ink)}
.ex-toolbar{display:flex;justify-content:flex-end;gap:10px;flex-wrap:wrap;margin-top:16px}
.is-invalid{border-color:#dc3545}
.invalid-feedback{color:#dc3545;font-size:12px}
.ex-sticky{margin-top: 20px;position:sticky;bottom:8px;background:#fff;border:1px solid var(--ex-line);border-radius:14px;padding:10px 12px;box-shadow:0 12px 28px rgba(15,23,42,.08);display:flex;justify-content:flex-end;gap:10px}
.ex-chips{display:flex;gap:8px;flex-wrap:wrap}
.ex-chip{padding:7px 12px;border-radius:999px;border:1px solid var(--ex-line);background:#fff;font-size:12px;font-weight:800;color:var(--ex-ink);cursor:pointer}
.ex-chip.active{border-color:var(--ex-primary);color:var(--ex-primary);background:rgba(0,85,210,.08)}
@media(max-width:768px){.ex-hero{padding:14px}.ex-btn{height:46px}}
</style>
@endsection

@section('content')
<div class="ex-shell">
  <div class="ex-hero">
    <div>
      <h2>{{ __('panel.create_exam') }}</h2>
      <p>{{ __('panel.create_new_exam_desc') }}</p>
    </div>
    <a href="{{ route('teacher.exams.index') }}" class="ex-btn"><i class="fas fa-arrow-left"></i>{{ __('panel.back_to_exams') }}</a>
  </div>

  @if ($errors->any())
    <div class="ex-card" style="margin-top:16px">
      <div class="ex-card-b">
        <div class="alert alert-danger m-0">
          <ul class="m-0 ps-3">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
  @endif

  <form action="{{ route('teacher.exams.store') }}" method="POST" id="exForm">
    @csrf

    <div class="ex-card">
      <div class="ex-card-h">
        <h5 class="ex-card-t"><i class="fas fa-info-circle me-2"></i>{{ __('panel.basic_information') }}</h5>
        <span class="ex-note">{{ __('panel.make_sure_all_fields_valid') }}</span>
      </div>
      <div class="ex-card-b">
        <div class="ex-grid">
          <div class="ex-col-6">
            <div class="ex-field">
              <label class="ex-label ex-req">{{ __('panel.exam_title_en') }}</label>
              <input type="text" name="title_en" class="ex-input @error('title_en') is-invalid @enderror" value="{{ old('title_en') }}" placeholder="{{ __('panel.enter_exam_title_en') }}">
              @error('title_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>
          <div class="ex-col-6">
            <div class="ex-field">
              <label class="ex-label ex-req">{{ __('panel.exam_title_ar') }}</label>
              <input type="text" name="title_ar" class="ex-input @error('title_ar') is-invalid @enderror" value="{{ old('title_ar') }}" placeholder="{{ __('panel.enter_exam_title_ar') }}">
              @error('title_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>
          <div class="ex-col-6">
            <div class="ex-field">
              <label class="ex-label">{{ __('panel.description_en') }}</label>
              <textarea name="description_en" class="ex-text @error('description_en') is-invalid @enderror" placeholder="{{ __('panel.enter_description_en') }}">{{ old('description_en') }}</textarea>
              @error('description_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>
          <div class="ex-col-6">
            <div class="ex-field">
              <label class="ex-label">{{ __('panel.description_ar') }}</label>
              <textarea name="description_ar" class="ex-text @error('description_ar') is-invalid @enderror" placeholder="{{ __('panel.enter_description_ar') }}">{{ old('description_ar') }}</textarea>
              @error('description_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="ex-card">
      <div class="ex-card-h">
        <h5 class="ex-card-t"><i class="fas fa-graduation-cap me-2"></i>{{ __('panel.course_subject_info') }}</h5>
      </div>
      <div class="ex-card-b">
        <div class="ex-grid">
          <div class="ex-col-4">
            <div class="ex-field">
              <label class="ex-label ex-req">{{ __('panel.subject') }}</label>
              <select name="subject_id" id="ex_subject" class="ex-select @error('subject_id') is-invalid @enderror">
                <option value="">{{ __('panel.select_subject') }}</option>
                @foreach ($subjects as $subject)
                  <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                    {{ app()->getLocale() === 'ar' ? $subject->name_ar : $subject->name_en }}
                  </option>
                @endforeach
              </select>
              @error('subject_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>
          <div class="ex-col-4">
            <div class="ex-field">
              <label class="ex-label">{{ __('panel.course') }}</label>
              <select name="course_id" id="ex_course" class="ex-select @error('course_id') is-invalid @enderror">
                <option value="">{{ __('panel.select_course') }}</option>
                @foreach ($courses as $course)
                  <option value="{{ $course->id }}" data-subject="{{ $course->subject_id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                    {{ app()->getLocale() === 'ar' ? $course->title_ar : $course->title_en }}
                  </option>
                @endforeach
              </select>
              @error('course_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>
          <div class="ex-col-4">
            <div class="ex-field">
              <label class="ex-label">{{ __('panel.section') }}</label>
              <select name="section_id" id="ex_section" class="ex-select @error('section_id') is-invalid @enderror">
                <option value="">{{ __('panel.select_section') }}</option>
              </select>
              @error('section_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>
        </div>
        <div class="ex-chips" style="margin-top:10px">
          <button type="button" class="ex-chip" data-quick="duration-30">30 {{ __('panel.minutes') }}</button>
          <button type="button" class="ex-chip" data-quick="duration-60">60 {{ __('panel.minutes') }}</button>
          <button type="button" class="ex-chip" data-quick="attempts-1">{{ __('panel.one_attempt') }}</button>
          <button type="button" class="ex-chip" data-quick="attempts-3">3 {{ __('panel.attempts') }}</button>
        </div>
      </div>
    </div>

    <div class="ex-card">
      <div class="ex-card-h">
        <h5 class="ex-card-t"><i class="fas fa-cog me-2"></i>{{ __('panel.exam_settings') }}</h5>
      </div>
      <div class="ex-card-b">
        <div class="ex-grid">
          <div class="ex-col-3">
            <div class="ex-field">
              <label class="ex-label">{{ __('panel.duration_minutes') }}</label>
              <input type="number" name="duration_minutes" class="ex-input @error('duration_minutes') is-invalid @enderror" value="{{ old('duration_minutes') }}" min="1" placeholder="{{ __('panel.unlimited') }}">
              <div class="ex-note">{{ __('panel.leave_empty_unlimited') }}</div>
              @error('duration_minutes')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>
          <div class="ex-col-3">
            <div class="ex-field">
              <label class="ex-label ex-req">{{ __('panel.attempts_allowed') }}</label>
              <input type="number" name="attempts_allowed" class="ex-input @error('attempts_allowed') is-invalid @enderror" value="{{ old('attempts_allowed',1) }}" min="1" max="10">
              @error('attempts_allowed')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>
          <div class="ex-col-3">
            <div class="ex-field">
              <label class="ex-label ex-req">{{ __('panel.passing_grade') }}</label>
              <div class="input-group">
                <input type="number" name="passing_grade" class="form-control ex-input @error('passing_grade') is-invalid @enderror" value="{{ old('passing_grade',60) }}" min="0" max="100" step="0.01">
                <span class="input-group-text">%</span>
              </div>
              @error('passing_grade')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>
          <div class="ex-col-3">
            <div class="ex-field">
              <label class="ex-label">{{ __('panel.start_date') }}</label>
              <input type="datetime-local" name="start_date" class="ex-input @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}">
              @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>
          <div class="ex-col-3">
            <div class="ex-field">
              <label class="ex-label">{{ __('panel.end_date') }}</label>
              <input type="datetime-local" name="end_date" class="ex-input @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}">
              @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="ex-card">
      <div class="ex-card-h">
        <h5 class="ex-card-t"><i class="fas fa-eye me-2"></i>{{ __('panel.display_options') }}</h5>
      </div>
      <div class="ex-card-b">
        <div class="ex-grid">
          <div class="ex-col-3">
            <div class="ex-switch">
              <input class="form-check-input" type="checkbox" name="shuffle_questions" id="shuffle_questions" value="1" {{ old('shuffle_questions') ? 'checked' : '' }}>
              <label for="shuffle_questions"><strong>{{ __('panel.shuffle_questions') }}</strong><small class="ex-note d-block">{{ __('panel.shuffle_questions_desc') }}</small></label>
            </div>
          </div>
          <div class="ex-col-3">
            <div class="ex-switch">
              <input class="form-check-input" type="checkbox" name="shuffle_options" id="shuffle_options" value="1" {{ old('shuffle_options') ? 'checked' : '' }}>
              <label for="shuffle_options"><strong>{{ __('panel.shuffle_options') }}</strong><small class="ex-note d-block">{{ __('panel.shuffle_options_desc') }}</small></label>
            </div>
          </div>
          <div class="ex-col-3">
            <div class="ex-switch">
              <input class="form-check-input" type="checkbox" name="show_results_immediately" id="show_results_immediately" value="1" {{ old('show_results_immediately') ? 'checked' : '' }}>
              <label for="show_results_immediately"><strong>{{ __('panel.show_results_immediately') }}</strong><small class="ex-note d-block">{{ __('panel.show_results_immediately_desc') }}</small></label>
            </div>
          </div>
          <div class="ex-col-3">
            <div class="ex-switch">
              <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
              <label for="is_active"><strong>{{ __('panel.active') }}</strong><small class="ex-note d-block">{{ __('panel.exam_active_desc') }}</small></label>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="ex-sticky">
      <a href="{{ route('teacher.exams.index') }}" class="ex-btn">{{ __('panel.cancel') }}</a>
      <button type="submit" class="ex-btn ex-btn-primary"><i class="fas fa-save"></i>{{ __('panel.create_exam') }}</button>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded',function(){
  var subjectSel=document.getElementById('ex_subject');
  var courseSel=document.getElementById('ex_course');
  var sectionSel=document.getElementById('ex_section');
  var form=document.getElementById('exForm');

  function filterCoursesBySubject(){
    var sid=subjectSel.value;
    if(!courseSel)return;
    if(!sid){
      [...courseSel.options].forEach(function(o){o.style.display='block'});
      return;
    }
    [...courseSel.options].forEach(function(o,i){
      if(i===0)return;
      var s=o.getAttribute('data-subject');
      o.style.display=(s===sid)?'block':'none';
    });
    if(courseSel.selectedOptions[0] && courseSel.selectedOptions[0].style.display==='none'){courseSel.value=''}
    sectionSel.innerHTML='<option value="">{{ __('panel.select_section') }}</option>';
    var url='{{ route("teacher.exams.subjects.courses", ":id") }}'.replace(':id',sid);
    fetch(url,{headers:{'X-Requested-With':'XMLHttpRequest'}}).then(r=>r.ok?r.json():Promise.reject()).then(function(cs){
      courseSel.innerHTML='<option value="">{{ __('panel.select_course') }}</option>';
      (cs||[]).forEach(function(c){
        var t='{{ app()->getLocale() }}'==='ar'?c.title_ar:c.title_en;
        var opt=document.createElement('option');opt.value=c.id;opt.textContent=t;opt.setAttribute('data-subject',c.subject_id);
        courseSel.appendChild(opt);
      });
    }).catch(function(){});
  }

  function loadSectionsForCourse(){
    var cid=courseSel.value;
    sectionSel.innerHTML='<option value="">{{ __('panel.select_section') }}</option>';
    if(!cid)return;
    var url='{{ route("teacher.exams.courses.sections", ":id") }}'.replace(':id',cid);
    fetch(url,{headers:{'X-Requested-With':'XMLHttpRequest'}}).then(r=>r.ok?r.json():Promise.reject()).then(function(ss){
      (ss||[]).forEach(function(s){
        var opt=document.createElement('option');opt.value=s.id;opt.textContent=s.title;sectionSel.appendChild(opt);
      });
    }).catch(function(){});
  }

  if(subjectSel){subjectSel.addEventListener('change',filterCoursesBySubject)}
  if(courseSel){courseSel.addEventListener('change',loadSectionsForCourse)}

  document.querySelectorAll('.ex-chip').forEach(function(ch){
    ch.addEventListener('click',function(){
      var k=this.getAttribute('data-quick');
      document.querySelectorAll('.ex-chip').forEach(x=>x.classList.remove('active'));
      this.classList.add('active');
      if(k==='duration-30'){var d=document.querySelector('[name="duration_minutes"]');if(d)d.value=30}
      if(k==='duration-60'){var d2=document.querySelector('[name="duration_minutes"]');if(d2)d2.value=60}
      if(k==='attempts-1'){var a1=document.querySelector('[name="attempts_allowed"]');if(a1)a1.value=1}
      if(k==='attempts-3'){var a3=document.querySelector('[name="attempts_allowed"]');if(a3)a3.value=3}
    });
  });

  if(form){
    form.addEventListener('submit',function(e){
      var ok=true;
      ['title_en','title_ar','subject_id','attempts_allowed','passing_grade'].forEach(function(n){
        var el=form.querySelector('[name="'+n+'"]');
        if(el && !String(el.value||'').trim()){el.classList.add('is-invalid');ok=false}else if(el){el.classList.remove('is-invalid')}
      });
      var sd=form.querySelector('[name="start_date"]');
      var ed=form.querySelector('[name="end_date"]');
      if(sd && ed && sd.value && ed.value){
        var s=new Date(sd.value);var t=new Date(ed.value);
        if(t<=s){ed.classList.add('is-invalid');ok=false;alert('{{ __("panel.end_date_must_be_after_start_date") }}')}
      }
      var pg=form.querySelector('[name="passing_grade"]');
      if(pg){
        var v=parseFloat(pg.value||'0');
        if(isNaN(v)||v<0||v>100){pg.classList.add('is-invalid');ok=false;alert('{{ __("panel.passing_grade_must_be_between_0_100") }}')}
      }
      if(!ok)e.preventDefault();
    });
  }

  document.querySelectorAll('textarea').forEach(function(t){
    function autoH(){t.style.height='auto';t.style.height=(t.scrollHeight)+'px'}
    t.addEventListener('input',autoH);autoH();
  });

  if(subjectSel && subjectSel.value){filterCoursesBySubject()}
  if(courseSel && courseSel.value){loadSectionsForCourse()}
});
</script>
@endpush
