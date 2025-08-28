<?php $__env->startSection('title', $exam->title); ?>

<?php $__env->startSection('content'); ?>

<section class="cmty-page">
    <div class="universities-header-wrapper">
        <div class="universities-header">
            <h2><?php echo e($exam->title); ?></h2>
        </div>
    </div>

    <div class="cmty-feed">
        <?php echo $__env->make('web.alert-message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <!-- Exam Info Bar -->
        <div class="examx-row">
            <div class="examx-dropdown">
                <button class="examx-pill">
                    <span>عدد المحاولات المسموحة: <?php echo e($exam->attempts_allowed); ?></span>
                </button>
            </div>
            <div class="examx-dropdown">
                <button class="examx-pill">
                    <span>الدرجة الكلية: <?php echo e($exam->total_grade); ?></span>
                </button>
            </div>
            <div class="examx-dropdown">
                <button class="examx-pill">
                    <span>المدة: <?php echo e($exam->duration_minutes); ?> دقيقة</span>
                </button>
            </div>
            <?php if($current_attempt && $current_attempt->remaining_time): ?>
                <div class="examx-dropdown">
                    <button class="examx-pill" style="background-color: #ff6b6b; color: white;">
                        <span id="timer">الوقت المتبقي: <?php echo e($current_attempt->remaining_time); ?> دقيقة</span>
                    </button>
                </div>
            <?php endif; ?>
        </div>

        <!-- Progress Bar -->
        <?php if($current_attempt): ?>
            <div class="examx-progress" style="margin: 20px 0;">
                <div style="background: #f0f0f0; border-radius: 10px; padding: 3px;">
                    <div style="background: #4CAF50; height: 20px; border-radius: 7px; width: <?php echo e($current_attempt->progress); ?>%; transition: width 0.3s;"></div>
                </div>
                <small>تم الإجابة على <?php echo e($current_attempt->answers->count()); ?> من <?php echo e(count($exam->questions)); ?> سؤال</small>
            </div>
        <?php endif; ?>

        <!-- Exam Introduction (shown before starting or on first question) -->
        <?php if(!$current_attempt || $question_nm == 1): ?>
            <article class="cmty-post cmty-post--outlined">
                <header class="cmty-head">
                    <?php echo e($exam->course?->title); ?>

                    <img class="cmty-mark" data-src="<?php echo e(asset('assets_front/images/community-logo1.png')); ?>" alt="">
                </header>
                <p class="cmty-text">
                    <?php echo e($exam->description); ?>

                </p>
                <div style="margin: 15px 0;">
                    <span>المدة: <?php echo e($exam->duration_minutes); ?> دقيقة</span> -
                    <?php if($exam->attempts->count()): ?>
                      <span> المحاولات السابقة: <?php echo e($exam->attempts->count()); ?></span>
                    <?php endif; ?>
                </div>
                <div>
                    <span>الدرجة الكلية: <?php echo e($exam->total_grade); ?></span> -
                    <span>درجة النجاح: <?php echo e($exam->passing_grade); ?>%</span>
                </div>

                <?php if(!$result && !$current_attempts->count() && $can_add_attempt): ?>
                    <div class="cmty-actions">
                        <form action="<?php echo e(route('start.exam',['exam'=>$exam->id,'slug'=>$exam->slug])); ?>" method='post'>
                            <?php echo csrf_field(); ?>
                            <button type='submit' class="cmty-like">
                                <?php if($attempts->count()): ?> محاولة جديدة <?php else: ?> بدء الامتحان <?php endif; ?>
                            </button>
                        </form>
                    </div>
                <?php endif; ?>
            </article>
        <?php endif; ?>

        <!-- Results Display -->
        <?php if($result && !$current_attempt && $question_nm == 1): ?>
            <article class="cmty-post cmty-post--outlined">
                <header class="cmty-head">
                    نتيجة الامتحان - <?php echo $result->passed(); ?>

                    <img class="cmty-mark" data-src="<?php echo e(asset('assets_front/images/community-logo1.png')); ?>" alt="">
                </header>

                <div style="margin: 15px 0;">
                    <span>بدأ في: <?php echo e($result->started_at->format('Y-m-d H:i')); ?></span><br>
                    <span>انتهى في: <?php echo e($result->submitted_at->format('Y-m-d H:i')); ?></span><br>
                    <span>المدة المستغرقة: <?php echo e($result->duration); ?> دقيقة</span>
                </div>

                <div style="margin: 15px 0;">
                    <span>النتيجة: <?php echo e($result->score); ?>/<?php echo e($exam->total_grade); ?></span><br>
                    <span>النسبة المئوية: <?php echo e($result->percentage); ?>%</span><br>
                    <span>الحالة: <?php echo $result->passed(); ?></span>
                </div>

                <?php if($exam->show_results_immediately): ?>
                    <div class="cmty-actions">
                        <a href="<?php echo e(route('review.attempt', ['exam' => $exam->id, 'attempt' => $result->id])); ?>" class="cmty-like">مراجعة الإجابات</a>
                    </div>
                <?php endif; ?>

                <?php if(!$current_attempts->count() && $can_add_attempt): ?>
                <div class="cmty-actions">
                    <form action="<?php echo e(route('start.exam',['exam'=>$exam->id,'slug'=>$exam->slug])); ?>" method='post'>
                        <?php echo csrf_field(); ?>
                        <button type='submit' class="cmty-like">محاولة جديدة</button>
                    </form>
                </div>
                <?php endif; ?>

            </article>
        <?php endif; ?>

        <!-- Current Question Display -->
        <?php if($current_attempt && $question): ?>
            <article class="cmty-post cmty-post--outlined">
                <header class="cmty-head">
                    <div class="cmty-user">
                        <div>
                            <h4>السؤال <?php echo e($question_nm); ?>: <?php echo e($question->title); ?></h4>
                        </div>
                    </div>
                    <img class="cmty-mark" data-src="<?php echo e(asset('assets_front/images/community-logo1.png')); ?>" alt="">
                </header>

                <p class="cmty-text">
                    <?php echo e($question->question); ?>

                </p>

                <?php if($question->explanation): ?>
                    <div style="margin: 10px 0; padding: 10px; background: #f9f9f9; border-radius: 5px;">
                        <small><strong>ملاحظة:</strong> <?php echo e($question->explanation); ?></small>
                    </div>
                <?php endif; ?>

                <div style="margin: 10px 0;">
                    <span>رقم السؤال: <?php echo e($question_nm); ?></span> |
                    <span>الدرجة: <?php echo e($question->grade); ?></span>
                </div>

                <form action="<?php echo e(route('answer.question',['exam'=>$exam->id,'question'=>$question->id])); ?>" method='post'>
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="page" value="<?php echo e($question_nm); ?>">

                    <?php if($question->type === 'essay'): ?>
                        <div style="margin: 20px 0;">
                            <label>إجابتك:</label>
                            <textarea name='answer' class="cmty-input" rows="5" placeholder="اكتب إجابتك هنا..." required></textarea>
                        </div>

                    <?php elseif($question->type === 'true_false'): ?>
                        <div style="margin: 20px 0;">
                            <div style="margin: 10px 0;">
                                <input type="radio" id="answer_true" name='answer' value='true' required>
                                <label for="answer_true" style="margin-right: 10px;">صحيح</label>
                            </div>
                            <div style="margin: 10px 0;">
                                <input type="radio" id="answer_false" name='answer' value='false' required>
                                <label for="answer_false" style="margin-right: 10px;">خطأ</label>
                            </div>
                        </div>

                    <?php elseif($question->type === 'multiple_choice'): ?>
                        <div style="margin: 20px 0;">
                            <?php
                                $multiple_choices = $exam->shuffle_options
                                    ? $question->getShuffledOptions()
                                    : $question->options()->orderBy('order','asc')->get();
                            ?>

                            <?php $__currentLoopData = $multiple_choices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div style="margin: 10px 0;">
                                    <input type="checkbox" name="answer[]" id="option_<?php echo e($option->id); ?>" value="<?php echo e($option->id); ?>">
                                    <label for="option_<?php echo e($option->id); ?>" style="margin-right: 10px;"><?php echo e($option->option); ?></label>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>

                    <div class="cmty-actions" style="margin: 20px 0;">
                        <button type='submit' class="cmty-like">حفظ الإجابة والمتابعة</button>

                        <?php if($question_nm > 1): ?>
                            <a href="<?php echo e(route('exam', ['exam' => $exam->id, 'slug' => $exam->slug, 'page' => $question_nm - 1])); ?>"
                               class="cmty-like" style="background: #6c757d; margin-right: 10px;">السؤال السابق</a>
                        <?php endif; ?>
                    </div>
                </form>

                <!-- Finish Exam Button -->
                <?php if($current_attempt->answers->count() > 0): ?>
                    <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee;">
                        <form action="<?php echo e(route('finish.exam',['exam'=>$exam->id])); ?>" method='post' onsubmit="return confirm('هل أنت متأكد من تسليم الامتحان؟ لن تتمكن من التراجع.')">
                            <?php echo csrf_field(); ?>
                            <button type='submit' class="cmty-like" style="background: #dc3545;">تسليم الامتحان</button>
                        </form>
                    </div>
                <?php endif; ?>
            </article>
        <?php endif; ?>

        <!-- Navigation -->
        <?php if($current_attempt && $questions): ?>
        <div class="pagination-wrapper">
        <?php echo e($questions?->links('pagination::custom-bootstrap-5') ?? ''); ?>

        </div>
        <?php endif; ?>

        <!-- Exam History -->
        <?php if($attempts->where('status', 'completed')->count() > 0): ?>
            <article class="cmty-post cmty-post--outlined">
                <header class="cmty-head">
                    تاريخ المحاولات السابقة
                </header>

                <div style="margin: 15px 0;">
                    <?php $__currentLoopData = $attempts->where('status', 'completed'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attempt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div style="padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px;">
                            <div>
                                <strong>
                                    <a href="<?php echo e(route('review.attempt',['exam'=>$exam->id,'attempt'=>$attempt->id])); ?>">المحاولة <?php echo e($loop->iteration); ?></a>
                                </strong> -
                                <span><?php echo e($attempt->submitted_at->format('Y-m-d H:i')); ?></span>
                            </div>
                            <div>
                                النتيجة: <?php echo e($attempt->score); ?>/<?php echo e($exam->total_grade); ?>

                                (<?php echo e($attempt->percentage); ?>%) -
                                <?php echo $attempt->passed(); ?>

                            </div>
                            <?php if($exam->show_results_immediately): ?>
                                <div style="margin-top: 10px;">
                                    <a href="<?php echo e(route('review.attempt', ['exam' => $exam->id, 'attempt' => $attempt->id])); ?>"
                                       class="cmty-like" style="font-size: 12px; padding: 5px 10px;">مراجعة</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </article>
        <?php endif; ?>

        <!-- Warning Messages -->
        <?php if(!$can_add_attempt && !$current_attempt): ?>
            <article class="cmty-post cmty-post--outlined" style="border-color: #dc3545;">
                <div style="color: #dc3545; text-align: center; padding: 20px;">
                    <strong>لقد استنفدت عدد المحاولات المسموحة (<?php echo e($exam->attempts_allowed); ?>)</strong>
                </div>
            </article>
        <?php endif; ?>

        <?php if(!$exam->is_available()): ?>
            <article class="cmty-post cmty-post--outlined" style="border-color: #ffc107;">
                <div style="color: #856404; text-align: center; padding: 20px;">
                    <strong>الامتحان غير متاح حاليا</strong>
                    <?php if($exam->start_date && now() < $exam->start_date): ?>
                        <br>يبدأ في: <?php echo e($exam->start_date->format('Y-m-d H:i')); ?>

                    <?php elseif($exam->end_date && now() > $exam->end_date): ?>
                        <br>انتهى في: <?php echo e($exam->end_date->format('Y-m-d H:i')); ?>

                    <?php endif; ?>
                </div>
            </article>
        <?php endif; ?>
    </div>
</section>

<?php if($current_attempt && $current_attempt->remaining_time): ?>
<script>
// Timer countdown
let remainingMinutes = <?php echo e($current_attempt->remaining_time); ?>;
const timerElement = document.getElementById('timer');

if (timerElement && remainingMinutes > 0) {
    const updateTimer = () => {
        if (remainingMinutes <= 0) {
            timerElement.textContent = 'انتهى الوقت!';
            timerElement.style.background = '#dc3545';

            // Auto submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?php echo e(route("finish.exam", ["exam" => $exam->id])); ?>';

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '<?php echo e(csrf_token()); ?>';
            form.appendChild(csrfToken);

            document.body.appendChild(form);
            form.submit();
            return;
        }

        const hours = Math.floor(remainingMinutes / 60);
        const minutes = remainingMinutes % 60;

        if (hours > 0) {
            timerElement.textContent = `الوقت المتبقي: ${hours}:${minutes.toString().padStart(2, '0')} ساعة`;
        } else {
            timerElement.textContent = `الوقت المتبقي: ${minutes} دقيقة`;
        }

        // Warning colors
        if (remainingMinutes <= 5) {
            timerElement.style.background = '#dc3545';
            timerElement.style.animation = 'blink 1s infinite';
        } else if (remainingMinutes <= 15) {
            timerElement.style.background = '#ffc107';
        }

        remainingMinutes--;
    };

    updateTimer();
    setInterval(updateTimer, 60000); // Update every minute
}

// Add blinking animation
const style = document.createElement('style');
style.textContent = `
    @keyframes blink {
        0%, 50% { opacity: 1; }
        51%, 100% { opacity: 0.5; }
    }
`;
document.head.appendChild(style);
</script>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/web/exam/exam.blade.php ENDPATH**/ ?>