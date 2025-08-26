<?php $__env->startSection('title',$course->title); ?>

<?php $__env->startSection('content'); ?>
<section class="lesson-page">

    <!-- Header -->
    <div class="grades-header-wrapper">
        <div class="grades-header">
            <h2><?php echo e($course->title); ?></h2>
            <span class="grade-number"><?php echo e(mb_substr($course->title,0,1)); ?></span>
        </div>
    </div>

    <div class="lesson-container">

        <!-- Right Sidebar -->
        <div class="lesson-sidebar">
            <div class="lesson-card">
                <?php if($freeContents?->video_url): ?>
                <div class="lesson-card-image playable" data-video="<?php echo e($freeContents?->video_url); ?>"
                    data-is-completed="<?php echo e($freeContents?->is_completed); ?>" data-content-id="<?php echo e($freeContents?->id); ?>" data-watched-time="<?php echo e($freeContents?->watched_time); ?>" data-duration="<?php echo e($freeContents?->video_duration); ?>">
                    <img data-src="<?php echo e(asset('assets_front/images/video-thumb-main.png')); ?>" alt="Course">
                    <span class="play-overlay"><i class="fas fa-play"></i></span>
                </div>
                <?php endif; ?>
                <div class="lesson-card-info">
                    <h3><?php echo e($course->title); ?></h3>
                    <p class="lesson-card-p"><?php echo e($freeContents?->title); ?></p>
                    <span class="price"><?php echo e($course->selling_price); ?> <?php echo e(CURRENCY); ?></span>
                    <?php if($is_enrolled): ?>
                        <a href="javascript:void(0)" class="btn-buy"><?php echo e(__('messages.enrolled')); ?></a>
                        <!-- Progress Display -->
                        <div class="progress-info">
                            <div class="progress-bar-container">
                                <div class="progress-bar" style="width: <?php echo e($course_progress); ?>%"></div>
                                <span class="progress-text"><?php echo e(round($course_progress)); ?>% <?php echo e(__('messages.completed')); ?></span>
                            </div>
                            <div class="progress-stats">
                                <span><?php echo e(__('messages.completed')); ?>: <?php echo e($completed_videos); ?></span>
                                <span><?php echo e(__('messages.watching')); ?>: <?php echo e($watching_videos); ?></span>
                                <span><?php echo e(__('messages.total_videos')); ?>: <?php echo e($total_videos); ?></span>
                            </div>
                        </div>
                    <?php elseif(is_array($user_courses) && in_array($course->id,$user_courses)): ?>
                      <a href="<?php echo e(route('checkout')); ?>"class="btn-buy"><?php echo e(__('messages.buy_now')); ?></a>
                      <a href="<?php echo e(route('checkout')); ?>"class="btn-add"><?php echo e(__('messages.go_to_checkout')); ?></a>
                    <?php else: ?>
                        <a id='buy_now' href="javascript:void(0)" data-course-id="<?php echo e($course->id); ?>" class="enroll-btn btn-buy"><?php echo e(__('messages.buy_now')); ?></a>
                        <a href="javascript:void(0)" data-course-id="<?php echo e($course->id); ?>" class="enroll-btn btn-add"><?php echo e(__('messages.add_to_cart')); ?></a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('contacts')); ?>" class="text-decoration-none"><p class="report"><?php echo e(__('messages.report_this_course')); ?></p></a>
                </div>
            </div>

            <div class="lesson-teacher">
                <img data-src="<?php echo e($course->teacher?->photo_url); ?>" alt="Instructor">
                <h4><?php echo e($course->teacher?->name); ?></h4>
                <p><?php echo e(__('messages.session_all_count')); ?> - <?php echo e($mainSections?->count()); ?>

                    <br> <?php echo e(__('messages.video_all_count')); ?> - <?php echo e($contents?->where('content_type','video')?->count()); ?></p>
            </div>

            <?php if(!$is_enrolled && $user): ?>
            <div class="lesson-card-activation">
                <h3><?php echo e(__('messages.card_qdemy')); ?></h3>
                <p><?php echo e(__('messages.enter_card_qdemy')); ?></p>
                <input class="lesson-card-activation input" type="text" placeholder="<?php echo e(__('messages.enter_card_here')); ?>">
                <button data-course-id="<?php echo e($course->id); ?>" class="lesson-card-activation button"><?php echo e(__('messages.activate_card')); ?></button>
            </div>
            <?php endif; ?>

        </div>

        <!-- Left Content -->
        <div class="lesson-content">
            <h2 class="lesson-title"><?php echo e($course->title); ?></h2>
            <p class="lesson-desc"><?php echo e($course->description); ?></p>

            <h3 class="lesson-subtitle"><?php echo e(__('messages.course_content')); ?></h3>

            <?php if($mainSections && $mainSections->count()): ?>
            <div class="accordion">
                <?php $__currentLoopData = $mainSections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="accordion-item">
                    <button class="accordion-header"> <?php echo e($section->title); ?></button>
                        <div class="accordion-body">
                            <?php
                                $sectionContents = $section->contents;
                                $subSections    = $section->children; // Assuming you have a `children` relationship for submainSections
                            ?>

                            <?php if($sectionContents && $sectionContents->count()): ?>
                                <?php $__currentLoopData = $sectionContents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $content): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $userProgress = $user_progress[$content->id] ?? null;
                                        $progressPercent = $content->video_duration > 0 ? min(100, ($content->watched_time / $content->video_duration) * 100) : 0;
                                    ?>
                                    <!-- main sections  -->
                                    <div class="lesson-item" data-content-id="<?php echo e($content->id); ?>">
                                        <?php if($content->video_url): ?>

                                            <?php if($content->is_free === 1 || $is_enrolled): ?>
                                            <div class="lesson-video" data-video="<?php echo e($content->video_url); ?>"
                                                data-is-completed="<?php echo e($content->is_completed); ?>" data-content-id="<?php echo e($content->id); ?>" data-watched-time="<?php echo e($content->watched_time); ?>" data-duration="<?php echo e($content->video_duration); ?>">
                                                <img data-src="<?php echo e(asset('assets_front/images/video-thumb.png')); ?>" alt="Video">
                                                <span class="play-icon">▶</span>
                                                <?php if($content->is_completed): ?>
                                                    <span class="completion-badge">✓</span>
                                                <?php elseif($content->watched_time > 0): ?>
                                                    <span class="progress-badge"><?php echo e(round($progressPercent)); ?>%</span>
                                                <?php endif; ?>
                                            </div>
                                            <?php else: ?>
                                            <div class="disabled-lesson-video"><img data-src="<?php echo e(asset('assets_front/images/video-thumb.png')); ?>" alt="Video"></div>
                                            <?php endif; ?>

                                        <?php elseif($content->file_path): ?>

                                            <?php if($content->is_free === 1 || $is_enrolled): ?>
                                            <div class="lessonvideo">
                                                <a class='text-decoration-none' target='_blank' href="<?php echo e($content->file_path); ?>">
                                                    <i class="fa fa-file" style='color:rgba(0, 85, 210, 0.7);'>
                                                </i>
                                               </a>
                                            </div>
                                            <?php else: ?>
                                                <a class='text-decoration-none' href="javascrip:void(0)">
                                                    <i class="fa fa-file" style='color:lightgray;'></i>
                                                </a>
                                            <?php endif; ?>

                                        <?php endif; ?>

                                        <div class="lesson-info">
                                            <h4><?php echo e($content->title); ?></h4>
                                            <?php if($content->content_type === 'video' && $is_enrolled): ?>
                                                <div class="video-progress-bar">
                                                    <div class="progress-fill" style="width: <?php echo e($progressPercent); ?>%"></div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- main sections Quizes -->
                                    <?php $sectionsExams = $section->exams ?>
                                    <?php if($is_enrolled && $sectionsExams && $sectionsExams->count()): ?>
                                        <span><?php echo e(__('messages.section Quiz')); ?></span>
                                        <?php $__currentLoopData = $sectionsExams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sectionsExam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="lesson-item exam">
                                                <a class='text-decoration-none d-flex' target='_blank' href="<?php echo e(route('exam',['exam'=>$course->id,'slug'=>$sectionsExam->slug])); ?>">
                                                <div class="lesson-info">
                                                    <h4> <i class="fa fa-question" style='color:rgba(0, 85, 210, 0.7);'></i> <?php echo e($sectionsExam->title); ?> </h4>
                                                    <div class="">
                                                        <br>
                                                        <i class="fa fa-check"></i> <?php echo e(__('messages.عدد المحاولات')); ?> <?php echo e($sectionsExam->user_attempts()->count()); ?>

                                                        <?php if($sectionsExam->result_attempt()?->is_passed): ?>
                                                            <i class="fa fa-check"></i>
                                                        <?php else: ?>
                                                            <i class="fa fa-times"></i>
                                                        <?php endif; ?>
                                                        <?php echo e($sectionsExam->result_attempt()?->percentage); ?>

                                                        <?php echo e(__('messages.أفضل نتيجة')); ?> <?php echo e($sectionsExam->result_attempt()?->score); ?>

                                                    </div>
                                                </div>
                                                </a>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                            <?php endif; ?>

                            
                            <?php if($subSections && $subSections->count()): ?>

                                <div class="accordion">
                                    <?php $__currentLoopData = $subSections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subSection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="accordion-item">
                                        <button class="accordion-header"> <?php echo e($subSection->title); ?></button>
                                        <div class="accordion-body">
                                            <?php $subSectionContents = $subSection->contents ?>
                                            <?php if($subSectionContents && $subSectionContents->count()): ?>
                                                <?php $__currentLoopData = $subSectionContents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subSectioncontent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="lesson-item" data-content-id="<?php echo e($subSectioncontent->id); ?>">
                                                    <?php if($subSectioncontent->video_url): ?>

                                                        <?php if($subSectioncontent->is_free === 1 || $is_enrolled): ?>
                                                        <div class="lesson-video" data-video="<?php echo e($subSectioncontent->video_url); ?>"
                                                            data-is-completed="<?php echo e($subSectioncontent->is_completed); ?>" data-content-id="<?php echo e($subSectioncontent->id); ?>" data-watched-time="<?php echo e($subSectioncontent->watched_time); ?>" data-duration="<?php echo e($subSectioncontent->video_duration); ?>">
                                                            <img data-src="<?php echo e(asset('assets_front/images/video-thumb.png')); ?>" alt="Video">
                                                            <span class="play-icon">▶</span>
                                                            <?php if($subSectioncontent->is_completed): ?>
                                                                <span class="completion-badge">✓</span>
                                                            <?php elseif($subSectioncontent->watched_time > 0): ?>
                                                                <span class="progress-badge"><?php echo e(round($progressPercent)); ?>%</span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <?php else: ?>
                                                        <div class="disabled-lesson-video"><img data-src="<?php echo e(asset('assets_front/images/video-thumb.png')); ?>" alt="Video"></div>
                                                        <?php endif; ?>

                                                    <?php elseif($subSectioncontent->file_path): ?>

                                                        <?php if($subSectioncontent->is_free === 1 || $is_enrolled): ?>
                                                        <div class="lessonvideo">
                                                            <a class='text-decoration-none' target='_blank' href="<?php echo e($subSectioncontent->file_path); ?>">
                                                                <i class="fa fa-file" style='color:rgba(0, 85, 210, 0.7);'>
                                                            </i></a>
                                                        </div>
                                                        <?php else: ?>
                                                            <a class='text-decoration-none' href="javascrip:void(0)">
                                                                <i class="fa fa-file" style='color:lightgray;'></i>
                                                            </a>
                                                        <?php endif; ?>

                                                    <?php endif; ?>
                                                    <div class="lesson-info">
                                                        <h4><?php echo e($subSectioncontent->title); ?></h4>
                                                        <?php if($subSectioncontent->content_type === 'video' && ($subSectioncontent->is_free === 1 || $is_enrolled)): ?>
                                                            <div class="video-progress-bar">
                                                                <div class="progress-fill" style="width: <?php echo e($progressPercent); ?>%"></div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <!-- sub sections Quizes -->
                                    <?php $subSectionsExams = $subSection->exams ?>
                                    <?php if($is_enrolled && $subSectionsExams && $subSectionsExams->count()): ?>
                                        <span><?php echo e(__('messages.section Quiz')); ?></span>
                                        <?php $__currentLoopData = $subSectionsExamss; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subSectionsExams): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="lesson-item exam">
                                                <a class='text-decoration-none d-flex' target='_blank' href="<?php echo e(route('exam',['exam'=>$course->id,'slug'=>$subSectionsExams->slug])); ?>">
                                                <div class="lesson-info">
                                                    <h4> <i class="fa fa-question" style='color:rgba(0, 85, 210, 0.7);'></i> <?php echo e($subSectionsExams->title); ?> </h4>
                                                    <div class="">
                                                        <br>
                                                        <i class="fa fa-check"></i> <?php echo e(__('messages.عدد المحاولات')); ?> <?php echo e($subSectionsExams->user_attempts()->count()); ?>

                                                        <?php if($subSectionsExams->result_attempt()?->is_passed): ?>
                                                            <i class="fa fa-check"></i>
                                                        <?php else: ?>
                                                            <i class="fa fa-times"></i>
                                                        <?php endif; ?>
                                                        <?php echo e($subSectionsExams->result_attempt()?->percentage); ?>

                                                        <?php echo e(__('messages.أفضل نتيجة')); ?> <?php echo e($subSectionsExams->result_attempt()?->score); ?>

                                                    </div>
                                                </div>
                                                </a>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



                                </div>
                            <?php endif; ?>

                        </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                
                <?php
                    $unassignedContents = $course->contents->where('section_id',null)
                ?>
                <?php if($unassignedContents->count()): ?>
                <div class="accordion-item">
                    <button class="accordion-header"> Unassigned Contents</button>
                    <div class="accordion-body">
                        <?php $__currentLoopData = $unassignedContents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_content): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="lesson-item" data-content-id="<?php echo e($_content->id); ?>">
                                <?php if($_content->video_url): ?>

                                    <?php if($_content->is_free === 1 || $is_enrolled): ?>
                                    <div class="lesson-video" data-video="<?php echo e($_content->video_url); ?>"
                                        data-is-completed="<?php echo e($_content->is_completed); ?>" data-content-id="<?php echo e($_content->id); ?>" data-watched-time="<?php echo e($_content->watched_time); ?>" data-duration="<?php echo e($_content->video_duration); ?>">
                                        <img data-src="<?php echo e(asset('assets_front/images/video-thumb.png')); ?>" alt="Video">
                                        <span class="play-icon">▶</span>
                                        <?php if($_content->is_completed): ?>
                                            <span class="completion-badge">✓</span>
                                        <?php elseif($_content->watched_time > 0): ?>
                                            <span class="progress-badge"><?php echo e(round($progressPercent)); ?>%</span>
                                        <?php endif; ?>
                                    </div>
                                    <?php else: ?>
                                    <div class="disabled-lesson-video"><img data-src="<?php echo e(asset('assets_front/images/video-thumb.png')); ?>" alt="Video"></div>
                                    <?php endif; ?>

                                <?php elseif($_content->file_path): ?>

                                    <?php if($_content->is_free === 1 || $is_enrolled): ?>
                                    <div class="lessonvideo">
                                        <a class='text-decoration-none' target='_blank' href="<?php echo e($_content->file_path); ?>">
                                            <i class="fa fa-file" style='color:rgba(0, 85, 210, 0.7);'>
                                        </i></a>
                                    </div>
                                    <?php else: ?>
                                        <a class='text-decoration-none' href="javascrip:void(0)">
                                            <i class="fa fa-file" style='color:lightgray;'></i>
                                        </a>
                                    <?php endif; ?>

                                <?php endif; ?>
                                <div class="lesson-info">
                                    <h4><?php echo e($_content->title); ?></h4>
                                    <?php if($_content->content_type === 'video' && ($_content->is_free === 1 || $is_enrolled)): ?>
                                        <div class="video-progress-bar">
                                            <div class="progress-fill" style="width: <?php echo e($progressPercent); ?>%"></div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- all course quiez -->
                <?php if($is_enrolled && $exams && $exams->count()): ?>
                <div class="accordion-item">
                    <button class="accordion-header"> <?php echo e(__('messages.All course Quiez')); ?></button>
                    <div class="accordion-body">
                        <?php $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="lesson-item exam">
                                <a class='text-decoration-none d-flex' target='_blank' href="<?php echo e(route('exam',['exam'=>$course->id,'slug'=>$exam->slug])); ?>">
                                    <div class="lesson-info">
                                    <h4> <i class="fa fa-question" style='color:rgba(0, 85, 210, 0.7);'></i> <?php echo e($exam->title); ?> </h4>
                                    <div class="">
                                        <br>
                                        <i class="fa fa-check"></i> <?php echo e(__('messages.trying times')); ?> <?php echo e($exam->user_attempts()->count()); ?>

                                        <?php if($exam->result_attempt() && $exam->result_attempt()->is_passed): ?>
                                            <i class="fa fa-check"></i>
                                        <?php elseif($exam->result_attempt() && !$exam->result_attempt()->is_passed): ?>
                                        <i class="fa fa-times"></i>
                                        <?php endif; ?>

                                        <?php if($exam->result_attempt()): ?>
                                            <?php echo e(__('messages.best_results')); ?>

                                            <?php echo e($exam->result_attempt()?->percentage); ?>%
                                            <?php echo e($exam->result_attempt()?->score); ?>

                                        <?php endif; ?>
                                    </div>
                                </div>
                               </a>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endif; ?>

            </div>
            <?php endif; ?>

        </div>
    </div>

