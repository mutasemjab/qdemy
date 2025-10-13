@extends('layouts.app')
@section('title', __('panel.edit_course'))

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
        <a href="{{ route('teacher.courses.index') }}" class="ud-item">
            <i class="fa-solid fa-arrow-left"></i>
            <span>{{ __('panel.back_to_courses') }}</span>
        </a>
    </aside>

    <div class="ud-content">
        <div class="ud-panel show">
            <div class="ud-title">{{ __('panel.edit_course') }}</div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin:0;padding-left:20px">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('teacher.courses.update', $course) }}" enctype="multipart/form-data" class="course-form" id="courseForm">
                @csrf
                @method('PUT')

                @if($course->photo)
                    <div class="form-group">
                        <label>{{ __('panel.current_photo') }}</label>
                        <div class="current-photo">
                            <img src="{{ asset('assets/admin/uploads/' . $course->photo) }}" alt="{{ $course->title_ar }}" class="course-current-image">
                        </div>
                    </div>
                @endif

                <div class="form-group">
                    <label for="photo">{{ __('panel.course_photo') }} {{ $course->photo ? '' : '*' }}</label>
                    <div class="photo-upload-wrapper">
                        <input type="file" class="form-control file-input" id="photo" name="photo" accept="image/*" {{ $course->photo ? '' : 'required' }}>
                        <div class="upload-preview" id="photo-preview" style="display:none">
                            <img src="" alt="Preview" class="preview-image">
                        </div>
                    </div>
                    <small class="form-text">{{ __('panel.supported_formats') }}: JPG, PNG, GIF. {{ __('panel.max_size') }}: 2MB</small>
                    @if($course->photo)
                        <small class="form-text text-info">{{ __('panel.leave_empty_keep_current') }}</small>
                    @endif
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="title_ar">{{ __('panel.course_title_ar') }} *</label>
                        <input type="text" id="title_ar" name="title_ar" value="{{ old('title_ar', $course->title_ar) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="title_en">{{ __('panel.course_title_en') }} *</label>
                        <input type="text" id="title_en" name="title_en" value="{{ old('title_en', $course->title_en) }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description_ar">{{ __('panel.description_ar') }} *</label>
                    <textarea id="description_ar" name="description_ar" rows="4" required>{{ old('description_ar', $course->description_ar) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="description_en">{{ __('panel.description_en') }} *</label>
                    <textarea id="description_en" name="description_en" rows="4" required>{{ old('description_en', $course->description_en) }}</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="parent_category">{{ __('panel.select_program') }} *</label>
                        <select id="parent_category" name="parent_category" required>
                            <option value="">{{ __('panel.select_program') }}</option>
                            @foreach($parentCategories as $category)
                                <option value="{{ $category->id }}" {{ old('parent_category', $course->subject->programm_id ?? '') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name_ar }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="category_id">{{ __('panel.select_grade') }} *</label>
                        <select id="category_id" name="category_id" disabled required>
                            <option value="">{{ __('panel.select_grade_first') }}</option>
                        </select>
                        <small class="form-text">{{ __('panel.select_parent_first') }}</small>
                    </div>
                </div>

                <div class="form-group">
                    <label for="subject_id">{{ __('panel.select_subject') }} *</label>
                    <select id="subject_id" name="subject_id" disabled required>
                        <option value="">{{ __('panel.select_category_first') }}</option>
                    </select>
                    <small class="form-text">{{ __('panel.select_category_first') }}</small>
                </div>

                <div class="form-group">
                    <label for="selling_price">{{ __('panel.course_price') }} *</label>
                    <div class="price-input-wrapper">
                        <input type="number" id="selling_price" name="selling_price" value="{{ old('selling_price', $course->selling_price) }}" min="0" step="0.01" required>
                        <span class="currency">{{ __('panel.currency') }}</span>
                    </div>
                    <small class="form-text">{{ __('panel.enter_zero_for_free') }}</small>
                </div>

                <div class="form-actions">
                    <a href="{{ route('teacher.courses.index') }}" class="btn btn-secondary">{{ __('panel.cancel') }}</a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fa-solid fa-save"></i>
                        {{ __('panel.update_course') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
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
.ud-title{font-size:20px;font-weight:900;margin-bottom:16px;color:#0f172a}

.course-form{max-width:900px}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.form-group{display:flex;flex-direction:column;gap:8px;margin-bottom:16px}
.form-group label{font-weight:800;color:#0f172a}
.form-group .form-text{color:#6b7280;font-size:12px}
.file-input{padding:10px 12px;border-radius:10px;border:1px solid #e5e7eb;background:#fff}
.form-group input,.form-group select,.form-group textarea{border:1px solid #e5e7eb;border-radius:10px;padding:12px 14px;font-size:14px;background:#fff;transition:border-color .16s,box-shadow .16s}
.form-group textarea{min-height:120px;resize:vertical}
.form-group select:disabled,.form-group input:disabled{background:#f8fafc;color:#9aa3af}
.form-group input:focus,.form-group select:focus,.form-group textarea:focus{outline:none;border-color:#0055D2;box-shadow:0 0 0 3px rgba(0,85,210,.12)}

.current-photo{display:flex;gap:12px;align-items:center}
.course-current-image{max-width:220px;height:130px;object-fit:cover;border-radius:12px;border:2px solid #e5e7eb}
.photo-upload-wrapper{display:flex;flex-direction:column;gap:10px}
.upload-preview{display:inline-block}
.preview-image{max-width:220px;height:130px;object-fit:cover;border-radius:12px;border:2px solid #0055D2}

.price-input-wrapper{position:relative}
.price-input-wrapper .currency{position:absolute;right:12px;top:50%;transform:translateY(-50%);color:#6b7280;font-weight:800}
.price-input-wrapper input{padding-right:70px}

.form-actions{display:flex;gap:12px;justify-content:flex-end;margin-top:10px;padding-top:16px;border-top:1px solid #eef0f3}
.btn{display:inline-flex;align-items:center;gap:8px;border-radius:12px;padding:12px 16px;font-weight:900;font-size:14px;text-decoration:none;cursor:pointer;transition:transform .16s,box-shadow .16s,border-color .16s}
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
  .ud-menu{margin: 10px;position:static}
}
@media (max-width:768px){
  .form-row{grid-template-columns:1fr}
  .form-actions{flex-direction:column}
  .course-form{max-width:100%}
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded',function(){
  const parentCategorySelect=document.getElementById('parent_category');
  const childCategorySelect=document.getElementById('category_id');
  const subjectSelect=document.getElementById('subject_id');
  const photoInput=document.getElementById('photo');
  const photoPreview=document.getElementById('photo-preview');
  const submitBtn=document.getElementById('submitBtn');
  const courseForm=document.getElementById('courseForm');

  photoInput.addEventListener('change',function(e){
    const file=e.target.files[0];
    if(file){
      const reader=new FileReader();
      reader.onload=function(ev){
        const img=photoPreview.querySelector('.preview-image');
        img.src=ev.target.result;
        photoPreview.style.display='inline-block';
      };
      reader.readAsDataURL(file);
    }else{
      photoPreview.style.display='none';
    }
  });

  parentCategorySelect.addEventListener('change',function(){
    const parentId=this.value;
    resetSelect(childCategorySelect,'{{ __("panel.select_grade_first") }}');
    resetSelect(subjectSelect,'{{ __("panel.select_subject") }}');
    childCategorySelect.disabled=true;
    subjectSelect.disabled=true;
    if(parentId){
      setLoadingState(childCategorySelect,'{{ __("panel.loading") }}...');
      const childrenUrl="{{ route('teacher.categories.children', ':id') }}".replace(':id',parentId);
      fetch(childrenUrl)
        .then(r=>r.json())
        .then(data=>{
          resetSelect(childCategorySelect,'{{ __("panel.select_grade_first") }}');
          addOption(childCategorySelect,parentId,'{{ __("panel.use_parent_category") }}');
          if(data&&data.length>0){
            data.forEach(function(category){
              const name=category.name_ar||category.name_en||'';
              addOption(childCategorySelect,category.id,name);
            });
          }
          childCategorySelect.disabled=false;
          @if(old('category_id', $course->subject->grade_id ?? ''))
            setTimeout(function(){
              childCategorySelect.value='{{ old("category_id", $course->subject->grade_id ?? "") }}';
              childCategorySelect.dispatchEvent(new Event('change'));
            },100);
          @endif
        })
        .catch(()=>{
          resetSelect(childCategorySelect,'{{ __("panel.select_grade_first") }}');
          childCategorySelect.disabled=false;
        });
    }
  });

  childCategorySelect.addEventListener('change',function(){
    const categoryId=this.value;
    resetSelect(subjectSelect,'{{ __("panel.select_subject") }}');
    subjectSelect.disabled=true;
    if(categoryId){
      loadSubjects(categoryId);
    }
  });

  function loadSubjects(categoryId){
    setLoadingState(subjectSelect,'{{ __("panel.loading") }}...');
    subjectSelect.disabled=true;
    const subjectsUrl="{{ route('teacher.subjects.by-category') }}?category_id="+categoryId;
    fetch(subjectsUrl)
      .then(r=>r.json())
      .then(data=>{
        resetSelect(subjectSelect,'{{ __("panel.select_subject") }}');
        if(data&&data.length>0){
          data.forEach(function(subject){
            const nm=subject.name_ar||subject.name_en||subject.name||'';
            addOption(subjectSelect,subject.id,nm);
          });
          subjectSelect.disabled=false;
          @if(old('subject_id', $course->subject_id))
            setTimeout(function(){
              subjectSelect.value='{{ old("subject_id", $course->subject_id) }}';
            },100);
          @endif
        }else{
          addOption(subjectSelect,'','{{ __("panel.no_subjects_available") }}',true);
          subjectSelect.disabled=true;
        }
      })
      .catch(()=>{
        resetSelect(subjectSelect,'{{ __("panel.select_subject") }}');
        subjectSelect.disabled=false;
      });
  }

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
    if(disabled) o.disabled=true;
    sel.appendChild(o);
  }

  courseForm.addEventListener('submit',function(){
    submitBtn.disabled=true;
    submitBtn.innerHTML='<i class="fa-solid fa-spinner fa-spin"></i> {{ __("panel.updating") }}...';
    setTimeout(function(){
      submitBtn.disabled=false;
      submitBtn.innerHTML='<i class="fa-solid fa-save"></i> {{ __("panel.update_course") }}';
    },10000);
  });

  @if(old('parent_category', $course->subject->programm_id ?? ''))
    parentCategorySelect.value='{{ old("parent_category", $course->subject->programm_id ?? "") }}';
    parentCategorySelect.dispatchEvent(new Event('change'));
  @endif
});
</script>
@endsection
