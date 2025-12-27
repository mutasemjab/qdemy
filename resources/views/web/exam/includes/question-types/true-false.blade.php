<div class="true-false-options">
    <label class="option-label">
        <input type="radio"
               name="answer"
               value="true"
               class="option-radio"
               @if(isset($currentAnswer) && !empty($currentAnswer->selected_options) && $currentAnswer->selected_options[0]) checked @endif>
        <div class="option-content">
            <div class="option-indicator true">✓</div>
            <div class="option-text">{{ __('front.true') }}</div>
        </div>
    </label>

    <label class="option-label">
        <input type="radio"
               name="answer"
               value="false"
               class="option-radio"
               @if(isset($currentAnswer) && !empty($currentAnswer->selected_options) && !$currentAnswer->selected_options[0]) checked @endif>
        <div class="option-content">
            <div class="option-indicator false">✗</div>
            <div class="option-text">{{ __('front.false') }}</div>
        </div>
    </label>
</div>