</section>

<!-- Video Popup -->
<div class="video-popup">
    <div class="video-popup-content">
        <span class="close-popup">&times;</span>
        <div class="video-controls">
            <button style="<?php if(!$user?->id): ?>display:none;<?php endif; ?>" id="mark-complete-btn" class="mark-complete-btn">تسجيل كمكتمل</button>
        </div>
        <iframe src="" frameborder="0" allowfullscreen></iframe>
    </div>
</div>

<!-- نافذة منبثقة -->
<div id="enrollment-modal" class="messages modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3> <i class="fa fa-check"></i> </h3>
        <h3><?php echo e(__('messages.course_added')); ?></h3>
        <p><?php echo e(__('messages.course_added_successfully')); ?></p>
        <div class="modal-buttons">
            <button id="continue-shopping"><?php echo e(__('messages.continue_shopping')); ?></button>
            <button id="go-to-checkout"><?php echo e(__('messages.go_to_checkout')); ?></button>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    let user = "<?php echo e($user?->id); ?>";
    let isEnrolled = <?php echo e($is_enrolled); ?>;
</script>
<script>
    // Card activation
    document.addEventListener('DOMContentLoaded', function() {
        // الحصول على العناصر
        const activateButton = document.querySelector('.lesson-card-activation button');
        const cardInput      = document.querySelector('.lesson-card-activation input');

        // الحصول على CSRF Token من meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // إضافة حدث النقر لزر التفعيل
        activateButton.addEventListener('click', function(e) {
            e.preventDefault();
            const cardNumber = cardInput.value.trim();
            const courseId   = this.getAttribute('data-course-id');

            if(!user){
                alert("<?php echo e(__('messages.please login first .')); ?>");
                return 0;
            }else if (!cardNumber) {
                alert('من فضلك أدخل رقم البطاقة');
                return;
            }

            // إظهار مؤشر تحميل (اختياري)
            activateButton.innerHTML = 'جارِ التفعيل...';
            activateButton.disabled = true;

            // إرسال طلب Ajax
            fetch('<?php echo e(route("activate.card")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ card_number: cardNumber ,course_id:courseId})
            })
            .then(response => {
                // إعادة زر التفعيل إلى حالته الأصلية
                activateButton.innerHTML = 'تفعيل البطاقة';
                activateButton.disabled = false;

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('تم تفعيل البطاقة بنجاح وإضافتك للكورس!');
                    // يمكنك إعادة توجيه المستخدم أو تحديث الصفحة
                    location.reload();
                } else {
                    alert('حدث خطأ أثناء التفعيل: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.log('Error:', error);
                alert('حدث خطأ في الاتصال بالخادم');
            });
        });


        // Card activation
        document.querySelector('.lesson-card-activation.button').addEventListener('click', function() {
            let courseId = this.getAttribute('data-course-id');
            let cardNumber = document.querySelector('.lesson-card-activation.input').value;

            if (!cardNumber) {
                alert('يرجى إدخال رقم البطاقة');
                return;
            }

            fetch('/activate-card', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({
                    course_id: courseId,
                    card_number: cardNumber
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('تم تفعيل البطاقة بنجاح');
                    location.reload();
                } else {
                    alert(data.message || 'خطأ في تفعيل البطاقة');
                }
            })
            .catch(error => {
                alert('حدث خطأ، يرجى المحاولة مرة أخرى');
                console.error('Error:', error);
            });
        });


    });
