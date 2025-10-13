@extends('layouts.app')
@section('title', __('panel.edit_section'))

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
        <h1>{{ __('panel.edit_section') }}</h1>
        <p class="course-name">{{ $course->title_ar }}</p>
        <div class="section-breadcrumb">
          <span>{{ __('panel.current_section') }}: {{ $section->title_ar }}</span>
          @if($section->parent_id)
            <small>{{ __('panel.subsection_of') }} {{ $section->parent->title_ar }}</small>
          @endif
        </div>
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

      <form method="POST" action="{{ route('teacher.courses.sections.update', [$course, $section]) }}" class="section-form">
        @csrf
        @method('PUT')

        <div class="form-section">
          <h3>{{ __('panel.section_information') }}</h3>

          <div class="form-row">
            <div class="form-group">
              <label for="title_ar">{{ __('panel.section_title_ar') }} *</label>
              <input type="text" id="title_ar" name="title_ar" value="{{ old('title_ar', $section->title_ar) }}" required>
            </div>
            <div class="form-group">
              <label for="title_en">{{ __('panel.section_title_en') }} *</label>
              <input type="text" id="title_en" name="title_en" value="{{ old('title_en', $section->title_en) }}" required>
            </div>
          </div>

          <div class="form-group">
            <label for="parent_id">{{ __('panel.parent_section') }}</label>
            <select id="parent_id" name="parent_id">
              <option value="">{{ __('panel.no_parent_section') }}</option>
              @foreach($sections as $parentSection)
                <option value="{{ $parentSection->id }}" {{ old('parent_id', $section->parent_id) == $parentSection->id ? 'selected' : '' }}>
                  {{ $parentSection->title_ar }}
                </option>
              @endforeach
            </select>
            <small class="form-text">{{ __('panel.parent_section_help') }}</small>
          </div>

          <div class="section-stats">
            <div class="stats-row">
              <div class="stat-item">
                <div class="stat-icon"><i class="fa-solid fa-play"></i></div>
                <div class="stat-info">
                  <h4>{{ $section->contents->count() }}</h4>
                  <p>{{ __('panel.direct_contents') }}</p>
                </div>
              </div>
              <div class="stat-item">
                <div class="stat-icon"><i class="fa-solid fa-folder"></i></div>
                <div class="stat-info">
                  <h4>{{ $section->children->count() }}</h4>
                  <p>{{ __('panel.subsections') }}</p>
                </div>
              </div>
              <div class="stat-item">
                <div class="stat-icon"><i class="fa-solid fa-list"></i></div>
                <div class="stat-info">
                  @php
                    $totalContents = $section->contents->count() + $section->children->sum(function($child){ return $child->contents->count(); });
                  @endphp
                  <h4>{{ $totalContents }}</h4>
                  <p>{{ __('panel.total_contents') }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        @if($section->contents->count() > 0 || $section->children->count() > 0)
          <div class="form-section">
            <h3>{{ __('panel.section_contents_preview') }}</h3>

            @if($section->contents->count() > 0)
              <div class="content-preview">
                <h4>{{ __('panel.direct_contents') }}</h4>
                <div class="content-list">
                  @foreach($section->contents as $content)
                    <div class="content-preview-item">
                      <div class="content-icon {{ $content->content_type }}">
                        <i class="fa-solid fa-{{ $content->content_type === 'video' ? 'play' : 'file-pdf' }}"></i>
                      </div>
                      <div class="content-info">
                        <h5>{{ $content->title_ar }}</h5>
                        <div class="content-meta">
                          <span class="content-type">{{ ucfirst($content->content_type) }}</span>
                          <span class="content-access">{{ $content->is_free == 1 ? __('panel.free') : __('panel.paid') }}</span>
                        </div>
                      </div>
                      <div class="content-actions">
                        <a href="{{ route('teacher.courses.contents.edit', [$course, $content]) }}" class="btn-small">
                          <i class="fa-solid fa-edit"></i>
                        </a>
                      </div>
                    </div>
                  @endforeach
                </div>
              </div>
            @endif

            @if($section->children->count() > 0)
              <div class="subsections-preview">
                <h4>{{ __('panel.subsections') }}</h4>
                <div class="subsections-list">
                  @foreach($section->children as $childSection)
                    <div class="subsection-preview-item">
                      <div class="subsection-header">
                        <h5><i class="fa-solid fa-folder-open"></i>{{ $childSection->title_ar }}</h5>
                        <span class="content-count">{{ $childSection->contents->count() }} {{ __('panel.items') }}</span>
                      </div>
                      @if($childSection->contents->count() > 0)
                        <div class="child-contents">
                          @foreach($childSection->contents as $content)
                            <div class="child-content-item">
                              <i class="fa-solid fa-{{ $content->content_type === 'video' ? 'play' : 'file-pdf' }}"></i>
                              <span>{{ $content->title_ar }}</span>
                            </div>
                          @endforeach
                        </div>
                      @endif
                    </div>
                  @endforeach
                </div>
              </div>
            @endif
          </div>
        @endif

        <div class="form-actions">
          <a href="{{ route('teacher.courses.sections.index', $course) }}" class="btn btn-secondary">{{ __('panel.cancel') }}</a>
          <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-save"></i>
            {{ __('panel.update_section') }}
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

.section-header{margin-bottom:24px;padding-bottom:16px;border-bottom:2px solid #f1f5f9}
.section-header h1{font-size:22px;font-weight:900;color:#0f172a;margin:0 0 6px 0}
.course-name{color:#6b7280;margin:0 0 8px 0;font-size:15px}
.section-breadcrumb{display:flex;flex-wrap:wrap;gap:6px;font-size:13px;color:#0055D2}
.section-breadcrumb small{color:#6b7280}

.section-form{max-width:880px}

.form-section{background:#fff;border:1px solid #eef0f3;border-radius:14px;padding:20px;margin-bottom:20px}
.form-section h3{margin:0 0 14px 0;color:#0f172a;font-size:16px;border-bottom:1px solid #eef0f3;padding-bottom:10px}

.form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.form-group{margin-bottom:14px}
.form-group label{display:block;margin-bottom:6px;font-weight:800;color:#0f172a}
.form-group input,.form-group select{width:100%;padding:10px 12px;border:1px solid #e5e7eb;border-radius:10px;font-size:14px;transition:border-color .18s,box-shadow .18s}
.form-group input:focus,.form-group select:focus{outline:0;border-color:#0055D2;box-shadow:0 0 0 3px rgba(0,85,210,.12)}
.form-text{color:#6b7280;font-size:12px;margin-top:6px;display:block}

.section-stats{margin-top:8px;padding-top:16px;border-top:1px solid #eef0f3}
.stats-row{display:grid;grid-template-columns:repeat(3,1fr);gap:12px}
.stat-item{display:flex;align-items:center;gap:10px;padding:14px;background:#f8fafc;border-radius:12px;border:1px solid #eef0f3}
.stat-icon{width:42px;height:42px;border-radius:10px;background:#0055D2;color:#fff;display:flex;align-items:center;justify-content:center;font-size:16px}
.stat-info h4{margin:0;font-size:20px;font-weight:900;color:#0f172a}
.stat-info p{margin:0;color:#6b7280;font-size:12px}

.content-preview,.subsections-preview{margin-bottom:8px}
.content-preview h4,.subsections-preview h4{color:#0f172a;font-size:15px;margin:0 0 10px 0;padding-bottom:8px;border-bottom:1px solid #eef0f3}
.content-list,.subsections-list{display:flex;flex-direction:column;gap:10px}

.content-preview-item{display:flex;align-items:center;gap:12px;padding:12px;background:#f8fafc;border-radius:12px;border:1px solid #eef0f3}
.content-icon{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:14px}
.content-icon.video{background:#ef4444}
.content-icon.pdf{background:#10b981}
.content-info{flex:1}
.content-info h5{margin:0 0 4px 0;color:#0f172a;font-size:14px;font-weight:800}
.content-meta{display:flex;gap:10px;font-size:12px;color:#6b7280}
.content-actions{display:flex;gap:6px}
.btn-small{width:30px;height:30px;border-radius:8px;background:#0055D2;color:#fff;border:0;display:flex;align-items:center;justify-content:center;text-decoration:none;font-size:12px;transition:box-shadow .16s,transform .16s}
.btn-small:hover{box-shadow:0 8px 20px rgba(0,85,210,.18);transform:translateY(-1px)}

.subsection-preview-item{border:1px solid #eef0f3;border-radius:12px;background:#fff;overflow:hidden}
.subsection-header{background:#f8fafc;padding:12px 14px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid #eef0f3}
.subsection-header h5{margin:0;color:#0f172a;font-size:14px;display:flex;align-items:center;gap:8px}
.content-count{background:#0055D2;color:#fff;padding:2px 8px;border-radius:999px;font-size:12px;font-weight:800}
.child-contents{padding:10px 14px}
.child-content-item{display:flex;align-items:center;gap:8px;padding:6px 0;color:#334155;font-size:13px}
.child-content-item i{color:#0055D2}

.form-actions{display:flex;gap:12px;justify-content:flex-end;margin-top:22px;padding-top:16px;border-top:1px solid #eef0f3}
.btn{padding:10px 16px;border:0;border-radius:12px;font-size:14px;font-weight:900;text-decoration:none;cursor:pointer;display:inline-flex;align-items:center;gap:8px;transition:transform .16s,box-shadow .16s}
.btn:hover{transform:translateY(-1px)}
.btn-primary{background:#0055D2;color:#fff;border:1px solid #0048b3}
.btn-primary:hover{box-shadow:0 12px 24px rgba(0,85,210,.22)}
.btn-secondary{background:#111827;color:#fff;border:1px solid #0b1220}
.btn-secondary:hover{box-shadow:0 12px 24px rgba(17,24,39,.22)}

.alert{padding:12px 14px;border-radius:12px;margin-bottom:14px}
.alert-danger{background:#fef2f2;color:#991b1b;border:1px solid #fee2e2}

@media (max-width:992px){
  .ud-wrap{grid-template-columns:1fr}
  .ud-menu{margin: 10px;position:static}
}

@media (max-width:768px){
  .form-row{grid-template-columns:1fr}
  .stats-row{grid-template-columns:1fr}
  .content-preview-item{flex-direction:column;align-items:flex-start;gap:8px}
  .subsection-header{flex-direction:column;gap:8px;align-items:flex-start}
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded',function(){
  const form=document.querySelector('.section-form');
  if(!form) return;
  form.addEventListener('submit',function(){
    const btn=this.querySelector('button[type="submit"]');
    if(!btn) return;
    btn.disabled=true;
    btn.innerHTML='<i class="fa-solid fa-spinner fa-spin"></i> {{ __("panel.updating") }}...';
    setTimeout(function(){
      btn.disabled=false;
      btn.innerHTML='<i class="fa-solid fa-save"></i> {{ __("panel.update_section") }}';
    },5000);
  });
});
</script>
@endsection
