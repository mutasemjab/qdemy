@extends('layouts.app')
@section('title', $teacher->name . ' - ' . __('front.Teacher Profile'))

@section('content')
<section class="teacher-profile-wrapper">

    <div class="teacher-header-wrapper">
        <div class="teacher-header-image">
            <img data-src="{{ $teacher->photo ? asset('assets/admin/uploads/' . $teacher->photo) : asset('assets_front/images/teacher1.png') }}" alt="{{ $teacher->name }}">
        </div>
        <div class="teacher-follow-btn">
            <button>{{ __('front.Follow') }}</button>
        </div>
    </div>

    <div class="teacher-details">
        <div class="teacher-stats">
            <div class="stat-item">
                <span>{{ __('front.Courses') }}</span>
                <strong>{{ $teacher->courses->count() }}</strong>
            </div>
        </div>

        <div class="teacher-bio">
            <h3>{{ __('front.About Teacher') }}</h3>
            <p>
                @if(app()->getLocale() == 'ar')
                    {{ $teacher->description_ar ?? __('front.No description available') }}
                @else
                    {{ $teacher->description_en ?? __('front.No description available') }}
                @endif
            </p>
        </div>

        <div class="teacher-tabs">
            <button class="tab-btn active">{{ __('front.Courses') }}</button>
        </div>

        <div class="teacher-content">
            <div class="tab-content active">
                @if($teacher->courses->count() > 0)
                    <p>
                        @foreach($teacher->courses as $course)
                            {{ app()->getLocale() == 'ar' ? $course->title_ar : $course->title_en }}
                            @if(!$loop->last) - @endif
                        @endforeach
                    </p>
                @else
                    <p>{{ __('front.No courses available') }}</p>
                @endif
            </div>
        </div>
    </div>

</section>
@endsection