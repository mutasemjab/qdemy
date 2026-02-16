<div class="ud-panel" id="community">
    <div class="ud-title">{{ __('panel.q_community') }}</div>
    <div class="ud-community">
        <!-- Post Creation Box -->
        <div class="ud-postbox">
            <div class="ud-post-head">
                <img data-src="{{ auth()->user()->photo_url }}" alt="Avatar">
                <b>{{ auth()->user()->name }}</b>
            </div>
            <form id="create-post-form">
                @csrf
                <textarea name="content" placeholder="{{ __('panel.write_your_question_or_post') }}" required maxlength="1000"></textarea>
                <div class="ud-post-actions">
                    <button type="submit" class="ud-primary">{{ __('panel.publish') }}</button>
                </div>
            </form>
        </div>

        <!-- Posts Feed -->
        <div class="ud-feed">
            @forelse($posts as $post)
                <div class="ud-post" data-post-id="{{ $post->id }}">
                    <div class="ud-post-top">
                        <div class="ud-post-user">
                            <img data-src="{{ $post->user->photo_url }}" alt="User Avatar">
                            <div>
                                <b>{{ $post->user->name }}</b>
                                <br>
                                <small>{{ $post->created_at->format('h:i A · M d, Y') }}</small>
                            </div>
                        </div>
                        <img data-src="{{ asset('assets_front/images/qmark.png') }}" class="ud-q" alt="Question Mark">
                    </div>
                    <p>{{ $post->content }}</p>
                    <div class="ud-post-actions" style="display: flex; gap: 12px; margin-top: 15px;">
                        <button class="like-btn {{ $post->isLikedBy(auth()->id()) ? 'liked' : '' }}" data-post-id="{{ $post->id }}" style="display: flex; align-items: center; gap: 8px; padding: 8px 16px; border: 1px solid #e0e0e0; background: #fff; border-radius: 20px; cursor: pointer; transition: all 0.3s ease; font-size: 20px; color: #666;">
                            <i class="fa-{{ $post->isLikedBy(auth()->id()) ? 'solid' : 'regular' }} fa-heart" style="color: {{ $post->isLikedBy(auth()->id()) ? '#ff4757' : '#999' }}; transition: color 0.3s ease;"></i>
                            <span class="likes-count" style="font-size: large;">{{ $post->likesCount() }}</span>
                        </button>
                        <button class="comment-toggle" data-post-id="{{ $post->id }}" style="display: flex; align-items: center; gap: 8px; padding: 8px 16px; border: 1px solid #e0e0e0; background: #fff; border-radius: 20px; cursor: pointer; transition: all 0.3s ease; font-size: 20px; color: #666;">
                            <i class="far fa-comment" style="color: #999; transition: color 0.3s ease;"></i>
                            <span style="font-weight: 500;">{{ $post->commentsCount() }}</span>
                        </button>
                    </div>

                    <!-- Comments Section -->
                    <div class="ud-comments-wrapper" id="comments-{{ $post->id }}" style="display: none;">
                        <div style="padding: 15px 0; border-top: 2px solid #f0f0f0; margin-top: 15px;">
                            <!-- Comments List -->
                            <div class="ud-comments-list">
                                @forelse($post->comments->where('parent_id', null) as $comment)
                                    <div class="ud-comment-item" data-comment-id="{{ $comment->id }}">
                                        <img data-src="{{ $comment->user->photo_url }}" alt="Comment Avatar" class="ud-comment-avatar">
                                        <div class="ud-comment-body">
                                            <div class="ud-comment-header">
                                                <b class="ud-comment-author">{{ $comment->user->name }}</b>
                                                <small class="ud-comment-time">{{ $comment->created_at->diffForHumans() }}</small>
                                            </div>
                                            <p class="ud-comment-text">{{ $comment->content }}</p>
                                            <button class="ud-reply-btn" data-comment-id="{{ $comment->id }}">
                                                <i class="fas fa-reply"></i> {{ __('panel.reply') }}
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Replies count toggle button (like Facebook) -->
                                    @if ($comment->replies && $comment->replies->count() > 0)
                                        <div class="ud-replies-toggle">
                                            <button class="ud-view-replies-btn" data-comment-id="{{ $comment->id }}">
                                                <i class="fas fa-caret-down"></i>
                                                {{ $comment->replies->count() }} {{ __('panel.replies') ?? 'ردود' }}
                                            </button>
                                        </div>
                                        <!-- Replies list (hidden by default) -->
                                        <div class="ud-replies-list" id="replies-{{ $comment->id }}" style="display: none;">
                                            @foreach ($comment->replies as $reply)
                                                <div class="ud-comment-item ud-reply" data-comment-id="{{ $reply->id }}">
                                                    <img data-src="{{ $reply->user->photo_url }}" alt="Reply Avatar" class="ud-comment-avatar ud-reply-avatar">
                                                    <div class="ud-comment-body">
                                                        <div class="ud-comment-header">
                                                            <b class="ud-comment-author">{{ $reply->user->name }}</b>
                                                            <small class="ud-comment-time">{{ $reply->created_at->diffForHumans() }}</small>
                                                        </div>
                                                        <p class="ud-comment-text">{{ $reply->content }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Reply form (hidden by default) -->
                                    <div class="ud-reply-form-wrapper" id="reply-form-{{ $comment->id }}" style="display: none;">
                                        <form class="ud-reply-form" data-comment-id="{{ $comment->id }}">
                                            @csrf
                                            <img data-src="{{ auth()->user()->photo_url }}" alt="Reply Avatar" class="ud-comment-avatar ud-reply-avatar">
                                            <div class="ud-comment-body">
                                                <textarea name="content" placeholder="{{ __('panel.write_reply') }}" required maxlength="500" class="ud-reply-textarea"></textarea>
                                                <div class="ud-reply-actions">
                                                    <button type="submit" class="ud-reply-submit">{{ __('panel.reply') }}</button>
                                                    <button type="button" class="ud-reply-cancel">{{ __('panel.cancel') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                @empty
                                    <div class="ud-no-comments">
                                        <p>{{ __('panel.no_comments_yet') }}</p>
                                    </div>
                                @endforelse
                            </div>

                            <!-- Add Comment Form -->
                            <div class="ud-add-comment" style="padding: 15px; background: #fff; border-radius: 8px; border: 1px solid #e0e0e0; margin-top: 15px;">
                                <form class="add-comment-form" data-post-id="{{ $post->id }}">
                                    @csrf
                                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                                    <textarea name="content" placeholder="{{ __('panel.write_comment') }}" required maxlength="500" class="ud-comment-textarea"></textarea>
                                    <button type="submit" class="ud-comment-submit">{{ __('panel.comment') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="ud-no-posts">
                    <p>{{ __('panel.no_posts_yet') }}</p>
                </div>
            @endforelse
        </div>
    </div>
</div>



<style>
/* Animation for Comments */
@keyframes slideDown {
    from {
        opacity: 0;
        max-height: 0;
        transform: translateY(-15px);
    }
    to {
        opacity: 1;
        max-height: 2000px;
        transform: translateY(0);
    }
}

@keyframes slideUp {
    from {
        opacity: 1;
        max-height: 2000px;
        transform: translateY(0);
    }
    to {
        opacity: 0;
        max-height: 0;
        transform: translateY(-15px);
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

/* Like and Comment Button Styles */
.ud-post-actions .like-btn:hover,
.ud-post-actions .comment-toggle:hover {
    border-color: #d0d0d0;
    background: #f9f9f9;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.ud-post-actions .like-btn.liked {
    border-color: #ff4757;
    background: #fff5f6;
    color: #ff4757;
}

.ud-post-actions .like-btn.liked:hover {
    border-color: #ff4757;
    background: #fff0f2;
    box-shadow: 0 2px 8px rgba(255, 71, 87, 0.15);
}

.ud-post-actions .like-btn.liked i {
    color: #ff4757 !important;
}

.ud-post-actions .comment-toggle:hover {
    border-color: #007bff;
    color: #007bff;
}

.ud-post-actions .comment-toggle:hover i {
    color: #007bff !important;
}

/* Comments Section Styles */
.ud-comments-wrapper {
    overflow: hidden;
    max-height: 0;
    opacity: 0;
}

.ud-comments-wrapper.show {
    animation: slideDown 0.4s ease-out forwards;
}

.ud-comments-wrapper.closing {
    animation: slideUp 0.4s ease-out forwards;
}

/* Comments List */
.ud-comments-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 15px;
}

.ud-comment-item {
    display: flex;
    gap: 12px;
    padding: 12px;
    background: #f9f9f9;
    border-radius: 8px;
    border: 1px solid #f0f0f0;
    animation: fadeIn 0.3s ease;
    transition: background 0.2s ease;
}

.ud-comment-item:hover {
    background: #f5f5f5;
}

.ud-comment-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
    border: 2px solid #e0e0e0;
}

.ud-comment-body {
    flex: 1;
    min-width: 0;
}

.ud-comment-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 6px;
}

.ud-comment-author {
    display: block;
    color: #333;
    font-size: 20px;
    font-weight: 600;
}

.ud-comment-time {
    color: #999;
    font-size: 19px;
    white-space: nowrap;
}

.ud-comment-text {
    margin: 0;
    color: #555;
    font-size: 20px;
    line-height: 1.4;
    word-break: break-word;
}

.ud-no-comments {
    padding: 20px 15px;
    text-align: center;
    color: #999;
    font-size: 20px;
}

/* Comment Form */
.ud-comment-textarea {
    width: 100%;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    padding: 10px 12px;
    font-size: 20px;
    font-family: inherit;
    resize: vertical;
    min-height: 70px;
    margin-bottom: 10px;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.ud-comment-textarea:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
}

.ud-comment-submit {
    background: #007bff;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 20px;
    font-weight: 500;
    transition: background 0.3s ease, box-shadow 0.3s ease;
}

.ud-comment-submit:hover {
    background: #0056b3;
    box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
}

.ud-comment-submit:active {
    transform: scale(0.98);
}

.ud-add-comment button:hover {
    background: #0056b3 !important;
    box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
}

.ud-add-comment button:active {
    transform: scale(0.98);
}

.ud-add-comment textarea:focus {
    outline: none;
    border-color: #007bff !important;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
}

/* Reply Styles */
.ud-reply-btn {
    background: none;
    border: none;
    color: #007bff;
    font-size: 18px;
    cursor: pointer;
    padding: 4px 8px;
    margin-top: 8px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: color 0.2s ease;
}

.ud-reply-btn:hover {
    color: #0056b3;
}

.ud-replies-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-right: 0;
    padding-right: 0;
    border-right: 2px solid #e0e0e0;
    margin-right: 20px;
    padding-right: 15px;
}

.ud-reply-avatar {
    width: 32px !important;
    height: 32px !important;
}

.ud-reply-form-wrapper {
    margin: 10px 0 10px 52px;
    padding: 12px;
    background: #f9f9f9;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
}

.ud-reply-textarea {
    width: 100%;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    padding: 8px 10px;
    font-size: 18px;
    font-family: inherit;
    resize: vertical;
    min-height: 60px;
    margin-bottom: 8px;
    transition: border-color 0.3s ease;
}

.ud-reply-textarea:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
}

.ud-reply-actions {
    display: flex;
    gap: 8px;
}

.ud-reply-submit {
    background: #007bff;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 18px;
    transition: background 0.3s ease;
}

.ud-reply-submit:hover {
    background: #0056b3;
}

/* Replies toggle button (Facebook style) */
.ud-replies-toggle {
    margin: 8px 0 8px 52px;
}

.ud-view-replies-btn {
    background: none;
    border: none;
    color: #666;
    cursor: pointer;
    font-size: 18px;
    padding: 4px 8px;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: color 0.2s ease;
}

.ud-view-replies-btn:hover {
    color: #007bff;
}

.ud-view-replies-btn i {
    font-size: 16px;
}

.ud-reply-cancel {
    background: #6c757d;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 18px;
    transition: background 0.3s ease;
}

.ud-reply-cancel:hover {
    background: #5a6268;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Create Post
    document.getElementById('create-post-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;

        submitBtn.disabled = true;
        submitBtn.textContent = '{{ __("panel.publishing") }}...';

        fetch('{{ route(auth()->user()->role_name . ".create-post") }}', {
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
                alert('{{ __("panel.error_occurred") }}');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __("panel.error_occurred") }}');
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

            fetch('{{ route(auth()->user()->role_name . ".toggle-like") }}', {
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
                        icon.classList.remove('far');
                        icon.classList.add('fas');
                        this.classList.add('liked');
                    } else {
                        icon.classList.remove('fas');
                        icon.classList.add('far');
                        this.classList.remove('liked');
                    }
                    countSpan.textContent = data.likes_count;
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });

    // Toggle Comments with Animation
    document.querySelectorAll('.comment-toggle').forEach(btn => {
        btn.addEventListener('click', function() {
            const postId = this.getAttribute('data-post-id');
            const commentsDiv = document.getElementById('comments-' + postId);

            if (commentsDiv.style.display === 'none' || !commentsDiv.classList.contains('show')) {
                // Show with animation
                commentsDiv.style.display = 'block';
                commentsDiv.classList.remove('closing');
                commentsDiv.offsetHeight; // Trigger reflow
                commentsDiv.classList.add('show');
            } else {
                // Hide with animation
                commentsDiv.classList.remove('show');
                commentsDiv.classList.add('closing');
                setTimeout(() => {
                    commentsDiv.style.display = 'none';
                    commentsDiv.classList.remove('closing');
                }, 400);
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
            submitBtn.textContent = '{{ __("panel.commenting") }}...';

            fetch('{{ route(auth()->user()->role_name . ".add-comment") }}', {
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
                    alert('{{ __("panel.error_occurred") }}');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('{{ __("panel.error_occurred") }}');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
        });
    });

    // Handle reply button clicks
    document.querySelectorAll('.ud-reply-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            const replyForm = document.getElementById('reply-form-' + commentId);

            // Close other reply forms
            document.querySelectorAll('.ud-reply-form-wrapper').forEach(form => {
                if (form.id !== 'reply-form-' + commentId) {
                    form.style.display = 'none';
                }
            });

            // Toggle this reply form
            if (replyForm) {
                replyForm.style.display = replyForm.style.display === 'none' ? 'block' : 'none';
                if (replyForm.style.display === 'block') {
                    const textarea = replyForm.querySelector('.ud-reply-textarea');
                    if (textarea) textarea.focus();
                }
            }
        });
    });

    // Handle reply form submissions
    document.querySelectorAll('.ud-reply-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const commentId = this.getAttribute('data-comment-id');
            const submitBtn = this.querySelector('.ud-reply-submit');
            const originalText = submitBtn.textContent;

            formData.append('comment_id', commentId);

            submitBtn.disabled = true;
            submitBtn.textContent = '{{ __("panel.replying") }}...';

            fetch('/panel/student/community/reply', {
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
                    // Add reply to DOM
                    addReplyToDOM(commentId, data.reply);
                    // Hide form
                    document.getElementById('reply-form-' + commentId).style.display = 'none';
                } else {
                    alert(data.message || '{{ __("panel.error_occurred") }}');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('{{ __("panel.error_occurred") }}');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
        });
    });

    // Handle reply cancel buttons
    document.querySelectorAll('.ud-reply-cancel').forEach(btn => {
        btn.addEventListener('click', function() {
            const form = this.closest('.ud-reply-form');
            form.reset();
            form.closest('.ud-reply-form-wrapper').style.display = 'none';
        });
    });

    // Handle view replies toggle button (Facebook style)
    document.querySelectorAll('.ud-view-replies-btn').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.dataset.commentId;
            const repliesList = document.getElementById(`replies-${commentId}`);
            const icon = this.querySelector('i');

            if (repliesList.style.display === 'none') {
                repliesList.style.display = 'block';
                icon.classList.remove('fa-caret-down');
                icon.classList.add('fa-caret-up');
            } else {
                repliesList.style.display = 'none';
                icon.classList.remove('fa-caret-up');
                icon.classList.add('fa-caret-down');
            }
        });
    });

    function addReplyToDOM(commentId, reply) {
        const commentItem = document.querySelector(`[data-comment-id="${commentId}"]`);
        if (!commentItem) return;

        // Find or create replies toggle button
        let repliesToggle = commentItem.nextElementSibling;
        if (!repliesToggle || !repliesToggle.classList.contains('ud-replies-toggle')) {
            repliesToggle = document.createElement('div');
            repliesToggle.className = 'ud-replies-toggle';

            const toggleBtn = document.createElement('button');
            toggleBtn.className = 'ud-view-replies-btn';
            toggleBtn.dataset.commentId = commentId;
            toggleBtn.innerHTML = '<i class="fas fa-caret-down"></i> 1 {{ __("panel.replies") ?? "ردود" }}';

            toggleBtn.addEventListener('click', function() {
                const repliesList = document.getElementById(`replies-${commentId}`);
                const icon = this.querySelector('i');
                if (repliesList.style.display === 'none') {
                    repliesList.style.display = 'block';
                    icon.classList.remove('fa-caret-down');
                    icon.classList.add('fa-caret-up');
                } else {
                    repliesList.style.display = 'none';
                    icon.classList.remove('fa-caret-up');
                    icon.classList.add('fa-caret-down');
                }
            });

            repliesToggle.appendChild(toggleBtn);
            commentItem.after(repliesToggle);
        }

        // Update the count in toggle button
        const toggleBtn = repliesToggle.querySelector('.ud-view-replies-btn');
        const currentCount = parseInt(toggleBtn.textContent.match(/\d+/)[0]) || 0;
        toggleBtn.innerHTML = `<i class="fas fa-caret-down"></i> ${currentCount + 1} {{ __("panel.replies") ?? "ردود" }}`;

        // Find or create replies container
        let repliesContainer = document.getElementById(`replies-${commentId}`);
        if (!repliesContainer) {
            repliesContainer = document.createElement('div');
            repliesContainer.className = 'ud-replies-list';
            repliesContainer.id = `replies-${commentId}`;
            repliesContainer.style.display = 'none';
            repliesToggle.after(repliesContainer);
        }

        const replyDiv = document.createElement('div');
        replyDiv.className = 'ud-comment-item ud-reply';
        replyDiv.setAttribute('data-comment-id', reply.id);

        const avatar = document.createElement('img');
        avatar.setAttribute('data-src', reply.user_avatar);
        avatar.alt = 'Reply Avatar';
        avatar.className = 'ud-comment-avatar ud-reply-avatar';

        const bodyDiv = document.createElement('div');
        bodyDiv.className = 'ud-comment-body';

        const headerDiv = document.createElement('div');
        headerDiv.className = 'ud-comment-header';

        const author = document.createElement('b');
        author.className = 'ud-comment-author';
        author.textContent = reply.user_name;

        const time = document.createElement('small');
        time.className = 'ud-comment-time';
        time.textContent = reply.created_at;

        headerDiv.appendChild(author);
        headerDiv.appendChild(time);

        const textP = document.createElement('p');
        textP.className = 'ud-comment-text';
        textP.textContent = reply.content;

        bodyDiv.appendChild(headerDiv);
        bodyDiv.appendChild(textP);

        replyDiv.appendChild(avatar);
        replyDiv.appendChild(bodyDiv);

        repliesContainer.appendChild(replyDiv);

        // Show the replies automatically when a new one is added
        repliesContainer.style.display = 'block';
        const icon = toggleBtn.querySelector('i');
        icon.classList.remove('fa-caret-down');
        icon.classList.add('fa-caret-up');
    }
});
</script>