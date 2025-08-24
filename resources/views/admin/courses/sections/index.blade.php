@extends('layouts.admin')

@section('title', __('messages.manage_sections_contents'))

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title">{{ __('messages.manage_sections_contents') }}</h3>
                            <p class="text-muted mb-0">{{ __('messages.course') }}: {{ $course->title_en }}</p>
                        </div>
                        <div>
                            <a href="{{ route('courses.index') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_courses') }}
                            </a>
                            @can('course-add')
                                <div class="btn-group">
                                    <a href="{{ route('courses.sections.create', $course) }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> {{ __('messages.add_section') }}
                                    </a>
                                    <a href="{{ route('courses.contents.create', $course) }}" class="btn btn-success">
                                        <i class="fas fa-plus"></i> {{ __('messages.add_content') }}
                                    </a>
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Course Sections with Hierarchical Structure -->
                    @php
                        $parentSections = $course->sections()->whereNull('parent_id')->get();
                    @endphp

                    @if($parentSections->count() > 0)
                        <div class="mb-4">
                            <h4>{{ __('messages.course_sections') }}</h4>
                            <div class="accordion" id="sectionsAccordion">
                                @foreach($parentSections as $index => $section)
                                    @include('admin.courses.partials.section-item', [
                                        'section' => $section,
                                        'course' => $course,
                                        'level' => 0,
                                        'index' => $index,
                                        'parentAccordion' => 'sectionsAccordion'
                                    ])
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Direct Contents (without sections) -->
                    @if($directContents->count() > 0)
                        <div class="mb-4">
                            <h4>{{ __('messages.direct_contents') }}</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="25%">{{ __('messages.title') }}</th>
                                            <th width="10%">{{ __('messages.type') }}</th>
                                            <th width="10%">{{ __('messages.access') }}</th>
                                            <th width="8%">{{ __('messages.order') }}</th>
                                            <th width="20%">{{ __('messages.details') }}</th>
                                            <th width="22%">{{ __('messages.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($directContents as $content)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <strong>{{ $content->title_en }}</strong><br>
                                                    <small class="text-muted">{{ $content->title_ar }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary">
                                                        {{ ucfirst($content->content_type) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($content->is_free == 1)
                                                        <span class="badge bg-success">{{ __('messages.free') }}</span>
                                                    @else
                                                        <span class="badge bg-warning">{{ __('messages.paid') }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $content->order }}</td>
                                                <td>
                                                    @if($content->content_type === 'video')
                                                        <small>
                                                            <strong>{{ __('messages.type') }}:</strong> {{ ucfirst($content->video_type ?? 'N/A') }}<br>
                                                            <strong>{{ __('messages.duration') }}:</strong> {{ $content->video_duration ? gmdate('H:i:s', $content->video_duration) : 'N/A' }}<br>
                                                            @if($content->video_url)
                                                                <a href="{{ $content->video_url }}" target="_blank" class="text-primary">
                                                                    <i class="fas fa-external-link-alt"></i> {{ __('messages.view_video') }}
                                                                </a>
                                                            @endif
                                                        </small>
                                                    @elseif($content->content_type !== 'video')
                                                        <small>
                                                            <strong>{{ __('messages.pdf_type') }}:</strong> {{ ucfirst($content->pdf_type ?? 'N/A') }}<br>
                                                            @if($content->file_path)
                                                                <a href="{{ $content->file_url }}" target="_blank" class="text-primary">
                                                                    <i class="fas fa-download"></i> {{ __('messages.download_pdf') }}
                                                                </a>
                                                            @endif
                                                        </small>
                                                    @else
                                                        <span class="text-muted">{{ __('messages.no_additional_details') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        @can('course-edit')
                                                            <a href="{{ route('courses.contents.edit', [$course, $content]) }}"
                                                               class="btn btn-sm btn-warning"
                                                               title="{{ __('messages.edit_content') }}">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        @endcan
                                                        @can('course-delete')
                                                            <form action="{{ route('courses.contents.destroy', [$course, $content]) }}"
                                                                  method="POST"
                                                                  class="d-inline"
                                                                  onsubmit="return confirm('{{ __('messages.confirm_delete_content') }}')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                        class="btn btn-sm btn-danger"
                                                                        title="{{ __('messages.delete_content') }}">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    @if($parentSections->count() == 0 && $directContents->count() == 0)
                        <div class="text-center py-5">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle fa-2x mb-2"></i>
                                <h5>{{ __('messages.no_content_yet') }}</h5>
                                <p class="mb-3">{{ __('messages.add_sections_and_contents') }}</p>
                                @can('course-add')
                                    <div class="btn-group">
                                        <a href="{{ route('courses.sections.create', $course) }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> {{ __('messages.add_first_section') }}
                                        </a>
                                        <a href="{{ route('courses.contents.create', $course) }}" class="btn btn-success">
                                            <i class="fas fa-plus"></i> {{ __('messages.add_direct_content') }}
                                        </a>
                                    </div>
                                @endcan
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Keep only the first accordion item open by default
document.addEventListener('DOMContentLoaded', function() {
    const accordionItems = document.querySelectorAll('.accordion-collapse');
    accordionItems.forEach((item, index) => {
        if (index > 0) {
            item.classList.remove('show');
        }
    });

    const accordionButtons = document.querySelectorAll('.accordion-button');
    accordionButtons.forEach((button, index) => {
        if (index > 0) {
            button.classList.add('collapsed');
            button.setAttribute('aria-expanded', 'false');
        }
    });
});
</script>
@endsection
