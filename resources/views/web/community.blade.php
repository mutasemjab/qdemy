@extends('layouts.app')
@section('title', __('front.title'))

@section('content')
    <section class="cmty-page">
         <div data-aos="flip-up" data-aos-duration="1000" class="anim animate-glow universities-header-wrapper">
            <div class="universities-header">
                <h2>{{ __('front.header') }}</h2>
            </div>
        </div>

        <!-- Post Creation Form (for logged-in users) -->
        @auth
            <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200" class="fb-create-post-card">
                <form action="{{ route('community.store') }}" method="POST">
                    @csrf
                    <div class="fb-post-composer">
                        <div class="fb-composer-header">
                            <img class="fb-user-avatar" 
                                 src="{{ Auth::user()->photo_url ?? asset('assets_front/images/Profile-picture.jpg') }}"
                                 alt="">
                            <input type="text" class="fb-composer-input" 
                                   placeholder="{{ __('front.write_post') }}" 
                                   readonly 
                                   onclick="this.nextElementSibling.focus()">
                            <textarea class="fb-composer-textarea" 
                                      name="content" 
                                      placeholder="{{ __('front.write_post') }}" 
                                      required 
                                      maxlength="1000" 
                                      style="display: none;"></textarea>
                        </div>
                        <div class="fb-composer-actions">
                            <button type="submit" class="fb-post-btn">{{ __('front.submit_post') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        @endauth

        <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200" class="fb-feed">
            @forelse($posts as $post)
                <article class="fb-post-card">
                    <div class="fb-post-header">
                        <img class="fb-post-avatar" 
                             src="{{ $post->user->photo_url ?? asset('assets_front/images/Profile-picture.jpg') }}"
                             alt="">
                        <div class="fb-post-user-info">
                            <h4 class="fb-user-name">{{ $post->user->name }}</h4>
                            <time class="fb-post-time">{{ $post->created_at->format('g:i A · M j, Y') }}</time>
                        </div>
                    </div>

                    <div class="fb-post-content">
                        <p class="fb-post-text">{{ $post->content }}</p>
                    </div>

                    <div class="fb-post-stats">
                        <span class="fb-likes-count">
                            <i class="fas fa-thumbs-up fb-like-icon-blue"></i>
                            <span>{{ $post->likes->count() }}</span>
                        </span>
                        @if ($post->approvedComments->count() > 0)
                            <span class="fb-comments-count">
                                {{ $post->approvedComments->count() }} {{ __('front.comments') ?? 'comments' }}
                            </span>
                        @endif
                    </div>

                    <div class="fb-post-actions">
                        <button class="fb-action-btn fb-like-btn {{ Auth::check() && $post->isLikedBy(Auth::id()) ? 'fb-liked' : '' }}"
                                data-post-id="{{ $post->id }}" 
                                {{ !Auth::check() ? 'disabled' : '' }}>
                            <i class="fas fa-thumbs-up"></i>
                            <span class="like-text">
                                {{ Auth::check() && $post->isLikedBy(Auth::id()) ? __('front.liked') : __('front.like') }}
                            </span>
                        </button>
                        <button class="fb-action-btn fb-comment-btn">
                            <i class="fas fa-comment-alt"></i>
                            <span>{{ __('front.comment') ?? 'Comment' }}</span>
                        </button>
                    </div>

                    <!-- Comments Section -->
                    <div class="fb-comments-section">
                        @auth
                            <!-- Comment Form -->
                            <form class="fb-comment-form" 
                                  action="{{ route('community.comments.store', $post) }}"
                                  method="POST">
                                @csrf
                                <img class="fb-comment-avatar" 
                                     src="{{ Auth::user()->photo_url ?? asset('assets_front/images/Profile-picture.jpg') }}"
                                     alt="">
                                <div class="fb-comment-input-wrapper">
                                    <input class="fb-comment-input" 
                                           name="content" 
                                           type="text"
                                           placeholder="{{ __('front.add_comment') }}" 
                                           required 
                                           maxlength="500">
                                    <button type="submit" class="fb-comment-submit">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="fb-comment-form">
                                <img class="fb-comment-avatar" 
                                     src="{{ asset('assets_front/images/Profile-picture.jpg') }}"
                                     alt="">
                                <input class="fb-comment-input fb-comment-disabled" 
                                       type="text" 
                                       placeholder="{{ __('front.login_to_comment') }}" 
                                       disabled>
                            </div>
                        @endauth

                        <div class="fb-comments-list">
                            @if ($post->approvedComments->whereNull('parent_id')->count() > 0)
                                @foreach ($post->approvedComments->whereNull('parent_id')->take(2) as $comment)
                                    <div class="fb-comment" data-comment-id="{{ $comment->id }}">
                                        <img class="fb-comment-avatar"
                                             src="{{ $comment->user->photo_url ?? asset('assets_front/images/Profile-picture.jpg') }}"
                                             alt="">
                                        <div class="fb-comment-content" style="position: relative;">
                                            <div class="fb-comment-bubble">
                                                <b class="fb-comment-author">{{ $comment->user->name }}</b>
                                                <p class="fb-comment-text">{{ $comment->content }}</p>
                                            </div>
                                            <div class="fb-comment-meta">
                                                <small class="fb-comment-time">{{ $comment->created_at->diffForHumans() }}</small>
                                                @auth
                                                    <button class="fb-reply-btn" data-comment-id="{{ $comment->id }}">
                                                        <i class="fas fa-reply"></i> {{ __('front.reply') }}
                                                    </button>
                                                    @if ($comment->canBeDeletedBy(Auth::id()))
                                                        <button class="fb-delete-comment"
                                                                data-comment-id="{{ $comment->id }}"
                                                                title="{{ __('front.delete_comment') }}"
                                                                onclick="deleteComment({{ $comment->id }}, this)">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    @endif
                                                @endauth
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Replies count toggle button (like Facebook) -->
                                    @if ($comment->replies && $comment->replies->count() > 0)
                                        <div class="fb-replies-toggle">
                                            <button class="fb-view-replies-btn" data-comment-id="{{ $comment->id }}">
                                                <i class="fas fa-caret-down"></i>
                                                {{ $comment->replies->count() }} {{ __('front.replies') ?? 'ردود' }}
                                            </button>
                                        </div>
                                        <!-- Replies list (hidden by default) -->
                                        <div class="fb-replies-list" id="replies-{{ $comment->id }}" style="display: none;">
                                            @foreach ($comment->replies as $reply)
                                                <div class="fb-comment fb-reply" data-comment-id="{{ $reply->id }}">
                                                    <img class="fb-comment-avatar fb-reply-avatar"
                                                         src="{{ $reply->user->photo_url ?? asset('assets_front/images/Profile-picture.jpg') }}"
                                                         alt="">
                                                    <div class="fb-comment-content">
                                                        <div class="fb-comment-bubble fb-reply-bubble">
                                                            <b class="fb-comment-author">{{ $reply->user->name }}</b>
                                                            <p class="fb-comment-text">{{ $reply->content }}</p>
                                                        </div>
                                                        <div class="fb-comment-meta">
                                                            <small class="fb-comment-time">{{ $reply->created_at->diffForHumans() }}</small>
                                                            @auth
                                                                @if ($reply->canBeDeletedBy(Auth::id()))
                                                                    <button class="fb-delete-comment fb-delete-reply"
                                                                            data-comment-id="{{ $reply->id }}"
                                                                            title="{{ __('front.delete_comment') }}"
                                                                            onclick="deleteComment({{ $reply->id }}, this)">
                                                                        <i class="fas fa-trash-alt"></i>
                                                                    </button>
                                                                @endif
                                                            @endauth
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Reply form (hidden by default) -->
                                    @auth
                                        <div class="fb-reply-form-wrapper" id="reply-form-{{ $comment->id }}" style="display: none;">
                                            <form class="fb-reply-form" data-comment-id="{{ $comment->id }}">
                                                @csrf
                                                <img class="fb-comment-avatar fb-reply-avatar"
                                                     src="{{ Auth::user()->photo_url ?? asset('assets_front/images/Profile-picture.jpg') }}"
                                                     alt="">
                                                <div class="fb-comment-input-wrapper">
                                                    <input class="fb-comment-input fb-reply-input"
                                                           name="content"
                                                           type="text"
                                                           placeholder="{{ __('front.write_reply') }}"
                                                           required
                                                           maxlength="500">
                                                    <button type="submit" class="fb-comment-submit fb-reply-submit">
                                                        <i class="fas fa-paper-plane"></i>
                                                    </button>
                                                    <button type="button" class="fb-reply-cancel">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    @endauth
                                @endforeach

                                @if ($post->approvedComments->count() > 2)
                                    <div class="fb-view-more-comments">
                                        <button class="fb-view-more-btn" data-post-id="{{ $post->id }}">
                                            <i class="fas fa-chevron-down"></i>
                                            {{ __('front.view_all_comments') }} ({{ $post->approvedComments->count() }})
                                        </button>
                                    </div>
                                @endif
                            @else
                                <div class="fb-no-comments">
                                    <p><small>{{ __('front.no_comments_yet') }}</small></p>
                                </div>
                            @endif
                        </div>

                        <!-- Hidden container for additional comments -->
                        <div class="fb-comments-all" id="comments-{{ $post->id }}" style="display: none;"></div>
                    </div>
                </article>
            @empty
                <div class="fb-no-posts">
                    <div class="fb-no-posts-content">
                        <i class="fas fa-comments fa-3x"></i>
                        <h3>{{ __('front.no_posts') }}</h3>
                        <p>{{ __('front.no_posts_description') }}</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="fb-pagination">
            {{ $posts->links() }}
        </div>
    </section>

    <style>
    /* Improved Community Reply Styles */
    .fb-comment {
        display: flex;
        gap: 12px;
        margin-bottom: 12px;
        position: relative;
    }

    .fb-comment-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
        border: 2px solid #e0e0e0;
    }

    .fb-comment-content {
        flex: 1;
        min-width: 0;
    }

    .fb-comment-bubble {
        background: #f5f5f5;
        padding: 12px 16px;
        border-radius: 18px;
        display: inline-block;
        max-width: 100%;
    }

    .fb-comment-author {
        display: block;
        font-weight: 600;
        color: #333;
        margin-bottom: 4px;
        font-size: 14px;
    }

    .fb-comment-text {
        margin: 0;
        color: #555;
        font-size: 15px;
        line-height: 1.4;
        word-break: break-word;
    }

    .fb-comment-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 6px;
        padding-left: 4px;
    }

    .fb-comment-time {
        color: #999;
        font-size: 13px;
    }

    .fb-reply-btn {
        background: #f0f2f5;
        border: none;
        color: #555;
        cursor: pointer;
        font-size: 12px;
        padding: 4px 10px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        gap: 4px;
        transition: all 0.2s ease;
        font-weight: 500;
    }

    .fb-reply-btn:hover {
        background: #e4e6eb;
        color: #007bff;
    }

    .fb-reply-btn i {
        font-size: 11px;
    }

    /* Delete Comment Button - Inline version */
    .fb-delete-comment {
        background: #f0f2f5;
        border: none;
        color: #999;
        cursor: pointer;
        font-size: 11px;
        padding: 4px 8px;
        border-radius: 50%;
        width: 26px;
        height: 26px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .fb-delete-comment:hover {
        background: #ffe5e5;
        color: #ff4757;
    }

    .fb-delete-comment i {
        font-size: 11px;
    }

    /* Replies Toggle Button */
    .fb-replies-toggle {
        margin: 8px 0 8px 52px;
    }

    .fb-view-replies-btn {
        background: none;
        border: none;
        color: #666;
        cursor: pointer;
        font-size: 14px;
        padding: 4px 8px;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: color 0.2s ease;
    }

    .fb-view-replies-btn:hover {
        color: #007bff;
    }

    .fb-view-replies-btn i {
        font-size: 14px;
    }

    /* Reply Styles */
    .fb-reply {
        margin-top: 8px;
    }

    .fb-reply-avatar {
        width: 32px !important;
        height: 32px !important;
        border-width: 1px !important;
    }

    .fb-reply-bubble {
        background: #e9e9e9 !important;
        padding: 10px 14px !important;
        border-radius: 16px !important;
    }

    .fb-reply .fb-comment-author {
        font-size: 13px !important;
    }

    .fb-reply .fb-comment-text {
        font-size: 14px !important;
    }

    .fb-reply .fb-comment-time {
        font-size: 12px !important;
    }

    /* Reply Form */
    .fb-reply-form-wrapper {
        margin: 10px 0 10px 52px;
        padding: 12px;
        background: #f9f9f9;
        border-radius: 12px;
        border: 1px solid #e0e0e0;
    }

    .fb-reply-form {
        display: flex;
        gap: 10px;
        align-items: flex-start;
    }

    .fb-reply-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
        border: 1px solid #e0e0e0;
    }

    .fb-comment-input-wrapper {
        flex: 1;
        display: flex;
        gap: 8px;
        align-items: center;
        position: relative;
    }

    .fb-comment-input,
    .fb-reply-input {
        flex: 1;
        padding: 8px 45px 8px 12px;
        border: 1px solid #e0e0e0;
        border-radius: 20px;
        font-size: 14px;
        outline: none;
        transition: border-color 0.3s ease;
    }

    /* For RTL languages, reverse padding */
    html[dir="rtl"] .fb-comment-input,
    html[dir="rtl"] .fb-reply-input {
        padding: 8px 12px 8px 45px;
    }

    .fb-comment-input:focus,
    .fb-reply-input:focus {
        border-color: #007bff;
    }

    .fb-comment-submit,
    .fb-reply-submit {
        position: absolute;
        right: 6px;
        top: 50%;
        transform: translateY(-50%);
        background: #007bff;
        color: white;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.3s ease;
        flex-shrink: 0;
    }

    /* For RTL, move to left */
    html[dir="rtl"] .fb-comment-submit,
    html[dir="rtl"] .fb-reply-submit {
        right: auto;
        left: 6px;
    }

    /* When there's a cancel button, adjust positions */
    .fb-reply-form .fb-comment-submit {
        right: 44px;
    }

    html[dir="rtl"] .fb-reply-form .fb-comment-submit {
        right: auto;
        left: 44px;
    }

    .fb-reply-cancel {
        position: absolute;
        right: 6px;
        top: 50%;
        transform: translateY(-50%);
        background: #6c757d;
        color: white;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.3s ease;
        flex-shrink: 0;
    }

    /* For RTL, move cancel to left (same side as submit) */
    html[dir="rtl"] .fb-reply-cancel {
        right: auto;
        left: 6px;
    }

    .fb-comment-submit:hover,
    .fb-reply-submit:hover {
        background: #0056b3;
    }

    .fb-comment-submit i,
    .fb-reply-submit i {
        font-size: 14px;
    }

    .fb-reply-cancel:hover {
        background: #5a6268;
    }

    .fb-reply-cancel i {
        font-size: 11px;
    }

    /* Replies List */
    .fb-replies-list {
        margin-left: 52px;
        margin-top: 8px;
    }

    html[dir="rtl"] .fb-replies-list {
        margin-left: 0;
        margin-right: 52px;
    }
    </style>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Handle composer input click to show textarea
                document.querySelectorAll('.fb-composer-input').forEach(input => {
                    input.addEventListener('click', function() {
                        const textarea = this.nextElementSibling;
                        this.style.display = 'none';
                        textarea.style.display = 'block';
                        textarea.focus();
                    });
                });

                // Handle like button clicks
                document.querySelectorAll('.fb-like-btn').forEach(button => {
                    if (!button.disabled) {
                        button.addEventListener('click', function() {
                            const postId = this.dataset.postId;
                            const likeText = this.querySelector('.like-text');
                            const statsLikesCount = this.closest('.fb-post-card').querySelector('.fb-likes-count span');

                            fetch(`{{ route('community.posts.toggle-like', ':postId') }}`.replace(':postId', postId), {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.liked) {
                                        this.classList.add('fb-liked');
                                        likeText.textContent = '{{ __('front.liked') }}';
                                    } else {
                                        this.classList.remove('fb-liked');
                                        likeText.textContent = '{{ __('front.like') }}';
                                    }
                                    if (statsLikesCount) {
                                        statsLikesCount.textContent = data.likes_count;
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                        });
                    }
                });

                // Handle comment form submission
                document.querySelectorAll('.fb-comment-form').forEach(form => {
                    const input = form.querySelector('.fb-comment-input');
                    const submitBtn = form.querySelector('.fb-comment-submit');

                    if (input && submitBtn) {
                        // Submit on Enter key
                        input.addEventListener('keypress', function(e) {
                            if (e.key === 'Enter') {
                                e.preventDefault();
                                if (this.value.trim()) {
                                    form.submit();
                                }
                            }
                        });

                        // Submit on button click
                        submitBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            if (input.value.trim()) {
                                form.submit();
                            }
                        });
                    }
                });

                // Handle "View all comments" button
                document.querySelectorAll('.fb-view-more-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const postId = this.dataset.postId;
                        const commentsContainer = document.getElementById(`comments-${postId}`);
                        const currentComments = this.closest('.fb-post-card').querySelector('.fb-comments-list');

                        if (commentsContainer.style.display === 'none') {
                            // Load all comments
                            fetch(`{{ route('community.posts.comments', ':postId') }}`.replace(':postId', postId))
                                .then(response => response.json())
                                .then(data => {
                                    commentsContainer.innerHTML = '';
                                    data.comments.forEach(comment => {
                                        const commentDiv = document.createElement('div');
                                        commentDiv.className = 'fb-comment';
                                        commentDiv.dataset.commentId = comment.id;
                                        let deleteBtn = '';
                                        if (comment.can_delete) {
                                            deleteBtn = `<button class="fb-delete-comment" data-comment-id="${comment.id}" title="{{ __('front.delete_comment') }}" onclick="deleteComment(${comment.id}, this)">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>`;
                                        }
                                        commentDiv.innerHTML = `
                                            <img class="fb-comment-avatar" src="${comment.user_avatar}" alt="">
                                            <div class="fb-comment-content">
                                                <div class="fb-comment-bubble">
                                                    <b class="fb-comment-author">${comment.user_name}</b>
                                                    <p class="fb-comment-text">${comment.content}</p>
                                                </div>
                                                <div class="fb-comment-meta">
                                                    <small class="fb-comment-time">${comment.created_at}</small>
                                                </div>
                                            </div>
                                            ${deleteBtn}
                                        `;
                                        commentsContainer.appendChild(commentDiv);
                                    });

                                    currentComments.style.display = 'none';
                                    commentsContainer.style.display = 'block';
                                    this.innerHTML = '<i class="fas fa-chevron-up"></i> {{ __('front.hide_comments') }}';
                                });
                        } else {
                            // Hide all comments, show only first 2
                            currentComments.style.display = 'block';
                            commentsContainer.style.display = 'none';
                            this.innerHTML = '<i class="fas fa-chevron-down"></i> {{ __('front.view_all_comments') }}';
                        }
                    });
                });

                // Handle comment button click to focus input
                document.querySelectorAll('.fb-comment-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const commentInput = this.closest('.fb-post-card').querySelector('.fb-comment-input');
                        if (commentInput) {
                            commentInput.focus();
                        }
                    });
                });

                // Handle reply button clicks
                attachReplyListeners();

                // Handle view replies toggle button (Facebook style)
                document.querySelectorAll('.fb-view-replies-btn').forEach(button => {
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
            });

            // Attach listeners to reply buttons and forms
            function attachReplyListeners() {
                // Reply button clicks
                document.querySelectorAll('.fb-reply-btn').forEach(button => {
                    button.removeEventListener('click', handleReplyButtonClick);
                    button.addEventListener('click', handleReplyButtonClick);
                });

                // Reply form submissions
                document.querySelectorAll('.fb-reply-form').forEach(form => {
                    form.removeEventListener('submit', handleReplyFormSubmit);
                    form.addEventListener('submit', handleReplyFormSubmit);

                    const input = form.querySelector('.fb-reply-input');
                    const cancelBtn = form.querySelector('.fb-reply-cancel');

                    // Submit on Enter key
                    if (input) {
                        input.removeEventListener('keypress', handleReplyKeyPress);
                        input.addEventListener('keypress', handleReplyKeyPress);
                    }

                    // Cancel button
                    if (cancelBtn) {
                        cancelBtn.removeEventListener('click', handleReplyCancel);
                        cancelBtn.addEventListener('click', handleReplyCancel);
                    }
                });
            }

            function handleReplyButtonClick(e) {
                e.preventDefault();
                const commentId = this.dataset.commentId;
                const replyForm = document.getElementById(`reply-form-${commentId}`);

                // Close other reply forms
                document.querySelectorAll('.fb-reply-form-wrapper').forEach(form => {
                    if (form.id !== `reply-form-${commentId}`) {
                        form.style.display = 'none';
                    }
                });

                // Toggle this reply form
                if (replyForm) {
                    replyForm.style.display = replyForm.style.display === 'none' ? 'block' : 'none';
                    if (replyForm.style.display === 'block') {
                        const input = replyForm.querySelector('.fb-reply-input');
                        if (input) input.focus();
                    }
                }
            }

            function handleReplyFormSubmit(e) {
                e.preventDefault();
                const form = e.target;
                const input = form.querySelector('.fb-reply-input');
                const commentId = form.dataset.commentId;

                if (!input.value.trim()) return;

                const formData = new FormData(form);
                formData.append('comment_id', commentId);

                fetch('/community/comments/' + commentId + '/reply', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Add reply to the DOM
                            addReplyToDOM(commentId, data.reply);
                            // Clear input and hide form
                            input.value = '';
                            document.getElementById(`reply-form-${commentId}`).style.display = 'none';
                        } else {
                            alert(data.message || 'Error adding reply');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error adding reply');
                    });
            }

            function handleReplyKeyPress(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const form = e.target.closest('.fb-reply-form');
                    if (e.target.value.trim()) {
                        form.dispatchEvent(new Event('submit'));
                    }
                }
            }

            function handleReplyCancel(e) {
                const form = e.target.closest('.fb-reply-form');
                const wrapper = form.closest('.fb-reply-form-wrapper');
                form.querySelector('.fb-reply-input').value = '';
                wrapper.style.display = 'none';
            }

            function addReplyToDOM(commentId, reply) {
                // Find the comment element
                const commentElement = document.querySelector(`.fb-comment[data-comment-id="${commentId}"]`);
                if (!commentElement) return;

                // Find or create replies toggle button
                let repliesToggle = commentElement.nextElementSibling;
                if (!repliesToggle || !repliesToggle.classList.contains('fb-replies-toggle')) {
                    // Create the toggle button container
                    repliesToggle = document.createElement('div');
                    repliesToggle.className = 'fb-replies-toggle';

                    const toggleBtn = document.createElement('button');
                    toggleBtn.className = 'fb-view-replies-btn';
                    toggleBtn.dataset.commentId = commentId;
                    toggleBtn.innerHTML = '<i class="fas fa-caret-down"></i> 1 {{ __("front.replies") ?? "ردود" }}';

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
                    commentElement.after(repliesToggle);
                }

                // Update the count in toggle button
                const toggleBtn = repliesToggle.querySelector('.fb-view-replies-btn');
                const currentCount = parseInt(toggleBtn.textContent.match(/\d+/)[0]) || 0;
                toggleBtn.innerHTML = `<i class="fas fa-caret-down"></i> ${currentCount + 1} {{ __("front.replies") ?? "ردود" }}`;

                // Find or create replies container
                let repliesContainer = document.getElementById(`replies-${commentId}`);
                if (!repliesContainer) {
                    repliesContainer = document.createElement('div');
                    repliesContainer.className = 'fb-replies-list';
                    repliesContainer.id = `replies-${commentId}`;
                    repliesContainer.style.display = 'none';
                    repliesToggle.after(repliesContainer);
                }

                const replyDiv = document.createElement('div');
                replyDiv.className = 'fb-comment fb-reply';
                replyDiv.dataset.commentId = reply.id;

                const avatar = document.createElement('img');
                avatar.className = 'fb-comment-avatar fb-reply-avatar';
                avatar.src = reply.user_avatar;
                avatar.alt = '';

                const contentDiv = document.createElement('div');
                contentDiv.className = 'fb-comment-content';

                const bubbleDiv = document.createElement('div');
                bubbleDiv.className = 'fb-comment-bubble fb-reply-bubble';

                const author = document.createElement('b');
                author.className = 'fb-comment-author';
                author.textContent = reply.user_name;

                const text = document.createElement('p');
                text.className = 'fb-comment-text';
                text.textContent = reply.content;

                const metaDiv = document.createElement('div');
                metaDiv.className = 'fb-comment-meta';

                const time = document.createElement('small');
                time.className = 'fb-comment-time';
                time.textContent = reply.created_at;

                bubbleDiv.appendChild(author);
                bubbleDiv.appendChild(text);
                contentDiv.appendChild(bubbleDiv);
                metaDiv.appendChild(time);
                contentDiv.appendChild(metaDiv);

                replyDiv.appendChild(avatar);
                replyDiv.appendChild(contentDiv);

                if (reply.can_delete) {
                    const deleteBtn = document.createElement('button');
                    deleteBtn.className = 'fb-delete-comment fb-delete-reply';
                    deleteBtn.dataset.commentId = reply.id;
                    deleteBtn.title = '{{ __('front.delete_comment') }}';
                    deleteBtn.innerHTML = '<i class="fas fa-trash-alt"></i>';
                    deleteBtn.onclick = function() { deleteComment(reply.id, this); };
                    replyDiv.appendChild(deleteBtn);
                }

                repliesContainer.appendChild(replyDiv);

                // Show the replies automatically when a new one is added
                repliesContainer.style.display = 'block';
                const icon = toggleBtn.querySelector('i');
                icon.classList.remove('fa-caret-down');
                icon.classList.add('fa-caret-up');
            }

            // Delete comment function
            function deleteComment(commentId, buttonElement) {
                if (!confirm('{{ __('front.confirm_delete_comment') }}')) {
                    return;
                }

                const commentElement = buttonElement.closest('.fb-comment');
                const deleteUrl = `{{ route('community.comments.destroy', ':commentId') }}`.replace(':commentId', commentId);

                fetch(deleteUrl, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Animate and remove the comment
                            commentElement.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                            commentElement.style.opacity = '0';
                            commentElement.style.transform = 'translateX(20px)';
                            setTimeout(() => {
                                commentElement.remove();
                            }, 300);
                        } else {
                            alert(data.message || '{{ __('front.error_deleting_comment') }}');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('{{ __('front.error_deleting_comment') }}');
                    });
            }
        </script>
    @endpush

@endsection