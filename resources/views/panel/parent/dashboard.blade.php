@extends('layouts.app')
@section('title', __('panel.my_account'))
@section('content')
    <section class="ud-wrap">

        <aside class="ud-menu">
            <div class="ud-user">
                <img data-src="{{ $user->photo ? asset('assets/admin/uploads/' . $user->photo) : asset('assets_front/images/avatar-big.png') }}"
                    alt="">
                <div>
                    <h3>{{ $user->name }}</h3>
                    <span>{{ $user->email }}</span>
                </div>
            </div>

            <button class="ud-item active" data-target="profile"><i
                    class="fa-regular fa-user"></i><span>{{ __('panel.personal_profile') }}</span><i
                    class="fa-solid fa-angle-left"></i></button>
            <button class="ud-item" data-target="settings"><i
                    class="fa-solid fa-gear"></i><span>{{ __('panel.account_settings') }}</span><i
                    class="fa-solid fa-angle-left"></i></button>
            <button class="ud-item" data-target="notifications"><i
                    class="fa-regular fa-bell"></i><span>{{ __('panel.notifications') }}</span><i
                    class="fa-solid fa-angle-left"></i></button>
            <button class="ud-item" data-target="inbox"><i
                    class="fa-regular fa-comments"></i><span>{{ __('panel.messages') }}</span><i
                    class="fa-solid fa-angle-left"></i></button>
            <button class="ud-item" data-target="kids"><i
                    class="fa-solid fa-children"></i><span>{{ __('panel.children_overview') }}</span><i
                    class="fa-solid fa-angle-left"></i></button>
            <button class="ud-item" data-target="child-reports"><i
                    class="fa-solid fa-chart-line"></i><span>{{ __('panel.children_reports') }}</span><i
                    class="fa-solid fa-angle-left"></i></button>
     
            <button class="ud-item" data-target="add-child"><i
                    class="fa-solid fa-user-plus"></i><span>{{ __('panel.add_child') }}</span><i
                    class="fa-solid fa-angle-left"></i></button>
            <button class="ud-item" data-target="support"><i
                    class="fa-brands fa-whatsapp"></i><span>{{ __('panel.technical_support') }}</span><i
                    class="fa-solid fa-angle-left"></i></button>

            <a href="{{ route('user.logout') }}" class="ud-logout"><i
                    class="fa-solid fa-arrow-left-long"></i><span>{{ __('panel.logout') }}</span></a>
        </aside>

        <div class="ud-content">

            <div class="ud-panel show" id="profile">
                <div class="ud-title">{{ __('panel.personal_profile') }}</div>
                <div class="ud-profile-head">
                    <img data-src="{{ $user->photo ? asset('assets/admin/uploads/' . $user->photo) : asset('assets_front/images/avatar-round.png') }}"
                        class="ud-ava" alt="">
                    <div class="ud-name">
                        <h2>{{ $user->name }}<br><span class="g-sub1">{{ $user->email }}</span></h2>
                    </div>
                </div>
                <div class="ud-formlist">
                    <div class="ud-row">
                        <div class="ud-key">{{ __('panel.name') }}</div>
                        <div class="ud-val">{{ $user->name }}</div>
                    </div>
                    <div class="ud-row">
                        <div class="ud-key">{{ __('panel.email') }}</div>
                        <div class="ud-val">{{ $user->email }}</div>
                    </div>
                    <div class="ud-row">
                        <div class="ud-key">{{ __('panel.phone') }}</div>
                        <div class="ud-val">{{ $user->phone ?? 'N/A' }}</div>
                    </div>
                    <div class="ud-row">
                        <div class="ud-key">{{ __('panel.total_children') }}</div>
                        <div class="ud-val">{{ $children->count() }}</div>
                    </div>
                </div>
            </div>

            <div class="ud-panel" id="settings">
                <div class="ud-title">{{ __('panel.account_settings') }}</div>
                <form method="POST" action="{{ route('parent.update.account') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="ud-profile-head">
                        <div class="ud-ava-wrap">
                            <!-- صورة البروفايل -->
                            <img id="preview-image"
                                src="{{ $user->photo ? asset('assets/admin/uploads/' . $user->photo) : asset('assets_front/images/avatar-round.png') }}"
                                class="ud-ava" alt="">

                            <!-- زر التعديل -->
                            <label class="ud-ava-edit">
                                <i class="fa-solid fa-pen"></i>
                                <input type="file" id="avatarInput" name="photo" accept="image/*" style="display:none">
                            </label>
                        </div>

                        <div class="ud-name">
                            <h2>{{ $user->name }}<br><span class="g-sub1">{{ $user->email }}</span></h2>
                        </div>
                    </div>

                    <div class="ud-edit">
                        <label>{{ __('panel.name') }}
                            <input type="text" name="name" value="{{ old('name', $user->name) }}">
                        </label>

                        <label>{{ __('panel.email') }}
                            <input type="email" name="email" value="{{ old('email', $user->email) }}">
                        </label>

                        <label>{{ __('panel.phone') }}
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}">
                        </label>

                        <button class="ud-primary" type="submit">{{ __('panel.save') }}</button>
                    </div>
                </form>
            </div>

            <!-- Children Panel with Real Data -->
            <div class="ud-panel" id="kids">
                <div class="ud-title">{{ __('panel.children_overview') }}</div>
                
                @if($children->count() > 0)
                    <div class="ud-children-list">
                        @foreach($children as $child)
                            <div class="ud-child-card">
                                <div class="ud-kid-head">
                                    <img data-src="{{ $child->photo_url ?? asset('assets_front/images/kid.png') }}">
                                    <div>
                                        <h2>{{ $child->name }}<br>
                                            <span class="g-sub1">
                                                @if($child->clas)
                                                    {{ $child->clas->name }}
                                                @else
                                                    {{ __('panel.no_class_assigned') }}
                                                @endif
                                            </span>
                                        </h2>
                                    </div>
                                    <div class="ud-child-actions">
                                        <button class="ud-child-btn" onclick="viewChildDetails({{ $child->user_id }})">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button class="ud-child-btn" onclick="removeChildFromParent({{ $child->user_id }}, '{{ $child->name }}')">
                                            <i class="fa-solid fa-trash text-danger"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Child Statistics -->
                                <div class="ud-child-stats">
                                    <div class="ud-stat-item">
                                        <div class="ud-stat-number">{{ $child->enrolledCoursesCount ?? 0 }}</div>
                                        <div class="ud-stat-label">{{ __('panel.courses') }}</div>
                                    </div>
                                    <div class="ud-stat-item">
                                        <div class="ud-stat-number">{{ $child->completedExamsCount ?? 0 }}</div>
                                        <div class="ud-stat-label">{{ __('panel.exams') }}</div>
                                    </div>
                                    <div class="ud-stat-item">
                                        <div class="ud-stat-number">{{ number_format($child->averageScore ?? 0, 1) }}%</div>
                                        <div class="ud-stat-label">{{ __('panel.avg_score') }}</div>
                                    </div>
                                </div>

                                <!-- Progress Bars for Recent Courses -->
                                <div class="ud-bars">
                                   @if($child->courses && $child->courses->count() > 0)
                                      @foreach($child->courses as $course)
                                          <div class="ud-bar">
                                              <div class="ud-bar-head">
                                                  <b>{{ Str::limit($course['title'], 25) }}</b>
                                                  <small>({{ $course['subject_name'] }})</small>
                                              </div>
                                              <div class="ud-bar-track">
                                                  <span style="width:{{ $course['progress'] }}%"></span>
                                              </div>
                                              <div class="ud-bar-foot">
                                                  100%<b>{{ number_format($course['progress'], 1) }}%</b>
                                              </div>
                                          </div>
                                      @endforeach
                                  @else
                                      <div class="ud-no-courses">
                                          <i class="fa-solid fa-book"></i>
                                          <span>{{ __('panel.no_courses_enrolled') }}</span>
                                      </div>
                                  @endif
                                </div>

                                <!-- Recent Exam Results -->
                             
                                
                                @if($child->recentExams && $child->recentExams->count() > 0)
                                    <div class="ud-recent-exams">
                                        <h4>{{ __('panel.recent_exams') }}</h4>
                                        @foreach($child->recentExams as $exam)
                                            <div class="ud-exam-result">
                                                <div class="ud-exam-info">
                                                    <span class="ud-exam-title">{{ Str::limit($exam['exam_title'], 30) }}</span>
                                                    <span class="ud-exam-date">{{ $exam['completed_at']->format('M d') }}</span>
                                                </div>
                                                <div class="ud-exam-score {{ $exam['passed'] ? 'passed' : 'failed' }}">
                                                    {{ number_format($exam['percentage'], 1) }}%
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="ud-no-children">
                        <i class="fa-solid fa-users"></i>
                        <h3>{{ __('panel.no_children_added') }}</h3>
                        <p>{{ __('panel.add_children_message') }}</p>
                        <button class="ud-primary" onclick="switchPanel('add-child')">
                            {{ __('panel.add_first_child') }}
                        </button>
                    </div>
                @endif
            </div>

            <!-- Children Reports Panel -->
            <div class="ud-panel" id="child-reports">
                <div class="ud-title">{{ __('panel.children_reports') }}</div>
                
                @if($children->count() > 0)
                    <div class="ud-reports-grid">
                        @foreach($children as $child)
                           
                            
                            <div class="ud-report-card">
                                <div class="ud-report-header">
                                    <img data-src="{{ $child->photo_url ?? asset('assets_front/images/kid.png') }}">
                                    <div>
                                        <h3>{{ $child->name }}</h3>
                                        <span>{{ $child->clas->name ?? __('panel.no_class') }}</span>
                                    </div>
                                </div>
                                
                                <div class="ud-report-metrics">
                                    <div class="ud-metric">
                                        <div class="ud-metric-value">{{ $child->courses ? $child->courses->count() : 0 }}</div>
                                        <div class="ud-metric-label">{{ __('panel.enrolled_courses') }}</div>
                                    </div>
                                    <div class="ud-metric">
                                        <div class="ud-metric-value">{{ $child->courses ? number_format($child->courses->avg('progress') ?? 0, 1) : 0 }}%</div>
                                        <div class="ud-metric-label">{{ __('panel.avg_progress') }}</div>
                                    </div>
                                    <div class="ud-metric">
                                        <div class="ud-metric-value">{{ $child->recentExams ? $child->recentExams->count() : 0 }}</div>
                                        <div class="ud-metric-label">{{ __('panel.completed_exams') }}</div>
                                    </div>
                                    <div class="ud-metric">
                                        <div class="ud-metric-value">{{ $child->recentExams ? number_format($child->recentExams->avg('percentage') ?? 0, 1) : 0 }}%</div>
                                        <div class="ud-metric-label">{{ __('panel.exam_average') }}</div>
                                    </div>
                                </div>
                                
                                <div class="ud-report-actions">
                                    <button class="ud-btn-outline" onclick="viewChildDetails({{ $child->user_id }})">
                                        {{ __('panel.view_details') }}
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="ud-no-data">
                        <i class="fa-solid fa-chart-line"></i>
                        <h3>{{ __('panel.no_reports_available') }}</h3>
                        <p>{{ __('panel.add_children_to_see_reports') }}</p>
                    </div>
                @endif
            </div>

            <!-- Add Child Panel with Search -->
            <div class="ud-panel" id="add-child">
                <div class="ud-title">{{ __('panel.add_child') }}</div>
                <div class="ud-add-child-form">
                    <div class="ud-search-student">
                        <label>{{ __('panel.search_student') }}
                            <input type="text" id="studentSearch" placeholder="{{ __('panel.search_by_name_or_phone') }}" 
                                   onkeyup="searchStudents()">
                        </label>
                        <div id="searchResults" class="ud-search-results"></div>
                    </div>
                    
                  
                </div>
            </div>

            <!-- Include common panels -->
            @include('panel.common.notifications')
            @include('panel.common.support')

        </div>
    </section>

    <!-- Remove Child Modal -->
    <div id="removeChildModal" class="ud-modal" style="display: none;">
        <div class="ud-modal-content">
            <div class="ud-modal-header">
                <h3>{{ __('panel.remove_child') }}</h3>
                <button class="ud-modal-close" onclick="closeModal('removeChildModal')">&times;</button>
            </div>
            <div class="ud-modal-body">
                <p>{{ __('panel.remove_child_confirmation') }} <strong id="childNameToRemove"></strong>?</p>
                <div class="ud-warning">
                    <i class="fa-solid fa-exclamation-triangle"></i>
                    {{ __('panel.remove_child_warning') }}
                </div>
            </div>
            <div class="ud-modal-footer">
                <button class="ud-btn-secondary" onclick="closeModal('removeChildModal')">{{ __('panel.cancel') }}</button>
                <button class="ud-btn-danger" id="confirmRemoveChild">{{ __('panel.remove') }}</button>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
