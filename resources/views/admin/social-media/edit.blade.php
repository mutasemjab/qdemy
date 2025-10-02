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
                    <form action="{{ route('social-media.update', $socialMedia->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="video" class="form-label">{{ __('messages.video') }}</label>
                            
                            @if($socialMedia->video)
                                <div class="mb-2">
                                    <video width="300" height="200" controls>
                                        <source src="{{ asset('assets/admin/uploads/' . $socialMedia->video) }}" type="video/mp4">
                                        {{ __('messages.browser_not_support_video') }}
                                    </video>
                                    <p class="text-muted small">{{ __('messages.current_video') }}</p>
                                </div>
                            @endif

                            <input type="file" 
                                   class="form-control @error('video') is-invalid @enderror" 
                                   id="video" 
                                   name="video" 
                                   accept="video/*">
                            @error('video')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">{{ __('messages.leave_blank_keep_current') }}</small>
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