<div class="essay-answer">
    <label for="essayAnswer" class="answer-label">اكتب إجابتك:</label>
    <textarea id="essayAnswer" name="answer" class="essay-textarea" 
              placeholder="اكتب إجابتك التفصيلية هنا..." 
              rows="8"></textarea>
    <div class="character-count">
        <span id="charCount">0</span> حرف
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('essayAnswer');
    const charCount = document.getElementById('charCount');
    
    if (textarea && charCount) {
        textarea.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });
        
        // Initialize count
        charCount.textContent = textarea.value.length;
    }
});
</script>