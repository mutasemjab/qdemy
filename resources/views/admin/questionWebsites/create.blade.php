@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
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
                    <form action="{{ route('questionWebsites.store') }}" method="POST">
                        @csrf
                        
                        <!-- Type Selection -->
                        <div class="form-group">
                            <label for="type" class="required">
                                <i class="fas fa-tag text-info"></i> {{ __('messages.Category') }}
                            </label>
                            <select class="form-control @error('type') is-invalid @enderror" 
                                    id="type" name="type" required>
                                <option value="">{{ __('messages.Select Category') }}</option>
                                <option value="all" {{ old('type') == 'all' ? 'selected' : '' }}>{{ __('messages.All Categories') }}</option>
                                <option value="register" {{ old('type') == 'register' ? 'selected' : '' }}>{{ __('messages.Registration') }}</option>
                                <option value="payment" {{ old('type') == 'payment' ? 'selected' : '' }}>{{ __('messages.Payment') }}</option>
                                <option value="card" {{ old('type') == 'card' ? 'selected' : '' }}>{{ __('messages.Card') }}</option>
                                <option value="courses" {{ old('type') == 'courses' ? 'selected' : '' }}>{{ __('messages.Courses') }}</option>
                                <option value="technical" {{ old('type') == 'technical' ? 'selected' : '' }}>{{ __('messages.Technical') }}</option>
                                <option value="privacy" {{ old('type') == 'privacy' ? 'selected' : '' }}>{{ __('messages.Privacy') }}</option>
                                <option value="account" {{ old('type') == 'account' ? 'selected' : '' }}>{{ __('messages.Account') }}</option>
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
                                       id="question_en" name="question_en" value="{{ old('question_en') }}" 
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
                                          placeholder="{{ __('messages.Provide a detailed and helpful answer in English') }}" required>{{ old('answer_en') }}</textarea>
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
                                       id="question_ar" name="question_ar" value="{{ old('question_ar') }}" 
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
                                          dir="rtl" required>{{ old('answer_ar') }}</textarea>
                                @error('answer_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

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
</style>
@endsection