</script>
<script>
    // enrollment
    document.addEventListener('DOMContentLoaded', function() {
        // الحصول على العناصر
        const modal = document.getElementById('enrollment-modal');
        const closeBtn = document.querySelector('.close');
        const continueBtn = document.getElementById('continue-shopping');
        const checkoutBtn = document.getElementById('go-to-checkout');
        const enrollButtons = document.querySelectorAll('.enroll-btn');

        // الحصول على CSRF Token من meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // إضافة حدث النقر لأزرار التسجيل
        enrollButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                if(!user){
                    return 0;
                }

                const courseId = this.getAttribute('data-course-id');
                const buttonId = this.getAttribute('id');
                const buttonInnerHTML = this.innerHTML;

                // إظهار مؤشر تحميل (اختياري)
                this.innerHTML = '<?php echo e(__("messages.loading")); ?>';
                this.disabled = true;

                // إرسال طلب Ajax
                fetch('<?php echo e(route("add.to.session")); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ course_id: courseId })
                })
                .then(response => {
                    // إعادة زر التسجيل إلى حالته الأصلية
                    this.innerHTML = "<?php echo e(__('messages.go_to_checkout')); ?>";
                    this.disabled = false;

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    return response.json();
                })
                .then(data => {
                    if (buttonId && buttonId == 'buy_now') {
                        window.location.href = '<?php echo e(route("checkout")); ?>';
                    }else if (data.success) {
                        // عرض النافذة المنبثقة
                        modal.style.display = 'flex';
                    }
                    // console.log(data);
                })
                .catch(error => {
                    // console.log('Error:', error);
                    this.innerHTML = buttonInnerHTML;
                });
            });
        });

        // إغلاق النافذة عند النقر على ×
        closeBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        // الاستمرار في التسوق
        continueBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        // الانتقال إلى صفحة الدفع
        checkoutBtn.addEventListener('click', function() {
            window.location.href = '<?php echo e(route("checkout")); ?>'; // تأكد من وجود هذا المسار
        });

        // إغلاق النافذة عند النقر خارجها
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });

        // Handle enrollment buttons
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('enroll-btn')) {

                if(!user){
                    alert("<?php echo e(__('messages.please login first .')); ?>");
                    return 0;
                }

                let courseId = e.target.getAttribute('data-course-id');
                let action = e.target.id === 'buy_now' ? 'buy' : 'add';

                fetch('/enroll-course', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    },
                    body: JSON.stringify({
                        course_id: courseId,
                        action: action
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('enrollment-modal').classList.add('show');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });

        // Modal functionality
        document.querySelector('.close').addEventListener('click', function() {
            document.getElementById('enrollment-modal').classList.remove('show');
        });

        document.getElementById('continue-shopping').addEventListener('click', function() {
            document.getElementById('enrollment-modal').classList.remove('show');
        });

        document.getElementById('go-to-checkout').addEventListener('click', function() {
            window.location.href = '<?php echo e(route("checkout")); ?>';
        });
        // Accordion functionality
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('accordion-header')) {
                e.target.classList.toggle('active');
                const body = e.target.nextElementSibling;
                body.classList.toggle('active');
            }
        });
    });
