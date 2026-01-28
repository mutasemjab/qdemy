{{-- resources/views/bootCampQuestions.blade.php --}}
@extends('layouts.app')

@section('title', __('front.boot_camp_questions'))

@section('content')
    <section class="bq-page">
        <div class="universities-header-wrapper">
            <div class="universities-header">
                <h2>{{ __('front.boot_camp_questions') }}</h2>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="examx-filters">
            <form method="GET" action="{{ route('boot-camp-questions.index') }}" id="filter-form">
                <div class="examx-row-2">
                    <!-- Category/Program Dropdown -->
                    <div class="examx-dropdown">
                        <button type="button" class="examx-pill" id="category-dropdown">
                            <i class="fas fa-caret-down"></i>
                            <span>{{ __('front.choose_program') }}</span>
                        </button>
                        <ul class="examx-menu" id="category-menu">
                            <li data-value="">{{ __('front.all_programs') }}</li>
                            @foreach ($categories as $category)
                                <li data-value="{{ $category->id }}"
                                    {{ $categoryId == $category->id ? 'class=selected' : '' }}>
                                    {{ app()->getLocale() === 'ar' ? $category->name_ar : $category->name_en ?? $category->name_ar }}
                                </li>
                            @endforeach
                        </ul>
                        <input type="hidden" name="category_id" value="{{ $categoryId }}" id="category-input">
                    </div>

                    <!-- Subject Dropdown -->
                    <div class="examx-dropdown">
                        <button type="button" class="examx-pill" id="subject-dropdown">
                            <i class="fas fa-caret-down"></i>
                            <span>{{ __('front.choose_subject') }}</span>
                        </button>
                        <ul class="examx-menu" id="subject-menu">
                            @if (!$categoryId)
                                <li class="disabled">{{ __('front.select_program_first') }}</li>
                            @else
                                <li data-value="">{{ __('front.all_subjects') }}</li>
                                @foreach ($subjects as $subject)
                                    <li data-value="{{ $subject->id }}"
                                        {{ $subjectId == $subject->id ? 'class=selected' : '' }}>
                                        {{ app()->getLocale() === 'ar' ? $subject->name_ar : $subject->name_en ?? $subject->name_ar }}
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                        <input type="hidden" name="subject_id" value="{{ $subjectId }}" id="subject-input">
                    </div>
                </div>

                <!-- Search Input -->
                <div class="examx-search">
                    <input type="text" name="search" value="{{ $search }}"
                        placeholder="{{ __('front.search_placeholder') }}">
                    <i class="fas fa-magnifying-glass"></i>
                </div>
            </form>
        </div>

        <!-- Results Section -->
        <div class="bq-list">
            @forelse($bootCampQuestions as $question)
                <div class="bq-item">
                    <div class="bq-thumb">
                        <img data-src="{{ asset('assets_front/images/pdf.png') }}" alt="PDF">
                    </div>

                    <div class="bq-right">
                        <h4 class="bq-title">
                            {{ $question->display_name ?? __('front.file_not_available') }}
                        </h4>
                        <div class="bq-sub">
                            {{ app()->getLocale() === 'ar' ? $question->subject->name_ar : $question->subject->name_en ?? $question->subject->name_ar }}
                            @if ($question->category)
                                -
                                {{ app()->getLocale() === 'ar' ? $question->category->name_ar : $question->category->name_en ?? $question->category->name_ar }}
                            @endif
                        </div>
                        <div class="bq-meta">
                            <span>{{ $question->created_at->format('d-m-Y') }}</span>
                            <span class="bq-sep">|</span>
                            <span>{{ __('front.downloads_count', ['count' => $question->download_count ?? 0]) }}</span>
                            @if ($question->pdf_size)
                                <span class="bq-sep">|</span>
                                <span>{{ $question->pdf_size }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="bq-left">
                        <a href="#" class="bq-btn bq-share"
                            onclick="shareFile('{{ $question->pdf_path }}', '{{ $question->display_name }}')">
                            <i class="fas fa-share-nodes"></i>
                            <span>{{ __('front.share') }}</span>
                        </a>
                        @if ($question->pdfExists())
                            <a href="{{ route('boot-camp-questions.download', $question) }}" class="bq-btn bq-download">
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
        @if ($bootCampQuestions->hasPages())
            <div class="pagination-wrapper">
                {{ $bootCampQuestions->links() }}
            </div>
        @endif
    </section>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle dropdown functionality
            function initDropdown(buttonId, menuId, inputId) {
                const button = document.getElementById(buttonId);
                const menu = document.getElementById(menuId);
                const input = document.getElementById(inputId);

                button.addEventListener('click', function() {
                    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
                });

                menu.addEventListener('click', function(e) {
                    if (e.target.tagName === 'LI' && !e.target.classList.contains('disabled')) {
                        const value = e.target.getAttribute('data-value');
                        const text = e.target.textContent;

                        // Update button text
                        button.querySelector('span').textContent = text;

                        // Update hidden input
                        input.value = value;

                        // Remove previous selection
                        menu.querySelectorAll('li').forEach(li => li.classList.remove('selected'));

                        // Add selection to clicked item
                        e.target.classList.add('selected');

                        // Hide menu
                        menu.style.display = 'none';

                        // Submit form for filtering
                        if (buttonId === 'category-dropdown') {
                            // If category changed, reset subject
                            document.getElementById('subject-input').value = '';
                            loadSubjects(value);
                        }

                        document.getElementById('filter-form').submit();
                    }
                });
            }

            // Initialize dropdowns
            initDropdown('category-dropdown', 'category-menu', 'category-input');
            initDropdown('subject-dropdown', 'subject-menu', 'subject-input');

            // Load subjects when category changes
            function loadSubjects(categoryId) {
                const subjectMenu = document.getElementById('subject-menu');
                const subjectButton = document.getElementById('subject-dropdown').querySelector('span');

                if (!categoryId) {
                    subjectMenu.innerHTML = '<li class="disabled">{{ __('front.select_program_first') }}</li>';
                    subjectButton.textContent = '{{ __('front.choose_subject') }}';
                    return;
                }

                // Show loading
                subjectMenu.innerHTML = '<li class="disabled">{{ __('front.loading') }}</li>';

                // Fetch subjects via AJAX
                fetch(`{{ route('boot-camp-questions.subjects-by-category') }}?category_id=${categoryId}`)
                    .then(response => response.json())
                    .then(subjects => {
                        let html = '<li data-value="">{{ __('front.all_subjects') }}</li>';
                        subjects.forEach(subject => {
                            html += `<li data-value="${subject.id}">${subject.name}</li>`;
                        });
                        subjectMenu.innerHTML = html;
                        subjectButton.textContent = '{{ __('front.choose_subject') }}';
                    })
                    .catch(error => {
                        console.error('Error loading subjects:', error);
                        subjectMenu.innerHTML = '<li class="disabled">Error loading subjects</li>';
                    });
            }

            // Handle search input
            const searchInput = document.querySelector('input[name="search"]');
            let searchTimeout;

            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    document.getElementById('filter-form').submit();
                }, 500);
            });

            // Close dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.examx-dropdown')) {
                    document.querySelectorAll('.examx-menu').forEach(menu => {
                        menu.style.display = 'none';
                    });
                }
            });
        });

        // Share functionality
        function shareFile(fileUrl, fileName) {
            if (navigator.share) {
                navigator.share({
                    title: fileName,
                    url: fileUrl
                });
            } else {
                // Fallback: copy to clipboard
                navigator.clipboard.writeText(fileUrl).then(function() {
                    alert('{{ __('Link copied to clipboard') }}');
                });
            }
        }
    </script>
@endpush
