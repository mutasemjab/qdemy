@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.Edit FAQ') }}</h3>
                    <div>
                        <a href="{{ route('questionWebsites.show', $question->id) }}" class="btn btn-info">
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
                        <div class="row">
                            <div class="col-md-6">
                                <div class="faq-preview-card">
                                    <h6 class="text-primary"><i class="fas fa-flag"></i> {{ __('messages.English') }}</h6>
                                    <div class="preview-question">
                                        <i class="fas fa-question-circle text-primary"></i>
                                        <strong>{{ $question->question_en }}</strong>
                                    </div>
                                    <div class="preview-answer">
                                        <i class="fas fa-reply text-success"></i>
                                        <span>{{ $question->answer_en }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="faq-preview-card">
                                    <h6 class="text-danger"><i class="fas fa-flag"></i> {{ __('messages.Arabic') }}</h6>
                                    <div class="preview-question" dir="rtl">
                                        <i class="fas fa-question-circle text-primary"></i>
                                        <strong>{{ $question->question_ar }}</strong>
                                    </div>
                                    <div class="preview-answer" dir="rtl">
                                        <i class="fas fa-reply text-success"></i>
                                        <span>{{ $question->answer_ar }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <span class="badge badge-info badge-lg">
                                <i class="fas fa-tag"></i> {{ ucfirst($question->type) }}
                            </span>
                        </div>
                    </div>

                    <hr>

                    <!-- Edit Form -->
                    <form action="{{ route('questionWebsites.update', $question) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Type Selection -->
                        <div class="form-group">
                            <label for="type" class="required">
                                <i class="fas fa-tag text-info"></i> {{ __('messages.Category') }}
                            </label>
                            <select class="form-control @error('type') is-invalid @enderror" 
                                    id="type" name="type" required>
                                <option value="all" {{ old('type', $question->type) == 'all' ? 'selected' : '' }}>{{ __('messages.All Categories') }}</option>
                                <option value="register" {{ old('type', $question->type) == 'register' ? 'selected' : '' }}>{{ __('messages.Registration') }}</option>
                                <option value="payment" {{ old('type', $question->type) == 'payment' ? 'selected' : '' }}>{{ __('messages.Payment') }}</option>
                                <option value="card" {{ old('type', $question->type) == 'card' ? 'selected' : '' }}>{{ __('messages.Card') }}</option>
                                <option value="courses" {{ old('type', $question->type) == 'courses' ? 'selected' : '' }}>{{ __('messages.Courses') }}</option>
                                <option value="technical" {{ old('type', $question->type) == 'technical' ? 'selected' : '' }}>{{ __('messages.Technical') }}</option>
                                <option value="privacy" {{ old('type', $question->type) == 'privacy' ? 'selected' : '' }}>{{ __('messages.Privacy') }}</option>
                                <option value="account" {{ old('type', $question->type) == 'account' ? 'selected' : '' }}>{{ __('messages.Account') }}</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <!-- English Version -->
                        <div class="language-section">
                            <h5 class="section-title">
                                <i class="fas fa-flag text-primary"></i> {{ __('messages.English Version') }}
                            </h5>
                            
                            <div class="form-group">
                                <label for="question_en" class="required">
                                    <i class="fas fa-question-circle text-primary"></i> {{ __('messages.Question (English)') }}
                                </label>
                                <input type="text" class="form-control @error('question_en') is-invalid @enderror" 
                                       id="question_en" name="question_en" 
                                       value="{{ old('question_en', $question->question_en) }}" 
                                       placeholder="{{ __('messages.Enter the frequently asked question in English') }}" required>
                                @error('question_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="answer_en" class="required">
                                    <i class="fas fa-reply text-success"></i> {{ __('messages.Answer (English)') }}
                                </label>
                                <textarea class="form-control @error('answer_en') is-invalid @enderror" 
                                          id="answer_en" name="answer_en" rows="6" 
                                          placeholder="{{ __('messages.Provide a detailed and helpful answer in English') }}" required>{{ old('answer_en', $question->answer_en) }}</textarea>
                                @error('answer_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Arabic Version -->
                        <div class="language-section">
                            <h5 class="section-title">
                                <i class="fas fa-flag text-danger"></i> {{ __('messages.Arabic Version') }}
                            </h5>
                            
                            <div class="form-group">
                                <label for="question_ar" class="required">
                                    <i class="fas fa-question-circle text-primary"></i> {{ __('messages.Question (Arabic)') }}
                                </label>
                                <input type="text" class="form-control @error('question_ar') is-invalid @enderror" 
                                       id="question_ar" name="question_ar" 
                                       value="{{ old('question_ar', $question->question_ar) }}" 
                                       placeholder="{{ __('messages.Enter the frequently asked question in Arabic') }}" 
                                       dir="rtl" required>
                                @error('question_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="answer_ar" class="required">
                                    <i class="fas fa-reply text-success"></i> {{ __('messages.Answer (Arabic)') }}
                                </label>
                                <textarea class="form-control @error('answer_ar') is-invalid @enderror" 
                                          id="answer_ar" name="answer_ar" rows="6" 
                                          placeholder="{{ __('messages.Provide a detailed and helpful answer in Arabic') }}" 
                                          dir="rtl" required>{{ old('answer_ar', $question->answer_ar) }}</textarea>
                                @error('answer_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

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
    height: 100%;
}

.faq-preview-card h6 {
    font-weight: 600;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #f8f9fa;
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

.language-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.section-title {
    color: #495057;
    font-weight: 600;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #dee2e6;
}

.section-title i {
    margin-right: 8px;
}

.badge-lg {
    font-size: 1rem;
    padding: 8px 15px;
}
</style>
@endsection