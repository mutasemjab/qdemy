@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.View FAQ') }}</h3>
                    <div>
                        @can('questionWebsite-edit')
                            <a href="{{ route('questionWebsites.edit', $question->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> {{ __('messages.Edit') }}
                            </a>
                        @endcan
                        <a href="{{ route('questionWebsites.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.Back to FAQs') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Category Badge -->
                    <div class="mb-4">
                        <span class="badge badge-info badge-lg">
                            <i class="fas fa-tag"></i> {{ ucfirst($question->type) }}
                        </span>
                    </div>

                    <!-- English Version -->
                    <div class="language-section">
                        <h4 class="section-title">
                            <i class="fas fa-flag text-primary"></i> {{ __('messages.English Version') }}
                        </h4>
                        
                        <div class="faq-detail-card">
                            <div class="detail-row">
                                <div class="detail-label">
                                    <i class="fas fa-question-circle text-primary"></i>
                                    <strong>{{ __('messages.Question') }}</strong>
                                </div>
                                <div class="detail-content">
                                    {{ $question->question_en }}
                                </div>
                            </div>

                            <div class="detail-row">
                                <div class="detail-label">
                                    <i class="fas fa-reply text-success"></i>
                                    <strong>{{ __('messages.Answer') }}</strong>
                                </div>
                                <div class="detail-content">
                                    {{ $question->answer_en }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Arabic Version -->
                    <div class="language-section">
                        <h4 class="section-title">
                            <i class="fas fa-flag text-danger"></i> {{ __('messages.Arabic Version') }}
                        </h4>
                        
                        <div class="faq-detail-card">
                            <div class="detail-row" dir="rtl">
                                <div class="detail-label">
                                    <i class="fas fa-question-circle text-primary"></i>
                                    <strong>{{ __('messages.Question') }}</strong>
                                </div>
                                <div class="detail-content">
                                    {{ $question->question_ar }}
                                </div>
                            </div>

                            <div class="detail-row" dir="rtl">
                                <div class="detail-label">
                                    <i class="fas fa-reply text-success"></i>
                                    <strong>{{ __('messages.Answer') }}</strong>
                                </div>
                                <div class="detail-content">
                                    {{ $question->answer_ar }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Metadata -->
                    <div class="metadata-section">
                        <h5 class="section-title">
                            <i class="fas fa-info-circle text-info"></i> {{ __('messages.Information') }}
                        </h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="metadata-item">
                                    <i class="fas fa-calendar-plus text-primary"></i>
                                    <strong>{{ __('messages.Created At') }}:</strong>
                                    <span>{{ $question->created_at->format('Y-m-d H:i') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="metadata-item">
                                    <i class="fas fa-calendar-check text-success"></i>
                                    <strong>{{ __('messages.Updated At') }}:</strong>
                                    <span>{{ $question->updated_at->format('Y-m-d H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        @can('questionWebsite-edit')
                            <a href="{{ route('questionWebsites.edit', $question->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> {{ __('messages.Edit FAQ') }}
                            </a>
                        @endcan
                        
                        @can('questionWebsite-delete')
                            <form action="{{ route('questionWebsites.destroy', $question->id) }}" 
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('{{ __('messages.Are you sure you want to delete this FAQ?') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> {{ __('messages.Delete FAQ') }}
                                </button>
                            </form>
                        @endcan
                        
                        <a href="{{ route('questionWebsites.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.Back to List') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.language-section {
    background: #f8f9fa;
    padding: 25px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.section-title {
    color: #495057;
    font-weight: 600;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #dee2e6;
}

.section-title i {
    margin-right: 10px;
}

.faq-detail-card {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 20px;
}

.detail-row {
    margin-bottom: 25px;
}

.detail-row:last-child {
    margin-bottom: 0;
}

.detail-label {
    color: #6c757d;
    font-size: 0.95rem;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.detail-label i {
    font-size: 1.1rem;
}

.detail-content {
    color: #333;
    font-size: 1.05rem;
    line-height: 1.7;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 6px;
    border-left: 4px solid #007bff;
}

.metadata-section {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #dee2e6;
}

.metadata-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 6px;
    margin-bottom: 10px;
}

.metadata-item i {
    font-size: 1.2rem;
}

.metadata-item strong {
    color: #495057;
}

.metadata-item span {
    color: #6c757d;
}

.badge-lg {
    font-size: 1.1rem;
    padding: 10px 20px;
}

.action-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.action-buttons .btn {
    min-width: 150px;
}

@media (max-width: 768px) {
    .action-buttons {
        flex-direction: column;
    }
    
    .action-buttons .btn,
    .action-buttons form {
        width: 100%;
    }
    
    .action-buttons .btn {
        min-width: auto;
    }
}
</style>
@endsection