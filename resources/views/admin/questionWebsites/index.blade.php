@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.FAQ Management') }}</h3>
                    @can('questionWebsite-add')
                        <a href="{{ route('questionWebsites.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> {{ __('messages.Add FAQ') }}
                        </a>
                    @endcan
                </div>

                <!-- Search and Filter -->
                <div class="card-body">
                    <form method="GET" action="{{ route('questionWebsites.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="{{ __('messages.Search questions and answers...') }}" 
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="type" class="form-control">
                                    <option value="">{{ __('messages.All Categories') }}</option>
                                    <option value="all" {{ request('type') == 'all' ? 'selected' : '' }}>{{ __('messages.All Categories') }}</option>
                                    <option value="register" {{ request('type') == 'register' ? 'selected' : '' }}>{{ __('messages.Registration') }}</option>
                                    <option value="payment" {{ request('type') == 'payment' ? 'selected' : '' }}>{{ __('messages.Payment') }}</option>
                                    <option value="card" {{ request('type') == 'card' ? 'selected' : '' }}>{{ __('messages.Card') }}</option>
                                    <option value="courses" {{ request('type') == 'courses' ? 'selected' : '' }}>{{ __('messages.Courses') }}</option>
                                    <option value="technical" {{ request('type') == 'technical' ? 'selected' : '' }}>{{ __('messages.Technical') }}</option>
                                    <option value="privacy" {{ request('type') == 'privacy' ? 'selected' : '' }}>{{ __('messages.Privacy') }}</option>
                                    <option value="account" {{ request('type') == 'account' ? 'selected' : '' }}>{{ __('messages.Account') }}</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-search"></i> {{ __('messages.Search') }}
                                </button>
                                <a href="{{ route('questionWebsites.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-redo"></i> {{ __('messages.Reset') }}
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- FAQ Cards -->
                    <div class="faq-container">
                        @forelse($questions as $question)
                            <div class="faq-card">
                                <div class="faq-header" data-toggle="collapse" data-target="#faq-{{ $question->id }}" 
                                     aria-expanded="false" aria-controls="faq-{{ $question->id }}">
                                    <div class="faq-question-wrapper">
                                        <h5 class="faq-question">
                                            <i class="fas fa-question-circle text-primary"></i>
                                            @if(app()->getLocale() == 'ar')
                                                {{ $question->question_ar }}
                                            @else
                                                {{ $question->question_en }}
                                            @endif
                                        </h5>
                                        <div class="faq-meta">
                                            <span class="badge badge-info">
                                                <i class="fas fa-tag"></i> {{ ucfirst($question->type) }}
                                            </span>
                                        </div>
                                    </div>
                                    <i class="fas fa-chevron-down toggle-icon"></i>
                                </div>
                                
                                <div class="collapse" id="faq-{{ $question->id }}">
                                    <div class="faq-body">
                                        <!-- English Version -->
                                        <div class="language-version">
                                            <h6 class="language-title">
                                                <i class="fas fa-flag text-primary"></i> {{ __('messages.English') }}
                                            </h6>
                                            <div class="faq-answer">
                                                <div class="answer-label">
                                                    <i class="fas fa-question-circle text-primary"></i>
                                                    <strong>{{ __('messages.Question') }}:</strong>
                                                </div>
                                                <div class="answer-content">
                                                    {{ $question->question_en }}
                                                </div>
                                            </div>
                                            <div class="faq-answer">
                                                <div class="answer-label">
                                                    <i class="fas fa-reply text-success"></i>
                                                    <strong>{{ __('messages.Answer') }}:</strong>
                                                </div>
                                                <div class="answer-content">
                                                    {{ $question->answer_en }}
                                                </div>
                                            </div>
                                        </div>

                                        <hr>

                                        <!-- Arabic Version -->
                                        <div class="language-version">
                                            <h6 class="language-title">
                                                <i class="fas fa-flag text-danger"></i> {{ __('messages.Arabic') }}
                                            </h6>
                                            <div class="faq-answer" dir="rtl">
                                                <div class="answer-label">
                                                    <i class="fas fa-question-circle text-primary"></i>
                                                    <strong>{{ __('messages.Question') }}:</strong>
                                                </div>
                                                <div class="answer-content">
                                                    {{ $question->question_ar }}
                                                </div>
                                            </div>
                                            <div class="faq-answer" dir="rtl">
                                                <div class="answer-label">
                                                    <i class="fas fa-reply text-success"></i>
                                                    <strong>{{ __('messages.Answer') }}:</strong>
                                                </div>
                                                <div class="answer-content">
                                                    {{ $question->answer_ar }}
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="faq-actions">
                                            @can('questionWebsite-edit')
                                                <a href="{{ route('questionWebsites.edit', $question) }}" 
                                                   class="btn btn-sm btn-warning" title="{{ __('messages.Edit') }}">
                                                    <i class="fas fa-edit"></i> {{ __('messages.Edit') }}
                                                </a>
                                            @endcan
                                            @can('questionWebsite-delete')
                                                <form action="{{ route('questionWebsites.destroy', $question) }}" 
                                                      method="POST" class="d-inline"
                                                      onsubmit="return confirm('{{ __('messages.Are you sure you want to delete this FAQ?') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" 
                                                            title="{{ __('messages.Delete') }}">
                                                        <i class="fas fa-trash"></i> {{ __('messages.Delete') }}
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="fas fa-question-circle fa-3x text-muted"></i>
                                <h5 class="text-muted mt-3">{{ __('messages.No FAQs found') }}</h5>
                                @can('questionWebsite-add')
                                    <a href="{{ route('questionWebsites.create') }}" class="btn btn-primary mt-2">
                                        <i class="fas fa-plus"></i> {{ __('messages.Create your first FAQ') }}
                                    </a>
                                @endcan
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($questions->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $questions->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.faq-container {
    max-width: 100%;
}

.faq-card {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    margin-bottom: 15px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.faq-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.faq-header {
    padding: 20px;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #f8f9fa;
}

.faq-header:hover {
    background-color: #f8f9fa;
}

.faq-question-wrapper {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.faq-question {
    margin: 0;
    color: #333;
    font-weight: 600;
    font-size: 1.1rem;
}

.faq-question i {
    margin-right: 10px;
}

.faq-meta {
    display: flex;
    align-items: center;
    gap: 10px;
}

.badge {
    font-size: 0.85rem;
    padding: 5px 10px;
}

.toggle-icon {
    transition: transform 0.3s ease;
    color: #6c757d;
    font-size: 1.2rem;
}

.faq-header[aria-expanded="true"] .toggle-icon {
    transform: rotate(180deg);
}

.faq-body {
    padding: 20px;
    background: #f8f9fa;
}

.language-version {
    margin-bottom: 20px;
}

.language-title {
    font-weight: 600;
    color: #495057;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #dee2e6;
}

.faq-answer {
    margin-bottom: 15px;
    background: white;
    padding: 15px;
    border-radius: 6px;
    border-left: 3px solid #007bff;
}

.answer-label {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 8px;
}

.answer-label i {
    margin-right: 5px;
}

.answer-content {
    color: #333;
    line-height: 1.6;
}

.faq-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    padding-top: 15px;
    border-top: 1px solid #dee2e6;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
}

@media (max-width: 768px) {
    .faq-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .faq-question-wrapper {
        width: 100%;
    }
    
    .toggle-icon {
        align-self: flex-end;
        margin-top: 10px;
    }
    
    .faq-question {
        font-size: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle FAQ collapse animations
    $('.collapse').on('show.bs.collapse', function() {
        $(this).prev('.faq-header').attr('aria-expanded', 'true');
    });
    
    $('.collapse').on('hide.bs.collapse', function() {
        $(this).prev('.faq-header').attr('aria-expanded', 'false');
    });
});
</script>
@endsection