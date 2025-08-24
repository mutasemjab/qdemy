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

                <!-- Search -->
                <div class="card-body">
                    <form method="GET" action="{{ route('questionWebsites.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-8">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="{{ __('messages.Search questions and answers...') }}" 
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-4">
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
                                    <h5 class="faq-question">
                                        <i class="fas fa-question-circle text-primary"></i>
                                        {{ $question->question }}
                                    </h5>
                                   
                                </div>
                                
                                <div class="collapse" id="faq-{{ $question->id }}">
                                    <div class="faq-body">
                                        <div class="faq-answer">
                                            <i class="fas fa-reply text-success"></i>
                                            <div class="answer-content">
                                                {{ $question->answer }}
                                            </div>
                                        </div>
                                        
                                        <div class="faq-actions">
                                           
                                            @can('questionWebsite-edit')
                                                <a href="{{ route('questionWebsites.edit', $question) }}" 
                                                   class="btn btn-sm btn-warning" title="{{ __('messages.Edit') }}">
                                                    <i class="fas fa-edit"></i>
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
                                                        <i class="fas fa-trash"></i>
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

.faq-question {
    margin: 0;
    color: #333;
    font-weight: 600;
    flex: 1;
    padding-right: 15px;
    font-size: 1.1rem;
}

.faq-question i {
    margin-right: 10px;
}

.faq-meta {
    display: flex;
    align-items: center;
    gap: 15px;
}

.toggle-icon {
    transition: transform 0.3s ease;
    color: #6c757d;
}

.faq-header[aria-expanded="true"] .toggle-icon {
    transform: rotate(180deg);
}

.faq-body {
    padding: 20px;
    background: #f8f9fa;
}

.faq-answer {
    display: flex;
    align-items: flex-start;
    margin-bottom: 15px;
}

.faq-answer i {
    margin-right: 10px;
    margin-top: 3px;
}

.answer-content {
    flex: 1;
    color: #555;
    line-height: 1.6;
}

.faq-actions {
    display: flex;
    gap: 5px;
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
    
    .faq-meta {
        margin-top: 10px;
        width: 100%;
        justify-content: space-between;
    }
    
    .faq-question {
        padding-right: 0;
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