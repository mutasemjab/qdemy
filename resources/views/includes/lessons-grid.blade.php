<!-- includes/lessons-grid.blade.php -->
<div class="lessons-grid">
    @foreach($lessons as $lesson)
        <div class="lesson-card" data-lesson-id="{{ $lesson->id }}">
            <div class="lesson-thumbnail">
                @if($lesson->isValidYoutubeUrl())
                    <img src="{{ $lesson->youtube_thumbnail }}" alt="{{ $lesson->name }}" class="thumbnail-image">
                    <div class="play-overlay">
                        <i class="fas fa-play"></i>
                    </div>
                @else
                    <div class="invalid-video">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>رابط غير صحيح</span>
                    </div>
                @endif
            </div>
            
            <div class="lesson-info">
                <h3 class="lesson-name">{{ $lesson->name }}</h3>
                <div class="lesson-meta">
                  
                    <span class="lesson-date">
                        <i class="fas fa-calendar"></i>
                        {{ $lesson->formatted_date }}
                    </span>
                </div>
            </div>
            
            @if($lesson->isValidYoutubeUrl())
                <div class="lesson-actions">
                   
                    <a href="{{ $lesson->watch_url }}" target="_blank" class="btn-lesson-action btn-youtube">
                        <i class="fab fa-youtube"></i>
                        يوتيوب
                    </a>
                </div>
            @else
                <div class="lesson-actions">
                    <button class="btn-lesson-action btn-disabled" disabled>
                        <i class="fas fa-times"></i>
                        غير متاح
                    </button>
                </div>
            @endif
        </div>
    @endforeach
</div>

<!-- Video Modal -->
<div id="videoModal" class="video-modal">
    <div class="video-modal-content">
        <div class="video-modal-header">
            <h3 id="videoTitle">عنوان الدرس</h3>
            <button class="video-modal-close">&times;</button>
        </div>
        <div class="video-container">
            <iframe id="videoIframe" src="" frameborder="0" allowfullscreen></iframe>
        </div>
    </div>
</div>

<script>
// Video modal functionality
function openVideo(embedUrl, title) {
    const modal = document.getElementById('videoModal');
    const iframe = document.getElementById('videoIframe');
    const titleElement = document.getElementById('videoTitle');
    
    iframe.src = embedUrl;
    titleElement.textContent = title;
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeVideo() {
    const modal = document.getElementById('videoModal');
    const iframe = document.getElementById('videoIframe');
    
    iframe.src = '';
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Close modal events
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('videoModal');
    const closeBtn = document.querySelector('.video-modal-close');
    
    closeBtn.addEventListener('click', closeVideo);
    
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeVideo();
        }
    });
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeVideo();
        }
    });
});
</script>