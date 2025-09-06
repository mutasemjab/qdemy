@extends('layouts.app')
@section('title', __('front.Teachers'))

@section('content')
<section class="tch-wrap">
    <div class="universities-header-wrapper">
        <div class="universities-header">
            <h2>{{ __('front.Teachers') }}</h2>
        </div>
    </div>

    <div class="examx-filters">
        <div class="examx-row">
            <!-- Subject Filter -->
            <div class="examx-dropdown">
                <button class="examx-pill">
                    <i class="fa-solid fa-caret-down"></i>
                    <span>
                        @if(request('subject') && $subjects->find(request('subject')))
                            {{ app()->getLocale() == 'ar' ? $subjects->find(request('subject'))->name_ar : $subjects->find(request('subject'))->name_en }}
                        @else
                            {{ __('front.Choose Subject') }}
                        @endif
                    </span>
                </button>
                <ul class="examx-menu">
                    <li><a href="{{ route('teachers') }}">{{ __('front.All Subjects') }}</a></li>
                    @foreach($subjects as $subject)
                        <li>
                            <a href="{{ route('teachers', ['subject' => $subject->id]) }}">
                                {{ app()->getLocale() == 'ar' ? $subject->name_ar : $subject->name_en }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="tch-grid">
        @if($teachers->count() > 0)
            @foreach ($teachers as $teacher)
                <div class="tch-item">
                    <a href="{{ route('teacher', $teacher->id) }}">
                        <img data-src="{{ $teacher->photo ? asset('assets/admin/uploads/' . $teacher->photo) : asset('assets_front/images/teacher1.png') }}" alt="{{ $teacher->name }}">
                    </a>
                </div>
            @endforeach
        @else
            <div class="no-results">
                <h3>{{ __('front.No Teachers Found') }}</h3>
                <p>{{ __('front.No teachers found matching your filter criteria') }}</p>
                <a href="{{ route('teachers') }}">{{ __('front.Show All Teachers') }}</a>
            </div>
        @endif
    </div>
</section>
@endsection