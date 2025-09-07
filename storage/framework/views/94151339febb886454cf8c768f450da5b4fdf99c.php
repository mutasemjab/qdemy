<div class="ud-panel" id="community">
    <div class="ud-title"><?php echo e(__('panel.q_community')); ?></div>
    <div class="ud-community">
        <!-- Post Creation Box -->
        <div class="ud-postbox">
            <div class="ud-post-head">
                <img data-src="<?php echo e(auth()->user()->photo ? asset('assets/admin/uploads/' . auth()->user()->photo) : asset('assets_front/images/avatar-round.png')); ?>" alt="Avatar">
                <b><?php echo e(auth()->user()->name); ?></b>
            </div>
            <form id="create-post-form">
                <?php echo csrf_field(); ?>
                <textarea name="content" placeholder="<?php echo e(__('panel.write_your_question_or_post')); ?>" required maxlength="1000"></textarea>
                <div class="ud-post-actions">
                    <button type="submit" class="ud-primary"><?php echo e(__('panel.publish')); ?></button>
                </div>
            </form>
        </div>

        <!-- Posts Feed -->
        <div class="ud-feed">
            <?php $__empty_1 = true; $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="ud-post" data-post-id="<?php echo e($post->id); ?>">
                    <div class="ud-post-top">
                        <div class="ud-post-user">
                            <img data-src="<?php echo e($post->user->photo ? asset('assets/admin/uploads/' . $post->user->photo) : asset('assets_front/images/avatar-round.png')); ?>" alt="User Avatar">
                            <div>
                                <b><?php echo e($post->user->name); ?></b>
                                <br>
                                <small><?php echo e($post->created_at->format('h:i A · M d, Y')); ?></small>
                            </div>
                        </div>
                        <img data-src="<?php echo e(asset('assets_front/images/qmark.png')); ?>" class="ud-q" alt="Question Mark">
                    </div>
                    <p><?php echo e($post->content); ?></p>
                    <div class="ud-post-actions">
                        <button class="like-btn <?php echo e($post->isLikedBy(auth()->id()) ? 'liked' : ''); ?>" data-post-id="<?php echo e($post->id); ?>">
                            <i class="fa-<?php echo e($post->isLikedBy(auth()->id()) ? 'solid' : 'regular'); ?> fa-heart"></i>
                            <span class="likes-count"><?php echo e($post->likesCount()); ?></span>
                        </button>
                        <button class="comment-toggle" data-post-id="<?php echo e($post->id); ?>">
                            <i class="fa-regular fa-comment"></i>
                            <span><?php echo e($post->commentsCount()); ?></span>
                        </button>
                    </div>

                    <!-- Comments Section -->
                    <div class="ud-comments" id="comments-<?php echo e($post->id); ?>" style="display: none;">
                        <?php $__currentLoopData = $post->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="ud-comment">
                                <img data-src="<?php echo e($comment->user->photo ? asset('assets/admin/uploads/' . $comment->user->photo) : asset('assets_front/images/avatar-round.png')); ?>" alt="Comment Avatar">
                                <div class="ud-comment-content">
                                    <b><?php echo e($comment->user->name); ?></b>
                                    <p><?php echo e($comment->content); ?></p>
                                    <small><?php echo e($comment->created_at->format('h:i A · M d, Y')); ?></small>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <!-- Add Comment Form -->
                        <div class="ud-add-comment">
                            <form class="add-comment-form" data-post-id="<?php echo e($post->id); ?>">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="post_id" value="<?php echo e($post->id); ?>">
                                <textarea name="content" placeholder="<?php echo e(__('panel.write_comment')); ?>" required maxlength="500"></textarea>
                                <button type="submit" class="ud-primary"><?php echo e(__('panel.comment')); ?></button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="ud-no-posts">
                    <p><?php echo e(__('panel.no_posts_yet')); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>



<script>
document.addEventListener('DOMContentLoaded', function() {
    // Create Post
    document.getElementById('create-post-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        
        submitBtn.disabled = true;
        submitBtn.textContent = '<?php echo e(__("panel.publishing")); ?>...';
        
        fetch('<?php echo e(route(auth()->user()->role_name . ".create-post")); ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.reset();
                alert(data.message);
                // Optionally reload the page to show new posts
                location.reload();
            } else {
                alert('<?php echo e(__("panel.error_occurred")); ?>');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('<?php echo e(__("panel.error_occurred")); ?>');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        });
    });

    // Toggle Like
    document.querySelectorAll('.like-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');
            const icon = this.querySelector('i');
            const countSpan = this.querySelector('.likes-count');
            
            fetch('<?php echo e(route(auth()->user()->role_name . ".toggle-like")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ post_id: postId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.liked) {
                        icon.classList.remove('fa-regular');
                        icon.classList.add('fa-solid');
                        this.classList.add('liked');
                    } else {
                        icon.classList.remove('fa-solid');
                        icon.classList.add('fa-regular');
                        this.classList.remove('liked');
                    }
                    countSpan.textContent = data.likes_count;
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });

    // Toggle Comments
    document.querySelectorAll('.comment-toggle').forEach(btn => {
        btn.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');
            const commentsDiv = document.getElementById('comments-' + postId);
            
            if (commentsDiv.style.display === 'none') {
                commentsDiv.style.display = 'block';
            } else {
                commentsDiv.style.display = 'none';
            }
        });
    });

    // Add Comment
    document.querySelectorAll('.add-comment-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            
            submitBtn.disabled = true;
            submitBtn.textContent = '<?php echo e(__("panel.commenting")); ?>...';
            
            fetch('<?php echo e(route(auth()->user()->role_name . ".add-comment")); ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.reset();
                    alert(data.message);
                    // Optionally reload to show new comment
                    location.reload();
                } else {
                    alert('<?php echo e(__("panel.error_occurred")); ?>');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('<?php echo e(__("panel.error_occurred")); ?>');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
        });
    });
});
</script><?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/panel/student/community.blade.php ENDPATH**/ ?>