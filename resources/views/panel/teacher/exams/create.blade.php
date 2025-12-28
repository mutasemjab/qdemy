@extends('layouts.app')

@section('title', __('panel.create_exam'))

@section('content')
<section class="ud-wrap">
    <aside class="ud-menu">
        <div class="ud-user">
            <img data-src="{{ auth()->user()->photo ? asset('assets/admin/uploads/' . auth()->user()->photo) : asset('assets_front/images/avatar-big.png') }}" alt="">
            <div>
                <h3>{{ auth()->user()->name }}</h3>
                <span>{{ auth()->user()->email }}</span>
            </div>
        </div>
        <a href="{{ route('teacher.exams.index') }}" class="ud-item">
            <i class="fa-solid fa-arrow-left"></i>
            <span>{{ __('panel.back_to_exams') }}</span>
        </a>
    </aside>

    <div class="ud-content">
        <div class="ud-panel show">
            <div class="ud-title">{{ __('panel.create_exam') }}</div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="ud-errors">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('teacher.exams.store') }}" class="exam-form" id="examForm">
                @csrf

                <!-- Basic Information -->
                <div class="form-section">
                    <div class="section-title">{{ __('panel.basic_information') }}</div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="title_en">{{ __('panel.exam_title_en') }} *</label>
                            <input type="text" id="title_en" name="title_en" value="{{ old('title_en') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="title_ar">{{ __('panel.exam_title_ar') }} *</label>
                            <input type="text" id="title_ar" name="title_ar" value="{{ old('title_ar') }}" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="description_en">{{ __('panel.description_en') }}</label>
                            <textarea id="description_en" name="description_en" rows="3">{{ old('description_en') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="description_ar">{{ __('panel.description_ar') }}</label>
                            <textarea id="description_ar" name="description_ar" rows="3">{{ old('description_ar') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Course & Subject Information -->
                <div class="form-section">
                    <div class="section-title">{{ __('panel.course_subject_info') }}</div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="subject_id">{{ __('panel.subject') }} *</label>
                            <select id="subject_id" name="subject_id" required>
                                <option value="">{{ __('panel.select_subject') }}</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                        {{ app()->getLocale() === 'ar' ? $subject->name_ar : $subject->name_en }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="course_id">{{ __('panel.course') }}</label>
                            <select id="course_id" name="course_id">
                                <option value="">{{ __('panel.select_course') }}</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" data-subject="{{ $course->subject_id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                        {{ app()->getLocale() === 'ar' ? $course->title_ar : $course->title_en }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="section_id">{{ __('panel.section') }}</label>
                            <select id="section_id" name="section_id">
                                <option value="">{{ __('panel.select_section') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="course_content_id">{{ __('messages.lesson') }}</label>
                            <select id="course_content_id" name="course_content_id" disabled>
                                <option value="">{{ __('messages.select_lesson_optional') }}</option>
                            </select>
                            <small class="form-text">{{ __('messages.select_section_first') }}</small>
                        </div>
                    </div>
                </div>

                <!-- Exam Settings -->
                <div class="form-section">
                    <div class="section-title">{{ __('panel.exam_settings') }}</div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="duration_minutes">{{ __('panel.duration_minutes') }}</label>
                            <input type="number" id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes') }}" min="1">
                            <small class="form-text">{{ __('panel.leave_empty_unlimited') }}</small>
                        </div>

                        <div class="form-group">
                            <label for="attempts_allowed">{{ __('panel.attempts_allowed') }} *</label>
                            <input type="number" id="attempts_allowed" name="attempts_allowed" value="{{ old('attempts_allowed', 1) }}" min="1" max="10" required>
                        </div>

                        <div class="form-group">
                            <label for="passing_grade">{{ __('panel.passing_grade') }} *</label>
                            <div class="input-wrapper">
                                <input type="number" id="passing_grade" name="passing_grade" value="{{ old('passing_grade', 60) }}" min="0" max="100" step="0.01" required>
                                <span class="suffix">%</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="start_date">{{ __('panel.start_date') }}</label>
                            <input type="datetime-local" id="start_date" name="start_date" value="{{ old('start_date') }}">
                        </div>

                        <div class="form-group">
                            <label for="end_date">{{ __('panel.end_date') }}</label>
                            <input type="datetime-local" id="end_date" name="end_date" value="{{ old('end_date') }}">
                        </div>
                    </div>
                </div>

                <!-- Display Options -->
                <div class="form-section">
                    <div class="section-title">{{ __('panel.display_options') }}</div>

                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="shuffle_questions" value="1" {{ old('shuffle_questions') ? 'checked' : '' }}>
                            <span>{{ __('panel.shuffle_questions') }}</span>
                        </label>
                        <small class="form-text">{{ __('panel.shuffle_questions_desc') }}</small>
                    </div>

                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="shuffle_options" value="1" {{ old('shuffle_options') ? 'checked' : '' }}>
                            <span>{{ __('panel.shuffle_options') }}</span>
                        </label>
                        <small class="form-text">{{ __('panel.shuffle_options_desc') }}</small>
                    </div>

                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="show_results_immediately" value="1" {{ old('show_results_immediately') ? 'checked' : '' }}>
                            <span>{{ __('panel.show_results_immediately') }}</span>
                        </label>
                        <small class="form-text">{{ __('panel.show_results_immediately_desc') }}</small>
                    </div>

                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <span>{{ __('panel.active') }}</span>
                        </label>
                        <small class="form-text">{{ __('panel.exam_active_desc') }}</small>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('teacher.exams.index') }}" class="btn btn-secondary">{{ __('panel.cancel') }}</a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fa-solid fa-save"></i>
                        {{ __('panel.create_exam') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
.ud-wrap{display:grid;grid-template-columns:320px 1fr;gap:24px;padding:16px 0}
.ud-menu{margin:10px;background:#fff;border:1px solid #eef0f3;border-radius:14px;padding:16px;position:sticky;top:88px;height:max-content}
.ud-user{display:flex;align-items:center;gap:12px;margin-bottom:12px}
.ud-user img{width:56px;height:56px;border-radius:50%;object-fit:cover;border:2px solid #f1f5f9}
.ud-user h3{font-size:16px;margin:0 0 2px 0}
.ud-user span{font-size:12px;color:#6b7280}
.ud-item{display:flex;align-items:center;gap:10px;padding:12px 14px;border:1px solid #e5e7eb;border-radius:10px;text-decoration:none;color:#0f172a;transition:all .18s}
.ud-item:hover{border-color:#0055D2;box-shadow:0 6px 18px rgba(0,85,210,.12);transform:translateY(-2px)}
.ud-content{min-width:0}
.ud-panel{background:#fff;border:1px solid #eef0f3;border-radius:14px;padding:18px}
.ud-title{font-size:20px;font-weight:900;margin-bottom:16px;color:#0f172a}

.exam-form{max-width:900px}
.form-section{margin-bottom:24px}
.section-title{font-size:14px;font-weight:800;color:#0f172a;margin-bottom:16px;padding-bottom:12px;border-bottom:2px solid #0055D2}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.form-row.three-cols{grid-template-columns:1fr 1fr 1fr}
.form-group{display:flex;flex-direction:column;gap:8px;margin-bottom:0}
.form-group label{font-weight:800;color:#0f172a;font-size:14px}
.form-group .form-text{color:#6b7280;font-size:12px}
.form-group input,.form-group select,.form-group textarea{border:1px solid #e5e7eb;border-radius:10px;padding:12px 14px;font-size:14px;background:#fff;transition:border-color .16s,box-shadow .16s;font-family:inherit}
.form-group textarea{min-height:80px;resize:vertical}
.form-group input:disabled,.form-group select:disabled{background:#f8fafc;color:#9aa3af}
.form-group input:focus,.form-group select:focus,.form-group textarea:focus{outline:none;border-color:#0055D2;box-shadow:0 0 0 3px rgba(0,85,210,.12)}

.input-wrapper{position:relative}
.input-wrapper .suffix{position:absolute;right:12px;top:50%;transform:translateY(-50%);color:#6b7280;font-weight:800;pointer-events:none}
.input-wrapper input{padding-right:40px}

.checkbox-group{margin-bottom:12px;padding:12px;background:#f9fafb;border-radius:8px}
.checkbox-label{display:flex;align-items:center;gap:8px;cursor:pointer;font-weight:800;color:#0f172a;margin:0}
.checkbox-label input[type="checkbox"]{margin:0;cursor:pointer;width:18px;height:18px}
.checkbox-label span{margin:0;font-size:14px}

.ud-errors{margin:0;padding-left:20px}

.form-actions{display:flex;gap:12px;justify-content:flex-end;margin-top:20px;padding-top:16px;border-top:1px solid #eef0f3}
.btn{display:inline-flex;align-items:center;gap:8px;border-radius:12px;padding:12px 16px;font-weight:900;font-size:14px;text-decoration:none;cursor:pointer;transition:transform .16s,box-shadow .16s,border-color .16s;border:1px solid transparent}
.btn:hover{transform:translateY(-1px)}
.btn-primary{background:#0055D2;color:#fff;border:1px solid #0048b3}
.btn-primary:hover{box-shadow:0 10px 22px rgba(0,85,210,.22)}
.btn-primary:disabled{opacity:.6;cursor:not-allowed}
.btn-secondary{background:#111827;color:#fff;border:1px solid #0b1220}
.btn-secondary:hover{box-shadow:0 10px 22px rgba(17,24,39,.22)}

.alert{padding:12px 14px;border-radius:12px;margin-bottom:16px}
.alert-danger{background:#fef2f2;color:#991b1b;border:1px solid #fee2e2}

@media (max-width:992px){
  .ud-wrap{grid-template-columns:1fr}
  .ud-menu{position:static}
}
@media (max-width:768px){
  .form-row{grid-template-columns:1fr}
  .form-row.three-cols{grid-template-columns:1fr}
  .form-actions{flex-direction:column}
  .exam-form{max-width:100%}
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded',function(){
  const subjectSelect=document.getElementById('subject_id');
  const courseSelect=document.getElementById('course_id');
  const sectionSelect=document.getElementById('section_id');
  const contentSelect=document.getElementById('course_content_id');
  const submitBtn=document.getElementById('submitBtn');
  const examForm=document.getElementById('examForm');

  // Handle subject change to filter courses
  if(subjectSelect){
    subjectSelect.addEventListener('change',function(){
      const subjectId=this.value;
      if(courseSelect){
        // Hide/show course options based on subject
        const courseOptions=courseSelect.querySelectorAll('option[data-subject]');
        courseOptions.forEach(option=>{
          if(subjectId&&option.getAttribute('data-subject')===subjectId){
            option.style.display='block';
          }else if(subjectId){
            option.style.display='none';
          }else{
            option.style.display='block';
          }
        });
        courseSelect.value='';
        if(sectionSelect){
          sectionSelect.innerHTML='<option value="">{{ __("panel.select_section") }}</option>';
        }
      }
    });
  }

  // Handle course change to load sections
  if(courseSelect){
    courseSelect.addEventListener('change',function(){
      const courseId=this.value;
      if(sectionSelect&&courseId){
        resetSelect(sectionSelect,'{{ __("panel.select_section") }}');
        setLoadingState(sectionSelect,'{{ __("panel.loading") }}...');
        const sectionsUrl='{{ route("teacher.exams.courses.sections","") }}/'+courseId;
        fetch(sectionsUrl)
          .then(r=>r.json())
          .then(sections=>{
            resetSelect(sectionSelect,'{{ __("panel.select_section") }}');
            if(sections&&sections.length>0){
              sections.forEach(section=>{
                addOption(sectionSelect,section.id,section.title);
              });
            }
          })
          .catch(error=>{
            console.warn('Failed to load sections:',error);
            resetSelect(sectionSelect,'{{ __("panel.select_section") }}');
          });
      }
    });
  }

  // Handle section change to load contents (lessons)
  if(sectionSelect){
    sectionSelect.addEventListener('change',function(){
      const sectionId=this.value;
      if(contentSelect){
        resetSelect(contentSelect,'{{ __("messages.select_lesson_optional") }}');
        if(!sectionId){
          contentSelect.disabled=true;
          return;
        }
        contentSelect.disabled=false;
        setLoadingState(contentSelect,'{{ __("panel.loading") }}...');
        const contentsUrl='{{ route("teacher.exams.sections.contents","") }}/'+sectionId;
        fetch(contentsUrl)
          .then(r=>r.json())
          .then(contents=>{
            resetSelect(contentSelect,'{{ __("messages.select_lesson_optional") }}');
            if(contents&&contents.length>0){
              contents.forEach(content=>{
                const title=content.title_en||content.title_ar;
                addOption(contentSelect,content.id,title);
              });
            }
          })
          .catch(error=>{
            console.warn('Failed to load contents:',error);
            resetSelect(contentSelect,'{{ __("messages.select_lesson_optional") }}');
            contentSelect.disabled=true;
          });
      }
    });
  }

  // Form validation
  if(examForm){
    examForm.addEventListener('submit',function(e){
      let isValid=true;
      const requiredFields=['title_en','title_ar','subject_id','attempts_allowed','passing_grade'];

      requiredFields.forEach(fieldName=>{
        const input=document.querySelector(`[name="${fieldName}"]`);
        if(input&&!input.value.trim()){
          input.style.borderColor='#dc3545';
          isValid=false;
        }else if(input){
          input.style.borderColor='#e5e7eb';
        }
      });

      // Validate date range
      const startDateInput=document.querySelector('[name="start_date"]');
      const endDateInput=document.querySelector('[name="end_date"]');
      if(startDateInput&&endDateInput&&startDateInput.value&&endDateInput.value){
        const startDate=new Date(startDateInput.value);
        const endDate=new Date(endDateInput.value);
        if(endDate<=startDate){
          endDateInput.style.borderColor='#dc3545';
          isValid=false;
          alert('{{ __("panel.end_date_must_be_after_start_date") }}');
        }
      }

      // Validate passing grade
      const passingGradeInput=document.querySelector('[name="passing_grade"]');
      if(passingGradeInput){
        const passingGrade=parseFloat(passingGradeInput.value);
        if(passingGrade<0||passingGrade>100){
          passingGradeInput.style.borderColor='#dc3545';
          isValid=false;
          alert('{{ __("panel.passing_grade_must_be_between_0_100") }}');
        }
      }

      if(!isValid){
        e.preventDefault();
      }else{
        submitBtn.disabled=true;
        submitBtn.innerHTML='<i class="fa-solid fa-spinner fa-spin"></i> {{ __("panel.creating") }}...';
      }
    });
  }

  // Auto-adjust textarea height
  const textareas=document.querySelectorAll('textarea');
  textareas.forEach(textarea=>{
    function adjustHeight(){
      textarea.style.height='auto';
      textarea.style.height=(textarea.scrollHeight)+'px';
    }
    adjustHeight();
    textarea.addEventListener('input',adjustHeight);
  });

  function resetSelect(sel,txt){
    sel.innerHTML='';
    addOption(sel,'',txt);
  }
  function setLoadingState(sel,txt){
    sel.innerHTML='';
    addOption(sel,'',txt);
  }
  function addOption(sel,val,txt,disabled=false){
    const o=document.createElement('option');
    o.value=val;
    o.textContent=txt;
    if(disabled)o.disabled=true;
    sel.appendChild(o);
  }

  // Restore old values on page load
  @if(old('subject_id'))
    if(subjectSelect){
      subjectSelect.value='{{ old("subject_id") }}';
      subjectSelect.dispatchEvent(new Event('change'));
      setTimeout(function(){
        @if(old('course_id'))
          if(courseSelect){
            courseSelect.value='{{ old("course_id") }}';
            courseSelect.dispatchEvent(new Event('change'));
          }
        @endif
      },300);
    }
  @endif
});
</script>
@endsection
