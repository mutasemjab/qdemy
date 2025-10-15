<div class="multiple-choice-options">
    @if($currentQuestion->options)
        @foreach($currentQuestion->options as $index => $option)
            <label class="option-label">
                <input type="radio" name="answer" value="{{ $option }}" class="option-radio">
                <div class="option-content">
                    <div class="option-indicator">{{ chr(65 + $index) }}</div>
                    <div class="option-text">{{ $option }}</div>
                </div>
            </label>
        @endforeach
    @endif
</div>