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
                    <div class="ud-post-actions">
                        <button class="like-btn {{ $post->isLikedBy(auth()->id()) ? 'liked' : '' }}" data-post-id="{{ $post->id }}">
                            <i class="fa-{{ $post->isLikedBy(auth()->id()) ? 'solid' : 'regular' }} fa-heart"></i>
                            <span class="likes-count">{{ $post->likesCount() }}</span>
                        </button>
                        <button class="comment-toggle" data-post-id="{{ $post->id }}">
                            <i class="far fa-comment"></i>
                            <span>{{ $post->commentsCount() }}</span>
                        </button>
                    </div>

                    <!-- Comments Section -->
                    <div class="ud-comments" id="comments-{{ $post->id }}" style="display: none;">
                        @foreach($post->comments as $comment)
                            <div class="ud-comment">
                                <img data-src="{{ $comment->user->photo_url }}" alt="Comment Avatar">
                                <div class="ud-comment-content">
                                    <b>{{ $comment->user->name }}</b>
                                    <p>{{ $comment->content }}</p>
                                    <small>{{ $comment->created_at->format('h:i A · M d, Y') }}</small>
                                </div>
                            </div>
                        @endforeach

                        <!-- Add Comment Form -->
                        <div class="ud-add-comment">
                            <form class="add-comment-form" data-post-id="{{ $post->id }}">
                                @csrf
                                <input type="hidden" name="post_id" value="{{ $post->id }}">
                                <textarea name="content" placeholder="{{ __('panel.write_comment') }}" required maxlength="500"></textarea>
                                <button type="submit" class="ud-primary">{{ __('panel.comment') }}</button>
                            </form>
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
});
</script>