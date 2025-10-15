{{-- Create this file: resources/views/panel/teacher/courses/partials/content-item.blade.php --}}

<div class="content-item" id="content-{{ $content->id }}">
    <div class="content-info">
        <div class="content-icon {{ $content->content_type }}">
            @switch($content->content_type)
                @case('video')
                    <i class="fa-solid fa-play"></i>
                    @break
                @case('pdf')
                    <i class="fa-solid fa-file-pdf"></i>
                    @break
                @case('quiz')
                    <i class="fa-solid fa-question-circle"></i>
                    @break
                @case('assignment')
                    <i class="fa-solid fa-tasks"></i>
                    @break
                @default
                    <i class="fa-solid fa-file"></i>
            @endswitch
        </div>
        
        <div class="content-details">
            <h4>{{ $content->title_ar }}</h4>
            <div class="content-meta">
                <span class="content-type">
                    <i class="fa-solid fa-tag"></i>
                    {{ ucfirst($content->content_type) }}
                </span>
                
                @if($content->content_type === 'video' && $content->video_duration)
                    <span class="duration">
                        <i class="fa-solid fa-clock"></i>
                        {{ gmdate('H:i:s', $content->video_duration) }}
                    </span>
                @endif
                
                <span class="access-type">
                    @if($content->is_free == 1)
                        <span class="badge badge-success">{{ __('panel.free') }}</span>
                    @else
                        <span class="badge badge-warning">{{ __('panel.paid') }}</span>
                    @endif
                </span>
                
                @if($content->order)
                    <span class="order">
                        <i class="fa-solid fa-sort-numeric-up"></i>
                        {{ __('panel.order') }}: {{ $content->order }}
                    </span>
                @endif
            </div>
        </div>
    </div>
    
    <div class="content-actions">
        @if($content->content_type === 'video' && $content->video_url)
            <a href="{{ $content->video_type === 'youtube' ? $content->video_url : asset($content->video_url) }}" 
               target="_blank" class="btn-action" title="{{ __('panel.preview') }}">
                <i class="fa-solid fa-external-link-alt"></i>
            </a>
        @elseif($content->content_type !== 'video' && $content->file_path)
            <a href="{{ asset($content->file_path) }}" 
               target="_blank" class="btn-action" title="{{ __('panel.download') }}">
                <i class="fa-solid fa-download"></i>
            </a>
        @endif
        
        <a href="{{ route('teacher.courses.contents.edit', [$course, $content]) }}" 
           class="btn-action" title="{{ __('panel.edit') }}">
            <i class="fa-solid fa-edit"></i>
        </a>
        
        <button onclick="deleteContent({{ $content->id }})" 
                class="btn-action btn-danger" title="{{ __('panel.delete') }}">
            <i class="fa-solid fa-trash"></i>
        </button>
    </div>
</div>