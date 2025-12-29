@extends('layouts.app')
@section('title', __('panel.edit_content'))

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
    <a href="{{ route('teacher.courses.sections.index', $course) }}" class="ud-item">
      <i class="fas fa-arrow-left"></i>
      <span>{{ __('panel.back_to_course') }}</span>
    </a>
  </aside>

  <div class="ud-content">
    <div class="ud-panel show">
      <div class="content-header">
        <h1>{{ __('panel.edit_content') }}</h1>
        <p class="course-name">{{ $course->title_ar }}</p>
        <div class="content-breadcrumb">
          <span>{{ __('panel.current_content') }}: {{ $content->title_ar }}</span>
          @if($content->section_id)
            <small>{{ __('panel.in_section') }} {{ $content->section->title_ar }}</small>
          @else
            <small>{{ __('panel.direct_content') }}</small>
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

      <form method="POST" action="{{ route('teacher.courses.contents.update', [$course, $content]) }}" enctype="multipart/form-data" class="content-form" id="contentForm">
        @csrf
        @method('PUT')

        <div class="form-section">
          <h3>{{ __('panel.basic_information') }}</h3>

          <div class="form-row">
            <div class="form-group">
              <label for="title_ar">{{ __('panel.title_ar') }} *</label>
              <input type="text" id="title_ar" name="title_ar" value="{{ old('title_ar', $content->title_ar) }}" required>
            </div>
            <div class="form-group">
              <label for="title_en">{{ __('panel.title_en') }} *</label>
              <input type="text" id="title_en" name="title_en" value="{{ old('title_en', $content->title_en) }}" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="content_type">{{ __('panel.content_type') }} *</label>
              <select id="content_type" name="content_type" required>
                <option value="">{{ __('panel.select_content_type') }}</option>
                <option value="video" {{ old('content_type', $content->content_type) == 'video' ? 'selected' : '' }}>{{ __('panel.video') }}</option>
                <option value="pdf" {{ old('content_type', $content->content_type) == 'pdf' ? 'selected' : '' }}>{{ __('panel.pdf_document') }}</option>
              </select>
            </div>
            <div class="form-group">
              <label for="section_id">{{ __('panel.section') }}</label>
              <select id="section_id" name="section_id">
                <option value="">{{ __('panel.no_section') }}</option>
                @foreach($sections as $section)
                  <option value="{{ $section->id }}" {{ old('section_id', $content->section_id) == $section->id ? 'selected' : '' }}>{{ $section->title_ar }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="is_free">{{ __('panel.access_type') }} *</label>
              <select id="is_free" name="is_free" required>
                <option value="2" {{ old('is_free', $content->is_free) == '2' ? 'selected' : '' }}>{{ __('panel.paid_content') }}</option>
                <option value="1" {{ old('is_free', $content->is_free) == '1' ? 'selected' : '' }}>{{ __('panel.free_content') }}</option>
              </select>
            </div>
            <div class="form-group">
              <label for="is_main_video">{{ __('messages.is it main video?') }} *</label>
              <select id="is_main_video" name="is_main_video" required>
                <option value="2" {{ old('is_main_video', $content->is_main_video) == '2' ? 'selected' : '' }}>{{ __('panel.no') }}</option>
                <option value="1" {{ old('is_main_video', $content->is_main_video) == '1' ? 'selected' : '' }}>{{ __('panel.yes') }}</option>
              </select>
            </div>
            <div class="form-group">
              <label for="order">{{ __('panel.order') }}</label>
              <input type="number" id="order" name="order" value="{{ old('order', $content->order) }}" min="1">
            </div>
          </div>
        </div>

        <div class="form-section">
          <h3>{{ __('panel.current_content') }}</h3>
          <div class="current-content-display">
            <div class="content-type-badge">
              <span class="badge {{ $content->content_type === 'video' ? 'badge-primary' : 'badge-secondary' }}">{{ ucfirst($content->content_type) }}</span>
            </div>

            @if($content->content_type === 'video')
              <div class="current-video">
                <div class="video-info">
                  <h4>{{ __('panel.current_video') }}</h4>
                  <p><strong>{{ __('panel.type') }}:</strong> {{ ucfirst($content->video_type) }}</p>
                  @if($content->video_duration)
                    <p><strong>{{ __('panel.duration') }}:</strong> {{ gmdate('H:i:s', $content->video_duration) }}</p>
                  @endif
                </div>
                @if($content->video_url)
                  <div class="video-preview">
                    @if($content->video_type === 'youtube')
                      <a href="{{ $content->video_url }}" target="_blank" class="btn-outline-primary"><i class="fas fa-arrow-up-right-from-square"></i> {{ __('panel.view_on_youtube') }}</a>
                    @else
                      <video controls width="300" height="200">
                        <source src="{{ asset($content->video_url) }}" type="video/mp4">
                        {{ __('panel.video_not_supported') }}
                      </video>
                    @endif
                  </div>
                @endif
              </div>
            @elseif($content->file_path)
              <div class="current-file">
                <div class="file-info">
                  <h4>{{ __('panel.current_file') }}</h4>
                  <p><strong>{{ __('panel.type') }}:</strong> {{ ucfirst($content->pdf_type ?? 'PDF') }}</p>
                </div>
                <div class="file-actions">
                  <a href="{{ asset($content->file_path) }}" target="_blank" class="btn-outline-primary"><i class="fas fa-download"></i> {{ __('panel.download_current_file') }}</a>
                </div>
              </div>
            @endif
          </div>
        </div>

        <div class="form-section" id="video-fields" style="display:none">
          <h3>{{ __('panel.video_settings') }}</h3>
          <div class="form-group">
            <label for="video_type">{{ __('panel.video_type') }} *</label>
            <select id="video_type" name="video_type">
              <option value="">{{ __('panel.select_video_type') }}</option>
              <option value="youtube" {{ old('video_type', $content->video_type) == 'youtube' ? 'selected' : '' }}>{{ __('panel.youtube_video') }}</option>
              <option value="bunny" {{ old('video_type', $content->video_type) == 'bunny' ? 'selected' : '' }}>{{ __('panel.upload_video') }}</option>
            </select>
          </div>
          <div class="form-group" id="video_url_field" style="display:none">
            <label for="video_url">{{ __('panel.youtube_url') }} *</label>
            <input type="url" id="video_url" name="video_url" value="{{ old('video_url', $content->video_url) }}" placeholder="https://www.youtube.com/watch?v=...">
            <small class="form-text">{{ __('panel.youtube_url_help') }}</small>
          </div>
          <div class="form-group" id="upload_video_field" style="display:none">
            <label for="upload_video">{{ __('panel.upload_video_file') }}</label>
            <div class="file-upload-wrapper">
              <input type="file" id="upload_video" name="upload_video" accept="video/mp4,video/avi,video/mov,video/wmv">
              <div class="upload-preview" id="video-preview" style="display:none">
                <video controls style="max-width:100%;height:200px"></video>
              </div>
            </div>
            <small class="form-text">{{ __('panel.leave_empty_keep_current') }}</small>
          </div>
          <div class="form-group">
            <label for="video_duration">{{ __('panel.video_duration') }} ({{ __('panel.seconds') }})</label>
            <input type="number" id="video_duration" name="video_duration" value="{{ old('video_duration', $content->video_duration) }}" min="1">
            <small class="form-text">{{ __('panel.video_duration_help') }}</small>
          </div>
        </div>

        <div class="form-section" id="pdf-fields" style="display:none">
          <h3>{{ __('panel.document_settings') }}</h3>
          <div class="form-group">
            <label for="pdf_type">{{ __('panel.document_type') }} *</label>
            <select id="pdf_type" name="pdf_type">
              <option value="">{{ __('panel.select_document_type') }}</option>
              <option value="notes" {{ old('pdf_type', $content->pdf_type) == 'notes' ? 'selected' : '' }}>{{ __('panel.notes') }}</option>
              <option value="homework" {{ old('pdf_type', $content->pdf_type) == 'homework' ? 'selected' : '' }}>{{ __('panel.homework') }}</option>
              <option value="worksheet" {{ old('pdf_type', $content->pdf_type) == 'worksheet' ? 'selected' : '' }}>{{ __('panel.worksheet') }}</option>
              <option value="other" {{ old('pdf_type', $content->pdf_type) == 'other' ? 'selected' : '' }}>{{ __('panel.other') }}</option>
            </select>
          </div>
          <div class="form-group">
            <label for="file_path">{{ __('panel.upload_pdf') }}</label>
            <div class="file-upload-wrapper">
              <input type="file" id="file_path" name="file_path" accept=".pdf">
              <div class="upload-preview" id="pdf-preview" style="display:none">
                <div class="pdf-info">
                  <i class="fas fa-file-pdf"></i>
                  <span class="file-name"></span>
                  <span class="file-size"></span>
                </div>
              </div>
            </div>
            <small class="form-text">{{ __('panel.leave_empty_keep_current') }}</small>
          </div>
        </div>


        <div class="form-actions">
          <a href="{{ route('teacher.courses.sections.index', $course) }}" class="btn btn-secondary">{{ __('panel.cancel') }}</a>
          <button type="submit" class="btn btn-primary" id="submitBtn"><i class="fas fa-save"></i>{{ __('panel.update_content') }}</button>
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

.content-header{margin-bottom:24px;padding-bottom:16px;border-bottom:2px solid #f1f5f9}
.content-header h1{font-size:22px;font-weight:900;color:#0f172a;margin:0 0 6px 0}
.course-name{color:#6b7280;margin:0 0 8px 0;font-size:15px}
.content-breadcrumb{display:flex;flex-wrap:wrap;gap:6px;font-size:13px;color:#0055D2}
.content-breadcrumb small{color:#6b7280}

.content-form{max-width:880px}
.form-section{background:#fff;border:1px solid #eef0f3;border-radius:14px;padding:20px;margin-bottom:20px}
.form-section h3{margin:0 0 14px 0;color:#0f172a;font-size:16px;border-bottom:1px solid #eef0f3;padding-bottom:10px}

.form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.form-group{margin-bottom:14px}
.form-group label{display:block;margin-bottom:6px;font-weight:800;color:#0f172a}
.form-group input,.form-group select,.form-group textarea{width:100%;padding:10px 12px;border:1px solid #e5e7eb;border-radius:10px;font-size:14px;transition:border-color .18s,box-shadow .18s}
.form-group input:focus,.form-group select:focus,.form-group textarea:focus{outline:0;border-color:#0055D2;box-shadow:0 0 0 3px rgba(0,85,210,.12)}

.current-content-display{background:#f8fafc;padding:16px;border-radius:12px;border:1px solid #eef0f3}
.content-type-badge{margin-bottom:12px}
.badge{padding:6px 12px;border-radius:999px;font-size:12px;font-weight:800}
.badge-primary{background:#0055D2;color:#fff}
.badge-secondary{background:#6b7280;color:#fff}

.current-video,.current-file{display:flex;gap:16px;align-items:flex-start}
.video-info h4,.file-info h4{color:#0f172a;font-size:15px;margin:0 0 8px 0}
.video-info p,.file-info p{color:#6b7280;font-size:13px;margin:4px 0}
.btn-outline-primary{border:1px solid #0055D2;color:#0055D2;background:transparent;padding:8px 14px;border-radius:10px;text-decoration:none;font-size:13px;transition:box-shadow .16s,transform .16s}
.btn-outline-primary:hover{background:#0055D2;color:#fff;box-shadow:0 10px 22px rgba(0,85,210,.18);transform:translateY(-1px)}

.file-upload-wrapper{position:relative}
.upload-preview{margin-top:12px;padding:12px;background:#f8fafc;border-radius:10px}
.pdf-info{display:flex;align-items:center;gap:10px}
.pdf-info i{font-size:22px;color:#ef4444}
.file-name{font-weight:800;color:#0f172a}
.file-size{color:#6b7280;font-size:12px}
.form-text{color:#6b7280;font-size:12px;margin-top:6px;display:block}

.form-actions{display:flex;gap:12px;justify-content:flex-end;margin-top:22px;padding-top:16px;border-top:1px solid #eef0f3}
.btn{padding:10px 16px;border:0;border-radius:12px;font-size:14px;font-weight:900;text-decoration:none;cursor:pointer;display:inline-flex;align-items:center;gap:8px;transition:transform .16s,box-shadow .16s}
.btn:hover{transform:translateY(-1px)}
.btn-primary{background:#0055D2;color:#fff;border:1px solid #0048b3}
.btn-primary:hover{box-shadow:0 12px 24px rgba(0,85,210,.22)}
.btn-secondary{background:#111827;color:#fff;border:1px solid #0b1220}
.btn-secondary:hover{box-shadow:0 12px 24px rgba(17,24,39,.22)}

.alert{padding:12px 14px;border-radius:12px;margin-bottom:14px}
.alert-danger{background:#fef2f2;color:#991b1b;border:1px solid #fee2e2}
.alert-info{background:#e0f2fe;color:#075985;border:1px solid #bae6fd}

@media (max-width:992px){
  .ud-wrap{grid-template-columns:1fr}
  .ud-menu{margin: 10px;position:static}
}
@media (max-width:768px){
  .form-row{grid-template-columns:1fr}
  .form-actions{flex-direction:column}
  .current-video,.current-file{flex-direction:column}
  .video-preview video{width:100%;height:auto}
}
</style>
@endsection

@section('scripts')
<script>
function toggleContentFields(){
  var ct=document.getElementById('content_type');
  var vt=document.getElementById('video_type');
  var videoFields=document.getElementById('video-fields');
  var videoUrl=document.getElementById('video_url_field');
  var videoUpload=document.getElementById('upload_video_field');
  var pdfFields=document.getElementById('pdf-fields');
  var contentType=ct?ct.value:'';
  var videoType=vt?vt.value:'';
  if(videoFields) videoFields.style.display='none';
  if(videoUrl) videoUrl.style.display='none';
  if(videoUpload) videoUpload.style.display='none';
  if(pdfFields) pdfFields.style.display='none';
  if(contentType==='video'){
    if(videoFields) videoFields.style.display='block';
    if(videoType==='youtube'&&videoUrl) videoUrl.style.display='block';
    if(videoType==='bunny'&&videoUpload) videoUpload.style.display='block';
  }else if(contentType==='pdf'){
    if(pdfFields) pdfFields.style.display='block';
  }
}
document.addEventListener('DOMContentLoaded',function(){
  var ct=document.getElementById('content_type');
  var vt=document.getElementById('video_type');
  var uploadVideo=document.getElementById('upload_video');
  var pdfInput=document.getElementById('file_path');
  var submitBtn=document.getElementById('submitBtn');
  var form=document.getElementById('contentForm');
  toggleContentFields();
  if(ct){ ct.addEventListener('change',toggleContentFields); }
  if(vt){ vt.addEventListener('change',toggleContentFields); }
  if(uploadVideo){
    uploadVideo.addEventListener('change',function(e){
      var f=e.target.files&&e.target.files[0];
      if(!f) return;
      var prev=document.getElementById('video-preview');
      var v=prev?prev.querySelector('video'):null;
      if(v&&prev){ v.src=URL.createObjectURL(f); prev.style.display='block'; }
    });
  }
  if(pdfInput){
    pdfInput.addEventListener('change',function(e){
      var f=e.target.files&&e.target.files[0];
      if(!f) return;
      var prev=document.getElementById('pdf-preview');
      if(!prev) return;
      var nameEl=prev.querySelector('.file-name');
      var sizeEl=prev.querySelector('.file-size');
      if(nameEl) nameEl.textContent=f.name;
      if(sizeEl) sizeEl.textContent=(f.size/1024/1024).toFixed(2)+' MB';
      prev.style.display='block';
    });
  }
  if(form){
    form.addEventListener('submit',function(){
      if(!submitBtn) return;
      submitBtn.disabled=true;
      submitBtn.innerHTML='<i class="fas fa-spinner fa-spin"></i> {{ __("panel.updating") }}...';
      setTimeout(function(){
        submitBtn.disabled=false;
        submitBtn.innerHTML='<i class="fas fa-save"></i> {{ __("panel.update_content") }}';
      },30000);
    });
  }
});
</script>
@endsection
