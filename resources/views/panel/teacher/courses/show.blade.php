@extends('layouts.app')
@section('title', __('panel.course_details'))

@section('content')
<section class="ud-wrap">
    <aside class="ud-menu">
        <div class="ud-user">
            <img
                data-src="{{ auth()->user()->photo ? asset('assets/admin/uploads/' . auth()->user()->photo) : asset('assets_front/images/avatar-big.png') }}"
                alt=""
            />
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
            <div class="course-details-header">
                <div class="course-image">
                    <img
                        src="{{ $course->photo ? asset('assets/admin/uploads/' . $course->photo) : asset('assets_front/images/course-default.png') }}"
                        alt="{{ $course->title_ar }}"
                        class="course-main-image"
                    />
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
                            <i class="fa-solid fa-edit"></i>
                            {{ __('panel.edit_course') }}
                        </a>

                        <a href="{{ route('teacher.courses.sections.index', $course) }}" class="btn btn-secondary">
                            <i class="fa-solid fa-list"></i>
                            {{ __('panel.manage_content') }}
                        </a>
                    </div>
                </div>
            </div>

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
                            $totalContents = $course->sections->sum(function ($section) {
                                return $section->contents->count()
                                    + $section->children->sum(function ($child) {
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
                            foreach ($course->sections as $section) {
                                foreach ($section->contents as $content) {
                                    if ($content->content_type === 'video' && $content->video_duration) {
                                        $totalDuration += $content->video_duration;
                                    }
                                }
                                foreach ($section->children as $child) {
                                    foreach ($child->contents as $content) {
                                        if ($content->content_type === 'video' && $content->video_duration) {
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

            <div class="course-structure-overview">
                <h3>{{ __('panel.course_structure') }}</h3>

                @if ($course->sections->count() > 0)
                    <div class="structure-list">
                        @foreach ($course->sections as $index => $section)
                            <div class="structure-item">
                                <div class="structure-header">
                                    <div class="structure-info">
                                        <h4>
                                            <i class="fa-solid fa-folder"></i>
                                            {{ $index + 1 }}. {{ $section->title_ar }}
                                        </h4>
                                        <span class="structure-count">
                                            {{ $section->contents->count() + $section->children->sum(function ($child) { return $child->contents->count(); }) }}
                                            {{ __('panel.items') }}
                                        </span>
                                    </div>
                                </div>

                                @if ($section->contents->count() > 0)
                                    <div class="structure-contents">
                                        @foreach ($section->contents as $contentIndex => $content)
                                            <div class="structure-content-item">
                                                <div class="content-left">
                                                    <i class="fa-solid fa-{{ $content->content_type === 'video' ? 'play' : 'file-pdf' }}"></i>
                                                    <span>{{ $contentIndex + 1 }}. {{ $content->title_ar }}</span>
                                                </div>
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

                                @foreach ($section->children as $childIndex => $childSection)
                                    <div class="structure-child">
                                        <div class="structure-child-header">
                                            <h5>
                                                <i class="fa-solid fa-folder-open"></i>
                                                {{ $index + 1 }}.{{ $childIndex + 1 }} {{ $childSection->title_ar }}
                                            </h5>
                                            <span class="structure-count">
                                                {{ $childSection->contents->count() }} {{ __('panel.items') }}
                                            </span>
                                        </div>

                                        @if ($childSection->contents->count() > 0)
                                            <div class="structure-contents">
                                                @foreach ($childSection->contents as $contentIndex => $content)
                                                    <div class="structure-content-item">
                                                        <div class="content-left">
                                                            <i class="fa-solid fa-{{ $content->content_type === 'video' ? 'play' : 'file-pdf' }}"></i>
                                                            <span>{{ $contentIndex + 1 }}. {{ $content->title_ar }}</span>
                                                        </div>
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
                        <a
                            href="{{ route('teacher.courses.sections.create', $course) }}"
                            class="btn btn-primary"
                        >{{ __('panel.start_adding_content') }}</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
.ud-wrap{display:grid;grid-template-columns:260px 1fr;gap:24px;padding:16px}
.ud-menu{background:#fff;border:1px solid #eef0f3;border-radius:14px;padding:16px;position:sticky;top:88px;height:max-content}
.ud-user{display:flex;align-items:center;gap:12px;margin-bottom:12px}
.ud-user img{width:56px;height:56px;border-radius:50%;object-fit:cover;border:2px solid #f1f5f9}
.ud-user h3{font-size:16px;margin:0 0 2px 0}
.ud-user span{font-size:12px;color:#6b7280}
.ud-item{display:flex;align-items:center;gap:10px;padding:12px 14px;border:1px solid #e5e7eb;border-radius:10px;text-decoration:none;color:#0f172a;transition:all .18s}
.ud-item:hover{border-color:#0055D2;box-shadow:0 6px 18px rgba(0,85,210,.12);transform:translateY(-2px)}
.ud-content{min-width:0}
.ud-panel{background:#fff;border:1px solid #eef0f3;border-radius:14px;padding:16px}

.course-details-header{display:grid;grid-template-columns:320px 1fr;gap:24px;margin-bottom:20px;align-items:start}
.course-image{border-radius:14px;overflow:hidden;border:1px solid #eef0f3;background:#fafbfc}
.course-main-image{width:100%;height:100%;max-height:240px;object-fit:cover;display:block}
.course-info{min-width:0}
.course-title{margin:0 0 4px 0;font-size:28px;font-weight:800;line-height:1.2;color:#0f172a}
.course-title-en{margin:0 0 14px 0;font-size:18px;font-weight:600;color:#64748b}
.course-meta{display:flex;flex-wrap:wrap;gap:12px 18px;margin-bottom:16px}
.meta-item{display:inline-flex;align-items:center;gap:8px;font-size:14px;color:#475569;background:#f8fafc;border:1px solid #eef2f7;border-radius:10px;padding:8px 12px}
.meta-item i{color:#0055D2}
.course-actions{display:flex;flex-wrap:wrap;gap:10px}

.btn{display:inline-flex;align-items:center;gap:8px;border-radius:10px;padding:10px 14px;font-weight:800;font-size:14px;text-decoration:none;cursor:pointer;transition:transform .16s,box-shadow .16s}
.btn:hover{transform:translateY(-1px)}
.btn-primary{background:#0055D2;color:#fff}
.btn-primary:hover{box-shadow:0 10px 22px rgba(0,85,210,.25)}
.btn-secondary{background:#111827;color:#fff}
.btn-secondary:hover{box-shadow:0 10px 22px rgba(17,24,39,.25)}

.course-stats{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px;margin-bottom:18px}
.stat-card{display:flex;align-items:center;gap:14px;background:#fff;border:1px solid #eef0f3;border-radius:14px;padding:16px;transition:transform .16s,box-shadow .16s}
.stat-card:hover{transform:translateY(-2px);box-shadow:0 12px 26px rgba(2,6,23,.06)}
.stat-icon{width:52px;height:52px;border-radius:12px;background:linear-gradient(135deg,#4f8efc 0%,#0055D2 100%);color:#fff;display:flex;align-items:center;justify-content:center;font-size:20px}
.stat-info h3{margin:0;font-size:22px;font-weight:900;color:#0f172a}
.stat-info p{margin:2px 0 0 0;font-size:12px;color:#64748b;font-weight:700;text-transform:uppercase;letter-spacing:.4px}

.course-descriptions{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:18px}
.description-section{background:#fff;border:1px solid #eef0f3;border-radius:14px;padding:16px}
.description-section h3{margin:0 0 12px 0;font-size:16px;font-weight:900;color:#0f172a;border-bottom:2px solid #0055D2;padding-bottom:8px}
.description-content{font-size:14px;line-height:1.8;color:#334155;white-space:pre-line}

.course-structure-overview{background:#fff;border:1px solid #eef0f3;border-radius:14px;padding:16px}
.course-structure-overview h3{margin:0 0 14px 0;font-size:18px;font-weight:900;color:#0f172a;border-bottom:2px solid #0055D2;padding-bottom:8px}
.structure-list{display:flex;flex-direction:column;gap:12px}
.structure-item{border:1px solid #e8ecf3;border-radius:12px;overflow:hidden;background:#fcfdff}
.structure-header{background:#f5f7fb;padding:12px 14px;border-bottom:1px solid #ecf0f6}
.structure-info{display:flex;align-items:center;justify-content:space-between;gap:12px}
.structure-info h4{margin:0;display:flex;align-items:center;gap:10px;font-size:15px;font-weight:900;color:#0f172a}
.structure-count{background:#0055D2;color:#fff;padding:6px 10px;border-radius:999px;font-size:12px;font-weight:800}
.structure-child{margin:0;border-top:1px solid #ecf0f6;background:#f8fafc}
.structure-child-header{display:flex;align-items:center;justify-content:space-between;padding:10px 14px;background:#eef2f7}
.structure-child-header h5{margin:0;display:flex;align-items:center;gap:8px;font-size:14px;font-weight:900;color:#111827}
.structure-contents{padding:8px 14px}
.structure-content-item{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:10px 0;border-bottom:1px solid #f1f4f9}
.structure-content-item:last-child{border-bottom:0}
.content-left{display:flex;align-items:center;gap:10px;color:#0f172a;font-weight:700}
.structure-content-item i{color:#0055D2}
.content-badges{display:flex;align-items:center;gap:6px}
.badge{padding:6px 10px;border-radius:999px;font-size:11px;font-weight:900;letter-spacing:.2px}
.badge-primary{background:#0055D2;color:#fff}
.badge-secondary{background:#6b7280;color:#fff}
.badge-success{background:#10b981;color:#fff}
.badge-warning{background:#fde68a;color:#111827}

.empty-structure{text-align:center;padding:40px 16px;color:#64748b}
.empty-structure i{font-size:40px;color:#cbd5e1;margin-bottom:10px}
.empty-structure .btn-primary{margin-top:10px}

@media (max-width:1200px){
  .course-stats{grid-template-columns:repeat(2,minmax(0,1fr))}
}
@media (max-width:992px){
  .ud-wrap{grid-template-columns:1fr}
  .ud-menu{position:static}
  .course-details-header{grid-template-columns:1fr}
  .course-main-image{max-height:260px}
}
@media (max-width:768px){
  .course-stats{grid-template-columns:repeat(2,minmax(0,1fr))}
  .course-descriptions{grid-template-columns:1fr}
  .structure-content-item{flex-direction:column;align-items:flex-start}
  .structure-count{font-size:11px}
  .course-title{font-size:24px}
}
@media (max-width:480px){
  .course-stats{grid-template-columns:1fr}
  .btn{width:100%}
  .course-actions{flex-direction:column}
}
</style>
@endsection
