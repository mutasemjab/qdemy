@extends('layouts.app')
@section('title', __('panel.my_courses'))

@section('content')
<section class="ud-wrap">
   

    <div class="ud-content">
        <div class="ud-panel show" id="courses">
            <div class="ud-title-actions">
                <div class="ud-title">{{ __('panel.my_courses') }}</div>
                <a href="{{ route('teacher.courses.create') }}" class="ud-btn-primary">
                    <i class="fa-solid fa-plus"></i> {{ __('panel.add_course') }}
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if($courses->count() > 0)
                <div class="courses-grid">
                    @foreach($courses as $course)
                        <div class="course-card">
                            <div class="course-image">
                                <img src="{{ $course->photo ? asset('assets/admin/uploads/' . $course->photo) : asset('assets_front/images/course-default.png') }}" 
                                     alt="{{ $course->title_ar }}">
                                <div class="course-actions">
                                    <a href="{{ route('teacher.courses.show', $course) }}" class="btn-action" title="{{ __('panel.view') }}">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('teacher.courses.edit', $course) }}" class="btn-action" title="{{ __('panel.edit') }}">
                                        <i class="fa-solid fa-edit"></i>
                                    </a>
                                    <button onclick="deleteCourse({{ $course->id }})" class="btn-action btn-danger" title="{{ __('panel.delete') }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="course-content">
                                <h3>{{ $course->title_ar }}</h3>
                                <p class="course-subject">{{ $course->subject->name_ar ?? __('panel.no_subject') }}</p>
                                <p class="course-description">{{ Str::limit($course->description_ar, 100) }}</p>
                                
                                <div class="course-meta">
                                    <div class="course-price">
                                        <span class="price">{{ number_format($course->selling_price, 2) }} JD</span>
                                    </div>
                                    <div class="course-date">
                                        <i class="fa-regular fa-calendar"></i>
                                        {{ $course->created_at->format('Y-m-d') }}
                                    </div>
                                </div>


                                <div class="course-buttons">
                                    <a href="{{ route('teacher.courses.sections.index', $course) }}" class="btn-secondary">
                                        {{ __('panel.manage_content') }}
                                    </a>
                                    <a href="{{ route('teacher.courses.show', $course) }}" class="btn-primary">
                                        {{ __('panel.view_details') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="pagination-wrapper">
                    {{ $courses->links() }}
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <h3>{{ __('panel.no_courses_yet') }}</h3>
                    <p>{{ __('panel.start_creating_courses') }}</p>
                    <a href="{{ route('teacher.courses.create') }}" class="btn-primary">
                        <i class="fa-solid fa-plus"></i> {{ __('panel.create_first_course') }}
                    </a>
                </div>
            @endif
        </div>

        
    </div>
</section>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('panel.confirm_delete') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('panel.delete_course_warning') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('panel.cancel') }}</button>
                <form id="deleteForm" method="POST" style="display: inline;">
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
.ud-title-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.courses-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.course-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.course-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
}

.course-image {
    position: relative;
    height: 180px;
    overflow: hidden;
}

.course-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.course-actions {
    position: absolute;
    top: 10px;
    right: 10px;
    display: flex;
    gap: 5px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.course-card:hover .course-actions {
    opacity: 1;
}

.btn-action {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: rgba(255,255,255,0.9);
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #333;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-action:hover {
    background: #fff;
    color: #007bff;
}

.btn-action.btn-danger:hover {
    color: #dc3545;
}

.course-content {
    padding: 20px;
}

.course-content h3 {
    margin: 0 0 8px 0;
    font-size: 1.2em;
    font-weight: 600;
    color: #333;
}

.course-subject {
    color: #666;
    font-size: 0.9em;
    margin: 0 0 10px 0;
}

.course-description {
    color: #777;
    font-size: 0.9em;
    line-height: 1.4;
    margin: 0 0 15px 0;
}

.course-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}

.price {
    font-size: 1.1em;
    font-weight: 600;
    color: #28a745;
}

.course-date {
    color: #666;
    font-size: 0.85em;
}

.course-stats {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #666;
    font-size: 0.85em;
}

.course-buttons {
    display: flex;
    gap: 10px;
}

.btn-primary, .btn-secondary {
    flex: 1;
    padding: 8px 12px;
    border-radius: 6px;
    text-align: center;
    text-decoration: none;
    font-size: 0.9em;
    transition: all 0.3s ease;
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

.ud-btn-primary {
    background: #007bff;
    color: white;
    padding: 10px 20px;
    border-radius: 6px;
    text-decoration: none;
    transition: background 0.3s ease;
}

.ud-btn-primary:hover {
    background: #0056b3;
    color: white;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-icon {
    font-size: 4em;
    color: #ddd;
    margin-bottom: 20px;
}

.empty-state h3 {
    color: #666;
    margin-bottom: 10px;
}

.empty-state p {
    color: #999;
    margin-bottom: 30px;
}

.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 30px;
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
    .courses-grid {
        grid-template-columns: 1fr;
    }
    
    .ud-title-actions {
        flex-direction: column;
        gap: 15px;
        align-items: stretch;
    }
    
    .course-meta {
        flex-direction: column;
        gap: 10px;
        align-items: flex-start;
    }
    
    .course-buttons {
        flex-direction: column;
    }
}
</style>
@endsection

@section('scripts')
<script>
function deleteCourse(courseId) {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const form = document.getElementById('deleteForm');
    form.action = `/teacher/courses/${courseId}`;
    modal.show();
}

// Handle delete form submission
document.getElementById('deleteForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const submitBtn = form.querySelector('button[type="submit"]');
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> {{ __("panel.deleting") }}...';
    
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
            submitBtn.innerHTML = '{{ __("panel.delete") }}';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('{{ __("panel.error_occurred") }}');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '{{ __("panel.delete") }}';
    });
});
</script>
@endsection