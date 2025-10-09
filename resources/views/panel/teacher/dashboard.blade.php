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
        <a class="ud-item nav-link {{ request()->routeIs('chat.*') ? 'active' : '' }}" 
                href="{{ route('chat.index') }}">
                    <i class="fa-solid fa-comments"></i>
                    <span>{{ __('panel.messages') }}</span>
                    {{-- Unread messages badge (optional - you can implement this later) --}}
                    <span class="badge bg-danger ms-auto" id="unreadCount" style="display: none;">0</span>
                    <i class="fa-solid fa-angle-left"></i>
                </a>

            <a href="{{route('teacher.courses.index')}}"><button class="ud-item" data-target="courses"><i
                    class="fa-solid fa-graduation-cap"></i><span>{{ __('panel.my_courses') }}</span><i
                    class="fa-solid fa-angle-left"></i></button></a>

            <!-- New Students Tab -->
            <button class="ud-item" data-target="students"><i
                    class="fa-solid fa-users"></i><span>{{ __('panel.my_students') }}</span><i
                    class="fa-solid fa-angle-left"></i></button>

             <a href="{{route('teacher.exams.index')}}"> <button class="ud-item" data-target="results"><i
                    class="fa-solid fa-square-poll-vertical"></i><span>{{ __('panel.exams') }}</span><i
                    class="fa-solid fa-angle-left"></i></button></a>
    
            <button class="ud-item" data-target="community"><i
                    class="fa-solid fa-magnifying-glass"></i><span>{{ __('panel.q_community') }}</span><i
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
                        <div class="ud-val">{{ $user->phone }}</div>
                    </div>
                    <div class="ud-row">
                        <div class="ud-key">{{ __('panel.grade') }}</div>
                        <div class="ud-val">{{ __('panel.grade') }}
                            {{ $user->clas_id ? 'الـ' . $user->clas_id : 'غير محدد' }}</div>
                    </div>
                </div>
            </div>

            <div class="ud-panel" id="settings">
                <div class="ud-title">{{ __('panel.account_settings') }}</div>
                <form method="POST" action="{{ route('teacher.update.account') }}" enctype="multipart/form-data">
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
                        <!-- Basic User Fields -->
                        <label>{{ __('panel.name') }}
                            <input type="text" name="name" value="{{ old('name', $user->name) }}">
                        </label>

                        <label>{{ __('panel.email') }}
                            <input type="email" name="email" value="{{ old('email', $user->email) }}">
                        </label>

                        <label>{{ __('panel.phone') }}
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}">
                        </label>

                        <!-- Teacher Specific Fields -->
                        <label>{{ __('panel.lesson_name') }}
                            <input type="text" name="name_of_lesson" value="{{ old('name_of_lesson', $teacher->name_of_lesson ?? '') }}">
                        </label>

                        <label>{{ __('panel.description_en') }}
                            <textarea name="description_en" rows="4">{{ old('description_en', $teacher->description_en ?? '') }}</textarea>
                        </label>

                        <label>{{ __('panel.description_ar') }}
                            <textarea name="description_ar" rows="4">{{ old('description_ar', $teacher->description_ar ?? '') }}</textarea>
                        </label>

                        <!-- Social Media Links -->
                        <div class="social-links">
                            <h3>{{ __('panel.social_media') }}</h3>
                            
                            <label>{{ __('panel.facebook') }}
                                <input type="url" name="facebook" value="{{ old('facebook', $teacher->facebook ?? '') }}" placeholder="https://facebook.com/username">
                            </label>

                            <label>{{ __('panel.instagram') }}
                                <input type="url" name="instagram" value="{{ old('instagram', $teacher->instagram ?? '') }}" placeholder="https://instagram.com/username">
                            </label>

                            <label>{{ __('panel.youtube') }}
                                <input type="url" name="youtube" value="{{ old('youtube', $teacher->youtube ?? '') }}" placeholder="https://youtube.com/channel/username">
                            </label>

                            <label>{{ __('panel.whatsapp') }}
                                <input type="text" name="whatsapp" value="{{ old('whatsapp', $teacher->whatsapp ?? '') }}" placeholder="+1234567890">
                            </label>
                        </div>

                        <button class="ud-primary" type="submit">{{ __('panel.save') }}</button>
                    </div>
                </form>
            </div>

            <!-- New Students Panel -->
            <div class="ud-panel" id="students">
                <div class="ud-title">{{ __('panel.my_students') }}</div>
                
                <!-- Students Statistics -->
                <div class="students-stats">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <div class="stat-info">
                            <h3>{{ $totalStudents ?? 0 }}</h3>
                            <p>{{ __('panel.total_students') }}</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <div class="stat-info">
                            <h3>{{ $coursesCount ?? 0 }}</h3>
                            <p>{{ __('panel.active_courses') }}</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fa-solid fa-user-plus"></i>
                        </div>
                        <div class="stat-info">
                            <h3>{{ $recentEnrollments ? $recentEnrollments->where('created_at', '>=', now()->subDays(7))->count() : 0 }}</h3>
                            <p>{{ __('panel.new_this_week') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Enrollments -->
                @if(isset($recentEnrollments) && $recentEnrollments->count() > 0)
                    <div class="recent-enrollments">
                        <h3>{{ __('panel.recent_enrollments') }}</h3>
                        <div class="enrollments-list">
                            @foreach($recentEnrollments as $enrollment)
                                <div class="enrollment-item">
                                    <div class="student-info">
                                        <img src="{{ $enrollment->user->photo ? asset('assets/admin/uploads/' . $enrollment->user->photo) : asset('assets_front/images/avatar-round.png') }}" 
                                             alt="{{ $enrollment->user->name }}" class="student-avatar">
                                        <div class="student-details">
                                            <h4>{{ $enrollment->user->name }}</h4>
                                            <p>{{ $enrollment->user->email }}</p>
                                            <small>{{ __('panel.enrolled_in') }}: {{ $enrollment->course->title_ar }}</small>
                                        </div>
                                    </div>
                                    <div class="enrollment-meta">
                                        <span class="enrollment-date">{{ $enrollment->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Courses with Students -->
                @if(isset($coursesWithStudents) && $coursesWithStudents->count() > 0)
                    <div class="courses-students">
                        <h3>{{ __('panel.courses_with_students') }}</h3>
                        @foreach($coursesWithStudents as $course)
                            <div class="course-students-section">
                                <div class="course-header">
                                    <div class="course-info">
                                        <h4>{{ $course->title_ar }}</h4>
                                        <span class="students-count">{{ $course->students_count }} {{ __('panel.students') }}</span>
                                    </div>
                                    <button class="toggle-students" onclick="toggleStudents({{ $course->id }})">
                                        <i class="fa-solid fa-chevron-down"></i>
                                    </button>
                                </div>
                                
                                @if($course->students->count() > 0)
                                    <div class="students-grid" id="students-{{ $course->id }}" style="display: none;">
                                        @foreach($course->students as $student)
                                            <div class="student-card">
                                                <img src="{{ $student->photo ? asset('assets/admin/uploads/' . $student->photo) : asset('assets_front/images/avatar-round.png') }}" 
                                                     alt="{{ $student->name }}" class="student-photo">
                                                <div class="student-info">
                                                    <h5>{{ $student->name }}</h5>
                                                    <p>{{ $student->email }}</p>
                                                    <small>{{ __('panel.joined') }}: {{ $student->pivot->created_at ? $student->pivot->created_at->format('Y-m-d') : __('panel.unknown') }}</small>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="no-students">
                                        <p>{{ __('panel.no_students_enrolled') }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="no-courses-students">
                        <div class="empty-state">
                            <i class="fa-solid fa-users"></i>
                            <h3>{{ __('panel.no_students_yet') }}</h3>
                            <p>{{ __('panel.students_will_appear_here') }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Include other panels -->
            @include('panel.common.notifications')
            @include('panel.teacher.community')
            @include('panel.common.support')

        </div>
    </section>
@endsection

@section('styles')
<style>
.students-stats {
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

.recent-enrollments {
    margin-bottom: 30px;
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.recent-enrollments h3 {
    color: #333;
    margin: 0 0 20px 0;
    padding-bottom: 10px;
    border-bottom: 2px solid #007bff;
}

.enrollments-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.enrollment-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.student-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.student-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #007bff;
}

.student-details h4 {
    margin: 0 0 3px 0;
    color: #333;
    font-size: 1em;
}

.student-details p {
    margin: 0 0 3px 0;
    color: #666;
    font-size: 0.9em;
}

.student-details small {
    color: #888;
    font-size: 0.8em;
}

.enrollment-date {
    color: #007bff;
    font-size: 0.9em;
    font-weight: 500;
}

.courses-students {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.courses-students h3 {
    color: #333;
    margin: 0 0 20px 0;
    padding-bottom: 10px;
    border-bottom: 2px solid #007bff;
}

.course-students-section {
    margin-bottom: 25px;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    overflow: hidden;
}

.course-header {
    background: #f8f9fa;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
}

.course-info h4 {
    margin: 0 0 5px 0;
    color: #333;
    font-size: 1.1em;
}

.students-count {
    background: #007bff;
    color: white;
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 0.8em;
}

.toggle-students {
    background: none;
    border: none;
    color: #666;
    font-size: 16px;
    cursor: pointer;
    transition: transform 0.3s ease;
}

.toggle-students.active {
    transform: rotate(180deg);
}

.students-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 15px;
    padding: 20px;
}

.student-card {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
    transition: background 0.3s ease;
}

.student-card:hover {
    background: #e9ecef;
}

.student-photo {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #007bff;
}

.student-card .student-info h5 {
    margin: 0 0 3px 0;
    color: #333;
    font-size: 0.95em;
}

.student-card .student-info p {
    margin: 0 0 3px 0;
    color: #666;
    font-size: 0.85em;
}

.student-card .student-info small {
    color: #888;
    font-size: 0.75em;
}

.no-students, .no-courses-students {
    text-align: center;
    padding: 40px 20px;
    color: #666;
}

.empty-state i {
    font-size: 3em;
    color: #ddd;
    margin-bottom: 15px;
}

.empty-state h3 {
    color: #666;
    margin-bottom: 10px;
}

@media (max-width: 768px) {
    .students-stats {
        grid-template-columns: 1fr;
    }
    
    .students-grid {
        grid-template-columns: 1fr;
    }
    
    .enrollment-item {
        flex-direction: column;
        gap: 10px;
        align-items: flex-start;
    }
    
    .course-header {
        flex-direction: column;
        gap: 10px;
        align-items: flex-start;
    }
}
</style>
@endsection

@section('scripts')
<!-- JavaScript لمعاينة الصورة -->
<script>
    document.getElementById('avatarInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                document.getElementById('preview-image').setAttribute('src', e.target.result);
            };

            reader.readAsDataURL(file);
        }
    });

    // Function to toggle students visibility
    function toggleStudents(courseId) {
        const studentsDiv = document.getElementById(`students-${courseId}`);
        const toggleBtn = event.target.closest('.toggle-students');
        
        if (studentsDiv.style.display === 'none') {
            studentsDiv.style.display = 'grid';
            toggleBtn.classList.add('active');
        } else {
            studentsDiv.style.display = 'none';
            toggleBtn.classList.remove('active');
        }
    }
</script>
@endsection