.ud-child-actions {
    display: flex;
    gap: 10px;
    margin-left: auto;
}

.ud-child-btn {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.ud-child-btn:hover {
    background: #e9ecef;
}

.ud-child-stats {
    display: flex;
    justify-content: space-around;
    margin: 15px 0;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}

.ud-stat-item {
    text-align: center;
}

.ud-stat-number {
    font-size: 1.5rem;
    font-weight: bold;
    color: #007bff;
}

.ud-stat-label {
    font-size: 0.8rem;
    color: #6c757d;
    margin-top: 5px;
}

.ud-recent-exams {
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid #dee2e6;
}

.ud-recent-exams h4 {
    margin-bottom: 10px;
    font-size: 1rem;
    color: #495057;
}

.ud-exam-result {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #f1f3f4;
}

.ud-exam-info {
    display: flex;
    flex-direction: column;
}

.ud-exam-title {
    font-weight: 500;
    font-size: 0.9rem;
}

.ud-exam-date {
    font-size: 0.8rem;
    color: #6c757d;
}

.ud-exam-score {
    font-weight: bold;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.9rem;
}

.ud-exam-score.passed {
    background: #d4edda;
    color: #155724;
}

.ud-exam-score.failed {
    background: #f8d7da;
    color: #721c24;
}

.ud-no-children, .ud-no-data, .ud-no-students {
    text-align: center;
    padding: 40px 20px;
}

.ud-no-children i, .ud-no-data i, .ud-no-students i {
    font-size: 3rem;
    color: #6c757d;
    margin-bottom: 15px;
}

.ud-reports-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.ud-report-card {
    background: #fff;
    border: 1px solid #dee2e6;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.ud-report-header {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.ud-report-header img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    margin-right: 15px;
}

.ud-report-metrics {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    margin: 20px 0;
}

.ud-metric {
    text-align: center;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 8px;
}

.ud-metric-value {
    font-size: 1.25rem;
    font-weight: bold;
    color: #007bff;
}

.ud-metric-label {
    font-size: 0.8rem;
    color: #6c757d;
    margin-top: 5px;
}

.ud-students-list {
    max-height: 400px;
    overflow-y: auto;
}

.ud-student-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    margin-bottom: 10px;
}

