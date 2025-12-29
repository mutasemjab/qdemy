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
    <div  data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200"  class="cmty-create-post">
        <form action="{{ route('community.store') }}" method="POST">
            @csrf
            <div class="cmty-post cmty-post--outlined">
                <header class="cmty-head">
                    <img class="cmty-mark" src="{{ asset('assets_front/images/community-logo1.png') }}" alt="">
                    <div class="cmty-user">
                        <div>
                            <h4>{{ Auth::user()->name }}</h4>
                        </div>
                        <img src="{{ Auth::user()->photo_url ?? asset('assets_front/images/Profile-picture.jpg') }}" alt="">
                    </div>
                </header>
                
                <textarea class="cmty-textarea" name="content" placeholder="{{ __('front.write_post') }}" required maxlength="1000"></textarea>
                
                <div class="cmty-actions">
                    <button type="submit" class="cmty-submit-btn">{{ __('front.submit_post') }}</button>
                </div>
            </div>
        </form>
    </div>
    @endauth

    <div  data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200"  class="cmty-feed">
        @forelse($posts as $post)
        <article class="cmty-post {{ $loop->first ? 'cmty-post--outlined' : '' }}">
            <header class="cmty-head">
                <img class="cmty-mark" src="{{ asset('assets_front/images/community-logo1.png') }}" alt="">
                <div class="cmty-user">
                    <div>
                        <h4>{{ $post->user->name }}</h4>
                    </div>
                    <img src="{{ $post->user->photo_url ?? asset('assets_front/images/Profile-picture.jpg') }}" alt="">
                </div>
            </header>

            <p class="cmty-text">
                {{ $post->content }}
            </p>
            <time>{{ $post->created_at->format('g:i A · M j, Y') }}</time>

            <div class="cmty-actions">
                @auth
                <!-- Comment Form -->
                <form class="cmty-comment-form" action="{{ route('community.comments.store', $post) }}" method="POST">
                    @csrf
                    <div class="comment-input-container">
                        <input class="cmty-input" name="content" type="text" placeholder="{{ __('front.add_comment') }}" required maxlength="500">
                        <button type="submit" class="cmty-comment-btn">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
                @else
                <input class="cmty-input" type="text" placeholder="{{ __('front.login_to_comment') }}" disabled>
                @endauth

                <!-- Like Button -->
                <button 
                    class="cmty-like {{ Auth::check() && $post->isLikedBy(Auth::id()) ? 'liked' : '' }}" 
                    data-post-id="{{ $post->id }}"
                    {{ !Auth::check() ? 'disabled' : '' }}
                >
                    <i class="fas fa-thumbs-up"></i> 
                    <span class="like-text">
                        {{ Auth::check() && $post->isLikedBy(Auth::id()) ? __('front.liked') : __('front.like') }}
                    </span>
                    <span class="likes-count">({{ $post->likes->count() }})</span>
                </button>
            </div>

            <!-- Comments Section -->
            <div class="cmty-comments">
                @if($post->approvedComments->count() > 0)
                    @foreach($post->approvedComments->take(2) as $comment)
                    <div class="cmty-comment">
                        <img src="{{ $comment->user->photo_url ?? asset('assets_front/images/Profile-picture.jpg') }}" alt="">
                        <div>
                            <b>{{ $comment->user->name }}</b>
                            <p>{{ $comment->content }}</p>
                            <small class="comment-time">{{ $comment->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    @endforeach

                    @if($post->approvedComments->count() > 2)
                    <div class="cmty-more-sec">
                        <button class="cmty-more" data-post-id="{{ $post->id }}">
                            {{ __('front.view_all_comments') }} ({{ $post->approvedComments->count() }}) ←
                        </button>
                    </div>
                    @endif
                @else
                    <!-- Show message when no approved comments yet -->
                    <div class="no-comments-msg">
                        <p><small>{{ __('front.no_comments_yet') }}</small></p>
                    </div>
                @endif
            </div>

            <!-- Hidden container for additional comments -->
            <div class="cmty-comments-all" id="comments-{{ $post->id }}" style="display: none;"></div>
        </article>
        @empty
        <div class="cmty-no-posts">
            <h3>{{ __('front.no_posts') }}</h3>
            <p>{{ __('front.no_posts_description') }}</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="cmty-pagination">
        {{ $posts->links() }}
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle like button clicks
    document.querySelectorAll('.cmty-like').forEach(button => {
        if (!button.disabled) {
            button.addEventListener('click', function() {
                const postId = this.dataset.postId;
                const likeText = this.querySelector('.like-text');
                const likesCount = this.querySelector('.likes-count');
                
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
                        this.classList.add('liked');
                        likeText.textContent = '{{ __('front.liked') }}';
                    } else {
                        this.classList.remove('liked');
                        likeText.textContent = '{{ __('front.like') }}';
                    }
                    likesCount.textContent = `(${data.likes_count})`;
                })
                .catch(error => console.error('Error:', error));
            });
        }
    });

    // Handle comment form submission
    document.querySelectorAll('.cmty-comment-form').forEach(form => {
        const input = form.querySelector('.cmty-input');
        const submitBtn = form.querySelector('.cmty-comment-btn');
        
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
    });

    // Handle "View all comments" button
    document.querySelectorAll('.cmty-more').forEach(button => {
        button.addEventListener('click', function() {
            const postId = this.dataset.postId;
            const commentsContainer = document.getElementById(`comments-${postId}`);
            const currentComments = this.closest('.cmty-post').querySelector('.cmty-comments');
            
            if (commentsContainer.style.display === 'none') {
                // Load all comments
                fetch(`{{ route('community.posts.comments', ':postId') }}`.replace(':postId', postId))
                    .then(response => response.json())
                    .then(data => {
                        commentsContainer.innerHTML = '';
                        data.comments.forEach(comment => {
                            const commentDiv = document.createElement('div');
                            commentDiv.className = 'cmty-comment';
                            commentDiv.innerHTML = `
                                <img src="${comment.user_avatar}" alt="">
                                <div>
                                    <b>${comment.user_name}</b>
                                    <p>${comment.content}</p>
                                    <small class="comment-time">${comment.created_at}</small>
                                </div>
                            `;
                            commentsContainer.appendChild(commentDiv);
                        });
                        
                        currentComments.style.display = 'none';
                        commentsContainer.style.display = 'block';
                        this.textContent = '{{ __('front.hide_comments') }} ←';
                    });
            } else {
                // Hide all comments, show only first 2
                currentComments.style.display = 'block';
                commentsContainer.style.display = 'none';
                this.textContent = `{{ __('front.view_all_comments') }} ←`;
            }
        });
    });
});
</script>
@endpush



@endsection