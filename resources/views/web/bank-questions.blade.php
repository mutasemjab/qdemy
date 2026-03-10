{{-- resources/views/bankQuestions.blade.php --}}
@extends('layouts.app')

@section('title', __('front.bank_questions'))

@section('content')
<section class="bq-page">
    <div class="universities-header-wrapper">
        <div class="universities-header">
            <h2>{{ __('front.Question Bank') }}</h2>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="examx-filters">
        <form method="GET" action="{{ route('bankQuestions.index') }}" id="filter-form">
            <div class="examx-row">
                <div class="examx-dropdown">
                    <select class="examx-pill" name="category_id" id="category_id">
                        <option value="">{{ __('front.all_programs') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
                                {{ app()->getLocale() === 'ar' ? $category->name_ar : ($category->name_en ?? $category->name_ar) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if($categoryId && $subjects->count())
                <div class="examx-dropdown">
                    <select class="examx-pill" name="subject_id" id="subject_id">
                        <option value="">{{ __('front.all_subjects') }}</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ $subjectId == $subject->id ? 'selected' : '' }}>
                                {{ app()->getLocale() === 'ar' ? $subject->name_ar : ($subject->name_en ?? $subject->name_ar) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>

            <div class="examx-search">
                <input type="text" name="search" value="{{ $search }}"
                       placeholder="{{ __('front.search_placeholder') }}">
            </div>
        </form>
    </div>

    <!-- Results Section -->
    <div class="bq-list">
        @forelse($bankQuestions as $question)
            <div class="bq-item">
                <div class="bq-thumb">
                    <img data-src="{{ asset('assets_front/images/pdf.png') }}" alt="PDF">
                </div>

                <div class="bq-right">
                    <h4 class="bq-title">
                        {{ $question->display_name ?? __('front.file_not_available') }}
                    </h4>
                    <div class="bq-sub">
                        {{ app()->getLocale() === 'ar' ? $question->subject->name_ar : ($question->subject->name_en ?? $question->subject->name_ar) }}
                        @if($question->category)
                            - {{ app()->getLocale() === 'ar' ? $question->category->name_ar : ($question->category->name_en ?? $question->category->name_ar) }}
                        @endif
                    </div>
                    <div class="bq-meta">
                        <span>{{ $question->created_at->format('d-m-Y') }}</span>
                        <span class="bq-sep">|</span>
                        <span>{{ __('front.downloads_count', ['count' => $question->download_count ?? 0]) }}</span>
                        @if($question->pdf_size)
                            <span class="bq-sep">|</span>
                            <span>{{ $question->pdf_size }}</span>
                        @endif
                    </div>
                </div>

                <div class="bq-left">
                    <a href="#" class="bq-btn bq-share" onclick="shareFile('{{ $question->pdf_path }}', '{{ $question->display_name }}')">
                        <i class="fas fa-share-nodes"></i>
                        <span>{{ __('front.share') }}</span>
                    </a>
                    @if($question->pdfExists())
                        <a href="{{ route('bankQuestions.download', $question) }}" class="bq-btn bq-download">
                           <img src="{{ asset('assets_front/images/download.gif') }}" width="20px" height="20px">
                            <span>{{ __('front.download_file') }}</span>
                        </a>
                    @else
                        <button class="bq-btn bq-download disabled" disabled>
                            <i class="fas fa-download"></i>
                            <span>{{ __('front.file_not_available') }}</span>
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="no-results">
                <p>{{ __('front.no_questions_found') }}</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($bankQuestions->hasPages())
        <div class="pagination-wrapper">
            {{ $bankQuestions->links() }}
        </div>
    @endif
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('filter-form');
    const categorySelect = document.getElementById('category_id');
    const subjectSelect = document.getElementById('subject_id');

    categorySelect?.addEventListener('change', function() {
        if (subjectSelect) subjectSelect.value = '';
        form.submit();
    });

    subjectSelect?.addEventListener('change', function() {
        form.submit();
    });

    const searchInput = document.querySelector('input[name="search"]');
    let searchTimeout;
    searchInput?.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => form.submit(), 500);
    });
});

function shareFile(fileUrl, fileName) {
    if (navigator.share) {
        navigator.share({ title: fileName, url: fileUrl });
    } else {
        navigator.clipboard.writeText(fileUrl).then(function() {
            alert('{{ __("Link copied to clipboard") }}');
        });
    }
}
</script>
@endpush