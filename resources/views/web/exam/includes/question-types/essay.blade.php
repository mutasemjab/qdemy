<div class="essay-answer">
    <label for="essayAnswer" class="answer-label">{{ __('front.write_your_answer') }}:</label>
    <textarea id="essayAnswer"
              name="answer"
              class="essay-textarea"
              placeholder="{{ __('front.write_your_answer') }}..."
              rows="8">@if(isset($currentAnswer) && $currentAnswer->essay_answer){{ $currentAnswer->essay_answer }}@endif</textarea>
    <div class="character-count">
        <span id="charCount">@if(isset($currentAnswer) && $currentAnswer->essay_answer){{ strlen($currentAnswer->essay_answer) }}@else 0 @endif</span>
        {{ __('front.characters') ?? 'حرف' }}
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