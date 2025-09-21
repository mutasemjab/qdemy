@extends('layouts.app')
@section('title', __('panel.course_details'))

@section('content')
<section class="ud-wrap">
    <aside class="ud-menu">
        <div class="ud-user">
            <img data-src="{{ auth()->user()->photo ? asset('assets/admin/uploads/' . auth()->user()->photo) : asset('assets_front/images/avatar-big.png') }}"
                alt="">
            <div>
                <h3>{{ auth()->user()->name }}</h3>
                <span>{{ auth()->user()->email }}</span>
            </div>
        </div>

        <a href="{{ route('teacher.courses.index') }}" class="ud-item">
            <i class="fa-solid fa-arrow-left"></i>
            <span>{{ __('panel.back_to_courses') }}</span>
        </a>
    </aside>

    <div class="ud-content">
        <div class="ud-panel show">
            <!-- Course Header -->
            <div class="course-details-header">
                <div class="course-image">
                    <img src="{{ $course->photo ? asset('assets/admin/uploads/' . $course->photo) : asset('assets_front/images/course-default.png') }}" 
                         alt="{{ $course->title_ar }}" class="course-main-image">
                </div>
                <div class="course-info">
                    <h1 class="course-title">{{ $course->title_ar }}</h1>
                    <h2 class="course-title-en">{{ $course->title_en }}</h2>
                    <div class="course-meta">
                        <div class="meta-item">
                            <i class="fa-solid fa-book"></i>
                            <span>{{ $course->subject->name_ar ?? __('panel.no_subject') }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fa-solid fa-tag"></i>
                            <span>{{ number_format($course->selling_price, 2) }} {{ __('panel.currency') }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fa-solid fa-calendar"></i>
                            <span>{{ $course->created_at->format('Y-m-d') }}</span>
                        </div>
                    </div>
                    <div class="course-actions">
                        <a href="{{ route('teacher.courses.edit', $course) }}" class="btn btn-primary">
                            <i class="fa-solid fa-edit"></i> {{ __('panel.edit_course') }}
                        </a>
                        <a href="{{ route('teacher.courses.sections.index', $course) }}" class="btn btn-secondary">
                            <i class="fa-solid fa-list"></i> {{ __('panel.manage_content') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Course Statistics -->
            <div class="course-stats">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fa-solid fa-folder"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $course->sections->count() }}</h3>
                        <p>{{ __('panel.sections') }}</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fa-solid fa-play"></i>
                    </div>
                    <div class="stat-info">
                        @php
                            $totalContents = $course->sections->sum(function($section) {
                                return $section->contents->count() + $section->children->sum(function($child) {
                                    return $child->contents->count();
                                });
                            });
                        @endphp
                        <h3>{{ $totalContents }}</h3>
                        <p>{{ __('panel.contents') }}</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <div class="stat-info">
                        @php
                            $totalDuration = 0;
                            foreach($course->sections as $section) {
                                foreach($section->contents as $content) {
                                    if($content->content_type === 'video' && $content->video_duration) {
                                        $totalDuration += $content->video_duration;
                                    }
                                }
                                foreach($section->children as $child) {
                                    foreach($child->contents as $content) {
                                        if($content->content_type === 'video' && $content->video_duration) {
                                            $totalDuration += $content->video_duration;
                                        }
                                    }
                                }
                            }
                        @endphp
                        <h3>{{ gmdate('H:i', $totalDuration) }}</h3>
                        <p>{{ __('panel.total_duration') }}</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3>0</h3>
                        <p>{{ __('panel.enrolled_students') }}</p>
                    </div>
                </div>
            </div>

            <!-- Course Descriptions -->
            <div class="course-descriptions">
                <div class="description-section">
                    <h3>{{ __('panel.description_ar') }}</h3>
                    <div class="description-content" dir="rtl">
                        {{ $course->description_ar }}
                    </div>
                </div>
                <div class="description-section">
                    <h3>{{ __('panel.description_en') }}</h3>
                    <div class="description-content">
                        {{ $course->description_en }}
                    </div>
                </div>
            </div>

            <!-- Course Structure Overview -->
            <div class="course-structure-overview">
                <h3>{{ __('panel.course_structure') }}</h3>
                
                @if($course->sections->count() > 0)
                    <div class="structure-list">
                        @foreach($course->sections as $index => $section)
                            <div class="structure-item">
                                <div class="structure-header">
                                    <div class="structure-info">
                                        <h4>
                                            <i class="fa-solid fa-folder"></i>
                                            {{ $index + 1 }}. {{ $section->title_ar }}
                                        </h4>
                                        <span class="structure-count">
                                            {{ $section->contents->count() + $section->children->sum(function($child) { return $child->contents->count(); }) }} {{ __('panel.items') }}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Direct section contents -->
                                @if($section->contents->count() > 0)
                                    <div class="structure-contents">
                                        @foreach($section->contents as $contentIndex => $content)
                                            <div class="structure-content-item">
                                                <i class="fa-solid fa-{{ $content->content_type === 'video' ? 'play' : 'file-pdf' }}"></i>
                                                <span>{{ $contentIndex + 1 }}. {{ $content->title_ar }}</span>
                                                <div class="content-badges">
                                                    <span class="badge badge-{{ $content->content_type === 'video' ? 'primary' : 'secondary' }}">
                                                        {{ ucfirst($content->content_type) }}
                                                    </span>
                                                    <span class="badge badge-{{ $content->is_free == 1 ? 'success' : 'warning' }}">
                                                        {{ $content->is_free == 1 ? __('panel.free') : __('panel.paid') }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                
                                <!-- Child sections -->
                                @foreach($section->children as $childIndex => $childSection)
                                    <div class="structure-child">
                                        <div class="structure-child-header">
                                            <h5>
                                                <i class="fa-solid fa-folder-open"></i>
                                                {{ $index + 1 }}.{{ $childIndex + 1 }} {{ $childSection->title_ar }}
                                            </h5>
                                            <span class="structure-count">{{ $childSection->contents->count() }} {{ __('panel.items') }}</span>
                                        </div>
                                        
                                        @if($childSection->contents->count() > 0)
                                            <div class="structure-contents">
                                                @foreach($childSection->contents as $contentIndex => $content)
                                                    <div class="structure-content-item">
                                                        <i class="fa-solid fa-{{ $content->content_type === 'video' ? 'play' : 'file-pdf' }}"></i>
                                                        <span>{{ $contentIndex + 1 }}. {{ $content->title_ar }}</span>
                                                        <div class="content-badges">
                                                            <span class="badge badge-{{ $content->content_type === 'video' ? 'primary' : 'secondary' }}">
                                                                {{ ucfirst($content->content_type) }}
                                                            </span>
                                                            <span class="badge badge-{{ $content->is_free == 1 ? 'success' : 'warning' }}">
                                                                {{ $content->is_free == 1 ? __('panel.free') : __('panel.paid') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-structure">
                        <i class="fa-solid fa-folder-open"></i>
                        <p>{{ __('panel.no_content_structure') }}</p>
                        <a href="{{ route('teacher.courses.sections.create', $course) }}" class="btn btn-primary">
                            {{ __('panel.start_adding_content') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
.course-details-header {
    display: flex;
    gap: 30px;
    margin-bottom: 30px;
    padding: 20px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.course-image {
    flex-shrink: 0;
}

.course-main-image {
    width: 300px;
    height: 200px;
    object-fit: cover;
    border-radius: 12px;
    border: 2px solid #e9ecef;
}

.course-info {
    flex: 1;
}

.course-title {
    font-size: 2em;
    font-weight: 700;
    color: #333;
    margin: 0 0 8px 0;
}

.course-title-en {
    font-size: 1.4em;
    font-weight: 500;
    color: #666;
    margin: 0 0 20px 0;
}

.course-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 20px;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #666;
    font-size: 14px;
}

.meta-item i {
    color: #007bff;
}

.course-actions {
    display: flex;
    gap: 10px;
}

.course-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 15px;
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    background: linear-gradient(135deg, #007bff, #0056b3);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
}

.stat-info h3 {
    font-size: 1.8em;
    font-weight: 700;
    color: #333;
    margin: 0;
}

.stat-info p {
    color: #666;
    font-size: 0.9em;
    margin: 0;
}

.course-descriptions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    margin-bottom: 30px;
}

.description-section {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.description-section h3 {
    color: #333;
    font-size: 1.2em;
    margin: 0 0 15px 0;
    padding-bottom: 10px;
    border-bottom: 2px solid #007bff;
}

.description-content {
    color: #555;
    line-height: 1.6;
    font-size: 14px;
}

.course-structure-overview {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.course-structure-overview h3 {
    color: #333;
    font-size: 1.3em;
    margin: 0 0 20px 0;
    padding-bottom: 10px;
    border-bottom: 2px solid #007bff;
}

.structure-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.structure-item {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    overflow: hidden;
}

.structure-header {
    background: #f8f9fa;
    padding: 15px;
    border-bottom: 1px solid #e9ecef;
}

.structure-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.structure-info h4 {
    margin: 0;
    color: #333;
    font-size: 1.1em;
    display: flex;
    align-items: center;
    gap: 8px;
}

.structure-count {
    background: #007bff;
    color: white;
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 0.8em;
}

.structure-child {
    margin-left: 20px;
    border-top: 1px solid #e9ecef;
    background: #f8f9fa;
}

.structure-child-header {
    padding: 12px 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #e9ecef;
}

.structure-child-header h5 {
    margin: 0;
    color: #495057;
    font-size: 1em;
    display: flex;
    align-items: center;
    gap: 8px;
}

.structure-contents {
    padding: 10px 15px;
}

.structure-content-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #f0f0f0;
}

.structure-content-item:last-child {
    border-bottom: none;
}

.structure-content-item i {
    color: #007bff;
    margin-right: 8px;
}

.content-badges {
    display: flex;
    gap: 5px;
}

.badge {
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 0.75em;
    font-weight: 600;
}

.badge-primary {
    background: #007bff;
    color: white;
}

.badge-secondary {
    background: #6c757d;
    color: white;
}

.badge-success {
    background: #28a745;
    color: white;
}

.badge-warning {
    background: #ffc107;
    color: #333;
}

.empty-structure {
    text-align: center;
    padding: 40px 20px;
    color: #666;
}

.empty-structure i {
    font-size: 3em;
    color: #ddd;
    margin-bottom: 15px;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover {
    background: #0056b3;
    color: white;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #545b62;
    color: white;
}

@media (max-width: 768px) {
    .course-details-header {
        flex-direction: column;
        text-align: center;
    }
    
    .course-main-image {
        width: 100%;
        max-width: 300px;
    }
    
    .course-descriptions {
        grid-template-columns: 1fr;
    }
    
    .course-stats {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    }
    
    .structure-child {
        margin-left: 10px;
    }
    
    .structure-content-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }
}
</style>
@endsection