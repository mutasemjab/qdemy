@extends('layouts.app')
@section('title', __('front.Teachers'))

@section('content')
    <section class="tch-wrap">
        <div data-aos="flip-up" data-aos-duration="1000" class="anim animate-glow universities-header-wrapper">
            <div class="universities-header">
                <h2>{{ __('front.Teachers') }}</h2>
            </div>
        </div>

        <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200" class="examx-filters">
            <div class="examx-row teacher-search-row">
                <!-- Search by Teacher Name -->
                <form action="{{ route('teachers') }}" method="GET" class="teacher-search-form">
                    <div class="teacher-search-input-wrapper">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="{{ __('front.search_teacher_placeholder') }}" class="teacher-search-input">
                        @if (request('search'))
                            <a href="{{ route('teachers') }}" class="teacher-search-clear">
                                <i class="fas fa-times-circle"></i>
                            </a>
                        @endif
                    </div>
                    <button type="submit" class="teacher-search-btn">
                        <i class="fas fa-search"></i>
                        <span>{{ __('front.search') }}</span>
                    </button>
                </form>
            </div>
        </div>

        <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300" class="tch-grid">
            @if ($teachers->count() > 0)
                @foreach ($teachers as $teacher)
                    <a class="tch-item tch-item-a" href="{{ route('teacher', $teacher->id) }}">
                        <div class="tch-card">
                            <img class="tch-img" data-src="{{ $teacher->photo_url }}" alt="{{ $teacher->name }}">
                            <div class="tch-name">
                                <span>{{ $teacher->name }}</span>
                            </div>
                        </div>
                    </a>
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

@push('styles')
    <style>
        /* Teacher Search Form Styles */
        .teacher-search-row {
            display: flex;
            justify-content: center;
            width: 100%;
        }

        .teacher-search-form {
            display: flex;
            gap: 12px;
            align-items: center;
            width: 100%;
            max-width: 600px;
        }

        .teacher-search-input-wrapper {
            position: relative;
            flex: 1;
            display: flex;
            align-items: center;
        }

        .teacher-search-input {
            width: 100%;
            padding: 14px 45px 14px 20px;
            border: 2px solid #e2e6ef;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 500;
            color: #333;
            background: #fff;
            transition: all 0.3s ease;
            outline: none;
        }

        .teacher-search-input:focus {
            border-color: #2f66d5;
            box-shadow: 0 0 0 4px rgba(47, 102, 213, 0.1);
        }

        .teacher-search-input::placeholder {
            color: #999;
            font-weight: 400;
        }

        .teacher-search-clear {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            text-decoration: none;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            transition: all 0.2s ease;
        }

        .teacher-search-clear:hover {
            color: #dc3545;
            background: rgba(220, 53, 69, 0.1);
        }

        .teacher-search-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 14px 28px;
            border: none;
            border-radius: 50px;
            background: linear-gradient(135deg, #2f66d5 0%, #1e4fc0 100%);
            color: #fff;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
            box-shadow: 0 4px 15px rgba(47, 102, 213, 0.3);
        }

        .teacher-search-btn:hover {
            background: linear-gradient(135deg, #1e4fc0 0%, #2f66d5 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(47, 102, 213, 0.4);
        }

        .teacher-search-btn i {
            font-size: 16px;
            color: #fff;
        }

        /* Teacher Card Styles */
        .tch-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 30px;
            padding: 40px 20px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .tch-item {
            text-decoration: none;
            display: block;
        }

        .tch-card {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .tch-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }

        .tch-img {
            width: 100%;
            aspect-ratio: 1;
            object-fit: cover;
            display: block;
        }

        .tch-name {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0) 100%);
            padding: 40px 15px 15px;
            text-align: center;
        }

        .tch-name span {
            color: #fff;
            font-size: 18px;
            font-weight: 600;
            display: block;
        }

        /* No Results Styles */
        .no-results {
            grid-column: 1 / -1;
            text-align: center;
            padding: 60px 20px;
            background: #f8f9fa;
            border-radius: 20px;
            margin: 20px 0;
        }

        .no-results h3 {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }

        .no-results p {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
        }

        .no-results a {
            display: inline-block;
            padding: 12px 30px;
            background: #2f66d5;
            color: #fff;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .no-results a:hover {
            background: #1e4fc0;
            transform: translateY(-2px);
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .teacher-search-form {
                flex-direction: column;
                gap: 10px;
            }

            .teacher-search-input-wrapper {
                width: 100%;
            }

            .teacher-search-btn {
                width: 100%;
                justify-content: center;
            }

            .tch-grid {
                grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
                gap: 20px;
                padding: 20px 10px;
            }

            .tch-name span {
                font-size: 16px;
            }
        }

        @media (max-width: 480px) {
            .tch-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }

            .teacher-search-input {
                font-size: 16px;
                padding: 12px 40px 12px 15px;
            }

            .teacher-search-btn {
                padding: 12px 20px;
                font-size: 16px;
            }
        }

        /* RTL Support */
        [dir="rtl"] .teacher-search-clear {
            right: auto;
            left: 15px;
        }

        [dir="rtl"] .teacher-search-input {
            padding: 14px 20px 14px 45px;
        }
    </style>
@endpush
