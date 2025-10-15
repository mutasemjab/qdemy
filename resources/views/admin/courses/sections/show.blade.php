@extends('layouts.admin')

@section('title', __('messages.section_contents'))

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title">{{ __('messages.section_contents') }}</h3>
                            <div class="mt-2">
                                <p class="text-muted mb-1">
                                    <strong>{{ __('messages.course') }}:</strong> {{ $course->title_en }}
                                </p>
                                <p class="text-muted mb-0">
                                    <strong>{{ __('messages.section') }}:</strong> 
                                    {{ $section->title_en }}
                                    @if($section->parent)
                                        <small class="text-info">({{ __('messages.subsection_of') }}: {{ $section->parent->title_en }})</small>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('courses.sections.index', $course) }}" class="btn btn-secondary me-2">
                                <i class="fas fa-arrow-left"></i> {{ __('messages.back_to_sections') }}
                            </a>
                            @can('course-add')
                                <div class="btn-group">
                                    @if($section->parent_id === null)
                                        <a href="{{ route('courses.sections.create', ['course' => $course, 'parent_id' => $section->id]) }}" 
                                           class="btn btn-info">
                                            <i class="fas fa-plus"></i> {{ __('messages.add_subsection') }}
                                        </a>
                                    @endif
                                    <a href="{{ route('courses.contents.create', ['course' => $course, 'section_id' => $section->id]) }}" 
                                       class="btn btn-success">
                                        <i class="fas fa-plus"></i> {{ __('messages.add_content') }}
                                    </a>
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Section Information -->
                    <div class="alert alert-info mb-4">
                        <div class="row">
                            <div class="col-md-8">
                                <h5 class="alert-heading">
                                    <i class="fas fa-info-circle me-2"></i>
                                    {{ $section->title_en }}
                                </h5>
                                @if($section->title_ar)
                                    <p class="mb-2"><strong>{{ __('messages.arabic_title') }}:</strong> {{ $section->title_ar }}</p>
                                @endif
                                @if($section->description_en)
                                    <p class="mb-1"><strong>{{ __('messages.description') }}:</strong> {{ $section->description_en }}</p>
                                @endif
                                @if($section->description_ar)
                                    <p class="mb-0"><strong>{{ __('messages.arabic_description') }}:</strong> {{ $section->description_ar }}</p>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <div class="text-end">
                                    <div class="mb-2">
                                        <span class="badge bg-primary fs-6">
                                            {{ $section->contents->count() }} {{ __('messages.contents') }}
                                        </span>
                                    </div>
                                    @if($section->children->count() > 0)
                                        <div class="mb-2">
                                            <span class="badge bg-info fs-6">
                                                {{ $section->children->count() }} {{ __('messages.subsections') }}
                                            </span>
                                        </div>
                                    @endif
                                    <div class="btn-group">
                                        @can('course-edit')
                                            <a href="{{ route('courses.sections.edit', [$course, $section]) }}" 
                                               class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i> {{ __('messages.edit_section') }}
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section Contents -->
                    @if($section->contents->count() > 0)
                        <div class="mb-4">
                            <h4 class="mb-3">
                                <i class="fas fa-file-alt text-success me-2"></i>
                                {{ __('messages.section_contents') }}
                            </h4>
                            
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead class="table-dark">
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
                                        @foreach($section->contents->sortBy('order') as $content)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <strong>{{ $content->title_en }}</strong><br>
                                                    <small class="text-muted">{{ $content->title_ar }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $content->content_type === 'video' ? 'primary' : 'secondary' }}">
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
                                                <td>
                                                    <span class="badge bg-light text-dark">{{ $content->order }}</span>
                                                </td>
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
                                                    @else
                                                        <small>
                                                            <strong>{{ __('messages.pdf_type') }}:</strong> {{ ucfirst($content->pdf_type ?? 'N/A') }}<br>
                                                           
                                                        </small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        @if($content->content_type === 'video' && $content->video_url)
                                                            <a href="{{ $content->video_url }}" target="_blank" 
                                                               class="btn btn-sm btn-outline-primary" title="{{ __('messages.view_video') }}">
                                                                <i class="fas fa-play"></i>
                                                            </a>
                                                        @elseif($content->content_type !== 'video' && $content->file_path)
                                                            <a href="{{ $content->file_url }}" target="_blank" 
                                                               class="btn btn-sm btn-outline-primary" title="{{ __('messages.download_pdf') }}">
                                                                <i class="fas fa-download"></i>
                                                            </a>
                                                        @endif
                                                        
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

                    <!-- Subsections -->
                    @if($section->children->count() > 0)
                        <div class="mb-4">
                            <h4 class="mb-3">
                                <i class="fas fa-folder-open text-info me-2"></i>
                                {{ __('messages.subsections') }}
                            </h4>
                            
                            @foreach($section->children->sortBy('order') as $subsection)
                                <div class="card subsection-card mb-3">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="mb-1">
                                                <i class="fas fa-folder me-2"></i>
                                                {{ $section->order }}.{{ $subsection->order }} {{ $subsection->title_en }}
                                            </h5>
                                            <small class="text-muted">{{ $subsection->title_ar }}</small>
                                        </div>
                                        <div>
                                            <span class="badge bg-primary me-2">
                                                {{ $subsection->contents->count() }} {{ __('messages.contents') }}
                                            </span>
                                            <div class="btn-group">
                                                <a href="{{ route('courses.sections.show', [$course, $subsection]) }}" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="fas fa-list"></i> {{ __('messages.view_contents') }}
                                                </a>
                                                @can('course-edit')
                                                    <a href="{{ route('courses.sections.edit', [$course, $subsection]) }}" 
                                                       class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($subsection->description_en || $subsection->description_ar)
                                        <div class="card-body">
                                            @if($subsection->description_en)
                                                <p class="mb-1">{{ $subsection->description_en }}</p>
                                            @endif
                                            @if($subsection->description_ar)
                                                <p class="mb-0 text-muted">{{ $subsection->description_ar }}</p>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Empty State -->
                    @if($section->contents->count() == 0 && $section->children->count() == 0)
                        <div class="text-center py-5">
                            <div class="alert alert-warning">
                                <i class="fas fa-folder-open fa-3x mb-3 text-warning"></i>
                                <h5>{{ __('messages.no_content_in_section') }}</h5>
                                <p class="mb-3">{{ __('messages.start_adding_content_to_section') }}</p>
                                @can('course-add')
                                    <div class="btn-group">
                                        @if($section->parent_id === null)
                                            <a href="{{ route('courses.sections.create', ['course' => $course, 'parent_id' => $section->id]) }}" 
                                               class="btn btn-info">
                                                <i class="fas fa-plus"></i> {{ __('messages.add_subsection') }}
                                            </a>
                                        @endif
                                        <a href="{{ route('courses.contents.create', ['course' => $course, 'section_id' => $section->id]) }}" 
                                           class="btn btn-success">
                                            <i class="fas fa-plus"></i> {{ __('messages.add_first_content') }}
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

<style>
.subsection-card {
    border: 1px solid #dee2e6;
    border-left: 4px solid #17a2b8;
}

.table-hover tbody tr:hover {
    background-color: rgba(0,0,0,.075);
}

.badge.fs-6 {
    font-size: 1rem !important;
}

.alert-info {
    border-left: 4px solid #17a2b8;
}

.btn-group .btn {
    border-radius: 0.375rem;
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}
</style>

@endsection