.ud-student-info {
    display: flex;
    align-items: center;
}

.ud-student-info img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 10px;
}

.ud-class-badge {
    background: #007bff;
    color: white;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.7rem;
    margin-left: 10px;
}

.ud-search-results {
    max-height: 200px;
    overflow-y: auto;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    margin-top: 10px;
    display: none;
}

.ud-modal {
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.ud-modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: none;
    border-radius: 8px;
    width: 80%;
    max-width: 500px;
}

.ud-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.ud-modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
}

.ud-warning {
    background: #fff3cd;
    color: #856404;
    padding: 10px;
    border-radius: 4px;
    margin: 10px 0;
}

.ud-modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 20px;
}

.ud-btn-secondary {
    background: #6c757d;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
}

.ud-btn-danger {
    background: #dc3545;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
}

.ud-no-courses {
    text-align: center;
    padding: 20px;
    color: #6c757d;
}

.ud-no-courses i {
    font-size: 2rem;
    margin-bottom: 10px;
}
</style>
@endpush

@push('scripts')
<script>
let childIdToRemove = null;

function viewChildDetails(childId) {
    // You can redirect to a detailed view or open a modal
  window.location.href = `{{ route('parent.children.detail', '') }}/${childId}`;

}

function removeChildFromParent(childId, childName) {
    childIdToRemove = childId;
    document.getElementById('childNameToRemove').textContent = childName;
    document.getElementById('removeChildModal').style.display = 'block';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

document.getElementById('confirmRemoveChild').addEventListener('click', function() {
    if (childIdToRemove) {
        this.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> {{ __("panel.removing") }}...';
        this.disabled = true;
        
        fetch('{{ route("parent.remove-child") }}', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                student_id: childIdToRemove
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || '{{ __("panel.error_occurred") }}');
                this.innerHTML = '{{ __("panel.remove") }}';
                this.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __("panel.error_occurred") }}');
            this.innerHTML = '{{ __("panel.remove") }}';
            this.disabled = false;
        });
    }
});

