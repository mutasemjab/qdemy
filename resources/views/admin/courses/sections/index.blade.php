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
                    <!-- Course Sections with Hierarchical Structure -->
                    @php
                        $parentSections = $course->sections()->whereNull('parent_id')->get();
                    @endphp

                    @if($parentSections->count() > 0)
                        <div class="mb-4">
                            <h4 class="mb-3">
                                <i class="fas fa-list-alt text-primary me-2"></i>
                                {{ __('messages.course_sections') }}
                            </h4>
                            
                            @foreach($parentSections as $index => $section)
                                <div class="card section-card mb-3">
                                    <div class="card-header section-header d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="mb-1 text-white">
                                                <i class="fas fa-folder me-2"></i>
                                                {{ $section->order }}. {{ $section->title_en }}
                                            </h5>
                                            <small class="text-white-50">{{ $section->title_ar }}</small>
                                        </div>
                                        <div class="section-actions">
                                            <span class="badge bg-light text-dark me-2">
                                                {{ $section->contents->count() }} {{ __('messages.contents') }}
                                            </span>
                                            @if($section->children->count() > 0)
                                                <span class="badge bg-info text-white me-2">
                                                    {{ $section->children->count() }} {{ __('messages.subsections') }}
                                                </span>
                                            @endif
                                            
                                            <div class="btn-group">
                                                @can('course-add')
                                                    <a href="{{ route('courses.contents.create', ['course' => $course, 'section_id' => $section->id]) }}" 
                                                       class="btn btn-sm btn-light" title="{{ __('messages.add_content_to_section') }}">
                                                        <i class="fas fa-plus"></i>
                                                    </a>
                                                @endcan
                                                
                                                @can('course-edit')
                                                    <a href="{{ route('courses.sections.edit', [$course, $section]) }}" 
                                                       class="btn btn-sm btn-warning" title="{{ __('messages.edit_section') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan
                                                
                                                @can('course-delete')
                                                    <form action="{{ route('courses.sections.destroy', [$course, $section]) }}"
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('{{ __('messages.confirm_delete_section') }}')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                                title="{{ __('messages.delete_section') }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                                
                                               <button class="btn btn-sm btn-light" data-toggle="collapse" 
                                                        data-target="#section{{ $section->id }}Contents" 
                                                        title="{{ __('messages.toggle_contents') }}">
                                                    <i class="fas fa-chevron-down"></i>
                                                </button>

                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="collapse {{ $index === 0 ? 'show' : '' }}" id="section{{ $section->id }}Contents">
                                        <!-- Section Contents -->
                                        @foreach($section->contents->sortBy('order') as $content)
                                            <div class="content-item p-3 border-bottom">
                                                <div class="row align-items-center">
                                                    <div class="col-md-4">
                                                        <strong>{{ $content->title_en }}</strong><br>
                                                        <small class="text-muted">{{ $content->title_ar }}</small>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <span class="badge bg-{{ $content->content_type === 'video' ? 'primary' : 'secondary' }} badge-type">
                                                            {{ ucfirst($content->content_type) }}
                                                        </span>
                                                    </div>
                                                    <div class="col-md-2">
                                                        @if($content->is_free == 1)
                                                            <span class="badge bg-success badge-type">{{ __('messages.free') }}</span>
                                                        @else
                                                            <span class="badge bg-warning badge-type">{{ __('messages.paid') }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-2">
                                                        <small>
                                                            @if($content->content_type === 'video')
                                                                <strong>{{ __('messages.duration') }}:</strong> 
                                                                {{ $content->video_duration ? gmdate('H:i:s', $content->video_duration) : 'N/A' }}<br>
                                                            @else
                                                                <strong>{{ __('messages.type') }}:</strong> {{ ucfirst($content->pdf_type ?? 'N/A') }}<br>
                                                            @endif
                                                            <strong>{{ __('messages.order') }}:</strong> {{ $content->order }}
                                                        </small>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="btn-group">
                                                            @if($content->content_type === 'video' && $content->video_url)
                                                                <a href="{{ $content->video_url }}" target="_blank" 
                                                                   class="btn btn-sm btn-outline-primary" title="{{ __('messages.view_video') }}">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                            @elseif($content->content_type !== 'video' && $content->file_path)
                                                                <a href="{{ $content->file_url }}" target="_blank" 
                                                                   class="btn btn-sm btn-outline-primary" title="{{ __('messages.download_pdf') }}">
                                                                    <i class="fas fa-download"></i>
                                                                </a>
                                                            @endif
                                                            
                                                            @can('course-edit')
                                                                <a href="{{ route('courses.contents.edit', [$course, $content]) }}" 
                                                                   class="btn btn-sm btn-warning" title="{{ __('messages.edit_content') }}">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                            @endcan
                                                            
                                                            @can('course-delete')
                                                                <form action="{{ route('courses.contents.destroy', [$course, $content]) }}"
                                                                      method="POST" class="d-inline"
                                                                      onsubmit="return confirm('{{ __('messages.confirm_delete_content') }}')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-danger" 
                                                                            title="{{ __('messages.delete_content') }}">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            @endcan
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                        <!-- Subsections -->
                                        @foreach($section->children->sortBy('order') as $subsection)
                                            <div class="subsection-card ms-4 mt-3">
                                                <div class="card-header section-header d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-1 text-white">
                                                            <i class="fas fa-folder-open me-2"></i>
                                                            {{ $section->order }}.{{ $subsection->order }} {{ $subsection->title_en }}
                                                        </h6>
                                                        <small class="text-white-50">{{ $subsection->title_ar }}</small>
                                                    </div>
                                                    <div class="section-actions">
                                                        <span class="badge bg-light text-dark me-2">
                                                            {{ $subsection->contents->count() }} {{ __('messages.contents') }}
                                                        </span>
                                                        
                                                        <div class="btn-group">
                                                            <a href="{{ route('courses.sections.show', [$course, $subsection]) }}" 
                                                               class="btn btn-sm btn-info" title="{{ __('messages.view_section_contents') }}">
                                                                <i class="fas fa-list"></i>
                                                            </a>
                                                            
                                                            @can('course-add')
                                                                <a href="{{ route('courses.contents.create', ['course' => $course, 'section_id' => $subsection->id]) }}" 
                                                                   class="btn btn-sm btn-light" title="{{ __('messages.add_content_to_section') }}">
                                                                    <i class="fas fa-plus"></i>
                                                                </a>
                                                            @endcan
                                                            
                                                            @can('course-edit')
                                                                <a href="{{ route('courses.sections.edit', [$course, $subsection]) }}" 
                                                                   class="btn btn-sm btn-warning" title="{{ __('messages.edit_section') }}">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                            @endcan
                                                            
                                                            @can('course-delete')
                                                                <form action="{{ route('courses.sections.destroy', [$course, $subsection]) }}"
                                                                      method="POST" class="d-inline"
                                                                      onsubmit="return confirm('{{ __('messages.confirm_delete_section') }}')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-danger" 
                                                                            title="{{ __('messages.delete_section') }}">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            @endcan
                                                            
                                                            <button class="btn btn-sm btn-light" data-toggle="collapse" 
                                                                    data-target="#subsection{{ $subsection->id }}Contents" 
                                                                    title="{{ __('messages.toggle_contents') }}">
                                                                <i class="fas fa-chevron-down"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="collapse" id="subsection{{ $subsection->id }}Contents">
                                                    @foreach($subsection->contents->sortBy('order') as $content)
                                                        <div class="content-item p-3 border-bottom">
                                                            <div class="row align-items-center">
                                                                <div class="col-md-4">
                                                                    <strong>{{ $content->title_en }}</strong><br>
                                                                    <small class="text-muted">{{ $content->title_ar }}</small>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <span class="badge bg-{{ $content->content_type === 'video' ? 'primary' : 'secondary' }} badge-type">
                                                                        {{ ucfirst($content->content_type) }}
                                                                    </span>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    @if($content->is_free == 1)
                                                                        <span class="badge bg-success badge-type">{{ __('messages.free') }}</span>
                                                                    @else
                                                                        <span class="badge bg-warning badge-type">{{ __('messages.paid') }}</span>
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <small>
                                                                        @if($content->content_type === 'video')
                                                                            <strong>{{ __('messages.duration') }}:</strong> 
                                                                            {{ $content->video_duration ? gmdate('H:i:s', $content->video_duration) : 'N/A' }}<br>
                                                                        @else
                                                                            <strong>{{ __('messages.type') }}:</strong> {{ ucfirst($content->pdf_type ?? 'N/A') }}<br>
                                                                        @endif
                                                                        <strong>{{ __('messages.order') }}:</strong> {{ $content->order }}
                                                                    </small>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="btn-group">
                                                                        @if($content->content_type === 'video' && $content->video_url)
                                                                            <a href="{{ $content->video_url }}" target="_blank" 
                                                                               class="btn btn-sm btn-outline-primary" title="{{ __('messages.view_video') }}">
                                                                                <i class="fas fa-eye"></i>
                                                                            </a>
                                                                        @elseif($content->content_type !== 'video' && $content->file_path)
                                                                            <a href="{{ $content->file_url }}" target="_blank" 
                                                                               class="btn btn-sm btn-outline-primary" title="{{ __('messages.download_pdf') }}">
                                                                                <i class="fas fa-download"></i>
                                                                            </a>
                                                                        @endif
                                                                        
                                                                        @can('course-edit')
                                                                            <a href="{{ route('courses.contents.edit', [$course, $content]) }}" 
                                                                               class="btn btn-sm btn-warning" title="{{ __('messages.edit_content') }}">
                                                                                <i class="fas fa-edit"></i>
                                                                            </a>
                                                                        @endcan
                                                                        
                                                                        @can('course-delete')
                                                                            <form action="{{ route('courses.contents.destroy', [$course, $content]) }}"
                                                                                  method="POST" class="d-inline"
                                                                                  onsubmit="return confirm('{{ __('messages.confirm_delete_content') }}')">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                                                        title="{{ __('messages.delete_content') }}">
                                                                                    <i class="fas fa-trash"></i>
                                                                                </button>
                                                                            </form>
                                                                        @endcan
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Direct Contents (without sections) -->
                    @if($directContents->count() > 0)
                        <div class="mb-4">
                            <h4 class="mb-3">
                                <i class="fas fa-file-alt text-success me-2"></i>
                                {{ __('messages.direct_contents') }}
                            </h4>
                            
                            <div class="card">
                                @foreach($directContents as $content)
                                    <div class="content-item p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <strong>{{ $content->title_en }}</strong><br>
                                                <small class="text-muted">{{ $content->title_ar }}</small>
                                            </div>
                                            <div class="col-md-2">
                                                <span class="badge bg-{{ $content->content_type === 'video' ? 'primary' : 'secondary' }} badge-type">
                                                    {{ ucfirst($content->content_type) }}
                                                </span>
                                            </div>
                                            <div class="col-md-2">
                                                @if($content->is_free == 1)
                                                    <span class="badge bg-success badge-type">{{ __('messages.free') }}</span>
                                                @else
                                                    <span class="badge bg-warning badge-type">{{ __('messages.paid') }}</span>
                                                @endif
                                            </div>
                                            <div class="col-md-2">
                                                <small>
                                                    @if($content->content_type === 'video')
                                                        <strong>{{ __('messages.duration') }}:</strong> 
                                                        {{ $content->video_duration ? gmdate('H:i:s', $content->video_duration) : 'N/A' }}<br>
                                                    @else
                                                        <strong>{{ __('messages.type') }}:</strong> {{ ucfirst($content->pdf_type ?? 'N/A') }}<br>
                                                    @endif
                                                    <strong>{{ __('messages.order') }}:</strong> {{ $content->order }}
                                                </small>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="btn-group">
                                                    @if($content->content_type === 'video' && $content->video_url)
                                                        <a href="{{ $content->video_url }}" target="_blank" 
                                                           class="btn btn-sm btn-outline-primary" title="{{ __('messages.view_video') }}">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    @elseif($content->content_type !== 'video' && $content->file_path)
                                                        <a href="{{ $content->file_url }}" target="_blank" 
                                                           class="btn btn-sm btn-outline-primary" title="{{ __('messages.download_pdf') }}">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    @endif
                                                    
                                                    @can('course-edit')
                                                        <a href="{{ route('courses.contents.edit', [$course, $content]) }}" 
                                                           class="btn btn-sm btn-warning" title="{{ __('messages.edit_content') }}">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endcan
                                                    
                                                    @can('course-delete')
                                                        <form action="{{ route('courses.contents.destroy', [$course, $content]) }}"
                                                              method="POST" class="d-inline"
                                                              onsubmit="return confirm('{{ __('messages.confirm_delete_content') }}')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                                    title="{{ __('messages.delete_content') }}">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
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

<style>
.section-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px 10px 0 0;
}

.section-card {
    border: none;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
}

.subsection-card {
    border-left: 4px solid #6c757d;
    background-color: #f8f9fa;
}

.subsection-card .section-header {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
}

.content-item {
    transition: background-color 0.2s;
}

.content-item:hover {
    background-color: #f8f9fa;
}

.badge-type {
    font-size: 0.75rem;
    padding: 4px 8px;
}

.section-actions {
    display: flex;
    gap: 5px;
    align-items: center;
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle collapse button icon rotation (Bootstrap 4)
    const toggleButtons = document.querySelectorAll('[data-toggle="collapse"]');
    
    toggleButtons.forEach(button => {
        const target = document.querySelector(button.getAttribute('data-target'));
        
        if (target) {
            $(target).on('show.bs.collapse', function() {
                const icon = button.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                }
            });
            
            $(target).on('hide.bs.collapse', function() {
                const icon = button.querySelector('i');
                if (icon) {
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                }
            });
        }
    });
});
</script>
@endsection