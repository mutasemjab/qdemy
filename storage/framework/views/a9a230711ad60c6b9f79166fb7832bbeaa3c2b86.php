<?php $__env->startSection('title', __('messages.dashboard')); ?>

<?php $__env->startSection('css'); ?>
<style>
.dashboard-container {
    background: #ffffff;
    min-height: 100vh;
    padding: 20px;
}

.dashboard-header {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 30px;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.dashboard-header h1 {
    color: white;
    font-weight: 700;
    margin-bottom: 10px;
}

.dashboard-header p {
    color: rgba(255, 255, 255, 0.8);
    margin: 0;
}

.stats-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
    height: 100%;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
}

.stats-card .icon {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
    margin-bottom: 20px;
}

.stats-card .icon.students { background: linear-gradient(45deg, #4facfe, #00f2fe); }
.stats-card .icon.teachers { background: linear-gradient(45deg, #43e97b, #38f9d7); }
.stats-card .icon.courses { background: linear-gradient(45deg, #fa709a, #fee140); }
.stats-card .icon.exams { background: linear-gradient(45deg, #a8edea, #fed6e3); }
.stats-card .icon.questions { background: linear-gradient(45deg, #ffecd2, #fcb69f); }
.stats-card .icon.attempts { background: linear-gradient(45deg, #667eea, #764ba2); }
.stats-card .icon.notifications { background: linear-gradient(45deg, #f093fb, #f5576c); }
.stats-card .icon.performance { background: linear-gradient(45deg, #4facfe, #00f2fe); }

.stats-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2d3436;
    margin-bottom: 5px;
}

.stats-label {
    color: #636e72;
    font-weight: 500;
    margin-bottom: 10px;
}

.stats-change {
    font-size: 0.875rem;
    font-weight: 600;
}

.stats-change.positive {
    color: #00b894;
}

.stats-change.negative {
    color: #e17055;
}

.chart-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    margin-bottom: 20px;
}

.activity-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    height: 400px;
    overflow-y: auto;
}

.activity-item {
    display: flex;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px solid #f1f3f4;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    font-size: 16px;
    color: white;
}

.activity-icon.exam { background: linear-gradient(45deg, #667eea, #764ba2); }
.activity-icon.student { background: linear-gradient(45deg, #4facfe, #00f2fe); }
.activity-icon.notification { background: linear-gradient(45deg, #f093fb, #f5576c); }

.progress-bar-custom {
    height: 8px;
    border-radius: 10px;
    background: #f1f3f4;
    overflow: hidden;
    margin-top: 10px;
}

.progress-fill {
    height: 100%;
    border-radius: 10px;
    transition: width 0.5s ease;
}

.progress-fill.students { background: linear-gradient(45deg, #4facfe, #00f2fe); }
.progress-fill.teachers { background: linear-gradient(45deg, #43e97b, #38f9d7); }
.progress-fill.exams { background: linear-gradient(45deg, #a8edea, #fed6e3); }



.top-student-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #f1f3f4;
}

.top-student-item:last-child {
    border-bottom: none;
}

.student-rank {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    color: white;
    margin-right: 15px;
}

.student-rank.first { background: #ffd700; }
.student-rank.second { background: #c0c0c0; }
.student-rank.third { background: #cd7f32; }
.student-rank.other { background: #95a5a6; }

.welcome-time {
    color: rgba(255, 255, 255, 0.9);
    font-size: 1.1rem;
}

@media (max-width: 768px) {
    .dashboard-container {
        padding: 10px;
    }

    .stats-number {
        font-size: 2rem;
    }

    .activity-card {
        height: 300px;
    }
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contentheaderlink'); ?>
<a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('messages.dashboard')); ?></a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contentheaderactive'); ?>
<?php echo e(__('messages.overview')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-container">
    <!-- Dashboard Header -->

    <!-- Main Statistics Grid -->
    <div class="row mb-4">
        <!-- Students Statistics -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="icon students">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="stats-number"><?php echo e(number_format($totalStudents)); ?></div>
                <div class="stats-label"><?php echo e(__('messages.total_students')); ?></div>
                <div class="stats-change positive">
                    <i class="fas fa-arrow-up mr-1"></i>
                    +<?php echo e($newStudentsThisMonth); ?> <?php echo e(__('messages.this_month')); ?>

                </div>
                <div class="progress-bar-custom">
                    <div class="progress-fill students" style="width: <?php echo e($totalStudents > 0 ? ($activeStudents / $totalStudents) * 100 : 0); ?>%"></div>
                </div>
                <small class="text-muted mt-2 d-block"><?php echo e($activeStudents); ?>/<?php echo e($totalStudents); ?> <?php echo e(__('messages.active')); ?></small>
            </div>
        </div>

        <!-- Teachers Statistics -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="icon teachers">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="stats-number"><?php echo e(number_format($totalTeachers)); ?></div>
                <div class="stats-label"><?php echo e(__('messages.total_teachers')); ?></div>
                <div class="stats-change positive">
                    <i class="fas fa-check-circle mr-1"></i>
                    <?php echo e(number_format($activeUserRate, 1)); ?>% <?php echo e(__('messages.active_rate')); ?>

                </div>
                <div class="progress-bar-custom">
                    <div class="progress-fill teachers" style="width: <?php echo e($totalTeachers > 0 ? ($activeTeachers / $totalTeachers) * 100 : 0); ?>%"></div>
                </div>
                <small class="text-muted mt-2 d-block"><?php echo e($activeTeachers); ?>/<?php echo e($totalTeachers); ?> <?php echo e(__('messages.active')); ?></small>
            </div>
        </div>

        <!-- Courses Statistics -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="icon courses">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stats-number"><?php echo e(number_format($totalCourses)); ?></div>
                <div class="stats-label"><?php echo e(__('messages.total_courses')); ?></div>
                <div class="stats-change positive">
                    <i class="fas fa-graduation-cap mr-1"></i>
                    <!--  'coursesWithExams'  <?php echo e(__('messages.with_exams')); ?> -->
                </div>
                <div class="progress-bar-custom">
                    <div class="progress-fill exams" style="width: <?php echo e($totalCourses > 0 ? ($activeCourses / $totalCourses) * 100 : 0); ?>%"></div>
                </div>
                <small class="text-muted mt-2 d-block"><?php echo e($activeCourses); ?>/<?php echo e($totalCourses); ?> <?php echo e(__('messages.active')); ?></small>
            </div>
        </div>

        <!-- Exams Statistics -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="icon exams">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div class="stats-number"><?php echo e(number_format($totalExams)); ?></div>
                <div class="stats-label"><?php echo e(__('messages.total_exams')); ?></div>
                <div class="stats-change positive">
                    <i class="fas fa-chart-line mr-1"></i>
                    <?php echo e(number_format($passRate, 1)); ?>% <?php echo e(__('messages.pass_rate')); ?>

                </div>
                <div class="progress-bar-custom">
                    <div class="progress-fill exams" style="width: <?php echo e($totalExams > 0 ? ($activeExams / $totalExams) * 100 : 0); ?>%"></div>
                </div>
                <small class="text-muted mt-2 d-block"><?php echo e($activeExams); ?>/<?php echo e($totalExams); ?> <?php echo e(__('messages.active')); ?></small>
            </div>
        </div>
    </div>

    <!-- Secondary Statistics Grid -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="icon questions">
                    <i class="fas fa-question-circle"></i>
                </div>
                <div class="stats-number"><?php echo e(number_format($totalQuestions)); ?></div>
                <div class="stats-label"><?php echo e(__('messages.total_questions')); ?></div>
                <small class="text-muted">
                    MC: <?php echo e($multipleChoiceQuestions); ?> | T/F: <?php echo e($trueFalseQuestions); ?> | Essay: <?php echo e($essayQuestions); ?>

                </small>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="icon attempts">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="stats-number"><?php echo e(number_format($totalAttempts)); ?></div>
                <div class="stats-label"><?php echo e(__('messages.exam_attempts')); ?></div>
                <div class="stats-change positive">
                    <i class="fas fa-trophy mr-1"></i>
                    <?php echo e(number_format($averageScore, 1)); ?>% <?php echo e(__('messages.avg_score')); ?>

                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="icon notifications">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="stats-number"><?php echo e(number_format($totalNotifications)); ?></div>
                <div class="stats-label"><?php echo e(__('messages.notifications')); ?></div>
                <div class="stats-change <?php echo e($unreadNotifications > 0 ? 'negative' : 'positive'); ?>">
                    <i class="fas fa-envelope mr-1"></i>
                    <?php echo e($unreadNotifications); ?> <?php echo e(__('messages.unread')); ?>

                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stats-card">
                <div class="icon performance">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <div class="stats-number"><?php echo e(number_format($examCompletionRate, 1)); ?>%</div>
                <div class="stats-label"><?php echo e(__('messages.completion_rate')); ?></div>
                <div class="stats-change positive">
                    <i class="fas fa-play mr-1"></i>
                    <?php echo e($inProgressAttempts); ?> <?php echo e(__('messages.in_progress')); ?>

                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Activities Row -->
    <div class="row mb-4">
        <!-- Monthly Students Chart -->
         <div class="col-lg-6 mb-4">
            <div class="activity-card">
                <h5 class="mb-4">
                    <i class="fas fa-clock mr-2"></i>
                    <?php echo e(__('messages.recent_exam_attempts')); ?>

                </h5>
                <?php if($recentExamAttempts->count() > 0): ?>
                    <?php $__currentLoopData = $recentExamAttempts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attempt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="activity-item">
                        <div class="activity-icon exam">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <div class="flex-grow-1">
                            <strong><?php echo e($attempt->user->name); ?></strong>
                            <p class="mb-1 text-muted"><?php echo e(Str::limit($attempt->exam->title_en, 30)); ?></p>
                            <small class="text-muted">
                                <?php echo e($attempt->submitted_at->diffForHumans()); ?> -
                                <span class="text-<?php echo e($attempt->is_passed ? 'success' : 'danger'); ?>">
                                    <?php echo e(number_format($attempt->percentage, 1)); ?>%
                                </span>
                            </small>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-clipboard-list fa-3x mb-3"></i>
                        <p><?php echo e(__('messages.no_recent_attempts')); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Top Students -->
        <div class="col-lg-6 mb-4">
            <div class="activity-card">
                <h5 class="mb-4">
                    <i class="fas fa-medal mr-2"></i>
                    <?php echo e(__('messages.top_students')); ?>

                </h5>
                <?php if($topStudents->count() > 0): ?>
                    <?php $__currentLoopData = $topStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="top-student-item">
                        <div class="d-flex align-items-center">
                            <div class="student-rank <?php echo e($index == 0 ? 'first' : ($index == 1 ? 'second' : ($index == 2 ? 'third' : 'other'))); ?>">
                                <?php echo e($index + 1); ?>

                            </div>
                            <div>
                                <strong><?php echo e($student->name); ?></strong>
                                <small class="d-block text-muted"><?php echo e($student->email); ?></small>
                            </div>
                        </div>
                        <div class="text-right">
                            <strong class="text-success"><?php echo e(number_format($student->avg_score, 1)); ?>%</strong>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-graduation-cap fa-3x mb-3"></i>
                        <p><?php echo e(__('messages.no_exam_data_yet')); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Recent Activities Row -->
    <div class="row mb-4">
        <!-- Recent Exam Attempts -->


        <!-- Recent Students -->
        <div class="col-lg-6 mb-4">
            <div class="activity-card">
                <h5 class="mb-4">
                    <i class="fas fa-user-plus mr-2"></i>
                    <?php echo e(__('messages.new_students')); ?>

                </h5>
                <?php if($recentStudents->count() > 0): ?>
                    <?php $__currentLoopData = $recentStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="activity-item">
                        <div class="activity-icon student">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="flex-grow-1">
                            <strong><?php echo e($student->name); ?></strong>
                            <p class="mb-1 text-muted"><?php echo e($student->email); ?></p>
                            <small class="text-muted">
                                <?php echo e(__('messages.joined')); ?> <?php echo e($student->created_at->diffForHumans()); ?>

                            </small>
                        </div>
                        <div class="text-right">
                            <span class="badge badge-<?php echo e($student->is_active ? 'success' : 'secondary'); ?>">
                                <?php echo e($student->is_active ? __('messages.active') : __('messages.inactive')); ?>

                            </span>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-user-plus fa-3x mb-3"></i>
                        <p><?php echo e(__('messages.no_new_students')); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recent Exams -->
        <div class="col-lg-6 mb-4">
            <div class="activity-card">
                <h5 class="mb-4">
                    <i class="fas fa-plus-circle mr-2"></i>
                    <?php echo e(__('messages.recent_exams')); ?>

                </h5>
                <?php if($recentExams->count() > 0): ?>
                    <?php $__currentLoopData = $recentExams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="activity-item">
                        <div class="activity-icon exam">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="flex-grow-1">
                            <strong><?php echo e(Str::limit($exam->title_en, 25)); ?></strong>
                            <p class="mb-1 text-muted"><?php echo e($exam->course->title_en ?? 'No Course'); ?></p>
                            <small class="text-muted">
                                <?php echo e(__('messages.created')); ?> <?php echo e($exam->created_at->diffForHumans()); ?>

                            </small>
                        </div>
                        <div class="text-right">
                            <span class="badge badge-<?php echo e($exam->is_active ? 'success' : 'secondary'); ?>">
                                <?php echo e($exam->is_active ? __('messages.active') : __('messages.inactive')); ?>

                            </span>
                            <small class="d-block text-muted"><?php echo e($exam->questions->count()); ?> <?php echo e(__('messages.questions')); ?></small>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-plus-circle fa-3x mb-3"></i>
                        <p><?php echo e(__('messages.no_recent_exams')); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Additional Charts Row -->
    <div class="row">
        <!-- Pass Rate by Exam -->
        <div class="col-lg-6 mb-4">
            <div class="chart-card">
                <h5 class="mb-4">
                    <i class="fas fa-chart-bar mr-2"></i>
                    <?php echo e(__('messages.pass_rate_by_exam')); ?>

                </h5>
                <canvas id="passRateChart" height="200"></canvas>
            </div>
        </div>

        <!-- Question Type Distribution -->
        <div class="col-lg-6 mb-4">
            <div class="chart-card">
                <h5 class="mb-4">
                    <i class="fas fa-pie-chart mr-2"></i>
                    <?php echo e(__('messages.question_type_distribution')); ?>

                </h5>
                <canvas id="questionTypeChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="chart-card">
                <h5 class="mb-4">
                    <i class="fas fa-bolt mr-2"></i>
                    <?php echo e(__('messages.quick_actions')); ?>

                </h5>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="<?php echo e(route('users.create')); ?>" class="btn btn-primary btn-block btn-lg">
                            <i class="fas fa-user-plus mr-2"></i>
                            <?php echo e(__('messages.add_student')); ?>

                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="<?php echo e(route('courses.create')); ?>" class="btn btn-success btn-block btn-lg">
                            <i class="fas fa-book-plus mr-2"></i>
                            <?php echo e(__('messages.add_course')); ?>

                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="<?php echo e(route('exams.create')); ?>" class="btn btn-warning btn-block btn-lg">
                            <i class="fas fa-clipboard-check mr-2"></i>
                            <?php echo e(__('messages.create_exam')); ?>

                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="<?php echo e(route('notifications.create')); ?>" class="btn btn-info btn-block btn-lg">
                            <i class="fas fa-bell mr-2"></i>
                            <?php echo e(__('messages.send_notification')); ?>

                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    // Monthly Students Chart
    const monthlyStudentsData = <?php echo json_encode($monthlyStudents, 15, 512) ?>;
    const ctx1 = document.getElementById('monthlyStudentsChart').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: monthlyStudentsData.map(item => item.month),
            datasets: [{
                label: '<?php echo e(__("messages.new_students")); ?>',
                data: monthlyStudentsData.map(item => item.count),
                borderColor: 'rgba(79, 172, 254, 1)',
                backgroundColor: 'rgba(79, 172, 254, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: 'rgba(79, 172, 254, 1)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    },
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Pass Rate Chart
    const passRateData = <?php echo json_encode($passRateByExam, 15, 512) ?>;
    if (passRateData.length > 0) {
        const ctx2 = document.getElementById('passRateChart').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: passRateData.map(item => item.title_en.substring(0, 20) + '...'),
                datasets: [{
                    label: '<?php echo e(__("messages.pass_rate")); ?> (%)',
                    data: passRateData.map(item => item.pass_rate),
                    backgroundColor: [
                        'rgba(79, 172, 254, 0.8)',
                        'rgba(67, 233, 123, 0.8)',
                        'rgba(247, 112, 154, 0.8)',
                        'rgba(255, 159, 64, 0.8)',
                        'rgba(153, 102, 255, 0.8)',
                        'rgba(255, 205, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 206, 86, 0.8)'
                    ],
                    borderColor: [
                        'rgba(79, 172, 254, 1)',
                        'rgba(67, 233, 123, 1)',
                        'rgba(247, 112, 154, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 205, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)'
                    ],
                    borderWidth: 2,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    } else {
        // Show message when no pass rate data
        document.getElementById('passRateChart').style.display = 'none';
        const passRateContainer = document.getElementById('passRateChart').parentElement;
        passRateContainer.innerHTML += '<div class="text-center text-muted py-4"><i class="fas fa-chart-bar fa-3x mb-3"></i><p><?php echo e(__("messages.no_pass_rate_data")); ?></p></div>';
    }

    // Question Type Distribution Chart
    const questionTypeData = <?php echo json_encode($questionTypeDistribution, 15, 512) ?>;
    const ctx3 = document.getElementById('questionTypeChart').getContext('2d');
    new Chart(ctx3, {
        type: 'doughnut',
        data: {
            labels: [
                '<?php echo e(__("messages.multiple_choice")); ?>',
                '<?php echo e(__("messages.true_false")); ?>',
                '<?php echo e(__("messages.essay")); ?>'
            ],
            datasets: [{
                data: [
                    questionTypeData.multiple_choice,
                    questionTypeData.true_false,
                    questionTypeData.essay
                ],
                backgroundColor: [
                    'rgba(79, 172, 254, 0.8)',
                    'rgba(67, 233, 123, 0.8)',
                    'rgba(247, 112, 154, 0.8)'
                ],
                borderColor: [
                    'rgba(79, 172, 254, 1)',
                    'rgba(67, 233, 123, 1)',
                    'rgba(247, 112, 154, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                }
            }
        }
    });

    // Auto-refresh dashboard data every 5 minutes
    setInterval(function() {
        // Only refresh if user is still on the page and tab is active
        if (!document.hidden) {
            location.reload();
        }
    }, 300000);

    // Add hover effects to stats cards
    document.querySelectorAll('.stats-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(-5px)';
        });
    });

    // Animate numbers on page load
    function animateNumbers() {
        const numbers = document.querySelectorAll('.stats-number');
        numbers.forEach(number => {
            const targetText = number.textContent;
            const target = parseInt(targetText.replace(/[,%]/g, ''));

            // Skip if not a number
            if (isNaN(target)) return;

            let current = 0;
            const increment = target / 100;
            const isPercentage = targetText.includes('%');

            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }

                let displayValue = Math.floor(current).toLocaleString();
                if (isPercentage) {
                    displayValue += '%';
                }
                number.textContent = displayValue;
            }, 20);
        });
    }

    // Start number animation after a delay
    setTimeout(animateNumbers, 500);

    // Add loading states for charts
    function showChartLoading(chartId) {
        const canvas = document.getElementById(chartId);
        const container = canvas.parentElement;
        const loadingDiv = document.createElement('div');
        loadingDiv.className = 'text-center py-4';
        loadingDiv.innerHTML = '<i class="fas fa-spinner fa-spin fa-2x text-primary"></i><p class="mt-2 text-muted"><?php echo e(__("messages.loading_chart")); ?></p>';
        container.insertBefore(loadingDiv, canvas);
        canvas.style.display = 'none';

        // Remove loading after 2 seconds
        setTimeout(() => {
            loadingDiv.remove();
            canvas.style.display = 'block';
        }, 2000);
    }

    // Progress bar animations
    function animateProgressBars() {
        const progressBars = document.querySelectorAll('.progress-fill');
        progressBars.forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0%';
            setTimeout(() => {
                bar.style.width = width;
            }, 1000);
        });
    }

    // Start progress bar animation
    setTimeout(animateProgressBars, 1000);

    // Add click handlers for quick actions
    document.querySelectorAll('.btn-lg').forEach(btn => {
        btn.addEventListener('click', function(e) {
            // Add ripple effect
            const ripple = document.createElement('span');
            ripple.style.position = 'absolute';
            ripple.style.borderRadius = '50%';
            ripple.style.background = 'rgba(255,255,255,0.3)';
            ripple.style.transform = 'scale(0)';
            ripple.style.animation = 'ripple 0.6s linear';
            ripple.style.left = e.offsetX + 'px';
            ripple.style.top = e.offsetY + 'px';
            ripple.style.width = ripple.style.height = '20px';

            this.style.position = 'relative';
            this.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });

    // Add CSS for ripple animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        .stats-card {
            transform: translateY(20px);
            opacity: 0;
            animation: slideInUp 0.6s ease-out forwards;
        }

        @keyframes slideInUp {
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    `;
    document.head.appendChild(style);

    // Stagger animation for stats cards
    document.querySelectorAll('.stats-card').forEach((card, index) => {
        card.style.animationDelay = (index * 0.1) + 's';
    });

    // Real-time clock update
    function updateClock() {
        const now = new Date();
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        };

        const timeElement = document.querySelector('.welcome-time');
        if (timeElement) {
            const timeString = now.toLocaleDateString('en-US', options);
            timeElement.innerHTML = '<i class="fas fa-clock mr-2"></i>' + timeString;
        }
    }

    // Update clock every second
    setInterval(updateClock, 1000);

    // Notification for low activity
    function checkActivity() {
        const totalStudents = <?php echo e($totalStudents); ?>;
        const activeStudents = <?php echo e($activeStudents); ?>;
        const activeRate = totalStudents > 0 ? (activeStudents / totalStudents) * 100 : 0;

        if (activeRate < 50 && totalStudents > 0) {
            console.warn('Low student activity detected: ' + activeRate.toFixed(1) + '%');
        }
    }

    checkActivity();

    // Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + R for refresh
        if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
            e.preventDefault();
            location.reload();
        }

        // Ctrl/Cmd + D for dashboard (already on dashboard, so just scroll to top)
        if ((e.ctrlKey || e.metaKey) && e.key === 'd') {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });

    console.log('Dashboard loaded successfully!');
    console.log('Statistics:', {
        students: <?php echo e($totalStudents); ?>,
        teachers: <?php echo e($totalTeachers); ?>,
        courses: <?php echo e($totalCourses); ?>,
        exams: <?php echo e($totalExams); ?>,
        questions: <?php echo e($totalQuestions); ?>,
        attempts: <?php echo e($totalAttempts); ?>

    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>