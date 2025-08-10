@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.Edit FAQ') }}</h3>
                    <div>
                        <a href="{{ route('questionWebsites.show', $question) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> {{ __('messages.View') }}
                        </a>
                        <a href="{{ route('questionWebsites.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.Back to FAQs') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Current FAQ Preview -->
                    <div class="current-faq-preview mb-4">
                        <h5 class="text-primary">{{ __('messages.Current FAQ') }}</h5>
                        <div class="faq-preview-card">
                            <div class="preview-question">
                                <i class="fas fa-question-circle text-primary"></i>
                                <strong>{{ $question->question }}</strong>
                            </div>
                            <div class="preview-answer">
                                <i class="fas fa-reply text-success"></i>
                                <span>{{ $question->answer }}</span>
                            </div>
                           
                        </div>
                    </div>

                    <hr>

                    <!-- Edit Form -->
                    <form action="{{ route('questionWebsites.update', $question) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="question" class="required">
                                <i class="fas fa-question-circle text-primary"></i> {{ __('messages.Question') }}
                            </label>
                            <input type="text" class="form-control @error('question') is-invalid @enderror" 
                                   id="question" name="question" value="{{ old('question', $question->question) }}" 
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
                                      placeholder="{{ __('messages.Provide a detailed and helpful answer') }}" required>{{ old('answer', $question->answer) }}</textarea>
                            @error('answer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">{{ __('messages.Be thorough and helpful in your response') }}</small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ __('messages.Update FAQ') }}
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

.current-faq-preview {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
}

.faq-preview-card {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 20px;
    margin-top: 15px;
}

.preview-question {
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #f8f9fa;
}

.preview-question i {
    margin-right: 8px;
}

.preview-answer {
    margin-bottom: 15px;
    padding-left: 10px;
    border-left: 3px solid #28a745;
}

.preview-answer i {
    margin-right: 8px;
}

.preview-meta {
    padding-top: 10px;
    border-top: 1px solid #f8f9fa;
}

.preview-meta i {
    margin-right: 5px;
}
</style>
@endsection