@extends('layouts.app')
@section('title', __('panel.create_section'))

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
    <a href="{{ route('teacher.courses.sections.index', $course) }}" class="ud-item">
      <i class="fa-solid fa-arrow-left"></i>
      <span>{{ __('panel.back_to_course') }}</span>
    </a>
  </aside>

  <div class="ud-content">
    <div class="ud-panel show">
      <div class="section-header">
        <h1>{{ __('panel.create_section') }}</h1>
        <p class="course-name">{{ $course->title_ar }}</p>
      </div>

      @if($errors->any())
        <div class="alert alert-danger">
          <ul style="margin:0;padding-left:20px">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('teacher.courses.sections.store', $course) }}" class="section-form" id="sectionForm">
        @csrf
        <div class="form-section">
          <h3>{{ __('panel.section_information') }}</h3>
          <div class="form-row">
            <div class="form-group">
              <label for="title_ar">{{ __('panel.section_title_ar') }} *</label>
              <input type="text" id="title_ar" name="title_ar" value="{{ old('title_ar') }}" required>
            </div>
            <div class="form-group">
              <label for="title_en">{{ __('panel.section_title_en') }} *</label>
              <input type="text" id="title_en" name="title_en" value="{{ old('title_en') }}" required>
            </div>
          </div>
          <div class="form-group">
            <label for="parent_id">{{ __('panel.parent_section') }}</label>
            <select id="parent_id" name="parent_id">
              <option value="">{{ __('panel.no_parent_section') }}</option>
              @foreach($sections as $section)
                <option value="{{ $section->id }}" {{ old('parent_id') == $section->id ? 'selected' : '' }}>
                  {{ $section->title_ar }}
                </option>
              @endforeach
            </select>
            <small class="form-text">{{ __('panel.parent_section_help') }}</small>
          </div>
        </div>

        <div class="form-actions">
          <a href="{{ route('teacher.courses.sections.index', $course) }}" class="btn btn-secondary">
            {{ __('panel.cancel') }}
          </a>
          <button type="submit" class="btn btn-primary" id="submitBtn">
            <i class="fa-solid fa-save"></i>
            {{ __('panel.create_section') }}
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

.section-header{margin-bottom:24px;padding-bottom:14px;border-bottom:2px solid #f1f5f9;display:flex;flex-direction:column;gap:6px}
.section-header h1{font-size:22px;margin:0;color:#0f172a;font-weight:900}
.course-name{margin:0;color:#6b7280;font-size:14px}

.section-form{max-width:760px}
.form-section{background:#fff;border:1px solid #eef0f3;border-radius:14px;padding:20px;margin-bottom:18px}
.form-section h3{margin:0 0 14px 0;color:#0f172a;font-size:16px;font-weight:900;border-bottom:1px solid #eef0f3;padding-bottom:10px}

.form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.form-group{display:flex;flex-direction:column;gap:8px}
.form-group label{font-weight:800;color:#0f172a}
.form-group input,.form-group select{width:100%;padding:12px 14px;border:1px solid #e5e7eb;border-radius:12px;font-size:14px;transition:box-shadow .16s,border-color .16s;background:#fff}
.form-group input:focus,.form-group select:focus{outline:none;border-color:#0055D2;box-shadow:0 0 0 4px rgba(0,85,210,.12)}
.form-text{color:#6b7280;font-size:12px;margin-top:-2px}

.form-actions{display:flex;gap:12px;justify-content:flex-end;margin-top:18px;padding-top:14px;border-top:1px solid #eef0f3}
.btn{display:inline-flex;align-items:center;gap:8px;border-radius:12px;padding:10px 14px;font-weight:900;font-size:14px;text-decoration:none;cursor:pointer;transition:transform .16s,box-shadow .16s}
.btn:hover{transform:translateY(-1px)}
.btn-primary{background:#0055D2;color:#fff;border:1px solid #0048b3}
.btn-primary:hover{box-shadow:0 10px 22px rgba(0,85,210,.22)}
.btn-secondary{background:#111827;color:#fff;border:1px solid #0b1220}
.btn-secondary:hover{box-shadow:0 10px 22px rgba(17,24,39,.22)}
.btn[disabled]{opacity:.6;cursor:not-allowed;transform:none}

.alert{padding:12px 14px;border-radius:12px;margin-bottom:14px}
.alert-danger{background:#fef2f2;color:#991b1b;border:1px solid #fee2e2}

@media (max-width:992px){.ud-wrap{grid-template-columns:1fr}.ud-menu{margin: 10px;position:static}}
@media (max-width:768px){
  .form-row{grid-template-columns:1fr}
  .form-actions{flex-direction:column}
  .btn{width:100%;justify-content:center}
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded',function(){
  const form=document.getElementById('sectionForm');
  const btn=document.getElementById('submitBtn');
  if(form&&btn){
    form.addEventListener('submit',function(){
      btn.disabled=true;
      btn.innerHTML='<i class="fa-solid fa-spinner fa-spin"></i> {{ __("panel.creating") }}...';
      setTimeout(function(){
        btn.disabled=false;
        btn.innerHTML='<i class="fa-solid fa-save"></i> {{ __("panel.create_section") }}';
      },8000);
    });
  }
});
</script>
@endsection
