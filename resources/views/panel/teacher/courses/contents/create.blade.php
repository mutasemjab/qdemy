@extends('layouts.app')
@section('title', __('panel.add_content'))

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
        <h1>{{ __('panel.add_content') }}</h1>
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

      <form method="POST" action="{{ route('teacher.courses.contents.store', $course) }}" enctype="multipart/form-data" class="content-form" id="contentForm">
        @csrf

        <div class="form-section">
          <h3>{{ __('panel.basic_information') }}</h3>

          <div class="form-row">
            <div class="form-group">
              <label for="title_ar">{{ __('panel.title_ar') }} *</label>
              <input type="text" id="title_ar" name="title_ar" value="{{ old('title_ar') }}" required>
            </div>
            <div class="form-group">
              <label for="title_en">{{ __('panel.title_en') }} *</label>
              <input type="text" id="title_en" name="title_en" value="{{ old('title_en') }}" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="content_type">{{ __('panel.content_type') }} *</label>
              <select id="content_type" name="content_type" required>
                <option value="">{{ __('panel.select_content_type') }}</option>
                <option value="video" {{ old('content_type') == 'video' ? 'selected' : '' }}>{{ __('panel.video') }}</option>
                <option value="pdf" {{ old('content_type') == 'pdf' ? 'selected' : '' }}>{{ __('panel.pdf_document') }}</option>
              </select>
            </div>
            <div class="form-group">
              <label for="section_id">{{ __('panel.section') }}</label>
              <select id="section_id" name="section_id">
                <option value="">{{ __('panel.no_section') }}</option>
                @foreach($sections as $section)
                  <option value="{{ $section->id }}" {{ (old('section_id') ?? $selectedSectionId) == $section->id ? 'selected' : '' }}>{{ $section->title_ar }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="is_free">{{ __('panel.access_type') }} *</label>
              <select id="is_free" name="is_free" required>
                <option value="2" {{ old('is_free') == '2' ? 'selected' : '' }}>{{ __('panel.paid_content') }}</option>
                <option value="1" {{ old('is_free') == '1' ? 'selected' : '' }}>{{ __('panel.free_content') }}</option>
              </select>
            </div>
            <div class="form-group">
              <label for="is_main_video">{{ __('messages.is it main video?') }} *</label>
              <select id="is_main_video" name="is_main_video" required>
                <option value="2" {{ old('is_main_video') == '2' ? 'selected' : '' }}>{{ __('panel.no') }}</option>
                <option value="1" {{ old('is_main_video') == '1' ? 'selected' : '' }}>{{ __('panel.yes') }}</option>
              </select>
            </div>
            <div class="form-group">
              <label for="order">{{ __('panel.order') }}</label>
              <input type="number" id="order" name="order" value="{{ old('order', $maxOrder + 1) }}" min="{{ $maxOrder + 1 }}">
            </div>
          </div>
        </div>

        <div class="form-section" id="video-fields" style="display:none">
          <h3>{{ __('panel.video_settings') }}</h3>
          <div class="form-group">
            <label for="video_type">{{ __('panel.video_type') }} *</label>
            <select id="video_type" name="video_type">
              <option value="">{{ __('panel.select_video_type') }}</option>
              <option value="youtube" {{ old('video_type') == 'youtube' ? 'selected' : '' }}>{{ __('panel.youtube_video') }}</option>
              <option value="bunny" {{ old('video_type') == 'bunny' ? 'selected' : '' }}>{{ __('panel.upload_video') }}</option>
            </select>
          </div>
          <div class="form-group" id="video_url_field" style="display:none">
            <label for="video_url">{{ __('panel.youtube_url') }} *</label>
            <input type="url" id="video_url" name="video_url" value="{{ old('video_url') }}" placeholder="https://www.youtube.com/watch?v=...">
            <small class="form-text">{{ __('panel.youtube_url_help') }}</small>
          </div>
          <div class="form-group" id="upload_video_field" style="display:none">
            <label for="upload_video">{{ __('panel.upload_video_file') }} *</label>
            <div class="file-upload-wrapper">
              <input type="file" id="upload_video" name="upload_video" accept="video/mp4,video/avi,video/mov,video/wmv">
              <div class="upload-preview" id="video-preview" style="display:none">
                <video controls style="max-width:100%;height:200px"></video>
              </div>
            </div>
            <small class="form-text">{{ __('panel.video_upload_help') }}</small>
          </div>
          <div class="form-group">
            <label for="video_duration">{{ __('panel.video_duration') }} ({{ __('panel.seconds') }})</label>
            <input type="number" id="video_duration" name="video_duration" value="{{ old('video_duration') }}" min="1">
            <small class="form-text">{{ __('panel.video_duration_help') }}</small>
          </div>
        </div>

        <div class="form-section" id="pdf-fields" style="display:none">
          <h3>{{ __('panel.document_settings') }}</h3>
          <div class="form-group">
            <label for="pdf_type">{{ __('panel.document_type') }} *</label>
            <select id="pdf_type" name="pdf_type">
              <option value="">{{ __('panel.select_document_type') }}</option>
              <option value="notes" {{ old('pdf_type') == 'notes' ? 'selected' : '' }}>{{ __('panel.notes') }}</option>
              <option value="homework" {{ old('pdf_type') == 'homework' ? 'selected' : '' }}>{{ __('panel.homework') }}</option>
              <option value="worksheet" {{ old('pdf_type') == 'worksheet' ? 'selected' : '' }}>{{ __('panel.worksheet') }}</option>
              <option value="other" {{ old('pdf_type') == 'other' ? 'selected' : '' }}>{{ __('panel.other') }}</option>
            </select>
          </div>
          <div class="form-group">
            <label for="file_path">{{ __('panel.upload_pdf') }} *</label>
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
            <small class="form-text">{{ __('panel.pdf_upload_help') }}</small>
          </div>
        </div>

        <div class="form-section" id="quiz-settings" style="display:none">
          <h3>{{ __('panel.quiz_assignment_settings') }}</h3>
          <div class="alert alert-info">{{ __('panel.quiz_assignment_note') }}</div>
        </div>

        <div class="form-actions">
          <a href="{{ route('teacher.courses.sections.index', $course) }}" class="btn btn-secondary">{{ __('panel.cancel') }}</a>
          <button type="submit" class="btn btn-primary" id="submitBtn"><i class="fas fa-save"></i>{{ __('panel.add_content') }}</button>
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
.course-name{color:#6b7280;margin:0;font-size:15px}

.content-form{max-width:880px}
.form-section{background:#fff;border:1px solid #eef0f3;border-radius:14px;padding:20px;margin-bottom:20px}
.form-section h3{margin:0 0 14px 0;color:#0f172a;font-size:16px;border-bottom:1px solid #eef0f3;padding-bottom:10px}

.form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px}
.form-group{margin-bottom:14px}
.form-group label{display:block;margin-bottom:6px;font-weight:800;color:#0f172a}
.form-group input,.form-group select,.form-group textarea{width:100%;padding:10px 12px;border:1px solid #e5e7eb;border-radius:10px;font-size:14px;transition:border-color .18s,box-shadow .18s}
.form-group input:focus,.form-group select:focus,.form-group textarea:focus{outline:0;border-color:#0055D2;box-shadow:0 0 0 3px rgba(0,85,210,.12)}

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
.btn-primary:disabled{background:#6b7280;border-color:#6b7280;cursor:not-allowed}
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
  var quizSettings=document.getElementById('quiz-settings');
  var contentType=ct?ct.value:'';
  var videoType=vt?vt.value:'';
  if(videoFields) videoFields.style.display='none';
  if(videoUrl) videoUrl.style.display='none';
  if(videoUpload) videoUpload.style.display='none';
  if(pdfFields) pdfFields.style.display='none';
  if(quizSettings) quizSettings.style.display='none';
  if(contentType==='video'){
    if(videoFields) videoFields.style.display='block';
    if(videoType==='youtube'&&videoUrl) videoUrl.style.display='block';
    if(videoType==='bunny'&&videoUpload) videoUpload.style.display='block';
  }else if(contentType==='pdf'){
    if(pdfFields) pdfFields.style.display='block';
  }else if(contentType==='quiz'||contentType==='assignment'){
    if(quizSettings) quizSettings.style.display='block';
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

  // ✅ BUNNY UPLOAD HANDLER
  var loadingDiv = document.createElement('div');
  loadingDiv.id = 'upload-loading';
  loadingDiv.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.8);color:#fff;font-size:1.5rem;display:none;justify-content:center;align-items:center;z-index:9999;flex-direction:column';
  loadingDiv.innerHTML = '<div style="text-align:center"><i class="fas fa-spinner fa-spin fa-3x" style="margin-bottom:16px"></i><p>{{ __("panel.uploading_video") }}</p><small>{{ __("panel.please_wait") }}</small></div>';
  document.body.appendChild(loadingDiv);

  async function uploadToBunny(file, courseId) {
    const res = await fetch('/api/bunny/sign-upload', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ course_id: courseId })
    });

    const data = await res.json();
    if (!data.upload_url || !data.file_path) {
      throw new Error('Failed to get upload URL from server');
    }

    const uploadRes = await fetch(data.upload_url, {
      method: 'PUT',
      headers: {
        'AccessKey': data.access_key,
        'Content-Type': file.type
      },
      body: file
    });

    if (!uploadRes.ok) {
      throw new Error('Video upload failed at Bunny CDN');
    }

    return data.file_path;
  }

  if(form){
    form.addEventListener('submit', async function(e){
      var contentType = document.getElementById('content_type').value;
      var videoType = vt ? vt.value : '';
      
      // ✅ Check if it's a Bunny video upload
      if (contentType === 'video' && videoType === 'bunny' && uploadVideo && uploadVideo.files.length > 0) {
        e.preventDefault();
        
        var file = uploadVideo.files[0];
        loadingDiv.style.display = 'flex';

        try {
          // Upload to Bunny
          var path = await uploadToBunny(file, {{ $course->id }});

          // Create hidden input for video_url
          var hiddenInput = document.createElement('input');
          hiddenInput.type = 'hidden';
          hiddenInput.name = 'video_url';
          hiddenInput.value = path;
          form.appendChild(hiddenInput);

          // Calculate duration if not provided
          var durationInput = document.getElementById('video_duration');
          if (!durationInput.value) {
            var video = document.createElement('video');
            video.preload = 'metadata';
            video.src = URL.createObjectURL(file);
            video.onloadedmetadata = function() {
              window.URL.revokeObjectURL(video.src);
              durationInput.value = Math.floor(video.duration);
              uploadVideo.value = '';
              form.submit();
            };
            return;
          }

          uploadVideo.value = '';
          form.submit();

        } catch (err) {
          alert('{{ __("panel.video_upload_failed") }}: ' + err.message);
          console.error(err);
          loadingDiv.style.display = 'none';
        }
        return;
      }

      // ✅ Normal validation for non-Bunny uploads
      var ok = true;
      var msg = '';
      if(contentType==='video'){
        var videoType=document.getElementById('video_type').value;
        if(!videoType){ ok=false; msg='{{ __("panel.please_select_video_type") }}'; }
        else if(videoType==='youtube'&&!document.getElementById('video_url').value){ ok=false; msg='{{ __("panel.please_enter_youtube_url") }}'; }
      }else if(contentType==='pdf'){
        if(!document.getElementById('pdf_type').value){ ok=false; msg='{{ __("panel.please_select_document_type") }}'; }
        else if(!(document.getElementById('file_path').files||[]).length){ ok=false; msg='{{ __("panel.please_upload_pdf_file") }}'; }
      }
      
      if(!ok){
        e.preventDefault();
        alert(msg);
        return;
      }
      
      if(submitBtn){
        submitBtn.disabled=true;
        submitBtn.innerHTML='<i class="fas fa-spinner fa-spin"></i> {{ __("panel.uploading") }}...';
        setTimeout(function(){
          submitBtn.disabled=false;
          submitBtn.innerHTML='<i class="fas fa-save"></i> {{ __("panel.add_content") }}';
        },30000);
      }
    });
  }
});
</script>
@endsection
