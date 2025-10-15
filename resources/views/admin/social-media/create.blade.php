@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('messages.add_social_media') }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('social-media.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="video" class="form-label">{{ __('messages.video') }} <span class="text-danger">*</span></label>
                            <input type="file" 
                                   class="form-control @error('video') is-invalid @enderror" 
                                   id="video" 
                                   name="video" 
                                   accept="video/*">
                            @error('video')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">{{ __('messages.video_formats') }}</small>
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">{{ __('messages.save') }}</button>
                            <a href="{{ route('social-media.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection