@extends('layouts.app')

@section('title', 'E-Exam')

@section('content')
    <h2 style="color: #d9534f; padding: 15px; background: #f5f5f5; margin: 10px 0;">بيانات الطالب المسجل</h2>
    @dump(auth('user')->user())

    {{-- DEBUG: Session Data --}}
    <h2 style="color: #d9534f; padding: 15px; background: #f5f5f5; margin: 10px 0;">بيانات الجلسة الحالية</h2>
    @dump(session()->all())
    <section class="examx-page">

        <div data-aos="flip-up" data-aos-duration="1000" class="anim animate-glow universities-header-wrapper">
            <div class="universities-header">
                <h2>{{ translate_lang('e-exams') }}</h2>
            </div>
        </div>

        <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200" class="examx-filters">
            @include('web.alert-message')

            <form action='{{ route($apiRoutePrefix . 'exam.index') }}' method='get' id="filterForm">
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
                    <i class="fas fa-magnifying-glass"></i>
                    <input type="text" placeholder="{{ translate_lang('search') }}" name='search'
                        value="{{ request('search') }}">
                </div>
            </form>
        </div>

        <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300" class="examx-grid">
            @forelse($exams as $exam)
                <div class="examx-card"
                    style="background-image: url('{{ asset('assets_front/images/card-exam-1.png') }}')">
                    <div class="examx-content">
                        <div class="examx-line">
                            <b>{{ translate_lang('subject') }}</b>
                            {{ $exam->subject ? $exam->subject->localized_name : '-' }}
                        </div>
                        <a href="#" class="examx-link">{{ $exam->title }}</a>
                        <div class="examx-meta">
                            <div>
                                <span>{{ translate_lang('exam_duration') }}</span>
                                <strong>{{ $exam->duration_minutes }} {{ translate_lang('minute') }}</strong>
                            </div>
                            <div>
                                <span>{{ translate_lang('question_count') }}:</span>
                                <strong>{{ $exam->questions?->count() }} {{ translate_lang('question') }}</strong>
                            </div>
                        </div>
                        @if ($exam->current_user_attempt())
                            <a href="{{ route($apiRoutePrefix . 'exam', ['exam' => $exam->id, 'slug' => $exam->slug]) }}"
                                class="examx-btn">
                                {{ translate_lang('continue') }}
                            </a>
                        @elseif($exam->can_add_attempt())
                            <a href="{{ route($apiRoutePrefix . 'exam', ['exam' => $exam->id, 'slug' => $exam->slug]) }}"
                                class="examx-btn">
                                {{ translate_lang('start_exam') }}
                            </a>
                        @elseif($exam->result_attempt())
                            <a href="{{ route($apiRoutePrefix . 'exam', ['exam' => $exam->id, 'slug' => $exam->slug]) }}"
                                class="examx-btn">
                                {{ translate_lang('result') }}
                            </a>
                        @else($exam->last_submitted_attempt())
                            <a href="{{ route($apiRoutePrefix . 'review.attempt', ['exam' => $exam->id, 'attempt' => $exam->last_submitted_attempt()->id]) }}"
                                class="examx-btn">
                                {{ translate_lang('last attempt') }}
                            </a>
                        @endif

                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p>{{ translate_lang('no_exams_found') }}</p>
                </div>
            @endforelse
        </div>

        {{ $exams?->links('pagination::custom-bootstrap-5') ?? '' }}

    </section>
@endsection

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
                    // Program needs grades - grades will be loaded via form submit
                    gradeSection.style.display = 'block';
                } else {
                    // Program doesn't need grades
                    gradeSection.style.display = 'none';
                    gradeSelect.innerHTML =
                        '<option value="">{{ translate_lang('select_grade') }}</option>';
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
