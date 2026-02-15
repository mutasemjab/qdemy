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
                            @if ($post->approvedComments->count() > 0)
                                @foreach ($post->approvedComments->take(2) as $comment)
                                    <div class="fb-comment" data-comment-id="{{ $comment->id }}">
                                        <img class="fb-comment-avatar" 
                                             src="{{ $comment->user->photo_url ?? asset('assets_front/images/Profile-picture.jpg') }}"
                                             alt="">
                                        <div class="fb-comment-content">
                                            <div class="fb-comment-bubble">
                                                <b class="fb-comment-author">{{ $comment->user->name }}</b>
                                                <p class="fb-comment-text">{{ $comment->content }}</p>
                                            </div>
                                            <div class="fb-comment-meta">
                                                <small class="fb-comment-time">{{ $comment->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                        @auth
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
            });

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