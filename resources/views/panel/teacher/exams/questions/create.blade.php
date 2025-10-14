@extends('layouts.app')

@section('title', __('panel.create_question'))
@section('page_title', __('panel.create_question'))

@section('styles')
<style>
:root{
  --cr-max:1300px;
  --cr-bg:#f6f8fc;
  --cr-surface:#ffffff;
  --cr-ink:#0f172a;
  --cr-muted:#667085;
  --cr-line:#e5e7eb;
  --cr-primary:#0055D2;
  --cr-success:#10b981;
  --cr-danger:#ef4444;
  --cr-warn:#f59e0b;
}
body{background:var(--cr-bg)}
.cr-shell{max-width:var(--cr-max);margin:0 auto;padding:16px}
.cr-head{border:1px solid var(--cr-line);border-radius:18px;padding:18px;background:radial-gradient(1200px 300px at 20% -10%, rgba(0,85,210,.06), transparent 60%),linear-gradient(180deg,#fff,#f8fbff);display:flex;justify-content:space-between;align-items:end;gap:10px;flex-wrap:wrap}
.cr-h-title{margin:0;color:var(--cr-ink);font-weight:900}
.cr-h-sub{margin:6px 0 0;color:var(--cr-muted)}
.cr-btn{display:inline-flex;align-items:center;gap:8px;border-radius:12px;height:44px;padding:0 14px;border:1px solid var(--cr-line);background:#fff;color:var(--cr-ink);font-weight:900;text-decoration:none;cursor:pointer;transition:box-shadow .16s,transform .16s}
.cr-btn:hover{transform:translateY(-1px);box-shadow:0 12px 22px rgba(15,23,42,.06)}
.cr-btn-primary{background:var(--cr-primary);border-color:var(--cr-primary);color:#fff}
.cr-btn-ghost{background:#fff;border:1px solid var(--cr-line);color:var(--cr-ink)}
.cr-card{background:var(--cr-surface);border:1px solid var(--cr-line);border-radius:18px;overflow:hidden;margin-top:16px}
.cr-card-h{padding:14px 16px;border-bottom:1px solid var(--cr-line);display:flex;justify-content:space-between;align-items:center}
.cr-card-t{margin:0;color:var(--cr-ink);font-weight:900}
.cr-card-b{padding:16px}
.cr-grid{display:grid;grid-template-columns:repeat(12,1fr);gap:14px}
.cr-col-12{grid-column:span 12}
.cr-col-6{grid-column:span 6}
.cr-col-4{grid-column:span 4}
@media(max-width:992px){.cr-col-6,.cr-col-4{grid-column:span 12}}
.cr-field{display:flex;flex-direction:column;gap:6px}
.cr-label{font-weight:900;color:var(--cr-ink);font-size:13px}
.cr-req::after{content:" *";color:var(--cr-danger)}
.cr-input,.cr-select,.cr-text{border-radius:12px;border:1px solid var(--cr-line);padding:10px 12px;min-height:44px;width:100%;font-size:14px}
.cr-text{min-height:120px;resize:vertical}
.cr-note{color:var(--cr-muted);font-size:12px}
.cr-segs{display:flex;gap:8px;flex-wrap:wrap}
.cr-seg{display:inline-flex;align-items:center;gap:8px;border:1px solid var(--cr-line);border-radius:12px;padding:10px 12px;font-weight:900;cursor:pointer;background:#fff}
.cr-seg.cr-active{border-color:var(--cr-primary);box-shadow:0 8px 20px rgba(0,85,210,.12)}
.cr-soft{display:inline-flex;align-items:center;gap:6px;border-radius:999px;background:rgba(0,85,210,.08);color:var(--cr-primary);padding:6px 10px;font-size:12px;font-weight:900}
.cr-options{display:flex;flex-direction:column;gap:12px}
.cr-opt{border:1px solid var(--cr-line);border-radius:14px;padding:12px;background:#fff}
.cr-opt-h{display:flex;justify-content:space-between;align-items:center;margin-bottom:10px}
.cr-opt-t{margin:0;font-weight:900;color:var(--cr-ink)}
.cr-opt-g{display:grid;grid-template-columns:1fr 1fr 170px;gap:10px}
@media(max-width:992px){.cr-opt-g{grid-template-columns:1fr}}
.cr-footer{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-top:16px}
.cr-sticky{position:sticky;bottom:8px;z-index:3;background:#fff;border:1px solid var(--cr-line);border-radius:14px;padding:10px 12px;box-shadow:0 12px 28px rgba(15,23,42,.08)}
.is-invalid{border-color:#dc3545}
.invalid-feedback{color:#dc3545;font-size:12px}
@media(max-width:768px){
  .cr-head{padding:14px}
  .cr-btn{height:46px}
}
</style>
@endsection

@section('content')
<div class="cr-shell">
  <div class="cr-head">
    <div>
      <h2 class="cr-h-title">{{ __('panel.create_question') }}</h2>
      <p class="cr-h-sub">{{ __('panel.exam') }}: {{ app()->getLocale() == 'ar' ? $exam->title_ar : $exam->title_en }}</p>
    </div>
    <a href="{{ route('teacher.exams.exam_questions.index', $exam) }}" class="cr-btn"><i class="fas fa-arrow-left"></i>{{ __('panel.back_to_questions') }}</a>
  </div>

  <form action="{{ route('teacher.exams.exam_questions.store', $exam) }}" method="POST" id="crForm">
    @csrf

    <div class="cr-card">
      <div class="cr-card-h">
        <h5 class="cr-card-t">{{ __('panel.titles') }}</h5>
        <span class="cr-soft">{{ __('panel.make_sure_all_fields_valid') }}</span>
      </div>
      <div class="cr-card-b">
        <div class="cr-grid">
          <div class="cr-col-6">
            <div class="cr-field">
              <label class="cr-label cr-req" for="cr_title_en">{{ __('panel.question_title_en') }}</label>
              <input id="cr_title_en" type="text" name="title_en" value="{{ old('title_en') }}" class="cr-input @error('title_en') is-invalid @enderror" placeholder="{{ __('panel.enter_question_title_en') }}">
              @error('title_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>
          <div class="cr-col-6">
            <div class="cr-field">
              <label class="cr-label cr-req" for="cr_title_ar">{{ __('panel.question_title_ar') }}</label>
              <input id="cr_title_ar" type="text" name="title_ar" value="{{ old('title_ar') }}" class="cr-input @error('title_ar') is-invalid @enderror" placeholder="{{ __('panel.enter_question_title_ar') }}" dir="rtl">
              @error('title_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>

          <div class="cr-col-6">
            <div class="cr-field">
              <label class="cr-label cr-req" for="cr_question_en">{{ __('panel.question_text_en') }}</label>
              <textarea id="cr_question_en" name="question_en" class="cr-text @error('question_en') is-invalid @enderror" placeholder="{{ __('panel.enter_question_text_en') }}">{{ old('question_en') }}</textarea>
              @error('question_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>
          <div class="cr-col-6">
            <div class="cr-field">
              <label class="cr-label cr-req" for="cr_question_ar">{{ __('panel.question_text_ar') }}</label>
              <textarea id="cr_question_ar" name="question_ar" class="cr-text @error('question_ar') is-invalid @enderror" placeholder="{{ __('panel.enter_question_text_ar') }}" dir="rtl">{{ old('question_ar') }}</textarea>
              @error('question_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>

          <div class="cr-col-4">
            <div class="cr-field">
              <label class="cr-label">{{ __('panel.course') }}</label>
              <input type="hidden" name="course_id" value="{{ $exam->course_id }}">
              <input type="text" class="cr-input" value="{{ $exam->course ? (app()->getLocale() === 'ar' ? $exam->course->title_ar : $exam->course->title_en) : __('panel.no_course_assigned') }}" readonly>
              <div class="cr-note">{{ __('panel.auto_assigned_from_exam') }}</div>
            </div>
          </div>

           <!-- Photo Upload Field -->
          <div class="cr-col-12">
            <div class="cr-field">
              <label class="cr-label" for="cr_photo">{{ __('panel.question_photo') }}</label>
              <input id="cr_photo" type="file" name="photo" class="cr-input @error('photo') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg,image/gif">
              <div class="cr-note">{{ __('panel.optional_image_for_question') }} (Max: 2MB)</div>
              @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
              
              <!-- Image Preview -->
              <div id="photoPreview" style="display:none; margin-top:10px;">
                <img id="previewImg" src="" alt="Preview" style="max-width:300px; max-height:300px; border-radius:8px; border:1px solid #ddd;">
                <button type="button" id="removePhoto" class="cr-btn cr-btn-danger mt-2" style="font-size:12px; padding:4px 8px;">
                  <i class="fas fa-times"></i> {{ __('panel.remove_photo') }}
                </button>
              </div>
            </div>
          </div>

          <div class="cr-col-4">
            <div class="cr-field">
              <label class="cr-label cr-req">{{ __('panel.question_type') }}</label>
              <div class="cr-segs" id="crTypeSeg">
                <label class="cr-seg" data-val="multiple_choice"><input type="radio" class="d-none" name="type_radio"><span>{{ __('panel.multiple_choice') }}</span></label>
                <label class="cr-seg" data-val="true_false"><input type="radio" class="d-none" name="type_radio"><span>{{ __('panel.true_false') }}</span></label>
                <label class="cr-seg" data-val="essay"><input type="radio" class="d-none" name="type_radio"><span>{{ __('panel.essay') }}</span></label>
              </div>
              <select id="cr_type" name="type" class="cr-select mt-2 @error('type') is-invalid @enderror">
                <option value="">{{ __('panel.select_question_type') }}</option>
                <option value="multiple_choice" {{ old('type') == 'multiple_choice' ? 'selected' : '' }}>{{ __('panel.multiple_choice') }}</option>
                <option value="true_false" {{ old('type') == 'true_false' ? 'selected' : '' }}>{{ __('panel.true_false') }}</option>
                <option value="essay" {{ old('type') == 'essay' ? 'selected' : '' }}>{{ __('panel.essay') }}</option>
              </select>
              @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>

          <div class="cr-col-4">
            <div class="cr-field">
              <label class="cr-label cr-req" for="cr_grade">{{ __('panel.grade') }}</label>
              <input id="cr_grade" type="number" name="grade" value="{{ old('grade',1) }}" step="0.25" min="0.25" class="cr-input @error('grade') is-invalid @enderror" placeholder="1.00">
              @error('grade')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>

          <div class="cr-col-6">
            <div class="cr-field">
              <label class="cr-label" for="cr_explanation_en">{{ __('panel.explanation_en') }}</label>
              <textarea id="cr_explanation_en" name="explanation_en" rows="3" class="cr-text @error('explanation_en') is-invalid @enderror" placeholder="{{ __('panel.enter_explanation_en') }}">{{ old('explanation_en') }}</textarea>
              @error('explanation_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>
          <div class="cr-col-6">
            <div class="cr-field">
              <label class="cr-label" for="cr_explanation_ar">{{ __('panel.explanation_ar') }}</label>
              <textarea id="cr_explanation_ar" name="explanation_ar" rows="3" class="cr-text @error('explanation_ar') is-invalid @enderror" placeholder="{{ __('panel.enter_explanation_ar') }}" dir="rtl">{{ old('explanation_ar') }}</textarea>
              @error('explanation_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>
        </div>
      </div>
    </div>

    <div id="cr-mc" class="cr-card" style="display:none">
      <div class="cr-card-h">
        <h5 class="cr-card-t">{{ __('panel.answer_options') }}</h5>
        <span class="cr-soft">{{ __('panel.minimum_two_options_required') }}</span>
      </div>
      <div class="cr-card-b">
        <div id="cr-options" class="cr-options"></div>
        <div class="cr-segs" style="margin-top:8px">
          <button type="button" class="cr-btn-ghost" id="crAddOpt"><i class="fas fa-plus"></i> {{ __('panel.add_option') }}</button>
          <button type="button" class="cr-btn-ghost" id="crAddTwo"><i class="fas fa-layer-group"></i> +2</button>
        </div>
      </div>
    </div>

    <div id="cr-tf" class="cr-card" style="display:none">
      <div class="cr-card-h">
        <h5 class="cr-card-t">{{ __('panel.correct_answer') }}</h5>
      </div>
      <div class="cr-card-b">
        <div class="cr-segs" id="crTfSeg">
          <label class="cr-seg"><input type="radio" class="d-none" name="true_false_answer" value="1" {{ old('true_false_answer')=='1'?'checked':'' }}><span><i class="fas fa-check" style="color:var(--cr-success)"></i> {{ __('panel.true') }}</span></label>
          <label class="cr-seg"><input type="radio" class="d-none" name="true_false_answer" value="0" {{ old('true_false_answer')=='0'?'checked':'' }}><span><i class="fas fa-times" style="color:var(--cr-danger)"></i> {{ __('panel.false') }}</span></label>
        </div>
        @error('true_false_answer')<div class="invalid-feedback" style="display:block">{{ $message }}</div>@enderror
      </div>
    </div>

    <div id="cr-essay" class="cr-card" style="display:none">
      <div class="cr-card-h">
        <h5 class="cr-card-t">{{ __('panel.essay') }}</h5>
      </div>
      <div class="cr-card-b">
        <div class="alert alert-info m-0 d-flex align-items-center gap-2"><i class="fas fa-info-circle"></i><span>{{ __('panel.essay_question_note') }}</span></div>
      </div>
    </div>

    <div class="cr-footer cr-sticky">
      <a href="{{ route('teacher.exams.exam_questions.index', $exam) }}" class="cr-btn">{{ __('panel.cancel') }}</a>
      <button type="submit" class="cr-btn cr-btn-primary"><i class="fas fa-save"></i>{{ __('panel.create_question') }}</button>
    </div>
  </form>
</div>

<script>
// Photo preview functionality
document.getElementById('cr_photo').addEventListener('change', function(e) {
  const file = e.target.files[0];
  if (file) {
    // Check file size (2MB = 2097152 bytes)
    if (file.size > 2097152) {
      alert('{{ __("panel.file_size_exceeds_2mb") }}');
      this.value = '';
      return;
    }

    const reader = new FileReader();
    reader.onload = function(event) {
      document.getElementById('previewImg').src = event.target.result;
      document.getElementById('photoPreview').style.display = 'block';
    };
    reader.readAsDataURL(file);
  }
});

// Remove photo
document.getElementById('removePhoto').addEventListener('click', function() {
  document.getElementById('cr_photo').value = '';
  document.getElementById('photoPreview').style.display = 'none';
  document.getElementById('previewImg').src = '';
});
</script>

<script>
let crCount=0;
function crLetter(i){return String.fromCharCode(65+i)}
function crSyncType(){
  const t=document.getElementById('cr_type').value;
  document.getElementById('cr-mc').style.display=t==='multiple_choice'?'block':'none';
  document.getElementById('cr-tf').style.display=t==='true_false'?'block':'none';
  document.getElementById('cr-essay').style.display=t==='essay'?'block':'none';
  document.querySelectorAll('#crTypeSeg .cr-seg').forEach(s=>{s.classList.toggle('cr-active',s.dataset.val===t);s.querySelector('input').checked=(s.dataset.val===t)});
  if(t==='multiple_choice'&&crCount===0){for(let i=0;i<4;i++) crAddOption()}
  if(t==='true_false'){crSegSync(document.getElementById('crTfSeg'))}
}
function crSegSync(group){group.querySelectorAll('.cr-seg').forEach(s=>s.classList.toggle('cr-active',s.querySelector('input').checked))}
function crAddOption(prefill){
  crCount++;const idx=crCount-1;
  const en=prefill&&prefill.en?prefill.en:'';const ar=prefill&&prefill.ar?prefill.ar:'';
  const ok=prefill&&prefill.ok?'checked':'';
  const box=document.createElement('div');box.className='cr-opt';box.dataset.index=idx;
  box.innerHTML=
    '<div class="cr-opt-h">'+
      '<h6 class="cr-opt-t">{{ __("panel.option") }} '+crLetter(idx)+'</h6>'+
      '<div class="cr-segs">'+
        '<button type="button" class="cr-btn-ghost btn-sm" data-act="dup"><i class="fas fa-clone"></i></button>'+
        '<button type="button" class="cr-btn-ghost btn-sm text-danger" data-act="del"><i class="fas fa-trash"></i></button>'+
      '</div>'+
    '</div>'+
    '<div class="cr-opt-g">'+
      '<div class="cr-field"><label class="cr-label">{{ __("panel.option_text_en") }}</label><input type="text" class="cr-input" name="options['+idx+'][option_en]" value="'+en+'" placeholder="{{ __("panel.enter_option_en") }}" required></div>'+
      '<div class="cr-field"><label class="cr-label">{{ __("panel.option_text_ar") }}</label><input type="text" class="cr-input" name="options['+idx+'][option_ar]" value="'+ar+'" placeholder="{{ __("panel.enter_option_ar") }}" dir="rtl" required></div>'+
      '<div class="cr-field"><label class="cr-label">{{ __("panel.correct") }}</label><div class="form-check" style="padding-top:10px"><input class="form-check-input" type="checkbox" name="options['+idx+'][is_correct]" value="1" '+ok+' id="cr-ok-'+idx+'"><label class="form-check-label" for="cr-ok-'+idx+'">{{ __("panel.correct_answer") }}</label></div></div>'+
    '</div>';
  document.getElementById('cr-options').appendChild(box);
}
function crRenumber(){
  const items=[...document.querySelectorAll('#cr-options .cr-opt')];
  items.forEach((el,i)=>{
    el.dataset.index=i;
    el.querySelector('.cr-opt-t').textContent='{{ __("panel.option") }} '+crLetter(i);
    el.querySelectorAll('input,textarea,select,label').forEach(inp=>{
      if(inp.name&&inp.name.startsWith('options[')){inp.name=inp.name.replace(/options\[\d+\]/,'options['+i+']')}
      if(inp.id&&/^cr-ok-\d+$/.test(inp.id)){inp.id='cr-ok-'+i}
      if(inp.tagName==='LABEL'&&inp.getAttribute('for')&&/^cr-ok-\d+$/.test(inp.getAttribute('for'))){inp.setAttribute('for','cr-ok-'+i)}
    });
  });
  crCount=items.length;
}
document.addEventListener('DOMContentLoaded',function(){
  document.getElementById('cr_type').addEventListener('change',crSyncType);
  document.querySelectorAll('#crTypeSeg .cr-seg').forEach(seg=>seg.addEventListener('click',function(){document.getElementById('cr_type').value=this.dataset.val;crSyncType()}));
  const tf=document.getElementById('crTfSeg');if(tf){tf.querySelectorAll('input').forEach(r=>r.addEventListener('change',function(){crSegSync(tf)}))}
  document.getElementById('crAddOpt').addEventListener('click',function(){crAddOption()});
  document.getElementById('crAddTwo').addEventListener('click',function(){crAddOption();crAddOption()});
  document.getElementById('cr-options').addEventListener('click',function(e){
    const btn=e.target.closest('button');if(!btn)return;
    const act=btn.dataset.act;const item=btn.closest('.cr-opt');
    if(act==='del'){item.remove();crRenumber()}
    if(act==='dup'){
      const i=parseInt(item.dataset.index,10);
      const en=item.querySelector('input[name^="options['+i+'][option_en]"]').value;
      const ar=item.querySelector('input[name^="options['+i+'][option_ar]"]').value;
      const ok=item.querySelector('input[name^="options['+i+'][is_correct]"]').checked;
      crAddOption({en:en,ar:ar,ok:ok});crRenumber();
    }
  });
  const initType=document.getElementById('cr_type').value;if(initType){crSyncType()}
});
document.getElementById('crForm').addEventListener('submit',function(e){
  const t=document.getElementById('cr_type').value;
  if(t==='multiple_choice'){
    const items=[...document.querySelectorAll('#cr-options .cr-opt')];
    if(items.length<2){e.preventDefault();alert('{{ __("panel.minimum_two_options_required") }}');return}
    const ok=[...document.querySelectorAll('input[name*="[is_correct]"]')].filter(x=>x.checked);
    if(ok.length===0){e.preventDefault();alert('{{ __("panel.at_least_one_correct_answer_required") }}');return}
    crRenumber();
  }else if(t==='true_false'){
    const ch=document.querySelector('input[name="true_false_answer"]:checked');
    if(!ch){e.preventDefault();alert('{{ __("panel.please_select_correct_answer") }}');return}
  }
});
</script>
@endsection