function addChild(studentId, studentName) {
    const button = event.target;
    button.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
    button.disabled = true;
    
    fetch('{{ route("parent.add-child-submit") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            student_id: studentId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove the student from the list
            button.closest('.ud-student-item').remove();
            alert(data.message);
        } else {
            alert(data.message || '{{ __("panel.error_occurred") }}');
            button.innerHTML = '{{ __("panel.add") }}';
            button.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('{{ __("panel.error_occurred") }}');
        button.innerHTML = '{{ __("panel.add") }}';
        button.disabled = false;
    });
}

function searchStudents() {
    const searchTerm = document.getElementById('studentSearch').value;
    
    if (searchTerm.length >= 2) {
        fetch(`{{ route("parent.search-students") }}?search=${encodeURIComponent(searchTerm)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displaySearchResults(data.students);
            }
        })
        .catch(error => console.error('Error:', error));
    } else {
        document.getElementById('searchResults').style.display = 'none';
    }
}

function displaySearchResults(students) {
    const resultsContainer = document.getElementById('searchResults');
    
    if (students.length > 0) {
        let html = '';
        students.forEach(student => {
            html += `
                <div class="ud-student-item" data-student-id="${student.id}">
                    <div class="ud-student-info">
                        <img data-src="${student.photo_url || '{{ asset("assets_front/images/kid.png") }}'}" style="width: 30px; height: 30px;">
                        <div>
                            <h5>${student.name}</h5>
                            <span>${student.phone || 'N/A'}</span>
                        </div>
                    </div>
                    <button class="ud-primary" onclick="addChild(${student.id}, '${student.name}')">
                        {{ __('panel.add') }}
                    </button>
                </div>
            `;
        });
        resultsContainer.innerHTML = html;
        resultsContainer.style.display = 'block';
    } else {
        resultsContainer.innerHTML = '<div class="ud-no-results">{{ __("panel.no_students_found") }}</div>';
        resultsContainer.style.display = 'block';
    }
}

function switchPanel(panelId) {
    // Hide all panels
    document.querySelectorAll('.ud-panel').forEach(panel => {
        panel.classList.remove('show');
    });
    
    // Remove active class from all menu items
    document.querySelectorAll('.ud-item').forEach(item => {
        item.classList.remove('active');
    });
    
    // Show target panel
    document.getElementById(panelId).classList.add('show');
    
    // Add active class to corresponding menu item
    document.querySelector(`[data-target="${panelId}"]`).classList.add('active');
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('removeChildModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}

// Preview uploaded image
document.getElementById('avatarInput').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-image').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});

</script>
@endpush