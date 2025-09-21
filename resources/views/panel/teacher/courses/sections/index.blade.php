@extends('layouts.app')
@section('title', __('panel.manage_course_content'))

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
            <div class="course-header">
                <div class="course-info">
                    <h1 class="course-title">{{ $course->title_ar }}</h1>
                    <p class="course-subject">{{ $course->subject->name_ar ?? __('panel.no_subject') }}</p>
                </div>
                <div class="course-actions">
                    <a href="{{ route('teacher.courses.sections.create', $course) }}" class="btn btn-primary">
                        <i class="fa-solid fa-plus"></i> {{ __('panel.add_section') }}
                    </a>
                    <a href="{{ route('teacher.courses.contents.create', $course) }}" class="btn btn-secondary">
                        <i class="fa-solid fa-file-plus"></i> {{ __('panel.add_content') }}
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <!-- Course Structure -->
            <div class="course-structure">
                
                <!-- Direct Course Contents (No Section) -->
                @if($directContents->count() > 0)
                    <div class="content-group direct-contents">
                        <div class="group-header">
                            <h3><i class="fa-solid fa-file"></i> {{ __('panel.course_materials') }}</h3>
                        </div>
                        <div class="contents-list">
                            @foreach($directContents as $content)
                                @include('panel.teacher.courses.partials.content-item', ['content' => $content])
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Parent Sections with Child Sections and Content -->
                @forelse($course->sections as $parentSection)
                    <div class="section-group" id="section-{{ $parentSection->id }}">
                        <div class="section-header">
                            <div class="section-info">
                                <h3>
                                    <i class="fa-solid fa-folder"></i>
                                    {{ $parentSection->title_ar }}
                                </h3>
                                <span class="content-count">
                                    {{ $parentSection->contents->count() + $parentSection->children->sum(function($child) { return $child->contents->count(); }) }} {{ __('panel.items') }}
                                </span>
                            </div>
                            <div class="section-actions">
                                <a href="{{ route('teacher.courses.contents.create', [$course, 'section_id' => $parentSection->id]) }}" 
                                   class="btn-action" title="{{ __('panel.add_content') }}">
                                    <i class="fa-solid fa-plus"></i>
                                </a>
                                <a href="{{ route('teacher.courses.sections.edit', [$course, $parentSection]) }}" 
                                   class="btn-action" title="{{ __('panel.edit_section') }}">
                                    <i class="fa-solid fa-edit"></i>
                                </a>
                                <button onclick="deleteSection({{ $parentSection->id }})" 
                                        class="btn-action btn-danger" title="{{ __('panel.delete_section') }}">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Parent Section Direct Content -->
                        @if($parentSection->contents->count() > 0)
                            <div class="contents-list">
                                @foreach($parentSection->contents->sortBy('order') as $content)
                                    @include('panel.teacher.courses.partials.content-item', ['content' => $content])
                                @endforeach
                            </div>
                        @endif

                        <!-- Child Sections (Lessons) -->
                        @foreach($parentSection->children->sortBy('created_at') as $childSection)
                            <div class="child-section-group" id="section-{{ $childSection->id }}">
                                <div class="child-section-header">
                                    <div class="section-info">
                                        <h4>
                                            <i class="fa-solid fa-folder-open"></i>
                                            {{ $childSection->title_ar }}
                                        </h4>
                                        <span class="content-count">{{ $childSection->contents->count() }} {{ __('panel.items') }}</span>
                                    </div>
                                    <div class="section-actions">
                                        <a href="{{ route('teacher.courses.contents.create', [$course, 'section_id' => $childSection->id]) }}" 
                                           class="btn-action" title="{{ __('panel.add_content') }}">
                                            <i class="fa-solid fa-plus"></i>
                                        </a>
                                        <a href="{{ route('teacher.courses.sections.edit', [$course, $childSection]) }}" 
                                           class="btn-action" title="{{ __('panel.edit_section') }}">
                                            <i class="fa-solid fa-edit"></i>
                                        </a>
                                        <button onclick="deleteSection({{ $childSection->id }})" 
                                                class="btn-action btn-danger" title="{{ __('panel.delete_section') }}">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Child Section Content -->
                                @if($childSection->contents->count() > 0)
                                    <div class="contents-list">
                                        @foreach($childSection->contents->sortBy('order') as $content)
                                            @include('panel.teacher.courses.partials.content-item', ['content' => $content])
                                        @endforeach
                                    </div>
                                @else
                                    <div class="empty-section">
                                        <i class="fa-regular fa-folder-open"></i>
                                        <p>{{ __('panel.no_content_in_section') }}</p>
                                        <a href="{{ route('teacher.courses.contents.create', [$course, 'section_id' => $childSection->id]) }}" 
                                           class="btn btn-sm btn-primary">
                                            {{ __('panel.add_first_content') }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endforeach

                        <!-- If parent has no content and no children with content -->
                        @if($parentSection->contents->count() == 0 && $parentSection->children->count() == 0)
                            <div class="empty-section">
                                <i class="fa-regular fa-folder-open"></i>
                                <p>{{ __('panel.no_content_in_section') }}</p>
                                <div class="empty-actions">
                                    <a href="{{ route('teacher.courses.sections.create', $course) }}" 
                                       class="btn btn-sm btn-secondary">
                                        {{ __('panel.add_subsection') }}
                                    </a>
                                    <a href="{{ route('teacher.courses.contents.create', [$course, 'section_id' => $parentSection->id]) }}" 
                                       class="btn btn-sm btn-primary">
                                        {{ __('panel.add_content') }}
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                @empty
                    @if($directContents->count() == 0)
                        <div class="empty-course">
                            <div class="empty-icon">
                                <i class="fa-solid fa-graduation-cap"></i>
                            </div>
                            <h3>{{ __('panel.empty_course') }}</h3>
                            <p>{{ __('panel.start_adding_content') }}</p>
                            <div class="empty-actions">
                                <a href="{{ route('teacher.courses.sections.create', $course) }}" class="btn btn-primary">
                                    <i class="fa-solid fa-folder-plus"></i> {{ __('panel.create_section') }}
                                </a>
                                <a href="{{ route('teacher.courses.contents.create', $course) }}" class="btn btn-secondary">
                                    <i class="fa-solid fa-file-plus"></i> {{ __('panel.add_content_directly') }}
                                </a>
                            </div>
                        </div>
                    @endif
                @endforelse
            </div>
        </div>
    </div>
</section>

<!-- Delete Section Modal -->
<div class="modal fade" id="deleteSectionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('panel.confirm_delete') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('panel.delete_section_warning') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('panel.cancel') }}</button>
                <form id="deleteSectionForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('panel.delete') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Content Modal -->
<div class="modal fade" id="deleteContentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('panel.confirm_delete') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('panel.delete_content_warning') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('panel.cancel') }}</button>
                <form id="deleteContentForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('panel.delete') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
/* Previous styles remain the same, plus these additions for child sections */

.child-section-group {
    margin-left: 20px;
    margin-top: 15px;
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    overflow: hidden;
}

.child-section-header {
    background: #e9ecef;
    padding: 12px 15px;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.child-section-header h4 {
    margin: 0;
    color: #495057;
    font-size: 1em;
    display: flex;
    align-items: center;
    gap: 8px;
}

.course-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f0f0f0;
}

.course-title {
    font-size: 1.8em;
    font-weight: 700;
    color: #333;
    margin: 0 0 5px 0;
}

.course-subject {
    color: #666;
    font-size: 1em;
    margin: 0;
}

.course-actions {
    display: flex;
    gap: 10px;
}

.course-structure {
    display: flex;
    flex-direction: column;
    gap: 25px;
}

.content-group,
.section-group {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 12px;
    overflow: hidden;
}

.group-header,
.section-header {
    background: #f8f9fa;
    padding: 15px 20px;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.group-header h3,
.section-header h3 {
    margin: 0;
    color: #495057;
    font-size: 1.1em;
    display: flex;
    align-items: center;
    gap: 8px;
}

.section-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.content-count {
    background: #007bff;
    color: white;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.8em;
}

.section-actions {
    display: flex;
    gap: 5px;
}

.btn-action {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    background: #fff;
    border: 1px solid #dee2e6;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #495057;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-action:hover {
    background: #e9ecef;
    color: #007bff;
}

.btn-action.btn-danger:hover {
    background: #dc3545;
    color: white;
    border-color: #dc3545;
}

.contents-list {
    padding: 0;
}

.empty-section,
.empty-course {
    padding: 40px 20px;
    text-align: center;
    color: #666;
}

.empty-course {
    padding: 60px 20px;
}

.empty-course .empty-icon {
    font-size: 4em;
    color: #ddd;
    margin-bottom: 20px;
}

.empty-section i {
    font-size: 2em;
    color: #ddd;
    margin-bottom: 10px;
}

.empty-actions {
    display: flex;
    gap: 10px;
    justify-content: center;
    margin-top: 20px;
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 12px;
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

.alert {
    padding: 12px 15px;
    border-radius: 6px;
    margin-bottom: 20px;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

@media (max-width: 768px) {
    .course-header {
        flex-direction: column;
        gap: 15px;
    }
    
    .course-actions {
        width: 100%;
        justify-content: stretch;
    }
    
    .course-actions .btn {
        flex: 1;
    }
    
    .child-section-group {
        margin-left: 10px;
    }
    
    .empty-actions {
        flex-direction: column;
        align-items: stretch;
    }
}
</style>
@endsection

@section('scripts')
<script>
function deleteSection(sectionId) {
    const modal = new bootstrap.Modal(document.getElementById('deleteSectionModal'));
    const form = document.getElementById('deleteSectionForm');
    form.action = `/teacher/courses/{{ $course->id }}/sections/${sectionId}`;
    modal.show();
}

function deleteContent(contentId) {
    const modal = new bootstrap.Modal(document.getElementById('deleteContentModal'));
    const form = document.getElementById('deleteContentForm');
    form.action = `/teacher/courses/{{ $course->id }}/contents/${contentId}`;
    modal.show();
}

// Handle delete forms submission
document.getElementById('deleteSectionForm').addEventListener('submit', function(e) {
    e.preventDefault();
    handleDelete(this, '{{ __("panel.deleting") }}', '{{ __("panel.delete") }}');
});

document.getElementById('deleteContentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    handleDelete(this, '{{ __("panel.deleting") }}', '{{ __("panel.delete") }}');
});

function handleDelete(form, loadingText, defaultText) {
    const submitBtn = form.querySelector('button[type="submit"]');
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> ${loadingText}...`;
    
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            _method: 'DELETE'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || '{{ __("panel.error_occurred") }}');
            submitBtn.disabled = false;
            submitBtn.innerHTML = defaultText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('{{ __("panel.error_occurred") }}');
        submitBtn.disabled = false;
        submitBtn.innerHTML = defaultText;
    });
}
</script>
@endsection