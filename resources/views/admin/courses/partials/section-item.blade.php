{{-- Section Item Partial for Hierarchical Display in Management Interface --}}
@php
    $uniqueId = 'section-' . $section->id . '-' . $level;
    $childSections = $section->children;
    $sectionContents = $section->contents()->orderBy('order')->get();
    $totalContents = $sectionContents->count() + $childSections->sum(function($child) {
        return $child->contents()->count();
    });
    // Set default values if not provided
    $index = $index ?? 0;
    $parentAccordion = $parentAccordion ?? 'sectionsAccordion';
@endphp

<div class="accordion-item {{ $level > 0 ? 'ms-' . ($level * 3) : '' }}">
    <h2 class="accordion-header" id="heading{{ $uniqueId }}">
        <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" 
                type="button" 
                data-bs-toggle="collapse" 
                data-bs-target="#collapse{{ $uniqueId }}" 
                aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" 
                aria-controls="collapse{{ $uniqueId }}"
                style="{{ $level > 0 ? 'background-color: ' . ($level % 2 === 1 ? '#f8f9fa' : '#e9ecef') . ';' : '' }}">
            
            <div class="d-flex justify-content-between align-items-center w-100 me-3">
                <div class="d-flex align-items-center">
                    {{-- Level Indicator --}}
                    @if($level > 0)
                        <span class="text-muted me-2">
                            @for($i = 0; $i < $level; $i++)
                                <i class="fas fa-level-up-alt fa-rotate-90"></i>
                            @endfor
                        </span>
                    @endif
                    
                    {{-- Section Title --}}
                    <div>
                        <strong class="{{ $level > 0 ? 'text-primary' : '' }}">
                            {{ $section->title_en }}
                        </strong>
                        @if($section->title_ar)
                            <span class="ms-2 text-muted">- {{ $section->title_ar }}</span>
                        @endif
                        
                        {{-- Parent/Child Indicator --}}
                        @if($level > 0)
                            <small class="badge bg-info ms-2">{{ __('messages.subsection') }}</small>
                        @elseif($childSections->count() > 0)
                            <small class="badge bg-primary ms-2">{{ $childSections->count() }} {{ __('messages.subsections') }}</small>
                        @endif
                    </div>
                </div>
                
                <div class="d-flex align-items-center">
                    {{-- Content Count --}}
                    <span class="badge bg-success me-2">
                        {{ $sectionContents->count() }} {{ __('messages.contents') }}
                    </span>
                    
                    @if($totalContents > $sectionContents->count())
                        <span class="badge bg-info me-2">
                            {{ $totalContents }} {{ __('messages.total') }}
                        </span>
                    @endif
                    
                    {{-- Action Buttons --}}
                    @can('course-edit')
                        <a href="{{ route('courses.sections.edit', [$course, $section]) }}" 
                           class="btn btn-sm btn-warning me-1"
                           onclick="event.stopPropagation();"
                           title="{{ __('messages.edit_section') }}">
                            <i class="fas fa-edit"></i>
                        </a>
                    @endcan
                    @can('course-delete')
                        <form action="{{ route('courses.sections.destroy', [$course, $section]) }}" 
                              method="POST" 
                              class="d-inline"
                              onclick="event.stopPropagation();"
                              onsubmit="return confirm('{{ __('messages.confirm_delete_section') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="btn btn-sm btn-danger"
                                    title="{{ __('messages.delete_section') }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
        </button>
    </h2>
    
    <div id="collapse{{ $uniqueId }}" 
         class="accordion-collapse collapse {{ $index === 0 && $level === 0 ? 'show' : '' }}" 
         aria-labelledby="heading{{ $uniqueId }}" 
         data-bs-parent="#{{ $parentAccordion }}">
        <div class="accordion-body">
            
            {{-- Section Contents --}}
            @if($sectionContents->count() > 0)
                <div class="mb-3">
                    <h6 class="text-primary">
                        <i class="fas fa-file-alt"></i> {{ __('messages.section_contents') }}
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead class="table-light">
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
                                @foreach($sectionContents as $content)
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
                                            @elseif($content->content_type === 'pdf')
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
            @else
                <div class="alert alert-light mb-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> {{ __('messages.no_contents_in_section') }}
                    </small>
                </div>
            @endif

            {{-- Child Sections (Recursive) --}}
            @if($childSections->count() > 0)
                <div class="mb-3">
                    <h6 class="text-info">
                        <i class="fas fa-sitemap"></i> {{ __('messages.subsections') }}
                    </h6>
                    <div class="accordion" id="subsectionsAccordion{{ $section->id }}">
                        @foreach($childSections as $childIndex => $childSection)
                            @include('admin.courses.partials.section-item', [
                                'section' => $childSection,
                                'course' => $course,
                                'level' => $level + 1,
                                'index' => $childIndex,
                                'parentAccordion' => 'subsectionsAccordion' . $section->id
                            ])
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Quick Add Actions --}}
            @can('course-add')
                <div class="border-top pt-3 mt-3">
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('courses.contents.create', $course) }}?section_id={{ $section->id }}" 
                           class="btn btn-outline-success">
                            <i class="fas fa-plus"></i> {{ __('messages.add_content_to_section') }}
                        </a>
                        <a href="{{ route('courses.sections.create', $course) }}?parent_id={{ $section->id }}" 
                           class="btn btn-outline-primary">
                            <i class="fas fa-plus"></i> {{ __('messages.add_subsection') }}
                        </a>
                    </div>
                </div>
            @endcan
        </div>
    </div>
</div>