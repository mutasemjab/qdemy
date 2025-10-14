@extends('layouts.app')

@section('title', __('panel.edit_question'))
@section('page_title', __('panel.edit_question'))

@section('styles')
<style>
:root{
  --qx-container:1300px;
  --qx-bg:#f5f7fb;
  --qx-surface:#ffffff;
  --qx-ink:#0f172a;
  --qx-muted:#6b7280;
  --qx-line:#e5e7eb;
  --qx-primary:#0055D2;
  --qx-success:#10b981;
  --qx-danger:#ef4444;
}
body{background:var(--qx-bg)}
.qx-shell{max-width:var(--qx-container);margin:0 auto;padding:18px}
.qx-head{display:flex;justify-content:space-between;align-items:end;gap:12px;flex-wrap:wrap;margin-bottom:16px}
.qx-title{margin:0;color:var(--qx-ink);font-weight:900}
.qx-sub{margin:6px 0 0;color:var(--qx-muted)}
.qx-btn{display:inline-flex;align-items:center;gap:8px;border-radius:12px;padding:10px 14px;font-weight:900;text-decoration:none;cursor:pointer;border:1px solid var(--qx-line);background:#fff;color:var(--qx-ink)}
.qx-btn:hover{box-shadow:0 10px 22px rgba(15,23,42,.06)}
.qx-btn-primary{background:var(--qx-primary);border-color:var(--qx-primary);color:#fff}
.qx-surface{background:var(--qx-surface);border:1px solid var(--qx-line);border-radius:18px;overflow:hidden}
.qx-tabs{display:flex;gap:8px;padding:10px;background:#f0f5ff;border-bottom:1px solid var(--qx-line);position:sticky;top:0;z-index:3;overflow-x:auto;-webkit-overflow-scrolling:touch;scroll-snap-type:x mandatory}
.qx-tabs::-webkit-scrollbar{height:0}
.qx-tab{display:flex;align-items:center;gap:8px;padding:10px 14px;border:1px solid var(--qx-line);border-radius:12px;background:#fff;font-weight:900;color:var(--qx-ink);cursor:pointer;white-space:nowrap;scroll-snap-align:start}
.qx-tab.qx-active{border-color:var(--qx-primary);box-shadow:0 10px 22px rgba(0,85,210,.12)}
.qx-stage{padding:16px}
.qx-grid{display:grid;grid-template-columns:repeat(12,1fr);gap:14px}
.qx-col-12{grid-column:span 12}
.qx-col-6{grid-column:span 6}
.qx-col-4{grid-column:span 4}
@media(max-width:992px){.qx-col-6,.qx-col-4{grid-column:span 12}}
.qx-card{border:1px solid var(--qx-line);border-radius:16px;background:#fff;overflow:hidden}
.qx-card-h{padding:14px 16px;border-bottom:1px solid var(--qx-line);display:flex;justify-content:space-between;align-items:center}
.qx-card-t{margin:0;color:var(--qx-ink);font-weight:900}
.qx-card-b{padding:16px}
.qx-field{display:flex;flex-direction:column;gap:6px}
.qx-label{font-weight:900;color:var(--qx-ink);font-size:13px}
.qx-req::after{content:" *";color:#dc3545}
.qx-input,.qx-select,.qx-text{border-radius:12px;border:1px solid var(--qx-line);padding:10px 12px;min-height:44px;width:100%;font-size:14px}
.qx-text{min-height:120px;resize:vertical}
.qx-note{color:var(--qx-muted);font-size:12px}
.qx-segs{display:flex;gap:8px;flex-wrap:wrap}
.qx-seg{display:inline-flex;align-items:center;gap:8px;border:1px solid var(--qx-line);border-radius:12px;padding:10px 12px;font-weight:900;cursor:pointer;background:#fff}
.qx-seg.qx-active{border-color:var(--qx-primary);box-shadow:0 8px 20px rgba(0,85,210,.12)}
.qx-soft{display:inline-flex;align-items:center;gap:6px;border-radius:999px;background:rgba(0,85,210,.08);color:var(--qx-primary);padding:6px 10px;font-size:12px;font-weight:900}
.qx-panel{display:none}
.qx-panel.qx-show{display:block}
.qx-options{display:flex;flex-direction:column;gap:12px}
.qx-opt{border:1px solid var(--qx-line);border-radius:14px;padding:12px;background:#fff}
.qx-opt-h{display:flex;justify-content:space-between;align-items:center;margin-bottom:10px}
.qx-opt-t{margin:0;font-weight:900;color:var(--qx-ink)}
.qx-opt-g{display:grid;grid-template-columns:1fr 1fr 160px;gap:10px}
@media(max-width:992px){.qx-opt-g{grid-template-columns:1fr}}
.qx-btn-ghost{cursor: pointer;border:1px solid var(--qx-line);background:#fff;color:var(--qx-ink);border-radius:10px;padding:10px 12px;font-weight:800}
.qx-btn-ghost:hover{box-shadow:0 10px 22px rgba(15,23,42,.06)}
.qx-footer{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-top:16px}
.qx-preview{border:1px dashed var(--qx-line);border-radius:14px;padding:12px}
.qx-preview h6{margin:0 0 8px;font-weight:900;color:var(--qx-ink)}
.qx-kpis{display:grid;grid-template-columns:repeat(12,1fr);gap:12px;margin-top:6px}
.qx-kpis .qx-k{grid-column:span 4;border:1px solid var(--qx-line);border-radius:12px;background:#fff;padding:12px;text-align:center}
.qx-k .qx-v{font-weight:900;font-size:18px;color:var(--qx-ink)}
.qx-k .qx-l{font-size:12px;color:var(--qx-muted)}
.is-invalid{border-color:#dc3545}
.invalid-feedback{color:#dc3545;font-size:12px}
@media(max-width:768px){
  .qx-shell{padding:12px}
  .qx-head{align-items:start}
  .qx-title{font-size:20px}
  .qx-btn{padding:12px 14px}
  .qx-stage{padding:12px}
  .qx-card-b{padding:12px}
  .qx-input,.qx-select,.qx-text{font-size:16px;min-height:46px}
  .qx-tab{padding:10px 12px}
  .qx-footer{position:sticky;bottom:0;left:0;right:0;background:#fff;border:1px solid var(--qx-line);border-radius:14px;padding:10px 12px;box-shadow:0 12px 28px rgba(15,23,42,.08)}
  .qx-footer .qx-btn,.qx-footer .qx-btn-ghost{flex:1;justify-content:center}
  .qx-opt-h{gap:8px}
}
@media(max-width:480px){
  .qx-tabs{gap:6px}
  .qx-tab{padding:8px 10px;font-size:13px}
  .qx-label{font-size:12px}
  .qx-soft{display:none}
  .qx-kpis .qx-k{grid-column:span 12}
}
</style>
@endsection

@section('content')
<div class="qx-shell">
  <div class="qx-head">
    <div>
      <h2 class="qx-title">{{ __('panel.edit_question') }}</h2>
      <p class="qx-sub">{{ __('panel.exam') }}: {{ app()->getLocale() == 'ar' ? $exam->title_ar : $exam->title_en }}</p>
    </div>
    <a href="{{ route('teacher.exams.exam_questions.index', $exam) }}" class="qx-btn"><i class="fas fa-arrow-left"></i>{{ __('panel.back_to_questions') }}</a>
  </div>

  <form action="{{ route('teacher.exams.exam_questions.update', [$exam, $question]) }}" method="POST" id="qxForm" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="qx-surface">
      <div class="qx-tabs" id="qxTabs">
        <div class="qx-tab qx-active" data-qx-tab="basics"><i class="fas fa-heading"></i><span>{{ __('panel.titles') }}</span></div>
        <div class="qx-tab" data-qx-tab="content"><i class="fas fa-align-left"></i><span>{{ __('panel.contents') }}</span></div>
        <div class="qx-tab" data-qx-tab="settings"><i class="fas fa-sliders-h"></i><span>{{ __('panel.settings') }}</span></div>
        <div class="qx-tab" data-qx-tab="answers"><i class="fas fa-list"></i><span>{{ __('panel.answer_options') }}</span></div>
        <div class="qx-tab" data-qx-tab="preview"><i class="fas fa-eye"></i><span>{{ __('panel.view') }}</span></div>
      </div>

      <div class="qx-stage">
        <div class="qx-panel qx-show" id="qx-panel-basics">
          <div class="qx-grid">
            <div class="qx-col-12">
              <div class="qx-card">
                <div class="qx-card-h">
                  <h5 class="qx-card-t">{{ __('panel.titles') }}</h5>
                  <span class="qx-soft">{{ __('panel.make_sure_all_fields_valid') }}</span>
                </div>
                <div class="qx-card-b">
                  <div class="qx-grid">
                    <div class="qx-col-6">
                      <div class="qx-field">
                        <label class="qx-label qx-req" for="qx_title_en">{{ __('panel.question_title_en') }}</label>
                        <input type="text" id="qx_title_en" name="title_en" class="qx-input @error('title_en') is-invalid @enderror" value="{{ old('title_en', $question->title_en) }}" placeholder="{{ __('panel.enter_question_title_en') }}">
                        @error('title_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                      </div>
                    </div>
                    <div class="qx-col-6">
                      <div class="qx-field">
                        <label class="qx-label qx-req" for="qx_title_ar">{{ __('panel.question_title_ar') }}</label>
                        <input type="text" id="qx_title_ar" name="title_ar" class="qx-input @error('title_ar') is-invalid @enderror" value="{{ old('title_ar', $question->title_ar) }}" placeholder="{{ __('panel.enter_question_title_ar') }}" dir="rtl">
                        @error('title_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                      </div>
                    </div>
                  </div>
                  <div class="qx-kpis">
                    <div class="qx-k"><div class="qx-v">{{ $question->grade }}</div><div class="qx-l">{{ __('panel.grade') }}</div></div>
                    <div class="qx-k"><div class="qx-v">{{ ucfirst(str_replace('_',' ',$question->type)) }}</div><div class="qx-l">{{ __('panel.question_type') }}</div></div>
                    <div class="qx-k"><div class="qx-v">{{ $exam->course ? (app()->getLocale()=='ar'?$exam->course->title_ar:$exam->course->title_en) : '-' }}</div><div class="qx-l">{{ __('panel.course') }}</div></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="qx-panel" id="qx-panel-content">
          <div class="qx-grid">
            <div class="qx-col-6">
              <div class="qx-card">
                <div class="qx-card-h"><h5 class="qx-card-t">{{ __('panel.question_text_en') }}</h5></div>
                <div class="qx-card-b">
                  <div class="qx-field">
                    <textarea id="qx_question_en" name="question_en" class="qx-text @error('question_en') is-invalid @enderror" rows="6" placeholder="{{ __('panel.enter_question_text_en') }}">{{ old('question_en', $question->question_en) }}</textarea>
                    @error('question_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                  </div>
                </div>
              </div>
            </div>
            <div class="qx-col-6">
              <div class="qx-card">
                <div class="qx-card-h"><h5 class="qx-card-t">{{ __('panel.question_text_ar') }}</h5></div>
                <div class="qx-card-b">
                  <div class="qx-field">
                    <textarea id="qx_question_ar" name="question_ar" class="qx-text @error('question_ar') is-invalid @enderror" rows="6" placeholder="{{ __('panel.enter_question_text_ar') }}" dir="rtl">{{ old('question_ar', $question->question_ar) }}</textarea>
                    @error('question_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                  </div>
                </div>
              </div>
            </div>

            <!-- Photo Upload Section -->
            <div class="qx-col-12">
              <div class="qx-card">
                <div class="qx-card-h">
                  <h5 class="qx-card-t">{{ __('panel.question_photo') }}</h5>
                  <span class="qx-soft">{{ __('panel.optional_image_for_question') }}</span>
                </div>
                <div class="qx-card-b">
                  <div class="qx-field">
                    <label class="qx-label" for="qx_photo">{{ __('panel.upload_new_photo') }}</label>
                    <input id="qx_photo" type="file" name="photo" class="qx-input @error('photo') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg,image/gif">
                    <div class="qx-note">{{ __('panel.max_file_size_2mb') }}</div>
                    @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    
                    <!-- Current Photo Display -->
                    @if($question->photo)
                    <div id="currentPhoto" style="margin-top:15px;">
                      <label class="qx-label">{{ __('panel.current_photo') }}</label>
                      <div style="position:relative; display:inline-block;">
                        <img src="{{ asset('assets/admin/uploads/' . $question->photo) }}" alt="Current Photo" style="max-width:300px; max-height:300px; border-radius:8px; border:2px solid #e0e0e0; display:block;">
                        <button type="button" id="deleteCurrentPhoto" class="qx-btn qx-btn-danger" style="margin-top:8px; font-size:12px; padding:6px 12px;">
                          <i class="fas fa-trash"></i> {{ __('panel.delete_current_photo') }}
                        </button>
                        <input type="hidden" name="delete_photo" id="deletePhotoInput" value="0">
                      </div>
                    </div>
                    @endif

                    <!-- New Photo Preview -->
                    <div id="photoPreview" style="display:none; margin-top:15px;">
                      <label class="qx-label">{{ __('panel.new_photo_preview') }}</label>
                      <div style="position:relative; display:inline-block;">
                        <img id="previewImg" src="" alt="Preview" style="max-width:300px; max-height:300px; border-radius:8px; border:2px solid #4CAF50; display:block;">
                        <button type="button" id="removeNewPhoto" class="qx-btn qx-btn-danger" style="margin-top:8px; font-size:12px; padding:6px 12px;">
                          <i class="fas fa-times"></i> {{ __('panel.remove_new_photo') }}
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="qx-col-6">
              <div class="qx-card">
                <div class="qx-card-h"><h5 class="qx-card-t">{{ __('panel.explanation_en') }}</h5></div>
                <div class="qx-card-b">
                  <textarea id="qx_explanation_en" name="explanation_en" rows="4" class="qx-text @error('explanation_en') is-invalid @enderror" placeholder="{{ __('panel.enter_explanation_en') }}">{{ old('explanation_en', $question->explanation_en) }}</textarea>
                  @error('explanation_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>
            </div>
            <div class="qx-col-6">
              <div class="qx-card">
                <div class="qx-card-h"><h5 class="qx-card-t">{{ __('panel.explanation_ar') }}</h5></div>
                <div class="qx-card-b">
                  <textarea id="qx_explanation_ar" name="explanation_ar" rows="4" class="qx-text @error('explanation_ar') is-invalid @enderror" placeholder="{{ __('panel.enter_explanation_ar') }}" dir="rtl">{{ old('explanation_ar', $question->explanation_ar) }}</textarea>
                  @error('explanation_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="qx-panel" id="qx-panel-settings">
          <div class="qx-grid">
            <div class="qx-col-12">
              <div class="qx-card">
                <div class="qx-card-h"><h5 class="qx-card-t">{{ __('panel.settings') }}</h5></div>
                <div class="qx-card-b">
                  <div class="qx-grid">
                    <div class="qx-col-4">
                      <div class="qx-field">
                        <label class="qx-label">{{ __('panel.course') }}</label>
                        <input type="hidden" name="course_id" value="{{ $exam->course_id }}">
                        <input type="text" class="qx-input" value="{{ $exam->course ? (app()->getLocale() === 'ar' ? $exam->course->title_ar : $exam->course->title_en) : __('panel.no_course_assigned') }}" readonly>
                        <div class="qx-note">{{ __('panel.auto_assigned_from_exam') }}</div>
                      </div>
                    </div>
                    <div class="qx-col-4">
                      <div class="qx-field">
                        <label class="qx-label qx-req">{{ __('panel.question_type') }}</label>
                        <div class="qx-segs" id="qxTypeSeg">
                          <label class="qx-seg" data-qx-val="multiple_choice"><input type="radio" class="d-none" name="qx_type_radio"><span>{{ __('panel.multiple_choice') }}</span></label>
                          <label class="qx-seg" data-qx-val="true_false"><input type="radio" class="d-none" name="qx_type_radio"><span>{{ __('panel.true_false') }}</span></label>
                          <label class="qx-seg" data-qx-val="essay"><input type="radio" class="d-none" name="qx_type_radio"><span>{{ __('panel.essay') }}</span></label>
                        </div>
                        <select class="qx-select mt-2 @error('type') is-invalid @enderror" id="qx_type" name="type">
                          <option value="">{{ __('panel.select_question_type') }}</option>
                          <option value="multiple_choice" {{ old('type', $question->type) == 'multiple_choice' ? 'selected' : '' }}>{{ __('panel.multiple_choice') }}</option>
                          <option value="true_false" {{ old('type', $question->type) == 'true_false' ? 'selected' : '' }}>{{ __('panel.true_false') }}</option>
                          <option value="essay" {{ old('type', $question->type) == 'essay' ? 'selected' : '' }}>{{ __('panel.essay') }}</option>
                        </select>
                        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                      </div>
                    </div>
                    <div class="qx-col-4">
                      <div class="qx-field">
                        <label class="qx-label qx-req" for="qx_grade">{{ __('panel.grade') }}</label>
                        <input type="number" id="qx_grade" name="grade" class="qx-input @error('grade') is-invalid @enderror" value="{{ old('grade', $question->grade) }}" step="0.25" min="0.25" placeholder="1.00">
                        @error('grade')<div class="invalid-feedback">{{ $message }}</div>@enderror
                      </div>
                    </div>
                  </div>
                  <div class="qx-footer">
                    <div class="qx-note">{{ __('panel.make_sure_all_fields_valid') }}</div>
                    <div><button type="button" class="qx-btn" data-qx-go="answers">{{ __('panel.next') }}</button></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="qx-panel" id="qx-panel-answers">
          <div class="qx-grid">
            <div class="qx-col-12" id="qx-wrap-mc" style="display:none">
              <div class="qx-card">
                <div class="qx-card-h">
                  <h5 class="qx-card-t">{{ __('panel.answer_options') }}</h5>
                  <span class="qx-soft">{{ __('panel.minimum_two_options_required') }}</span>
                </div>
                <div class="qx-card-b">
                  <div id="qx-options" class="qx-options"></div>
                  <div class="qx-segs" style="margin-top:8px">
                    <button type="button" class="qx-btn-ghost" id="qxAddOption"><i class="fas fa-plus"></i> {{ __('panel.add_option') }}</button>
                    <button type="button" class="qx-btn-ghost" id="qxAddTwo"><i class="fas fa-layer-group"></i> +2</button>
                  </div>
                </div>
              </div>
            </div>

            <div class="qx-col-12" id="qx-wrap-tf" style="display:none">
              <div class="qx-card">
                <div class="qx-card-h"><h5 class="qx-card-t">{{ __('panel.correct_answer') }}</h5></div>
                <div class="qx-card-b">
                  <div class="qx-segs" id="qxTfSeg">
                    <label class="qx-seg"><input type="radio" class="d-none" name="true_false_answer" value="1" {{ old('true_false_answer', $question->type == 'true_false' && $question->options && $question->options->first() && $question->options->first()->is_correct ? '1' : '') == '1' ? 'checked' : '' }}><span><i class="fas fa-check" style="color:var(--qx-success)"></i> {{ __('panel.true') }}</span></label>
                    <label class="qx-seg"><input type="radio" class="d-none" name="true_false_answer" value="0" {{ old('true_false_answer', $question->type == 'true_false' && $question->options && $question->options->count() > 1 && $question->options->skip(1)->first() && $question->options->skip(1)->first()->is_correct ? '0' : '') == '0' ? 'checked' : '' }}><span><i class="fas fa-times" style="color:var(--qx-danger)"></i> {{ __('panel.false') }}</span></label>
                  </div>
                  @error('true_false_answer')<div class="invalid-feedback" style="display:block">{{ $message }}</div>@enderror
                </div>
              </div>
            </div>

            <div class="qx-col-12" id="qx-wrap-essay" style="display:none">
              <div class="qx-card">
                <div class="qx-card-h"><h5 class="qx-card-t">{{ __('panel.essay') }}</h5></div>
                <div class="qx-card-b">
                  <div class="alert alert-info m-0 d-flex align-items-center gap-2"><i class="fas fa-info-circle"></i><span>{{ __('panel.essay_question_note') }}</span></div>
                </div>
              </div>
            </div>

            <div class="qx-col-12">
              <div class="qx-footer">
                <a href="{{ route('teacher.exams.exam_questions.index', $exam) }}" class="qx-btn">{{ __('panel.cancel') }}</a>
                <button type="submit" class="qx-btn qx-btn-primary"><i class="fas fa-save"></i>{{ __('panel.update_question') }}</button>
              </div>
            </div>
          </div>
        </div>

        <div class="qx-panel" id="qx-panel-preview">
          <div class="qx-grid">
            <div class="qx-col-12">
              <div class="qx-card">
                <div class="qx-card-h"><h5 class="qx-card-t">{{ __('panel.view') }}</h5></div>
                <div class="qx-card-b">
                  <div class="qx-preview">
                    <h6 id="qx-pv-title"></h6>
                    <div class="qx-note" id="qx-pv-course"></div>
                    <hr>
                    <div id="qx-pv-question"></div>
                    <div id="qx-pv-photo" style="margin-top:10px"></div>
                    <div id="qx-pv-options" style="margin-top:10px"></div>
                  </div>
                  <div class="qx-footer">
                    <button type="button" class="qx-btn" data-qx-go="answers">{{ __('panel.back') }}</button>
                    <button type="submit" class="qx-btn qx-btn-primary"><i class="fas fa-save"></i>{{ __('panel.update_question') }}</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </form>
</div>


<script>
// Photo preview for new upload
document.getElementById('qx_photo').addEventListener('change', function(e) {
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

// Remove new photo preview
document.getElementById('removeNewPhoto').addEventListener('click', function() {
  document.getElementById('qx_photo').value = '';
  document.getElementById('photoPreview').style.display = 'none';
  document.getElementById('previewImg').src = '';
});

// Delete current photo
@if($question->photo)
document.getElementById('deleteCurrentPhoto').addEventListener('click', function() {
  if (confirm('{{ __("panel.are_you_sure_delete_photo") }}')) {
    document.getElementById('deletePhotoInput').value = '1';
    document.getElementById('currentPhoto').style.display = 'none';
  }
});
@endif

// Update preview panel to show photo
function updatePreviewPhoto() {
  const photoPreviewDiv = document.getElementById('qx-pv-photo');
  const fileInput = document.getElementById('qx_photo');
  
  if (fileInput.files && fileInput.files[0]) {
    const reader = new FileReader();
    reader.onload = function(e) {
      photoPreviewDiv.innerHTML = '<img src="' + e.target.result + '" style="max-width:400px; border-radius:8px; margin:10px 0;">';
    };
    reader.readAsDataURL(fileInput.files[0]);
  } else if (document.getElementById('deletePhotoInput').value !== '1' && '{{ $question->photo }}') {
    photoPreviewDiv.innerHTML = '<img src="{{ asset("assets/admin/uploads/" . $question->photo) }}" style="max-width:400px; border-radius:8px; margin:10px 0;">';
  } else {
    photoPreviewDiv.innerHTML = '';
  }
}

// Call updatePreviewPhoto when switching to preview tab
document.querySelectorAll('[data-qx-tab="preview"]').forEach(function(tab) {
  tab.addEventListener('click', updatePreviewPhoto);
});
</script>


<script>
let qxOptionCount=0;
let qxExisting=@json($question->options ?? []);
function qxActivateTab(id){
  document.querySelectorAll('.qx-tab').forEach(t=>t.classList.toggle('qx-active',t.dataset.qxTab===id));
  document.querySelectorAll('.qx-panel').forEach(p=>p.classList.toggle('qx-show',p.id==='qx-panel-'+id));
  if(window.innerWidth<992){document.querySelector('.qx-stage').scrollIntoView({behavior:'smooth',block:'start'})}
  const active=document.querySelector('.qx-tab.qx-active');if(active){active.scrollIntoView({inline:'center',behavior:'smooth',block:'nearest'})}
}
function qxSegSync(group){
  group.querySelectorAll('.qx-seg').forEach(s=>s.classList.toggle('qx-active',s.querySelector('input').checked));
}
function qxSyncType(){
  const t=document.getElementById('qx_type').value;
  document.getElementById('qx-wrap-mc').style.display=t==='multiple_choice'?'block':'none';
  document.getElementById('qx-wrap-tf').style.display=t==='true_false'?'block':'none';
  document.getElementById('qx-wrap-essay').style.display=t==='essay'?'block':'none';
  document.querySelectorAll('#qxTypeSeg .qx-seg').forEach(s=>{s.classList.toggle('qx-active',s.dataset.qxVal===t);s.querySelector('input').checked=(s.dataset.qxVal===t)});
  if(t==='multiple_choice'&&qxOptionCount===0){qxLoadOptions()}
  if(t==='true_false'){qxSegSync(document.getElementById('qxTfSeg'))}
}
function qxLoadOptions(){
  const c=document.getElementById('qx-options');
  c.innerHTML='';qxOptionCount=0;
  if(qxExisting&&qxExisting.length){qxExisting.forEach(o=>qxAddOption(o))}else{for(let i=0;i<4;i++) qxAddOption()}
}
function qxLetter(i){return String.fromCharCode(65+i)}
function qxAddOption(prefill){
  qxOptionCount++;const idx=qxOptionCount-1;
  const en=prefill&&prefill.option_en?prefill.option_en:'';const ar=prefill&&prefill.option_ar?prefill.option_ar:'';
  const ok=prefill&&prefill.is_correct?'checked':'';const oid=prefill&&prefill.id?prefill.id:'';
  const box=document.createElement('div');box.className='qx-opt';box.dataset.index=idx;
  box.innerHTML=
    '<div class="qx-opt-h">'+
      '<h6 class="qx-opt-t">{{ __("panel.option") }} '+qxLetter(idx)+'</h6>'+
      '<div class="qx-segs">'+
        '<button type="button" class="qx-btn-ghost btn-sm" data-qx-act="dup"><i class="fas fa-clone"></i></button>'+
        '<button type="button" class="qx-btn-ghost btn-sm text-danger" data-qx-act="del"><i class="fas fa-trash"></i></button>'+
      '</div>'+
    '</div>'+
    '<div class="qx-opt-g">'+
      '<div class="qx-field"><label class="qx-label">{{ __("panel.option_text_en") }}</label><input type="text" class="qx-input" name="options['+idx+'][option_en]" value="'+en+'" placeholder="{{ __("panel.enter_option_en") }}" required></div>'+
      '<div class="qx-field"><label class="qx-label">{{ __("panel.option_text_ar") }}</label><input type="text" class="qx-input" name="options['+idx+'][option_ar]" value="'+ar+'" placeholder="{{ __("panel.enter_option_ar") }}" dir="rtl" required></div>'+
      '<div class="qx-field"><label class="qx-label">{{ __("panel.correct") }}</label><div class="form-check" style="padding-top:10px"><input class="form-check-input" type="checkbox" name="options['+idx+'][is_correct]" value="1" '+ok+' id="qx-ok-'+idx+'"><label class="form-check-label" for="qx-ok-'+idx+'">{{ __("panel.correct_answer") }}</label></div></div>'+
    '</div>'+
    '<input type="hidden" name="options['+idx+'][id]" value="'+oid+'">';
  document.getElementById('qx-options').appendChild(box);
}
function qxRenumber(){
  const items=[...document.querySelectorAll('#qx-options .qx-opt')];
  items.forEach((el,i)=>{
    el.dataset.index=i;
    el.querySelector('.qx-opt-t').textContent='{{ __("panel.option") }} '+qxLetter(i);
    el.querySelectorAll('input,textarea,select,label').forEach(inp=>{
      if(inp.name&&inp.name.startsWith('options[')){inp.name=inp.name.replace(/options\[\d+\]/,'options['+i+']')}
      if(inp.id&&/^qx-ok-\d+$/.test(inp.id)){inp.id='qx-ok-'+i}
      if(inp.tagName==='LABEL'&&inp.getAttribute('for')&&/^qx-ok-\d+$/.test(inp.getAttribute('for'))){inp.setAttribute('for','qx-ok-'+i)}
    });
  });
  qxOptionCount=items.length;
}
function qxBuildPreview(){
  const locale='{{ app()->getLocale() }}';
  const title=locale==='ar'?document.getElementById('qx_title_ar').value:document.getElementById('qx_title_en').value;
  const qText=locale==='ar'?document.getElementById('qx_question_ar').value:document.getElementById('qx_question_en').value;
  const course='{{ $exam->course ? (app()->getLocale() == "ar" ? $exam->course->title_ar : $exam->course->title_en) : "-" }}';
  const type=document.getElementById('qx_type').value;
  document.getElementById('qx-pv-title').textContent=title||'—';
  document.getElementById('qx-pv-course').textContent=course;
  document.getElementById('qx-pv-question').textContent=qText||'';
  const pv=document.getElementById('qx-pv-options');pv.innerHTML='';
  if(type==='multiple_choice'){
    const items=[...document.querySelectorAll('#qx-options .qx-opt')];
    items.forEach((el,i)=>{
      const en=el.querySelector('input[name^="options['+i+'][option_en]"]')?.value||'';
      const ar=el.querySelector('input[name^="options['+i+'][option_ar]"]')?.value||'';
      const ok=el.querySelector('input[name^="options['+i+'][is_correct]"]')?.checked;
      const line=document.createElement('div');line.className='qx-note';
      line.innerHTML=(ok?'<i class="fas fa-check" style="color:var(--qx-success)"></i> ':'<i class="far fa-circle"></i> ')+(locale==='ar'?ar:en);
      pv.appendChild(line);
    });
  }
  if(type==='true_false'){
    const t=document.querySelector('input[name="true_false_answer"]:checked');
    const txt=t&&t.value==='1'?'{{ __("panel.true") }}':'{{ __("panel.false") }}';
    const line=document.createElement('div');line.className='qx-note';
    line.innerHTML='<i class="fas fa-check" style="color:var(--qx-success)"></i> '+txt;
    pv.appendChild(line);
  }
}
document.addEventListener('DOMContentLoaded',function(){
  document.querySelectorAll('.qx-tab').forEach(b=>b.addEventListener('click',function(){qxActivateTab(this.dataset.qxTab);if(this.dataset.qxTab==='preview'){qxBuildPreview()}}));
  document.querySelectorAll('[data-qx-go]').forEach(b=>b.addEventListener('click',function(){qxActivateTab(this.getAttribute('data-qx-go'))}));
  document.getElementById('qx_type').addEventListener('change',qxSyncType);
  document.querySelectorAll('#qxTypeSeg .qx-seg').forEach(seg=>seg.addEventListener('click',function(){document.getElementById('qx_type').value=this.dataset.qxVal;qxSyncType()}));
  document.querySelectorAll('#qxTfSeg .qx-seg input').forEach(r=>r.addEventListener('change',function(){qxSegSync(document.getElementById('qxTfSeg'))}));
  document.getElementById('qxAddOption').addEventListener('click',function(){qxAddOption()});
  document.getElementById('qxAddTwo').addEventListener('click',function(){qxAddOption();qxAddOption()});
  document.getElementById('qx-options').addEventListener('click',function(e){
    const btn=e.target.closest('button');if(!btn)return;
    const act=btn.dataset.qxAct;const item=btn.closest('.qx-opt');
    if(act==='del'){item.remove();qxRenumber()}
    if(act==='dup'){
      const i=parseInt(item.dataset.index,10);
      const en=item.querySelector('input[name^="options['+i+'][option_en]"]').value;
      const ar=item.querySelector('input[name^="options['+i+'][option_ar]"]').value;
      const ok=item.querySelector('input[name^="options['+i+'][is_correct]"]').checked;
      qxAddOption({option_en:en,option_ar:ar,is_correct:ok});qxRenumber();
    }
  });
  qxSyncType();
});
document.getElementById('qxForm').addEventListener('submit',function(e){
  const t=document.getElementById('qx_type').value;
  if(t==='multiple_choice'){
    const items=[...document.querySelectorAll('.qx-opt')];
    if(items.length<2){e.preventDefault();alert('{{ __("panel.minimum_two_options_required") }}');return}
    const ok=[...document.querySelectorAll('input[name*="[is_correct]"]')].filter(x=>x.checked);
    if(ok.length===0){e.preventDefault();alert('{{ __("panel.at_least_one_correct_answer_required") }}');return}
    qxRenumber();
  }else if(t==='true_false'){
    const ch=document.querySelector('input[name="true_false_answer"]:checked');
    if(!ch){e.preventDefault();alert('{{ __("panel.please_select_correct_answer") }}');return}
  }
});
</script>
@endsection
