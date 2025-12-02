@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('messages.edit_social_media') }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('social-media.update', $social_medium->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="video" class="form-label">{{ __('messages.youtube_link') }} <span class="text-danger">*</span></label>
                            
                            @if($social_medium->video)
                                <div class="mb-2">
                                    <a href="{{ $social_medium->video }}" target="_blank" class="btn btn-sm btn-info">
                                        {{ __('messages.view_current_video') }}
                                    </a>
                                    <p class="text-muted small mt-1">{{ __('messages.current_link') }}: {{ Str::limit($social_medium->video, 50) }}</p>
                                </div>
                            @endif

                            <input type="url" 
                                   class="form-control @error('video') is-invalid @enderror" 
                                   id="video" 
                                   name="video" 
                                   value="{{ old('video', $social_medium->video) }}"
                                   placeholder="https://www.youtube.com/watch?v=...">
                            @error('video')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">{{ __('messages.enter_youtube_url') }}</small>
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">{{ __('messages.update') }}</button>
                            <a href="{{ route('social-media.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection