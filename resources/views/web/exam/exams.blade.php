{{-- resources/views/web/exam/exams.blade.php --}}
@extends('layouts.app')

@section('content')
    <section class="universities-page">

        <div data-aos="flip-up" data-aos-duration="1000" class="anim animate-glow courses-header-wrapper">
            <div class="courses-header-1">
                <img src="{{ app()->getLocale() == 'ar'
                    ? asset('assets_front/images/courses-header-line-new.png')
                    : asset('assets_front/images/en/courses-header-line-new.png') }}"
                    alt="" class="courses-header-img" loading="lazy">
                <span class="grade-number">{{ mb_substr(__('front.exams'), 0, 1) }}</span>
            </div>
        </div>

        <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200" class="examx-filters">
            @include('web.alert-message')
            <form action='{{ route('exams') }}' method='get' id="filterForm">
                <div class="examx-row">
                    <div class="examx-dropdown">
                        <select class="examx-pill" name="programm_id" id="programm_id">
                            <option value="">{{ translate_lang('select_program') }}</option>
                            @foreach ($programms as $programm)
                                <option value="{{ $programm->id }}" data-ctg-key="{{ $programm->ctg_key }}"
                                    {{ request('programm_id') == $programm->id ? 'selected' : '' }}>
                                    {{ $programm->localized_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="examx-dropdown" id="gradeSection" style="">
                        <select class="examx-pill" name="grade_id" id="grade_id">
                            <option value="">{{ translate_lang('select_grade') }}</option>
                            @foreach ($grades as $grade)
                                <option value="{{ $grade->id }}"
                                    {{ request('grade_id') == $grade->id ? 'selected' : '' }}>
                                    {{ $grade->localized_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="examx-dropdown" id="subjectSection" style="">
                        <select class="examx-pill" name="subject_id" id="subject_id">
                            <option value="">{{ translate_lang('select_subject') }}</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}"
                                    {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->localized_name }}
                                    @if ($subject->semester)
                                        - {{ $subject->semester->localized_name }}
                                    @endif
                                    @if ($subject->grade && $subject->grade->level == 'international-program-child')
                                        - {{ $subject->grade->localized_name }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="examx-search">
                    <input type="text" placeholder="{{ translate_lang('search') }}" name='search'
                        value="{{ request('search') }}">
                </div>
            </form>
        </div>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @php
            // Check if current user is a student
            $user = auth('user')->user();
            $isStudent = $user && $user->role_name === 'student';
        @endphp

        @if (!$isStudent && auth('user')->check())
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                {{ __('front.exams_for_students_only') }}
            </div>
        @endif

        @if ($exams->count() > 0)
            <div class="exams-grid">
                @foreach ($exams as $exam)
                    <div class="exam-card">
                        @if ($isStudent && $exam->current_user_attempt() && !$exam->current_user_attempt()->submitted_at)
                            <div class="active-attempt-badge">{{ __('front.in_progress') }}</div>
                        @endif

                        <div class="exam-card-header">
                            <h3 class="exam-title">{{ $exam->title }}</h3>
                            <div class="exam-status-badge status-{{ $exam->isAvailable() ? 'available' : 'unavailable' }}">
                                @if ($exam->isAvailable())
                                    <i class="fas fa-check-circle"></i> {{ __('front.available') }}
                                @else
                                    <i class="fas fa-lock"></i> {{ __('front.unavailable') }}
                                @endif
                            </div>
                        </div>

                        @if ($exam->description)
                            <div class="exam-description">
                                {{ Str::limit($exam->description, 120) }}
                            </div>
                        @endif

                        <div class="exam-stats">
                            <div class="stat-item">
                                <i class="fas fa-list"></i>
                                <span>{{ $exam->questions()->count() }} {{ __('front.questions') }}</span>
                            </div>

                            <div class="stat-item">
                                <i class="fas fa-clock"></i>
                                <span>{{ $exam->duration_minutes ?? '-' }} {{ __('front.minutes') }}</span>
                            </div>

                            <div class="stat-item">
                                <i class="fas fa-star"></i>
                                <span>{{ $exam->total_grade }} {{ __('front.grade') }}</span>
                            </div>
                        </div>

                        <div class="exam-actions">
                            @if (!$isStudent)
                                <button class="btn btn-disabled" disabled>
                                    <i class="fas fa-user-lock"></i>
                                    {{ __('front.students_only') }}
                                </button>
                            @elseif($exam->isAvailable())
                                <a href="{{ route($apiRoutePrefix . 'exam', ['exam' => $exam->id, 'slug' => $exam->slug]) }}"
                                    class="btn btn-primary">
                                    <i class="fas fa-play"></i>
                                    {{ __('front.start_exam') }}
                                </a>
                            @else
                                <button class="btn btn-disabled" disabled>
                                    <i class="fas fa-lock"></i>
                                    {{ __('front.unavailable') }}
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{ $exams->links() }}
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>{{ __('front.no_exams_found') }}</h3>
                <p>{{ __('front.no_exams_description') }}</p>
            </div>
        @endif

    </section>

    <style>
        .exams-section {
            padding: 40px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .section-title {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .section-subtitle {
            font-size: 20px;
            color: #666;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: #f0f0f0;
            color: #333;
            text-decoration: none;
            border-radius: 6px;
            margin-bottom: 20px;
            transition: all 0.3s;
        }

        .back-btn:hover {
            background: #e0e0e0;
            color: #000;
        }

        .exams-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 24px;
            margin-bottom: 40px;
        }

        .exam-card {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s;
            position: relative;
            color: #333 !important;
            display: flex;
            flex-direction: column;
        }

        .exam-card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            border-color: #007bff;
        }

        .exam-card>* {
            padding: 0 20px;
        }

        .exam-card>*:first-child {
            padding-top: 20px;
        }

        .exam-card>*:last-child {
            padding-bottom: 20px;
        }

        .exam-card * {
            color: inherit !important;
        }

        .active-attempt-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #ffc107;
            color: #333;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 18px;
            font-weight: bold;
        }

        .exam-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 12px;
        }

        .exam-title {
            font-size: 26px;
            font-weight: bold;
            margin: 0;
            color: #333;
            flex: 1;
        }

        .exam-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 18px;
            font-weight: bold;
            white-space: nowrap;
        }

        .exam-status-badge.status-available {
            background: #d4edda;
            color: #155724;
        }

        .exam-status-badge.status-unavailable {
            background: #f8d7da;
            color: #721c24;
        }

        .exam-description {
            color: #666;
            font-size: 22px;
            margin-bottom: 16px;
            line-height: 1.5;
        }

        .exam-stats {
            display: flex;
            gap: 12px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #666;
        }

        .stat-item i {
            color: #007bff;
        }

        .exam-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .exam-actions .btn {
            flex: 1;
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            font-size: 20px;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.3s;
            min-width: 150px;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background: #0056b3;
        }

        .btn-disabled {
            background: #e0e0e0;
            color: #999;
            cursor: not-allowed;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state i {
            font-size: 64px;
            color: #ccc;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 24px;
            color: #333;
            margin: 0 0 10px 0;
        }

        .empty-state p {
            color: #666;
            margin: 0;
        }
    </style>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const programmSelect = document.getElementById('programm_id');
                const gradeSection = document.getElementById('gradeSection');
                const gradeSelect = document.getElementById('grade_id');
                const subjectSection = document.getElementById('subjectSection');
                const subjectSelect = document.getElementById('subject_id');
                const form = document.getElementById('filterForm');

                // Auto submit form on filter change
                [programmSelect, gradeSelect, subjectSelect].forEach(element => {
                    if (element) {
                        element.addEventListener('change', function() {
                            // Clear dependent filters
                            if (element === programmSelect) {
                                gradeSelect.value = '';
                                subjectSelect.value = '';
                            } else if (element === gradeSelect) {
                                subjectSelect.value = '';
                            }
                            form.submit();
                        });
                    }
                });

                // Handle program change for dynamic grade loading
                programmSelect.addEventListener('change', async function() {
                    const programId = this.value;
                    const selectedOption = this.selectedOptions[0];

                    if (!programId) {
                        gradeSection.style.display = 'none';
                        subjectSection.style.display = 'none';
                        return;
                    }

                    const ctgKey = selectedOption.dataset.ctgKey;

                    // Check if program needs grades
                    if (['tawjihi-and-secondary-program', 'elementary-grades-program'].includes(ctgKey)) {
                        gradeSection.style.display = 'block';
                    } else {
                        gradeSection.style.display = 'none';
                        while (gradeSelect.options.length > 1) {
                            gradeSelect.remove(1);
                        }
                    }
                });

                // Show/hide sections based on initial state
                if (programmSelect.value) {
                    const selectedOption = programmSelect.selectedOptions[0];
                    const ctgKey = selectedOption.dataset.ctgKey;

                    if (!['tawjihi-and-secondary-program', 'elementary-grades-program'].includes(ctgKey)) {
                        if (document.querySelectorAll('#subject_id option').length > 1) {
                            subjectSection.style.display = 'block';
                        }
                    }
                }

                if (gradeSelect.value || document.querySelectorAll('#grade_id option').length > 1) {
                    gradeSection.style.display = 'block';
                }

                if (document.querySelectorAll('#subject_id option').length > 1) {
                    subjectSection.style.display = 'block';
                }
            });
        </script>
    @endpush
@endsection
