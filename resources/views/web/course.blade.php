@extends('layouts.app')
@section('title', $course->title)

@section('content')
    <section class="crs2">
        <!-- Hero Section -->
        <div class="crs2-hero">
            <div class="crs2-hero-inner">
                <h1 class="crs2-hero-title">{{ $course->title }}</h1>
                <p class="crs2-hero-sub">{{ $course->description }}</p>

                <div class="crs2-hero-meta">
                    <span class="crs2-chip crs2-chip--outline">
                        {{ translate_lang('created_at') }}: {{ $course->created_at->format('l d F Y') }}
                    </span>
                    <span class="crs2-chip crs2-chip--outline">
                        {{ translate_lang('updated_at') }}: {{ $course->updated_at->format('l d F Y') }}
                    </span>
                </div>
            </div>
        </div>

        <div class="crs2-main">
            <div class="crs2-main-inner">
                <div class="crs2-main-top">
                    <!-- Side Card -->
                    <div class="crs2-side-card">
                        <div class="crs2-side-cover">
                            @if ($freeContents?->video_url && $freeContents?->is_free == 1)
                                @php
                                    // video_url accessor handles Bunny CDN URLs automatically
                                    $coverVideoSource = $freeContents->video_url;
                                @endphp
                                <div class="playable" data-video="{{ $coverVideoSource }}"
                                    data-content-id="{{ $freeContents->id }}"
                                    data-duration="{{ $freeContents->video_duration }}"
                                    data-is-bunny="{{ $freeContents->video_type === 'bunny' ? 1 : 0 }}">
                                    <img data-src="{{ $course->photo_url ?? asset('assets_front/images/ourse-cover-2.webp') }}"
                                        alt="{{ $course->title }}">
                                    <span class="play-overlay"><i class="fas fa-play"></i></span>
                                </div>
                            @else
                                <img src="{{ $course->photo_url ?? asset('assets_front/images/ourse-cover-2.webp') }}"
                                    alt="{{ $course->title }}">
                            @endif
                        </div>

                        <div class="crs2-side-body">


                            <div class="crs2-side-price-row">
                                <div class="crs2-side-price-chip">
                                    <span>{{ CURRENCY }}</span>
                                </div>
                                <div class="crs2-side-price-value">
                                    <span class="crs2-side-price-number">{{ $course->selling_price }}</span>
                                </div>
                            </div>

                            @if ($is_enrolled)
                                <button class="crs2-side-btn-enrolled" disabled>
                                    {{ translate_lang('enrolled') }}
                                </button>
                            @elseif(is_array($user_courses) && in_array($course->id, $user_courses))
                                <a href="{{ route('checkout') }}" class="crs2-side-btn-primary">
                                    {{ translate_lang('go_to_checkout') }}
                                </a>
                            @else
                                <button id='buy_now' data-course-id="{{ $course->id }}"
                                    class="enroll-btn crs2-side-btn-primary">
                                    {{ translate_lang('buy_now') }}
                                </button>
                                <button data-course-id="{{ $course->id }}" class="enroll-btn crs2-side-btn-secondary">
                                    {{ translate_lang('add_to_cart') }}
                                </button>
                            @endif

                            <!-- Teacher Info -->
                            <div class="crs2-teacher-info">
                                <img data-src="{{ $course->teacher?->photo_url }}" alt="{{ $course->teacher?->name }}">
                                <div>
                                    <h4>{{ $course->teacher?->name }}</h4>
                                    <p>{{ translate_lang('section_all_count') }}: {{ $mainSections?->count() }}</p>
                                    <p>{{ translate_lang('video_all_count') }}:
                                        {{ $contents?->where('content_type', 'video')?->count() }}</p>
                                    <p>{{ translate_lang('course_duration') }}: {{ $courseHours['formatted_duration'] }}
                                    </p>
                                    @if ($is_enrolled && $userWatchTime)
                                        <p>{{ translate_lang('watched') }}: {{ $userWatchTime['formatted_duration'] }}</p>
                                    @endif
                                </div>
                            </div>

                            <a href="{{ route('contacts') }}" class="crs2-report-link">
                                {{ translate_lang('report_this_course') }}
                            </a>
                        </div>
                    </div>

                    <!-- Preview Card -->
                    <div class="crs2-preview-card">
                        <img src="{{ $course->photo_url ?? asset('assets_front/images/ourse-cover-2.webp') }}"
                            alt="{{ $course->title }}" class="crs2-preview-image">
                    </div>
                </div>

                <!-- Summary Card -->
                <div class="crs2-summary-card">
                    <h2 class="crs2-summary-title">{{ $course->title }}</h2>
                    <p class="crs2-summary-sub">{{ $course->description }}</p>
                </div>

                <!-- Card Activation (if not enrolled) -->
                @if (!$is_enrolled && $user)
                    <div class="crs2-card-activation">
                        <h3>{{ translate_lang('card_qdemy') }}</h3>
                        <p>{{ translate_lang('enter_card_qdemy') }}</p>
                        <input class="crs2-card-input" type="text"
                            placeholder="{{ translate_lang('enter_card_here') }}">
                        <button data-course-id="{{ $course->id }}" class="crs2-card-button">
                            {{ translate_lang('activate_card') }}
                        </button>
                    </div>
                @endif

                <!-- Course Content -->
                @if ($mainSections && $mainSections->count())
                    <section class="crs2-content">
                        <div class="crs2-content-header">
                            <h2 class="crs2-content-title">
                                <span>{{ translate_lang('course_content') }}</span>
                            </h2>
                        </div>

                        <div class="crs2-sections">
                            @foreach ($mainSections as $section)
                                <div class="crs2-section">
                                    <button type="button" class="crs2-section-header">
                                        <span class="crs2-section-arrow"></span>
                                        <div class="crs2-section-main">
                                            <h3 class="crs2-section-title">{{ $section->title }}</h3>
                                            @if ($section->description)
                                                <p class="crs2-section-sub">{{ $section->description }}</p>
                                            @endif
                                        </div>
                                        <span class="crs2-section-icon">
                                            <i class="fa fa-th-large"></i>
                                        </span>
                                    </button>

                                    <div class="crs2-section-body">
                                        @php
                                            $sectionContents = $section->contents;
                                            $subSections = $section->children;
                                        @endphp

                                        {{-- Main Section Contents --}}
                                        @if ($sectionContents && $sectionContents->count())
                                            @foreach ($sectionContents as $content)
                                                @php
                                                    // video_url accessor handles Bunny CDN URLs automatically
                                                    $videoSource = $content->video_url;
                                                @endphp

                                                {{-- Video Content --}}
                                                @if ($content->video_url && $content->is_free == 1)
                                                    {{-- Free Video - Only visible if marked as free --}}
                                                    <div class="crs2-resource crs2-resource--video">
                                                        <div class="crs2-resource-main lesson-video"
                                                            data-video="{{ $videoSource }}"
                                                            data-content-id="{{ $content->id }}"
                                                            data-duration="{{ $content->video_duration }}"
                                                            data-is-bunny="{{ $content->video_type === 'bunny' ? 1 : 0 }}">
                                                            <span class="crs2-resource-icon">
                                                                <i class="fa fa-play-circle"></i>
                                                            </span>
                                                            <span class="crs2-resource-title">{{ $content->title }}
                                                                <small
                                                                    style="opacity: 0.7;">({{ gmdate('H:i:s', (int) $content->video_duration) }})</small></span>
                                                            <span
                                                                class="crs2-free-badge">{{ translate_lang('free') }}</span>
                                                        </div>
                                                        <div class="crs2-resource-actions">
                                                            <a href="javascript:void(0)"
                                                                class="crs2-pill-btn crs2-pill-btn--gray">
                                                                {{ translate_lang('watch') }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                @elseif($content->video_url && $content->is_free != 1)
                                                    {{-- Paid Video - Show mobile-only notice --}}
                                                    <div class="crs2-resource crs2-resource--mobile-notice">
                                                        <div class="crs2-resource-main">
                                                            <span class="crs2-resource-icon">
                                                                <i class="fa fa-mobile-alt"></i>
                                                            </span>
                                                            <span class="crs2-resource-title crs2-mobile-notice-text">
                                                                {{ __('front.paid_videos_mobile_only') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endif

                                                {{-- File Content --}}
                                                @if ($content->file_path)
                                                    @if ($content->is_free == 1 || ($is_enrolled && !isset($lockedContents[$content->id])))
                                                        {{-- Free File OR Enrolled & Unlocked --}}
                                                        <div class="crs2-resource crs2-resource--file">
                                                            <div class="crs2-resource-main">
                                                                <span class="crs2-resource-icon">
                                                                    <i class="fa fa-file-alt"></i>
                                                                </span>
                                                                <span
                                                                    class="crs2-resource-title">{{ $content->title }}</span>
                                                                @if ($content->is_free == 1)
                                                                    <span
                                                                        class="crs2-free-badge">{{ translate_lang('free') }}</span>
                                                                @endif
                                                            </div>
                                                            <div class="crs2-resource-actions">
                                                                <a href="{{ $content->file_path }}" target="_blank"
                                                                    class="crs2-pill-btn crs2-pill-btn--blue">
                                                                    {{ translate_lang('download') }}
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @elseif($is_enrolled && isset($lockedContents[$content->id]) && $lockedContents[$content->id]['is_locked'])
                                                        {{-- Enrolled but Locked file --}}
                                                        <div class="crs2-resource crs2-resource--locked-enrolled">
                                                            <div class="crs2-resource-main">
                                                                <span class="crs2-resource-icon">
                                                                    <i class="fa fa-lock"></i>
                                                                </span>
                                                                <div class="crs2-resource-content">
                                                                    <span
                                                                        class="crs2-resource-title">{{ $content->title }}</span>
                                                                    <span class="crs2-resource-app-message">
                                                                        {{ translate_lang('complete_lesson_first') }}:
                                                                        {{ $lockedContents[$content->id]['previous_content_title'] }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        {{-- Not enrolled - locked --}}
                                                        <div class="crs2-resource crs2-resource--locked">
                                                            <div class="crs2-resource-main">
                                                                <span class="crs2-resource-icon">
                                                                    <i class="fa fa-lock"></i>
                                                                </span>
                                                                <span
                                                                    class="crs2-resource-title">{{ $content->title }}</span>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endforeach

                                            {{-- Section Exams - VISIBLE TO ALL --}}
                                            @php $sectionsExams = $section->exams @endphp
                                            @if ($sectionsExams && $sectionsExams->count())
                                                <div class="crs2-subgroup">
                                                    <button type="button" class="crs2-sub-header">
                                                        <span
                                                            class="crs2-sub-title">{{ translate_lang('section_quiz') }}</span>
                                                        <span class="crs2-sub-toggle">{{ translate_lang('show') }}</span>
                                                    </button>
                                                    <div class="crs2-sub-body">
                                                        @foreach ($sectionsExams as $sectionsExam)
                                                            @if ($is_enrolled)
                                                                <div class="crs2-resource crs2-resource--exam">
                                                                    <div class="crs2-resource-main">
                                                                        <span class="crs2-resource-icon">
                                                                            <i class="fa fa-question-circle"></i>
                                                                        </span>
                                                                        <span
                                                                            class="crs2-resource-title">{{ $sectionsExam->title }}</span>
                                                                    </div>
                                                                    <div class="crs2-resource-actions">
                                                                        <div class="crs2-resource-meta">
                                                                            <span class="crs2-meta-chip">
                                                                                {{ translate_lang('attempts') }}:
                                                                                {{ $sectionsExam->user_attempts()->count() }}
                                                                            </span>
                                                                            @if ($sectionsExam->result_attempt())
                                                                                <span
                                                                                    class="crs2-meta-chip {{ $sectionsExam->result_attempt()->is_passed ? 'passed' : 'failed' }}">
                                                                                    <i
                                                                                        class="fa fa-{{ $sectionsExam->result_attempt()->is_passed ? 'check' : 'times' }}"></i>
                                                                                    {{ $sectionsExam->result_attempt()->percentage }}%
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                        <a href="{{ route('exam', ['exam' => $sectionsExam->id, 'slug' => $sectionsExam->slug]) }}"
                                                                            target="_blank"
                                                                            class="crs2-pill-btn crs2-pill-btn--red">
                                                                            {{ translate_lang('enter_exam') }}
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="crs2-resource crs2-resource--locked">
                                                                    <div class="crs2-resource-main">
                                                                        <span class="crs2-resource-icon">
                                                                            <i class="fa fa-lock"></i>
                                                                        </span>
                                                                        <span
                                                                            class="crs2-resource-title">{{ $sectionsExam->title }}</span>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        @endif

                                        {{-- Sub Sections - Apply same logic --}}
                                        @if ($subSections && $subSections->count())
                                            @foreach ($subSections as $subSection)
                                                <div class="crs2-subgroup">
                                                    <button type="button" class="crs2-sub-header">
                                                        <span class="crs2-sub-title">{{ $subSection->title }}</span>
                                                        <span class="crs2-sub-toggle">{{ translate_lang('show') }}</span>
                                                    </button>
                                                    <div class="crs2-sub-body">
                                                        @php $subSectionContents = $subSection->contents @endphp
                                                        @if ($subSectionContents && $subSectionContents->count())
                                                            @foreach ($subSectionContents as $subContent)
                                                                @php
                                                                    // video_url accessor handles Bunny CDN URLs automatically
                                                                    $subVideoSource = $subContent->video_url;
                                                                @endphp
                                                                {{-- Video Content in Sub Sections --}}
                                                                @if ($subContent->video_url && $subContent->is_free == 1)
                                                                    {{-- Free Video - Only visible if marked as free --}}
                                                                    <div class="crs2-resource crs2-resource--video">
                                                                        <div class="crs2-resource-main lesson-video"
                                                                            data-video="{{ $subVideoSource }}"
                                                                            data-content-id="{{ $subContent->id }}"
                                                                            data-duration="{{ $subContent->video_duration }}"
                                                                            data-is-bunny="{{ $subContent->video_type === 'bunny' ? 1 : 0 }}">
                                                                            <span class="crs2-resource-icon">
                                                                                <i class="fa fa-play-circle"></i>
                                                                            </span>
                                                                            <span
                                                                                class="crs2-resource-title">{{ $subContent->title }}
                                                                                <small
                                                                                    style="opacity: 0.7;">({{ gmdate('H:i:s', (int) $subContent->video_duration) }})</small></span>
                                                                            <span
                                                                                class="crs2-free-badge">{{ translate_lang('free') }}</span>
                                                                        </div>
                                                                        <div class="crs2-resource-actions">
                                                                            <a href="javascript:void(0)"
                                                                                class="crs2-pill-btn crs2-pill-btn--gray">
                                                                                {{ translate_lang('watch') }}
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                @elseif($subContent->video_url && $subContent->is_free != 1)
                                                                    {{-- Paid Video - Show mobile-only notice --}}
                                                                    <div
                                                                        class="crs2-resource crs2-resource--mobile-notice">
                                                                        <div class="crs2-resource-main">
                                                                            <span class="crs2-resource-icon">
                                                                                <i class="fa fa-mobile-alt"></i>
                                                                            </span>
                                                                            <span
                                                                                class="crs2-resource-title crs2-mobile-notice-text">
                                                                                {{ __('front.paid_videos_mobile_only') }}
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                @endif

                                                                {{-- File Content in Sub Sections --}}
                                                                @if ($subContent->file_path)
                                                                    @if ($subContent->is_free == 1 || ($is_enrolled && !isset($lockedContents[$subContent->id])))
                                                                        {{-- Free File OR Enrolled & Unlocked --}}
                                                                        <div class="crs2-resource crs2-resource--file">
                                                                            <div class="crs2-resource-main">
                                                                                <span class="crs2-resource-icon">
                                                                                    <i class="fa fa-file-alt"></i>
                                                                                </span>
                                                                                <span
                                                                                    class="crs2-resource-title">{{ $subContent->title }}</span>
                                                                                @if ($subContent->is_free == 1)
                                                                                    <span
                                                                                        class="crs2-free-badge">{{ translate_lang('free') }}</span>
                                                                                @endif
                                                                            </div>
                                                                            <div class="crs2-resource-actions">
                                                                                <a href="{{ $subContent->file_path }}"
                                                                                    target="_blank"
                                                                                    class="crs2-pill-btn crs2-pill-btn--blue">
                                                                                    {{ translate_lang('download') }}
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    @elseif($is_enrolled && isset($lockedContents[$subContent->id]) && $lockedContents[$subContent->id]['is_locked'])
                                                                        {{-- Enrolled but Locked --}}
                                                                        <div
                                                                            class="crs2-resource crs2-resource--locked-enrolled">
                                                                            <div class="crs2-resource-main">
                                                                                <span class="crs2-resource-icon">
                                                                                    <i class="fa fa-lock"></i>
                                                                                </span>
                                                                                <div class="crs2-resource-content">
                                                                                    <span
                                                                                        class="crs2-resource-title">{{ $subContent->title }}</span>
                                                                                    <span
                                                                                        class="crs2-resource-app-message">
                                                                                        {{ translate_lang('complete_lesson_first') }}:
                                                                                        {{ $lockedContents[$subContent->id]['previous_content_title'] }}
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @else
                                                                        {{-- Not enrolled --}}
                                                                        <div class="crs2-resource crs2-resource--locked">
                                                                            <div class="crs2-resource-main">
                                                                                <span class="crs2-resource-icon">
                                                                                    <i class="fa fa-lock"></i>
                                                                                </span>
                                                                                <span
                                                                                    class="crs2-resource-title">{{ $subContent->title }}</span>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        @endif

                                                        {{-- Sub Section Exams --}}
                                                        @php $subSectionsExams = $subSection->exams @endphp
                                                        @if ($subSectionsExams && $subSectionsExams->count())
                                                            @foreach ($subSectionsExams as $subExam)
                                                                @if ($is_enrolled)
                                                                    <div class="crs2-resource crs2-resource--exam">
                                                                        <div class="crs2-resource-main">
                                                                            <span class="crs2-resource-icon">
                                                                                <i class="fa fa-question-circle"></i>
                                                                            </span>
                                                                            <span
                                                                                class="crs2-resource-title">{{ $subExam->title }}</span>
                                                                        </div>
                                                                        <div class="crs2-resource-actions">
                                                                            <div class="crs2-resource-meta">
                                                                                <span class="crs2-meta-chip">
                                                                                    {{ translate_lang('attempts') }}:
                                                                                    {{ $subExam->user_attempts()->count() }}
                                                                                </span>
                                                                                @if ($subExam->result_attempt())
                                                                                    <span
                                                                                        class="crs2-meta-chip {{ $subExam->result_attempt()->is_passed ? 'passed' : 'failed' }}">
                                                                                        <i
                                                                                            class="fa fa-{{ $subExam->result_attempt()->is_passed ? 'check' : 'times' }}"></i>
                                                                                        {{ $subExam->result_attempt()->percentage }}%
                                                                                    </span>
                                                                                @endif
                                                                            </div>
                                                                            <a href="{{ route('exam', ['exam' => $subExam->id, 'slug' => $subExam->slug]) }}"
                                                                                target="_blank"
                                                                                class="crs2-pill-btn crs2-pill-btn--red">
                                                                                {{ translate_lang('enter_exam') }}
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <div class="crs2-resource crs2-resource--locked">
                                                                        <div class="crs2-resource-main">
                                                                            <span class="crs2-resource-icon">
                                                                                <i class="fa fa-lock"></i>
                                                                            </span>
                                                                            <span
                                                                                class="crs2-resource-title">{{ $subExam->title }}</span>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            {{-- Unassigned Contents --}}
                            @php
                                $unassignedContents = $course->contents->where('section_id', null);
                            @endphp
                            @if ($unassignedContents->count())
                                <div class="crs2-section">
                                    <button type="button" class="crs2-section-header">
                                        <span class="crs2-section-arrow"></span>
                                        <div class="crs2-section-main">
                                            <h3 class="crs2-section-title">{{ translate_lang('unassigned_contents') }}
                                            </h3>
                                        </div>
                                        <span class="crs2-section-icon">
                                            <i class="fa fa-th-large"></i>
                                        </span>
                                    </button>
                                    <div class="crs2-section-body">
                                        @foreach ($unassignedContents as $_content)
                                            @php
                                                // video_url accessor handles Bunny CDN URLs automatically
                                                $unVideoSource = $_content->video_url;
                                            @endphp

                                            {{-- Video Content --}}
                                            @if ($_content->video_url && $_content->is_free == 1)
                                                {{-- Free Video - Only visible if marked as free --}}
                                                <div class="crs2-resource crs2-resource--video">
                                                    <div class="crs2-resource-main lesson-video"
                                                        data-video="{{ $unVideoSource }}"
                                                        data-content-id="{{ $_content->id }}"
                                                        data-duration="{{ $_content->video_duration }}"
                                                        data-is-bunny="{{ $_content->video_type === 'bunny' ? 1 : 0 }}">
                                                        <span class="crs2-resource-icon">
                                                            <i class="fa fa-play-circle"></i>
                                                        </span>
                                                        <span class="crs2-resource-title">{{ $_content->title }}
                                                            <small
                                                                style="opacity: 0.7;">({{ gmdate('H:i:s', (int) $_content->video_duration) }})</small></span>
                                                        <span class="crs2-free-badge">{{ translate_lang('free') }}</span>
                                                    </div>
                                                    <div class="crs2-resource-actions">
                                                        <a href="javascript:void(0)"
                                                            class="crs2-pill-btn crs2-pill-btn--gray">
                                                            {{ translate_lang('watch') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            @elseif($_content->video_url && $_content->is_free != 1)
                                                {{-- Paid Video - Show mobile-only notice --}}
                                                <div class="crs2-resource crs2-resource--mobile-notice">
                                                    <div class="crs2-resource-main">
                                                        <span class="crs2-resource-icon">
                                                            <i class="fa fa-mobile-alt"></i>
                                                        </span>
                                                        <span class="crs2-resource-title crs2-mobile-notice-text">
                                                            {{ __('front.paid_videos_mobile_only') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- File Content --}}
                                            @if ($_content->file_path)
                                                @if ($_content->is_free == 1)
                                                    {{-- Free File - ALWAYS Show --}}
                                                    <div class="crs2-resource crs2-resource--file">
                                                        <div class="crs2-resource-main">
                                                            <span class="crs2-resource-icon">
                                                                <i class="fa fa-file-alt"></i>
                                                            </span>
                                                            <span
                                                                class="crs2-resource-title">{{ $_content->title }}</span>
                                                            <span
                                                                class="crs2-free-badge">{{ translate_lang('free') }}</span>
                                                        </div>
                                                        <div class="crs2-resource-actions">
                                                            <a href="{{ $_content->file_path }}" target="_blank"
                                                                class="crs2-pill-btn crs2-pill-btn--blue">
                                                                {{ translate_lang('download') }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                @elseif($is_enrolled)
                                                    @if (isset($lockedContents[$_content->id]) && $lockedContents[$_content->id]['is_locked'])
                                                        {{-- Locked file --}}
                                                        <div class="crs2-resource crs2-resource--locked-enrolled">
                                                            <div class="crs2-resource-main">
                                                                <span class="crs2-resource-icon">
                                                                    <i class="fa fa-lock"></i>
                                                                </span>
                                                                <div class="crs2-resource-content">
                                                                    <span
                                                                        class="crs2-resource-title">{{ $_content->title }}</span>
                                                                    <span class="crs2-resource-app-message">
                                                                        {{ translate_lang('complete_lesson_first') }}:
                                                                        {{ $lockedContents[$_content->id]['previous_content_title'] }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        {{-- Unlocked file --}}
                                                        <div class="crs2-resource crs2-resource--file">
                                                            <div class="crs2-resource-main">
                                                                <span class="crs2-resource-icon">
                                                                    <i class="fa fa-file-alt"></i>
                                                                </span>
                                                                <span
                                                                    class="crs2-resource-title">{{ $_content->title }}</span>
                                                            </div>
                                                            <div class="crs2-resource-actions">
                                                                <a href="{{ $_content->file_path }}" target="_blank"
                                                                    class="crs2-pill-btn crs2-pill-btn--blue">
                                                                    {{ translate_lang('download') }}
                                                                </a>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @else
                                                    {{-- Not enrolled - locked --}}
                                                    <div class="crs2-resource crs2-resource--locked">
                                                        <div class="crs2-resource-main">
                                                            <span class="crs2-resource-icon">
                                                                <i class="fa fa-lock"></i>
                                                            </span>
                                                            <span
                                                                class="crs2-resource-title">{{ $_content->title }}</span>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- All Course Exams --}}
                            {{-- All Course Exams --}}
                            @if ($exams && $exams->count())
                                <div class="crs2-section">
                                    <button type="button" class="crs2-section-header">
                                        <span class="crs2-section-arrow"></span>
                                        <div class="crs2-section-main">
                                            <h3 class="crs2-section-title">{{ translate_lang('all_course_quiz') }}</h3>
                                        </div>
                                        <span class="crs2-section-icon">
                                            <i class="fa fa-th-large"></i>
                                        </span>
                                    </button>
                                    <div class="crs2-section-body">
                                        @foreach ($exams as $exam)
                                            @if ($is_enrolled)
                                                {{-- Enrolled - Show Clickable Exam --}}
                                                <div class="crs2-resource crs2-resource--exam">
                                                    <div class="crs2-resource-main">
                                                        <span class="crs2-resource-icon">
                                                            <i class="fa fa-question-circle"></i>
                                                        </span>
                                                        <span class="crs2-resource-title">{{ $exam->title }}</span>
                                                    </div>
                                                    <div class="crs2-resource-actions">
                                                        <div class="crs2-resource-meta">
                                                            <span class="crs2-meta-chip">
                                                                {{ translate_lang('attempts') }}:
                                                                {{ $exam->user_attempts()->count() }}
                                                            </span>
                                                            @if ($exam->result_attempt())
                                                                <span
                                                                    class="crs2-meta-chip {{ $exam->result_attempt()->is_passed ? 'passed' : 'failed' }}">
                                                                    <i
                                                                        class="fa fa-{{ $exam->result_attempt()->is_passed ? 'check' : 'times' }}"></i>
                                                                    {{ $exam->result_attempt()->percentage }}%
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <a href="{{ route('exam', ['exam' => $exam->id, 'slug' => $exam->slug]) }}"
                                                            target="_blank" class="crs2-pill-btn crs2-pill-btn--red">
                                                            {{ translate_lang('enter_exam') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            @else
                                                {{-- Not Enrolled - Show Lock --}}
                                                <div class="crs2-resource crs2-resource--locked">
                                                    <div class="crs2-resource-main">
                                                        <span class="crs2-resource-icon">
                                                            <i class="fa fa-lock"></i>
                                                        </span>
                                                        <span class="crs2-resource-title">{{ $exam->title }}</span>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </section>
                @endif
                <!-- Video Popup -->
                <div class="video-popup">
                    <div class="video-popup-content">
                        <span class="close-popup">&times;</span>
                        <iframe src="" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>

                <!-- Enrollment Modal -->
                <div id="enrollment-modal" class="messages modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <h3><i class="fa fa-check"></i></h3>
                        <h3>{{ translate_lang('course_added') }}</h3>
                        <p>{{ translate_lang('course_added_successfully') }}</p>
                        <div class="modal-buttons">
                            <button id="continue-shopping">{{ translate_lang('continue_shopping') }}</button>
                            <button id="go-to-checkout">{{ translate_lang('go_to_checkout') }}</button>
                        </div>
                    </div>
                </div>
            @endsection

            @push('scripts')
                <script>
                    let user = "{{ $user?->id }}";
                    let isEnrolled = {{ $is_enrolled ? 'true' : 'false' }};
                </script>
                <script>
                    // Card activation
                    document.addEventListener('DOMContentLoaded', function() {
                        const activateButton = document.querySelector('.crs2-card-button');
                        const cardInput = document.querySelector('.crs2-card-input');
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                        if (activateButton) {
                            activateButton.addEventListener('click', function(e) {
                                e.preventDefault();
                                const cardNumber = cardInput.value.trim();
                                const courseId = this.getAttribute('data-course-id');

                                if (!user) {
                                    alert("{{ translate_lang('please_login_first') }}");
                                    return;
                                } else if (!cardNumber) {
                                    alert('{{ translate_lang('please_enter_card_number') }}');
                                    return;
                                }

                                activateButton.innerHTML = '{{ translate_lang('activating') }}...';
                                activateButton.disabled = true;

                                fetch('{{ route('activate.card') }}', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': csrfToken,
                                            'Accept': 'application/json'
                                        },
                                        body: JSON.stringify({
                                            card_number: cardNumber,
                                            course_id: courseId
                                        })
                                    })
                                    .then(response => {
                                        activateButton.innerHTML = '{{ translate_lang('activate_card') }}';
                                        activateButton.disabled = false;

                                        if (!response.ok) {
                                            throw new Error('Network response was not ok');
                                        }
                                        return response.json();
                                    })
                                    .then(data => {
                                        if (data.success) {
                                            alert('{{ translate_lang('card_activated_successfully') }}');
                                            location.reload();
                                        } else {
                                            alert('{{ translate_lang('activation_error') }}: ' + (data.message ||
                                                'Unknown error'));
                                        }
                                    })
                                    .catch(error => {
                                        console.log('Error:', error);
                                        alert('{{ translate_lang('connection_error') }}');
                                    });
                            });
                        }
                    });
                </script>
                <script>
                    // Enrollment
                    document.addEventListener('DOMContentLoaded', function() {
                        const modal = document.getElementById('enrollment-modal');
                        const closeBtn = document.querySelector('.close');
                        const continueBtn = document.getElementById('continue-shopping');
                        const checkoutBtn = document.getElementById('go-to-checkout');
                        const enrollButtons = document.querySelectorAll('.enroll-btn');
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                        enrollButtons.forEach(button => {
                            button.addEventListener('click', function(e) {
                                e.preventDefault();

                                if (!user) {
                                    alert("{{ translate_lang('login_required') }}");
                                    return;
                                }

                                const courseId = this.getAttribute('data-course-id');
                                const buttonId = this.getAttribute('id');
                                const buttonInnerHTML = this.innerHTML;

                                this.innerHTML = '{{ translate_lang('loading') }}';
                                this.disabled = true;

                                fetch('{{ route('add.to.session') }}', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': csrfToken,
                                            'Accept': 'application/json'
                                        },
                                        body: JSON.stringify({
                                            course_id: courseId
                                        })
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (buttonId && buttonId == 'buy_now') {
                                            window.location.href = '{{ route('checkout') }}';
                                        }
                                        if (data.success) {
                                            modal.style.display = 'flex';
                                            this.innerHTML = "{{ translate_lang('go_to_checkout') }}";
                                            this.disabled = false;
                                        } else {
                                            alert('{{ translate_lang('error_adding_course') }}: ' + (data
                                                .message || 'Unknown error'));
                                        }
                                    })
                                    .catch(error => {
                                        this.innerHTML = buttonInnerHTML;
                                    });
                            });
                        });

                        if (closeBtn) {
                            closeBtn.addEventListener('click', function() {
                                modal.style.display = 'none';
                            });
                        }

                        if (continueBtn) {
                            continueBtn.addEventListener('click', function() {
                                modal.style.display = 'none';
                            });
                        }

                        if (checkoutBtn) {
                            checkoutBtn.addEventListener('click', function() {
                                window.location.href = '{{ route('checkout') }}';
                            });
                        }

                        window.addEventListener('click', function(event) {
                            if (event.target === modal) {
                                modal.style.display = 'none';
                            }
                        });

                        // Accordion functionality
                        document.addEventListener('click', function(e) {
                            if (e.target.classList.contains('accordion-header')) {
                                e.target.classList.toggle('active');
                                const body = e.target.nextElementSibling;
                                body.classList.toggle('active');
                            }
                        });
                    });
                </script>

                <!-- Video Progress Tracking -->
                <script>
                    let currentVideoId = null;
                    let videoStartTime = 0;
                    let progressUpdateInterval = null;
                    let currentVideoDuration = 0;
                    let lastWatchedTime = 0;
                    let stopProgress = 0;
                    const COMPLETED_MINUTE = {{ env('COMPLETED_WATCHING_COURSES', 5) }};

                    function fixYouTubeUrl(url) {
                        let start = (lastWatchedTime <= currentVideoDuration) ? lastWatchedTime : 0;

                        // Check if it's a Bunny CDN video (mp4 file)
                        if (url.includes('.mp4') || url.includes('/assets/admin/uploads/')) {
                            return null; // Will be handled with HTML5 video
                        }

                        // YouTube URL handling
                        if (url.includes('youtube.com/watch?v=')) {
                            let videoId = url.split('v=')[1].split('&')[0];
                            return `https://www.youtube.com/embed/${videoId}?rel=0&modestbranding=1&start=${start}`;
                        } else if (url.includes('youtu.be/')) {
                            let videoId = url.split('youtu.be/')[1].split('?')[0];
                            return `https://www.youtube.com/embed/${videoId}?rel=0&modestbranding=1&start=${start}`;
                        } else if (url.includes('youtube.com/embed/')) {
                            return url + (url.includes('?') ? '&' : '?') + `rel=0&modestbranding=1&start=${start}`;
                        }
                        return url;
                    }

                    function startProgressTracking() {
                        if (progressUpdateInterval) {
                            clearInterval(progressUpdateInterval);
                        }

                        progressUpdateInterval = setInterval(function() {
                            updateVideoProgress();
                        }, 3000);
                    }

                    function stopProgressProgressTracking() {
                        if (progressUpdateInterval) {
                            clearInterval(progressUpdateInterval);
                            progressUpdateInterval = null;

                            if (currentVideoId && !stopProgress) {
                                updateVideoProgress(true);
                            }
                        }
                    }

                    function updateVideoProgress(isFinalUpdate = false) {
                        if (!currentVideoId || stopProgress) return;

                        let currentTime = Date.now();
                        let watchedSeconds = Math.floor((currentTime - videoStartTime) / 1000) + lastWatchedTime;

                        let shouldComplete = false;
                        if (currentVideoDuration > 0) {
                            let remainingMinutes = (currentVideoDuration - watchedSeconds) / 60;
                            shouldComplete = remainingMinutes <= COMPLETED_MINUTE;
                        }

                        fetch("{{ route('video.progress.update') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    content_id: currentVideoId,
                                    watch_time: watchedSeconds,
                                    completed: shouldComplete ? 1 : 0,
                                    is_final_update: isFinalUpdate
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    updateProgressDisplay(data.progress);
                                    if (data.completed) {
                                        updateVideoCompletionStatus(currentVideoId, true);
                                    }
                                }
                            })
                            .catch(error => console.error('Error:', error));
                    }

                    function updateVideoCompletionStatus(contentId, completed) {
                        const videoElement = document.querySelector(`.lesson-video[data-content-id="${contentId}"]`);

                        if (completed && videoElement) {
                            const progressBadge = videoElement.querySelector('.progress-badge');
                            if (progressBadge) {
                                progressBadge.remove();
                            }

                            if (!videoElement.querySelector('.completion-badge')) {
                                const completionBadge = document.createElement('span');
                                completionBadge.className = 'completion-badge';
                                completionBadge.textContent = '✓';
                                videoElement.appendChild(completionBadge);
                            }

                            const progressFill = videoElement.closest('.crs2-resource').querySelector('.crs2-progress-fill');
                            if (progressFill) {
                                progressFill.style.width = '100%';
                            }
                        }
                    }

                    function updateProgressDisplay(progressData) {
                        if (progressData) {
                            const progressBar = document.querySelector('.crs2-progress-bar');
                            const progressText = document.querySelector('.crs2-progress-text');
                            const progressStats = document.querySelector('.crs2-progress-stats');

                            if (progressBar) {
                                progressBar.style.width = progressData.course_progress + '%';
                            }

                            if (progressText) {
                                progressText.textContent = Math.round(progressData.course_progress) + '% ' +
                                    "{{ translate_lang('completed') }}";
                            }

                            if (progressStats) {
                                progressStats.innerHTML = `
                <span>{{ translate_lang('completed') }}: ${progressData.completed_videos}</span>
                <span>{{ translate_lang('watching') }}: ${progressData.watching_videos}</span>
                <span>{{ translate_lang('total_videos') }}: ${progressData.total_videos}</span>
            `;
                            }
                        }
                    }

                    document.addEventListener('DOMContentLoaded', function() {
                        // Handle video click
                        document.addEventListener('click', function(e) {
                            // Check if clicked element or its parent has the video data
                            const element = e.target.closest('.lesson-video, .playable');

                            if (element) {
                                e.preventDefault(); // Prevent default action

                                let videoUrl = element.getAttribute('data-video');
                                let contentId = element.getAttribute('data-content-id');
                                let isBunny = element.getAttribute('data-is-bunny') === '1';

                                console.log('Video clicked:', {
                                    videoUrl,
                                    contentId,
                                    isBunny,
                                    videoUrlLength: videoUrl ? videoUrl.length : 0
                                }); // Debug log

                                // Validate videoUrl
                                if (!videoUrl || videoUrl.trim() === '' || videoUrl === window.location.href) {
                                    console.error('Invalid video URL:', videoUrl);
                                    alert('Error: Video URL is missing or invalid. Please contact administrator.');
                                    return;
                                }

                                if (videoUrl && contentId) {
                                    const popup = document.querySelector('.video-popup');
                                    const iframe = popup.querySelector('iframe');
                                    const videoContainer = popup.querySelector('.video-popup-content');

                                    if (isBunny) {
                                        // Create HTML5 video player for Bunny
                                        console.log('Creating Bunny video player with URL:', videoUrl);
                                        iframe.style.display = 'none';
                                        let existingVideo = videoContainer.querySelector('video');
                                        if (existingVideo) existingVideo.remove();

                                        const video = document.createElement('video');
                                        video.controls = true;
                                        video.autoplay = true;
                                        video.style.width = '100%';
                                        video.style.height = '70vh';

                                        const source = document.createElement('source');
                                        source.src = videoUrl;
                                        source.type = 'video/mp4';

                                        video.appendChild(source);
                                        videoContainer.appendChild(video);

                                        // Log video events for debugging
                                        video.addEventListener('loadeddata', function() {
                                            console.log('Video loaded successfully');
                                        });
                                        video.addEventListener('error', function(e) {
                                            console.error('Video error:', e);
                                            console.error('Video URL that failed:', videoUrl);
                                            console.error('Video error code:', video.error ? video.error.code :
                                                'unknown');
                                        });
                                    } else {
                                        // YouTube video
                                        iframe.style.display = 'block';
                                        let existingVideo = videoContainer.querySelector('video');
                                        if (existingVideo) existingVideo.remove();

                                        videoUrl = fixYouTubeUrl(videoUrl);
                                        iframe.src = videoUrl;
                                    }

                                    popup.classList.add('active');
                                }
                            }
                        });

                        // Close popup handlers
                        const popup = document.querySelector('.video-popup');
                        const closeButton = document.querySelector('.close-popup');

                        if (closeButton) {
                            closeButton.addEventListener('click', function(e) {
                                e.stopPropagation();
                                popup.classList.remove('active');
                                const iframe = popup.querySelector('iframe');
                                const video = popup.querySelector('video');

                                if (iframe) iframe.src = '';
                                if (video) video.remove();
                            });
                        }

                        if (popup) {
                            popup.addEventListener('click', function(event) {
                                if (event.target === popup) {
                                    popup.classList.remove('active');
                                    const iframe = popup.querySelector('iframe');
                                    const video = popup.querySelector('video');

                                    if (iframe) iframe.src = '';
                                    if (video) video.remove();
                                }
                            });
                        }
                    });
                </script>
            @endpush


            @push('styles')
                <style>
                    /* Keep all the existing crs2 styles and add these additional styles */
                    .crs2 {
                        direction: rtl;
                        background: #f3f4f6;
                        padding-bottom: 60px
                    }

                    .crs2-hero {
                        background-color: #2175ff;
                        background-image: url('{{ asset('assets_front/images/course-pattern.png') }}');
                        background-size: 120px 120px;
                        background-repeat: repeat;
                        color: #fff;
                        padding: 70px 0 80px
                    }

                    .crs2-hero-inner {
                        max-width: 1200px;
                        margin: 0 auto;
                        padding: 0 40px
                    }

                    .crs2-hero-title {
                        font-size: 84px;
                        font-weight: 800;
                        margin: 0 0 10px
                    }

                    .crs2-hero-sub {
                        font-size: 21px;
                        margin: 0 0 18px
                    }

                    .crs2-hero-text p {
                        margin: 0 0 4px;
                        font-size: 19px;
                        line-height: 1.9
                    }

                    .crs2-hero-meta {
                        margin-top: 26px;
                        display: flex;
                        gap: 12px;
                        justify-content: flex-end;
                        flex-wrap: wrap
                    }

                    .crs2-chip {
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        border-radius: 999px;
                        padding: 6px 16px;
                        font-size: 17px
                    }

                    .crs2-chip--outline {
                        background: rgba(255, 255, 255, .16);
                        border: 1px solid rgba(255, 255, 255, .6)
                    }

                    .crs2-main {
                        margin-top: -50px;
                        position: relative;
                        z-index: 2
                    }

                    .crs2-main-inner {
                        max-width: 1200px;
                        margin: 0 auto;
                        padding: 0 20px
                    }

                    .crs2-main-top {
                        display: flex;
                        gap: 24px;
                        align-items: flex-start;
                        margin-bottom: 26px
                    }

                    .crs2-side-card {
                        width: 360px;
                        max-width: 100%;
                        background: #fff;
                        border-radius: 18px;
                        overflow: hidden;
                        box-shadow: 0 18px 45px rgba(15, 23, 42, .16)
                    }

                    .crs2-side-cover img {
                        display: block;
                        width: 100%;
                        height: auto
                    }

                    .crs2-side-body {
                        padding: 16px 18px 20px;
                        background: #f3f4f6
                    }

                    .crs2-side-note {
                        background: #4b5563;
                        color: #fff;
                        border-radius: 6px;
                        padding: 9px 10px;
                        text-align: center;
                        font-size: 19px;
                        margin-bottom: 14px
                    }

                    .crs2-side-price-row {
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        margin-bottom: 14px
                    }

                    .crs2-side-price-chip {
                        background: #ffd54a;
                        border-radius: 999px;
                        padding: 4px 12px;
                        font-size: 18px;
                        color: #5b3b00
                    }

                    .crs2-side-price-value {
                        display: flex;
                        align-items: center;
                        gap: 6px
                    }

                    .crs2-side-price-number {
                        font-size: 25px;
                        font-weight: 800;
                        color: #0055d2
                    }

                    .crs2-side-btn-primary {
                        width: 100%;
                        border-radius: 8px;
                        background: #2174ff;
                        color: #fff;
                        border: none;
                        padding: 11px 0;
                        font-size: 20px;
                        font-weight: 700;
                        cursor: pointer
                    }

                    .crs2-preview-card {
                        flex: 1;
                        background: #fff;
                        border-radius: 18px;
                        box-shadow: 0 18px 45px rgba(15, 23, 42, .12);
                        padding: 16px
                    }

                    .crs2-preview-image {
                        display: block;
                        width: 100%;
                        height: auto;
                        border-radius: 14px
                    }

                    .crs2-summary-card {
                        background: #fff;
                        border-radius: 18px;
                        box-shadow: 0 14px 35px rgba(15, 23, 42, .08);
                        padding: 24px 32px;
                        margin-bottom: 40px
                    }

                    .crs2-summary-title {
                        margin: 0 0 6px;
                        font-size: 22px;
                        font-weight: 800;
                        color: #111827;
                        text-align: right
                    }

                    .crs2-summary-sub {
                        margin: 0 0 14px;
                        font-size: 19px;
                        color: #6b7280;
                        text-align: right
                    }

                    .crs2-summary-text p {
                        margin: 0 0 4px;
                        font-size: 19px;
                        color: #4b5563;
                        line-height: 1.9;
                        text-align: right
                    }

                    .crs2-content {
                        margin-top: 10px
                    }

                    .crs2-content-header {
                        text-align: right;
                        margin-bottom: 18px
                    }

                    .crs2-content-title {
                        font-size: 26px;
                        font-weight: 800;
                        margin: 0;
                        color: #111827
                    }

                    .crs2-content-title span {
                        border-bottom: 4px solid #00a3ff;
                        padding-bottom: 4px
                    }

                    .crs2-sections {
                        display: flex;
                        flex-direction: column;
                        gap: 18px
                    }

                    .crs2-section {
                        background: #fff;
                        border-radius: 18px;
                        box-shadow: 0 12px 30px rgba(15, 23, 42, .09);
                        overflow: hidden
                    }

                    .crs2-section-header {
                        width: 100%;
                        display: flex;
                        align-items: center;
                        gap: 16px;
                        padding: 16px 22px;
                        background: #f7f7f9;
                        border: none;
                        cursor: pointer
                    }

                    .crs2-section-main {
                        flex: 1;
                        text-align: right
                    }

                    .crs2-section-title {
                        margin: 0 0 2px;
                        font-size: 22px;
                        font-weight: 800;
                        color: #111827
                    }

                    .crs2-section-sub {
                        margin: 0;
                        font-size: 18px;
                        color: #6b7280
                    }

                    .crs2-section-icon {
                        font-size: 21px;
                        color: #ff4b5c
                    }

                    .crs2-section-arrow {
                        display: inline-block;
                        width: 18px;
                        height: 18px;
                        border-radius: 999px;
                        border: 2px solid #111827;
                        position: relative
                    }

                    .crs2-section-arrow::before {
                        content: "";
                        position: absolute;
                        top: 2px;
                        left: 4px;
                        width: 7px;
                        height: 8px;
                        border-right: 2px solid #111827;
                        border-bottom: 2px solid #111827;
                        transform: rotate(45deg)
                    }

                    .crs2-section-body {
                        padding: 14px 18px 18px;
                        border-top: 1px solid #eceff4;
                        display: none;
                    }

                    .crs2-section--open .crs2-section-body {
                        display: block;
                    }

                    .crs2-section--open .crs2-section-header {
                        background: #ffe5ea
                    }

                    .crs2-section--open .crs2-section-arrow {
                        transform: rotate(180deg)
                    }

                    .crs2-subgroup {
                        margin-bottom: 12px;
                        border-radius: 12px;
                        overflow: hidden
                    }

                    .crs2-subgroup:last-child {
                        margin-bottom: 0
                    }

                    .crs2-sub-header {
                        width: 100%;
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        padding: 12px 14px;
                        background: #fffbea;
                        border: none;
                        cursor: pointer;
                        font-size: 19px
                    }

                    .crs2-sub-title {
                        font-weight: 700;
                        color: #b91c1c
                    }

                    .crs2-sub-toggle {
                        font-size: 18px;
                        color: #4b5563
                    }

                    .crs2-sub-body {
                        background: #fff;
                        border-top: 1px solid #f1f5f9;
                        padding: 10px 14px;
                        border-inline-start: 3px solid #facc15
                    }

                    .crs2-subgroup:not(.crs2-subgroup--open) .crs2-sub-body {
                        display: none
                    }

                    .crs2-resource {
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        gap: 12px;
                        padding: 9px 0;
                        border-bottom: 1px solid #f3f4f6
                    }

                    .crs2-resource:last-child {
                        border-bottom: none
                    }

                    .crs2-resource-main {
                        display: flex;
                        align-items: center;
                        gap: 10px;
                        flex: 1
                    }

                    .crs2-resource-icon {
                        width: 28px;
                        height: 28px;
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        background: #e5f0ff;
                        color: #0055d2;
                        font-size: 19px
                    }

                    .crs2-resource-title {
                        font-size: 19px;
                        color: #111827
                    }

                    .crs2-resource-actions {
                        display: flex;
                        align-items: center;
                        gap: 10px
                    }

                    .crs2-resource-meta {
                        display: flex;
                        gap: 6px;
                        font-size: 17px;
                        color: #6b7280
                    }

                    .crs2-meta-chip {
                        padding: 3px 8px;
                        border-radius: 999px;
                        background: #e5f3ff
                    }

                    .crs2-pill-btn {
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        border-radius: 999px;
                        padding: 6px 14px;
                        font-size: 18px;
                        font-weight: 700;
                        border: none;
                        text-decoration: none;
                        cursor: pointer
                    }

                    .crs2-pill-btn--blue {
                        background: #1d4ed8;
                        color: #fff
                    }

                    .crs2-pill-btn--gray {
                        background: #e5e7eb;
                        color: #111827;
                        pointer-events: none;
                    }

                    .crs2-pill-btn--red {
                        background: #ef4444;
                        color: #fff
                    }

                    .crs2-pill-btn--orange {
                        background: #f97316;
                        color: #fff
                    }

                    .crs2-resource--exam .crs2-resource-icon {
                        background: #fee2e2;
                        color: #b91c1c
                    }

                    .crs2-resource--task .crs2-resource-icon {
                        background: #ffedd5;
                        color: #c2410c
                    }

                    .crs2-resource--video .crs2-resource-icon {
                        background: #dbeafe;
                        color: #1d4ed8
                    }

                    @media(max-width:1024px) {
                        .crs2-hero-inner {
                            padding: 0 20px
                        }

                        .crs2-main-top {
                            flex-direction: column
                        }

                        .crs2-side-card {
                            width: 100%
                        }
                    }

                    @media(max-width:768px) {
                        .crs2-hero {
                            padding: 50px 0 60px
                        }

                        .crs2-hero-title {
                            font-size: 26px
                        }

                        .crs2-main-inner {
                            padding: 0 12px
                        }

                        .crs2-summary-card {
                            padding: 20px
                        }

                        .crs2-content-title {
                            font-size: 22px
                        }
                    }

                    .crs2-side-btn-enrolled {
                        width: 100%;
                        border-radius: 8px;
                        background: #6b7280;
                        color: #fff;
                        border: none;
                        padding: 11px 0;
                        font-size: 19px;
                        font-weight: 700;
                        cursor: not-allowed;
                        opacity: 0.7;
                    }

                    .crs2-side-btn-secondary {
                        width: 100%;
                        border-radius: 8px;
                        background: #10b981;
                        color: #fff;
                        border: none;
                        padding: 11px 0;
                        font-size: 19px;
                        font-weight: 700;
                        cursor: pointer;
                        margin-top: 10px;
                    }

                    .crs2-teacher-info {
                        display: flex;
                        align-items: center;
                        gap: 12px;
                        padding: 16px 0;
                        border-top: 1px solid #e5e7eb;
                        margin-top: 16px;
                    }

                    .crs2-teacher-info img {
                        width: 50px;
                        height: 50px;
                        border-radius: 50%;
                        object-fit: cover;
                    }

                    .crs2-teacher-info h4 {
                        margin: 0 0 4px;
                        font-size: 19px;
                        font-weight: 700;
                        color: #111827;
                    }

                    .crs2-teacher-info p {
                        margin: 0;
                        font-size: 18px;
                        color: #6b7280;
                    }

                    .crs2-report-link {
                        display: block;
                        text-align: center;
                        padding: 8px 0;
                        font-size: 18px;
                        color: #ef4444;
                        text-decoration: none;
                        margin-top: 12px;
                    }

                    .crs2-progress-info {
                        margin-top: 15px;
                        padding: 15px;
                        background: #f8f9fa;
                        border-radius: 8px;
                    }

                    .crs2-progress-bar-container {
                        position: relative;
                        width: 100%;
                        height: 20px;
                        background: #e9ecef;
                        border-radius: 10px;
                        overflow: hidden;
                        margin-bottom: 10px;
                    }

                    .crs2-progress-bar {
                        height: 100%;
                        background: linear-gradient(90deg, #28a745, #20c997);
                        transition: width 0.3s ease;
                    }

                    .crs2-progress-text {
                        position: absolute;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        font-size: 17px;
                        font-weight: bold;
                        color: #333;
                    }

                    .crs2-progress-stats {
                        display: flex;
                        justify-content: space-between;
                        font-size: 18px;
                        color: #666;
                    }

                    .crs2-card-activation {
                        background: #fff;
                        border-radius: 18px;
                        box-shadow: 0 14px 35px rgba(15, 23, 42, .08);
                        padding: 24px;
                        margin-bottom: 24px;
                    }

                    .crs2-card-activation h3 {
                        margin: 0 0 8px;
                        font-size: 22px;
                        font-weight: 700;
                        color: #111827;
                    }

                    .crs2-card-activation p {
                        margin: 0 0 16px;
                        font-size: 19px;
                        color: #6b7280;
                    }

                    .crs2-card-input {
                        width: 100%;
                        padding: 12px;
                        border: 1px solid #d1d5db;
                        border-radius: 8px;
                        font-size: 19px;
                        margin-bottom: 12px;
                    }

                    .crs2-card-button {
                        width: 100%;
                        padding: 12px;
                        background: #2174ff;
                        color: #fff;
                        border: none;
                        border-radius: 8px;
                        font-size: 20px;
                        font-weight: 700;
                        cursor: pointer;
                    }

                    .crs2-enrolled-message {
                        background: #fff;
                        border-radius: 18px;
                        box-shadow: 0 14px 35px rgba(15, 23, 42, .08);
                        padding: 40px;
                        text-align: center;
                        margin-bottom: 24px;
                    }

                    .crs2-enrolled-message h3 {
                        margin: 0;
                        font-size: 20px;
                        font-weight: 700;
                        color: #111827;
                    }

                    .crs2-free-badge {
                        display: inline-block;
                        background: #10b981;
                        color: #fff;
                        padding: 2px 8px;
                        border-radius: 4px;
                        font-size: 17px;
                        font-weight: 700;
                        margin-left: 8px;
                        text-transform: uppercase;
                    }

                    .crs2-resource--locked .crs2-resource-icon {
                        background: #f3f4f6;
                        color: #9ca3af;
                    }

                    .crs2-resource--locked .crs2-resource-title {
                        color: #9ca3af;
                    }

                    .crs2-resource-content {
                        display: flex;
                        flex-direction: column;
                        gap: 4px;
                        flex: 1;
                    }

                    .crs2-resource-locked-message {
                        font-size: 18px;
                        color: #ef4444;
                        font-weight: 600;
                    }

                    .completion-badge {
                        position: absolute;
                        top: 5px;
                        right: 5px;
                        background: #28a745;
                        color: white;
                        border-radius: 50%;
                        width: 20px;
                        height: 20px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 17px;
                    }

                    .progress-badge {
                        position: absolute;
                        top: 5px;
                        right: 5px;
                        background: #ffc107;
                        color: #333;
                        border-radius: 10px;
                        padding: 2px 6px;
                        font-size: 15px;
                        font-weight: bold;
                    }

                    .crs2-video-progress-bar {
                        width: 100%;
                        height: 3px;
                        background: #e9ecef;
                        border-radius: 2px;
                        overflow: hidden;
                        margin-top: 5px;
                    }

                    .crs2-progress-fill {
                        height: 100%;
                        background: #007bff;
                        transition: width 0.3s ease;
                    }

                    .crs2-meta-chip.passed {
                        background: #d1fae5;
                        color: #065f46;
                    }

                    .crs2-meta-chip.failed {
                        background: #fee2e2;
                        color: #991b1b;
                    }

                    /* Video Popup */
                    .video-popup {
                        display: none;
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: rgba(0, 0, 0, 0.9);
                        z-index: 9999;
                        align-items: center;
                        justify-content: center;
                    }

                    .video-popup.active {
                        display: flex;
                    }

                    .video-popup-content {
                        position: relative;
                        width: 90%;
                        max-width: 1200px;
                        background: #000;
                        border-radius: 8px;
                        overflow: hidden;
                    }

                    .close-popup {
                        position: absolute;
                        top: 10px;
                        right: 20px;
                        font-size: 30px;
                        color: #fff;
                        cursor: pointer;
                        z-index: 10000;
                    }

                    .video-popup iframe {
                        width: 100%;
                        height: 70vh;
                    }

                    /* Modal */
                    .messages.modal {
                        display: none;
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: rgba(0, 0, 0, 0.5);
                        z-index: 9999;
                        align-items: center;
                        justify-content: center;
                    }

                    .modal-content {
                        background: #fff;
                        padding: 30px;
                        border-radius: 12px;
                        max-width: 500px;
                        width: 90%;
                        text-align: center;
                        position: relative;
                    }

                    .modal-content .close {
                        position: absolute;
                        top: 10px;
                        right: 15px;
                        font-size: 24px;
                        cursor: pointer;
                    }

                    .modal-buttons {
                        display: flex;
                        gap: 12px;
                        margin-top: 20px;
                    }

                    .modal-buttons button {
                        flex: 1;
                        padding: 12px;
                        border: none;
                        border-radius: 8px;
                        font-size: 25px;
                        font-weight: 700;
                        cursor: pointer;
                    }

                    #continue-shopping {
                        background: #e5e7eb;
                        color: #111827;
                    }

                    #go-to-checkout {
                        background: #2174ff;
                        color: #fff;
                    }

                    .play-overlay {
                        position: absolute;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        width: 60px;
                        height: 60px;
                        background: rgba(33, 116, 255, 0.9);
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: #fff;
                        font-size: 24px;
                        cursor: pointer;
                    }

                    .playable {
                        position: relative;
                        cursor: pointer;
                    }

                    @media(max-width: 768px) {
                        .crs2-progress-stats {
                            flex-direction: column;
                            gap: 4px;
                        }

                        .crs2-teacher-info {
                            flex-direction: column;
                            text-align: center;
                        }
                    }

                    /* Locked Enrolled Resources Styles */
                    .crs2-resource--locked-enrolled .crs2-resource-icon {
                        background: #dbeafe;
                        color: #2563eb;
                    }

                    .crs2-resource-app-message {
                        font-size: 18px;
                        color: #2563eb;
                        font-weight: 600;
                    }

                    .crs2-resource--locked-enrolled .crs2-resource-title {
                        color: #374151;
                    }

                    .crs2-resource--locked-enrolled .crs2-resource-content {
                        display: flex;
                        flex-direction: column;
                        gap: 4px;
                        flex: 1;
                    }

                    /* Mobile Notice Banner for Paid Videos */
                    .crs2-resource--mobile-notice {
                        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
                        border: 1px solid #f59e0b;
                        border-radius: 8px;
                        padding: 12px 16px;
                        margin: 8px 0;
                    }

                    .crs2-resource--mobile-notice .crs2-resource-main {
                        display: flex;
                        align-items: center;
                        gap: 12px;
                    }

                    .crs2-resource--mobile-notice .crs2-resource-icon {
                        width: 32px;
                        height: 32px;
                        background: #f59e0b;
                        color: white;
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 16px;
                        flex-shrink: 0;
                    }

                    .crs2-resource--mobile-notice .crs2-mobile-notice-text {
                        color: #92400e;
                        font-size: 15px;
                        font-weight: 600;
                        line-height: 1.4;
                    }

                    [dir="rtl"] .crs2-resource--mobile-notice .crs2-resource-main {
                        flex-direction: row-reverse;
                    }

                    @media (max-width: 640px) {
                        .crs2-resource--mobile-notice {
                            padding: 10px 12px;
                        }

                        .crs2-resource--mobile-notice .crs2-mobile-notice-text {
                            font-size: 14px;
                        }
                    }
                </style>
            @endpush

            @push('scripts')
                <script>
                    document.addEventListener('click', function(e) {
                        var head = e.target.closest('.crs2-section-header');
                        if (head) {
                            var section = head.closest('.crs2-section');
                            section.classList.toggle('crs2-section--open');
                            return;
                        }
                        var subHead = e.target.closest('.crs2-sub-header');
                        if (subHead) {
                            var group = subHead.closest('.crs2-subgroup');
                            group.classList.toggle('crs2-subgroup--open');
                            var toggle = subHead.querySelector('.crs2-sub-toggle');
                            if (toggle) {
                                if (group.classList.contains('crs2-subgroup--open'))
                                    toggle.textContent = '{{ translate_lang('hide') }}';
                                else
                                    toggle.textContent = '{{ translate_lang('show') }}';
                            }
                        }
                    });
                </script>
            @endpush
