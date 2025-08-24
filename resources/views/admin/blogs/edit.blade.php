@extends('layouts.app')

@section('title', __('messages.edit_blog'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.edit_blog') }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('blogs.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.back') }}
                        </a>
                    </div>
                </div>

                <form action="{{ route('blogs.update', $blog) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <!-- Arabic Title -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title_ar">{{ __('messages.title_ar') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title_ar') is-invalid @enderror" 
                                           id="title_ar" name="title_ar" value="{{ old('title_ar', $blog->title_ar) }}" 
                                           placeholder="{{ __('messages.enter_title_ar') }}" dir="rtl">
                                    @error('title_ar')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- English Title -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title_en">{{ __('messages.title_en') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title_en') is-invalid @enderror" 
                                           id="title_en" name="title_en" value="{{ old('title_en', $blog->title_en) }}" 
                                           placeholder="{{ __('messages.enter_title_en') }}">
                                    @error('title_en')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Arabic Description -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description_ar">{{ __('messages.description_ar') }} <span class="text-danger">*</span></label>
                                    <textarea class="form-control tinymce @error('description_ar') is-invalid @enderror" 
                                              id="description_ar" name="description_ar" rows="6" 
                                              placeholder="{{ __('messages.enter_description_ar') }}" dir="rtl">{{ old('description_ar', $blog->description_ar) }}</textarea>
                                    @error('description_ar')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- English Description -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description_en">{{ __('messages.description_en') }} <span class="text-danger">*</span></label>
                                    <textarea class="form-control tinymce @error('description_en') is-invalid @enderror" 
                                              id="description_en" name="description_en" rows="6" 
                                              placeholder="{{ __('messages.enter_description_en') }}">{{ old('description_en', $blog->description_en) }}</textarea>
                                    @error('description_en')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Main Photo -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="photo">{{ __('messages.main_photo') }}</label>
                                    <input type="file" class="form-control-file @error('photo') is-invalid @enderror" 
                                           id="photo" name="photo" accept="image/*">
                                    <small class="form-text text-muted">
                                        {{ __('messages.allowed_formats') }}: JPG, JPEG, PNG, GIF. {{ __('messages.max_size') }}: 2MB
                                    </small>
                                    @error('photo')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                    
                                    <!-- Current Photo -->
                                    @if($blog->photo_url)
                                    <div class="mt-2">
                                        <label class="form-label">{{ __('messages.current_photo') }}:</label><br>
                                        <img src="{{ $blog->photo_url }}" alt="{{ $blog->title }}" 
                                             class="img-thumbnail current-photo" 
                                             style="width: 150px; height: 150px; object-fit: cover;">
                                    </div>
                                    @endif
                                    
                                    <!-- Preview -->
                                    <div class="mt-2">
                                        <img id="photo-preview" src="#" alt="" class="img-thumbnail d-none" 
                                             style="width: 150px; height: 150px; object-fit: cover;">
                                    </div>
                                </div>
                            </div>

                            <!-- Cover Photo -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="photo_cover">{{ __('messages.cover_photo') }}</label>
                                    <input type="file" class="form-control-file @error('photo_cover') is-invalid @enderror" 
                                           id="photo_cover" name="photo_cover" accept="image/*">
                                    <small class="form-text text-muted">
                                        {{ __('messages.allowed_formats') }}: JPG, JPEG, PNG, GIF. {{ __('messages.max_size') }}: 2MB
                                    </small>
                                    @error('photo_cover')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                    
                                    <!-- Current Cover Photo -->
                                    @if($blog->photo_cover_url)
                                    <div class="mt-2">
                                        <label class="form-label">{{ __('messages.current_cover_photo') }}:</label><br>
                                        <img src="{{ $blog->photo_cover_url }}" alt="{{ $blog->title }}" 
                                             class="img-thumbnail current-cover" 
                                             style="width: 150px; height: 150px; object-fit: cover;">
                                    </div>
                                    @endif
                                    
                                    <!-- Preview -->
                                    <div class="mt-2">
                                        <img id="cover-preview" src="#" alt="" class="img-thumbnail d-none" 
                                             style="width: 150px; height: 150px; object-fit: cover;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> {{ __('messages.update') }}
                        </button>
                        <a href="{{ route('blogs.index') }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-times"></i> {{ __('messages.cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Preview main photo
    $('#photo').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#photo-preview').attr('src', e.target.result).removeClass('d-none');
                $('.current-photo').addClass('opacity-50');
            }
            reader.readAsDataURL(file);
        } else {
            $('#photo-preview').addClass('d-none');
            $('.current-photo').removeClass('opacity-50');
        }
    });
        // Preview cover photo
    $('#photo_cover').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#cover-preview').attr('src', e.target.result).removeClass('d-none');
                $('.current-cover').addClass('opacity-50');
            }
            reader.readAsDataURL(file);
        } else {
            $('#cover-preview').addClass('d-none');
            $('.current-cover').removeClass('opacity-50');
        }
    });
</script>
@endpush

