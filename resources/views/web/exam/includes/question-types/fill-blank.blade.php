<div class="fill-blank-answer">
    <label for="fillBlankAnswer" class="answer-label">{{ __('front.write_your_answer') }}:</label>
    <input type="text"
           id="fillBlankAnswer"
           name="answer"
           class="fill-blank-input"
           placeholder="{{ __('front.write_your_answer') }}..."
           autocomplete="off"
           @if(isset($currentAnswer))value="{{ $currentAnswer->essay_answer }}"@endif>
    <div class="fill-blank-hint">
        <i class="fas fa-lightbulb"></i>
        {{ __('front.please_write_answer') }}
    </div>
</div>