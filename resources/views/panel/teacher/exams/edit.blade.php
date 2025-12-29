@extends('layouts.app')

@section('title', __('panel.edit_exam'))

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
        <a href="{{ route('teacher.exams.index') }}" class="ud-item">
            <i class="fas fa-arrow-left"></i>
            <span>{{ __('panel.back_to_exams') }}</span>
        </a>
    </aside>

    <div class="ud-content">
        <div class="ud-panel show">
            <div class="ud-title">{{ __('panel.edit_exam') }}</div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="ud-errors">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('teacher.exams.update', $exam->id) }}" class="exam-form" id="examForm">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="form-section">
                    <h5><i class="fas fa-info-circle me-2"></i>{{ __('panel.basic_information') }}</h5>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="title_en">{{ __('panel.exam_title_en') }} *</label>
                            <input type="text" id="title_en" name="title_en" value="{{ old('title_en', $exam->title_en) }}" placeholder="{{ __('panel.enter_exam_title_en') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="title_ar">{{ __('panel.exam_title_ar') }} *</label>
                            <input type="text" id="title_ar" name="title_ar" value="{{ old('title_ar', $exam->title_ar) }}" placeholder="{{ __('panel.enter_exam_title_ar') }}" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="description_en">{{ __('panel.description_en') }}</label>
                            <textarea id="description_en" name="description_en" rows="3" placeholder="{{ __('panel.enter_description_en') }}">{{ old('description_en', $exam->description_en) }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="description_ar">{{ __('panel.description_ar') }}</label>
                            <textarea id="description_ar" name="description_ar" rows="3" placeholder="{{ __('panel.enter_description_ar') }}">{{ old('description_ar', $exam->description_ar) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Course & Subject Information -->
                <div class="form-section">
                    <h5><i class="fas fa-graduation-cap me-2"></i>{{ __('panel.course_subject_info') }}</h5>

                    <div class="form-row form-row.three-cols">
                        <div class="form-group">
                            <label for="subject_id">{{ __('panel.subject') }} *</label>
                            <select id="subject_id" name="subject_id" required>
                                <option value="">{{ __('panel.select_subject') }}</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id', $exam->subject_id) == $subject->id ? 'selected' : '' }}>
                                        {{ app()->getLocale() === 'ar' ? $subject->name_ar : $subject->name_en }}
                                        @if($subject->grade)
                                            - {{ app()->getLocale() === 'ar' ? $subject->grade->name_ar : $subject->grade->name_en }}
                                        @endif
                                        @if($subject->semester)
                                            - {{ app()->getLocale() === 'ar' ? $subject->semester->name_ar : $subject->semester->name_en }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="course_id">{{ __('panel.course') }}</label>
                            <select id="course_id" name="course_id" {{ $exam->subject_id ? '' : 'disabled' }}>
                                <option value="">{{ __('panel.select_course') }}</option>
                            </select>
                            <small class="form-text">{{ __('messages.select_subject_first') }}</small>
                        </div>

                        <div class="form-group">
                            <label for="section_id">{{ __('panel.section') }}</label>
                            <select id="section_id" name="section_id" {{ $exam->course_id ? '' : 'disabled' }}>
                                <option value="">{{ __('panel.select_section') }}</option>
                            </select>
                            <small class="form-text">{{ __('messages.select_course_first') }}</small>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="course_content_id">{{ __('messages.lesson') }}</label>
                            <select id="course_content_id" name="course_content_id" {{ !$exam->section_id ? 'disabled' : '' }}>
                                <option value="">{{ __('messages.select_lesson_optional') }}</option>
                            </select>
                            <small class="form-text">{{ __('messages.select_section_first') }}</small>
                        </div>
                    </div>
                </div>

                <!-- Exam Settings -->
                <div class="form-section">
                    <h5><i class="fas fa-cog me-2"></i>{{ __('panel.exam_settings') }}</h5>

                    <div class="form-row form-row.three-cols">
                        <div class="form-group">
                            <label for="duration_minutes">{{ __('panel.duration_minutes') }}</label>
                            <input type="number" id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', $exam->duration_minutes) }}" min="1" placeholder="{{ __('panel.unlimited') }}">
                            <small class="form-text">{{ __('panel.leave_empty_unlimited') }}</small>
                        </div>

                        <div class="form-group">
                            <label for="attempts_allowed">{{ __('panel.attempts_allowed') }} *</label>
                            <input type="number" id="attempts_allowed" name="attempts_allowed" value="{{ old('attempts_allowed', $exam->attempts_allowed) }}" min="1" max="10" required>
                        </div>

                        <div class="form-group">
                            <label for="passing_grade">{{ __('panel.passing_grade') }} *</label>
                            <div class="input-wrapper">
                                <input type="number" id="passing_grade" name="passing_grade" value="{{ old('passing_grade', $exam->passing_grade) }}" min="0" max="100" step="0.01" required>
                                <span class="suffix">%</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="start_date">{{ __('panel.start_date') }}</label>
                            <input type="datetime-local" id="start_date" name="start_date" value="{{ old('start_date', $exam->start_date ? \Carbon\Carbon::parse($exam->start_date)->format('Y-m-d\TH:i') : '') }}">
                        </div>

                        <div class="form-group">
                            <label for="end_date">{{ __('panel.end_date') }}</label>
                            <input type="datetime-local" id="end_date" name="end_date" value="{{ old('end_date', $exam->end_date ? \Carbon\Carbon::parse($exam->end_date)->format('Y-m-d\TH:i') : '') }}">
                        </div>
                    </div>
                </div>

                <!-- Display Options -->
                <div class="form-section">
                    <h5><i class="fas fa-eye me-2"></i>{{ __('panel.display_options') }}</h5>

                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="shuffle_questions" value="1" {{ old('shuffle_questions', $exam->shuffle_questions) ? 'checked' : '' }}>
                            <span>{{ __('panel.shuffle_questions') }}</span>
                        </label>
                        <small class="form-text">{{ __('panel.shuffle_questions_desc') }}</small>
                    </div>

                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="shuffle_options" value="1" {{ old('shuffle_options', $exam->shuffle_options) ? 'checked' : '' }}>
                            <span>{{ __('panel.shuffle_options') }}</span>
                        </label>
                        <small class="form-text">{{ __('panel.shuffle_options_desc') }}</small>
                    </div>

                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="show_results_immediately" value="1" {{ old('show_results_immediately', $exam->show_results_immediately) ? 'checked' : '' }}>
                            <span>{{ __('panel.show_results_immediately') }}</span>
                        </label>
                        <small class="form-text">{{ __('panel.show_results_immediately_desc') }}</small>
                    </div>

                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $exam->is_active) ? 'checked' : '' }}>
                            <span>{{ __('panel.active') }}</span>
                        </label>
                        <small class="form-text">{{ __('panel.exam_active_desc') }}</small>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('teacher.exams.index') }}" class="btn btn-secondary">{{ __('panel.cancel') }}</a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save"></i>
                        {{ __('panel.update_exam') }}
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
  const examForm=document.getElementById('examForm');

  // Handle subject change to load courses
  if(subjectSelect){
    subjectSelect.addEventListener('change',function(){
      const subjectId=this.value;

      // Reset and disable course select
      resetSelect(courseSelect,'{{ __("panel.select_course") }}');
      resetSelect(sectionSelect,'{{ __("panel.select_section") }}');
      resetSelect(contentSelect,'{{ __("messages.select_lesson_optional") }}');

      if(!subjectId){
        courseSelect.disabled=true;
        sectionSelect.disabled=true;
        contentSelect.disabled=true;
        return;
      }

      // Load courses by subject
      courseSelect.disabled=false;
      setLoadingState(courseSelect,'{{ __("panel.loading") }}...');

      // Fetch teacher's courses for this subject
      const url='{{ route("teacher.exams.subjects.courses", ":subject") }}'.replace(":subject", subjectId);
      fetch(url)
        .then(r=>r.json())
        .then(courses=>{
          resetSelect(courseSelect,'{{ __("panel.select_course") }}');
          if(courses&&courses.length>0){
            courses.forEach(course=>{
              const title='{{ app()->getLocale() }}'==='ar'?course.title_ar:course.title_en;
              const selected=course.id=='{{ old("course_id",$exam->course_id??"") }}'?'selected':'';
              addOption(courseSelect,course.id,title,false,selected);
            });
          }
        })
        .catch(error=>{
          console.warn('Failed to load courses:',error);
          resetSelect(courseSelect,'{{ __("panel.select_course") }}');
          courseSelect.disabled=true;
        });
    });
  }

  // Handle course change to load sections
  if(courseSelect){
    courseSelect.addEventListener('change',function(){
      const courseId=this.value;

      // Reset and disable section select
      resetSelect(sectionSelect,'{{ __("panel.select_section") }}');
      resetSelect(contentSelect,'{{ __("messages.select_lesson_optional") }}');

      if(!courseId){
        sectionSelect.disabled=true;
        contentSelect.disabled=true;
        return;
      }

      // Load sections
      sectionSelect.disabled=false;
      setLoadingState(sectionSelect,'{{ __("panel.loading") }}...');

      const url='{{ route("teacher.exams.courses.sections", ":course") }}'.replace(":course", courseId);
      fetch(url)
        .then(r=>r.json())
        .then(sections=>{
          resetSelect(sectionSelect,'{{ __("panel.select_section") }}');
          if(sections&&sections.length>0){
            sections.forEach(section=>{
              const selected=section.id=='{{ old("section_id",$exam->section_id??"") }}'?'selected':'';
              addOption(sectionSelect,section.id,section.title,false,selected);
            });
          }
        })
        .catch(error=>{
          console.warn('Failed to load sections:',error);
          resetSelect(sectionSelect,'{{ __("panel.select_section") }}');
          sectionSelect.disabled=true;
        });
    });
  }

  // Handle section change to load contents (lessons)
  if(sectionSelect){
    sectionSelect.addEventListener('change',function(){
      const sectionId=this.value;

      resetSelect(contentSelect,'{{ __("messages.select_lesson_optional") }}');

      if(!sectionId){
        contentSelect.disabled=true;
        return;
      }

      contentSelect.disabled=false;
      setLoadingState(contentSelect,'{{ __("panel.loading") }}...');

      const url='{{ route("teacher.exams.sections.contents", ":section") }}'.replace(":section", sectionId);
      fetch(url)
        .then(r=>r.json())
        .then(contents=>{
          resetSelect(contentSelect,'{{ __("messages.select_lesson_optional") }}');
          if(contents&&contents.length>0){
            contents.forEach(content=>{
              const title=content.title_en||content.title_ar;
              const selected=content.id=='{{ old("course_content_id",$exam->course_content_id??"") }}'?'selected':'';
              addOption(contentSelect,content.id,title,false,selected);
            });
          }
        })
        .catch(error=>{
          console.warn('Failed to load contents:',error);
          resetSelect(contentSelect,'{{ __("messages.select_lesson_optional") }}');
          contentSelect.disabled=true;
        });
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
  function addOption(sel,val,txt,disabled=false,selected=false){
    const o=document.createElement('option');
    o.value=val;
    o.textContent=txt;
    if(disabled)o.disabled=true;
    if(selected)o.selected=true;
    sel.appendChild(o);
  }

  // Load data on page load
  @if($exam->subject_id)
    if(subjectSelect){
      subjectSelect.value='{{ $exam->subject_id }}';
      subjectSelect.dispatchEvent(new Event('change'));
      setTimeout(function(){
        @if($exam->course_id)
          if(courseSelect){
            courseSelect.value='{{ $exam->course_id }}';
            courseSelect.dispatchEvent(new Event('change'));
            setTimeout(function(){
              @if($exam->section_id)
                if(sectionSelect){
                  sectionSelect.value='{{ $exam->section_id }}';
                  sectionSelect.dispatchEvent(new Event('change'));
                }
              @endif
            },300);
          }
        @endif
      },300);
    }
  @endif
});
</script>
@endsection