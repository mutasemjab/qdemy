@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.Create FAQ') }}</h3>
                    <div>
                        <a href="{{ route('questionWebsites.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.Back to FAQs') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Create Form -->
                    <form action="{{ route('questionWebsites.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="question" class="required">
                                <i class="fas fa-question-circle text-primary"></i> {{ __('messages.Question') }}
                            </label>
                            <input type="text" class="form-control @error('question') is-invalid @enderror" 
                                   id="question" name="question" value="{{ old('question') }}" 
                                   placeholder="{{ __('messages.Enter the frequently asked question') }}" required>
                            @error('question')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">{{ __('messages.Make it clear and concise') }}</small>
                        </div>

                        <div class="form-group">
                            <label for="answer" class="required">
                                <i class="fas fa-reply text-success"></i> {{ __('messages.Answer') }}
                            </label>
                            <textarea class="form-control @error('answer') is-invalid @enderror" 
                                      id="answer" name="answer" rows="6" 
                                      placeholder="{{ __('messages.Provide a detailed and helpful answer') }}" required>{{ old('answer') }}</textarea>
                            @error('answer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">{{ __('messages.Be thorough and helpful in your response') }}</small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ __('messages.Create FAQ') }}
                            </button>
                            <a href="{{ route('questionWebsites.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> {{ __('messages.Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.required:after {
    content: " *";
    color: red;
}

.form-group label {
    font-weight: 600;
    color: #333;
}

.form-control {
    border-radius: 6px;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

textarea.form-control {
    resize: vertical;
    min-height: 120px;
}
</style>
@endsection