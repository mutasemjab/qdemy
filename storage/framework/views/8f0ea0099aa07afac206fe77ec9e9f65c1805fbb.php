

<?php $__env->startSection('title', 'مراجعة الامتحان - ' . $exam->title); ?>

<?php $__env->startSection('content'); ?>
<section class="cmty-page">
    <div class="universities-header-wrapper">
        <div class="universities-header">
            <h2>مراجعة الامتحان: <?php echo e($exam->title); ?></h2>
        </div>
    </div>

    <div class="cmty-feed">

        <!-- Exam Summary -->
        <article class="cmty-post cmty-post--outlined">
            <header class="cmty-head">
                ملخص النتائج - <?php echo $attempt->passed(); ?>

                <img class="cmty-mark" data-src="<?php echo e(asset('assets_front/images/community-logo1.png')); ?>" alt="">
            </header>

            <div style="margin: 15px 0;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                    <div>
                        <strong>تاريخ الامتحان:</strong><br>
                        <?php echo e($attempt->started_at->format('Y-m-d H:i')); ?>

                    </div>
                    <div>
                        <strong>المدة المستغرقة:</strong><br>
                        <?php echo e($attempt->duration); ?> دقيقة
                    </div>
                    <div>
                        <strong>النتيجة:</strong><br>
                        <?php echo e($attempt->score); ?>/<?php echo e($exam->total_grade); ?>

                    </div>
                    <div>
                        <strong>النسبة المئوية:</strong><br>
                        <?php echo e($attempt->percentage); ?>%
                    </div>
                </div>
            </div>

            <div class="cmty-actions">
                <a href="<?php echo e(route('exam', ['exam' => $exam->id, 'slug' => $exam->slug])); ?>" class="cmty-like">العودة للامتحان</a>
            </div>
        </article>

        <!-- Questions Review -->
        <?php $__currentLoopData = $answers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $answer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $question = $answer->question;
                $is_correct = $answer->is_correct;
            ?>

            <article class="cmty-post cmty-post--outlined"
                     style="border-left: 5px solid <?php echo e($is_correct ? '#4CAF50' : ($is_correct === false ? '#f44336' : '#ff9800')); ?>;">

                <header class="cmty-head">
                    <div class="cmty-user">
                        <div>
                            <h4>
                                السؤال <?php echo e($index + 1); ?>: <?php echo e($question->title); ?>

                                <?php if($is_correct === 1): ?>
                                    <span style="color: #4CAF50; font-size: 14px;">(صحيح - <?php echo e($answer->score); ?>/<?php echo e($question->grade); ?>)</span>
                                <?php elseif($is_correct === 0): ?>
                                    <span style="color: #f44336; font-size: 14px;">(خطأ - <?php echo e($answer->score); ?>/<?php echo e($question->grade); ?>)</span>
                                <?php else: ?>
                                    <span style="color: #ff9800; font-size: 14px;">(قيد التصحيح - <?php echo e($answer->score); ?>/<?php echo e($question->grade); ?>)</span>
                                <?php endif; ?>
                            </h4>
                        </div>
                    </div>
                </header>

                <div class="cmty-text">
                    <p><strong>السؤال:</strong> <?php echo e($question->question); ?></p>

                    <?php if($question->explanation): ?>
                        <div style="margin: 10px 0; padding: 10px; background: #f0f8ff; border-radius: 5px;">
                            <small><strong>ملاحظة:</strong> <?php echo e($question->explanation); ?></small>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Display Answer Based on Question Type -->
                <div style="margin: 15px 0; padding: 15px; background: #f9f9f9; border-radius: 5px;">
                    <?php if($question->type === 'essay'): ?>
                        <div>
                            <strong>إجابتك:</strong>
                            <p style="margin: 10px 0; padding: 10px; background: white; border-radius: 3px;">
                                <?php echo e($answer->essay_answer ?: 'لم يتم الإجابة'); ?>

                            </p>
                        </div>

                    <?php elseif($question->type === 'true_false'): ?>
                        <div>
                            <strong>إجابتك:</strong>
                            <span style="color: <?php echo e($is_correct ? '#4CAF50' : '#f44336'); ?>;">
                                <?php echo e($answer->answer_display); ?>

                            </span>
                        </div>

                        <div style="margin-top: 10px;">
                            <strong>الإجابة الصحيحة:</strong>
                            <?php
                                $correct_option = $question->getCorrectOptions()->first();
                            ?>
                            <span style="color: #4CAF50;">
                                <?php echo e($correct_option ? $correct_option->option : 'غير محدد'); ?>

                            </span>
                        </div>

                    <?php elseif($question->type === 'multiple_choice'): ?>
                        <div>
                            <strong>إجاباتك:</strong>
                            <?php if($answer->selected_options): ?>
                                <ul style="margin: 10px 0;">
                                    <?php $__currentLoopData = $answer->getSelectedOptionsModels(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $selected): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li style="color: <?php echo e(in_array($selected->id, $question->getCorrectOptions()->pluck('id')->toArray()) ? '#4CAF50' : '#f44336'); ?>;">
                                            <?php echo e($selected->option); ?>

                                            <?php if(in_array($selected->id, $question->getCorrectOptions()->pluck('id')->toArray())): ?>
                                                ✓
                                            <?php else: ?>
                                                ✗
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            <?php else: ?>
                                <span style="color: #999;">لم يتم الإجابة</span>
                            <?php endif; ?>
                        </div>

                        <div style="margin-top: 10px;">
                            <strong>الإجابات الصحيحة:</strong>
                            <ul style="margin: 10px 0;">
                                <?php $__currentLoopData = $question->getCorrectOptions(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $correct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li style="color: #4CAF50;"><?php echo e($correct->option); ?> ✓</li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>

                        <!-- Show all options with indicators -->
                        <div style="margin-top: 15px;">
                            <strong>جميع الخيارات:</strong>
                            <ul style="margin: 10px 0;">
                                <?php $__currentLoopData = $question->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $was_selected = $answer->selected_options && in_array($option->id, $answer->selected_options);
                                        $is_correct_option = $option->is_correct;
                                    ?>

                                    <li style="
                                        color: <?php echo e($is_correct_option ? '#4CAF50' : '#333'); ?>;
                                        background: <?php echo e($was_selected ? ($is_correct_option ? '#e8f5e8' : '#ffeaea') : 'transparent'); ?>;
                                        padding: 5px;
                                        border-radius: 3px;
                                        margin: 5px 0;
                                    ">
                                        <?php echo e($option->option); ?>


                                        <?php if($is_correct_option): ?>
                                            <span style="color: #4CAF50; font-weight: bold;"> ✓ (صحيح)</span>
                                        <?php endif; ?>

                                        <?php if($was_selected && !$is_correct_option): ?>
                                            <span style="color: #f44336; font-weight: bold;"> ✗ (اخترت هذا)</span>
                                        <?php elseif($was_selected && $is_correct_option): ?>
                                            <span style="color: #4CAF50; font-weight: bold;"> ✓ (اخترت هذا)</span>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Grade Display -->
                <div style="text-align: left; margin-top: 10px;">
                    <span style="
                        background: <?php echo e($is_correct ? '#4CAF50' : ($is_correct === false ? '#f44336' : '#ff9800')); ?>;
                        color: white;
                        padding: 5px 10px;
                        border-radius: 15px;
                        font-size: 12px;
                        font-weight: bold;
                    ">
                        <?php echo e($answer->score); ?>/<?php echo e($question->grade); ?>

                    </span>
                </div>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <!-- Final Statistics -->
        <article class="cmty-post cmty-post--outlined">
            <header class="cmty-head">
                إحصائيات مفصلة
            </header>

            <?php
                $total_questions = (clone $answers)->count();
                $correct_answers = (clone $answers)->where('is_correct','===', 1)->count();
                $wrong_answers   = (clone $answers)->where('is_correct','===',0)->count();
                $pending_answers = (clone $answers)->where('is_correct','===', null)->count();
            ?>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin: 15px 0;">
                <div style="text-align: center; padding: 15px; background: #e8f5e8; border-radius: 10px;">
                    <div style="font-size: 24px; font-weight: bold; color: #4CAF50;"><?php echo e($correct_answers); ?></div>
                    <div>إجابات صحيحة</div>
                </div>

                <div style="text-align: center; padding: 15px; background: #ffeaea; border-radius: 10px;">
                    <div style="font-size: 24px; font-weight: bold; color: #f44336;"><?php echo e($wrong_answers); ?></div>
                    <div>إجابات خاطئة</div>
                </div>

                <?php if($pending_answers > 0): ?>
                <div style="text-align: center; padding: 15px; background: #fff3cd; border-radius: 10px;">
                    <div style="font-size: 24px; font-weight: bold; color: #ff9800;"><?php echo e($pending_answers); ?></div>
                    <div>قيد التصحيح</div>
                </div>
                <?php endif; ?>

                <div style="text-align: center; padding: 15px; background: #e3f2fd; border-radius: 10px;">
                    <div style="font-size: 24px; font-weight: bold; color: #1976d2;"><?php echo e($total_questions); ?></div>
                    <div>إجمالي الأسئلة</div>
                </div>
            </div>

            <div class="cmty-actions">
                <a href="<?php echo e(route('exam.results', ['exam' => $exam->id])); ?>" class="cmty-like">عرض جميع المحاولات</a>
                <a href="<?php echo e(route('exam', ['exam' => $exam->id, 'slug' => $exam->slug])); ?>" class="cmty-like">العودة للامتحان</a>
            </div>
        </article>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/web/exam/review.blade.php ENDPATH**/ ?>