</script>
<!-- save user progress -->

<script>
    let currentVideoId = null;
    let videoStartTime = 0;
    let progressUpdateInterval = null;
    let currentVideoDuration = 0;
    let lastWatchedTime = 0; // وقت المشاهدة الأخير
    let stopProgress = 0; // لايقاف التحديث التلقائي عندما لا تساوي 0
    const COMPLETED_MINUTE = <?php echo e(env('COMPLETED_WATCHING_COURSES', 5)); ?>;

    document.addEventListener('DOMContentLoaded', function() {

        // Handle video click
        document.addEventListener('click', function(e) {
            if (e.target.closest('.lesson-video, .playable')) {
                const element   = e.target.closest('.lesson-video, .playable');
                let videoUrl    = element.getAttribute('data-video');
                let videoProgress = element.getAttribute('data-progress');
                let contentId   = element.getAttribute('data-content-id');
                let isCompleted = element.getAttribute('data-is-completed');
                let duration    = element.getAttribute('data-duration') || 0;

                // التحكم في زر زر تسجيل الفيديو كمكتمل اذا كان مكتمل - اذا تواجد يوزر مسجل دخول - اذا كان الفيديو ف الاشتراك
                if(user && isEnrolled){
                    document.getElementById('mark-complete-btn').style.display='block';
                    if(isCompleted){
                        document.getElementById('mark-complete-btn').style.display='none';
                    }
                }

                // mark-complete-btn
                // الغاء ايقاف التحديث التلقائي ان كان موقوف
                stopProgress = 0;
                if(!isEnrolled){
                    stopProgress = 1;
                }

                // استرداد وقت المشاهدة الأخير
                lastWatchedTime = parseInt(element.getAttribute('data-watched-time')) || 0;

                if (videoUrl && contentId) {
                    currentVideoId = contentId;
                    currentVideoDuration = duration;
                    videoStartTime = Date.now();
                    // اضبط بداية الفيديو إلى وقت المشاهدة الأخير
                    videoUrl = fixYouTubeUrl(videoUrl);

                    document.querySelector('.video-popup iframe').src = videoUrl;
                    document.querySelector('.video-popup').classList.add('active');
                    // Start progress tracking
                    //  dont update progress if user deoesnt sighn in
                    if(!user) return 0;
                    startProgressTracking();
                }

            }
        });

        //  dont update progress if user deoesnt sighn in
        if(!user) return 0;

        // Close video popup
        // تحديث التقدم عند الإغلاق
        const popup = document.querySelector('.video-popup');
        const closeButton = document.querySelector('.close-popup');
        // إضافة مستمع حدث واحد
        popup.addEventListener('click', function(event) {
            // تحقق إذا كان العنصر المستهدف هو زر الإغلاق أو العنصر الخارجي
            if (event.target === closeButton || event.target === popup) {
                document.querySelector('.video-popup').classList.remove('active');
                document.querySelector('.video-popup iframe').src = '';
                stopProgressProgressTracking();
            }
        });

        // Mark as complete button
        document.getElementById('mark-complete-btn').addEventListener('click', function() {
            if (currentVideoId) {
                markVideoComplete(currentVideoId);
            }
        });

    });

    function fixYouTubeUrl(url) {
        // lastWatchedTime = parseInt(element.getAttribute('data-watched-time')) || 0;
        let start = (lastWatchedTime <= currentVideoDuration) ? lastWatchedTime :0;
        if (url.includes('youtube.com/watch?v=')) {
            let videoId = url.split('v=')[1].split('&')[0];
            return `https://www.youtube.com/embed/${videoId}?rel=0&modestbranding=1` + `&start=${start}`;
        } else if (url.includes('youtu.be/')) {
            let videoId = url.split('youtu.be/')[1].split('?')[0];
            return `https://www.youtube.com/embed/${videoId}?rel=0&modestbranding=1` + `&start=${start}`;
        } else if (url.includes('youtube.com/embed/')) {
            return url + (url.includes('?') ? '&' : '?') + 'rel=0&modestbranding=1' + `&start=${start}`;
        }
        return url;
    }

    function startProgressTracking() {
        if (progressUpdateInterval) {
            clearInterval(progressUpdateInterval);
        }

        // Update progress every 10 seconds
        progressUpdateInterval = setInterval(function() {
            updateVideoProgress();
        }, 3000);
    }

    function stopProgressProgressTracking() {
        if (progressUpdateInterval) {
            clearInterval(progressUpdateInterval);
            progressUpdateInterval = null;

            // Final progress update when closing
            if (currentVideoId && !stopProgress) {
                updateVideoProgress(true); // تحديث التقدم عند الإغلاق
            }
        }
    }

    function updateVideoProgress(isFinalUpdate = false) {
        if (!currentVideoId || stopProgress) return;

        let currentTime = Date.now();
        let watchedSeconds = Math.floor((currentTime - videoStartTime) / 1000) + lastWatchedTime;

        // Check if video should be marked as completed
        let shouldComplete = false;
        if (currentVideoDuration > 0) {
            let remainingMinutes = (currentVideoDuration - watchedSeconds) / 60;
            shouldComplete = remainingMinutes <= COMPLETED_MINUTE;
        }
        fetch('/update-video-progress', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            body: JSON.stringify({
                content_id: currentVideoId,
                watch_time: watchedSeconds,
                completed: shouldComplete ? 1 : 0,
                is_final_update: isFinalUpdate
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateProgressDisplay(data.progress);

                // Update visual indicators
                if (data.completed) {
                    updateVideoCompletionStatus(currentVideoId, true);
                }
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function markVideoComplete(contentId) {
        fetch('/mark-video-complete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            body: JSON.stringify({
                content_id: contentId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateVideoCompletionStatus(contentId, true);
                updateProgressDisplay(data.progress);
                stopProgress = true;
                document.getElementById('mark-complete-btn').style.display='none';
                console.log('تم تسجيل الفيديو كمكتمل المشاهدة');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function updateVideoCompletionStatus(contentId, completed) {
        const videoElement = document.querySelector(`.lesson-item[data-content-id="${contentId}"] .lesson-video`);

        if (completed && videoElement) {
            const progressBadge = videoElement.querySelector('.progress-badge');
            if (progressBadge) {
                progressBadge.remove();
            }

            if (!videoElement.querySelector('.completion-badge')) {
                const completionBadge = document.createElement('span');
                completionBadge.className = 'completion-badge';
                completionBadge.textContent = '✓';
                videoElement.appendChild(completionBadge);
            }

            const progressFill = videoElement.closest('.lesson-item').querySelector('.progress-fill');
            if (progressFill) {
                progressFill.style.width = '100%';
            }
        }
    }

    function updateProgressDisplay(progressData) {
        if (progressData) {
            const progressBar = document.querySelector('.progress-bar');
            const progressText = document.querySelector('.progress-text');
            const progressStats = document.querySelector('.progress-stats');

            if (progressBar) {
                progressBar.style.width = progressData.course_progress + '%';
            }

            if (progressText) {
                progressText.textContent = Math.round(progressData.course_progress) + '% ' + "<?php echo e(__('messages.completed')); ?>";
            }

            if (progressStats) {
                progressStats.innerHTML = `
                    <span><?php echo e(__('messages.completed')); ?>: ${progressData.completed_videos}</span>
                    <span><?php echo e(__('messages.watching')); ?>: ${progressData.watching_videos}</span>
                    <span><?php echo e(__('messages.total_videos')); ?>: ${progressData.total_videos}</span>
                `;
            }
        }
    }
</script>

<?php $__env->startPush('styles'); ?>
<style>
    .progress-info {
        margin-top: 15px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .progress-bar-container {
        position: relative;
        width: 100%;
        height: 20px;
        background: #e9ecef;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 10px;
    }

    .progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #28a745, #20c997);
        transition: width 0.3s ease;
    }

    .progress-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 12px;
        font-weight: bold;
        color: #333;
    }

    .progress-stats {
        display: flex;
        justify-content: space-between;
        font-size: 13px;
        color: #666;
    }

    .completion-badge {
        position: absolute;
        top: 5px;
        right: 5px;
        background: #28a745;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }

    .progress-badge {
        position: absolute;
        top: 5px;
        right: 5px;
        background: #ffc107;
        color: #333;
        border-radius: 10px;
        padding: 2px 6px;
        font-size: 10px;
        font-weight: bold;
    }

    .video-progress-bar {
        width: 100%;
        height: 3px;
        background: #e9ecef;
        border-radius: 2px;
        overflow: hidden;
        margin-top: 5px;
    }

    .progress-fill {
        height: 100%;
        background: #007bff;
        transition: width 0.3s ease;
    }

    .video-controls {
        position: absolute;
        top: 15px;
        right: 50px;
        z-index: 1000;
    }

    .mark-complete-btn {
        background: #28a745;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
    }

    .mark-complete-btn:hover {
        background: #218838;
    }

    .lesson-video {
        position: relative;
    }
</style>
<style>
    /* Exam Item Styling */
    .lesson-item.exam {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border: 1px solid #e0e6ed;
        border-radius: 8px;
        padding: 12px 16px;
        margin: 10px 0;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .lesson-item.exam::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 4px;
        background: linear-gradient(180deg, #4a90e2 0%, #0055d2 100%);
    }

    .lesson-item.exam:hover {
        box-shadow: 0 3px 12px rgba(0, 85, 210, 0.1);
        transform: translateY(-2px);
        border-color: #d0dae5;
    }

    .lesson-item.exam a {
        display: flex !important;
        align-items: center;
        width: 100%;
        color: inherit;
    }

    .lesson-item.exam .lesson-info {
        flex: 1;
        padding-left: 8px;
    }

    .lesson-item.exam h4 {
        margin: 0 0 8px 0;
        color: #2c3e50;
        font-size: 16px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .lesson-item.exam h4 i {
        font-size: 18ンpx;
        background: rgba(0, 85, 210, 0.1);
        padding: 6px;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Exam Stats Styling */
    .lesson-item.exam .lesson-info > div {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        font-size: 14px;
        color: #6c757d;
        margin-top: 8px;
    }

    .lesson-item.exam .lesson-info > div > * {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .lesson-item.exam .fa-check {
        color: #28a745;
        font-size: 12px;
    }

    .lesson-item.exam .fa-times {
        color: #dc3545;
        font-size: 12px;
    }

    /* File/Resource Item Styling */
    .lesson-item .lessonvideo a,
    .lesson-item a[href*="file_path"] {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        background: linear-gradient(135deg, #f0f4f8 0%, #ffffff 100%);
        border: 1px solid #d0dae5;
        border-radius: 6px;
        transition: all 0.3s ease;
        text-decoration: none !important;
        color: #2c3e50;
    }

    .lesson-item .lessonvideo a:hover,
    .lesson-item a[href*="file_path"]:hover {
        background: linear-gradient(135deg, #e8f0f7 0%, #f5f8fb 100%);
        border-color: rgba(0, 85, 210, 0.3);
        transform: translateX(3px);
    }

    .lesson-item .lessonvideo a i,
    .lesson-item a[href*="file_path"] i {
        font-size: 16px;
        color: rgba(0, 85, 210, 0.7);
    }

    /* Add "Download Resource" text after file icon */
    .lesson-item .lessonvideo a::after {
        content: 'Download Resource';
        font-size: 14px;
        color: #4a5568;
        font-weight: 500;
    }

    /* Disabled file style */
    .lesson-item a[href="javascrip:void(0)"] i,
    .lesson-item a[href="javascript:void(0)"] i {
        color: #b0b9c3 !important;
    }

    .lesson-item a[href="javascrip:void(0)"]::after,
    .lesson-item a[href="javascript:void(0)"]::after {
        content: 'Locked Resource';
        font-size: 14px;
        color: #a0a9b3;
        margin-left: 8px;
    }

    /* Section Quiz Label Styling */
    .accordion-body > span:contains('Quiz') {
        display: inline-block;
        margin: 15px 0 10px 0;
        padding: 4px 12px;
        background: rgba(0, 85, 210, 0.08);
        color: #0055d2;
        border-radius: 4px;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Video progress enhancements */
    .video-progress-bar {
        height: 4px;
        background: #e9ecef;
        border-radius: 2px;
        overflow: hidden;
        margin-top: 8px;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #4a90e2 0%, #0055d2 100%);
        transition: width 0.3s ease;
        border-radius: 2px;
    }

    /* Completion and Progress Badges Enhancement */
    .completion-badge {
        position: absolute;
        top: 5px;
        right: 5px;
        background: #28a745;
        color: white;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
        box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);
    }

    .progress-badge {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(0, 85, 210, 0.9);
        color: white;
        padding: 2px 6px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        box-shadow: 0 2px 4px rgba(0, 85, 210, 0.3);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .lesson-item.exam .lesson-info > div {
            flex-direction: column;
            gap: 8px;
        }

        .lesson-item .lessonvideo a::after {
            content: 'Download';
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/user/course.blade.php ENDPATH**/ ?>