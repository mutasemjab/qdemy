@extends('layouts.app')
@section('title', $childUser->name . ' - ' . __('panel.details'))

@section('content')
    <section class="ud-wrap">

        <aside class="ud-menu">
            <div class="ud-user">
                <img data-src="{{ $childUser->photo_url }}"
                    alt="">
                <div>
                    <h3>{{ $childUser->name }}</h3>
                    <span>{{ $childUser->phone ?? 'N/A' }}</span>
                </div>
            </div>

            <button class="ud-item active" data-target="overview"><i
                    class="fas fa-chart-pie"></i><span>{{ __('panel.overview') }}</span><i
                    class="fas fa-angle-left"></i></button>
            <button class="ud-item" data-target="courses"><i
                    class="fas fa-book"></i><span>{{ __('panel.courses') }}</span><i
                    class="fas fa-angle-left"></i></button>
            <button class="ud-item" data-target="exams"><i
                    class="fas fa-clipboard-check"></i><span>{{ __('panel.exams') }}</span><i
                    class="fas fa-angle-left"></i></button>
            <button class="ud-item" data-target="progress"><i
                    class="fas fa-chart-line"></i><span>{{ __('panel.progress') }}</span><i
                    class="fas fa-angle-left"></i></button>
            <button class="ud-item" data-target="performance"><i
                    class="fas fa-trophy"></i><span>{{ __('panel.performance') }}</span><i
                    class="fas fa-angle-left"></i></button>

            <a href="{{ route('parent.dashboard') }}" class="ud-logout"><i
                    class="fas fa-arrow-left-long"></i><span>{{ __('panel.back_to_dashboard') }}</span></a>
        </aside>

        <div class="ud-content">

            <!-- Overview Panel -->
            <div class="ud-panel show" id="overview">
                <div class="ud-title">{{ __('panel.student_overview') }} - {{ $childUser->name }}</div>
                
                <!-- Student Info Card -->
                <div class="ud-student-info-card">
                    <div class="ud-profile-head">
                        <img data-src="{{ $childUser->photo_url }}"
                            class="ud-ava" alt="">
                        <div class="ud-name">
                            <h2>{{ $childUser->name }}<br>
                                <span class="g-sub1">
                                    @if($childUser->clas)
                                        {{ $childUser->clas->name }}
                                    @else
                                        {{ __('panel.no_class_assigned') }}
                                    @endif
                                </span>
                            </h2>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="ud-quick-stats">
                        <div class="ud-stat-card">
                            <div class="ud-stat-icon bg-primary">
                                <i class="fas fa-book"></i>
                            </div>
                            <div class="ud-stat-info">
                                <h3>{{ $overallStats['total_courses'] }}</h3>
                                <p>{{ __('panel.enrolled_courses') }}</p>
                            </div>
                        </div>
                        
                        <div class="ud-stat-card">
                            <div class="ud-stat-icon bg-success">
                                <i class="fas fa-clipboard-check"></i>
                            </div>
                            <div class="ud-stat-info">
                                <h3>{{ $overallStats['completed_exams'] }}</h3>
                                <p>{{ __('panel.completed_exams') }}</p>
                            </div>
                        </div>
                        
                        <div class="ud-stat-card">
                            <div class="ud-stat-icon bg-warning">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="ud-stat-info">
                                <h3>{{ number_format($overallStats['average_score'], 1) }}%</h3>
                                <p>{{ __('panel.average_score') }}</p>
                            </div>
                        </div>
                        
                        <div class="ud-stat-card">
                            <div class="ud-stat-icon bg-info">
                                <i class="fas fa-award"></i>
                            </div>
                            <div class="ud-stat-info">
                                <h3>{{ $overallStats['total_attempts'] }}</h3>
                                <p>{{ __('panel.total_attempts') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Best Subject -->
                    @if($overallStats['best_subject'])
                        <div class="ud-best-subject">
                            <div class="ud-achievement-badge">
                                <i class="fas fa-star"></i>
                                <div>
                                    <h4>{{ __('panel.best_subject') }}</h4>
                                    <p>{{ $overallStats['best_subject']['name'] }} - {{ number_format($overallStats['best_subject']['average_score'], 1) }}%</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Recent Activity -->
                @if($overallStats['recent_activity']->count() > 0)
                    <div class="ud-recent-activity">
                        <h3>{{ __('panel.recent_activity') }}</h3>
                        <div class="ud-activity-timeline">
                            @foreach($overallStats['recent_activity'] as $activity)
                                <div class="ud-activity-item">
                                    <div class="ud-activity-icon {{ $activity['score'] >= 70 ? 'success' : ($activity['score'] >= 50 ? 'warning' : 'danger') }}">
                                        <i class="fas fa-clipboard-check"></i>
                                    </div>
                                    <div class="ud-activity-content">
                                        <h4>{{ $activity['title'] }}</h4>
                                        <p>{{ __('panel.scored') }} {{ number_format($activity['score'], 1) }}%</p>
                                        <span class="ud-activity-date">{{ $activity['date']->diffForHumans() }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Courses Panel -->
            <div class="ud-panel" id="courses">
                <div class="ud-title">{{ __('panel.enrolled_courses') }}</div>
                
                @if($courses->count() > 0)
                    <div class="ud-courses-grid">
                        @foreach($courses as $course)
                            <div class="ud-course-card">
                                <div class="ud-course-header">
                                    <img data-src="{{ $course['photo_url'] }}" alt="{{ $course['title'] }}">
                                    <div class="ud-course-progress-circle">
                                        <svg viewBox="0 0 36 36" class="circular-chart">
                                            <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                            <path class="circle" stroke-dasharray="{{ $course['progress'] }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                            <text x="18" y="20.35" class="percentage">{{ number_format($course['progress'], 0) }}%</text>
                                        </svg>
                                    </div>
                                </div>
                                
                                <div class="ud-course-body">
                                    <h4>{{ Str::limit($course['title'], 40) }}</h4>
                                    <p class="ud-course-teacher">
                                        <i class="fas fa-user"></i> {{ $course['teacher_name'] }}
                                    </p>
                                    <p class="ud-course-subject">
                                        <i class="fas fa-tag"></i> {{ $course['subject_name'] }}
                                    </p>
                                    
                                    <div class="ud-course-stats">
                                        <div class="ud-course-stat">
                                            <span>{{ $course['total_sections'] }}</span>
                                            <small>{{ __('panel.sections') }}</small>
                                        </div>
                                        <div class="ud-course-stat">
                                            <span>{{ $course['completed_sections'] }}</span>
                                            <small>{{ __('panel.completed') }}</small>
                                        </div>
                                    </div>
                                    
                                    <div class="ud-course-enrolled">
                                        <small>{{ __('panel.enrolled_on') }}: {{ $course['enrolled_at']->format('M d, Y') }}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="ud-no-content">
                        <i class="fas fa-book"></i>
                        <h3>{{ __('panel.no_courses_enrolled') }}</h3>
                        <p>{{ __('panel.student_not_enrolled_message') }}</p>
                    </div>
                @endif
            </div>

            <!-- Exams Panel -->
            <div class="ud-panel" id="exams">
                <div class="ud-title">{{ __('panel.exam_results') }}</div>
                
                @if($examsProgress->count() > 0)
                    <!-- Exam Stats Summary -->
                    <div class="ud-exam-summary">
                        <div class="ud-summary-card">
                            <h4>{{ $examsProgress->count() }}</h4>
                            <p>{{ __('panel.total_exams') }}</p>
                        </div>
                        <div class="ud-summary-card success">
                            <h4>{{ $examsProgress->where('passed', true)->count() }}</h4>
                            <p>{{ __('panel.passed') }}</p>
                        </div>
                        <div class="ud-summary-card danger">
                            <h4>{{ $examsProgress->where('passed', false)->count() }}</h4>
                            <p>{{ __('panel.failed') }}</p>
                        </div>
                        <div class="ud-summary-card warning">
                            <h4>{{ number_format($examsProgress->avg('percentage'), 1) }}%</h4>
                            <p>{{ __('panel.average') }}</p>
                        </div>
                    </div>

                    <!-- Exam Results List -->
                    <div class="ud-exam-results">
                        @foreach($examsProgress as $exam)
                            <div class="ud-exam-item {{ $exam['passed'] ? 'passed' : 'failed' }}">
                                <div class="ud-exam-info">
                                    <h4>{{ $exam['exam_title'] }}</h4>
                                    <p class="ud-exam-course">{{ $exam['course_title'] }}</p>
                                    <p class="ud-exam-subject">{{ $exam['subject_name'] }}</p>
                                </div>
                                
                                <div class="ud-exam-score-section">
                                    <div class="ud-exam-score-circle">
                                        <span class="score">{{ number_format($exam['percentage'], 1) }}%</span>
                                        <small>{{ $exam['correct_answers'] }}/{{ $exam['total_questions'] }}</small>
                                    </div>
                                    <div class="ud-exam-status">
                                        <span class="badge {{ $exam['passed'] ? 'badge-success' : 'badge-danger' }}">
                                            {{ $exam['passed'] ? __('panel.passed') : __('panel.failed') }}
                                        </span>
                                        <small>{{ $exam['completed_at']->format('M d, Y H:i') }}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="ud-no-content">
                        <i class="fas fa-clipboard-check"></i>
                        <h3>{{ __('panel.no_exam_attempts') }}</h3>
                        <p>{{ __('panel.student_no_exams_message') }}</p>
                    </div>
                @endif
            </div>

            <!-- Progress Panel -->
            <div class="ud-panel" id="progress">
                <div class="ud-title">{{ __('panel.learning_progress') }}</div>
                
                @if($courses->count() > 0)
                    <div class="ud-progress-overview">
                        <div class="ud-overall-progress">
                            <h3>{{ __('panel.overall_progress') }}</h3>
                            <div class="ud-progress-bar-large">
                                <div class="ud-progress-fill" style="width: {{ $courses->avg('progress') }}%"></div>
                                <span class="ud-progress-text">{{ number_format($courses->avg('progress'), 1) }}%</span>
                            </div>
                        </div>
                        
                        <div class="ud-course-progress-list">
                            @foreach($courses as $course)
                                <div class="ud-progress-item">
                                    <div class="ud-progress-info">
                                        <h4>{{ Str::limit($course['title'], 35) }}</h4>
                                        <p>{{ $course['subject_name'] }}</p>
                                    </div>
                                    <div class="ud-progress-bar">
                                        <div class="ud-progress-fill" style="width: {{ $course['progress'] }}%"></div>
                                        <span>{{ number_format($course['progress'], 1) }}%</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="ud-no-content">
                        <i class="fas fa-chart-line"></i>
                        <h3>{{ __('panel.no_progress_data') }}</h3>
                        <p>{{ __('panel.enroll_courses_to_see_progress') }}</p>
                    </div>
                @endif
            </div>

            <!-- Performance Panel -->
            <div class="ud-panel" id="performance">
                <div class="ud-title">{{ __('panel.performance_analysis') }}</div>
                
                @if($examsProgress->count() > 0)
                    <!-- Performance Metrics -->
                    <div class="ud-performance-metrics">
                        <div class="ud-metric-card">
                            <div class="ud-metric-header">
                                <i class="fas fa-chart-line"></i>
                                <h4>{{ __('panel.performance_trend') }}</h4>
                            </div>
                            <div class="ud-metric-body">
                                @php
                                    $recentExams = $examsProgress->take(5);
                                    $trend = $recentExams->count() > 1 ? 
                                        ($recentExams->first()['percentage'] > $recentExams->last()['percentage'] ? 'up' : 'down') : 'stable';
                                @endphp
                                <span class="ud-trend {{ $trend }}">
                                    <i class="fas fa-arrow-{{ $trend == 'up' ? 'up' : ($trend == 'down' ? 'down' : 'right') }}"></i>
                                    {{ ucfirst($trend) }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="ud-metric-card">
                            <div class="ud-metric-header">
                                <i class="fas fa-target"></i>
                                <h4>{{ __('panel.pass_rate') }}</h4>
                            </div>
                            <div class="ud-metric-body">
                                <span class="ud-pass-rate">
                                    {{ number_format(($examsProgress->where('passed', true)->count() / $examsProgress->count()) * 100, 1) }}%
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Subject Performance -->
                    @php
                        $subjectPerformance = $examsProgress->groupBy('subject_name')->map(function($exams) {
                            return [
                                'count' => $exams->count(),
                                'average' => $exams->avg('percentage'),
                                'passed' => $exams->where('passed', true)->count()
                            ];
                        });
                    @endphp
                    
                    @if($subjectPerformance->count() > 0)
                        <div class="ud-subject-performance">
                            <h3>{{ __('panel.performance_by_subject') }}</h3>
                            @foreach($subjectPerformance as $subject => $stats)
                                <div class="ud-subject-card">
                                    <div class="ud-subject-info">
                                        <h4>{{ $subject }}</h4>
                                        <p>{{ $stats['count'] }} {{ __('panel.exams') }}</p>
                                    </div>
                                    <div class="ud-subject-stats">
                                        <div class="ud-subject-average">
                                            <span>{{ number_format($stats['average'], 1) }}%</span>
                                            <small>{{ __('panel.average') }}</small>
                                        </div>
                                        <div class="ud-subject-passed">
                                            <span>{{ $stats['passed'] }}/{{ $stats['count'] }}</span>
                                            <small>{{ __('panel.passed') }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="ud-no-content">
                        <i class="fas fa-trophy"></i>
                        <h3>{{ __('panel.no_performance_data') }}</h3>
                        <p>{{ __('panel.complete_exams_to_see_performance') }}</p>
                    </div>
                @endif
            </div>

        </div>
    </section>
@endsection

@push('styles')
<style>

.ud-student-info-card {
    background: #fff;
    color: white;
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 30px;
}

.ud-quick-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-top: 25px;
}

.ud-stat-card {
    background: rgb(211 203 203 / 10%);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    color: #000;
}

.ud-name {
    color: #000;
}

.ud-stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
}

.ud-stat-icon.bg-primary { background: #007bff; }
.ud-stat-icon.bg-success { background: #28a745; }
.ud-stat-icon.bg-warning { background: #ffc107; }
.ud-stat-icon.bg-info { background: #17a2b8; }

.ud-stat-info h3 {
    font-size: 24px;
    font-weight: bold;
    margin: 0;
}

.ud-stat-info p {
    margin: 5px 0 0 0;
    opacity: 0.9;
    font-size: 14px;
}

.ud-best-subject {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid rgba(255,255,255,0.2);
}

.ud-achievement-badge {
    display: flex;
    align-items: center;
    gap: 15px;
    background: rgba(255,215,0,0.2);
    padding: 15px;
    border-radius: 10px;
    border-left: 4px solid #ffd700;
    color: #000;
}

.ud-achievement-badge i {
    font-size: 24px;
    color: #ffd700;
}

.ud-recent-activity {
    margin-top: 30px;
}

.ud-recent-activity h3 {
    margin-bottom: 20px;
    color: #495057;
}

.ud-activity-timeline {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.ud-activity-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
    border-left: 4px solid #dee2e6;
}

.ud-activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.ud-activity-icon.success { background: #28a745; }
.ud-activity-icon.warning { background: #ffc107; }
.ud-activity-icon.danger { background: #dc3545; }

.ud-activity-content h4 {
    margin: 0 0 5px 0;
    font-size: 14px;
}

.ud-activity-content p {
    margin: 0 0 5px 0;
    font-size: 13px;
    color: #6c757d;
}

.ud-activity-date {
    font-size: 12px;
    color: #adb5bd;
}

.ud-courses-grid{
  display:grid;
  grid-template-columns:repeat(2,minmax(0,1fr));
  gap:18px;
}

.ud-course-card{
  background:#fff;
  border:1px solid #e6e9f2;
  border-radius:16px;
  box-shadow:0 14px 30px rgba(17,24,39,.06);
  overflow:hidden;
  display:flex;
  flex-direction:column;
  transition:transform .12s ease, box-shadow .12s ease, border-color .12s ease;
}
.ud-course-card:hover{
  transform:translateY(-2px);
  border-color:#dbe3f7;
  box-shadow:0 18px 34px rgba(17,24,39,.08);
}

.ud-course-header{
  position:relative;
  aspect-ratio:16/9;
  background:#f3f5f9;
  overflow:hidden;
}
.ud-course-header img{
  width:100%;
  height:100%;
  object-fit:cover;
  display:block;
  transform:scale(1.02);
  transition:transform .25s ease;
}
.ud-course-card:hover .ud-course-header img{ transform:scale(1.06); }

.ud-course-progress-circle{
  position:absolute;
  inset:auto 12px 12px auto;
  width:66px;
  height:66px;
  border-radius:50%;
  display:grid;
  place-items:center;
}

.circular-chart{width:54px;height:54px}
.circular-chart .circle-bg{
  fill:none; stroke:#eef2f7; stroke-width:3.2;
}
.circular-chart .circle{
  fill:none; stroke:#0b57d0; stroke-width:3.2; stroke-linecap:round;
  transform:rotate(-90deg); transform-origin:50% 50%;
}
.circular-chart .percentage{
  fill:#0f172a; font-weight:800; font-size:9px; text-anchor:middle;
}

.ud-course-body{padding:14px 14px 16px; display:flex; flex-direction:column; gap:8px}
.ud-course-body h4{
  margin:0; font-size:16px; font-weight:900; color:#0f172a; line-height:1.35;
}

.ud-course-teacher,
.ud-course-subject{
  margin:0; display:flex; align-items:center; gap:8px; color:#475569; font-size:13px; font-weight:700;
}
.ud-course-teacher i,
.ud-course-subject i{font-size:12px; color:#64748b}

.ud-course-stats{
  margin-top:6px;
  display:grid; grid-template-columns:repeat(2,1fr); gap:10px;
}
.ud-course-stat{
  background:#f7f9fc;
  border:1px solid #eef2f7;
  border-radius:12px;
  padding:10px;
  text-align:center;
}
.ud-course-stat span{display:block; font-weight:900; font-size:18px; color:#0b57d0}
.ud-course-stat small{display:block; margin-top:3px; font-size:11px; color:#6b7280; font-weight:800; text-transform:uppercase; letter-spacing:.02em}

.ud-course-enrolled{
  margin-top:8px; padding-top:8px;
  border-top:1px dashed #e6e9f2;
  color:#6b7280; font-size:12px; font-weight:700;
}

@media (max-width:900px){
  .ud-courses-grid{grid-template-columns:1fr}
  .ud-course-progress-circle{width:58px;height:58px}
  .circular-chart{width:48px;height:48px}
}


.ud-course-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.ud-course-card:hover {
    transform: translateY(-5px);
}

.ud-course-header {
    position: relative;
    height: 200px;
}

.ud-course-header img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.ud-course-progress-circle {
    position: absolute;
    top: 15px;
    right: 15px;
    width: 60px;
    height: 60px;
}

.circular-chart {
    display: block;
    margin: 10px auto;
    max-width: 80%;
    max-height: 250px;
}

.circle-bg {
    fill: none;
    stroke: rgba(255,255,255,0.3);
    stroke-width: 3.8;
}

.circle {
    fill: none;
    stroke-width: 2.8;
    stroke-linecap: round;
    animation: progress 1s ease-out forwards;
    stroke: #28a745;
}

.percentage {
    fill: white;
    font-family: sans-serif;
    font-size: 0.5em;
    text-anchor: middle;
    font-weight: bold;
}

.ud-course-body {
    padding: 20px;
}

.ud-course-body h4 {
    margin: 0 0 10px 0;
    color: #2c3e50;
}

.ud-course-teacher, .ud-course-subject {
    margin: 5px 0;
    color: #6c757d;
    font-size: 14px;
}

.ud-course-stats {
    display: flex;
    justify-content: space-around;
    margin: 15px 0;
    padding: 15px 0;
    border-top: 1px solid #f1f3f4;
    border-bottom: 1px solid #f1f3f4;
}

.ud-course-stat {
    text-align: center;
}

.ud-course-stat span {
    display: block;
    font-size: 18px;
    font-weight: bold;
    color: #007bff;
}

.ud-course-stat small {
    color: #6c757d;
    font-size: 12px;
}

.ud-course-enrolled {
    margin-top: 10px;
}

.ud-course-enrolled small {
    color: #adb5bd;
    font-size: 12px;
}

.ud-exam-summary {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.ud-summary-card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.ud-summary-card.success { border-left-color: #28a745; }
.ud-summary-card.danger { border-left-color: #dc3545; }
.ud-summary-card.warning { border-left-color: #ffc107; }

.ud-summary-card h4 {
    font-size: 24px;
    font-weight: bold;
    margin: 0 0 5px 0;
    color: #2c3e50;
}

.ud-summary-card p {
    margin: 0;
    color: #6c757d;
    font-size: 14px;
}

.ud-exam-results {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.ud-exam-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background: white;
    border-radius: 10px;
    border-left: 4px solid #28a745;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.ud-exam-item.failed {
    border-left-color: #dc3545;
}

.ud-exam-info h4 {
    margin: 0 0 5px 0;
    color: #2c3e50;
}

.ud-exam-course, .ud-exam-subject {
    margin: 3px 0;
    color: #6c757d;
    font-size: 14px;
}

.ud-exam-score-section {
    display: flex;
    align-items: center;
    gap: 20px;
}

.ud-exam-score-circle {
    text-align: center;
    padding: 15px;
    border-radius: 50%;
    min-width: 80px;
}

.ud-exam-score-circle .score {
    display: block;
    font-size: 18px;
    font-weight: bold;
    color: #2c3e50;
}

.ud-exam-score-circle small {
    color: #6c757d;
    font-size: 12px;
}

.ud-exam-status {
    text-align: center;
}

.badge {
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: bold;
}

.badge-success {
    background: #d4edda;
    color: #155724;
}

.badge-danger {
    background: #f8d7da;
    color: #721c24;
}

.ud-progress-overview {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.ud-overall-progress {
    margin-bottom: 30px;
    text-align: center;
}

.ud-overall-progress h3 {
    margin-bottom: 20px;
    color: #2c3e50;
}

.ud-progress-bar-large {
    position: relative;
    height: 30px;
    background: #ffffff;
    border-radius: 15px;
    overflow: hidden;
    border: solid #e9e9e9;
}

.ud-progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #b3d7ff, #0c5ce5);
    border-radius: 15px;
    transition: width 0.6s ease;
}

.ud-progress-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #000;
    font-weight: bold;
    font-size: 14px;
}

.ud-course-progress-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.ud-progress-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px solid #f1f3f4;
}

.ud-progress-info h4 {
    margin: 0 0 5px 0;
    color: #2c3e50;
    font-size: 16px;
}

.ud-progress-info p {
    margin: 0;
    color: #6c757d;
    font-size: 14px;
}

.ud-progress-bar {
    position: relative;
    width: 200px;
    height: 8px;
    background: #f1f3f4;
    border-radius: 4px;
    overflow: hidden;
}

.ud-progress-bar .ud-progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #b3d7ff, #0c5ce5);
    border-radius: 4px;
    transition: width 0.6s ease;
}

.ud-progress-bar span {
    position: absolute;
    right: -50px;
    top: -2px;
    font-size: 12px;
    font-weight: bold;
    color: #2c3e50;
}

.ud-performance-metrics{
  display:grid !important;
  grid-template-columns:repeat(2,minmax(0,1fr)) !important;
  gap:14px !important;
}

.ud-metric-card{
  background:#fff !important;
  border:1px solid #e6e9f2 !important;
  border-radius:12px !important;
  box-shadow:0 10px 22px rgba(17,24,39,.05) !important;
  padding:12px 14px !important;
  display:flex !important;
  flex-direction:column !important;
  gap:10px !important;
}

.ud-metric-header{
  display:flex !important;
  align-items:center !important;
  gap:10px !important;
  border-bottom:1px solid #f1f5f9 !important;
  padding-bottom:8px !important;
}
.ud-metric-header i{font-size:16px !important; color:#0b57d0 !important}
.ud-metric-header h4{margin:0 !important; font-size:14px !important; font-weight:900 !important; color:#0f172a !important}

.ud-metric-body{
  display:flex !important;
  align-items:center !important;
  justify-content:space-between !important;
  min-height:44px !important;
}

.ud-trend{
  display:inline-flex !important;
  align-items:center !important;
  gap:8px !important;
  font-weight:900 !important;
  padding:8px 10px !important;
  border-radius:10px !important;
  border:1px solid #eef2f7 !important;
  background:#f8fafc !important;
  color:#0f172a !important;
}
.ud-trend.up{background:#e9f7ef !important; border-color:#c7ebd7 !important; color:#0f9d58 !important}
.ud-trend.down{background:#feeeee !important; border-color:#f6c7c3 !important; color:#d93025 !important}
.ud-trend.right{background:#f8fafc !important; border-color:#eef2f7 !important; color:#475569 !important}

.ud-pass-rate{
  display:inline-flex !important;
  align-items:center !important;
  justify-content:center !important;
  min-width:84px !important;
  padding:8px 10px !important;
  border-radius:10px !important;
  background:#eef4ff !important;
  border:1px solid #d9e6ff !important;
  color:#0b57d0 !important;
  font-weight:900 !important;
}

@media (max-width:600px){
  .ud-performance-metrics{grid-template-columns:1fr !important}
}


.ud-trend {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 8px 15px;
    border-radius: 20px;
    font-weight: bold;
    font-size: 14px;
}

.ud-trend.up {
    background: #d4edda;
    color: #155724;
}

.ud-trend.down {
    background: #f8d7da;
    color: #721c24;
}

.ud-trend.stable {
    background: #fff3cd;
    color: #856404;
}

.ud-pass-rate {
    font-size: 24px;
    font-weight: bold;
    color: #28a745;
}

.ud-subject-performance {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    margin-top: 20px;
}

.ud-subject-performance h3 {
    margin-bottom: 20px;
    color: #2c3e50;
}

.ud-subject-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px solid #f1f3f4;
}

.ud-subject-card:last-child {
    border-bottom: none;
}

.ud-subject-info h4 {
    margin: 0 0 5px 0;
    color: #2c3e50;
    font-size: 16px;
}

.ud-subject-info p {
    margin: 0;
    color: #6c757d;
    font-size: 14px;
}

.ud-subject-stats {
    display: flex;
    gap: 20px;
}

.ud-subject-average, .ud-subject-passed {
    text-align: center;
    min-width: 60px;
}

.ud-subject-average span, .ud-subject-passed span {
    display: block;
    font-size: 18px;
    font-weight: bold;
    color: #007bff;
}

.ud-subject-average small, .ud-subject-passed small {
    color: #6c757d;
    font-size: 12px;
}

.ud-no-content {
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
}

.ud-no-content i {
    font-size: 4rem;
    margin-bottom: 20px;
    opacity: 0.5;
}

.ud-no-content h3 {
    margin: 0 0 10px 0;
    color: #495057;
    font-size: 24px;
}

.ud-no-content p {
    margin: 0;
    font-size: 16px;
}

@keyframes progress {
    0% {
        stroke-dasharray: 0 100;
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .ud-quick-stats {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    
    .ud-stat-card {
        flex-direction: column;
        text-align: center;
        gap: 10px;
    }
    
    .ud-courses-grid {
        grid-template-columns: 1fr;
    }
    
    .ud-exam-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .ud-exam-score-section {
        align-self: stretch;
        justify-content: space-between;
    }
    
    .ud-progress-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .ud-progress-bar {
        width: 100%;
    }
    
    .ud-progress-bar span {
        position: static;
        margin-top: 5px;
    }
    
    .ud-subject-card {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .ud-subject-stats {
        align-self: stretch;
        justify-content: space-around;
    }
}

@media (max-width: 480px) {
    .ud-quick-stats {
        grid-template-columns: 1fr;
    }
    
    .ud-exam-summary {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .ud-performance-metrics {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Panel switching functionality
document.addEventListener('DOMContentLoaded', function() {
    const menuItems = document.querySelectorAll('.ud-item');
    const panels = document.querySelectorAll('.ud-panel');
    
    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            const target = this.getAttribute('data-target');
            
            // Remove active class from all menu items
            menuItems.forEach(menuItem => {
                menuItem.classList.remove('active');
            });
            
            // Hide all panels
            panels.forEach(panel => {
                panel.classList.remove('show');
            });
            
            // Show target panel and activate menu item
            this.classList.add('active');
            document.getElementById(target).classList.add('show');
        });
    });
    
    // Initialize progress animations
    setTimeout(() => {
        const progressBars = document.querySelectorAll('.ud-progress-fill');
        progressBars.forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0%';
            setTimeout(() => {
                bar.style.width = width;
            }, 100);
        });
    }, 500);
});

// Print functionality (optional)
function printReport() {
    window.print();
}

// Export functionality (optional)
function exportData() {
    // Implementation for exporting child data
    console.log('Export functionality can be implemented here');
}
</script>
@endpush
    